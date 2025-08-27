# Personal Portfolio Website

A responsive personal portfolio website built with PHP, Bootstrap, and Cloudinary integration for image management.

## Features

- **Responsive Design**: Built with Bootstrap for mobile-first responsive design
- **Multi-language Support**: Vietnamese and English language support
- **Admin Panel**: Complete admin dashboard for content management
- **Cloudinary Integration**: Image upload and management with Cloudinary
- **Contact Form**: Contact form with email functionality
- **Project Showcase**: Dynamic project gallery with image uploads

## Tech Stack

- **Backend**: PHP (MVC Architecture)
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Image Management**: Cloudinary API
- **Email**: PHPMailer
- **Database**: MySQL (implied from structure)

## Project Structure

```
personal-portfolio/
├── app/
│   ├── controllers/     # MVC Controllers
│   ├── models/         # Data models
│   ├── views/          # View templates
│   └── lang/           # Language files
├── public/             # Public assets
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   └── image/          # Images
├── config/             # Configuration files
├── helpers/            # Helper functions
└── vendor/             # Composer dependencies
```

## Setup Instructions

1. Clone the repository
2. Install PHP dependencies: `composer install`
3. Configure your database settings in `config/config.php`
4. Set up Cloudinary credentials
5. Import database schema from `database_update.sql`
6. Configure web server to point to the project directory

## Requirements

- PHP 7.4 or higher
- MySQL/MariaDB
- Composer
- Cloudinary account (for image management)

## License

This project is open source and available under the MIT License.
