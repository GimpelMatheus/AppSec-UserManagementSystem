

# User Management System

## Overview

This Laravel application provides a user management system with functionality for viewing, editing, updating, and deleting user profiles. It includes authentication features such as login, logout, and email verification. The application also supports administrative functionalities for managing users.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)
- [Folder Structure](#folder-structure)
- [License](#license)

## Features

- **User Authentication:** Login, logout, and password reset.
- **Profile Management:** View, edit, update, and delete user profiles.
- **Admin Functionality:** Create, update, and delete user accounts.
- **Email Verification:** Send and verify email addresses.

## Requirements

- PHP 8.1 or higher
- Composer
- Laravel 10.x
- MySQL or another supported database

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/your-username/user-management-system.git
   cd user-management-system
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Set Up Environment**

   Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   Edit the `.env` file to configure your database and other environment variables:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**

   ```bash
   php artisan migrate
   ```

6. **Seed the Database (Optional)**

   ```bash
   php artisan db:seed
   ```

## Configuration

1. **Email Configuration**

   Configure email settings in `.env` for email verification:

   ```dotenv
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=no-reply@example.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

2. **Update Routes**

   Ensure routes are correctly defined in `routes/web.php`. Refer to the [Routes](#usage) section for more information.

## Usage

1. **Start the Development Server**

   ```bash
   php artisan serve
   ```

   Access the application at `http://localhost:8000`.

2. **Authentication Routes**

   - **Login:** `/login` (GET, POST)
   - **Logout:** `/logout` (POST)

3. **Profile Routes**

   - **View Profile:** `/profile` (GET)
   - **Edit Profile:** `/profile/edit` (GET)
   - **Update Profile:** `/profile` (PATCH)
   - **Delete Profile:** `/profile` (DELETE)

4. **Admin Routes**

   - **View Users:** `/admin/users` (GET)
   - **Create User:** `/admin/users` (POST)
   - **Edit User:** `/admin/users/{user}/edit` (GET)
   - **Update User:** `/admin/users/{user}` (PUT)
   - **Delete User:** `/admin/users/{user}` (DELETE)

5. **Email Verification Routes**

   - **Verify Email:** `/verify-email/{id}/{hash}` (GET)
   - **Resend Verification Email:** `/email/resend` (POST)

## Testing

1. **Run Tests**

   ```bash
   php artisan test
   ```

   Or to run specific tests:

   ```bash
   php artisan test --filter ProfileControllerTest
   ```

2. **Test Cases**

   - **View Profile**
   - **Show Edit Profile Form**
   - **Update Profile with Valid Data**
   - **Update Profile with Invalid Data**
   - **Delete Profile**

   Test cases are located in `tests/Feature/Auth/ProfileControllerTest.php`.

## Folder Structure

- **app/**: Contains application logic, including controllers, models, and exceptions.
  - **Http/Controllers/**: Controllers for handling requests.
  - **Models/**: Eloquent models for database interactions.
  - **Exceptions/**: Custom exception classes.
- **routes/**: Defines application routes.
  - **web.php**: Routes for web requests.
- **tests/**: Contains test cases for the application.
  - **Feature/Auth/**: Test cases for authentication and profile management.
- **resources/views/**: Blade templates for views.
- **database/**: Contains migration files and seeders.
