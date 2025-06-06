# 📰 News Portal

Simple news portal built with **Symfony 7** and **Doctrine 3**.

## 🚀 Quick Start

```bash
# Install dependencies
composer install

# Setup database
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

# Seed with fake data (10 categories, 50 articles, 200 comments)
php bin/console app:seed-data

# Start server
php -S 127.0.0.1:8000 -t public/
```

**Public**: http://127.0.0.1:8000  
**Admin**: http://127.0.0.1:8000/admin (admin/admin123)

> ✅ **Ready to go!** All environment variables are pre-configured in `.env.local`

## 🔧 Commands

```bash
# Data seeding
php bin/console app:seed-data           # Add sample data
php bin/console app:seed-data --clear   # Reset and reseed

# Weekly email statistics
php bin/console app:send-weekly-stats   # Send Top 10 news via email

# Database
php bin/console doctrine:schema:update --force

# Debug
php bin/console debug:router
php bin/console cache:clear
```

## 📧 Email Setup

Email configuration is **pre-configured** in `.env.local`:
- `STATS_EMAIL=admin@example.com` - Where weekly stats are sent
- `FROM_EMAIL=noreply@newsportal.com` - Sender address
- `MAILER_DSN=null://null` - Uses Symfony's null transport (for development)

**Automated weekly sending** (add to crontab for production):
```bash
0 9 * * 0 php /path/to/project/bin/console app:send-weekly-stats
```

> 💡 **Note**: Configure `MAILER_DSN` with real SMTP settings for production use

## ⚙️ Pre-configured Environment

The project includes a ready-to-use `.env.local` file with:

```bash
# Admin credentials (username: admin, password: admin123)
ADMIN_USERNAME=admin
ADMIN_PASSWORD_HASH=$2y$13$am/.XFWLedqMnDxRo6YSKucUlYBJeuolf1sfrrChV3G8M.MYBF/HG

# Email configuration for weekly stats
STATS_EMAIL=admin@example.com
FROM_EMAIL=noreply@newsportal.com
```

**No manual setup required!** The app works immediately after `git clone` and `composer install`.

## 📁 Structure

```
src/Controller/Public/    # Public site
src/Controller/Admin/     # Admin interface  
src/Entity/              # News, Category, Comment
templates/               # Twig templates
config/routes.yaml       # Routes (no annotations)
```

**Built with Symfony 7 ** 
