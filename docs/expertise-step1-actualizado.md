# Paso 1 del Peritaje Completo - ACTUALIZADO

## 🔄 Cambios Realizados

### Problema Original
- Se tenía un Paso 1 solo para información del servicio
- Un Paso 2 separado para búsqueda de cliente
- Error de base de datos: columnas `client_id` e `id_number` no existían (son `id` e `identification`)

### Solución Implementada
- **Integración**: Paso 1 ahora incluye TANTO la información del servicio COMO la búsqueda y selección del cliente
- **Optimización**: Reducción de 11 pasos a 10 pasos totales
- **Corrección**: Nombres de columnas actualizados correctamente

---

## 📁 Archivos Actualizados

### 1. `app/views/expertise/create.php` ✅
**Cambios:**
- Título actualizado: "Paso 1 de 10: Información del Servicio y Cliente"
- Campo "Servicio Para" clarificado como "Aseguradora/Entidad"
- Integrada búsqueda de clientes con AJAX en tiempo real
- Campo hidden `client_id` para enviar ID del cliente seleccionado
- Botón submit deshabilitado hasta que se seleccione un cliente
- Validación en frontend y backend

**Campos del formulario:**
1. Fecha del Servicio (date) - Requerido
2. Número de Servicio (text) - Requerido
3. Servicio Para (text) - Aseguradora/Entidad - Requerido
4. Número de Convenio (text) - Opcional
5. **Cliente** (búsqueda + selección) - Requerido

### 2. `app/controllers/ExpertiseController.php` ✅
**Métodos actualizados:**
- `store()`: Ahora valida y guarda `client_id` junto con los demás datos
- `searchClients()`: Corregido para usar columnas correctas (`id`, `identification`, `status='active'`)

**Métodos eliminados:**
- ~~`step2()`~~ - Ya no es necesario
- ~~`saveStep2()`~~ - Ya no es necesario

**Estructura de sesión:**
```php
$_SESSION['expertise_step1'] = [
    'service_date' => '2025-10-04',
    'service_number' => 'S-2025-001',
    'service_for' => 'Seguros La Fortaleza',
    'agreement' => 'CONV-2025-A',
    'client_id' => 1  // ← NUEVO: ID del cliente seleccionado
];
```

### 3. `public/assets/js/expertise-step1.js` ✅
**Nuevo archivo creado** (basado en expertise-step2.js)

**Funcionalidades:**
- Búsqueda en tiempo real con debounce de 500ms
- Requiere mínimo 3 caracteres
- Muestra resultados con información completa del cliente
- Permite seleccionar un cliente
- Deshabilita botón submit hasta que se seleccione cliente
- Función "Cambiar Cliente" para modificar selección
- Validación antes de enviar formulario
- Sanitización HTML para prevenir XSS

**Nombres de columnas corregidos:**
- `client.id` (antes: `client.client_id`)
- `client.identification` (antes: `client.id_number`)

### 4. `index.php` ✅
**Rutas eliminadas:**
```php
// ❌ Eliminadas
// $router->get('/expertise/step2', 'ExpertiseController@step2');
// $router->post('/expertise/save-step2', 'ExpertiseController@saveStep2');
```

**Rutas mantenidas:**
```php
// ✅ Activas
$router->get('/expertise', 'ExpertiseController@index');
$router->get('/expertise/create', 'ExpertiseController@create');
$router->post('/expertise/store', 'ExpertiseController@store');
$router->get('/expertise/search-clients', 'ExpertiseController@searchClients');
```

### 5. Archivos que YA NO se usan:
- ❌ `app/views/expertise/step2.php` - Ya no es necesario
- ❌ `public/assets/js/expertise-step2.js` - Reemplazado por step1.js
- ❌ `docs/expertise-step2.md` - Obsoleto

---

## 🗄️ Estructura de Base de Datos

### Tabla `clients`
```sql
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,          -- ← IMPORTANTE: se llama 'id', no 'client_id'
  `first_name` varchar(100),
  `last_name` varchar(100),
  `identification` varchar(50),              -- ← IMPORTANTE: se llama 'identification', no 'id_number'
  `phone` varchar(20),
  `email` varchar(255),
  `address` text,
  `status` enum('active','inactive','deleted') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Correcciones aplicadas:**
- ✅ `id` en lugar de `client_id`
- ✅ `identification` en lugar de `id_number`
- ✅ `status = 'active'` en lugar de `status = 1`

---

## 🔄 Flujo de Trabajo Actualizado

### Paso 1: Usuario llega a crear peritaje
- URL: `http://localhost/pritec_v2/expertise/create`
- Ve formulario con 4 campos + búsqueda de cliente

### Paso 2: Usuario completa campos básicos
- Fecha del servicio (pre-llenada con fecha actual)
- Número de servicio
- Aseguradora/Entidad
- Convenio (opcional)

### Paso 3: Usuario busca cliente
- Escribe en el campo de búsqueda (mínimo 3 caracteres)
- Se dispara búsqueda AJAX automáticamente después de 500ms
- Backend busca en tabla `clients` por nombre, apellido, identificación, teléfono o email

### Paso 4: Usuario selecciona cliente
- Click en botón "Seleccionar" de algún resultado
- Se muestra tarjeta verde con información del cliente seleccionado
- Se habilita botón "Siguiente: Datos del Vehículo"
- Campo hidden `client_id` se llena con el ID

### Paso 5: Usuario envía formulario
- Submit del form a `/expertise/store`
- Backend valida todos los campos incluido `client_id`
- Se guarda todo en `$_SESSION['expertise_step1']`
- Redirige (temporalmente al mismo paso hasta que se implemente Paso 2)

---

## ✅ Validaciones Implementadas

### Frontend (JavaScript)
- ✅ Cliente debe estar seleccionado antes de submit
- ✅ Botón submit deshabilitado hasta selección
- ✅ Validación HTML5 de campos requeridos

### Backend (PHP)
- ✅ Verificación CSRF token
- ✅ Validación de campos requeridos: `service_date`, `service_number`, `service_for`, `client_id`
- ✅ Campo `agreement` es opcional

---

## 🎯 Progreso de Pasos

### Actualización del indicador de progreso
```
Antes: Paso 1 de 11 (9%)
Ahora: Paso 1 de 10 (10%)
```

### Nuevos 10 pasos:
1. ✅ Información del Servicio y Cliente (IMPLEMENTADO)
2. ⏳ Datos del Vehículo (PENDIENTE - antes era Paso 3)
3. ⏳ Inspección Carrocería
4. ⏳ Inspección Estructura
5. ⏳ Inspección Chasis
6. ⏳ Inspección Llantas
7. ⏳ Scanner
8. ⏳ Motor
9. ⏳ Fugas
10. ⏳ Resumen Final

---

## 🧪 Cómo Probar

1. **Ir al formulario:**
   ```
   http://localhost/pritec_v2/expertise/create
   ```

2. **Completar campos básicos:**
   - Fecha: (viene pre-llenada)
   - Número: S-2025-001
   - Servicio Para: Seguros XYZ
   - Convenio: (opcional)

3. **Buscar cliente:**
   - Escribir "Carmen" (o cualquier nombre/cédula)
   - Ver resultados aparecer automáticamente
   - Click en "Seleccionar"

4. **Verificar:**
   - ✅ Tarjeta verde aparece mostrando cliente seleccionado
   - ✅ Botón "Siguiente" se habilita
   - ✅ Click en "Siguiente" guarda todo correctamente

5. **Verificar en sesión:**
   ```php
   print_r($_SESSION['expertise_step1']);
   // Debe mostrar: service_date, service_number, service_for, agreement, client_id
   ```

---

## 🐛 Problemas Resueltos

### 1. ✅ Error: "Column not found: 1054 Unknown column 'client_id'"
**Causa:** La tabla `clients` usa `id`, no `client_id`
**Solución:** Actualizado en `searchClients()` query

### 2. ✅ Error: "Column not found: 1054 Unknown column 'id_number'"
**Causa:** La tabla usa `identification`, no `id_number`
**Solución:** Actualizado en query y JavaScript

### 3. ✅ Flujo innecesariamente largo
**Causa:** Paso 2 completo solo para seleccionar cliente
**Solución:** Integrado en Paso 1, reduciendo total a 10 pasos

### 4. ✅ Confusión sobre "Servicio Para"
**Causa:** Campo poco claro
**Solución:** Renombrado a "Servicio Para (Aseguradora/Entidad)" con ejemplo

---

## 📝 Próximos Pasos

1. **Implementar Paso 2: Datos del Vehículo**
   - Marca, modelo, año
   - Placa, VIN
   - Color, tipo de vehículo
   - Kilometraje

2. **Diseñar esquema de base de datos**
   - Tabla `expertises` (principal)
   - Tablas relacionadas para cada inspección

3. **Implementar guardado real en BD**
   - Actualmente todo está en sesión
   - Necesita guardarse al final del wizard

---

## 🔐 Seguridad

- ✅ Tokens CSRF en todos los formularios
- ✅ Escape HTML en resultados (prevención XSS)
- ✅ Validación de sesión
- ✅ Solo busca clientes activos
- ✅ Prepared statements (prevención SQL injection)

---

## 📞 Soporte

Si encuentras algún error:
1. Verifica que la tabla `clients` tenga datos activos
2. Revisa la consola del navegador (F12) para errores JavaScript
3. Verifica que `APP_URL` esté correctamente configurado
4. Asegúrate de que la sesión esté iniciada
