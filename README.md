# Laravel Amortization Payment Process

This is a Laravel project to process amortization payments. Follow the instructions below to set up and run the project.

## Prerequisites

-   PHP >= 7.3
-   Composer
-   MySQL or any other database engine compatible with Laravel

## Installation Steps

### 1. Check PHP Installation

Open your terminal and run:

```bash
php -v
```

If PHP is installed, it should display the version. If not, install PHP first.

### 2. Check Composer Installation

In the terminal, run:

```bash
composer --version
```

If Composer is installed, it should display the version. If not, [install Composer](https://getcomposer.org/doc/00-intro.md).

### 3. Clone the Repository

Clone the repository to your local machine.

```bash
git clone https://github.com/JSegundo/amortization-payment-process
```

Navigate to the project folder.

```bash
cd amortization-payment-process
```

### 4. Install Dependencies

Install PHP dependencies.

```bash
composer install
```

### 5. Environment Setup

Copy the `.env.example` file to a new `.env` file.

```bash
cp .env.example .env
```

Edit the `.env` file to add your database configuration. For example,

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Generate App Key

Generate a new application key.

```bash
php artisan key:generate
```

### 7. Database Setup

Run the database migrations and seed the database.

```bash
php artisan migrate --seed
```

### 8. Run the Development Server

Now you can run the development server.

```bash
php artisan serve
```

Open your browser and navigate to `http://127.0.0.1:8000/amortizations`.
You can also check `/amortizations-to-pay/{date}` and `/amortizations/{id}`

## And you're all set! 🎉
