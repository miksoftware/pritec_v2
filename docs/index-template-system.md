# Sistema de Plantillas Index - Documentación

## Resumen
Se implementó un sistema de plantillas unificado para normalizar todas las vistas index del sistema, garantizando consistencia visual y funcional.

## Archivos Principales

### 1. Helper Principal
- **Archivo**: `app/helpers/index_helpers.php`
- **Función**: `renderIndexView($config)`
- **Propósito**: Renderiza vistas index estandarizadas con configuración declarativa

### 2. CSS de Plantillas
- **Archivo**: `public/assets/css/index-template.css`
- **Propósito**: Estilos unificados para todas las vistas index
- **Características**: Stats cards, filtros, tablas, paginación, estados vacíos

## Configuración de Vista Index

### Estructura Básica
```php
renderIndexView([
    'title' => 'Título de la Vista',
    'subtitle' => 'Descripción de la funcionalidad',
    'icon' => 'fas fa-icon',
    'module_name' => 'nombre_modulo',
    'show_stats' => true/false,
    'show_filters' => true/false,
    'data' => $data_array,
    'columns' => [...],
    'actions' => [...],
    'empty_message' => 'Mensaje cuando no hay datos',
    'empty_description' => 'Descripción del estado vacío',
    'create_url' => 'URL para crear nuevo elemento',
    'create_text' => 'Texto del botón crear',
    'pagination' => $pagination_object,
    'custom_scripts' => '<script>...</script>'
]);
```

## Tipos de Columnas Soportados

### 1. Tipos Básicos
- **id**: Muestra ID con estilo muted
- **text**: Texto plano escapado
- **number**: Número formateado
- **currency**: Moneda con formato $X,XXX.XX
- **date**: Fecha formato dd/mm/yyyy
- **datetime**: Fecha y hora formato dd/mm/yyyy HH:mm

### 2. Tipos Especiales
- **user**: Avatar + nombre + email opcional
- **contact**: Teléfono + dirección (truncada)
- **vehicle_type**: Badge con ícono según tipo (carro/moto)
- **description**: Texto con truncado y tooltip
- **status**: Badge con colores según estado
- **actions**: Botones de acción configurables

### 3. Configuración de Columnas
```php
'columns' => [
    [
        'title' => 'Título de Columna',
        'field' => 'campo_base_datos',
        'type' => 'tipo_columna',
        // Opciones específicas según tipo
        'subtitle_field' => 'campo_subtitulo',
        'email_field' => 'campo_email',
        'address_field' => 'campo_direccion',
        'max_length' => 50,
        'status_map' => [
            'active' => ['class' => 'success', 'text' => 'Activo'],
            'inactive' => ['class' => 'danger', 'text' => 'Inactivo']
        ]
    ]
]
```

## Botones de Acción

### Configuración
```php
'buttons' => [
    [
        'url' => APP_URL . 'modulo/accion/{id}',
        'class' => 'btn-outline-primary',
        'icon' => 'fas fa-edit',
        'title' => 'Editar',
        'onclick' => 'funcionJS({id}, \'{campo}\')',
        'condition' => 'status_toggle' // Para botones condicionales
    ]
]
```

### Condiciones Especiales
- **status_toggle**: Cambia ícono/clase según estado activo/inactivo

## Filtros

### Tipos Soportados
```php
'filters' => [
    [
        'name' => 'search',
        'label' => 'Buscar',
        'type' => 'text',
        'placeholder' => 'Texto placeholder...',
        'width' => '6' // Columnas Bootstrap
    ],
    [
        'name' => 'status',
        'label' => 'Estado',
        'type' => 'select',
        'width' => '3',
        'options' => [
            'value1' => 'Texto 1',
            'value2' => 'Texto 2'
        ]
    ],
    [
        'name' => 'fecha',
        'label' => 'Fecha',
        'type' => 'date',
        'width' => '3'
    ]
]
```

## Stats Cards

### Configuración
```php
'stats' => [
    [
        'title' => 'Total Elementos',
        'value' => $count,
        'color' => 'primary', // primary, success, info, warning, danger
        'icon' => 'fas fa-users'
    ]
]
```

## Vistas Normalizadas

### 1. Usuarios (`app/views/users/index.php`)
- ✅ Convertido a nueva plantilla
- **Características**: Stats cards, filtros, tabla de usuarios con roles
- **Funciones JS**: editUser, deleteUser, toggleUserStatus

### 2. Clientes (`app/views/clients/index.php`)
- ✅ Convertido a nueva plantilla
- **Características**: Stats cards, filtros, contacto con dirección
- **Funciones JS**: toggleClientStatus, deleteClient

### 3. Tipos de Vehículos (`app/views/vehicle_types/index.php`)
- ✅ Convertido a nueva plantilla
- **Características**: Tabla simple, badges por tipo, estados
- **Funciones JS**: toggleStatus, deleteVehicleType

## Beneficios del Sistema

### 1. Consistencia Visual
- Todas las vistas index siguen el mismo patrón
- Estilos unificados con el diseño minimalista
- Comportamientos predecibles

### 2. Mantenibilidad
- Código DRY (Don't Repeat Yourself)
- Cambios centralizados en helpers
- Fácil adición de nuevos tipos de columna

### 3. Escalabilidad
- Sistema extensible para nuevos módulos
- Configuración declarativa
- Reutilización de componentes

### 4. Experiencia de Usuario
- Carga rápida con CSS optimizado
- Animaciones suaves y consistentes
- Responsive design integrado

## Próximos Pasos

1. **Nuevos Módulos**: Usar `renderIndexView()` para todas las nuevas vistas index
2. **Tipos Personalizados**: Añadir nuevos tipos de columna según necesidades
3. **Optimización**: Lazy loading para tablas grandes
4. **Accesibilidad**: Mejoras ARIA y navegación por teclado

## Archivos Modificados

- `app/helpers/autoload.php` - Incluye index_helpers
- `app/helpers/index_helpers.php` - Funciones de plantilla
- `public/assets/css/index-template.css` - Estilos unificados
- `app/views/users/index.php` - Normalizado
- `app/views/clients/index.php` - Normalizado  
- `app/views/vehicle_types/index.php` - Normalizado
