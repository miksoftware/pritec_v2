# Instrucciones para Generación de PDF

## 🎯 Configuración Completada

Se ha implementado la generación de PDF para peritajes usando **mPDF v8.2.6**.

## 📋 Archivos Creados/Modificados

1. **`app/helpers/pdf_generator.php`** - Helper con funciones para generar PDFs
2. **`app/controllers/ExpertiseController.php`** - Métodos `generatePDF()` y `previewPDF()` agregados
3. **`index.php`** - Rutas `/expertise/pdf/{id}` y `/expertise/preview-pdf/{id}` agregadas

## 🖼️ Logo

- **Ubicación**: `public/assets/img/logo.png`
- **Formato**: PNG con fondo transparente (recomendado)
- **Tamaño**: ~300x100 px
- **Nota**: Si no existe el logo, se muestra un placeholder temporal

## 🚀 Cómo Probar

### Opción 1: Desde el Índice de Peritajes
1. Ve a: `http://localhost/pritec_v2/expertise`
2. Busca cualquier peritaje completado
3. Haz clic en el botón rojo con icono de PDF 📄
4. El PDF se abrirá en el navegador

### Opción 2: URL Directa
```
http://localhost/pritec_v2/expertise/pdf/1
```
(Reemplaza `1` con el ID de un peritaje existente)

### Opción 3: Vista Previa (Desarrollo)
```
http://localhost/pritec_v2/expertise/preview-pdf/1
```

## 📄 Contenido Actual del PDF

### Encabezado (en todas las páginas):
- ✅ Logo de PRITEC (izquierda)
- ✅ Título: "SALA TÉCNICA EN AUTOMOTORES"
- ✅ Subtítulo: "INFORME TÉCNICO"
- ✅ Fecha del servicio
- ✅ No. de Servicio
- ✅ Servicio para
- ✅ Convenio

### Pie de Página (en todas las páginas):
- ✅ Dirección: Carrera 16 No. 18-197 Barrio Tenerife
- ✅ Teléfono: 3132049245-3158928492
- ✅ Web: peritos.pritec.co
- ✅ Texto: "Peritos e inspecciones técnicas vehiculares Neiva-Huila"

### Contenido Primera Página:
- ✅ Información del Vehículo (Placa, Tipo, Marca, Línea, Modelo, Color)
- ✅ Información del Cliente (Nombre, Documento, Teléfono)

## 📝 Próximos Pasos

1. **Agregar el logo real** en `public/assets/img/logo.png`
2. **Completar contenido del PDF** con:
   - Inspecciones de carrocería
   - Inspecciones de estructura
   - Inspecciones de chasis
   - Estado de llantas
   - Estado de amortiguadores
   - Pruebas de batería
   - Motor y sistemas
   - Fugas y niveles
   - Fotos del vehículo
   - Conclusiones y recomendaciones

## 🔧 Personalización

El diseño del PDF se puede personalizar editando las funciones en `app/helpers/pdf_generator.php`:

- `generatePDFHeader()` - Encabezado
- `generatePDFFooter()` - Pie de página  
- `generateFirstPage()` - Contenido de la primera página
- Agregar más funciones para páginas adicionales

## 🐛 Solución de Problemas

### El PDF no se genera
- Verifica que mPDF esté instalado: `vendor/mpdf/mpdf`
- Revisa logs de PHP: `C:\laragon\logs\`

### El logo no aparece
- Verifica que el archivo exista en `public/assets/img/logo.png`
- Verifica permisos de lectura del archivo

### Error de permisos
- En Windows con Laragon, normalmente no hay problemas de permisos
- Si hay errores, verifica que la carpeta `vendor` tenga permisos de lectura

## ✅ Estado Actual

- ✅ Librería mPDF instalada
- ✅ Helper de generación creado
- ✅ Rutas configuradas
- ✅ Métodos en controlador
- ✅ Botón en interfaz de peritajes
- ✅ Encabezado y pie de página funcionales
- ⏳ Contenido completo del peritaje (próximo paso)
