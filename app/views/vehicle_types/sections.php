<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/vehicle-types.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-puzzle-piece me-2"></i>
                Configurar Secciones
            </h1>
            <p class="text-muted mb-0">Gestiona las secciones de <?= htmlspecialchars($vehicleType['name']) ?></p>
        </div>
        <a href="<?= APP_URL ?>vehicle-types" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver
        </a>
    </div>
</div>

<!-- Content Body -->
<div class="content-body">
    <div class="row">
        <!-- Secciones Disponibles -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Secciones del Vehículo
                    </h6>
                    <small class="text-muted">
                        <?= count($sections) ?> de <?= $vehicleType['type'] === 'carro' ? '3' : '2' ?> secciones configuradas
                    </small>
                </div>
                <div class="card-body">
                    <?php if (count($sections) > 0): ?>
                        <div class="sections-grid">
                            <?php foreach ($sections as $section): ?>
                            <div class="section-card" data-section-id="<?= $section['id'] ?>">
                                <div class="section-image-container">
                                    <?php if ($section['image_path']): ?>
                                        <img src="<?= ASSETS_URL ?>uploads/vehicle_sections/<?= $section['image_path'] ?>" 
                                             alt="<?= htmlspecialchars($section['name']) ?>"
                                             class="section-image">
                                        <div class="section-overlay">
                                            <div class="section-actions">
                                                <button class="btn btn-sm btn-light" onclick="viewSection(<?= $section['id'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="editSection(<?= $section['id'] ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <label class="btn btn-sm btn-primary">
                                                    <i class="fas fa-upload"></i>
                                                    <input type="file" class="d-none" accept="image/*" onchange="uploadSectionImage(this, <?= $section['id'] ?>)">
                                                </label>
                                            </div>
                                            <?php if (isset($section['pieces_count']) && $section['pieces_count'] > 0): ?>
                                            <div class="pieces-count">
                                                <span class="badge bg-success">
                                                    <?= $section['pieces_count'] ?> piezas
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="section-placeholder">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="text-muted mt-2">Sin imagen</p>
                                            <label class="btn btn-primary btn-sm">
                                                <i class="fas fa-upload me-1"></i>
                                                Subir Imagen
                                                <input type="file" class="d-none" accept="image/*" onchange="uploadSectionImage(this, <?= $section['id'] ?>)">
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="section-info">
                                    <h6 class="section-name"><?= htmlspecialchars($section['name']) ?></h6>
                                    <div class="section-meta">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Creado: <?= date('d/m/Y', strtotime($section['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay secciones configuradas</h5>
                            <p class="text-muted">Las secciones se crearán automáticamente al continuar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Panel de Acciones -->
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
                        <h6><i class="fas fa-list-ul me-2"></i>Secciones esperadas:</h6>
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
                                $exists = false;
                                foreach ($sections as $section) {
                                    if ($section['section_name'] === $expectedSection) {
                                        $exists = true;
                                        break;
                                    }
                                }
                            ?>
                            <li class="mb-1">
                                <?php if ($exists): ?>
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                <?php else: ?>
                                    <i class="fas fa-circle text-muted me-2"></i>
                                <?php endif; ?>
                                <?= $sectionNames[$expectedSection] ?>
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
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
