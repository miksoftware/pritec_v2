<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/vehicle-types.css" rel="stylesheet">
<link href="<?= ASSETS_URL ?>css/vehicle_types_sections.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Configurar el header de contenido
renderContentHeader('Configurar Secciones', [
    'subtitle' => 'Gestiona las secciones de ' . htmlspecialchars($vehicleType['name']),
    'icon' => 'fas fa-puzzle-piece',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Tipos de Vehículos', 'url' => APP_URL . 'vehicle-types'],
        ['text' => htmlspecialchars($vehicleType['name']), 'url' => APP_URL . 'vehicle-types/' . $vehicleType['id'] . '/edit'],
        ['text' => 'Secciones', 'url' => null]
    ]),
    'actions' => [
        createHeaderAction('Editar Tipo', APP_URL . 'vehicle-types/' . $vehicleType['id'] . '/edit', [
            'icon' => 'fas fa-edit',
            'class' => 'btn-outline-info'
        ]),
        createHeaderAction('Volver a Tipos', APP_URL . 'vehicle-types', [
            'icon' => 'fas fa-arrow-left',
            'class' => 'btn-outline-secondary'
        ])
    ]
]);
?>

<!-- Content Body -->
<div class="content-body">
    <div class="row">
        <!-- Panel de Imagen de Sección -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">
                                <i class="fas fa-image me-2"></i>
                                <span id="currentSectionTitle">Imagen de Sección</span>
                            </h6>
                            <small class="text-muted" id="currentSectionInfo">Selecciona una sección para cargar imagen</small>
                        </div>
                        <?php if (count($sections) > 0): ?>
                        <div class="d-flex gap-2">
                            <!-- Navegación entre secciones -->
                            <div class="btn-group btn-group-sm" role="group">
                                <?php foreach ($sections as $index => $section): ?>
                                <button class="btn <?= $index === 0 ? 'btn-primary' : 'btn-outline-primary' ?> section-nav-btn"
                                        data-section-id="<?= $section['id'] ?>"
                                        data-section-name="<?= htmlspecialchars($section['name']) ?>"
                                        data-section-image="<?= $section['image_path'] ? (ASSETS_URL . 'uploads/vehicle_sections/' . $section['image_path']) : '' ?>"
                                        onclick="switchSection(<?= $section['id'] ?>, '<?= htmlspecialchars($section['name']) ?>', '<?= $section['image_path'] ? (ASSETS_URL . 'uploads/vehicle_sections/' . $section['image_path']) : '' ?>')">
                                    <?= htmlspecialchars($section['name']) ?>
                                    <?php if ($section['image_path']): ?>
                                        <i class="fas fa-check-circle text-success ms-1"></i>
                                    <?php else: ?>
                                        <i class="fas fa-times-circle text-danger ms-1"></i>
                                    <?php endif; ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="section-image-viewer" id="sectionImageViewer">
                        <?php if (count($sections) > 0): ?>
                            <?php $firstSection = $sections[0]; ?>
                            <?php if ($firstSection['image_path']): ?>
                                <img src="<?= ASSETS_URL ?>uploads/vehicle_sections/<?= $firstSection['image_path'] ?>" 
                                     alt="<?= htmlspecialchars($firstSection['name']) ?>"
                                     class="section-display-image"
                                     id="sectionDisplayImage"
                                     style="max-width: 100%; height: auto; display: block;">
                                <div class="section-overlay-actions">
                                    <button class="btn btn-light btn-sm" onclick="viewCurrentSection()">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="editCurrentSection()">
                                        <i class="fas fa-edit me-1"></i> Editar Piezas
                                    </button>
                                    <label class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload me-1"></i> Cambiar
                                        <input type="file" class="d-none" accept="image/*" onchange="uploadCurrentSectionImage(this)">
                                    </label>
                                </div>
                            <?php else: ?>
                                <div class="section-placeholder text-center py-5" id="sectionPlaceholder">
                                    <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Sin imagen cargada</h5>
                                    <p class="text-muted mb-3">Sube una imagen para esta sección</p>
                                    <label class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>
                                        Subir Imagen
                                        <input type="file" class="d-none" accept="image/*" onchange="uploadCurrentSectionImage(this)">
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-puzzle-piece fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay secciones configuradas</h5>
                                <p class="text-muted">Las secciones se crearán automáticamente al continuar.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>        <!-- Panel de Acciones -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (count($sections) === 0): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Paso siguiente:</strong> Se crearán automáticamente las secciones para este tipo de vehículo.
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary" onclick="createSections()">
                                <i class="fas fa-plus me-2"></i>
                                Crear Secciones
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>¡Excelente!</strong> Las secciones están configuradas. Ahora puedes subir imágenes y definir piezas.
                        </div>
                    <?php endif; ?>
                    
                    <div class="sections-info">
                        <h6><i class="fas fa-list-ul me-2"></i>Estado de las secciones:</h6>
                        <ul class="list-unstyled">
                            <?php
                            $expectedSections = $vehicleType['type'] === 'carro' 
                                ? ['carroceria', 'estructura', 'chasis']
                                : ['carroceria', 'chasis'];
                            
                            $sectionNames = [
                                'carroceria' => 'Carrocería',
                                'estructura' => 'Estructura', 
                                'chasis' => 'Chasis'
                            ];
                            
                            foreach ($expectedSections as $expectedSection):
                                $sectionExists = false;
                                $hasImage = false;
                                
                                foreach ($sections as $section) {
                                    if ($section['section_name'] === $expectedSection) {
                                        $sectionExists = true;
                                        $hasImage = !empty($section['image_path']);
                                        break;
                                    }
                                }
                            ?>
                            <li class="mb-1">
                                <?php if ($sectionExists && $hasImage): ?>
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-success"><?= $sectionNames[$expectedSection] ?></span>
                                    <small class="text-muted ms-2">(Imagen cargada)</small>
                                <?php elseif ($sectionExists && !$hasImage): ?>
                                    <i class="fas fa-times-circle text-warning me-2"></i>
                                    <span class="text-warning"><?= $sectionNames[$expectedSection] ?></span>
                                    <small class="text-muted ms-2">(Sin imagen)</small>
                                <?php else: ?>
                                    <i class="fas fa-circle text-muted me-2"></i>
                                    <span class="text-muted"><?= $sectionNames[$expectedSection] ?></span>
                                    <small class="text-muted ms-2">(No creada)</small>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="<?= APP_URL ?>vehicle-types/<?= $vehicleType['id'] ?>/edit" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver a Editar
                        </a>
                        
                        <?php if (count($sections) > 0): ?>
                        <button class="btn btn-success" onclick="proceedToPieces()">
                            <i class="fas fa-arrow-right me-2"></i>
                            Continuar a Piezas
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Instrucciones -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Instrucciones
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li class="mb-2">
                            <strong>Crear secciones:</strong> Se generarán automáticamente según el tipo de vehículo
                        </li>
                        <li class="mb-2">
                            <strong>Subir imágenes:</strong> Usa imágenes de 400x400 píxeles para mejor calidad
                        </li>
                        <li class="mb-2">
                            <strong>Definir piezas:</strong> Podrás posicionar piezas sobre cada imagen
                        </li>
                        <li>
                            <strong>Finalizar:</strong> ¡Tu tipo de vehículo estará listo para usar!
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver sección -->
<div class="modal fade" id="sectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Ver Sección
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="sectionModalContent">
                    <!-- Contenido dinámico -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de entrada
    animateElements('.section-card', 100);
    animateElements('.card', 200);
});

// Crear secciones automáticamente
function createSections() {
    Swal.fire({
        title: '¿Crear secciones?',
        text: 'Se crearán las secciones estándar para este tipo de vehículo',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, crear',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
            formData.append('vehicle_type_id', '<?= $vehicleType['id'] ?>');
            
            fetch('<?= APP_URL ?>vehicle-types/create-sections', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error al crear las secciones');
            });
        }
    });
}

// Subir imagen de sección
function uploadSectionImage(input, sectionId) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    
    // Validar archivo
    if (!file.type.startsWith('image/')) {
        showNotification('error', 'Por favor selecciona un archivo de imagen válido');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) { // 5MB
        showNotification('error', 'La imagen no puede ser mayor a 5MB');
        return;
    }
    
    const formData = new FormData();
    formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
    formData.append('section_id', sectionId);
    formData.append('image', file);
    
    // Mostrar loading en la sección
    const sectionCard = document.querySelector(`[data-section-id="${sectionId}"]`);
    const overlay = sectionCard.querySelector('.section-overlay') || sectionCard.querySelector('.section-placeholder');
    
    showLoading(overlay, 'Subiendo imagen...');
    
    fetch('<?= APP_URL ?>vehicle-types/upload-section-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading(overlay);
        
        if (data.success) {
            showNotification('success', data.message);
            // Recargar la página para mostrar la nueva imagen
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        hideLoading(overlay);
        console.error('Error:', error);
        showNotification('error', 'Error al subir la imagen');
    });
    
    // Limpiar input
    input.value = '';
}

// Ver sección
function viewSection(sectionId) {
    fetch(`<?= APP_URL ?>vehicle-types/section/${sectionId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('sectionModalContent').innerHTML = `
                <div class="text-center">
                    <img src="${data.section.image_url}" 
                         alt="${data.section.name}" 
                         class="img-fluid rounded"
                         style="max-height: 400px;">
                    <h5 class="mt-3">${data.section.name}</h5>
                    <p class="text-muted">Piezas configuradas: ${data.section.pieces_count || 0}</p>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('sectionModal'));
            modal.show();
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al cargar la sección');
    });
}

// Editar sección (redirige a la vista de piezas)
function editSection(sectionId) {
    window.location.href = `<?= APP_URL ?>vehicle-types/section/${sectionId}/pieces`;
}

// Continuar a definir piezas
function proceedToPieces() {
    // Buscar la primera sección para empezar
    const firstSection = <?= json_encode($sections[0] ?? null) ?>;
    
    if (firstSection) {
        window.location.href = `<?= APP_URL ?>vehicle-types/section/${firstSection.id}/pieces`;
    } else {
        showNotification('error', 'No hay secciones disponibles');
    }
}

// Mostrar loading
function showLoading(element, message = 'Cargando...') {
    element.innerHTML = `
        <div class="text-center p-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 mb-0 text-muted">${message}</p>
        </div>
    `;
}

// Ocultar loading
function hideLoading(element) {
    // Este método se usa cuando se recarga la página
}

// Variables globales para el estado actual
let currentSectionId = <?= count($sections) > 0 ? $sections[0]['id'] : 'null' ?>;

// Inicializar la vista al cargar
document.addEventListener('DOMContentLoaded', function() {
    // Animación de entrada
    animateElements('.section-nav-btn', 100);
    animateElements('.card', 200);
    
    // Inicializar la primera sección si existe
    <?php if (count($sections) > 0): ?>
    updateSectionInfo(<?= $sections[0]['id'] ?>, '<?= htmlspecialchars($sections[0]['name']) ?>');
    <?php endif; ?>
});

// Cambiar de sección
function switchSection(sectionId, sectionName, imagePath) {
    currentSectionId = sectionId;
    
    // Actualizar botones de navegación
    document.querySelectorAll('.section-nav-btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary');
    });
    
    event.target.classList.remove('btn-outline-primary');
    event.target.classList.add('btn-primary');
    
    // Actualizar título
    document.getElementById('currentSectionTitle').textContent = sectionName;
    document.getElementById('currentSectionInfo').textContent = imagePath ? 'Imagen cargada' : 'Sin imagen cargada';
    
    // Actualizar visor de imagen
    const viewer = document.getElementById('sectionImageViewer');
    
    if (imagePath) {
        viewer.innerHTML = `
            <img src="${imagePath}" 
                 alt="${sectionName}"
                 class="section-display-image"
                 id="sectionDisplayImage"
                 style="max-width: 100%; height: auto; display: block;">
            <div class="section-overlay-actions">
                <button class="btn btn-light btn-sm" onclick="viewCurrentSection()">
                    <i class="fas fa-eye me-1"></i> Ver
                </button>
                <button class="btn btn-warning btn-sm" onclick="editCurrentSection()">
                    <i class="fas fa-edit me-1"></i> Editar Piezas
                </button>
                <label class="btn btn-primary btn-sm">
                    <i class="fas fa-upload me-1"></i> Cambiar
                    <input type="file" class="d-none" accept="image/*" onchange="uploadCurrentSectionImage(this)">
                </label>
            </div>
        `;
    } else {
        viewer.innerHTML = `
            <div class="section-placeholder text-center py-5" id="sectionPlaceholder">
                <i class="fas fa-image fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Sin imagen cargada</h5>
                <p class="text-muted mb-3">Sube una imagen para ${sectionName}</p>
                <label class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i>
                    Subir Imagen
                    <input type="file" class="d-none" accept="image/*" onchange="uploadCurrentSectionImage(this)">
                </label>
            </div>
        `;
    }
}

// Actualizar información de la sección
function updateSectionInfo(sectionId, sectionName) {
    document.getElementById('currentSectionTitle').textContent = sectionName;
}

// Subir imagen de la sección actual
function uploadCurrentSectionImage(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    
    // Validar archivo
    if (!file.type.startsWith('image/')) {
        showNotification('error', 'Por favor selecciona un archivo de imagen válido');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) { // 5MB
        showNotification('error', 'La imagen no puede ser mayor a 5MB');
        return;
    }
    
    const formData = new FormData();
    formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
    formData.append('section_id', currentSectionId);
    formData.append('image', file);
    
    // Mostrar loading
    const viewer = document.getElementById('sectionImageViewer');
    showLoading(viewer, 'Subiendo imagen...');
    
    fetch('<?= APP_URL ?>vehicle-types/upload-section-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            // Recargar la página para mostrar la nueva imagen
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al subir la imagen');
    });
    
    // Limpiar input
    input.value = '';
}

// Ver sección actual
function viewCurrentSection() {
    fetch(`<?= APP_URL ?>vehicle-types/section/${currentSectionId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('sectionModalContent').innerHTML = `
                <div class="text-center">
                    <img src="${data.section.image_url}" 
                         alt="${data.section.name}" 
                         class="img-fluid rounded"
                         style="max-height: 400px;">
                    <h5 class="mt-3">${data.section.name}</h5>
                    <p class="text-muted">Piezas configuradas: ${data.section.pieces_count || 0}</p>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('sectionModal'));
            modal.show();
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al cargar la sección');
    });
}

// Editar sección actual (redirige a la vista de piezas)
function editCurrentSection() {
    window.location.href = `<?= APP_URL ?>vehicle-types/section/${currentSectionId}/pieces`;
}
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
