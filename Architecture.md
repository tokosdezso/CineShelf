# CineShelf Architecture Overview

## Monorepo Structure

The CineShelf project is a monorepo containing a Laravel backend (API, business logic, database) and a Vue 3 frontend (SPA). The two are separated into distinct folders:

- `laravel-backend/` — Laravel 10+ application (API, authentication, business logic, database)
- `vue-frontend/` — Vue 3 application (SPA, Pinia store, axios, component-based UI)

---

## Backend: Laravel

### Key Folders
- `app/Http/Controllers/` — API controllers (REST endpoints, error handling)
- `app/Services/` — Service classes (TMDB integration, movie processing, business logic)
- `app/Exceptions/` — Custom exceptions (e.g., `ApiResponseException` for API error handling)
- `app/Http/Requests/` — Form requests (validation, e.g., `MovieFilterRequest`)
- `app/Domains/` — Domain models, Filament resources, jobs, policy (e.g., Movie, Genre, MovieList, PopularMovie)
- `routes/` — Route definitions (`web.php`, `api.php`)
- `database/` — Migrations, seeders, factories, SQLite DB
- `tests/Unit/` — PHPUnit unit tests (service and controller logic)

### API & Business Logic
- All API endpoints are under `/api` and handled by controllers in `app/Http/Controllers/`.
- Business logic is separated into service classes (e.g., `TMDBService`, `MovieProcessor`).
- TMDBService handles all external API calls to The Movie Database (TMDB), with caching and error handling.
- Custom exceptions (notably `ApiResponseException`) are used for consistent API error responses.
- Validation is handled via FormRequest classes (e.g., `RegistrationRequest`).
- Authentication uses Laravel Sanctum in session/cookie mode: login and API requests are authenticated via session cookie and XSRF-TOKEN.

### Error Handling
- Controllers catch `ApiResponseException` and return a JSON error with status code and message.
- Service classes throw `ApiResponseException` for all business/API errors (e.g., empty results, invalid data).
- Only successful API responses are cached; errors are never cached.

### Testing
- Unit tests use PHPUnit and Mockery for mocking (especially Guzzle HTTP client in TMDBService).
- All TMDB service methods and error cases are covered by tests in `tests/Unit/`.

---

## Frontend: Vue 3

### Key Folders
- `src/` — Main source code
  - `components/` — Vue components (UI, layouts, film grid, etc.)
  - `assets/` — Static assets
  - `App.vue`, `main.js` — App entry point
  - `store/` — Pinia store (user, auth, etc.)
- `public/` — Static files (favicon, images)

### Features
- SPA communicates with backend via axios (API calls to `/api` endpoints)
- State management with Pinia
- Auth flow: login, registration, token storage
- Modern UI with slots, layouts, and reusable components

---

## Database
- SQLite for local development (see `database/database.sqlite`)
- Migrations for all tables (users, movies, genres, movie lists, pivot tables)
- Eloquent models for all entities
- Factory and seeders for test data

---

## Integration & Deployment
- CORS configured for local frontend-backend communication
- Environment variables in `.env` for API keys, DB, etc.
- Vite used for both Laravel and Vue asset building
- To run locally:
  1. Start backend: `php artisan serve` (in `laravel-backend/`)
  2. Start frontend: `npm run dev` (in `vue-frontend/`)

---

## Best Practices
- All business logic is in services, not controllers
- All API errors are handled via exceptions for consistency
- All user input is validated via FormRequest
- All external API calls are cached and error-handled
- All new features should include unit tests

---

## Extending the Project
- Add new API endpoints: create a controller, service, and (optionally) FormRequest
- Add new frontend features: create a Vue component and connect to the API via axios
- Add new models: create migration, model, and update relationships
- Always write tests for new backend logic

---

## Contact
For questions, contact the original author or check the README.md in each subproject for more details.
