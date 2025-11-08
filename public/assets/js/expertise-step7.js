/**
 * JavaScript para Paso 7 - Inspección de Amortiguadores
 */

(function() {
    'use strict';
    
    // Elementos del DOM
    const step7Form = document.getElementById('step7Form');
    const inputs = [
        document.getElementById('amortiguador_anterior_izquierdo'),
        document.getElementById('amortiguador_anterior_derecho'),
        document.getElementById('amortiguador_posterior_izquierdo'),
        document.getElementById('amortiguador_posterior_derecho')
    ].filter(input => input !== null); // Filtrar solo los inputs que existen (motos tienen solo 2)
    
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
        step7Form.addEventListener('submit', function(event) {
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
            
            if (!step7Form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            step7Form.classList.add('was-validated');
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
     * Actualizar indicador visual del estado del amortiguador
     */
    function actualizarIndicadorEstado(input) {
        if (!input) return;
        
        const valor = parseInt(input.value);
        
        if (isNaN(valor)) return;
        
        // Remover clases anteriores
        input.classList.remove('border-success', 'border-warning', 'border-danger');
        
        // Agregar clase según el estado
        if (valor >= 80) {
            input.classList.add('border-success');
            input.style.borderWidth = '2px';
        } else if (valor >= 50) {
            input.classList.add('border-success');
            input.style.borderWidth = '2px';
        } else if (valor >= 30) {
            input.classList.add('border-warning');
            input.style.borderWidth = '2px';
        } else {
            input.classList.add('border-danger');
            input.style.borderWidth = '2px';
        }
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
