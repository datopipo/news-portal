# News Portal - Quick Installation Guide

## System Requirements
- PHP 8.2 or higher
- Composer
- SQLite (default) or MySQL/PostgreSQL
- Web server (Apache/Nginx) or PHP built-in server

## Quick Start

1. **Extract the project files**

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   - The project comes pre-configured with SQLite
   - Update `.env` file if you need to change database or email settings

4. **Set up the database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Create uploads directory**
   ```bash
   mkdir -p public/uploads/pictures
   chmod 755 public/uploads/pictures
   ```

6. **Start the server**
   ```bash
   php -S localhost:8000 -t public
   ```

## Access Information

### Public Interface
- URL: http://localhost:8000
- No authentication required

### Admin Interface
- URL: http://localhost:8000/admin/login
- Username: `admin`
- Password: `admin123`

## Weekly Statistics
To set up weekly statistics email:
1. Configure `STATS_EMAIL` in `.env`
2. Set up cron job:
   ```bash
   0 9 * * 0 /path/to/php /path/to/project/bin/console app:send-weekly-stats
   ```

## Support
For any questions or issues, please contact: valeria.maltseva@we.ee 