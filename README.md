# Schedule App

A Laravel + Vue 3 content scheduling workspace. Draft posts, assign them to connected platforms, schedule them, and track everything from a dashboard with an upcoming-posts calendar.

<!-- Add screenshots after running the app: -->
<!-- ![Dashboard](docs/dashboard.png) -->
<!-- ![Post editor](docs/editor.png) -->

## Features

| Area | Description |
| --- | --- |
| Landing page | Public home page for visitors, with sign-in entry point. |
| Dashboard | Post totals by status, filterable content queue, recent posts, upcoming calendar. |
| Post editor | Title, content, optional image upload, platform multi-select, schedule time, status. |
| Platforms | Per-user publishing destinations, with per-user active/inactive toggle. |
| Activity log | Last 50 actions of the signed-in user. |
| Daily cap | Maximum of 10 posts scheduled per day per user. |
| Auth | Laravel Sanctum bearer tokens; all API routes scoped to the authenticated user. |

## Tech stack

- Laravel, PHP 8.2+, Sanctum
- Vue 3 (`<script setup>`), Vue Router, Axios
- Vite, Tailwind CSS v4
- PHPUnit (backend), Vitest + Vue Test Utils (frontend)

## Local setup

Requirements: PHP 8.2+, Composer, Node.js 20+, MySQL or SQLite.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# configure DB in .env, then:
php artisan migrate --seed
php artisan storage:link
```

Run in two terminals:

```bash
php artisan serve
npm run dev
```

Demo login (from the seeder): `demo@example.com` / `password`

## Tests

```bash
php artisan test   # backend
npm test           # frontend
npm run build      # production build
```

## API

All routes except login require `Authorization: Bearer <token>`.

| Method | Path | Purpose |
| --- | --- | --- |
| POST | `/api/login` | Get a bearer token |
| GET | `/api/user` | Current user |
| GET | `/api/user/{user}/posts` | List own posts (403 for other users) |
| POST | `/api/posts` | Create a post |
| PUT | `/api/post/{post}` | Update a scheduled post (owner only) |
| DELETE | `/api/post/{post}` | Delete a post (owner only) |
| POST | `/api/upload-image` | Upload an image (max 2 MB) |
| GET / POST | `/api/platforms` | List / create platforms |
| DELETE | `/api/platforms/{platform}` | Delete a platform (owner only) |
| PUT | `/api/platforms/{platform}/toggle` | Toggle a platform on/off for the user |
| GET | `/api/activity-logs` | Recent user activity |

## Data model

```text
User ──< Post ──< post_platforms >── Platform
User ──< user_platforms >── Platform   (is_active per user)
```

## Project structure

```text
app/Http/Controllers/Api/   # Auth, Post, Platform, User, ActivityLog controllers
resources/js/
├── App.vue                 # App shell and navigation
├── components/             # Landing, Dashboard, PostEditor, PostList, CalendarView, ...
└── router/index.js         # Routes and auth guard
tests/                      # Feature, Unit, and frontend tests
```

## Known limitations / roadmap

- Platforms are records only; real publishing is not implemented yet (Facebook is the first planned integration).
- Scheduled posts are not published automatically yet (needs queued jobs + scheduler).
- Post edit/delete UI is not built yet (API endpoints exist).
- Planned: role-based permissions, `published_at` / `failed_reason` tracking.

## License

MIT, see [LICENSE](LICENSE).