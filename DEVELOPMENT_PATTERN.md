# PRITEC V2.0 - PATRÓN DE DESARROLLO

## 📋 ARQUITECTURA GENERAL

### **Estructura MVC Implementada**
```
pritec_v2/
├── app/
│   ├── config/          # Configuración de la aplicación
│   │   ├── database.php # Conexión a base de datos
│   │   └── config.php   # Configuraciones generales
│   │
│   ├── core/           # Núcleo del framework MVC
│   │   ├── Router.php  # Manejo de rutas
│   │   ├── Controller.php # Controlador base
│   │   └── Model.php   # Modelo base
│   │
│   ├── controllers/    # Controladores de la aplicación
│   │   ├── AuthController.php    # Autenticación
│   │   ├── DashboardController.php # Dashboard
│   │   └── UserController.php    # 🆕 CRUD de usuarios
│   │
│   ├── models/         # Modelos de datos
│   │   └── User.php    # Modelo de usuario
│   │
│   └── views/          # Vistas de la aplicación
│       ├── layouts/    # Layouts principales
│       │   └── app.php # Layout principal del sistema
│       │
│       ├── components/ # Componentes reutilizables
│       │   └── sidebar.php # Sidebar del admin panel
│       │
│       ├── auth/       # Vistas de autenticación
│       │   ├── login.php
│       │   └── register.php
│       │
│       ├── users/      # 🆕 Vistas CRUD de usuarios
│       │   ├── index.php   # Listado de usuarios
│       │   ├── create.php  # Crear usuario
│       │   └── edit.php    # Editar usuario
│       │
│       └── dashboard/  # Vistas del dashboard
│           └── index.php
│
├── public/             # Archivos públicos
│   ├── index.php      # Punto de entrada
│   │
│   └── assets/        # Recursos estáticos
│       ├── css/       # Hojas de estilo
│       │   ├── style.css    # Estilos principales
│       │   ├── sidebar.css  # Estilos del sidebar
│       │   ├── main.css     # Estilos globales
│       │   └── users.css    # 🆕 Estilos del CRUD usuarios
│       │
│       └── js/        # Scripts JavaScript
│           ├── app.js       # Funciones principales
│           ├── sidebar.js   # Funciones del sidebar
│           └── main.js      # Funciones globales
│
└── database.sql       # Esquema de base de datos
```

## 🎯 PRINCIPIOS DE DESARROLLO

### **1. SEPARACIÓN DE RESPONSABILIDADES**
- **CSS**: Cada componente tiene su propio archivo CSS
- **JavaScript**: Funciones separadas por responsabilidad
- **PHP**: MVC estricto, cada clase con una responsabilidad

### **2. MODULARIDAD**
- Componentes reutilizables en `/views/components/`
- Layouts separados en `/views/layouts/`
- Assets organizados por tipo y función

### **3. MANTENIBILIDAD**
- Código limpio sin duplicaciones
- Archivos pequeños y específicos
- Nomenclatura consistente y descriptiva

### **4. ESCALABILIDAD**
- Estructura preparada para crecimiento
- Componentes independientes
- Fácil agregar nuevas funcionalidades

## 🔧 PATRONES IMPLEMENTADOS

### **Patrón MVC**
```php
// Controlador
class DashboardController extends Controller {
    public function index() {
        // Lógica de negocio
        $data = $this->processData();
        // Cargar vista
        $this->view('dashboard/index', $data);
    }
}

// Modelo
class User extends Model {
    // Interacción con base de datos
    public function findById($id) { ... }
}

// Vista
<?php
ob_start();
// HTML del contenido
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
```

### **Patrón de Componentes**
```php
// Layout principal incluye componentes
<?php include APP_PATH . '/views/components/sidebar.php'; ?>

// CSS y JS modulares
<link href="<?= ASSETS_URL ?>css/main.css" rel="stylesheet">
<script src="<?= ASSETS_URL ?>js/main.js"></script>
```

### **Patrón de Configuración**
```php
// Variables globales disponibles
define('APP_URL', 'http://localhost/pritec_v2/');
define('ASSETS_URL', APP_URL . 'assets/');
define('APP_PATH', __DIR__ . '/../app');
```

## 📝 CONVENCIONES DE CÓDIGO

### **Archivos PHP**
- Nombres en PascalCase para clases: `UserController.php`
- Nombres en snake_case para vistas: `login.php`
- Nombres en kebab-case para assets: `main.css`

### **CSS**
- Variables CSS en `:root` para colores y medidas
- Comentarios separadores para secciones
- BEM methodology para clases específicas

### **JavaScript**
- Funciones globales en `window` object
- JSDoc comments para documentación
- Event listeners en DOMContentLoaded

## 🔒 CARACTERÍSTICAS DE SEGURIDAD

### **Autenticación**
- Hash de contraseñas con `password_hash()`
- Validación de sesiones
- Tokens CSRF en formularios

### **Base de Datos**
- Prepared statements en todas las consultas
- Validación de entrada
- Escape de salida con `htmlspecialchars()`

### **Archivos**
- Protección de directorios con `.htaccess`
- Separación de lógica y presentación
- Variables de entorno para configuración sensible

## 🎨 ESTÁNDARES DE UI/UX

### **Framework CSS**
- Bootstrap 5 como base
- Variables CSS custom para theming
- Componentes responsivos

### **Iconografía**
- Font Awesome 6 para iconos
- Consistencia en el uso de iconos
- Accesibilidad con aria-labels

### **Notificaciones**
- SweetAlert2 para alertas y confirmaciones
- Toast notifications para feedback
- Estados de carga y error

## 📱 RESPONSIVE DESIGN

### **Breakpoints**
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

### **Sidebar Behavior**
- Desktop: Siempre visible, colapsible
- Mobile: Overlay, oculto por defecto
- Toggle button siempre accesible

## 🔄 FLUJO DE DESARROLLO

### **Agregar Nueva Funcionalidad**
1. Crear modelo si es necesario (`/app/models/`)
2. Crear controlador (`/app/controllers/`)
3. Crear vistas (`/app/views/`)
4. Agregar CSS específico (`/public/assets/css/`)
5. Agregar JS específico (`/public/assets/js/`)
6. Actualizar rutas en Router
7. Probar funcionalidad

### **Modificar Componente Existente**
1. Identificar archivos involucrados
2. Mantener separación de responsabilidades
3. Actualizar solo archivos necesarios
4. Verificar no romper otras funcionalidades

## 🚀 PRÓXIMAS FUNCIONALIDADES

### **Módulos Planificados**
- Gestión de Peritajes
- Gestión de Clientes
- Sistema de Citas
- Generación de Reportes
- Configuración del Sistema
- Gestión de Usuarios (Admin)

### **Mejoras Técnicas**
- API REST para funciones CRUD
- Sistema de permisos granular
- Cache de consultas frecuentes
- Logs de actividad del sistema
- Backup automático de base de datos

---

**Desarrollado con PHP 8.3 + MySQL + Bootstrap 5**
*Manteniendo siempre los principios de código limpio y arquitectura escalable*
