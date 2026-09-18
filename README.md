# RankPro

Laravel 9 NEET coaching platform: public site, student exams, admin question bank, test series, and counsellor portal.

Do **not** commit `.env`, SQL dumps, `vendor/`, `public/uploads`, or `demo.rankpro.co.in`.

## Requirements

- PHP 8.1 or 8.2 with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `zip`, `gd`
- Composer
- MySQL / MariaDB 10.4+

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Point `.env` at a local database named `rankproco_site`. Import a **local copy** of the schema (do not commit dump files). Then:

```bash
php artisan config:clear
php artisan serve
```

Open http://127.0.0.1:8000

`php artisan serve` uses `public/` as the web root. Keep `APP_URL` and `ASSET_URL` as `http://127.0.0.1:8000` with **no** `/public` suffix.

Do **not** run `php artisan migrate` against an imported dump. Migrations only cover a small CMS slice; the real schema lives in the database dump.

## Portals

| Area | URL |
|------|-----|
| Student site | `/` |
| Admin | `/admin` or `/webadmin` |
| Counsellor | `/counsellor` |
| Ranker | `/rankers` (incomplete) |

## Optional env

- `RAZORPAY_KEY` / `RAZORPAY_SECRET` — checkout
