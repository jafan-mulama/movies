# Video Streaming Platform

A modern video streaming platform built with Laravel, offering features similar to YouTube with structured dashboards for different user roles.

## Features

- User Management with Role-based Access Control
- Subscription System with Multiple Plans
- Video Content Management
- Cast & Crew Management
- Review and Rating System
- Category Management
- Admin Dashboard
- Content Creator Dashboard
- Moderator Dashboard

## Tech Stack

- PHP 8.x
- Laravel 10.x
- MySQL
- Bootstrap 5
- JavaScript/jQuery

## Requirements

- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM

## Installation

1. Clone the repository
```bash
git clone [repository-url]
cd movies
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Create environment file
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Configure your database in .env file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations and seeders
```bash
php artisan migrate
```

8. Create storage link
```bash
php artisan storage:link
```

9. Start the development server
```bash
php artisan serve
```

## Project Structure

- `app/Models/` - Contains all Eloquent models
- `app/Http/Controllers/` - Contains all controllers
- `database/migrations/` - Contains all database migrations
- `routes/` - Contains all route definitions
- `resources/views/` - Contains all blade views

## Database Schema

### Main Tables
- users
- movies
- categories
- reviews
- cast_crews
- roles
- subscription_plans

### Pivot Tables
- movie_category
- movie_cast_crew

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details
