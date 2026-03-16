# Matt P Music Podcast

The website for the Matt P Music Podcast — a deep, dark, underground blend of house, techno, trance, and progressive. Built with Laravel, Filament, and Vite.

## Requirements

- Docker (via [Laravel Sail](https://laravel.com/docs/sail))

## Setup

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail artisan make:filament-user
./vendor/bin/sail npm run dev
```

The site will be available at `http://localhost` and the admin panel at `http://localhost/admin`.

## Seeding

The seeder expects `storage/app/episodes.json` (not tracked in git). Place the exported episode data there, then run:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

This imports all episodes from the previous Tumblr site, and auto-generates `config/redirects.php` for old Tumblr URL redirects.

## Stack

- **Laravel** — application framework
- **Filament** — admin panel / CMS
- **MySQL** — database (via Sail)
- **Vite + Sass** — frontend build

## License

MIT
