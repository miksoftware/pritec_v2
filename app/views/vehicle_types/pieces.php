<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/vehicle-types.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Configurar el header de contenido
renderContentHeader('Definir Piezas', [
    'subtitle' => 'Configura las piezas para ' . htmlspecialchars($section['name']) . ' de ' . htmlspecialchars($vehicleType['name']),
    'icon' => 'fas fa-crosshairs',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Tipos de Vehículos', 'url' => APP_URL . 'vehicle-types'],
        ['text' => htmlspecialchars($vehicleType['name']), 'url' => APP_URL . 'vehicle-types/' . $vehicleType['id'] . '/edit'],
        ['text' => 'Secciones', 'url' => APP_URL . 'vehicle-types/' . $vehicleType['id'] . '/sections'],
        ['text' => htmlspecialchars($section['name']), 'url' => null]
    ]),
    'actions' => [
        createHeaderAction('Volver a Secciones', APP_URL . 'vehicle-types/' . $vehicleType['id'] . '/sections', [
            'icon' => 'fas fa-arrow-left',
            'class' => 'btn-outline-secondary'
        ])
    ]
]);
?>

<!-- Content Body -->
<div class="content-body">
    <div class="row">
        <!-- Panel de Imagen Interactiva -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">
                                <i class="fas fa-image me-2"></i>
                                Editor de Piezas - <?= htmlspecialchars($section['name']) ?>
                            </h6>
                            <small class="text-muted"><?= count($pieces) ?> piezas configuradas</small>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Navegación entre secciones -->
                            <div class="btn-group btn-group-sm" role="group">
                                <?php foreach ($allSections as $s): ?>
                                <a href="<?= APP_URL ?>vehicle-types/section/<?= $s['id'] ?>/pieces" 
                                   class="btn <?= $s['id'] == $section['id'] ? 'btn-primary' : 'btn-outline-primary' ?>"
                                   title="<?= htmlspecialchars($s['name']) ?>">
                                    <?= htmlspecialchars($s['name']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                            <!-- Controles de zoom -->
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-secondary" onclick="zoomOut()" title="Alejar">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="resetZoom()" title="Tamaño normal">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="zoomIn()" title="Acercar">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if ($section['image_path']): ?>
                        <div class="image-editor-container">
                            <div class="image-wrapper" id="imageWrapper">
                                <img src="<?= ASSETS_URL ?>uploads/vehicle_sections/<?= htmlspecialchars($section['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($section['name']) ?>"
                                     class="section-image"
                                     id="sectionImage"
                                     ondragstart="return false;"
                                     style="max-width: 100%; height: auto; display: block;">
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay imagen para esta sección</h5>
                            <p class="text-muted mb-3">Necesitas subir una imagen antes de poder definir piezas.</p>
                            <label class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>
                                Subir Imagen
                                <input type="file" class="d-none" accept="image/*" onchange="uploadSectionImage(this)">
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Panel de Control -->
        <div class="col-lg-4">
            <!-- Panel de Nueva Pieza -->
            <div class="card mb-3" id="addPiecePanel">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Agregar Pieza
                    </h6>
                </div>
                <div class="card-body">
                    <form id="addPieceForm">
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                        <input type="hidden" name="section_id" value="<?= $section['id'] ?>">
                        <input type="hidden" name="position_x" id="positionX">
                        <input type="hidden" name="position_y" id="positionY">
                        
                        <div class="mb-3">
                            <label for="pieceNumber" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>
                                Número de Pieza *
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="pieceNumber" 
                                   name="piece_number" 
                                   min="1" 
                                   max="999"
                                   required
                                   placeholder="Ej: 1, 2, 3...">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pieceName" class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                Nombre de la Pieza
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="pieceName" 
                                   name="piece_name" 
                                   placeholder="Opcional: Ej: Puerta delantera izquierda">
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-mouse-pointer me-1"></i>
                                Haz clic en la imagen para posicionar la pieza
                            </small>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" disabled id="addPieceBtn">
                                <i class="fas fa-plus me-2"></i>
                                Agregar Pieza
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Panel de Pieza Seleccionada -->
            <div class="card mb-3" id="editPiecePanel" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Editar Pieza
                    </h6>
                </div>
                <div class="card-body">
                    <form id="editPieceForm">
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                        <input type="hidden" name="piece_id" id="editPieceId">
                        <input type="hidden" name="position_x" id="editPositionX">
                        <input type="hidden" name="position_y" id="editPositionY">
                        
                        <div class="mb-3">
                            <label for="editPieceNumber" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>
                                Número de Pieza *
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="editPieceNumber" 
                                   name="piece_number" 
                                   min="1" 
                                   max="999"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editPieceName" class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                Nombre de la Pieza
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="editPieceName" 
                                   name="piece_name">
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Posición X</label>
                                <input type="number" class="form-control form-control-sm" id="displayPositionX" readonly>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Posición Y</label>
                                <input type="number" class="form-control form-control-sm" id="displayPositionY" readonly>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>
                                Guardar Cambios
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deletePiece()">
                                <i class="fas fa-trash me-2"></i>
                                Eliminar Pieza
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="deselectPiece()">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Lista de Piezas -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Piezas (<?= count($pieces) ?>)
                    </h6>
                    <?php if (count($pieces) > 0): ?>
                    <button class="btn btn-sm btn-outline-danger" onclick="clearAllPieces()">
                        <i class="fas fa-trash"></i>
                    </button>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if (count($pieces) > 0): ?>
                        <div class="pieces-list" id="piecesList">
                            <?php foreach ($pieces as $piece): ?>
                            <div class="piece-list-item" data-piece-id="<?= $piece['id'] ?>" onclick="selectPieceFromList(<?= $piece['id'] ?>)">
                                <div class="piece-number-badge"><?= $piece['piece_number'] ?></div>
                                <div class="piece-info">
                                    <div class="piece-name">
                                        <?= $piece['piece_name'] ? htmlspecialchars($piece['piece_name']) : 'Pieza sin nombre' ?>
                                    </div>
                                    <small class="text-muted">
                                        Posición: (<?= $piece['position_x'] ?>, <?= $piece['position_y'] ?>)
                                    </small>
                                </div>
                                <div class="piece-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); highlightPiece(<?= $piece['id'] ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-puzzle-piece fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay piezas definidas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Panel de Acciones -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if ($nextSection): ?>
                        <a href="<?= APP_URL ?>vehicle-types/section/<?= $nextSection['id'] ?>/pieces" class="btn btn-success">
                            <i class="fas fa-arrow-right me-2"></i>
                            Siguiente: <?= htmlspecialchars($nextSection['name']) ?>
                        </a>
                        <?php else: ?>
                        <a href="<?= APP_URL ?>vehicle-types" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>
                            ¡Completar Configuración!
                        </a>
                        <?php endif; ?>
                        
                        <a href="<?= APP_URL ?>vehicle-types/<?= $vehicleType['id'] ?>/sections" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver a Secciones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript del editor de piezas -->
<script src="<?= ASSETS_URL ?>js/piece-editor.js"></script>
<script>
// Variables globales para el JavaScript
window.APP_URL = '<?= APP_URL ?>';
window.CSRF_TOKEN_NAME = '<?= CSRF_TOKEN_NAME ?>';
window.CSRF_TOKEN = '<?= $csrf_token ?>';

// Datos de las piezas existentes
const existingPieces = <?= json_encode($pieces) ?>;

// Inicializar el editor cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el editor de piezas
    window.pieceEditor.init(existingPieces);
    
    // Configurar notificaciones si no existe la función global
    if (typeof window.showNotification === 'undefined') {
        window.showNotification = function(type, message) {
            console.log(`[${type.toUpperCase()}] ${message}`);
            
            // Si existe SweetAlert2, usarlo
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type === 'error' ? 'error' : 'success',
                    title: type === 'error' ? 'Error' : 'Éxito',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert(message);
            }
        };
    }
    
    // Animaciones
    animateElements('.card', 100);
});

// Funciones legacy para compatibilidad con otros elementos del DOM
function selectPiece(pieceId) {
    window.pieceEditor.selectPiece(pieceId);
}

function uploadSectionImage(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    
    // Validar archivo
    if (!file.type.startsWith('image/')) {
        window.showNotification('error', 'Por favor selecciona un archivo de imagen válido');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) { // 5MB
        window.showNotification('error', 'La imagen no puede ser mayor a 5MB');
        return;
    }
    
    const formData = new FormData();
    formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
    formData.append('section_id', '<?= $section['id'] ?>');
    formData.append('image', file);
    
    fetch('<?= APP_URL ?>vehicle-types/upload-section-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showNotification('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            window.showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showNotification('error', 'Error al subir la imagen');
    });
    
    input.value = '';
}

function clearAllPieces() {
    if (existingPieces.length === 0) return;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Eliminar todas las piezas?',
            text: 'Se eliminarán todas las piezas de esta sección',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar todas',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                executeClearPieces();
            }
        });
    } else if (confirm('¿Estás seguro de que quieres eliminar todas las piezas?')) {
        executeClearPieces();
    }
}

function executeClearPieces() {
    const formData = new FormData();
    formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
    formData.append('section_id', '<?= $section['id'] ?>');
    
    fetch('<?= APP_URL ?>vehicle-types/clear-pieces', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showNotification('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            window.showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showNotification('error', 'Error al eliminar las piezas');
    });
}

// Función de animación para elementos
function animateElements(selector, delay = 100) {
    const elements = document.querySelectorAll(selector);
    elements.forEach((element, index) => {
        setTimeout(() => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 50);
        }, index * delay);
    });
}
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
