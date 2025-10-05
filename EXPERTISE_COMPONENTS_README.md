# Componentes Reutilizables del Módulo de Peritajes

Este documento describe los componentes reutilizables creados para el módulo de peritajes completos.

## 📁 Ubicación

Archivo: `app/helpers/expertise_components.php`

## 🎯 Componentes Disponibles

### 1. `renderExpertiseProgressIndicator($current_step)`

Renderiza el indicador de progreso visual para los 12 pasos del peritaje.

**Parámetros:**
- `$current_step` (int): El paso actual (1-12)

**Características:**
- Muestra progreso visual con barra de porcentaje
- Badge con el número de paso actual
- Lista de todos los pasos con iconos
- Código de colores automático:
  - Pasos completados: Verde con ✓
  - Paso actual: Azul con ●
  - Pasos pendientes: Gris con ○
- Colores dinámicos según progreso:
  - Steps 1-6: Azul (bg-primary)
  - Steps 7-10: Cyan (bg-info)
  - Steps 11-12: Verde (bg-success)

**Uso:**
```php
<?php
// Cargar helper
if (!function_exists('renderExpertiseProgressIndicator')) {
    require_once APP_PATH . '/helpers/expertise_components.php';
}

// Renderizar para el paso 3
renderExpertiseProgressIndicator(3);
?>
```

**Resultado visual:**
```
┌────────────────────────────────────────────────────┐
│ 🎯 Progreso del Peritaje         [Paso 3 de 12]  │
│ ▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░ 25%                      │
│ ✓Info ✓Vehículo ●Carrocería ○Estructura ...       │
└────────────────────────────────────────────────────┘
```

---

### 2. `renderPreviousStepSummary($data, $alert_class = 'alert-info')`

Renderiza un resumen compacto de los datos del paso anterior.

**Parámetros:**
- `$data` (array): Array de datos a mostrar
- `$alert_class` (string, opcional): Clase CSS del alert (default: 'alert-info')

**Estructura de $data:**
Cada elemento del array debe tener:
- `label` (string, requerido): Etiqueta del campo
- `value` (string, requerido): Valor a mostrar
- `icon` (string, opcional): Clase de icono Font Awesome
- `col` (int, opcional): Tamaño de columna Bootstrap (default: 3)

**Uso:**
```php
<?php
if (isset($_SESSION['expertise_step1'])) {
    $step1 = $_SESSION['expertise_step1'];
    renderPreviousStepSummary([
        ['label' => 'Fecha', 'value' => $step1['service_date'], 'icon' => 'fas fa-calendar', 'col' => 3],
        ['label' => 'Servicio #', 'value' => $step1['service_number'], 'icon' => 'fas fa-hashtag', 'col' => 3],
        ['label' => 'Cliente', 'value' => $step1['client_name'], 'icon' => 'fas fa-user', 'col' => 6]
    ], 'alert-success');
}
?>
```

**Resultado visual:**
```
┌──────────────────────────────────────────────┐
│ 📅 Fecha: 2025-10-04  │  #️⃣ Servicio: 12345 │
│ 👤 Cliente: Juan Pérez                       │
└──────────────────────────────────────────────┘
```

---

### 3. `renderSectionHeader($title, $subtitle = '', $icon = 'fas fa-clipboard-check', $bg_class = 'bg-dark')`

Renderiza un header consistente para las cards de cada sección.

**Parámetros:**
- `$title` (string): Título de la sección
- `$subtitle` (string, opcional): Subtítulo descriptivo
- `$icon` (string, opcional): Clase del icono (default: 'fas fa-clipboard-check')
- `$bg_class` (string, opcional): Color de fondo (default: 'bg-dark')

**Uso:**
```php
<?php
renderSectionHeader(
    'Datos del Vehículo', 
    'Complete la información del vehículo a inspeccionar',
    'fas fa-car',
    'bg-primary'
);
?>
```

**Resultado visual:**
```
┌────────────────────────────────────────┐
│ 🚗 Datos del Vehículo                  │
│    Complete la información...          │
└────────────────────────────────────────┘
```

---

### 4. `renderStepNavigation($current_step, $next_text = null, $submit_disabled = false)`

Renderiza los botones de navegación estándar entre pasos.

**Parámetros:**
- `$current_step` (int): Paso actual (1-12)
- `$next_text` (string|null, opcional): Texto personalizado del botón siguiente
- `$submit_disabled` (bool, opcional): Si el botón debe estar deshabilitado inicialmente

**Características:**
- Genera automáticamente URLs de navegación
- Texto dinámico según el paso:
  - Paso 11: "Finalizar y Ver Resumen"
  - Paso 12: "Guardar Peritaje Completo"
  - Otros: "Continuar al Paso X"
- Colores automáticos:
  - Pasos 1-10: Botón azul (btn-primary)
  - Pasos 11-12: Botón verde (btn-success)
- Iconos apropiados por contexto

**Uso:**
```php
<!-- Dentro de una card-footer -->
<?php renderStepNavigation(2); ?>

<!-- Con botón deshabilitado inicialmente -->
<?php renderStepNavigation(2, null, true); ?>

<!-- Con texto personalizado -->
<?php renderStepNavigation(5, 'Guardar y Continuar'); ?>
```

**Resultado visual:**
```
┌────────────────────────────────────────────┐
│ ← Volver al Paso 1    [Continuar al Paso 3 →] │
└────────────────────────────────────────────┘
```

---

## 📝 Ejemplo Completo de Uso

```php
<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/expertise.css" rel="stylesheet">

<?php
// Cargar helpers
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}
if (!function_exists('renderExpertiseProgressIndicator')) {
    require_once APP_PATH . '/helpers/expertise_components.php';
}

// Header de la página
renderContentHeader('Nuevo Peritaje Completo', [
    'subtitle' => 'Paso 3 de 12: Inspección de Carrocería',
    'icon' => 'fas fa-clipboard-check',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);

// Indicador de progreso
renderExpertiseProgressIndicator(3);
?>

<!-- Contenido -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            <!-- Resumen del paso anterior -->
            <?php 
            if (isset($_SESSION['expertise_step2'])) {
                renderPreviousStepSummary([
                    ['label' => 'Placa', 'value' => $_SESSION['expertise_step2']['placa'], 'icon' => 'fas fa-car'],
                    ['label' => 'Marca', 'value' => $_SESSION['expertise_step2']['marca'], 'icon' => 'fas fa-tag'],
                    ['label' => 'Modelo', 'value' => $_SESSION['expertise_step2']['modelo'], 'icon' => 'fas fa-calendar']
                ]);
            }
            ?>
            
            <!-- Formulario -->
            <form method="POST" action="<?= APP_URL ?>expertise/save-step3">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm">
                    <?php renderSectionHeader('Inspección de Carrocería', 'Evalúe el estado de las piezas', 'fas fa-car-side'); ?>
                    
                    <div class="card-body p-4">
                        <!-- Contenido del formulario aquí -->
                    </div>
                    
                    <?php renderStepNavigation(3); ?>
                </div>
            </form>
            
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
```

---

## 🎨 Ventajas de Usar Componentes

### ✅ Antes (Código Duplicado):
- 50+ líneas de HTML por vista
- Cambios manuales en 12 archivos
- Inconsistencias visuales
- Difícil mantener sincronizado

### ✅ Después (Con Componentes):
- 1 línea de PHP por componente
- Cambio centralizado
- Consistencia garantizada
- Fácil mantenimiento

**Ejemplo comparativo:**

**Antes:**
```php
<!-- 50 líneas de HTML duplicado -->
<div class="mb-4">
    <div class="card">
        <div class="card-body py-3">
            <!-- ... más código ... -->
        </div>
    </div>
</div>
```

**Después:**
```php
<?php renderExpertiseProgressIndicator(3); ?>
```

---

## 🔄 Actualización Masiva

Para actualizar todos los pasos existentes, ejecuta:

```bash
php update_expertise_steps.php
```

Este script automáticamente:
1. Agrega el require del helper de componentes
2. Actualiza "Paso X de 10" a "Paso X de 12"
3. Reemplaza el HTML del indicador por el componente
4. Mantiene respaldo de los archivos originales

---

## 📚 Pasos Definidos

| # | Nombre | Icono | Descripción |
|---|--------|-------|-------------|
| 1 | Info | fas fa-info-circle | Información del servicio |
| 2 | Vehículo | fas fa-car | Datos del vehículo |
| 3 | Carrocería | fas fa-car-side | Inspección de carrocería |
| 4 | Estructura | fas fa-building | Inspección de estructura |
| 5 | Chasis | fas fa-cogs | Inspección de chasis |
| 6 | Llantas | fas fa-circle | Estado de llantas |
| 7 | Amortiguadores | fas fa-compress-arrows-alt | Estado de amortiguadores |
| 8 | Batería | fas fa-battery-full | Pruebas de batería |
| 9 | Motor | fas fa-cog | Motor y sistemas |
| 10 | Fugas | fas fa-tint | Fugas y niveles |
| 11 | Fotos | fas fa-camera | Fijación fotográfica |
| 12 | Resumen | fas fa-check-circle | Resumen final |

---

## 🛠️ Mantenimiento

Para modificar los pasos globalmente:

1. Edita `app/helpers/expertise_components.php`
2. Modifica el array `$steps` en `renderExpertiseProgressIndicator()`
3. Los cambios se reflejan automáticamente en todos los pasos

**Ejemplo: Agregar un paso 13**

```php
$steps = [
    // ... pasos existentes ...
    13 => ['name' => 'PDF', 'icon' => 'fas fa-file-pdf']
];
```

---

## 📞 Soporte

Para dudas o mejoras, contactar al equipo de desarrollo.

**Versión:** 1.0  
**Última actualización:** Octubre 2025  
**Autor:** miksoftware
