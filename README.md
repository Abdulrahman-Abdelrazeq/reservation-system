# Service Reservation System

This is a simple Laravel-based service reservation system that allows users to browse services, make reservations, and manage them. Admin users can manage services and update reservation statuses.

## Tech Stack

-   PHP 8.x
-   Laravel 10+
-   MySQL
-   Blade Templates

## Setup Instructions

1. **Clone the repository**

    ```bash
    git clone https://github.com/Abdulrahman-Abdelrazeq/reservation-system.git
    cd reservation-system
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install && npm run build
    ```

3. **Set up the environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure `.env`**

    - Update database settings: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

5. **Run migrations and seeders**

    ```bash
    php artisan migrate:fresh --seed
    ```

6. **Run the application**

    ```bash
    php artisan serve
    ```

7. **Login Credentials**
    - Admin: `abdo@gmail.com` / `abdo2025`

## Tool Choices & Design Decisions

-   **Laravel** was chosen for its elegant syntax and ecosystem.
-   **Blade** templating for simple and maintainable frontend.
-   **Seeders** were used to quickly populate data for testing.

---

## Business Requirements Understanding

The system allows users to book services easily by browsing available options and reserving a time. It supports user authentication, service filtering, and reservation management. Admins can control available services and monitor reservations. The system is aimed at simplifying scheduling between customers and service providers.

## Feature Suggestion

A helpful addition would be to implement availability checking to prevent double booking. This ensures that no two users can reserve the same service at the same time, improving reliability and user trust.
