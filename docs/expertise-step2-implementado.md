# Paso 2 del Peritaje Completo - Datos del Vehículo

## 📋 Descripción General
El Paso 2 permite seleccionar el tipo de vehículo y completar todos los datos técnicos y administrativos del vehículo a inspeccionar.

---

## 📁 Archivos Implementados

### 1. `app/views/expertise/step2.php` ✅
**Funcionalidades:**
- Muestra resumen del Paso 1 (fecha, servicio, aseguradora, cliente)
- Búsqueda de tipos de vehículos activos
- Selección visual del tipo de vehículo (Carro/Moto)
- Formulario completo con 19 campos de datos del vehículo
- Validación de campos requeridos (tipo de vehículo y placa)
- Botón submit deshabilitado hasta completar requisitos

**Indicador de progreso:**
- Paso 2 de 10 (20% completado)
- Muestra paso 1 como completado (✓)

### 2. `public/assets/js/expertise-step2-new.js` ✅
**Funcionalidades JavaScript:**
- Búsqueda en tiempo real de tipos de vehículos (500ms debounce)
- Muestra todos los tipos disponibles al hacer clic en "Buscar"
- Selección de tipo con información visual (íconos diferentes para carro/moto)
- Muestra/oculta secciones dinámicamente
- Botón "Cambiar Tipo" para modificar selección
- Habilita submit solo cuando hay tipo y placa
- Validación antes de enviar formulario
- Auto-focus en campo placa después de seleccionar tipo

### 3. `app/controllers/ExpertiseController.php` ✅
**Métodos añadidos:**

- **`step2()`**: Muestra la vista del paso 2
  - Verifica que existan datos del paso 1
  - Genera CSRF token
  - Carga la vista step2

- **`searchVehicleTypes()`**: Búsqueda AJAX de tipos de vehículos
  - Busca por nombre o descripción
  - Si no hay búsqueda, muestra todos (límite 50)
  - Solo tipos activos (`status = 'active'`)
  - Retorna JSON con resultados

- **`saveStep2()`**: Guarda datos del paso 2
  - Valida CSRF token
  - Verifica que exista paso 1
  - Valida tipo de vehículo y placa (requeridos)
  - Guarda 19 campos en `$_SESSION['expertise_step2']`
  - Redirige al paso 3 (temporalmente al mismo paso)

**Método modificado:**
- **`store()`**: Ahora redirige a `expertise/step2` en lugar de volver a `create`

### 4. `index.php` ✅
**Rutas añadidas:**
```php
$router->get('/expertise/step2', 'ExpertiseController@step2');
$router->get('/expertise/search-vehicle-types', 'ExpertiseController@searchVehicleTypes');
$router->post('/expertise/save-step2', 'ExpertiseController@saveStep2');
```

---

## 📝 Campos del Formulario

### Campos Requeridos (*)
1. **Tipo de Vehículo** - Selección desde búsqueda
2. **Placa** - Identificación del vehículo

### Campos Opcionales
3. Clase
4. Marca
5. Línea
6. Cilindraje
7. Servicio
8. Modelo
9. Color
10. No de Chasis
11. No de Motor
12. No de Serie
13. Tipo de Carrocería
14. Organismo de Tránsito
15. Kilometraje (numérico)
16. Código Fasecolda
17. Valor Fasecolda ($)
18. Valor Sugerido ($)
19. Valor Accesorios ($)

---

## 🗄️ Tabla `vehicle_types`

```sql
CREATE TABLE IF NOT EXISTS `vehicle_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('carro','moto'),
  `name` varchar(100),
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
);
```

**Datos de ejemplo:**
- ID: 2, Type: carro, Name: Veterinaria, Status: active

---

## 🔄 Flujo de Trabajo

### 1. Usuario completa Paso 1
- Datos guardados en `$_SESSION['expertise_step1']`
- Redirigido automáticamente a `/expertise/step2`

### 2. Usuario llega al Paso 2
- Ve resumen del paso 1 en alerta azul
- Ve búsqueda de tipo de vehículo
- Al cargar, se muestran todos los tipos disponibles automáticamente

### 3. Usuario selecciona tipo de vehículo
Opción A: **Buscar específicamente**
- Escribe en el campo de búsqueda
- Se filtran los resultados en tiempo real

Opción B: **Ver todos**
- Hace clic en botón "Buscar" (sin escribir nada)
- Se muestran todos los tipos activos

### 4. Usuario hace clic en "Seleccionar"
- Se oculta la búsqueda
- Se muestra tarjeta verde con tipo seleccionado
- Aparece botón "Cambiar Tipo"
- Se muestra formulario de 19 campos
- Auto-focus en campo "Placa"

### 5. Usuario completa datos
- **Placa** es requerida (botón submit deshabilitado hasta llenarla)
- Resto de campos son opcionales
- Puede usar "Cambiar Tipo" para seleccionar otro

### 6. Usuario envía formulario
- Submit a `/expertise/save-step2`
- Backend valida tipo y placa
- Guarda en `$_SESSION['expertise_step2']`
- Redirige al paso 3 (pendiente de implementar)

---

## 💾 Estructura de Sesión

```php
// Paso 1 (ya existente)
$_SESSION['expertise_step1'] = [
    'service_date' => '2025-10-04',
    'service_number' => 'S-2025-001',
    'service_for' => 'Seguros La Fortaleza',
    'agreement' => 'CONV-2025-A',
    'client_id' => 1
];

// Paso 2 (nuevo)
$_SESSION['expertise_step2'] = [
    'tipo_vehiculo' => 2,              // ID del tipo seleccionado
    'placa' => 'ABC123',               // REQUERIDO
    'clase' => 'Automóvil',
    'marca' => 'Toyota',
    'linea' => 'Corolla',
    'cilindraje' => '1800',
    'servicio' => 'Particular',
    'modelo' => '2020',
    'color' => 'Blanco',
    'no_chasis' => 'CH123456789',
    'no_motor' => 'MT987654321',
    'no_serie' => 'SR555666777',
    'tipo_carroceria' => 'Sedan',
    'organismo_transito' => 'Tránsito Municipal',
    'kilometraje' => '45000',
    'codigo_fasecolda' => 'F123456',
    'valor_fasecolda' => '50000000',
    'valor_sugerido' => '48000000',
    'valor_accesorios' => '2000000'
];
```

---

## 🎨 Características de UI/UX

### Indicadores Visuales
- ✅ **Paso 1 Completado**: Marca verde con check
- 🔵 **Paso 2 Activo**: Círculo azul
- ⚪ **Pasos Siguientes**: Círculos grises

### Íconos por Tipo
- 🚗 **Carro**: `fa-car` + Badge azul
- 🏍️ **Moto**: `fa-motorcycle` + Badge amarillo

### Comportamiento del Botón Submit
- **Deshabilitado** (gris) hasta que:
  1. Se seleccione un tipo de vehículo
  2. Se complete el campo "Placa"
- **Habilitado** (azul) cuando se cumplen ambos requisitos

### Búsqueda Inteligente
- Muestra **todos** los tipos al cargar la página
- Filtra en tiempo real al escribir
- Sin límite mínimo de caracteres
- Máximo 50 resultados

---

## ✅ Validaciones Implementadas

### Frontend (JavaScript)
- ✅ Tipo de vehículo debe estar seleccionado
- ✅ Placa debe tener contenido
- ✅ Botón submit deshabilitado hasta cumplir requisitos
- ✅ Validación HTML5 en campo placa (required)

### Backend (PHP)
- ✅ Verificación CSRF token
- ✅ Verificación de existencia del paso 1
- ✅ Validación de tipo de vehículo (requerido)
- ✅ Validación de placa (requerida)
- ✅ Todos los demás campos son opcionales

---

## 🧪 Cómo Probar

### 1. Completar Paso 1
```
http://localhost/pritec_v2/expertise/create
```
- Llenar todos los campos
- Buscar y seleccionar un cliente
- Click en "Siguiente"

### 2. Automáticamente se abre Paso 2
```
http://localhost/pritec_v2/expertise/step2
```

### 3. Verificar carga automática
- ✅ Debe mostrar tipos de vehículos disponibles sin hacer clic

### 4. Seleccionar tipo
- Click en "Seleccionar" de algún tipo
- ✅ Debe ocultarse búsqueda
- ✅ Debe aparecer formulario
- ✅ Debe hacer focus en "Placa"

### 5. Completar datos
- **Placa**: ABC123 (requerido)
- Resto: opcional
- ✅ Botón "Continuar" debe habilitarse

### 6. Enviar formulario
- Click en "Continuar al Paso 3"
- ✅ Debe guardar en sesión
- ✅ Debe redirigir (temporalmente al mismo paso)

### 7. Verificar sesión
```php
print_r($_SESSION['expertise_step2']);
```

---

## 🔐 Seguridad

- ✅ Tokens CSRF en todos los formularios
- ✅ Validación de sesión
- ✅ Solo tipos de vehículos activos
- ✅ Prepared statements (prevención SQL injection)
- ✅ Escape HTML en resultados

---

## 📊 Progreso del Wizard

```
✅ Paso 1/10: Información del Servicio y Cliente (COMPLETADO)
✅ Paso 2/10: Datos del Vehículo (COMPLETADO)
⏳ Paso 3/10: Inspección Carrocería (PENDIENTE)
⏳ Paso 4/10: Inspección Estructura (PENDIENTE)
⏳ Paso 5/10: Inspección Chasis (PENDIENTE)
⏳ Paso 6/10: Inspección Llantas (PENDIENTE)
⏳ Paso 7/10: Scanner (PENDIENTE)
⏳ Paso 8/10: Motor (PENDIENTE)
⏳ Paso 9/10: Fugas (PENDIENTE)
⏳ Paso 10/10: Resumen Final (PENDIENTE)
```

---

## 🐛 Posibles Problemas y Soluciones

### Problema: No se muestran tipos de vehículos
**Causa**: No hay tipos activos en la BD
**Solución**: Insertar tipos de vehículos con status='active'

### Problema: Error al buscar tipos
**Causa**: Tabla `vehicle_types` no existe
**Solución**: Ejecutar migration correspondiente

### Problema: Botón "Continuar" no se habilita
**Causa**: Campo placa vacío o tipo no seleccionado
**Solución**: Completar ambos campos requeridos

---

## 📝 Próximos Pasos

1. **Implementar Paso 3: Inspección Carrocería**
   - Formulario de inspección visual
   - Carga de fotos
   - Sistema de puntos/calificación

2. **Diseñar tablas de base de datos**
   - Tabla principal `expertises`
   - Tablas de inspecciones por categoría

3. **Implementar guardado final**
   - Al completar wizard, guardar en BD
   - Generar PDF del peritaje

---

## 🎯 Resumen de Funcionalidades

| Funcionalidad | Estado |
|--------------|--------|
| Búsqueda de tipos de vehículo | ✅ |
| Selección visual de tipo | ✅ |
| Formulario de 19 campos | ✅ |
| Validación de campos requeridos | ✅ |
| Guardado en sesión | ✅ |
| Navegación Paso 1 ↔ Paso 2 | ✅ |
| UI/UX con íconos y badges | ✅ |
| Botón "Cambiar Tipo" | ✅ |
| Auto-carga de tipos al iniciar | ✅ |
| Resumen del Paso 1 | ✅ |

---

**Estado del Paso 2: ✅ COMPLETO Y FUNCIONAL**
