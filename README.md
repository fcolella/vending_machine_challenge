# Vending Machine Challenge

A fully functional vending machine backend/frontend built with PHP/Laravel 12, MySQL, Docker and hexagonal architecture.

## Requirements

- Docker Desktop (or Docker Engine + Docker Compose)
- Git

> No PHP, Composer or MySQL installation required.

## How to Run

### 1. Clone the repository
```
git clone https://github.com/fcolella/vending_machine_challenge.git
```

### 2. Start the application
```
docker-compose up -d --build
```

> **Note**:This starts:
> - `app` (PHP 8.3 + Laravel)
> - `db` (MySQL 8.0)
> - `web` (Nginx)

### 3. Run database migrations
```
docker-compose exec app php artisan migrate
```

### 4. Seed initial data (items + change)
```
docker-compose exec app php artisan db:seed --class=TestVendingSeeder
```

> **Note**: The seeder creates:
> - Water (10 units, $0.65)
> - Juice (10 units, $1.00)
> - Soda (10 units, $1.50)
> - 20 coins of each type: $0.05, $0.10, $0.25, $1.00

## Access the App

**Frontend**: http://localhost:8000  
**API Base URL**: http://localhost:8000/api

## Tests

Tests use SQLite in-memory and run migrations + seeding automatically.

docker-compose exec app composer test

## Project Structure (Hexagonal)
```
app/
├── Domain/           → Entities, Value Objects, Interfaces
├── Application/      → Use Cases
└── Infrastructure/   → Controllers, Eloquent Repositories
```

## Built With

- Laravel 12 – Backend framework
- MySQL 8.0 – Database
- PHP 8.3 – Runtime
- Docker – Containerization
- jQuery + CSS – Frontend animations
- PHPUnit – Testing

## Author

Facundo Colella
