# Laravel Blog CMS

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

<p align="center">
  <em>A modern, feature-rich Content Management System built with Laravel</em>
</p>

## 🚀 Features

### 📝 Content Management
- **Rich Text Editor**: CKEditor 4 integration for content creation
- **Post Management**: Create, edit, delete, and publish blog posts
- **Category System**: Organize posts with hierarchical categories
- **Tag System**: Flexible tagging for better content discovery
- **Featured Images**: Upload and manage post thumbnails
- **SEO Optimization**: Meta titles, descriptions, and URL slugs

### 👥 User Management
- **Authentication**: Secure login/logout system
- **User Roles**: Admin and regular user permissions
- **Email Verification**: Account activation via email
- **Password Reset**: Secure password recovery

### 💬 Comment System
- **Moderation**: Admin approval for comments
- **Spam Detection**: Advanced spam filtering with keyword detection
- **Threaded Comments**: Reply to comments (extensible)
- **Comment Statistics**: Dashboard analytics

### 🎨 Design & UI
- **Responsive Design**: Mobile-first Bootstrap 5 implementation
- **Magazine Layout**: Professional blog appearance
- **Admin Dashboard**: Clean, intuitive admin interface
- **Theme Customization**: CSS variables for easy styling

### 🔒 Security Features
- **CSRF Protection**: Cross-site request forgery prevention
- **XSS Prevention**: Input sanitization and output escaping
- **Rate Limiting**: Brute force attack protection
- **File Upload Security**: Validated file types and sizes
- **SQL Injection Protection**: Eloquent ORM usage

### 📊 Analytics & Management
- **Dashboard Statistics**: Posts, comments, and user metrics
- **Content Search**: Full-text search functionality
- **Content Filtering**: Filter by categories, tags, and status
- **Pagination**: Efficient content browsing

## 🛠️ Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| **Backend** | Laravel | 11.x |
| **Database** | MySQL | 8.0+ |
| **Frontend** | Bootstrap | 5.3.2 |
| **Icons** | Font Awesome | 6.0 |
| **Editor** | CKEditor | 4.22.1 |
| **PHP** | PHP | 8.2+ |
| **Build Tool** | Vite | 5.x |

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Composer**: 2.x
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **MySQL**: 8.0 or higher
- **Apache/Nginx**: Web server
- **PHP Extensions**:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - GD (for image processing)

## 🚀 Installation

### 1. Clone Repository
```bash
git clone https://github.com/your-username/laravel-blog-cms.git
cd laravel-blog-cms
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_cms
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```

### 5. Storage Setup
```bash
# Create storage symlink
php artisan storage:link
```

### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

Your application will be available at `http://localhost:8000`

## 🔐 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@example.com | password |

⚠️ **Important**: Change default credentials immediately after installation!

## 🌐 URLs & Navigation

| Page | URL | Description |
|------|-----|-------------|
| **Homepage** | `/` | Featured posts and blog overview |
| **Blog** | `/blog` | All blog posts with search/filter |
| **Single Post** | `/blog/{slug}` | Individual post view |
| **Category** | `/blog/category/{slug}` | Posts by category |
| **Tag** | `/blog/tag/{slug}` | Posts by tag |
| **About** | `/about` | About page |
| **Contact** | `/contact` | Contact form |
| **Admin Login** | `/login` | Admin authentication |
| **Dashboard** | `/dashboard` | Admin dashboard |

## 🎯 Admin Features

### Post Management (`/admin/posts`)
- Create new posts with rich text editor
- Edit existing posts
- Manage post status (draft/published)
- Upload featured images
- Assign categories and tags
- SEO meta management

### Category Management (`/admin/categories`)
- Create/edit/delete categories
- Category hierarchy management
- SEO-friendly slugs

### Comment Management (`/admin/comments`)
- Approve/reject comments
- Bulk operations
- Spam detection integration
- Comment statistics

### System Settings (`/admin/settings`)
- Site configuration
- Blog title and description
- Contact information
- Social media links

## 🛡️ Security Features

### Implemented Protections
- **CSRF Tokens**: All forms protected
- **Input Validation**: Server-side validation rules
- **XSS Prevention**: Output escaping in templates
- **Rate Limiting**: Login attempts and form submissions
- **File Upload Security**: Type and size validation
- **SQL Injection**: Eloquent ORM protection
- **Password Hashing**: Bcrypt encryption

### Security Headers
```php
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
```

## 📱 Mobile Responsiveness

- **Bootstrap 5**: Mobile-first responsive framework
- **Breakpoints**: 
  - Mobile: < 576px
  - Tablet: 576px - 768px
  - Desktop: 768px - 992px
  - Large: > 992px
- **Touch-friendly**: Optimized for touch interactions
- **Performance**: Optimized images and assets

## 🔧 Configuration

### Key Configuration Files
- **Environment**: `.env`
- **Database**: `config/database.php`
- **Mail**: `config/mail.php`
- **Filesystems**: `config/filesystems.php`
- **Cache**: `config/cache.php`

### Important Settings
```env
# Application
APP_NAME="Laravel Blog CMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=local
```

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificate
- [ ] Configure mail settings
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set proper file permissions
- [ ] Configure cron jobs for scheduled tasks

### Apache Configuration
```apache
<VirtualHost *:80>
    DocumentRoot "/var/www/html/blog/public"
    ServerName yourdomain.com
    <Directory "/var/www/html/blog/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/html/blog/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=PostTest
```

## 📈 Performance Optimization

### Implemented Optimizations
- **Database Indexing**: Optimized queries
- **Eager Loading**: Reduced N+1 queries
- **Image Optimization**: Proper sizing and compression
- **Asset Bundling**: Vite for efficient asset compilation
- **Caching**: Route, config, and view caching

### Cache Commands
```bash
# Clear all caches
php artisan optimize:clear

# Cache for production
php artisan optimize
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Ensure mobile responsiveness
- Test across different browsers

## 📝 Changelog

### Version 1.0.0 (Current)
- ✅ Initial release
- ✅ Complete blog CMS functionality
- ✅ Admin dashboard
- ✅ User authentication
- ✅ Comment system with spam detection
- ✅ Security hardening
- ✅ Responsive design
- ✅ Rich text editor integration

## 🐛 Known Issues

- None currently reported

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/your-username/laravel-blog-cms/issues)
- **Documentation**: This README and inline code comments
- **Laravel Docs**: [Laravel Documentation](https://laravel.com/docs)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Team**: For the amazing framework
- **Bootstrap Team**: For the responsive CSS framework
- **CKEditor Team**: For the rich text editor
- **Font Awesome**: For the icon library
- **Contributors**: Thanks to all contributors who helped improve this project

---

<p align="center">
  Made with ❤️ using Laravel
</p>
