# Componente Content Header - Guía de Uso

## Descripción
El componente `content-header` es un header reutilizable que incluye título, subtítulo, breadcrumbs y botones de acción. Se puede usar en cualquier vista para mantener consistencia en el diseño.

## Función Principal: `renderContentHeader()`

### Sintaxis Básica
```php
renderContentHeader('Título de la Página', [
    'subtitle' => 'Descripción opcional',
    'icon' => 'fas fa-icon-name',
    'breadcrumbs' => [...],
    'actions' => [...]
]);
```

## Ejemplos de Uso

### 1. Header Simple
```php
<?php
renderContentHeader('Mi Página', [
    'icon' => 'fas fa-home'
]);
?>
```

### 2. Header con Subtítulo
```php
<?php
renderContentHeader('Gestión de Productos', [
    'subtitle' => 'Administra todos los productos del inventario',
    'icon' => 'fas fa-boxes'
]);
?>
```

### 3. Header con Breadcrumbs
```php
<?php
renderContentHeader('Editar Producto', [
    'icon' => 'fas fa-edit',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Productos', 'url' => APP_URL . 'products'],
        ['text' => 'Editar Producto', 'url' => null]
    ])
]);
?>
```

### 4. Header con Botones de Acción
```php
<?php
renderContentHeader('Lista de Ventas', [
    'subtitle' => 'Historial de todas las ventas',
    'icon' => 'fas fa-shopping-cart',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Ventas', 'url' => null]
    ]),
    'actions' => [
        createHeaderAction('Exportar PDF', APP_URL . 'sales/export-pdf', [
            'icon' => 'fas fa-file-pdf',
            'class' => 'btn-outline-danger'
        ]),
        createHeaderAction('Nueva Venta', APP_URL . 'sales/create', [
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary'
        ])
    ]
]);
?>
```

### 5. Header con Botón JavaScript
```php
<?php
renderContentHeader('Dashboard', [
    'subtitle' => 'Panel de control principal',
    'icon' => 'fas fa-tachometer-alt',
    'actions' => [
        createHeaderAction('Actualizar Datos', null, [
            'icon' => 'fas fa-sync',
            'class' => 'btn-outline-info',
            'onclick' => 'refreshDashboard()'
        ])
    ]
]);
?>
```

## Funciones Helper

### `createBreadcrumbs($items)`
Crea breadcrumbs automáticamente incluyendo "Inicio" como primer elemento.

```php
$breadcrumbs = createBreadcrumbs([
    ['text' => 'Categoría', 'url' => APP_URL . 'categories'],
    ['text' => 'Subcategoría', 'url' => APP_URL . 'categories/1'],
    ['text' => 'Elemento Actual', 'url' => null] // null = activo
]);
```

### `createHeaderAction($text, $url, $options)`
Crea un botón de acción para el header.

```php
// Botón con enlace
$action = createHeaderAction('Nuevo Item', APP_URL . 'items/create', [
    'icon' => 'fas fa-plus',
    'class' => 'btn-primary',
    'title' => 'Crear nuevo elemento'
]);

// Botón con JavaScript
$action = createHeaderAction('Eliminar Todo', null, [
    'icon' => 'fas fa-trash',
    'class' => 'btn-danger',
    'onclick' => 'deleteAll()',
    'title' => 'Eliminar todos los elementos'
]);
```

## Opciones Disponibles

### Para `renderContentHeader()`
- **title** (string): Título principal (requerido)
- **subtitle** (string): Subtítulo opcional
- **icon** (string): Clase del icono (ej: 'fas fa-users')
- **breadcrumbs** (array): Array de breadcrumbs
- **actions** (array): Array de botones de acción

### Para `createHeaderAction()`
- **text** (string): Texto del botón (requerido)
- **url** (string|null): URL del enlace (null para botones JavaScript)
- **icon** (string): Clase del icono
- **class** (string): Clases CSS adicionales (default: 'btn-primary')
- **title** (string): Texto del atributo title
- **onclick** (string): Código JavaScript para eventos click

## Clases CSS Disponibles para Botones
- `btn-primary` (azul)
- `btn-secondary` (gris)
- `btn-success` (verde)
- `btn-danger` (rojo)
- `btn-warning` (amarillo)
- `btn-info` (cian)
- `btn-light` (blanco)
- `btn-dark` (negro)
- `btn-outline-*` (versiones outline de los colores)

## Componente de Tarjeta: `renderContentCard()`

También puedes usar el helper para crear tarjetas consistentes:

```php
<?php
// Preparar contenido
ob_start();
?>
<p>Contenido de la tarjeta aquí...</p>
<?php
$content = ob_get_clean();

// Renderizar tarjeta
renderContentCard('Título de la Tarjeta', $content, [
    'icon' => 'fas fa-list',
    'header_actions' => [
        createHeaderAction('Acción', APP_URL . 'action', [
            'icon' => 'fas fa-cog',
            'class' => 'btn-sm btn-outline-primary'
        ])
    ]
]);
?>
```

## Migración de Vistas Existentes

### Antes:
```php
<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">
            <i class="fas fa-users me-2"></i>
            Gestión de Usuarios
        </h1>
        <a href="<?= APP_URL ?>users/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Nuevo Usuario
        </a>
    </div>
</div>
```

### Después:
```php
<?php
renderContentHeader('Gestión de Usuarios', [
    'icon' => 'fas fa-users',
    'actions' => [
        createHeaderAction('Nuevo Usuario', APP_URL . 'users/create', [
            'icon' => 'fas fa-plus'
        ])
    ]
]);
?>
```

## Responsive
El componente es completamente responsive:
- En desktop: Título a la izquierda, acciones a la derecha
- En móvil: Todo apilado verticalmente, botones ocupan el ancho completo
