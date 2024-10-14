# kki-market
Single-page E-commerce web application built with Laravel and Vue.js, using Tailwind CSS and DaisyUI for a modern and responsive design.

## Tech Stack
### Backend: Laravel (PHP 8.2)
- Framework: Laravel 11.9
- Database: MySQL 8.0 / MariaDB 11.4
- Authentication: Laravel Sanctum
- Dev tools: Composer 2.7, PHPStan 1.12

### Frontend: Vue.js (3.5)
- Routing: Vue Router 4.4
- UI: Tailwind CSS 3.4, DaisyUI 4.12, typed.js 2.1
- HTTP Requests: Axios 1.7.7
- Dev tools: Node 20.11, Vite 5.4, ESLint 9.12

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