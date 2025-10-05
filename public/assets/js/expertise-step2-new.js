/**
 * JavaScript para Paso 2 - Datos del Vehículo
 */

(function() {
    'use strict';
    
    // Variables globales
    let searchTimeout = null;
    let selectedVehicleTypeId = null;
    
    // Elementos del DOM
    const searchInput = document.getElementById('vehicleTypeSearchInput');
    const searchBtn = document.getElementById('searchVehicleTypeBtn');
    const vehicleTypeResults = document.getElementById('vehicleTypeResults');
    const vehicleTypesList = document.getElementById('vehicleTypesList');
    const vehicleTypeLoading = document.getElementById('vehicleTypeLoading');
    const vehicleTypeNoResults = document.getElementById('vehicleTypeNoResults');
    const vehicleTypeCount = document.getElementById('vehicleTypeCount');
    const selectedVehicleTypeSection = document.getElementById('selectedVehicleTypeSection');
    const selectedVehicleTypeInfo = document.getElementById('selectedVehicleTypeInfo');
    const vehicleTypeInput = document.getElementById('tipo_vehiculo_input');
    const vehicleDataSection = document.getElementById('vehicleDataSection');
    const vehicleTypeSearchSection = document.getElementById('vehicleTypeSearchSection');
    const cambiarTipoVehiculo = document.getElementById('cambiarTipoVehiculo');
    const submitBtn = document.getElementById('submitBtn');
    const step2Form = document.getElementById('step2Form');
    
    /**
     * Inicializar eventos
     */
    function init() {
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            const searchTerm = this.value.trim();
            
            if (searchTerm.length === 0) {
                hideResults();
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchVehicleTypes(searchTerm);
            }, 500);
        });
        
        // Botón de búsqueda (muestra todos si está vacío)
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchInput.value.trim();
            searchVehicleTypes(searchTerm);
        });
        
        // Enter en el campo de búsqueda
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value.trim();
                searchVehicleTypes(searchTerm);
            }
        });
        
        // Cambiar tipo de vehículo
        if (cambiarTipoVehiculo) {
            cambiarTipoVehiculo.addEventListener('click', clearSelection);
        }
        
        // Validación del formulario antes de enviar
        step2Form.addEventListener('submit', function(event) {
            if (!selectedVehicleTypeId) {
                event.preventDefault();
                event.stopPropagation();
                alert('Debe seleccionar un tipo de vehículo antes de continuar');
                return false;
            }
            
            if (!step2Form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            step2Form.classList.add('was-validated');
        });
        
        // Cargar todos los tipos al iniciar
        searchVehicleTypes('');
    }
    
    /**
     * Buscar tipos de vehículo mediante AJAX
     */
    function searchVehicleTypes(searchTerm) {
        // Mostrar loading
        showLoading();
        hideResults();
        hideNoResults();
        
        // Hacer petición AJAX
        fetch(APP_URL + 'expertise/search-vehicle-types?search=' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    if (data.count > 0) {
                        displayResults(data.vehicle_types, data.count);
                    } else {
                        showNoResults();
                    }
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                hideLoading();
                showError('Error al buscar tipos de vehículos: ' + error.message);
            });
    }
    
    /**
     * Mostrar resultados de búsqueda
     */
    function displayResults(vehicleTypes, count) {
        vehicleTypesList.innerHTML = '';
        
        vehicleTypes.forEach(vehicleType => {
            const item = createVehicleTypeItem(vehicleType);
            vehicleTypesList.appendChild(item);
        });
        
        vehicleTypeCount.textContent = count;
        vehicleTypeResults.style.display = 'block';
    }
    
    /**
     * Crear elemento HTML para un tipo de vehículo
     */
    function createVehicleTypeItem(vehicleType) {
        const div = document.createElement('div');
        div.className = 'list-group-item list-group-item-action client-item';
        div.style.cursor = 'pointer';
        div.dataset.vehicleTypeId = vehicleType.id;
        
        // Ícono según el tipo
        const icon = vehicleType.type === 'moto' ? 'fa-motorcycle' : 'fa-car';
        const badgeColor = vehicleType.type === 'moto' ? 'bg-warning' : 'bg-primary';
        
        div.innerHTML = `
            <div class="d-flex w-100 justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-1">
                        <i class="fas ${icon} me-2 text-dark"></i>
                        <strong>${escapeHtml(vehicleType.name)}</strong>
                        <span class="badge ${badgeColor} ms-2">${vehicleType.type === 'moto' ? 'Moto' : 'Carro'}</span>
                    </h6>
                    ${vehicleType.description ? `
                    <div class="mb-1">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            ${escapeHtml(vehicleType.description)}
                        </small>
                    </div>
                    ` : ''}
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-dark select-vehicle-type-btn">
                        <i class="fas fa-check me-1"></i>Seleccionar
                    </button>
                </div>
            </div>
        `;
        
        // Evento click en el botón
        const selectBtn = div.querySelector('.select-vehicle-type-btn');
        selectBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            selectVehicleType(vehicleType);
        });
        
        return div;
    }
    
    /**
     * Seleccionar un tipo de vehículo
     */
    function selectVehicleType(vehicleType) {
        selectedVehicleTypeId = vehicleType.id;
        
        // Ícono según el tipo
        const icon = vehicleType.type === 'moto' ? 'fa-motorcycle' : 'fa-car';
        const badgeColor = vehicleType.type === 'moto' ? 'bg-warning' : 'bg-primary';
        
        // Mostrar información del tipo seleccionado
        selectedVehicleTypeInfo.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas ${icon} fa-3x text-dark"></i>
                </div>
                <div>
                    <h5 class="mb-1">${escapeHtml(vehicleType.name)}</h5>
                    <span class="badge ${badgeColor}">${vehicleType.type === 'moto' ? 'Motocicleta' : 'Automóvil'}</span>
                    ${vehicleType.description ? `
                    <p class="mb-0 mt-2 text-muted">
                        <small>${escapeHtml(vehicleType.description)}</small>
                    </p>
                    ` : ''}
                </div>
            </div>
        `;
        
        // Actualizar campo hidden
        vehicleTypeInput.value = vehicleType.id;
        
        // Mostrar sección de tipo seleccionado
        selectedVehicleTypeSection.style.display = 'block';
        
        // Mostrar formulario de datos del vehículo
        vehicleDataSection.style.display = 'block';
        
        // Ocultar búsqueda
        vehicleTypeSearchSection.style.display = 'none';
        
        // Mostrar botón de cambiar
        cambiarTipoVehiculo.style.display = 'inline-block';
        
        // Habilitar botón de submit después de que se llene la placa
        document.getElementById('placa').addEventListener('input', function() {
            if (this.value.trim().length > 0 && selectedVehicleTypeId) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('disabled');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled');
            }
        });
        
        // Focus en el campo placa
        document.getElementById('placa').focus();
        
        // Scroll hacia el formulario
        vehicleDataSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    /**
     * Limpiar selección de tipo de vehículo
     */
    function clearSelection() {
        selectedVehicleTypeId = null;
        vehicleTypeInput.value = '';
        selectedVehicleTypeSection.style.display = 'none';
        vehicleDataSection.style.display = 'none';
        vehicleTypeSearchSection.style.display = 'block';
        cambiarTipoVehiculo.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled');
        searchInput.value = '';
        searchInput.focus();
        
        // Limpiar formulario
        document.getElementById('step2Form').reset();
        document.getElementById('tipo_vehiculo_input').value = '';
    }
    
    /**
     * Mostrar indicador de carga
     */
    function showLoading() {
        vehicleTypeLoading.style.display = 'block';
    }
    
    /**
     * Ocultar indicador de carga
     */
    function hideLoading() {
        vehicleTypeLoading.style.display = 'none';
    }
    
    /**
     * Ocultar resultados
     */
    function hideResults() {
        vehicleTypeResults.style.display = 'none';
    }
    
    /**
     * Mostrar mensaje de sin resultados
     */
    function showNoResults() {
        vehicleTypeNoResults.style.display = 'block';
    }
    
    /**
     * Ocultar mensaje de sin resultados
     */
    function hideNoResults() {
        vehicleTypeNoResults.style.display = 'none';
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
