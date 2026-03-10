# Garage Management System

A SaaS-based Garage Management System built with Laravel.

## Features
- Multi-tenant garage management
- Customer management
- Vehicle records
- MOT reminders
- Service history
- Invoice generation
- Automated notifications

## Tech Stack
- Laravel 11
- MySQL
- Bootstrap
- JavaScript

## Installation

1. Clone repository
git clone https://github.com/khanbro2/garage-management-system.git

2. Install dependencies
composer install

3. Copy env file
cp .env.example .env

4. Generate key
php artisan key:generate

5. Run migrations
php artisan migrate

6. Start server
php artisan serve
