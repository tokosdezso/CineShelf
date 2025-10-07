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
- User context is available in controllers via `Auth::user()` or `$request->user()`.

### Scheduled Jobs
- Jobs are defined in `app/Domains/*/Jobs/` (e.g., `UpdatePopularMovies`, `UpdateAllMovies`, `UpdateSingleMovie`).
- Jobs implement `ShouldQueue` and are dispatched for background processing (e.g., updating movie data from TMDB).
- Job failures are logged; jobs can be chained for batch processing (see `UpdateAllMovies` and `UpdateSingleMovie`).
- Scheduling is managed via Laravel's scheduler (see `app/Console/Kernel.php` if present).

### Filament Admin Panel
- Filament resources are in `app/Domains/*/Filament/Resources/` (e.g., `MovieListResource`, `MovieResource`, `GenreResource`, `PopularMovieResource`).
- The Filament admin panel is accessible only to admin users (after login).
- Features:
  - List, create, edit, and delete resources (CRUD) for resources.
  - Advanced table features: sorting, filtering, searching, pagination.
  - Relation managers for managing related models.
  - Custom pages for detailed views and actions.
  - All actions are permission-checked; only authorized admins can access or modify data.
  - UI is highly customizable via Filament's resource and page classes.

### Policies
- Policies are in `app/Domains/*/Policies/` (e.g., `MovieListPolicy`).
- Policies control access to models (view, update, delete, create) and are linked via attributes (e.g., `#[UsePolicy(MovieListPolicy::class)]`).
- Controllers use `$this->authorize()` for per-action checks.

### Request Flow & Validation
- API requests enter via `routes/api.php` and are routed to controllers.
- Controllers use FormRequest classes (e.g., `MovieFilterRequest`, `RegistrationRequest`) for validation and authorization.
- Validated data is passed to service classes for business logic.

### Service Structure & Caching
- All business logic is in service classes (e.g., `TMDBService`, `MovieProcessor`).
- Services handle API calls, data processing, and throw `ApiResponseException` for errors.
- Caching is handled via Laravel's Cache facade (e.g., movie/genre data from TMDB is cached for performance).
- Only successful responses are cached; errors are never cached.

### Model Relationships
- Eloquent models are in `app/Domains/*/Models/` (e.g., `Movie`, `Genre`, `MovieList`, `PopularMovie`).
- Relationships:
  - `Movie` <-> `Genre`: many-to-many
  - `MovieList` <-> `Movie`: many-to-many
  - `MovieList` <-> `User`: many-to-one
  - `PopularMovie` <-> `Movie`: many-to-one
- Pivot tables are used for many-to-many relations (e.g., `movie_list_movie`).

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
- Movie listing supports both grid and list views (toggleable)
- Responsive design: mobile and desktop layouts
- Pagination and search/filter for movies
- User feedback (success and error messages) is provided via a global toast notification system.

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
For questions check the README.md in each subproject for more details.
