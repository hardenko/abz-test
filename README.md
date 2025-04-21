# User Management API

A Laravel-based REST API for user management with authentication, input validation, and image processing capabilities.

## Features

- User registration and authentication
- User profile management (create, read)
- List users with pagination
- Image upload and optimization with TinyPNG
- Token-based authentication
- PostgreSQL database support
- Comprehensive validation

## Requirements

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/downloads)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/hardenko/abz-test.git
cd abz-test
```

### 2. Copy environment file

```bash
cp .env.example .env
```

### 3. Start Laravel Sail

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

```bash
./vendor/bin/sail up -d
```

### 4. Generate application key

```bash
./vendor/bin/sail artisan key:generate
```

### 5. Run migrations and seeders

```bash
./vendor/bin/sail artisan migrate --seed
```

### 6. Set TinyPNG API key

You need to get a TinyPNG API key from [https://tinypng.com/developers](https://tinypng.com/developers).

Add your TinyPNG API key to the `.env` file:

```
TINIFY_API_KEY=your_api_key_here
```

### 7. Create symbolic link for storage

```bash
./vendor/bin/sail artisan storage:link
```

## Running Tests

To run the test suite and ensure everything is working correctly:

```bash
./vendor/bin/sail artisan test
```

## API Endpoints

### Authentication

- `GET /api/token` - Get an authentication token

### Users

- `GET /api/users` - Get paginated user list
- `GET /api/users/{id}` - Get a specific user
- `POST /api/users` - Create a new user (requires authentication token)

### Positions
- `GET /api/positions` - Get position list


## Development Commands

### Start the application

```bash
./vendor/bin/sail up -d
```

### Stop the application

```bash
./vendor/bin/sail down
```

### Run database migrations

```bash
./vendor/bin/sail artisan migrate
```

### Clear cache

```bash
./vendor/bin/sail artisan optimize:clear
```

### Run PHP Code Style Fixer (Pint)

```bash
./vendor/bin/sail artisan pint
```

## Project Structure

```
app/
├── Controllers/           # Controllers
├── Dto/                   # Data Transfer Objects
├── Exceptions/            # Custom exceptions
├── Http/                  # HTTP layer
│   ├── Controllers/       # API controllers
│   └── Request/           # Form requests
├── Interfaces/            # Service interfaces
├── Models/                # Eloquent models
├── Resources/             # API resources
├── Rules/                 # Custom validation rules
└── Services/              # Application services
```

## Configuration

### TinyPNG

The application uses TinyPNG for image optimization. You can configure the API key in your `.env` file:

```
TINIFY_API_KEY=your_api_key_here
```

### Database

The default configuration uses PostgreSQL. You can modify database settings in your `.env` file:

```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
