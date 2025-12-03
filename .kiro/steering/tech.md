# Tech Stack

## Backend
- PHP 8.3+
- MySQL 5.7+ with PDO (prepared statements)
- Custom MVC framework (no external PHP framework)
- Apache with mod_rewrite

## Frontend
- Bootstrap 5
- Font Awesome 6
- SweetAlert2 for notifications
- Vanilla JavaScript (ES6+)
- Fetch API for AJAX

## Database
- Database name: `pritec_v2`
- Uses PDO with FETCH_ASSOC mode
- Soft deletes via `status = 'deleted'`
- Timestamps: `created_at`, `updated_at`

## Project URLs
- Base URL: `http://localhost/pritec_v2/`
- Assets: `public/assets/`

## Common Commands

### Database Setup
```bash
# Import database schema
mysql -u root -p pritec_v2 < database.sql
```

### Development Server
```bash
# Using PHP built-in server (from project root)
php -S localhost:8000
```

### File Uploads
- Vehicle sections: `public/assets/uploads/vehicle_sections/`
- Expertise images: `public/assets/expertises/`
