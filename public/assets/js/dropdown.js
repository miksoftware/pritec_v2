// Dropdown functionality for sidebar
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dropdown functionality
    const dropdowns = document.querySelectorAll('.sidebar-dropdown-toggle');
    
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function() {
            // Toggle active class on parent
            const parent = this.closest('.sidebar-dropdown');
            parent.classList.toggle('active');
        });
    });
    
    // Check if any dropdown item is active and open the parent
    const currentPath = window.location.pathname;
    const dropdownItems = document.querySelectorAll('.sidebar-dropdown-content .sidebar-nav-link');
    
    dropdownItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && href !== '#' && currentPath.includes(href.split('/').pop())) {
            // Add active class to the item
            item.classList.add('active');
            // Open the parent dropdown
            const parent = item.closest('.sidebar-dropdown');
            if (parent) {
                parent.classList.add('active');
            }
        }
    });
});
