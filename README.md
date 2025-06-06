# 📰 News Portal

A simple, clean news portal built with **Symfony 7** and **Doctrine 3**, following **KISS principles**.

## ✨ Features

- **📖 Public Interface**: Browse news articles by categories, read articles, leave comments
- **⚙️ Admin Interface**: Manage news articles, categories, and comments
- **🔐 Authentication**: Simple admin login system
- **📁 Categories**: Organize articles into categories
- **💬 Comments**: Readers can comment on articles
- **🖼️ Images**: Upload and display article images
- **📱 Responsive**: Clean, modern UI that works on all devices

## 🛠️ Technology Stack

- **Backend**: Symfony 7, Doctrine 3, PHP 8+
- **Frontend**: Twig templates, TailwindCSS
- **Database**: SQLite (development), easily configurable for production
- **Forms**: Symfony Forms with validation
- **File Upload**: Local file storage for images

## 🚀 Quick Start

### 1. Install Dependencies

```bash
composer install
```

### 2. Set Up Database

```bash
# Create database and schema
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
```

### 3. Seed with Fake Data

```bash
# Load sample data (10 categories, 50 articles, 200 comments)
php bin/console app:seed-data

# Or clear existing data and reload
php bin/console app:seed-data --clear
```

### 4. Start Development Server

```bash
# Start the built-in PHP server
php -S 127.0.0.1:8000 -t public/

# Visit your site
# Public site: http://127.0.0.1:8000
# Admin panel: http://127.0.0.1:8000/admin
```

## 🔑 Admin Access

**Username**: `admin`  
**Password**: `admin123`



## 📁 Project Structure

```
src/
├── Controller/
│   ├── Public/PublicController.php      # Public site (home, articles, comments)
│   └── Admin/                           # Admin interface
│       ├── AdminController.php          # Dashboard, login/logout
│       ├── AdminNewsController.php      # Manage articles
│       ├── AdminCategoryController.php  # Manage categories
│       └── AdminCommentController.php   # Manage comments
├── Entity/                              # Data models
│   ├── News.php                        # Article entity
│   ├── Category.php                    # Category entity
│   └── Comment.php                     # Comment entity
├── Form/                               # Symfony forms
├── Repository/                         # Database queries
├── DataFixtures/                       # Fake data generators
└── Command/SeedDataCommand.php         # Data seeding command

templates/
├── base.html.twig                      # Base template
├── home/                               # Public homepage
├── news/                               # Article pages
├── category/                           # Category pages
└── admin/                              # Admin interface templates

config/
├── routes.yaml                         # Route definitions (YAML, no annotations)
├── doctrine/                           # XML entity mapping (no annotations)
└── packages/                           # Bundle configurations
```

## 🎯 Key Design Principles

### KISS (Keep It Simple, Stupid)
- **No Overengineering**: Simple controllers, no abstract base classes
- **Direct Logic**: Clear, readable code over clever abstractions
- **Simple Workflow**: Create article → immediately visible (no draft/publish complexity)

### No Annotations Policy
- **YAML Routing**: All routes defined in `config/routes.yaml`
- **XML Doctrine Mapping**: Entity definitions in `config/doctrine/`
- **Clean PHP Classes**: No attribute clutter

## 🔧 Development Commands

### Database
```bash
# Create database
php bin/console doctrine:database:create

# Update schema after entity changes
php bin/console doctrine:schema:update --force

# Validate schema
php bin/console doctrine:schema:validate
```

### Data Management
```bash
# Clear cache
php bin/console cache:clear
```

### Debugging
```bash
# Check routes
php bin/console debug:router

# Check services
php bin/console debug:container

# Check forms
php bin/console debug:form
```

## 📝 Usage Guide

### 🌱 Using the Data Seeder

```bash
# Seed with sample data (10 categories, 50 articles, 200 comments)
php bin/console app:seed-data

# Clear existing data and reseed
php bin/console app:seed-data --clear
```

**Generated data**: Technology/Sports/Business categories, realistic articles with content, user comments, proper relationships

### For Administrators

1. **Login**: Visit `/admin` and login with admin/admin123
2. **Dashboard**: View statistics and recent activity
3. **Manage Articles**: Create, edit, delete news articles
4. **Manage Categories**: Organize content with categories
5. **Moderate Comments**: Review and manage user comments

### For Developers

1. **Adding Features**: Follow the simple controller pattern
2. **Database Changes**: Update entities, run schema update
3. **New Routes**: Add to `config/routes.yaml`
4. **Styling**: TailwindCSS classes in templates

## 🛡️ Security Features

- **Admin Authentication**: Login required for admin panel
- **CSRF Protection**: All forms protected against CSRF attacks
- **Input Validation**: Symfony form validation on all inputs
- **File Upload Security**: Validated image uploads with size limits

## 📚 Additional Information

### File Uploads
- **Location**: `public/uploads/pictures/`
- **Allowed Types**: JPEG, PNG, GIF
- **Size Limit**: 5MB maximum
- **Processing**: Automatic filename generation to prevent conflicts

### Database
- **Development**: SQLite database in project root
- **Production**: Easily configurable for MySQL/PostgreSQL
- **Migrations**: Not used (keeping it simple with direct schema updates)

---

**Built with ❤️ using Symfony and KISS principles** 