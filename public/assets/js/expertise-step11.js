/**
 * JavaScript para Paso 11 - Fijación Fotográfica (Simple)
 */

(function() {
    'use strict';
    
    // Elementos del DOM
    const step11Form = document.getElementById('step11Form');
    const fotosInput = document.getElementById('fotosInput');
    const fotosContainer = document.getElementById('fotosContainer');
    const contadorFotos = document.getElementById('contadorFotos');
    const numeroFotos = document.getElementById('numeroFotos');
    
    let fotosSeleccionadas = [];
    
    /**
     * Inicializar
     */
    function init() {
        // Event listener cuando se seleccionan archivos
        fotosInput.addEventListener('change', function(e) {
            agregarFotos(e.target.files);
        });
        
        // Validación del formulario
        step11Form.addEventListener('submit', function(event) {
            if (fotosSeleccionadas.length === 0) {
                event.preventDefault();
                alert('Debe seleccionar al menos una fotografía antes de continuar.');
                return false;
            }
            
            // Mostrar mensaje de carga
            const submitBtn = step11Form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Subiendo fotografías...';
        });
    }
    
    /**
     * Agregar fotos seleccionadas
     */
    function agregarFotos(files) {
        // Convertir FileList a Array
        const nuevasFilesArray = Array.from(files);
        
        // Validar y agregar cada archivo
        nuevasFilesArray.forEach(function(file) {
            // Validar que sea imagen
            if (!file.type.match('image.*')) {
                alert('El archivo "' + file.name + '" no es una imagen válida.');
                return;
            }
            
            // Validar tamaño (5MB max)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('El archivo "' + file.name + '" es muy grande. Máximo 5MB.');
                return;
            }
            
            // Verificar si ya existe
            const existe = fotosSeleccionadas.some(f => 
                f.name === file.name && f.size === file.size
            );
            
            if (!existe) {
                fotosSeleccionadas.push(file);
                mostrarPreview(file);
            }
        });
        
        actualizarContador();
    }
    
    /**
     * Mostrar preview de una imagen
     */
    function mostrarPreview(file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Crear elemento de preview
            const col = document.createElement('div');
            col.className = 'col-md-3 col-sm-4 col-6';
            col.setAttribute('data-filename', file.name);
            
            col.innerHTML = `
                <div class="card shadow-sm foto-preview-card">
                    <img src="${e.target.result}" class="card-img-top" alt="${file.name}" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-2">
                        <p class="card-text small text-truncate mb-2" title="${file.name}">
                            <i class="fas fa-image me-1"></i>${file.name}
                        </p>
                        <p class="card-text small text-muted mb-2">
                            ${formatBytes(file.size)}
                        </p>
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarFoto('${file.name}')">
                            <i class="fas fa-trash-alt me-1"></i>Eliminar
                        </button>
                    </div>
                </div>
            `;
            
            fotosContainer.appendChild(col);
        };
        
        reader.readAsDataURL(file);
    }
    
    /**
     * Eliminar foto
     */
    window.eliminarFoto = function(filename) {
        if (confirm('¿Está seguro de eliminar esta fotografía?')) {
            // Eliminar del array
            fotosSeleccionadas = fotosSeleccionadas.filter(f => f.name !== filename);
            
            // Eliminar del DOM
            const col = fotosContainer.querySelector(`[data-filename="${filename}"]`);
            if (col) {
                col.remove();
            }
            
            // Actualizar el input file con DataTransfer
            actualizarInputFile();
            actualizarContador();
        }
    };
    
    /**
     * Actualizar el input file con las fotos actuales
     */
    function actualizarInputFile() {
        const dt = new DataTransfer();
        fotosSeleccionadas.forEach(file => dt.items.add(file));
        fotosInput.files = dt.files;
    }
    
    /**
     * Actualizar contador de fotos
     */
    function actualizarContador() {
        const total = fotosSeleccionadas.length;
        
        if (total > 0) {
            contadorFotos.style.display = 'block';
            numeroFotos.textContent = total;
            
            // Cambiar color según cantidad
            contadorFotos.className = 'alert text-center';
            if (total >= 8) {
                contadorFotos.classList.add('alert-success');
            } else if (total >= 4) {
                contadorFotos.classList.add('alert-primary');
            } else {
                contadorFotos.classList.add('alert-warning');
            }
        } else {
            contadorFotos.style.display = 'none';
        }
    }
    
    /**
     * Formatear bytes a tamaño legible
     */
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
