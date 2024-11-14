# kki-market
Single-page E-commerce web application built with Laravel and Vue.js, using Tailwind CSS and DaisyUI for a modern and responsive design.

## Tech Stack
### Backend: Laravel (PHP 8.2)
- Framework: Laravel
- Database: MySQL / MariaDB
- Authentication: Laravel Sanctum
- Dev tools: Composer, PHPStan

### Frontend: Vue.js 3
- Routing: Vue Router
- UI: Tailwind CSS 3, DaisyUI, typed.js
- HTTP Requests: Axios
- Dev tools: Node 20.11, Vite, ESLint

## Installation
1. Make sure you've installed PHP, Composer, Node and Laravel Framework. Then run
    ```
    composer install
    npm install
    ```
2. Make a table called kki_db in your SQL and run
    ```
    php artisan migrate:fresh --seed
    ```
3. Then run
    ```
    npm run dev
    php artisan serve
    ```
