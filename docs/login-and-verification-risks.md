# RankPro — Login and verification: problems and risks

Prepared: 18 Sep 2026  
Scope: every login, signup OTP, password-reset, and related auth page in the **current** app (`app/`, `routes/web.php`, `routes/rankers.php`). Not a code change list — a risk register.

No login or verification route uses `throttle`. None of the staff portals use Laravel’s `Auth` guards.

---

## 1. Inventory of pages and flows

| # | Audience | Page | URL | Controller | View |
|---|----------|------|-----|------------|------|
| 1 | Student | Login (student or parent) | `GET/POST /login` | `AdmissionController@showLogin` / `@login` | `site/login.blade.php` |
| 2 | Student | Sign up | `GET /signup` `POST /admissionstore` | `AdmissionController@signup` / `@store` | `site/registration.blade.php` |
| 3 | Student | OTP verify (modal, not a URL) | `POST /verifyOtp` | `AdmissionController@verifyOtp` | OTP modal in registration |
| 4 | Student | Resend OTP | `POST /resend-otp` | `AdmissionController@resendOtp` | Same modal |
| 5 | Student | Forgot password | `GET/POST /forgot-password` | `PasswordController` | `site/password/forgot-password.blade.php` |
| 6 | Student | Verify reset code | `GET/POST /verify-code` | `PasswordController` | `site/password/verify-code.blade.php` |
| 7 | Student | Reset password | `GET/POST /reset-password` | `PasswordController` | `site/password/reset-password.blade.php` |
| 8 | Admin | Login | `GET /admin` `GET /webadmin` `POST /admin/dologin` | `Admin\AuthController` | `admin/login.blade.php` |
| 9 | Admin | Forgot password | `GET/POST /admin/forgot-password` | `AuthController` | `admin/forgot_password.blade.php` |
| 10 | Admin | Reset password | `GET /admin/reset-password/{id}` `POST /admin/update-reset-password` | `AuthController` | `admin/reset_password.blade.php` |
| 11 | Admin | Change password (logged in) | `/admin/profile/change-password` | `AdminController` | `admin/change_password.blade.php` |
| 12 | Counsellor | Login | `GET /counsellor` `POST /counsellor/dologin` | `CounsellorLoginController` | `counsellor/login.blade.php` |
| 13 | Counsellor | Forgot password | `GET/POST /counsellor/forgot-password` | `CounsellorLoginController` | `counsellor/forgot_password.blade.php` |
| 14 | Counsellor | Reset password (routed) | `/counsellor/reset-password/{id}` | **methods missing** | `counsellor/reset_password.blade.php` exists |
| 15 | Counsellor | Change password | `/counsellor/profile/change-password` | `CounsellorProfileController` | `counsellor/change_password.blade.php` |
| 16 | Ranker | Login | `GET /rankers/` `POST /rankers/dologin` | `RankerAuthController` | `rankers/login.blade.php` (never reached) |

Unused / leftover:

- `site/registration_new.blade.php` — 6-digit OTP UI; signup uses the 4-digit `registration.blade.php`.
- `emails/otp_verification.blade.php` — signup never sends mail.
- `RankerAuthController` copy-paste: `Admin`, `Option`, `forgotpassword` — dead from another product.

Middleware named `verifyMembership` only checks `Auth::check()`. It does not check package or payment.

---

## 2. How each flow actually works

### 2.1 Student login (`/login`)

Form: email or phone + password. CSRF present. No rate limit.

Server looks up:

```php
User::where('status',1)->where('email_id', $request->email_id)
    ->orWhere('father_mobile_number', $request->email_id)
    ->first();
```

If the identifier matches `email_id`, session `parant_login_type = S` and bcrypt check on `users.password`.  
If it matches `father_mobile_number`, `parant_login_type = P` and the **same** student password is checked (`users.password`), not `parant_password`.

Placeholder says “Email Or Phone Number” but student `mobile_number` is never queried — only email and father’s mobile.

### 2.2 Student signup + OTP

`store()` validates (password min 4), writes `user_applies` with a 4-digit OTP (`rand(1000,9999)`), bcrypt password, `status=0`. SMS via hardcoded 2Factor URL. `$emailData` is built but **email is never sent**. UI copy says “check your email”.

OTP modal collects 4 boxes. `verifyOtp` matches OTP on `user_applies` by client-supplied `user_id`, then creates `users`. Does not log the user in.

`resendOtp` loads **`User` by email**, not `User_apply`. Pending signups cannot resend. SMS uses `$request->mobile_number` which the JS resend call may not send.

### 2.3 Student forgot / verify / reset

1. Email must exist (reveals accounts).
2. 4-digit code stored on `users`, 10-minute expiry, mailed via `emails.verification_code`.
3. Verify looks up **any** user with that code still unexpired — not bound to the email from step 1.
4. Redirects to reset with `user_id=md5(id)` in the query string.
5. Reset **POST** uses a hidden field with the **numeric** `users.id`. Anyone who knows or guesses an id can POST a new password if that row’s expiry is still set (or, if expiry is null, the expiry check is skipped).

### 2.4 Admin login / forgot / reset

Password compared as **MD5** in SQL. Session key `adminAuth`. Logout sets the key to `null` rather than flushing the session.

Forgot password: writes `hash_token`, shows “Please check your email”, **never sends mail**. Invalid email is disclosed.

Reset GET loads by `hash_token`. Form posts that token as `id`. Reset POST does `Administrator::where('id', $request->id)` — a hash string will not match a numeric id. Confirm password is JS-only and `validateForm()` always `return true`. Token is not cleared after success. No current-password or min length.

### 2.5 Counsellor

Same MD5 login. Failed login fires **success** toastr (“Incorrect login credentials!”).

Forgot password queries **`Admin`** (class does not exist) instead of `Counsellor`. Reset routes are registered; controller methods `reset_password` / `updateresetpassword` **do not exist**.

### 2.6 Ranker

`index()` starts with `dd(session()->get('rankersAuth'))` — login page never renders. Dashboard/profile/appointment have **no** `rankerAuth` middleware. Rankers route group has no session/CSRF stack.

---

## 3. Problem and risk register

Severity: **P0** exploit or account takeover now · **P1** high likelihood / high impact · **P2** broken UX or incomplete · **P3** hygiene.

### P0 — Account takeover / auth bypass

| ID | Problem | Where | Risk | Improve |
|----|---------|-------|------|---------|
| P0-1 | Student reset POST accepts raw `user_id` in a hidden field | `reset-password.blade.php` + `PasswordController@resetPassword` | Attacker sets any student’s password without the email code | Bind reset to a one-time signed token in session/DB; never trust client id |
| P0-2 | Reset verify matches code globally, not per email | `PasswordController@verifyCode` | 4-digit space is 10 000 codes; first match wins | Lookup by email + code; increment fail count; expire after N tries |
| P0-3 | Admin reset POST looks up `id`, not `hash_token` | `AuthController@updateresetpassword` | If `id` is numeric, any admin password can be set with no token | Require valid unused token; confirm password server-side; invalidate token |
| P0-4 | Ranker dashboard/profile unauthenticated | `routes/rankers.php` | Anyone who knows `/rankers/dashboard` can use the portal | Apply `rankerAuth`; add session+CSRF to the rankers middleware group |
| P0-5 | Signup OTP: 4 digits, no lockout, sequential `user_id` | `verifyOtp` | Brute-force OTP for a known apply id | 6+ digits, attempt limit, expiry, store hash of OTP |
| P0-6 | Staff passwords are MD5 | Admin / counsellor / ranker login | Fast to crack; dump already used `123456` | bcrypt/argon2; force password reset on next login |
| P0-7 | `orWhere` breaks `status=1` on student login | `AdmissionController@login` | Inactive / deleted users can match on father mobile | Group: `where(status,1)->where(fn ($q) => $q->where email or father or mobile)` |

### P1 — High

| ID | Problem | Where | Risk | Improve |
|----|---------|-------|------|---------|
| P1-1 | No rate limit on any login, OTP, or forgot-password | All auth routes | Credential stuffing, OTP flood, SMS cost | `throttle:5,1` per IP + per email/phone |
| P1-2 | 2Factor API key hardcoded | `AdmissionController` | Key theft from GitHub/source | Env + `config/services.php`; rotate key |
| P1-3 | SMS over HTTP GET with OTP in the query string | Same | Logs, proxies, browser history leak OTP | HTTPS POST; do not put OTP in URL |
| P1-4 | Parent login uses student password | `login()` | Father mobile + child’s password = full student session | Separate `parant_password`; restrict parent capabilities |
| P1-5 | Login ignores student `mobile_number` | Login form vs query | Students who type their own phone cannot log in | Include `mobile_number` in the lookup |
| P1-6 | `resendOtp` updates `User`, signup OTP is on `User_apply` | `resendOtp` | Resend does not work during registration | Resend against `User_apply`; require the same phone |
| P1-7 | Admin/counsellor forgot-password never emails | Auth controllers | Users stuck; token unused | Send signed reset link; do not disclose whether email exists |
| P1-8 | Counsellor forgot uses missing `Admin` model | `CounsellorLoginController` | Forgot password 500s | Use `Counsellor`; implement real reset |
| P1-9 | Counsellor reset routes point at missing methods | `web.php` 697–698 | 500 on those URLs | Implement or remove routes |
| P1-10 | Session logout does not `session()->flush()` / `invalidate()` | Admin/counsellor/ranker logout | Session fixation leftovers | `session()->invalidate(); regenerateToken();` |
| P1-11 | `verifyMembership` ≠ membership | Middleware | Unpaid users get full student area after login | Check `payments` / package |
| P1-12 | Password min length 4 (signup); staff reset has no min | Validators | Weak passwords | Min 8, complexity; reject common passwords |
| P1-13 | OTP stored plaintext; no expiry on signup OTP | `user_applies.otp` | DB leak = instant verify | Hash OTP; 5–10 min TTL |
| P1-14 | Unique email/phone only checked in PHP, not unique on `user_applies` | `store()` | Race: two pending applies | Unique indexes; upsert pending row |

### P2 — Broken or misleading

| ID | Problem | Where | Risk | Improve |
|----|---------|-------|------|---------|
| P2-1 | Ranker login `dd()` | `RankerAuthController@index` | Demo/prod ranker portal down | Remove `dd`; render login |
| P2-2 | Signup success text says email OTP; only SMS is sent | `store()` JSON + unused `$emailData` | Users wait on inbox | Send email too, or change copy |
| P2-3 | `Mail` imported, `otp_verification` view unused | Admission / emails | Dead code | Wire Mail or delete |
| P2-4 | `registration_new` is 6-box OTP; live form is 4-box | Views vs `digits:4` | Confusion if someone switches the view | One registration view; align digit count |
| P2-5 | Counsellor failed login uses success toastr | `dologin` | Looks like success | Error toast only |
| P2-6 | Duplicate `counsellor.dologin` route | `web.php` | Noise | Register once |
| P2-7 | Admin reset JS always returns true; references missing `first_name` | `admin/reset_password.blade.php` | Confirm password not enforced | Server-side `confirmed`; fix JS |
| P2-8 | Admin reset success redirects to `admin.reset_password` without id | `updateresetpassword` | Broken redirect | Redirect to login |
| P2-9 | Ranker `RankerAppointmentController` missing | `rankers.php` | 500 on /appointment | Add controller or drop route |
| P2-10 | `set_password` / `Option` / `Admin` leftovers | Admin, counsellor, ranker auth | Fatal if those URLs are hit | Delete dead methods |
| P2-11 | Email enumeration on student + admin forgot | Responses | Confirms who has an account | Same generic message always |
| P2-12 | GET logout | `/logout` | CSRF logout | POST logout |
| P2-13 | Admin login URLs are public and guessable (`/admin`, `/webadmin`) | Routes | Easy to find staff login | Obscure path + extra protection still not a substitute for hashing |

### P3 — Hygiene / UX

| ID | Problem | Improve |
|----|---------|---------|
| P3-1 | `parant_login_type` typo | Rename `parent_login_type` |
| P3-2 | `admmin_is_super` / `admmin_subject_id` typos | Rename session keys |
| P3-3 | Login `Log::info` on every failed student login | Don’t log unless needed; never log passwords |
| P3-4 | `console.log(response)` on signup AJAX | Remove in production |
| P3-5 | No “already logged in” redirect on login pages | Redirect students/admins if session exists |
| P3-6 | No remember-me / 2FA for staff | Add TOTP for admin |
| P3-7 | `users.password` mass-assignable | Remove from `$fillable` |
| P3-8 | Parent vs student not distinguished after login except a session flag | Enforce in queries so a parent cannot sit exams if that is the product rule |

---

## 4. Cross-cutting risks

1. **Four auth systems** — student (`Auth` + bcrypt), admin/counsellor/ranker (custom session + MD5). Fixes must be repeated four times unless you unify on Laravel guards.
2. **No throttle** anywhere on `web.php` auth routes.
3. **CSRF** is present on student/admin/counsellor forms; rankers group skips the web stack.
4. **Staff MD5** plus a dump that already contained `123456` means staff accounts should be treated as compromised until rotated.
5. **Same production DB name** was also used by `demo.rankpro.co.in` — a weak demo login is a production login.

---

## 5. Suggested fix order

1. P0-1, P0-2, P0-3 — password-reset takeover.  
2. P0-6 — stop MD5; force staff password change.  
3. P0-7, P1-4, P1-5 — student/parent login correctness.  
4. P0-5, P1-1, P1-6, P1-12, P1-13 — OTP.  
5. P0-4, P2-1, P2-9 — ranker portal.  
6. P1-7, P1-8, P1-9 — counsellor/admin forgot-password actually sending mail.  
7. P1-2 — 2Factor key to env (after you decide).  
8. P1-11 — real membership check.

---

## 6. What is acceptable today

- Student passwords use `Hash::make` / `Hash::check` (bcrypt).  
- Student and admin login forms include `@csrf`.  
- Student reset codes have a 10-minute expiry (the verify step uses it; the final POST is the weak link).  
- Duplicate email/phone is blocked at signup for existing `users` (not for concurrent `user_applies`).
