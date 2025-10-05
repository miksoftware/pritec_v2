/**
 * JavaScript para Paso 4 - Inspección Visual Interna (Estructura)
 */

(function() {
    'use strict';
    
    // Variables globales
    let piezasDisponibles = [];
    let conceptosDisponibles = [];
    
    // Elementos del DOM
    const tbody = document.getElementById('tbodyInspeccion');
    const agregarFilaBtn = document.getElementById('agregarFilaBtn');
    const template = document.getElementById('filaInspeccionTemplate');
    const loadingPieces = document.getElementById('loadingPieces');
    const step4Form = document.getElementById('step4Form');
    
    /**
     * Inicializar
     */
    function init() {
        // Verificar que tengamos el ID del tipo de vehículo
        if (!VEHICLE_TYPE_ID || VEHICLE_TYPE_ID === null) {
            alert('Error: No se pudo obtener el tipo de vehículo. Por favor, regrese al paso 2.');
            return;
        }
        
        // Cargar piezas y conceptos
        cargarDatos();
        
        // Event listener para agregar fila
        agregarFilaBtn.addEventListener('click', agregarFila);
        
        // Validación del formulario
        step4Form.addEventListener('submit', function(event) {
            const filas = tbody.querySelectorAll('.fila-inspeccion');
            
            if (filas.length === 0) {
                event.preventDefault();
                alert('Debe agregar al menos una inspección de pieza');
                return false;
            }
            
            // Validar que todas las filas tengan pieza y concepto seleccionados
            let valido = true;
            filas.forEach(function(fila) {
                const piezaSelect = fila.querySelector('.pieza-select');
                const conceptoSelect = fila.querySelector('.concepto-select');
                
                if (!piezaSelect.value || !conceptoSelect.value) {
                    valido = false;
                }
            });
            
            if (!valido) {
                event.preventDefault();
                alert('Todas las filas deben tener una pieza y un concepto seleccionados');
                return false;
            }
            
            if (!step4Form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            step4Form.classList.add('was-validated');
        });
    }
    
    /**
     * Cargar piezas y conceptos desde el servidor
     */
    async function cargarDatos() {
        showLoading();
        
        try {
            // Cargar piezas de estructura
            const piezasResponse = await fetch(
                `${APP_URL}expertise/get-pieces-by-vehicle-type?vehicle_type_id=${VEHICLE_TYPE_ID}&section=estructura`
            );
            const piezasData = await piezasResponse.json();
            
            if (!piezasData.success) {
                throw new Error(piezasData.message || 'Error al cargar piezas');
            }
            
            piezasDisponibles = piezasData.pieces;
            
            // Cargar conceptos de estructura
            const conceptosResponse = await fetch(
                `${APP_URL}expertise/get-inspection-concepts?category=estructura`
            );
            const conceptosData = await conceptosResponse.json();
            
            if (!conceptosData.success) {
                throw new Error(conceptosData.message || 'Error al cargar conceptos');
            }
            
            conceptosDisponibles = conceptosData.concepts;
            
            hideLoading();
            
            // Agregar primera fila automáticamente
            agregarFila();
            
        } catch (error) {
            hideLoading();
            alert('Error al cargar datos: ' + error.message);
        }
    }
    
    /**
     * Agregar nueva fila a la tabla
     */
    function agregarFila() {
        // Clonar template
        const clone = template.content.cloneNode(true);
        const fila = clone.querySelector('.fila-inspeccion');
        
        // Obtener selects
        const piezaSelect = fila.querySelector('.pieza-select');
        const conceptoSelect = fila.querySelector('.concepto-select');
        
        // Llenar select de piezas
        piezasDisponibles.forEach(function(pieza) {
            const option = document.createElement('option');
            option.value = pieza.id;
            option.textContent = `${pieza.piece_number}. ${pieza.piece_name}`;
            piezaSelect.appendChild(option);
        });
        
        // Llenar select de conceptos
        conceptosDisponibles.forEach(function(concepto) {
            const option = document.createElement('option');
            option.value = concepto.id;
            option.textContent = concepto.name;
            conceptoSelect.appendChild(option);
        });
        
        // Event listener para botón eliminar
        const eliminarBtn = fila.querySelector('.eliminar-fila-btn');
        eliminarBtn.addEventListener('click', function() {
            eliminarFila(fila);
        });
        
        // Agregar fila al tbody
        tbody.appendChild(fila);
        
        // Animación de entrada
        fila.style.opacity = '0';
        setTimeout(() => {
            fila.style.transition = 'opacity 0.3s';
            fila.style.opacity = '1';
        }, 10);
    }
    
    /**
     * Eliminar fila de la tabla
     */
    function eliminarFila(fila) {
        // Confirmar si hay más de una fila
        const totalFilas = tbody.querySelectorAll('.fila-inspeccion').length;
        
        if (totalFilas === 1) {
            alert('Debe mantener al menos una fila para la inspección');
            return;
        }
        
        // Animación de salida
        fila.style.transition = 'opacity 0.3s';
        fila.style.opacity = '0';
        
        setTimeout(() => {
            fila.remove();
        }, 300);
    }
    
    /**
     * Mostrar loading
     */
    function showLoading() {
        if (loadingPieces) {
            loadingPieces.style.display = 'block';
        }
        agregarFilaBtn.disabled = true;
    }
    
    /**
     * Ocultar loading
     */
    function hideLoading() {
        if (loadingPieces) {
            loadingPieces.style.display = 'none';
        }
        agregarFilaBtn.disabled = false;
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
