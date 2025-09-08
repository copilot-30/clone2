# GetCare System Documentation

This document provides a comprehensive guide for setting up, using, and maintaining the GetCare system.

## 1. Setup Guide

### Prerequisites

Ensure the following software is installed on your host machine:

*   **Git:** For version control.
*   **Docker:** Docker Engine.
*   **Docker Compose:** For orchestrating multi-container Docker applications.

### Installation Steps

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/your-repo/getcare.git
    cd getcare
    ```

2.  **Environment Variable Setup:**
    Create a `.env` file from the example:
    ```bash
    cp get-care/.env.example get-care/.env
    ```
    Edit `get-care/.env` and update the following:
    *   **Database Credentials:**
        ```
        DB_CONNECTION=pgsql
        DB_HOST=db
        DB_PORT=5432
        DB_DATABASE=getcare_db
        DB_USERNAME=getcare_user
        DB_PASSWORD=getcare_password
        ```
        *Ensure these match the `environment` section in `docker-compose.yml` for the `db` service.*
    *   **Application Key:**
        This will be generated automatically during setup, but ensure `APP_KEY` is empty for it to be generated.
    *   **Payment Gateway Keys (if applicable):**
        ```
        PAYMONGO_SECRET_KEY=your_paymongo_secret_key
        PAYMONGO_PUBLIC_KEY=your_paymongo_public_key
        ```
        *Replace with your actual keys from PayMongo or chosen provider.*
    *   **Google API Credentials (if applicable):**
        ```
        GOOGLE_APPLICATION_CREDENTIALS=/var/www/html/config/google_service_account.json
        ```
        *Place your Google Service Account JSON key file in `get-care/config/google_service_account.json`.*

3.  **Build and Run Docker Containers:**
    Navigate to the root `getcare` directory (where `docker-compose.yml` is located) and run:
    ```bash
    docker-compose up --build -d
    ```
    This command will:
    *   Build the `app` Docker image based on `get-care/Dockerfile`.
    *   Create and start the `db` (PostgreSQL), `app` (PHP-FPM Laravel), and `nginx` containers.
    *   `-d` runs the containers in detached mode.

4.  **Generate Application Key:**
    Once containers are running, generate the Laravel application key:
    ```bash
    docker-compose exec app php artisan key:generate
    ```

5.  **Run Database Migrations:**
    This will create all necessary tables in the PostgreSQL database:
    ```bash
    docker-compose exec app php artisan migrate --force
    ```
    *The `--force` flag is needed when running migrations in production-like environments.*

### Running the Application

*   The application should now be accessible in your web browser at `http://localhost:8000`.
*   The frontend application (if separate and running locally) should be configured to point to this backend URL.

## 2. Usage Guide

### Authentication

*   **Patient Registration:** Use the `/api/register` endpoint with `name`, `email`, `password`, `password_confirmation`, and `privacy_consent` (boolean `true`).
*   **User Login:** Use the `/api/login` endpoint with `email` and `password`. Returns `access_token`.
*   **User Logout:** Use the `/api/logout` endpoint with an authenticated token.

### Roles and Permissions

*   **Admin:** Full access to all modules (user management, appointment oversight, reporting, audit logs).
*   **Doctor:** Access to their profile, availability, assigned patients, consultations, and earnings.
*   **Patient:** Access to their profile, appointments, health records, and subscriptions.

### Key Features

*   **Doctor Module:**
    *   **Dashboard:** View upcoming appointments, quick actions, earnings summary.
    *   **Availability:** Configure online and face-to-face schedules.
    *   **Consultations:** Access patient records (with AI recommendations), conduct consultations, document notes, prescribe medication, request lab tests.
    *   **Payments:** Track earnings, request payouts.
*   **Admin/Secretary Module:**
    *   **Dashboard:** System overview, quick links.
    *   **User Management:** Create/edit/delete doctor and patient accounts, assign specialties.
    *   **Appointment Oversight:** View, cancel, reschedule, reassign appointments.
    *   **Reporting:** Access consultation history, doctor performance metrics.
    *   **Audit Logs:** View detailed system activity logs.

## 3. Maintenance Guide

### Dependency Management

*   **Composer (PHP/Laravel):** To update backend dependencies, use:
    ```bash
    docker-compose exec app composer update
    ```
*   **NPM (Frontend/UI):** For frontend dependencies (in `ui/` directory):
    ```bash
    cd ui
    npm install
    npm update
    ```

### Database Management

*   **Running Migrations:**
    ```bash
    docker-compose exec app php artisan migrate
    ```
*   **Rolling Back Migrations:** (Use with caution!)
    ```bash
    docker-compose exec app php artisan migrate:rollback
    # or to rollback all migrations
    docker-compose exec app php artisan migrate:reset
    # or to refresh (rollback all, then migrate)
    docker-compose exec app php artisan migrate:refresh
    ```
*   **Database Backups:**
    Use `pg_dump` from your host or a dedicated backup solution for the PostgreSQL volume.
    Example: `docker exec getcare_db pg_dump -U getcare_user getcare_db > backup.sql`

### Logging

*   **Laravel Logs:** Found at `get-care/storage/logs/laravel.log` (accessible from the host due to volume mount, or `docker-compose exec app tail -f storage/logs/laravel.log`).
*   **Docker Logs:** View logs for individual services:
    ```bash
    docker-compose logs -f app
    docker-compose logs -f db
    docker-compose logs -f nginx
    ```

### Troubleshooting Common Issues

*   **`Error: port already in use`:**
    *   Identify the process using the port (e.g., `lsof -i :8000`).
    *   Kill the process (`kill -9 PID`) or change the port mapping in `docker-compose.yml`.
*   **`Class 'Laravel\Sanctum\...' not found`:**
    *   Clear Laravel caches and re-dump Composer autoload:
        ```bash
        docker-compose exec app php artisan optimize:clear
        docker-compose exec app composer dump-autoload
        ```
    *   Ensure Sanctum service provider is correctly registered in `get-care/config/app.php`.
    *   If persistent in tests, might be a deeper environmental issue as noted in development.
*   **`could not translate host name "db" to address`:**
    *   Ensure `DB_HOST=db` in `get-care/.env`.
    *   Ensure `db` service is healthy in `docker-compose ps`.
    *   Try restarting Docker containers: `docker-compose down && docker-compose up -d`.

### Security Best Practices

*   **Environment Variables:** Never commit `.env` files to version control.
*   **Strong Passwords:** Use strong, unique passwords for all database users and application accounts.
*   **Regular Updates:** Keep all dependencies (Composer, npm, Docker images) updated to patch security vulnerabilities.
*   **HTTPS:** Always use HTTPS for production environments. Configure Nginx with SSL certificates.
*   **Firewall:** Restrict access to necessary ports (e.g., 80/443 for web, SSH for server access).

### Deployment

Refer to the "Deployment Strategy and CI/CD Pipelines" section in the `design_document.md` for detailed deployment information.

---