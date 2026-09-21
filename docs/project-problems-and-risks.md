# RankPro — whole-project problems and risk register

Prepared: 18 Sep 2026  
Scope: current app only (`app/`, `routes/web.php`, `routes/rankers.php`, `helper/`, `resources/views`, `config/`, `.env` shape, SQL dumps). Not a patch list.

Related: login-only detail is in `docs/login-and-verification-risks.md`. This document covers **auth plus payments, exams, admin RBAC, uploads, secrets, data, and maintainability**.

Severity: **P0** exploit / money / exam integrity / account takeover now · **P1** high likelihood or high impact · **P2** broken or misleading · **P3** hygiene.

---

## 1. What the product is

RankPro is a Laravel 9 / PHP 8 NEET coaching monolith: public marketing site, student dashboard, online exams, test-series and ranker checkout (Razorpay), admin CMS, counsellor portal, ranker portal.

| Area | How it is built | Immediate issue |
|------|-----------------|-----------------|
| Student auth | Laravel `Auth` + bcrypt | Weak OTP/reset; `orWhere` status bypass |
| Admin / counsellor / ranker | Custom session keys + **MD5** | Fast to crack; four systems to patch |
| Paid products | Client-side Razorpay Checkout | **No server order, no signature check** |
| Exams | JS exam runner + CSRF-exempt POSTs | Correct answers sent to the browser |
| Admin roles | `isUserPermitted()` in Blade only | Any logged-in admin can hit any URL |
| Membership | Middleware named `verifyMembership` | Only `Auth::check()` |

There are **no automated tests** beyond Laravel’s example stubs. Laravel 9 is end-of-life (Feb 2024).

---

## 2. P0 — fix first

| ID | Problem | Where | Risk | Improve |
|----|---------|-------|------|---------|
| P0-1 | Student reset POST trusts hidden numeric `user_id` | `PasswordController@resetPassword`, `reset-password.blade.php` | Set any student’s password | One-time signed token; never trust client id |
| P0-2 | Reset verify matches 4-digit code globally, not per email | `PasswordController@verifyCode` | 10 000 codes; first unexpired match wins | Lookup email + code; lock after N failures |
| P0-3 | Admin reset POST looks up `Administrator.id`, not `hash_token` | `Admin\AuthController@updateresetpassword` | Numeric id can set any admin password | Require unused token; confirm password server-side; invalidate token |
| P0-4 | Ranker dashboard/profile have **no** `rankerAuth`; rankers group has **no** session/CSRF | `routes/rankers.php`, `Kernel` `rankers` group | Anyone can use `/rankers/dashboard` | Web middleware + `rankerAuth` on all ranker pages |
| P0-5 | Signup OTP is 4 digits, plaintext, no expiry/lockout | `AdmissionController@store` / `@verifyOtp` | Brute-force a sequential `user_id` | 6+ digits, hash, TTL, attempt cap |
| P0-6 | Staff passwords stored and compared as MD5 | Admin / counsellor / ranker login and create | Dump + GPU = staff takeover | bcrypt/argon2; force reset |
| P0-7 | Student login `orWhere` drops `status=1` | `AdmissionController@login` | Inactive users match on father mobile | Group: `status=1` AND (email OR father OR own mobile) |
| P0-8 | Checkout marks payment SUCCESS from the browser; Razorpay signature is commented out; amount is `$request->final_price` | `HomeController@testSeriesSaveCheckout`, `@rankerSaveCheckout`; checkout blades | Pay ₹1 (or nothing) and get a SUCCESS row | Create Razorpay **order** server-side; verify signature; recompute amount from DB; never take price from the client |
| P0-9 | Live exam dumps `questions.*` into JS, including the **correct answer** | `ExamsController@start_online_exam` + `start_online_exam.blade.php` `json_encode($question_list)` | Student opens DevTools and sees answers | Send only stem/options; score on the server |
| P0-10 | Exam start/result/save/end/time do not check `exam_users.user_id` == current user | `ExamsController`, `Exam_givenController@exam_result_detail` | Open another student’s attempt; submit/score for them | Always `where('user_id', Auth::id())`; 403 otherwise |
| P0-11 | Exam POSTs are CSRF-exempt | `VerifyCsrfToken::$except` (`save-exam`, `end-exam`, `update-user-exam-question`, `update-exam-time`, `update-time`) | Cross-site submit answers or end a test | Remove except; send CSRF token from the exam JS |
| P0-12 | `start-exam` is GET | `routes/web.php` | Logged-in user can be started into an exam via a link | POST + confirm |

---

## 3. P1 — high

### 3.1 Auth (also in the login document)

| ID | Problem | Improve |
|----|---------|---------|
| P1-1 | No `throttle` on login, OTP, forgot-password | `throttle:5,1` per IP + identifier |
| P1-2 | 2Factor API key hardcoded in `AdmissionController` | Env + `config/services.php`; rotate key (when you decide) |
| P1-3 | SMS over HTTP GET with OTP in the URL | HTTPS POST; never put OTP in query string |
| P1-4 | Parent login uses student `password`, not `parant_password` | Separate parent secret; limit parent capabilities |
| P1-5 | Login ignores student `mobile_number` | Include it in the lookup |
| P1-6 | `resendOtp` updates `User`; OTP lives on `User_apply` | Resend against pending apply |
| P1-7 | Admin/counsellor forgot-password never emails | Send signed reset link; same message if email missing |
| P1-8 | Counsellor forgot uses missing `Admin` model; reset methods missing | Use `Counsellor`; implement or drop routes |
| P1-9 | Session logout sets key to `null` instead of invalidate | `session()->invalidate(); regenerateToken();` |
| P1-10 | `verifyMembership` does not check payment/package | Gate student area on paid entitlement |
| P1-11 | Password min length 4 | Min 8; reject common passwords |
| P1-12 | OTP stored plaintext; no unique index on pending applies | Hash OTP; unique email/phone on `user_applies` |
| P1-13 | GET `/logout` | POST logout |
| P1-14 | Admin-created students get **MD5** passwords; student login uses **bcrypt** | Same hasher everywhere. Admin `User::where('email')` also uses a column that does not exist (`email_id`) so duplicate check never fires |

### 3.2 Payments and coupons

| ID | Problem | Where | Improve |
|----|---------|-------|---------|
| P1-15 | No Razorpay `Api` / `orders->create` anywhere in PHP | Whole app | Server-created order; Checkout only gets `order_id` |
| P1-16 | Coupon apply is public (`POST /testseries-coupon`); save checkout trusts client `coupon_code` / `coupon_discount` | `HomeController@checkCoupon` / save checkout | Re-validate coupon server-side; cap uses; bind to product |
| P1-17 | `new_lightCheckout` inserts a payment row with **no** Razorpay step | `HomeController@new_lightCheckout` | Either lead-only (rename) or real payment |
| P1-18 | Checkout JS uses `env('RAZORPAY_KEY')` in Blade; secret is in `.env` | Checkout views | Key via `config()`; never `env()` after `config:cache` |
| P1-19 | Test Razorpay keys in local `.env` (`rzp_test_…`) | `.env` | Confirm production uses live keys; rotate if this tree was copied |

### 3.3 Admin RBAC and destructive GET

| ID | Problem | Where | Improve |
|----|---------|-------|---------|
| P1-20 | `isUserPermitted()` is **never called from controllers** — only Blade menus | `helper/helper.php` vs `app/Http/Controllers/Admin` | Middleware per permission; 403 on the action |
| P1-21 | Almost every admin delete/status change is **GET** (`/admin/student/delete?id=`) | `routes/web.php` | POST/DELETE + CSRF. A logged-in admin visiting a crafted URL deletes a student, question, coupon, exam |
| P1-22 | Counsellor can delete students the same way | Counsellor routes | Same as P1-21; also scope by counsellor_id |
| P1-23 | Super-admin check is `type == "SA"`; teachers (`T`) denied in helper but can still hit URLs | AdminAuth only checks session | Enforce type + permission in middleware |

### 3.4 Uploads and XSS

| ID | Problem | Where | Improve |
|----|---------|-------|---------|
| P1-24 | ZIP upload extracted to `public/uploads/question` with no path sanitisation | `QuestionController@upload_save`, counsellor zip import | Validate mime; extract to private disk; reject `../` entries (zip-slip) |
| P1-25 | Most admin/counsellor uploads keep original extension, no `mimes` rule | Student/ranker/exam controllers | `mimes` + store random name; serve from non-executable path |
| P1-26 | Uploads land in **web-root** `public/uploads/` | Whole app | Private disk + authorised download; PHP must not execute there |
| P1-27 | Question CSVs sit in `public/uploads/` (e.g. `question-04-07-2025.csv`) | Public folder | Delete; never leave question banks web-accessible |
| P1-28 | CMS/ranker/test-series HTML rendered with `{!! !!}` | Many site blades | Restrict to trusted admins; sanitise HTML; or `{{ }}` |
| P1-29 | Exam runner injects option HTML/images via template strings | `start_online_exam.blade.php` | Escape; do not treat option text as HTML unless sanitised |

### 3.5 Secrets, debug, hosting

| ID | Problem | Improve |
|----|---------|---------|
| P1-30 | `.env` holds live mail password, Razorpay secret, APP_KEY; `APP_DEBUG=true`, `APP_ENV=local` in this tree | Production: `APP_DEBUG=false`, `APP_ENV=production`, `SESSION_SECURE_COOKIE=true` |
| P1-31 | SQL dumps (`rankproco_site.sql`, `mysql/`) contain student PII and hashes | Keep dumps **out of git** (already gitignored); treat existing copies as a leak until rotated |
| P1-32 | Nested `demo.rankpro.co.in` is a second Laravel tree on the **same DB name** | Isolate demo DB; or remove; never share production credentials |
| P1-33 | Session cookie `secure` unset; `encrypt` false | HTTPS-only + encrypt sessions in production |
| P1-34 | Laravel 9 is EOL; PHP constraint `^8.0.2` | Plan Laravel 10/11 upgrade; PHP 8.2+ |

### 3.6 Data / privacy (NEET students are often minors)

| ID | Problem | Improve |
|----|---------|---------|
| P1-35 | `users` stores parent names, phones, school, signatures, certificates | Access control; retention policy; encrypt at rest if required |
| P1-36 | `User::$fillable` includes `password`, `otp`, `status`, `package`, `is_deleted` | Guarded / `$hidden`; never mass-assign status from request |
| P1-37 | Admin student list is `User::get()` with no pagination | Paginate; audit who exports |
| P1-38 | Contact form has no captcha / throttle | Throttle + honeypot |

---

## 4. P2 — broken, incomplete, or misleading

| ID | Problem | Where | Improve |
|----|---------|-------|---------|
| P2-1 | Ranker login starts with `dd(session()->get('rankersAuth'))` | `RankerAuthController@index` | Remove `dd`; render login |
| P2-2 | `RankerAppointmentController` missing | `routes/rankers.php` | Add controller or drop route |
| P2-3 | Signup copy says “check your email”; only SMS is sent | `AdmissionController@store` | Send mail or change copy |
| P2-4 | `registration_new` is 6-box OTP branded “Shikkha”; live form is 4-box RankPro | Views | One registration view |
| P2-5 | Counsellor failed login uses **success** toastr | `CounsellorLoginController` | Error toast |
| P2-6 | Admin reset JS always `return true`; missing `first_name` | `admin/reset_password.blade.php` | Server-side `confirmed` |
| P2-7 | `downloadPdf` is `dd($input)` | `Exam_givenController` | Implement or hide the button |
| P2-8 | `/blank`, `/blank_two`… leftover pages | `web.php` | Remove from production |
| P2-9 | Duplicate route files: `web-03-10-2025.php`, `web-06-10-2025.php`, dated controllers/views | `routes/`, `app/Http/Controllers/Admin/*-20*.php` | Delete backups from the live tree |
| P2-10 | Nested copies `app-25-08-2026`, `resources-25-08-2026`, `demo.rankpro.co.in` | Repo | Archive outside the app |
| P2-11 | `getLanguageId()` hardcodes `1` in most student controllers | Students/Exams/Home | Use `getDefaultLanguage()` / session locale |
| P2-12 | Leaderboard join uses `exams.id = exam_users.user_id` in one helper | `StudentsController@getLeaderBoardData` | Join `exam_users.exam_id` |
| P2-13 | Online exam has no paid-entitlement check | `start_exam` | Require payment/package for that exam |
| P2-14 | Exam timer `update_exam_time` adds +5 with no cap and no ownership | `ExamsController` | Server clock; freeze after duration |
| P2-15 | `save_exam` and `end_exam` are near-duplicates | `ExamsController` | One submit path; idempotent |
| P2-16 | Coupon “applied successfully” JSON on **payment** save | `testSeriesSaveCheckout` return message | Correct API messages |
| P2-17 | Checkout success handler still `alert()`s payment ids | Checkout blades | Remove debug alerts |
| P2-18 | `terms_and_condition` view is empty of CMS | `StudentsController` | Load CMS or legal copy |
| P2-19 | Sanctum `/api/user` plus `/api/test-download` returning `1` | `routes/api.php` | Remove test route |
| P2-20 | Duplicate `counsellor.dologin` route | `web.php` | Register once |
| P2-21 | Email enumeration on forgot-password | Student + admin | Generic response |
| P2-22 | `console.log` on signup AJAX and exam JS | Registration / exam | Strip in production |
| P2-23 | Admin login URLs `/admin` and `/webadmin` are guessable | Routes | Extra protection is not a substitute for hashing |

---

## 5. P3 — hygiene and engineering risk

| ID | Problem | Improve |
|----|---------|---------|
| P3-1 | Typos: `parant_login_type`, `admmin_is_super`, `admmin_subject_id` | Rename with a migration of session keys |
| P3-2 | Copy-paste CRUD: District/Footer/Exam controllers create **passwords** for geo records | Delete dead fields/methods |
| P3-3 | 80+ controllers, many 1:1 with tables; dated `-26-06-2025` twins | One controller per resource; git history instead of copies |
| P3-4 | Only `tests/Feature/ExampleTest.php` | Auth, payment, exam scoring tests |
| P3-5 | `QUEUE_CONNECTION=sync` | Queue mail/SMS |
| P3-6 | File sessions | Redis/database in production |
| P3-7 | `Log::info` on failed student login | Don’t log unless needed; never log passwords |
| P3-8 | CKEditor 5 + old jQuery 3.2.1 in `public/exam` | Patch or replace |
| P3-9 | `ASSET_URL` / double-slash CSS already bitten local | Keep `asset()` without extra `/`; document Apache vs `artisan serve` |
| P3-10 | No remember-me / TOTP for staff | TOTP for admin |
| P3-11 | Parent vs student only a session flag | Enforce in queries (parent must not sit exams if that is the rule) |
| P3-12 | `users.password` mass-assignable | Remove from `$fillable` |

---

## 6. Cross-cutting risks

1. **Four auth systems.** Student (`Auth` + bcrypt), admin, counsellor, ranker (session + MD5). A fix in one portal does not apply to the others.
2. **Trusting the client.** Price, coupon, payment SUCCESS, exam answers, exam_user id, reset user id, OTP user_id.
3. **GET for mutations.** Deletes, status toggles, start-exam, logout.
4. **PII + dumps.** Students are likely 16–18. Dumps and public CSVs are a DPDP/IT Act exposure, not just a git problem.
5. **EOL stack.** Laravel 9 will not get security fixes. Plan an upgrade after the P0s, not instead of them.
6. **Demo shares production DB name.** A weak demo password is a production password.

---

## 7. Suggested fix order

1. **Money:** P0-8, P1-15, P1-16, P1-17 (Razorpay order + signature + server amount).  
2. **Accounts:** P0-1, P0-2, P0-3, P0-6, P1-14.  
3. **Exam integrity:** P0-9, P0-10, P0-11, P0-12, P2-14.  
4. **Ranker portal:** P0-4, P2-1, P2-2.  
5. **Login correctness:** P0-7, P1-4, P1-5, P1-1, P0-5.  
6. **Admin:** P1-20, P1-21 (RBAC middleware + POST deletes).  
7. **Uploads / XSS:** P1-24–P1-29.  
8. **Ops:** P1-30–P1-34 (`APP_DEBUG`, session secure, dump isolation).  
9. **2Factor key to env** — only after you decide.  
10. **Laravel upgrade** after the above, with tests from P3-4.

---

## 8. What is acceptable today

- Student self-signup passwords use `Hash::make` / `Hash::check`.  
- Student and admin HTML forms include `@csrf` (exam JS and rankers group do not).  
- Many public detail URLs encrypt ids (`new_light/{encrypted}`).  
- Student reset codes have a 10-minute expiry (verify uses it; the final POST is the hole).  
- `.gitignore` now excludes `.env`, `*.sql`, `demo.rankpro.co.in`, uploads, logs, zips.  
- Gallery/banner-style admin forms often validate `image|mimes|max:2048` (student/exam uploads often do not).

---

## 9. Inventory (for tracing)

| Surface | Entry | Notes |
|---------|-------|-------|
| Public site | `/`, test series, ranker, new light, contact | Contact unthrottled |
| Student | `/login`, `/signup`, OTP, forgot/verify/reset | See login doc |
| Student app | `/dashboard`, exams, analytics, profile | `verifyMembership` = logged in |
| Payments | Checkout blades → `testSeriesSaveCheckout` / `rankerSaveCheckout` | Trusts client |
| Admin | `/admin`, `/webadmin` | MD5; RBAC is menu-only |
| Counsellor | `/counsellor` | Broken forgot/reset |
| Ranker | `/rankers` | `dd()`; dashboard public |
| API | `/api/user`, `/api/test-download` | Test route |

Controllers of note: `AdmissionController`, `PasswordController`, `HomeController` (checkout), `ExamsController`, `Exam_givenController`, `StudentsController`, `Admin\AuthController`, `Admin\StudentController`, `Admin\QuestionController`, `CounsellorLoginController`, `RankerAuthController`.
