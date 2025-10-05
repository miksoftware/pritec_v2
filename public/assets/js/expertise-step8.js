/**
 * JavaScript para Paso 8 - Inspección de Batería
 */

(function() {
    'use strict';
    
    // Elementos del DOM
    const step8Form = document.getElementById('step8Form');
    const inputs = [
        document.getElementById('prueba_bateria'),
        document.getElementById('prueba_arranque'),
        document.getElementById('carga_bateria')
    ];
    
    /**
     * Inicializar
     */
    function init() {
        // Agregar validación en tiempo real a cada input
        inputs.forEach(function(input) {
            if (input) {
                input.addEventListener('input', function() {
                    validarPorcentaje(this);
                    actualizarIndicadorEstado(this);
                });
                
                input.addEventListener('blur', function() {
                    validarPorcentaje(this);
                });
            }
        });
        
        // Validación del formulario
        step8Form.addEventListener('submit', function(event) {
            let valido = true;
            
            // Validar todos los campos
            inputs.forEach(function(input) {
                if (!validarPorcentaje(input)) {
                    valido = false;
                }
            });
            
            if (!valido) {
                event.preventDefault();
                alert('Por favor, corrija los valores de porcentaje (deben estar entre 0 y 100)');
                return false;
            }
            
            if (!step8Form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            step8Form.classList.add('was-validated');
        });
    }
    
    /**
     * Validar que el porcentaje esté entre 0 y 100
     */
    function validarPorcentaje(input) {
        if (!input) return true;
        
        const valor = parseInt(input.value);
        
        if (isNaN(valor) || valor < 0 || valor > 100) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            return false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            return true;
        }
    }
    
    /**
     * Actualizar indicador visual del estado según el tipo de prueba
     */
    function actualizarIndicadorEstado(input) {
        if (!input) return;
        
        const valor = parseInt(input.value);
        
        if (isNaN(valor)) return;
        
        // Remover clases anteriores
        input.classList.remove('border-success', 'border-warning', 'border-danger');
        
        const inputId = input.id;
        
        // Diferentes rangos según el tipo de prueba
        if (inputId === 'prueba_bateria') {
            // Prueba de batería: 90-100 excelente, 70-89 bueno, 50-69 regular, 0-49 malo
            if (valor >= 90) {
                input.classList.add('border-success');
            } else if (valor >= 70) {
                input.classList.add('border-success');
            } else if (valor >= 50) {
                input.classList.add('border-warning');
            } else {
                input.classList.add('border-danger');
            }
        } else if (inputId === 'prueba_arranque') {
            // Prueba de arranque: 80-100 óptimo, 60-79 aceptable, 40-59 bajo, 0-39 crítico
            if (valor >= 80) {
                input.classList.add('border-success');
            } else if (valor >= 60) {
                input.classList.add('border-success');
            } else if (valor >= 40) {
                input.classList.add('border-warning');
            } else {
                input.classList.add('border-danger');
            }
        } else if (inputId === 'carga_bateria') {
            // Carga de batería: 80-100 completa, 60-79 media, 40-59 baja, 0-39 necesita recarga
            if (valor >= 80) {
                input.classList.add('border-success');
            } else if (valor >= 60) {
                input.classList.add('border-success');
            } else if (valor >= 40) {
                input.classList.add('border-warning');
            } else {
                input.classList.add('border-danger');
            }
        }
        
        input.style.borderWidth = '2px';
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
