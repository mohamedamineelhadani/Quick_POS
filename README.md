# Laravel POS System

A simple Point of Sale system built with Laravel 10.

## Installation

1. Install dependencies:
   composer install

2. Setup environment:
   cp .env.example .env
   php artisan key:generate

3. Configure database in .env file, then run:
   php artisan migrate

4. Create storage link for images:
   php artisan storage:link

5. Start the server:
   php artisan serve

## Default Login
- Email: admin@gmail.com
- Password: admin123

## Features
- Category Management (CRUD)
- Product Management (CRUD with image upload)
- Point of Sale (Cart, 5% Tax, Checkout)
- Order History (View past orders and details)