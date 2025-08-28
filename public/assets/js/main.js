/**
 * ===================================
 * PRITEC V2.0 - MAIN JAVASCRIPT
 * Sistema de Peritajes - Funciones Principales
 * ===================================
 */

// ===================================
// CONFIGURACIÓN GLOBAL
// ===================================
document.addEventListener('DOMContentLoaded', function() {
    // Configuración global de SweetAlert2
    window.Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    
    // Prevenir scroll horizontal
    document.body.style.overflowX = 'hidden';
});

// ===================================
// FUNCIONES DE AUTENTICACIÓN
// ===================================

/**
 * Cerrar sesión del usuario
 */
function logout() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: '¿Estás seguro de que quieres cerrar la sesión?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cerrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(APP_URL + 'logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Sesión cerrada',
                        text: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = APP_URL + data.redirect;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al cerrar sesión', 'error');
            });
        }
    });
}

// ===================================
// FUNCIONES DE NOTIFICACIÓN
// ===================================

/**
 * Mostrar notificación toast
 * @param {string} type - Tipo de notificación (success, error, warning, info)
 * @param {string} message - Mensaje a mostrar
 */
function showNotification(type, message) {
    if (window.Toast) {
        window.Toast.fire({
            icon: type,
            title: message
        });
    }
}

/**
 * Mostrar alerta de "Próximamente"
 * @param {string} feature - Nombre de la funcionalidad
 */
function showComingSoon(feature) {
    Swal.fire({
        title: 'Próximamente',
        text: `La funcionalidad "${feature}" estará disponible pronto.`,
        icon: 'info',
        confirmButtonColor: '#3498db',
        confirmButtonText: 'Entendido'
    });
}

// ===================================
// FUNCIONES DE UTILIDAD
// ===================================

/**
 * Mostrar/ocultar elementos de carga
 * @param {string} elementId - ID del elemento
 * @param {boolean} show - Mostrar o ocultar
 */
function toggleLoading(elementId, show = true) {
    const element = document.getElementById(elementId);
    if (element) {
        if (show) {
            element.classList.add('show');
        } else {
            element.classList.remove('show');
        }
    }
}

/**
 * Validar formulario básico
 * @param {HTMLFormElement} form - Formulario a validar
 * @returns {boolean} - Es válido o no
 */
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

/**
 * Limpiar formulario
 * @param {HTMLFormElement} form - Formulario a limpiar
 */
function clearForm(form) {
    form.reset();
    const invalidFields = form.querySelectorAll('.is-invalid');
    invalidFields.forEach(field => {
        field.classList.remove('is-invalid');
    });
}

// ===================================
// FUNCIONES DE ANIMACIÓN
// ===================================

/**
 * Animar entrada de elementos
 * @param {string} selector - Selector CSS de elementos
 * @param {number} delay - Retraso entre animaciones (ms)
 */
function animateElements(selector, delay = 100) {
    const elements = document.querySelectorAll(selector);
    elements.forEach((element, index) => {
        setTimeout(() => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 50);
        }, index * delay);
    });
}

// ===================================
// MANEJO DE ERRORES GLOBAL
// ===================================

/**
 * Manejar errores de fetch
 * @param {Error} error - Error capturado
 * @param {string} context - Contexto donde ocurrió el error
 */
function handleFetchError(error, context = 'Operación') {
    console.error(`Error en ${context}:`, error);
    showNotification('error', `Error en ${context}. Por favor, intenta nuevamente.`);
}

// ===================================
// EVENTOS GLOBALES
// ===================================

// Prevenir envío de formularios con Enter en campos de texto
document.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.target.tagName === 'INPUT' && e.target.type === 'text') {
        const form = e.target.closest('form');
        if (form) {
            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                e.preventDefault();
                submitButton.click();
            }
        }
    }
});

// Limpiar mensajes de error cuando el usuario empiece a escribir
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('is-invalid')) {
        e.target.classList.remove('is-invalid');
    }
});

// ===================================
// EXPORTAR FUNCIONES PARA USO GLOBAL
// ===================================
window.logout = logout;
window.showNotification = showNotification;
window.showComingSoon = showComingSoon;
window.toggleLoading = toggleLoading;
window.validateForm = validateForm;
window.clearForm = clearForm;
window.animateElements = animateElements;
window.handleFetchError = handleFetchError;
