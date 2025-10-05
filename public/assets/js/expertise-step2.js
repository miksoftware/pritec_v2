/**
 * JavaScript para búsqueda de clientes - Paso 2 del Peritaje
 */

(function() {
    'use strict';
    
    // Variables globales
    let searchTimeout = null;
    let selectedClientId = null;
    
    // Elementos del DOM
    const searchInput = document.getElementById('clientSearchInput');
    const searchBtn = document.getElementById('searchBtn');
    const searchResults = document.getElementById('searchResults');
    const clientsList = document.getElementById('clientsList');
    const searchLoading = document.getElementById('searchLoading');
    const noResults = document.getElementById('noResults');
    const resultsCount = document.getElementById('resultsCount');
    const selectedClientSection = document.getElementById('selectedClientSection');
    const selectedClientInfo = document.getElementById('selectedClientInfo');
    const selectedClientIdInput = document.getElementById('selectedClientId');
    const step2Form = document.getElementById('step2Form');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    
    /**
     * Inicializar eventos
     */
    function init() {
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            const searchTerm = this.value.trim();
            
            if (searchTerm.length < 3) {
                hideResults();
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchClients(searchTerm);
            }, 500);
        });
        
        // Botón de búsqueda
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchInput.value.trim();
            if (searchTerm.length >= 3) {
                searchClients(searchTerm);
            }
        });
        
        // Enter en el campo de búsqueda
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value.trim();
                if (searchTerm.length >= 3) {
                    searchClients(searchTerm);
                }
            }
        });
        
        // Limpiar selección
        if (clearSelectionBtn) {
            clearSelectionBtn.addEventListener('click', clearSelection);
        }
    }
    
    /**
     * Buscar clientes mediante AJAX
     */
    function searchClients(searchTerm) {
        // Mostrar loading
        showLoading();
        hideResults();
        hideNoResults();
        
        // Hacer petición AJAX
        fetch(APP_URL + 'expertise/search-clients?search=' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    if (data.count > 0) {
                        displayResults(data.clients, data.count);
                    } else {
                        showNoResults();
                    }
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                showError('Error al buscar clientes: ' + error.message);
            });
    }
    
    /**
     * Mostrar resultados de búsqueda
     */
    function displayResults(clients, count) {
        clientsList.innerHTML = '';
        
        clients.forEach(client => {
            const clientItem = createClientItem(client);
            clientsList.appendChild(clientItem);
        });
        
        resultsCount.textContent = count;
        searchResults.style.display = 'block';
    }
    
    /**
     * Crear elemento HTML para un cliente
     */
    function createClientItem(client) {
        const div = document.createElement('div');
        div.className = 'list-group-item list-group-item-action client-item';
        div.style.cursor = 'pointer';
        div.dataset.clientId = client.client_id;
        
        div.innerHTML = `
            <div class="d-flex w-100 justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-1">
                        <i class="fas fa-user me-2 text-primary"></i>
                        <strong>${escapeHtml(client.first_name)} ${escapeHtml(client.last_name)}</strong>
                    </h6>
                    <div class="mb-1">
                        <small class="text-muted">
                            <i class="fas fa-id-card me-2"></i>
                            <strong>Cédula/RUC:</strong> ${escapeHtml(client.id_number)}
                        </small>
                    </div>
                    ${client.phone ? `
                    <div class="mb-1">
                        <small class="text-muted">
                            <i class="fas fa-phone me-2"></i>
                            <strong>Teléfono:</strong> ${escapeHtml(client.phone)}
                        </small>
                    </div>
                    ` : ''}
                    ${client.email ? `
                    <div class="mb-1">
                        <small class="text-muted">
                            <i class="fas fa-envelope me-2"></i>
                            <strong>Email:</strong> ${escapeHtml(client.email)}
                        </small>
                    </div>
                    ` : ''}
                    ${client.address ? `
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <strong>Dirección:</strong> ${escapeHtml(client.address)}
                        </small>
                    </div>
                    ` : ''}
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-primary select-client-btn">
                        <i class="fas fa-check me-1"></i>Seleccionar
                    </button>
                </div>
            </div>
        `;
        
        // Evento click en el item completo
        div.addEventListener('click', function(e) {
            if (!e.target.closest('.select-client-btn')) {
                return;
            }
            selectClient(client);
        });
        
        // Evento click en el botón
        const selectBtn = div.querySelector('.select-client-btn');
        selectBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            selectClient(client);
        });
        
        return div;
    }
    
    /**
     * Seleccionar un cliente
     */
    function selectClient(client) {
        selectedClientId = client.client_id;
        
        // Mostrar información del cliente seleccionado
        selectedClientInfo.innerHTML = `
            <div class="col-md-6">
                <p class="mb-2">
                    <strong><i class="fas fa-user me-2"></i>Nombre:</strong><br>
                    <span class="ms-4">${escapeHtml(client.first_name)} ${escapeHtml(client.last_name)}</span>
                </p>
                <p class="mb-2">
                    <strong><i class="fas fa-id-card me-2"></i>Cédula/RUC:</strong><br>
                    <span class="ms-4">${escapeHtml(client.id_number)}</span>
                </p>
                ${client.phone ? `
                <p class="mb-2">
                    <strong><i class="fas fa-phone me-2"></i>Teléfono:</strong><br>
                    <span class="ms-4">${escapeHtml(client.phone)}</span>
                </p>
                ` : ''}
            </div>
            <div class="col-md-6">
                ${client.email ? `
                <p class="mb-2">
                    <strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                    <span class="ms-4">${escapeHtml(client.email)}</span>
                </p>
                ` : ''}
                ${client.address ? `
                <p class="mb-2">
                    <strong><i class="fas fa-map-marker-alt me-2"></i>Dirección:</strong><br>
                    <span class="ms-4">${escapeHtml(client.address)}</span>
                </p>
                ` : ''}
            </div>
        `;
        
        // Actualizar campo hidden
        selectedClientIdInput.value = client.client_id;
        
        // Mostrar sección de cliente seleccionado y formulario
        selectedClientSection.style.display = 'block';
        step2Form.style.display = 'block';
        
        // Ocultar resultados de búsqueda
        hideResults();
        
        // Limpiar campo de búsqueda
        searchInput.value = '';
        
        // Scroll hacia el cliente seleccionado
        selectedClientSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    /**
     * Limpiar selección de cliente
     */
    function clearSelection() {
        selectedClientId = null;
        selectedClientIdInput.value = '';
        selectedClientSection.style.display = 'none';
        step2Form.style.display = 'none';
        searchInput.focus();
    }
    
    /**
     * Mostrar indicador de carga
     */
    function showLoading() {
        searchLoading.style.display = 'block';
    }
    
    /**
     * Ocultar indicador de carga
     */
    function hideLoading() {
        searchLoading.style.display = 'none';
    }
    
    /**
     * Mostrar resultados
     */
    function showResults() {
        searchResults.style.display = 'block';
    }
    
    /**
     * Ocultar resultados
     */
    function hideResults() {
        searchResults.style.display = 'none';
    }
    
    /**
     * Mostrar mensaje de sin resultados
     */
    function showNoResults() {
        noResults.style.display = 'block';
    }
    
    /**
     * Ocultar mensaje de sin resultados
     */
    function hideNoResults() {
        noResults.style.display = 'none';
    }
    
    /**
     * Mostrar error
     */
    function showError(message) {
        alert('Error: ' + message);
    }
    
    /**
     * Escapar HTML para prevenir XSS
     */
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();
