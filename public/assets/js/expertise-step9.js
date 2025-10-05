/**
 * JavaScript para Paso 9 - Motor y Sistemas
 */

(function() {
    'use strict';
    
    // Elementos del DOM
    const step9Form = document.getElementById('step9Form');
    
    /**
     * Inicializar
     */
    function init() {
        // Validación del formulario
        step9Form.addEventListener('submit', function(event) {
            // Verificar que todos los selects de estado tengan un valor
            const selects = step9Form.querySelectorAll('select[name^="estado_"], select[name^="tension_"]');
            let todosCompletos = true;
            let primerVacio = null;
            
            selects.forEach(function(select) {
                if (!select.value || select.value === '') {
                    todosCompletos = false;
                    select.classList.add('is-invalid');
                    if (!primerVacio) {
                        primerVacio = select;
                    }
                } else {
                    select.classList.remove('is-invalid');
                    select.classList.add('is-valid');
                }
            });
            
            if (!todosCompletos) {
                event.preventDefault();
                alert('Por favor, complete el estado de todos los sistemas antes de continuar.');
                if (primerVacio) {
                    primerVacio.focus();
                    primerVacio.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            
            if (!step9Form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            step9Form.classList.add('was-validated');
        });
        
        // Agregar validación en tiempo real a los selects
        const selects = step9Form.querySelectorAll('select[name^="estado_"], select[name^="tension_"]');
        selects.forEach(function(select) {
            select.addEventListener('change', function() {
                if (this.value && this.value !== '') {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }
            });
        });
        
        // Contador de campos completados
        actualizarContador();
        step9Form.addEventListener('change', actualizarContador);
    }
    
    /**
     * Actualizar contador de progreso
     */
    function actualizarContador() {
        const selects = step9Form.querySelectorAll('select[name^="estado_"], select[name^="tension_"]');
        const total = selects.length;
        let completados = 0;
        
        selects.forEach(function(select) {
            if (select.value && select.value !== '') {
                completados++;
            }
        });
        
        const porcentaje = Math.round((completados / total) * 100);
        
        // Mostrar contador si existe
        let contador = document.getElementById('contadorProgreso');
        if (!contador) {
            contador = document.createElement('div');
            contador.id = 'contadorProgreso';
            contador.className = 'alert alert-info mt-3';
            const cardBody = step9Form.querySelector('.card-body');
            cardBody.insertBefore(contador, cardBody.firstChild);
        }
        
        contador.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="fas fa-tasks me-2"></i><strong>Progreso de evaluación:</strong></span>
                <span class="badge bg-primary">${completados} de ${total} sistemas evaluados (${porcentaje}%)</span>
            </div>
            <div class="progress mt-2" style="height: 8px;">
                <div class="progress-bar" role="progressbar" style="width: ${porcentaje}%" aria-valuenow="${porcentaje}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        `;
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
