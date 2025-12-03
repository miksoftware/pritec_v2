# Project Structure

```
pritec_v2/
├── index.php              # Entry point, route definitions
├── .htaccess              # Apache rewrite rules, security headers
├── app/
│   ├── config/
│   │   ├── config.php     # App constants (APP_URL, paths, session)
│   │   └── database.php   # Database class with PDO connection
│   ├── core/
│   │   ├── Controller.php # Base controller (view, json, redirect, validation, CSRF)
│   │   ├── Model.php      # Base model (CRUD, fillable fields)
│   │   └── Router.php     # Simple router with GET/POST and params
│   ├── controllers/       # Feature controllers extending Controller
│   ├── models/            # Data models extending Model
│   ├── views/
│   │   ├── layouts/       # Main layout templates
│   │   ├── components/    # Reusable view components
│   │   ├── auth/          # Login, register views
│   │   ├── dashboard/     # Dashboard views
│   │   ├── clients/       # Client CRUD views
│   │   ├── expertise/     # Multi-step inspection views (step1-12)
│   │   ├── users/         # User management views
│   │   └── vehicle_types/ # Vehicle config views
│   ├── helpers/           # Utility functions (view_helpers, index_helpers)
│   └── middleware/        # (Reserved for future use)
├── public/assets/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files (per-feature)
│   ├── img/               # Static images
│   ├── uploads/           # User uploads
│   └── expertises/        # Expertise-related uploads
└── database/migrations/   # SQL migration files
```

## Conventions

### Routing
- Routes defined in `index.php` using `$router->get()` / `$router->post()`
- Format: `'ControllerName@methodName'`
- Route params: `/resource/{id}` extracted automatically

### Controllers
- Extend `Controller` base class
- Check auth in constructor: `$_SESSION['logged_in']`
- Use `$this->view('folder/file', $data)` for rendering
- Use `$this->json($data)` for API responses
- CSRF: `$this->generateCSRFToken()` / `$this->verifyCSRFToken()`

### Models
- Extend `Model` base class
- Set `$table` and `$fillable` properties
- Use `$this->db->query($sql, $params)` for queries
- Validation in model methods, throw `Exception` on error

### Views
- Plain PHP templates with `extract($data)`
- Include layouts manually
- Use helpers from `app/helpers/`

### JavaScript
- Per-feature files: `expertise-step{N}.js`
- Use Fetch API for AJAX
- SweetAlert2 for confirmations/notifications
