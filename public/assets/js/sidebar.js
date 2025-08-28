// Sidebar JavaScript - Pritec v2.0

// Funciones del sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');
    
    sidebar.classList.toggle('collapsed');
    mainWrapper.classList.toggle('expanded');
    
    // Guardar estado en localStorage
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Función para mostrar "próximamente"
function showComingSoon(feature) {
    Swal.fire({
        title: 'Próximamente',
        text: `La funcionalidad "${feature}" estará disponible pronto.`,
        icon: 'info',
        confirmButtonColor: '#3498db'
    });
}

// Inicialización del sidebar
document.addEventListener('DOMContentLoaded', function() {
    // Restaurar estado del sidebar al cargar
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        document.getElementById('sidebar')?.classList.add('collapsed');
        document.getElementById('mainWrapper')?.classList.add('expanded');
    }
    
    // Marcar enlace activo en el sidebar
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-nav-link');
    
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href !== '#' && currentPath.includes(href.split('/').pop())) {
            link.classList.add('active');
        }
    });
});

// Cerrar sidebar móvil al hacer clic fuera
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const mobileToggle = document.querySelector('.mobile-toggle');
    
    if (window.innerWidth <= 768 && 
        sidebar && 
        !sidebar.contains(e.target) && 
        mobileToggle && 
        !mobileToggle.contains(e.target)) {
        sidebar.classList.remove('show');
    }
});

// Redimensionar ventana
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    
    // En desktop, remover clase show si existe
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('show');
    }
});
