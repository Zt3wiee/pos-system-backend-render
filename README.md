# POS System API

A RESTful Point of Sale API built with Laravel. This project handles product and category management, cashier/admin authentication, sale checkout, stock updates, and basic sales reporting.

The goal of this project is to practice building a real backend API with authentication, role-based permissions, relational database design, and transaction-safe business logic.

## Features

- Staff login with Laravel Sanctum
- Admin-managed user accounts
- Token-based API authentication
- Role-based access control for `admin` and `cashier`
- Product CRUD with category relationship
- Category CRUD for admins
- Sale checkout with multiple items
- Automatic stock reduction after a sale
- Database transaction handling for sale processing
- Daily and monthly sales reports
- Top-selling product report
- Dashboard summary for revenue and order counts

## Tech Stack

- PHP 8.2+
- Laravel 12
- Laravel Sanctum
- SQLite
- Eloquent ORM
- PHPUnit

## Project Structure

```text
app/Http/Controllers
  Api/AuthController.php
  ProductController.php
  CategoryController.php
  SaleController.php
  ReportController.php
  SummaryController.php
  UserController.php

app/Http/Middleware
  RoleMiddleware.php

app/Models
  User.php
  Product.php
  Category.php
  Sale.php
  SaleItem.php

routes
  api.php
```

## Installation

Clone the repository:

```bash
git clone <your-repository-url>
cd Pos-System-Api
```

Install PHP dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the app key:

```bash
php artisan key:generate
```

Create the SQLite database file if it does not exist:

```bash
touch database/database.sqlite
```

Run migrations:

```bash
php artisan migrate
```

Start the development server:

```bash
php artisan serve
```

The API will be available at:

```text
http://127.0.0.1:8000/api
```

## Authentication

This POS system does not use public registration. Staff accounts are created and managed by an admin.

Login:

```http
POST /api/login
```

```json
{
  "email": "cashier@example.com",
  "password": "password123"
}
```

Use the returned token for protected routes:

```http
Authorization: Bearer <token>
```

Logout:

```http
POST /api/logout
```

## API Endpoints

### Public

| Method | Endpoint | Description |
| --- | --- | --- |
| POST | `/api/login` | Login and receive an API token |

### Authenticated Users

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/user` | Get the current authenticated user |
| POST | `/api/logout` | Logout current user |

### Products

Accessible by `admin` and `cashier`.

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/products` | List products |
| POST | `/api/products` | Create product |
| GET | `/api/products/{id}` | Show product detail |
| PUT/PATCH | `/api/products/{id}` | Update product |
| DELETE | `/api/products/{id}` | Delete product |

### Categories

Accessible by `admin`.

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/categories` | List categories |
| POST | `/api/categories` | Create category |
| GET | `/api/categories/{id}` | Show category detail |
| PUT/PATCH | `/api/categories/{id}` | Update category |
| DELETE | `/api/categories/{id}` | Delete category |

### Sales

Accessible by `admin` and `cashier`.

| Method | Endpoint | Description |
| --- | --- | --- |
| POST | `/api/sales` | Create a sale and reduce product stock |

Example sale request:

```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 3,
      "quantity": 1
    }
  ]
}
```

### Reports

| Method | Endpoint | Role | Description |
| --- | --- | --- | --- |
| GET | `/api/reports/orders` | Admin, Cashier | List sales orders |
| GET | `/api/reports/daily` | Admin | Daily sales report |
| GET | `/api/reports/monthly` | Admin | Monthly sales report |
| GET | `/api/reports/top-products` | Admin | Top-selling products |
| GET | `/api/reports/summary` | Admin | Revenue and order summary |

### Users

Accessible by `admin`.

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/users` | List users |
| POST | `/api/users` | Create admin or cashier account |
| PUT/PATCH | `/api/users/{id}` | Update user |

## Database Relationships

- A category has many products.
- A product belongs to one category.
- A user has many sales.
- A sale belongs to one user.
- A sale has many sale items.
- A sale item belongs to one product.

## Running Tests

```bash
php artisan test
```

## Current Status

This project is suitable as a junior backend portfolio project. It demonstrates core API development skills, but the next improvements would make it stronger:

- Add feature tests for authentication, products, categories, and sales
- Add seeders for admin, cashier, categories, and sample products
- Add pagination for product, user, and order lists
- Add a Postman collection or OpenAPI documentation
- Improve naming consistency in controller methods
- Add request classes for validation

## Author

Built by Chheangheng as a Laravel backend portfolio project.
