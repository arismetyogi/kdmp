# Laravel + Livewire Portal Admin App

## Technologies

- [Laravel 12](https://laravel.com/docs/)
- [Livewire v3](https://livewire.laravel.com/docs/)
- [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v6)

## Prerequisites

Before you begin, ensure you have met the following requirements:

- Optional: [Laravel Herd](https://herd.laravel.com) -> for local development server (node, nginx and php included)
- PHP >= 8.3
- Composer (for package management)
- Node.js 20+ & npm 10.9+ (for frontend dependencies)
- MySQL or another [compatible database](https://laravel.com/docs/11.x/database#configuration)
- Optional: [Laravel installer & Get ready](https://laravel.com/docs/12.x/installation)

## Installation

1. Clone the repository: `git clone https://github.com/arismetyogi/portal-kam-2.git [project directory]`
2. Navigate into the project directory: `cd [project directory]`
3. Install PHP dependencies: `composer install`
4. Copy `.env.example` to `.env` and configure your environment variables, including database settings and application
   key.
5. Generate application key: `php artisan key:generate`
6. Run database migrations: `php artisan migrate`
7. Optionally, seed the database: `php artisan db:seed`
8. Install frontend dependencies: `npm install && npm run build` then `composer run dev` to run local development server

## Usage

To start the development server, run:

```
composer run dev
```

Access the application in your browser at `http://localhost:8000` or `http://[appname].test` by default.

## Contact

If you have any questions, feedback, or support requests, you can reach me
here [arismetyogi@gmail.com](https://github.com/arismetyogi)
