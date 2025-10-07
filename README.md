# CineShelf Monorepo - Quickstart & Setup

## Overview
CineShelf is a monorepo containing a Laravel backend (API, business logic, database) and a Vue 3 frontend (SPA). The backend integrates with TMDB (The Movie Database) and provides a modern admin interface with Filament.

---

## Prerequisites
- PHP 8.1+
- Composer
- Node.js & npm
- SQLite (or other supported DB)

---

## Backend Setup (Laravel)

1. **Clone the repository and enter the backend folder:**
   ```sh
   cd CineShelf/laravel-backend
   ```
2. **Install dependencies:**
   ```sh
   composer install
   ```
3. **Copy and configure environment:**
   ```sh
   cp .env.example .env
   # Edit .env and set your TMDB_API_READ_ACCESS_TOKEN (get from TMDB)
   ```
4. **Generate app key:**
   ```sh
   php artisan key:generate
   ```
5. **Run migrations and seeders:**
   ```sh
   php artisan migrate --seed
   ```
   #If you use Sqlite press enter.
6. **Create a Filament admin user:**
   ```sh
   php artisan make:filament-user
   ```
   #Type the name, email, and password when prompted.
7. **Start the backend server (default: port 8000):**
   ```sh
   php artisan serve
   ```

---

## Frontend Setup (Vue 3)

1. **Enter the frontend folder:**
   ```sh
   cd vue-frontend
   ```
2. **Install dependencies:**
   ```sh
   npm install
   ```
3. **Copy and configure environment:**
   ```sh
   cp .env.example .env
   ```

4. **Start the frontend dev server (default: port 3000):**
   ```sh
   npm run dev
   ```

---

## Background Workers & Scheduling

- **To process queued jobs:**
  ```sh
  php artisan queue:work
  ```
- **To run scheduled jobs (e.g., TMDB sync):**
  ```sh
  php artisan schedule:work
  ```

---

## Ports
- Backend: http://localhost:8000
- Frontend: http://localhost:3000

---

## TMDB API Token
- Register at https://www.themoviedb.org/ to get an API Read Access Token.
- Set `TMDB_API_READ_ACCESS_TOKEN` in your `.env` file.

---

## Useful Commands
- `php artisan migrate` — Run database migrations
- `php artisan db:seed` — Seed the database
- `php artisan make:filament-user` — Create an admin user for Filament
- `php artisan queue:work` — Start the queue worker
- `php artisan schedule:work` — Start the scheduler for jobs
- `php artisan serve` — Start the project

---

## Notes
- The backend uses session/cookie authentication (Laravel Sanctum) with XSRF-TOKEN for SPA security.
- All API endpoints are under `/api` and require authentication.
- The Filament admin panel is available only to admin users after login.

---

For more details, see `Architecture.md`.
