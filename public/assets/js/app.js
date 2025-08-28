// JavaScript principal para Pritec v2.0

document.addEventListener('DOMContentLoaded', function() {
    // Inicialización general
    initializeApp();
});

function initializeApp() {
    // Configurar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Configurar popovers de Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Agregar animaciones de entrada
    addFadeInAnimations();
    
    // Verificar autenticación periódicamente
    if (window.location.pathname.includes('dashboard')) {
        setInterval(checkAuthStatus, 300000); // Cada 5 minutos
    }
}

function addFadeInAnimations() {
    const elements = document.querySelectorAll('.card, .auth-card');
    elements.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add('fade-in');
        }, index * 100);
    });
}

function checkAuthStatus() {
    fetch(window.location.origin + '/pritec_v2/auth/check')
        .then(response => response.json())
        .then(data => {
            if (!data.authenticated) {
                Swal.fire({
                    title: 'Sesión Expirada',
                    text: 'Tu sesión ha expirado. Serás redirigido al login.',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    window.location.href = window.location.origin + '/pritec_v2/login';
                });
            }
        })
        .catch(error => {
            console.error('Error checking auth status:', error);
        });
}

// Utilidades globales
window.AppUtils = {
    // Formatear fechas
    formatDate: function(dateString, format = 'dd/mm/yyyy') {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        switch(format) {
            case 'dd/mm/yyyy':
                return `${day}/${month}/${year}`;
            case 'dd/mm/yyyy hh:mm':
                return `${day}/${month}/${year} ${hours}:${minutes}`;
            default:
                return dateString;
        }
    },
    
    // Validar email
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    // Generar ID único
    generateId: function() {
        return '_' + Math.random().toString(36).substr(2, 9);
    },
    
    // Debounce function
    debounce: function(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    },
    
    // Mostrar loading en botón
    showButtonLoading: function(button, text = 'Cargando...') {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            ${text}
        `;
        
        return function() {
            button.disabled = false;
            button.innerHTML = originalText;
        };
    }
};

// Configuración global de fetch para incluir headers necesarios
const originalFetch = window.fetch;
window.fetch = function(url, options = {}) {
    // Agregar headers por defecto
    options.headers = {
        'X-Requested-With': 'XMLHttpRequest',
        ...options.headers
    };
    
    return originalFetch(url, options);
};

// Manejo global de errores AJAX
window.addEventListener('unhandledrejection', function(event) {
    console.error('Unhandled promise rejection:', event.reason);
    
    // Solo mostrar alert en desarrollo
    if (window.location.hostname === 'localhost') {
        Swal.fire({
            title: 'Error de Desarrollo',
            text: 'Se ha producido un error no manejado. Revisa la consola.',
            icon: 'error',
            confirmButtonColor: '#e74c3c'
        });
    }
});

// Shortcut para confirmaciones
window.confirmAction = function(title, text, callback) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
};
