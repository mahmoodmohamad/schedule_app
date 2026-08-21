# Schedule App

Schedule App is a Laravel and Vue.js workspace for preparing, organizing, and publishing content across connected platforms. The frontend is designed around a focused dashboard, a structured post editor, a publishing calendar, and lightweight platform management.

> This README is provisional. API integrations and publishing providers are still evolving, so endpoint behavior and supported platform capabilities may change.

## Current product surface

| Area | Description |
| --- | --- |
| Dashboard | View post totals, filter the queue, inspect recent posts, and review upcoming scheduled content. |
| Post editor | Create a draft, scheduled, or published post with optional image upload and platform selection. |
| Platforms | Add and remove publishing destinations used by the post editor. |
| Authentication | Sign in through the Laravel API and maintain a browser session with a bearer token. |
| Frontend validation | Vue component tests run with Vitest and Vue Test Utils. |

## Technology

- Laravel and PHP
- Vue 3 with `<script setup>`
- Vue Router
- Vite
- Tailwind CSS v4 and a small custom design system
- Axios
- PHPUnit for backend tests
- Vitest, JSDOM, and Vue Test Utils for frontend tests

## Local setup

### Requirements

- PHP 8.2 or newer
- Composer
- Node.js 20 or newer
- npm
- A database supported by the Laravel configuration, such as MySQL or SQLite

### Install dependencies

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure the database and storage values in `.env`, then run migrations:

```bash
php artisan migrate
```

Start the Laravel server and Vite development server in separate terminals:

```bash
php artisan serve
npm run dev
```

The application is served by Laravel and mounts the Vue app from `resources/js/app.js`.

## Testing and build

Run the frontend test suite:

```bash
npm test
```

Run the frontend production build:

```bash
npm run build
```

Run the Laravel test suite:

```bash
php artisan test
```

## API routes used by the frontend

The Vue application currently expects the following authenticated endpoints:

- `POST /api/login`
- `GET /api/user/{user}/posts`
- `GET /api/platforms`
- `POST /api/platforms`
- `DELETE /api/platforms/{platform}`
- `POST /api/posts`
- `POST /api/upload-image`

The exact response shapes are defined by the Laravel controllers in `app/Http/Controllers/Api`.

## Project structure

```text
resources/
├── css/app.css                 # Global design system and responsive styles
└── js/
    ├── App.vue                 # Authenticated application shell and navigation
    ├── components/             # Dashboard, editor, calendar, posts, and settings views
    └── router/index.js          # Vue Router routes and auth guard

tests/
├── Feature/                    # Laravel feature tests
├── Unit/                       # Laravel unit tests
└── frontend/                   # Vue component tests
```

## Product notes

The frontend redesign prioritizes clear content hierarchy, responsive behavior, keyboard-visible focus states, explicit form labels, useful loading and empty states, and consistent feedback instead of browser alerts. The current application is a strong foundation for adding provider-specific publishing adapters, richer post editing, queued publishing, and role-based workspace permissions.

## License

No license has been selected yet. Add a license before distributing this project publicly.
