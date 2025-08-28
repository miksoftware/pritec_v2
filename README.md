# Pritec v2.0 - Sistema de Peritajes

Sistema web moderno para gestión de peritajes desarrollado con PHP 8.3, MySQL y arquitectura MVC.

## 🚀 Características

- **Arquitectura MVC**: Estructura organizada y mantenible
- **PHP 8.3**: Aprovecha las últimas características del lenguaje
- **MySQL**: Base de datos robusta y confiable
- **Bootstrap 5**: Interfaz moderna y responsiva
- **SweetAlert2**: Notificaciones elegantes y confirmaciones
- **Sistema de Autenticación**: Login/registro seguro
- **Gestión de Sesiones**: Control de acceso y seguridad
- **Historial de Logins**: Registro de accesos por usuario
- **Estados de Usuario**: Activación/desactivación de cuentas

## 📋 Requisitos

- PHP 8.3 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensiones PHP: PDO, PDO_MySQL, mbstring, openssl

## 🛠 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [repositorio] pritec_v2
   cd pritec_v2
   ```

2. **Configurar la base de datos**
   - Crear la base de datos MySQL
   - Importar el archivo `database.sql`
   - Ajustar la configuración en `app/config/database.php`

3. **Configurar el servidor web**
   - Configurar el document root hacia la carpeta `public/`
   - Habilitar mod_rewrite (Apache) o configurar rewrites (Nginx)

4. **Configurar la aplicación**
   - Revisar y ajustar `app/config/config.php`
   - Configurar la URL base en `APP_URL`

## 🎯 Uso

### Acceso por defecto:
- **Usuario**: admin@pritec.com
- **Contraseña**: admin123

### Rutas principales:
- `/` - Redirección automática
- `/login` - Iniciar sesión
- `/register` - Registrarse
- `/dashboard` - Panel principal

## 📁 Estructura del Proyecto

```
pritec_v2/
├── app/
│   ├── config/          # Configuraciones
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers/     # Controladores MVC
│   │   ├── AuthController.php
│   │   └── DashboardController.php
│   ├── core/           # Núcleo del framework
│   │   ├── Controller.php
│   │   ├── Model.php
│   │   └── Router.php
│   ├── models/         # Modelos de datos
│   │   └── User.php
│   ├── views/          # Vistas
│   │   ├── auth/
│   │   ├── dashboard/
│   │   └── layouts/
│   └── middleware/     # Middlewares (futuro)
├── public/             # Archivos públicos
│   └── assets/
│       ├── css/
│       └── js/
├── database.sql        # Estructura de BD
├── index.php          # Punto de entrada
└── README.md
```

## 🔧 Arquitectura

### MVC Pattern
- **Models**: Gestión de datos y lógica de negocio
- **Views**: Presentación y interfaz de usuario
- **Controllers**: Lógica de aplicación y coordinación

### Características de Seguridad
- Hash de contraseñas con `password_hash()`
- Protección CSRF
- Validación de entrada
- Sesiones seguras
- Prepared statements

### Base de Datos
- **users**: Información de usuarios
- **user_login_logs**: Historial de accesos
- **system_settings**: Configuraciones del sistema

## 🚦 Estados del Usuario

- **active**: Usuario activo, puede acceder
- **inactive**: Usuario desactivado, no puede acceder

## 📊 Funcionalidades Implementadas

### ✅ Completado
- [x] Sistema de autenticación (login/logout)
- [x] Registro de usuarios
- [x] Dashboard básico
- [x] Gestión de sesiones
- [x] Historial de logins
- [x] Estados de usuario
- [x] Validaciones de formularios
- [x] Notificaciones con SweetAlert2
- [x] Interfaz responsiva

### 🔄 Próximamente
- [ ] Gestión de peritajes
- [ ] Reportes
- [ ] Configuraciones de usuario
- [ ] Panel de administración
- [ ] Exportación de datos
- [ ] API REST

## 🎨 Tecnologías Utilizadas

- **Backend**: PHP 8.3
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Framework CSS**: Bootstrap 5
- **Icons**: Font Awesome 6
- **Notifications**: SweetAlert2
- **HTTP Client**: Fetch API

## 🔒 Seguridad

- Protección contra SQL Injection (PDO Prepared Statements)
- Protección CSRF
- Hash seguro de contraseñas
- Validación de entrada
- Gestión segura de sesiones
- Headers de seguridad HTTP

## 🐛 Debugging

Para habilitar el modo de desarrollo:
```php
// En app/config/config.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📝 Convenciones de Código

- PSR-4 para autoloading
- CamelCase para clases
- snake_case para métodos y variables
- Comentarios en español
- Validación de entrada obligatoria

## 🤝 Contribución

1. Fork el proyecto
2. Crear rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👥 Autores

- **Desarrollador Principal** - *Desarrollo inicial* - Tu Nombre

## 📞 Soporte

Para soporte técnico o consultas:
- Email: soporte@pritec.com
- Issues: [GitHub Issues](enlace-a-issues)

---

**Pritec v2.0** - Sistema de Peritajes Moderno 🏢
