/**
 * JavaScript para Paso 10 - Fugas y Niveles
 */

(function() {
    'use strict';
    
    // Elementos del DOM
    const step10Form = document.getElementById('step10Form');
    
    /**
     * Inicializar
     */
    function init() {
        // Contador de observaciones completadas
        actualizarContador();
        
        // Actualizar contador cuando se escriba en cualquier input
        const inputs = step10Form.querySelectorAll('.observacion-input');
        inputs.forEach(function(input) {
            input.addEventListener('input', actualizarContador);
            
            // Feedback visual cuando se completa
            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                }
            });
        });
        
        // Validación del formulario (opcional, no se requieren observaciones)
        step10Form.addEventListener('submit', function(event) {
            if (!step10Form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            step10Form.classList.add('was-validated');
        });
    }
    
    /**
     * Actualizar contador de progreso
     */
    function actualizarContador() {
        const inputs = step10Form.querySelectorAll('.observacion-input');
        const total = inputs.length;
        let completados = 0;
        
        inputs.forEach(function(input) {
            if (input.value.trim() !== '') {
                completados++;
            }
        });
        
        const porcentaje = Math.round((completados / total) * 100);
        
        // Mostrar contador si existe
        let contador = document.getElementById('contadorProgreso');
        if (!contador) {
            contador = document.createElement('div');
            contador.id = 'contadorProgreso';
            contador.className = 'alert alert-info mb-3';
            const cardBody = step10Form.querySelector('.card-body');
            cardBody.insertBefore(contador, cardBody.firstChild);
        }
        
        let colorBadge = 'bg-danger';
        let mensaje = 'Comenzando evaluación...';
        
        if (porcentaje >= 75) {
            colorBadge = 'bg-success';
            mensaje = '¡Excelente progreso!';
        } else if (porcentaje >= 50) {
            colorBadge = 'bg-primary';
            mensaje = 'Buen avance...';
        } else if (porcentaje >= 25) {
            colorBadge = 'bg-warning';
            mensaje = 'Continúa evaluando...';
        }
        
        contador.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="fas fa-tint me-2"></i><strong>Progreso de evaluación:</strong> ${mensaje}</span>
                <span class="badge ${colorBadge}">${completados} de ${total} sistemas evaluados (${porcentaje}%)</span>
            </div>
            <div class="progress mt-2" style="height: 8px;">
                <div class="progress-bar ${colorBadge.replace('bg-', 'bg-')}" role="progressbar" style="width: ${porcentaje}%" aria-valuenow="${porcentaje}" aria-valuemin="0" aria-valuemax="100"></div>
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
