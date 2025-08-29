<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/vehicle-types.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">
            <i class="fas fa-crosshairs me-2"></i>
            Definir Piezas
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>dashboard">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>vehicle-types">Tipos de Vehículos</a></li>
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>vehicle-types/<?= $vehicleType['id'] ?>/sections">Secciones</a></li>
                <li class="breadcrumb-item active">Piezas</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Step Indicator -->
<div class="step-indicator">
    <div class="step completed">
        <div class="step-number">✓</div>
        <span>Información Básica</span>
    </div>
    <div class="step-connector"></div>
    <div class="step completed">
        <div class="step-number">✓</div>
        <span>Configurar Secciones</span>
    </div>
    <div class="step-connector"></div>
    <div class="step active">
        <div class="step-number">3</div>
        <span>Definir Piezas</span>
    </div>
</div>

<!-- Section Info -->
<div class="section-info-card mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="mb-1">
                <?= $vehicleType['type'] === 'carro' ? '🚗' : '🏍️' ?>
                <?= htmlspecialchars($vehicleType['name']) ?> - <?= htmlspecialchars($section['name']) ?>
            </h5>
            <p class="text-muted mb-0">
                <i class="fas fa-puzzle-piece me-1"></i>
                <?= count($pieces) ?> piezas configuradas
                <?php if ($section['image_path']): ?>
                | <i class="fas fa-image me-1"></i>Con imagen
                <?php else: ?>
                | <i class="fas fa-image me-1 text-muted"></i>Sin imagen
                <?php endif; ?>
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <!-- Navegación entre secciones -->
            <div class="btn-group" role="group">
                <?php foreach ($allSections as $s): ?>
                <a href="<?= APP_URL ?>vehicle-types/section/<?= $s['id'] ?>/pieces" 
                   class="btn btn-sm <?= $s['id'] == $section['id'] ? 'btn-primary' : 'btn-outline-primary' ?>">
                    <?= htmlspecialchars($s['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Content Body -->
<div class="content-body">
    <div class="row">
        <!-- Panel de Imagen Interactiva -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-image me-2"></i>
                        Editor de Piezas
                    </h6>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-secondary" onclick="zoomOut()">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="resetZoom()">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="zoomIn()">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if ($section['image_path']): ?>
                        <div class="image-editor-container">
                            <div class="image-wrapper" id="imageWrapper">
                                <img src="<?= ASSETS_URL ?>uploads/<?= $section['image_path'] ?>" 
                                     alt="<?= htmlspecialchars($section['name']) ?>"
                                     class="section-image-editor"
                                     id="sectionImage"
                                     ondragstart="return false;">
                                
                                <!-- Piezas existentes -->
                                <?php foreach ($pieces as $piece): ?>
                                <div class="piece-marker" 
                                     data-piece-id="<?= $piece['id'] ?>"
                                     data-piece-number="<?= $piece['piece_number'] ?>"
                                     style="left: <?= $piece['position_x'] ?>px; top: <?= $piece['position_y'] ?>px;"
                                     onclick="selectPiece(<?= $piece['id'] ?>)"
                                     title="Pieza #<?= $piece['piece_number'] ?>">
                                    <span class="piece-number"><?= $piece['piece_number'] ?></span>
                                </div>
                                <?php endforeach; ?>
                                
                                <!-- Marker temporal para nueva pieza -->
                                <div class="piece-marker piece-marker-new" 
                                     id="newPieceMarker" 
                                     style="display: none;">
                                    <span class="piece-number" id="newPieceNumber"></span>
                                </div>
                            </div>
                            
                            <!-- Overlay para clics -->
                            <div class="image-click-overlay" 
                                 id="imageClickOverlay"
                                 onclick="handleImageClick(event)"></div>
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

<script>
// Variables globales
let selectedPieceId = null;
let zoomLevel = 1;
let isDragging = false;
let existingPieces = <?= json_encode($pieces) ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar editor
    initializePieceEditor();
    
    // Configurar formularios
    setupForms();
    
    // Animaciones
    animateElements('.card', 100);
});

function initializePieceEditor() {
    const imageWrapper = document.getElementById('imageWrapper');
    const sectionImage = document.getElementById('sectionImage');
    
    if (!sectionImage) return;
    
    // Hacer piezas arrastrables
    makePiecesDraggable();
    
    // Ajustar imagen al cargar
    sectionImage.onload = function() {
        adjustImageSize();
    };
    
    if (sectionImage.complete) {
        adjustImageSize();
    }
}

function makePiecesDraggable() {
    const pieces = document.querySelectorAll('.piece-marker:not(.piece-marker-new)');
    
    pieces.forEach(piece => {
        piece.addEventListener('mousedown', function(e) {
            e.stopPropagation();
            startDragging(this, e);
        });
    });
}

function startDragging(piece, e) {
    isDragging = true;
    const pieceId = piece.dataset.pieceId;
    const rect = document.getElementById('imageWrapper').getBoundingClientRect();
    
    const offsetX = e.clientX - rect.left - parseInt(piece.style.left);
    const offsetY = e.clientY - rect.top - parseInt(piece.style.top);
    
    function onMouseMove(e) {
        if (!isDragging) return;
        
        const rect = document.getElementById('imageWrapper').getBoundingClientRect();
        let newX = e.clientX - rect.left - offsetX;
        let newY = e.clientY - rect.top - offsetY;
        
        // Limitar al área de la imagen
        const imageRect = document.getElementById('sectionImage').getBoundingClientRect();
        const wrapperRect = document.getElementById('imageWrapper').getBoundingClientRect();
        
        const maxX = imageRect.width - 30; // 30px es el ancho del marker
        const maxY = imageRect.height - 30;
        
        newX = Math.max(0, Math.min(newX, maxX));
        newY = Math.max(0, Math.min(newY, maxY));
        
        piece.style.left = newX + 'px';
        piece.style.top = newY + 'px';
    }
    
    function onMouseUp() {
        if (isDragging) {
            isDragging = false;
            
            // Guardar nueva posición
            const newX = parseInt(piece.style.left);
            const newY = parseInt(piece.style.top);
            
            updatePiecePosition(pieceId, newX, newY);
        }
        
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
    }
    
    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup', onMouseUp);
}

function handleImageClick(event) {
    if (isDragging) return;
    
    const rect = event.currentTarget.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    
    // Verificar que el clic esté dentro de la imagen
    const imageRect = document.getElementById('sectionImage').getBoundingClientRect();
    const wrapperRect = document.getElementById('imageWrapper').getBoundingClientRect();
    
    if (x < 0 || y < 0 || x > imageRect.width || y > imageRect.height) {
        return;
    }
    
    // Actualizar posición en el formulario
    document.getElementById('positionX').value = Math.round(x);
    document.getElementById('positionY').value = Math.round(y);
    
    // Mostrar marker temporal
    showNewPieceMarker(x, y);
    
    // Habilitar botón de agregar
    document.getElementById('addPieceBtn').disabled = false;
    
    // Enfocar en el campo de número
    document.getElementById('pieceNumber').focus();
}

function showNewPieceMarker(x, y) {
    const marker = document.getElementById('newPieceMarker');
    const pieceNumber = document.getElementById('pieceNumber').value || '?';
    
    marker.style.left = x + 'px';
    marker.style.top = y + 'px';
    marker.style.display = 'block';
    document.getElementById('newPieceNumber').textContent = pieceNumber;
}

function setupForms() {
    // Formulario de agregar pieza
    document.getElementById('addPieceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addPiece();
    });
    
    // Formulario de editar pieza
    document.getElementById('editPieceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updatePiece();
    });
    
    // Actualizar marker temporal cuando cambie el número
    document.getElementById('pieceNumber').addEventListener('input', function() {
        const newNumber = this.value || '?';
        document.getElementById('newPieceNumber').textContent = newNumber;
        
        // Validar que el número no exista
        validatePieceNumber(this.value);
    });
}

function validatePieceNumber(number) {
    const input = document.getElementById('pieceNumber');
    const feedback = input.nextElementSibling;
    
    if (!number) {
        input.classList.remove('is-valid', 'is-invalid');
        feedback.textContent = '';
        return false;
    }
    
    // Verificar si ya existe
    const exists = existingPieces.some(piece => piece.piece_number == number);
    
    if (exists) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        feedback.textContent = 'Este número ya está en uso';
        return false;
    } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        feedback.textContent = '';
        return true;
    }
}

function addPiece() {
    const form = document.getElementById('addPieceForm');
    const formData = new FormData(form);
    
    // Validar número
    const pieceNumber = formData.get('piece_number');
    if (!validatePieceNumber(pieceNumber)) {
        showNotification('error', 'Por favor corrige los errores en el formulario');
        return;
    }
    
    fetch('<?= APP_URL ?>vehicle-types/add-piece', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            
            // Agregar pieza a la imagen
            addPieceToImage(data.piece);
            
            // Actualizar lista
            updatePiecesList();
            
            // Limpiar formulario
            resetAddPieceForm();
            
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al agregar la pieza');
    });
}

function addPieceToImage(piece) {
    const imageWrapper = document.getElementById('imageWrapper');
    
    const pieceElement = document.createElement('div');
    pieceElement.className = 'piece-marker';
    pieceElement.dataset.pieceId = piece.id;
    pieceElement.dataset.pieceNumber = piece.piece_number;
    pieceElement.style.left = piece.position_x + 'px';
    pieceElement.style.top = piece.position_y + 'px';
    pieceElement.title = `Pieza #${piece.piece_number}`;
    pieceElement.onclick = () => selectPiece(piece.id);
    
    pieceElement.innerHTML = `<span class="piece-number">${piece.piece_number}</span>`;
    
    imageWrapper.appendChild(pieceElement);
    
    // Hacer arrastrble
    pieceElement.addEventListener('mousedown', function(e) {
        e.stopPropagation();
        startDragging(this, e);
    });
    
    // Agregar a la lista
    existingPieces.push(piece);
}

function resetAddPieceForm() {
    document.getElementById('addPieceForm').reset();
    document.getElementById('newPieceMarker').style.display = 'none';
    document.getElementById('addPieceBtn').disabled = true;
    document.getElementById('pieceNumber').classList.remove('is-valid', 'is-invalid');
}

function selectPiece(pieceId) {
    // Deseleccionar pieza anterior
    if (selectedPieceId) {
        const prevPiece = document.querySelector(`[data-piece-id="${selectedPieceId}"]`);
        if (prevPiece) prevPiece.classList.remove('selected');
    }
    
    // Seleccionar nueva pieza
    selectedPieceId = pieceId;
    const piece = document.querySelector(`[data-piece-id="${pieceId}"]`);
    piece.classList.add('selected');
    
    // Cargar datos en el formulario de edición
    const pieceData = existingPieces.find(p => p.id == pieceId);
    if (pieceData) {
        document.getElementById('editPieceId').value = pieceData.id;
        document.getElementById('editPieceNumber').value = pieceData.piece_number;
        document.getElementById('editPieceName').value = pieceData.piece_name || '';
        document.getElementById('editPositionX').value = pieceData.position_x;
        document.getElementById('editPositionY').value = pieceData.position_y;
        document.getElementById('displayPositionX').value = pieceData.position_x;
        document.getElementById('displayPositionY').value = pieceData.position_y;
    }
    
    // Mostrar panel de edición
    document.getElementById('addPiecePanel').style.display = 'none';
    document.getElementById('editPiecePanel').style.display = 'block';
    
    // Resaltar en la lista
    document.querySelectorAll('.piece-list-item').forEach(item => {
        item.classList.remove('selected');
    });
    const listItem = document.querySelector(`.piece-list-item[data-piece-id="${pieceId}"]`);
    if (listItem) listItem.classList.add('selected');
}

function deselectPiece() {
    if (selectedPieceId) {
        const piece = document.querySelector(`[data-piece-id="${selectedPieceId}"]`);
        if (piece) piece.classList.remove('selected');
        
        const listItem = document.querySelector(`.piece-list-item[data-piece-id="${selectedPieceId}"]`);
        if (listItem) listItem.classList.remove('selected');
    }
    
    selectedPieceId = null;
    
    // Mostrar panel de agregar
    document.getElementById('editPiecePanel').style.display = 'none';
    document.getElementById('addPiecePanel').style.display = 'block';
}

function updatePiece() {
    const form = document.getElementById('editPieceForm');
    const formData = new FormData(form);
    
    fetch('<?= APP_URL ?>vehicle-types/update-piece', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            
            // Actualizar datos locales
            const pieceIndex = existingPieces.findIndex(p => p.id == selectedPieceId);
            if (pieceIndex !== -1) {
                existingPieces[pieceIndex] = { ...existingPieces[pieceIndex], ...data.piece };
            }
            
            // Actualizar UI
            updatePieceInImage(data.piece);
            updatePiecesList();
            
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al actualizar la pieza');
    });
}

function updatePieceInImage(pieceData) {
    const pieceElement = document.querySelector(`[data-piece-id="${pieceData.id}"]`);
    if (pieceElement) {
        pieceElement.querySelector('.piece-number').textContent = pieceData.piece_number;
        pieceElement.dataset.pieceNumber = pieceData.piece_number;
        pieceElement.title = `Pieza #${pieceData.piece_number}`;
    }
}

function updatePiecePosition(pieceId, x, y) {
    const formData = new FormData();
    formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
    formData.append('piece_id', pieceId);
    formData.append('position_x', x);
    formData.append('position_y', y);
    
    fetch('<?= APP_URL ?>vehicle-types/update-piece-position', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar datos locales
            const pieceIndex = existingPieces.findIndex(p => p.id == pieceId);
            if (pieceIndex !== -1) {
                existingPieces[pieceIndex].position_x = x;
                existingPieces[pieceIndex].position_y = y;
            }
            
            // Si la pieza está seleccionada, actualizar el formulario
            if (selectedPieceId == pieceId) {
                document.getElementById('editPositionX').value = x;
                document.getElementById('editPositionY').value = y;
                document.getElementById('displayPositionX').value = x;
                document.getElementById('displayPositionY').value = y;
            }
            
            updatePiecesList();
            
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al actualizar la posición');
    });
}

function deletePiece() {
    if (!selectedPieceId) return;
    
    Swal.fire({
        title: '¿Eliminar pieza?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
            formData.append('piece_id', selectedPieceId);
            
            fetch('<?= APP_URL ?>vehicle-types/delete-piece', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    
                    // Remover del DOM
                    const pieceElement = document.querySelector(`[data-piece-id="${selectedPieceId}"]`);
                    if (pieceElement) pieceElement.remove();
                    
                    const listItem = document.querySelector(`.piece-list-item[data-piece-id="${selectedPieceId}"]`);
                    if (listItem) listItem.remove();
                    
                    // Remover de la lista local
                    existingPieces = existingPieces.filter(p => p.id != selectedPieceId);
                    
                    // Deseleccionar
                    deselectPiece();
                    
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error al eliminar la pieza');
            });
        }
    });
}

function selectPieceFromList(pieceId) {
    selectPiece(pieceId);
    
    // Hacer scroll hasta la pieza en la imagen
    const piece = document.querySelector(`[data-piece-id="${pieceId}"]`);
    if (piece) {
        piece.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Efecto de pulso
        piece.classList.add('pulse-effect');
        setTimeout(() => piece.classList.remove('pulse-effect'), 2000);
    }
}

function highlightPiece(pieceId) {
    const piece = document.querySelector(`[data-piece-id="${pieceId}"]`);
    if (piece) {
        piece.classList.add('highlight-effect');
        setTimeout(() => piece.classList.remove('highlight-effect'), 3000);
    }
}

function updatePiecesList() {
    // Recargar la página para actualizar la lista (método simple)
    // En una implementación más avanzada, se actualizaría dinámicamente
    setTimeout(() => location.reload(), 1000);
}

function clearAllPieces() {
    if (existingPieces.length === 0) return;
    
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
                    showNotification('success', data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error al eliminar las piezas');
            });
        }
    });
}

// Funciones de zoom
function zoomIn() {
    zoomLevel = Math.min(zoomLevel + 0.2, 3);
    applyZoom();
}

function zoomOut() {
    zoomLevel = Math.max(zoomLevel - 0.2, 0.5);
    applyZoom();
}

function resetZoom() {
    zoomLevel = 1;
    applyZoom();
}

function applyZoom() {
    const imageWrapper = document.getElementById('imageWrapper');
    if (imageWrapper) {
        imageWrapper.style.transform = `scale(${zoomLevel})`;
        imageWrapper.style.transformOrigin = 'top left';
    }
}

function adjustImageSize() {
    // Función para ajustar el tamaño de la imagen al contenedor
    const image = document.getElementById('sectionImage');
    const container = image.parentElement;
    
    if (image && container) {
        const containerWidth = container.clientWidth;
        const imageWidth = image.naturalWidth;
        
        if (imageWidth > containerWidth) {
            const scale = containerWidth / imageWidth;
            image.style.width = containerWidth + 'px';
            image.style.height = (image.naturalHeight * scale) + 'px';
        }
    }
}

// Subir imagen de sección
function uploadSectionImage(input) {
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
    formData.append('section_id', '<?= $section['id'] ?>');
    formData.append('image', file);
    
    fetch('<?= APP_URL ?>vehicle-types/upload-section-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error al subir la imagen');
    });
    
    input.value = '';
}
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
