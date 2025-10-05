# Paso 2 del Peritaje Completo - Documentación

## Descripción General
El Paso 2 permite buscar y seleccionar el cliente para el cual se está realizando el peritaje.

## Archivos Creados/Modificados

### 1. Vista: `app/views/expertise/step2.php`
- Muestra un formulario de búsqueda de clientes
- Incluye indicador de progreso (Paso 2 de 11 - 18%)
- Muestra los datos guardados del Paso 1
- Búsqueda en tiempo real con AJAX
- Selección visual del cliente con toda su información

### 2. Controlador: `app/controllers/ExpertiseController.php`
**Métodos añadidos:**
- `step2()`: Muestra la vista del paso 2 (verifica que existan datos del paso 1)
- `searchClients()`: Endpoint AJAX para buscar clientes por nombre, cédula, RUC, teléfono o email
- `saveStep2()`: Guarda el cliente seleccionado en sesión y redirige al paso 3

**Método modificado:**
- `store()`: Ahora guarda los datos del paso 1 en `$_SESSION['expertise_step1']` y redirige a `step2`

### 3. JavaScript: `public/assets/js/expertise-step2.js`
**Funcionalidades:**
- Búsqueda en tiempo real (dispara búsqueda después de 500ms de inactividad)
- Búsqueda requiere mínimo 3 caracteres
- Muestra resultados en lista interactiva
- Permite seleccionar un cliente con un click
- Valida selección antes de permitir continuar
- Función de "Cambiar Cliente" para rehacer la selección
- Sanitización de HTML para prevenir XSS

### 4. Rutas: `index.php`
**Rutas añadidas:**
```php
$router->get('/expertise/step2', 'ExpertiseController@step2');
$router->get('/expertise/search-clients', 'ExpertiseController@searchClients');
$router->post('/expertise/save-step2', 'ExpertiseController@saveStep2');
```

## Flujo de Trabajo

### 1. Usuario completa Paso 1
- Datos guardados en `$_SESSION['expertise_step1']`
- Redirigido a `/expertise/step2`

### 2. Usuario llega al Paso 2
- Se verifica que existan datos del paso 1
- Se muestra resumen del paso 1 (fecha, número de servicio, etc.)
- Se muestra campo de búsqueda de cliente

### 3. Usuario busca cliente
- Escribe al menos 3 caracteres
- JavaScript hace petición AJAX a `/expertise/search-clients?search=...`
- Backend busca en tabla `clients` por:
  - Nombre (first_name)
  - Apellido (last_name)
  - Cédula/RUC (id_number)
  - Teléfono (phone)
  - Email (email)
- Retorna máximo 20 resultados

### 4. Usuario selecciona cliente
- Click en botón "Seleccionar" o en el item completo
- JavaScript muestra información del cliente seleccionado
- Se habilita botón "Continuar al Paso 3"
- Campo hidden `client_id` se llena con el ID del cliente

### 5. Usuario continua al Paso 3
- Submit del formulario a `/expertise/save-step2`
- Backend guarda en `$_SESSION['expertise_step2']` el `client_id`
- Redirige a paso 3 (pendiente de implementar)

## Estructura de Sesión

```php
$_SESSION['expertise_step1'] = [
    'service_date' => '2025-10-04',
    'service_number' => 'S-2025-001',
    'service_for' => 'Vehículos El Carmen',
    'agreement' => 'Convenio A'
];

$_SESSION['expertise_step2'] = [
    'client_id' => 123
];
```

## Seguridad
- Validación CSRF en todos los formularios
- Escape de HTML en resultados de búsqueda (prevención XSS)
- Validación de sesión antes de mostrar paso 2
- Solo busca clientes activos (status = 1)

## Próximos Pasos
- [ ] Implementar Paso 3: Datos del Vehículo
- [ ] Diseñar y crear tablas de base de datos para peritajes
- [ ] Implementar guardado real en BD (actualmente todo en sesión)

## URLs de Prueba
- Paso 1: http://localhost/pritec_v2/expertise/create
- Paso 2: http://localhost/pritec_v2/expertise/step2 (requiere completar paso 1)
- Búsqueda AJAX: http://localhost/pritec_v2/expertise/search-clients?search=juan

## Notas Técnicas
- La búsqueda usa LIKE con wildcards: `%search%`
- El límite de resultados es 20 clientes
- La búsqueda es case-insensitive (MySQL default)
- El campo de búsqueda tiene autocomplete="off" para evitar sugerencias del navegador
