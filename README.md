# News Portal - Symfony 7 Application

A modern news portal built with Symfony 7 and Doctrine 3, featuring both public and admin interfaces.

## 🚀 Features

### Public Interface
- **Home Page**: Displays categories with their 3 most recent news articles
- **Category Pages**: Paginated news listings (10 per page) for each category
- **News Detail Pages**: Full article view with comments system
- **Responsive Design**: Modern Bootstrap 5 UI with mobile support

### Admin Interface
- **Dashboard**: Statistics overview with top news, latest articles, and recent comments
- **Category Management**: Add, edit, and delete news categories
- **News Management**: Create, edit, and delete news articles with image upload
- **Comment Management**: View and delete user comments
- **Secure Authentication**: Hardcoded admin user (admin/admin123)

### Technical Features
- **File Upload**: Image upload for news articles (JPEG, PNG, GIF, max 5MB)
- **View Tracking**: Automatic view count increment for news articles
- **Weekly Statistics**: Email automation for top 10 news statistics
- **Modern Architecture**: Symfony 7 with Doctrine 3, PSR-compliant code

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- SQLite (default) or MySQL/PostgreSQL
- Web server (Apache/Nginx) or PHP built-in server

## 🛠️ Installation

1. **Clone or extract the project**
   ```bash
   cd news-portal
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   # The .env file is already configured with SQLite
   # Update MAILER_DSN and email settings if needed
   ```

4. **Create database and run migrations**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Create uploads directory**
   ```bash
   mkdir -p public/uploads/pictures
   chmod 755 public/uploads/pictures
   ```

6. **Start the development server**
   ```bash
   php -S localhost:8000 -t public
   ```

## 🎯 Usage

### Public Access
- **Home Page**: http://localhost:8000
- **Category Pages**: Click on "View All" for any category
- **News Articles**: Click on any news title to read full article
- **Comments**: Add comments on news detail pages

### Admin Access
- **Login URL**: http://localhost:8000/admin/login
- **Credentials**: 
  - Username: `admin`
  - Password: `admin123`
- **Dashboard**: Overview of all content and statistics
- **Management**: Create and manage categories, news, and comments

### Sample Data
The application includes sample categories and news articles:
- Technology category with a sample tech news article
- Sports category with a sample sports news article

## 📧 Weekly Statistics

Configure and run weekly statistics email:

```bash
# Test the command
php bin/console app:send-weekly-stats

# Set up cron job for weekly execution (every Sunday at 9 AM)
0 9 * * 0 /path/to/php /path/to/project/bin/console app:send-weekly-stats
```

## 🗂️ Project Structure

```
news-portal/
├── src/
│   ├── Controller/          # Controllers for public and admin interfaces
│   ├── Entity/             # Doctrine entities (News, Category, Comment)
│   ├── Form/               # Symfony forms
│   ├── Repository/         # Database repositories
│   └── Command/            # Console commands
├── templates/              # Twig templates
│   ├── admin/             # Admin interface templates
│   ├── home/              # Public home page
│   ├── news/              # News detail pages
│   └── category/          # Category pages
├── public/
│   └── uploads/pictures/   # Uploaded images
├── config/                # Symfony configuration
└── migrations/            # Database migrations
```

## 🔧 Configuration

### Database
- Default: SQLite (`var/data_dev.db`)
- Change `DATABASE_URL` in `.env` for MySQL/PostgreSQL

### Email
- Update `MAILER_DSN` in `.env` for email functionality
- Configure `STATS_EMAIL` for weekly statistics recipient

### File Uploads
- Directory: `public/uploads/pictures/`
- Max size: 5MB
- Allowed types: JPEG, PNG, GIF

## 🎨 Customization

### Styling
- Bootstrap 5 is used for responsive design
- Custom CSS can be added to `templates/base.html.twig`
- Admin interface has its own styling in `templates/admin/base.html.twig`

### Adding Features
- Create new controllers in `src/Controller/`
- Add new entities in `src/Entity/`
- Create migrations: `php bin/console doctrine:migrations:diff`

## 🔒 Security

- Admin authentication via Symfony Security
- CSRF protection on forms
- File upload validation
- SQL injection protection via Doctrine ORM

## 📱 Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive design
- HTML5 and CSS3 features

## 🐛 Troubleshooting

### Common Issues

1. **Database connection errors**
   - Check `DATABASE_URL` in `.env`
   - Ensure database file permissions for SQLite

2. **File upload errors**
   - Check `public/uploads/pictures/` directory permissions
   - Verify PHP upload settings (`upload_max_filesize`, `post_max_size`)

3. **Admin login issues**
   - Use credentials: admin/admin123
   - Clear cache: `php bin/console cache:clear`

### Development Commands

```bash
# Clear cache
php bin/console cache:clear

# Update database schema
php bin/console doctrine:schema:update --force

# Create new migration
php bin/console doctrine:migrations:diff

# List all routes
php bin/console debug:router
```

## 📄 License

This project is open source and available under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📞 Support

For issues and questions:
- Check the troubleshooting section
- Review Symfony documentation
- Create an issue in the project repository

## Image Setup

After pulling the project and running `composer install`, you need to set up the images for news articles:

1. Place the original images in `public/uploads/pictures/`:
   - `majorana1-1260x708-v2-1024x575-684034d960c9e.jpg` (for AI breakthrough)
   - `2025-Oscars-1030x580-68407d2a59259.jpg` (for World Cup)
   - `5ZD3FGEX2JJU7FZSN2FDIIXFQ4-68407c60b12a6.jpg` (for Climate Summit)
   - `images-68407cc6004a8.jpg` (for Tech Product)
   - `stock-market-data-with-uptrend-vector-68407cab284d6.jpg` (for Economy)

2. Run the image setup command:
   ```bash
   php bin/console app:setup-news-images
   ```

This will copy the original images to the correct filenames used by the fixtures.

## Development

1. Clone the repository
2. Run `composer install`
3. Set up images (see above)
4. Run migrations and load fixtures:
   ```bash
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load
   ```
5. Start the development server:
   ```bash
   symfony server:start
   ``` 