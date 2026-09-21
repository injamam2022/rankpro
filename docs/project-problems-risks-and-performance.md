# RankPro — problems, risks, and performance register

Prepared: 20 Sep 2026  
Scope: whole current tree (`app/`, `routes/`, `helper/`, `resources/views`, `public/exam`, `config/`, `.env` shape, live `rankproco_site` schema). Not a patch list.

Related: `docs/login-and-verification-risks.md` (login only), `docs/project-problems-and-risks.md` (18 Sep security). **This document supersedes those for whole-project work** and adds a full performance section plus proctoring.

Severity: **P0** exploit / money / exam integrity / account takeover · **P1** high · **P2** broken · **P3** hygiene.  
Performance: **PF** items are slowness, scale, or resource waste. A PF can also be P0/P1 if it is also a security hole.

---

## 1. Product snapshot

Laravel 9 (EOL) NEET monolith: marketing site, student dashboard, live exams + webcam proctoring, Razorpay checkout, admin/counsellor/ranker portals.

| Area | Built as | Immediate issue |
|------|----------|-----------------|
| Student auth | `Auth` + bcrypt | Weak OTP/reset; `orWhere` status bypass |
| Staff auth | Session keys + **MD5** | Four systems; fast to crack |
| Payments | Browser Razorpay Checkout | No server order, no signature |
| Exams | JS runner + CSRF-exempt POSTs | Correct answers in the page JSON |
| Proctoring (new) | Webcam + events + public JPEGs | CSRF-exempt; snapshots in web root |
| Admin roles | `isUserPermitted()` in Blade only | Any admin URL works if session exists |
| Membership | `verifyMembership` | Only `Auth::check()` |
| Data access | Almost no secondary indexes | Analytics and exams full-scan |

Live DB (20 Sep 2026, InnoDB estimates): `question_details` ~15.6k / 37 MB, `questions` ~16k, `exam_results` ~34k, `offline_exam_questions` ~7k. **`exam_results`, `exam_users`, `questions`, `question_details`, `users`, `payments` have PRIMARY KEY only.**

---

## 2. P0 — security / integrity (fix first)

| ID | Problem | Where | Improve |
|----|---------|-------|---------|
| P0-1 | Student reset POST trusts hidden numeric `user_id` | `PasswordController`, reset blade | Signed one-time token |
| P0-2 | 4-digit reset code matched globally, not per email | `PasswordController@verifyCode` | Email + code + lockout |
| P0-3 | Admin reset POST looks up numeric `id`, not `hash_token` | `Admin\AuthController` | Require token; invalidate after use |
| P0-4 | Ranker dashboard has no `rankerAuth`; rankers group has no session/CSRF | `routes/rankers.php`, `Kernel` | Web stack + middleware |
| P0-5 | Signup OTP 4 digits, plaintext, no expiry | `AdmissionController` | 6+ digits, hash, TTL, cap |
| P0-6 | Staff passwords MD5 | Admin / counsellor / ranker | bcrypt; force reset |
| P0-7 | Login `orWhere` drops `status=1` | `AdmissionController@login` | Group status with email/phone |
| P0-8 | Payment SUCCESS from browser; signature commented out; amount = `final_price` | `HomeController` checkout | Server Razorpay order + verify + DB price |
| P0-9 | Live exam `json_encode`s `questions.*` including **correct answer** | `ExamsController@start_online_exam` | Stem/options only; score server-side |
| P0-10 | Start/result/save/end/time do not require `exam_users.user_id` = current user | `ExamsController`, `exam_result_detail` | Always `where('user_id', Auth::id())` |
| P0-11 | Exam **and proctoring** POSTs are CSRF-exempt | `VerifyCsrfToken::$except` now includes `log-proctoring-event`, `save-proctoring-snapshot` | Send CSRF from exam JS |
| P0-12 | `start-exam` is GET | `web.php` | POST + confirm |

Proctoring event/snapshot APIs *do* check `exam_user.user_id`. The live exam page and timer/save still do not (P0-10).

---

## 3. P1 — high (security / product)

### Auth

| ID | Problem | Improve |
|----|---------|---------|
| P1-1 | No throttle on login, OTP, forgot-password | Per IP + identifier |
| P1-2 | 2Factor key hardcoded | Env when you decide; rotate |
| P1-3 | SMS GET with OTP in URL | HTTPS POST |
| P1-4 | Parent login uses student password | Separate `parant_password` |
| P1-5 | Login ignores student `mobile_number` | Include it |
| P1-6 | `resendOtp` hits `User` not `User_apply` | Resend pending apply |
| P1-7 | Admin/counsellor forgot never emails | Signed link |
| P1-8 | Counsellor forgot uses missing `Admin` model | Use `Counsellor` |
| P1-9 | Logout does not invalidate session | `invalidate()` + regenerate CSRF |
| P1-10 | `verifyMembership` ≠ paid membership | Check payments/package |
| P1-11 | Password min length 4 | Min 8 |
| P1-12 | OTP plaintext; no unique on `user_applies` | Hash; unique indexes |
| P1-13 | GET `/logout` | POST |
| P1-14 | Admin-created students MD5; login is bcrypt; duplicate check uses `users.email` (column is `email_id`) | One hasher; correct column |

### Payments

| ID | Problem | Improve |
|----|---------|---------|
| P1-15 | No `Razorpay\Api` / `orders->create` in PHP | Server order_id only in Checkout |
| P1-16 | Coupon endpoint public; save trusts client discount | Re-validate server-side |
| P1-17 | `new_lightCheckout` inserts payment with no Razorpay | Lead vs pay |
| P1-18 | Blade `env('RAZORPAY_KEY')` | `config()` |
| P1-19 | Test Razorpay keys in this `.env` | Confirm production keys |

### Admin / uploads / data

| ID | Problem | Improve |
|----|---------|---------|
| P1-20 | RBAC only hides menus | Permission middleware |
| P1-21 | Admin delete/status via GET | POST + CSRF |
| P1-22 | Counsellor student delete GET | Same + scope |
| P1-23 | Teacher type blocked in helper only | Enforce in middleware |
| P1-24 | ZIP extract to `public/uploads` (zip-slip) | Private disk; sanitise paths |
| P1-25 | Uploads keep original extension | `mimes` + random name |
| P1-26 | Files in web root | Private + authorised download |
| P1-27 | Question CSVs in `public/uploads/` | Delete |
| P1-28 | `{!! !!}` CMS HTML | Sanitize |
| P1-29 | Exam JS injects option HTML | Escape |
| P1-30 | `APP_DEBUG=true`; Laravel 9 EOL | Production flags; upgrade plan |
| P1-31 | SQL dumps with PII | Keep out of git (already ignored) |
| P1-32 | `demo.rankpro.co.in` same DB name | Isolate |
| P1-33 | Session `secure` unset | HTTPS-only cookie |
| P1-34 | `User::$fillable` includes password, otp, status, package | Guard |
| P1-35 | Proctoring JPEGs under `public/uploads/proctoring/{exam_user_id}/` | Private disk; signed URLs; retention job |
| P1-36 | Proctoring snapshots CSRF-exempt (also P0-11) | CSRF token |

---

## 4. Performance — whole project

These are the items that will hurt first as students, questions, and `exam_results` grow. Today `exam_results` is already ~34k rows with **no index except `id`**.

### 4.1 Database (highest leverage)

| ID | Problem | Evidence | Improve |
|----|---------|----------|---------|
| PF-1 | `exam_results` has only PRIMARY KEY | Every dashboard/analytics/exam query filters `user_id`, `exam_user_id`, `exam_id` | Index `(user_id, exam_user_id, exam_question_id)`, `(exam_id, user_id)` |
| PF-2 | `exam_users` PRIMARY only | Start exam, timer, leaderboard, rank | Unique `(exam_id, user_id, id)` or `(user_id, exam_id)`; index `user_id` |
| PF-3 | `questions` / `question_details` PRIMARY only | `question_details` is 37 MB; lookup by `question_id` + `language_id` | Unique `(question_id, language_id)`; index `subject_id` |
| PF-4 | `question_paper_questions` PRIMARY only | Paper load joins `question_paper_id` | Index `question_paper_id`, `question_id` |
| PF-5 | `users` PRIMARY only | Login by `email_id` / father mobile | Unique `email_id`; index `mobile_number`, `father_mobile_number`, `status` |
| PF-6 | `payments` PRIMARY only | Membership checks (once you add them) | Index `(user_id, type, payment_status)` |
| PF-7 | `COALESCE(questions.subject_id, offline_exam_questions.subject_id)` in WHERE | Dashboard, analytics, leaderboard | Store `subject_id` on `exam_results` at insert time; query that column |
| PF-8 | Joining **both** `question_paper_questions` and `offline_exam_questions` then `DISTINCT` | `StudentsController@dashboard`, `exam_given` | Two queries or UNION; never cartesian then DISTINCT |
| PF-9 | Rank is a nested full scan | `StudentsController` `DB::select` COUNT over grouped `exam_users` | Materialise rank nightly or indexed sum table |
| PF-10 | `GROUP BY exam_users.user_id` while selecting `exam_users.*` | Leaderboard | Select only aggregates; ONLY_FULL_GROUP_BY safe |
| PF-11 | Dead table `offline_exam_question_results_old` (~23k) | Schema | Archive/drop |

### 4.2 N+1 and hot PHP paths

| ID | Problem | Where | Cost today | Improve |
|----|---------|-------|------------|---------|
| PF-12 | One heavy aggregate **per subject** on dashboard | `StudentsController@dashboard` foreach `$subject_list` | Subjects × scan of `exam_results` | One grouped query |
| PF-13 | Leaderboard: **all users** `->get()` then per user × per subject `count()` | `Exam_givenController@leader_board` | Users × subjects queries | One grouped SQL; paginate top N |
| PF-14 | Start exam: per question `Exam_result::first` **and** `Question_detail::first` | `ExamsController@start_online_exam` | 2N queries (N = paper size, often 180+) | `whereIn` + keyBy |
| PF-15 | Save/end exam: per question `first` + `update` | `save_exam` / `end_exam` | 2N writes | Load all results once; bulk update |
| PF-16 | Result detail: per question `Exam_result::first` | `exam_result_detail` | N queries | `whereIn exam_question_id` |
| PF-17 | PDF download: per question `Question_detail::first` | `Offline_examController@download_question` | N queries + DomPDF | Join details; queue PDF |
| PF-18 | Analytics controllers copy the same join+loop pattern | `Exam_homeController`, `Exam_givenController`, `Common_confusionController`, `ResultController` | Same cost on every analytics URL | Shared query service + cache |

### 4.3 Live exam runtime

| ID | Problem | Where | Improve |
|----|---------|-------|---------|
| PF-19 | Whole paper JSON in the HTML (also P0-9) | `start_online_exam.blade.php` | Paginate/fetch one question; smaller payload |
| PF-20 | `update_exam_time` every **5 seconds**, no ownership, `total_time+5` | AJAX in exam blade | Server clock; one row update; CSRF |
| PF-21 | `console.log(t)` every **1 second** | `updateExamClock` | Remove |
| PF-22 | Face sample every **1.2s** on canvas | `proctoring.js` `faceTimer` | 3–5s; Web Worker |
| PF-23 | JPEG snapshot every **45s** to public disk (up to 400 KB) | `save_proctoring_snapshot` | Private disk; cap count; delete after exam |
| PF-24 | Lock watch every **700ms** | `proctoring.js` | 2s is enough |

A 60-minute proctored paper ≈ 12 timer POSTs/min + face work 50×/min + ~80 snapshots. That is the first thing that will melt PHP-FPM and disk when many students sit together.

### 4.4 Lists, cache, assets, ops

| ID | Problem | Improve |
|----|---------|---------|
| PF-25 | Almost every admin list is `Model::get()` | Paginate 25 (only `QuestionController` does today) |
| PF-26 | `User::get()` on admin students | Paginate + search |
| PF-27 | Homepage ~10 queries, no cache | Cache CMS blocks 5–15 min |
| PF-28 | `CACHE_DRIVER=file`, `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync` | Redis + queue mail/SMS/PDF |
| PF-29 | `APP_DEBUG=true`, `LOG_LEVEL=debug` | Off in production; rotate logs |
| PF-30 | Duplicate vendor JS (jQuery 3.2.1 × several, bootstrap, bxslider dated copies) | One minified bundle; long cache |
| PF-31 | Images via Intervention on upload request | Queue resize |
| PF-32 | `getLanguageId()` hardcoded `1` everywhere | Session locale; still avoid repeat Language queries |
| PF-33 | No query log / slow-query monitoring | Enable MySQL slow log; Laravel telescope only locally |
| PF-34 | File sessions under concurrent exam AJAX | Redis sessions so timer POSTs do not lock files |

---

## 5. P2 — broken / misleading

| ID | Problem | Improve |
|----|---------|---------|
| P2-1 | Ranker login `dd()` | Remove; render login |
| P2-2 | Missing `RankerAppointmentController` | Add or drop route |
| P2-3 | Signup says email OTP; SMS only | Mail or copy |
| P2-4 | `registration_new` is Shikkha 6-box OTP | One view |
| P2-5 | Counsellor fail toast is success style | Error |
| P2-6 | Admin reset JS always true | Server `confirmed` |
| P2-7 | `downloadPdf` is `dd()` | Implement or hide |
| P2-8 | `/blank*` leftover | Remove |
| P2-9 | Dated route/controller copies | Delete from live tree |
| P2-10 | Nested `demo` / `app-25-08-2026` | Archive outside app |
| P2-11 | Language hardcoded | Use default language helper |
| P2-12 | `getLeaderBoardData` joins `exams.id = exam_users.user_id` | Join `exam_id` |
| P2-13 | Online exam no paywall | Entitlement |
| P2-14 | Timer +5 unbounded | Cap at duration |
| P2-15 | `save_exam` ≈ `end_exam` | One path |
| P2-16 | Payment save returns “Coupon applied” | Fix message |
| P2-17 | Checkout `alert()` payment ids | Remove |
| P2-18 | Empty terms page | CMS |
| P2-19 | `/api/test-download` | Remove |
| P2-20 | Duplicate counsellor.dologin | Once |
| P2-21 | Email enumeration | Generic message |
| P2-22 | `console.log` in signup/exam | Strip |
| P2-23 | Guessable `/admin` | Not a substitute for hashing |

---

## 6. P3 — hygiene

| ID | Improve |
|----|---------|
| P3-1 | Rename `parant_login_type`, `admmin_*` |
| P3-2 | District/Footer controllers still copy password fields |
| P3-3 | Dated `-20*.php` twins; 80+ CRUD controllers |
| P3-4 | Real tests (auth, payment signature, exam scoring, indexes) |
| P3-5 | Queue mail/SMS |
| P3-6 | Don’t log failed login unless needed |
| P3-7 | Patch old jQuery / CKEditor |
| P3-8 | Keep `asset()` slash-safe |
| P3-9 | Staff TOTP |
| P3-10 | Parent cannot sit exams if that is the rule |

---

## 7. Cross-cutting

1. **Trust the client** — price, coupon, payment SUCCESS, exam answers, `exam_user_id`, reset `user_id`, OTP `user_id`.  
2. **Four auth systems** — one fix does not cover admin/counsellor/ranker.  
3. **GET mutations** — delete, status, start-exam, logout.  
4. **No indexes on the tables you query most.** Security fixes will not make the dashboard fast.  
5. **EOL Laravel 9** — no framework security patches.  
6. **PII** — students often 16–18; proctoring faces are biometric-adjacent; public snapshot URLs are a DPDP issue, not only a perf issue.

---

## 8. Suggested order

1. **Money:** P0-8, P1-15–P1-17.  
2. **Accounts:** P0-1–P0-3, P0-6, P1-14.  
3. **Exam integrity:** P0-9–P0-12 (answers out of JSON, ownership, CSRF, POST start).  
4. **Indexes (PF-1–PF-6)** before more students sit exams — cheap and high impact.  
5. **Dashboard/leaderboard queries (PF-8, PF-12, PF-13)** so logged-in home does not scan `exam_results` per subject.  
6. **Live exam N+1 (PF-14, PF-15, PF-20)** and Redis sessions (PF-34).  
7. **Proctoring storage (P1-35, PF-23)** — private disk + retention.  
8. Ranker portal (P0-4). Student login/OTP. Admin POST-deletes.  
9. Cache homepage (PF-27). Paginate admin (PF-25).  
10. 2Factor env when you decide. Laravel 10/11 after tests (P3-4).

---

## 9. What is already in better shape

- Student self-signup uses bcrypt.  
- Proctoring log/snapshot endpoints check `exam_users.user_id` (other exam endpoints do not).  
- Snapshot size capped at 400 KB and JPEG prefix checked.  
- `exam_proctoring_events` has indexes on `exam_id` and `(exam_user_id, event_type)`.  
- Question admin list paginates 25.  
- Many public detail URLs use `encrypt(id)`.  
- `.gitignore` excludes `.env`, SQL dumps, demo tree, uploads, logs.

---

## 10. Trace map

| Surface | Hot files |
|---------|-----------|
| Login / OTP | `AdmissionController`, `PasswordController` |
| Payments | `HomeController`, checkout blades |
| Live exam | `ExamsController`, `start_online_exam.blade.php`, `proctoring.js` |
| Dashboard / analytics | `StudentsController`, `Exam_givenController`, `Exam_homeController` |
| Admin | `Admin\*` lists `::get()`, GET delete |
| Schema | Live MySQL; only proctoring migration applied on top of dump |
