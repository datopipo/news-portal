# 📰 News Portal

Simple news portal built with **Symfony 7** and **Doctrine 3**.

## ✨ Features

- Public site: browse articles, categories, comments
- Admin panel: manage content
- Authentication, file uploads, responsive UI

## 🚀 Quick Start

```bash
# Install
composer install

# Setup database
php bin/console doctrine:database:create
php bin/console doctrine:schema:create

# Seed with fake data (10 categories, 50 articles, 200 comments)
php bin/console app:seed-data

# Start server
php -S 127.0.0.1:8000 -t public/
```

**Public**: http://127.0.0.1:8000  
**Admin**: http://127.0.0.1:8000/admin (admin/admin123)

## 🔧 Commands

```bash
# Data seeding
php bin/console app:seed-data           # Add sample data
php bin/console app:seed-data --clear   # Reset and reseed

# Database
php bin/console doctrine:schema:update --force

# Debug
php bin/console debug:router
php bin/console cache:clear
```

## 📁 Structure

```
src/Controller/Public/    # Public site
src/Controller/Admin/     # Admin interface  
src/Entity/              # News, Category, Comment
templates/               # Twig templates
config/routes.yaml       # Routes (no annotations)
```

**Built with Symfony 7 following KISS principles** 