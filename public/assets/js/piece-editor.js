/**
 * Editor de Piezas para Tipos de Vehículos
 * Maneja la funcionalidad de posicionamiento y gestión de piezas
 */

class PieceEditor {
    constructor() {
        this.selectedPieceId = null;
        this.zoomLevel = 1;
        this.isDragging = false;
        this.existingPieces = [];
        this.imageContainer = null;
        this.sectionImage = null;
        
        // Bind methods
        this.handleImageClick = this.handleImageClick.bind(this);
        this.handlePieceClick = this.handlePieceClick.bind(this);
    }

    /**
     * Inicializar el editor
     */
    init(pieces = []) {
        this.existingPieces = pieces;
        this.imageContainer = document.getElementById('imageWrapper');
        this.sectionImage = document.getElementById('sectionImage');
        
        if (!this.imageContainer || !this.sectionImage) {
            console.warn('Elementos del editor no encontrados');
            return;
        }

        this.setupImageContainer();
        this.setupEventListeners();
        this.renderExistingPieces();
        this.setupForms();
        
        console.log('Editor de piezas inicializado con', pieces.length, 'piezas');
    }

    /**
     * Configurar el contenedor de la imagen
     */
    setupImageContainer() {
        // Asegurar que el contenedor tenga position relative
        this.imageContainer.style.position = 'relative';
        this.imageContainer.style.display = 'inline-block';
        this.imageContainer.style.cursor = 'crosshair';
        
        // Agregar evento de clic al contenedor
        this.imageContainer.addEventListener('click', this.handleImageClick);
    }

    /**
     * Configurar event listeners
     */
    setupEventListeners() {
        // Ajustar posiciones cuando la imagen cargue
        this.sectionImage.addEventListener('load', () => {
            this.adjustImageSize();
            this.renderExistingPieces();
        });

        if (this.sectionImage.complete) {
            this.adjustImageSize();
            this.renderExistingPieces();
        }

        // Event listener para cambios en el número de pieza
        const pieceNumberInput = document.getElementById('pieceNumber');
        if (pieceNumberInput) {
            pieceNumberInput.addEventListener('input', (e) => {
                this.updateTempMarker(e.target.value);
            });
        }
    }

    /**
     * Manejar clic en la imagen
     */
    handleImageClick(event) {
        if (this.isDragging) return;

        // Solo procesar si el clic fue directamente en la imagen o el contenedor
        if (event.target !== this.sectionImage && event.target !== this.imageContainer) {
            return;
        }

        // Mostrar panel de agregar pieza
        this.showAddPiecePanel();

        // Obtener coordenadas relativas al contenedor de la imagen
        const rect = this.imageContainer.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        console.log('Clic detectado en:', { x, y, containerRect: rect });

        // Verificar que el clic esté dentro de los límites de la imagen
        const imageRect = this.sectionImage.getBoundingClientRect();
        const containerRect = this.imageContainer.getBoundingClientRect();
        
        const imageX = event.clientX - imageRect.left;
        const imageY = event.clientY - imageRect.top;

        if (imageX < 0 || imageY < 0 || imageX > imageRect.width || imageY > imageRect.height) {
            console.log('Clic fuera de la imagen');
            return;
        }

        // Calcular coordenadas para la base de datos (normalizadas a 400x400)
        const scaleX = 400 / imageRect.width;
        const scaleY = 400 / imageRect.height;
        
        const dbX = Math.round(imageX * scaleX);
        const dbY = Math.round(imageY * scaleY);

        // Guardar coordenadas en los campos del formulario
        this.setFormPosition(dbX, dbY);

        // Mostrar marcador temporal en la posición visual
        this.showTempMarker(x, y);

        // Habilitar botón de agregar
        this.enableAddButton();

        console.log('Posición calculada:', {
            visual: { x, y },
            image: { x: imageX, y: imageY },
            database: { x: dbX, y: dbY },
            scale: { x: scaleX, y: scaleY }
        });
    }

    /**
     * Mostrar marcador temporal
     */
    showTempMarker(x, y) {
        let tempMarker = document.getElementById('tempPieceMarker');
        
        if (!tempMarker) {
            tempMarker = document.createElement('div');
            tempMarker.id = 'tempPieceMarker';
            tempMarker.className = 'piece-marker piece-marker-temp';
            tempMarker.innerHTML = '<span class="piece-number">?</span>';
            this.imageContainer.appendChild(tempMarker);
        }

        tempMarker.style.position = 'absolute';
        tempMarker.style.left = x + 'px';
        tempMarker.style.top = y + 'px';
        tempMarker.style.display = 'block';
        tempMarker.style.zIndex = '1000';

        // Actualizar número si existe
        const pieceNumber = document.getElementById('pieceNumber')?.value || '?';
        tempMarker.querySelector('.piece-number').textContent = pieceNumber;
    }

    /**
     * Actualizar marcador temporal cuando cambie el número
     */
    updateTempMarker(number) {
        const tempMarker = document.getElementById('tempPieceMarker');
        if (tempMarker) {
            tempMarker.querySelector('.piece-number').textContent = number || '?';
        }
    }

    /**
     * Renderizar piezas existentes
     */
    renderExistingPieces() {
        // Limpiar piezas existentes del DOM
        const existingMarkers = this.imageContainer.querySelectorAll('.piece-marker:not(.piece-marker-temp)');
        existingMarkers.forEach(marker => marker.remove());

        if (!this.sectionImage.complete) return;

        const imageRect = this.sectionImage.getBoundingClientRect();
        const containerRect = this.imageContainer.getBoundingClientRect();
        
        // Calcular escala para convertir coordenadas de BD a visuales
        const scaleX = imageRect.width / 400;
        const scaleY = imageRect.height / 400;
        
        // Calcular offset de la imagen dentro del contenedor
        const offsetX = imageRect.left - containerRect.left;
        const offsetY = imageRect.top - containerRect.top;

        this.existingPieces.forEach(piece => {
            const visualX = (piece.position_x * scaleX) + offsetX;
            const visualY = (piece.position_y * scaleY) + offsetY;

            this.createPieceMarker(piece, visualX, visualY);
        });

        console.log('Piezas renderizadas:', this.existingPieces.length, {
            imageSize: { w: imageRect.width, h: imageRect.height },
            scale: { x: scaleX, y: scaleY },
            offset: { x: offsetX, y: offsetY }
        });
    }

    /**
     * Crear marcador de pieza en el DOM
     */
    createPieceMarker(piece, x, y) {
        const marker = document.createElement('div');
        marker.className = 'piece-marker';
        marker.dataset.pieceId = piece.id;
        marker.dataset.pieceNumber = piece.piece_number;
        marker.dataset.originalX = piece.position_x;
        marker.dataset.originalY = piece.position_y;
        marker.title = `Pieza #${piece.piece_number}`;
        
        marker.style.position = 'absolute';
        marker.style.left = x + 'px';
        marker.style.top = y + 'px';
        marker.style.zIndex = '500';
        
        marker.innerHTML = `<span class="piece-number">${piece.piece_number}</span>`;
        
        // Agregar evento de clic
        marker.addEventListener('click', (e) => {
            e.stopPropagation();
            this.handlePieceClick(piece.id);
        });

        // Agregar funcionalidad de arrastre
        this.makeDraggable(marker);
        
        this.imageContainer.appendChild(marker);
    }

    /**
     * Manejar clic en pieza
     */
    handlePieceClick(pieceId) {
        this.selectPiece(pieceId);
    }

    /**
     * Hacer una pieza arrastrable
     */
    makeDraggable(marker) {
        let startX, startY, startLeft, startTop;

        marker.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            this.isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = parseInt(marker.style.left);
            startTop = parseInt(marker.style.top);

            const handleMouseMove = (e) => {
                if (!this.isDragging) return;

                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;
                
                let newLeft = startLeft + deltaX;
                let newTop = startTop + deltaY;

                // Limitar a los bordes del contenedor
                const containerRect = this.imageContainer.getBoundingClientRect();
                newLeft = Math.max(0, Math.min(newLeft, containerRect.width - 30));
                newTop = Math.max(0, Math.min(newTop, containerRect.height - 30));

                marker.style.left = newLeft + 'px';
                marker.style.top = newTop + 'px';
            };

            const handleMouseUp = () => {
                if (this.isDragging) {
                    this.isDragging = false;
                    this.updatePiecePosition(marker);
                }
                
                document.removeEventListener('mousemove', handleMouseMove);
                document.removeEventListener('mouseup', handleMouseUp);
            };

            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
        });
    }

    /**
     * Actualizar posición de pieza en la base de datos
     */
    updatePiecePosition(marker) {
        const pieceId = marker.dataset.pieceId;
        const visualX = parseInt(marker.style.left);
        const visualY = parseInt(marker.style.top);

        // Convertir a coordenadas de base de datos
        const imageRect = this.sectionImage.getBoundingClientRect();
        const containerRect = this.imageContainer.getBoundingClientRect();
        
        const offsetX = imageRect.left - containerRect.left;
        const offsetY = imageRect.top - containerRect.top;
        
        const imageX = visualX - offsetX;
        const imageY = visualY - offsetY;
        
        const scaleX = 400 / imageRect.width;
        const scaleY = 400 / imageRect.height;
        
        const dbX = Math.round(imageX * scaleX);
        const dbY = Math.round(imageY * scaleY);

        // Enviar actualización al servidor
        const formData = new FormData();
        formData.append(window.CSRF_TOKEN_NAME || 'csrf_token', window.CSRF_TOKEN || '');
        formData.append('piece_id', pieceId);
        formData.append('position_x', dbX);
        formData.append('position_y', dbY);

        fetch(`${window.APP_URL}vehicle-types/update-piece-position`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar datos locales
                const piece = this.existingPieces.find(p => p.id == pieceId);
                if (piece) {
                    piece.position_x = dbX;
                    piece.position_y = dbY;
                }
                console.log('Posición actualizada:', { pieceId, dbX, dbY });
            } else {
                window.showNotification?.('error', data.message || 'Error al actualizar posición');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showNotification?.('error', 'Error al actualizar la posición');
        });
    }

    /**
     * Configurar formularios
     */
    setupForms() {
        // Formulario de agregar pieza
        const addForm = document.getElementById('addPieceForm');
        if (addForm) {
            addForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.addPiece();
            });
        }

        // Formulario de editar pieza
        const editForm = document.getElementById('editPieceForm');
        if (editForm) {
            editForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.updatePiece();
            });
        }
    }

    /**
     * Agregar nueva pieza
     */
    addPiece() {
        const form = document.getElementById('addPieceForm');
        const formData = new FormData(form);
        
        // Validar número
        const pieceNumber = formData.get('piece_number');
        if (!this.validatePieceNumber(pieceNumber)) {
            window.showNotification?.('error', 'Por favor corrige los errores en el formulario');
            return;
        }

        fetch(`${window.APP_URL}vehicle-types/add-piece`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.showNotification?.('success', data.message);
                
                // Agregar pieza a la lista local
                this.existingPieces.push(data.piece);
                
                // Re-renderizar piezas
                this.renderExistingPieces();
                
                // Limpiar formulario
                this.resetAddForm();
                
            } else {
                window.showNotification?.('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showNotification?.('error', 'Error al agregar la pieza');
        });
    }

    /**
     * Validar número de pieza
     */
    validatePieceNumber(number) {
        const input = document.getElementById('pieceNumber');
        const feedback = input?.nextElementSibling;
        
        if (!number) {
            input?.classList.remove('is-valid', 'is-invalid');
            if (feedback) feedback.textContent = '';
            return false;
        }
        
        // Verificar si ya existe
        const exists = this.existingPieces.some(piece => piece.piece_number == number);
        
        if (exists) {
            input?.classList.remove('is-valid');
            input?.classList.add('is-invalid');
            if (feedback) feedback.textContent = 'Este número ya está en uso';
            return false;
        } else {
            input?.classList.remove('is-invalid');
            input?.classList.add('is-valid');
            if (feedback) feedback.textContent = '';
            return true;
        }
    }

    /**
     * Seleccionar pieza
     */
    selectPiece(pieceId) {
        // Deseleccionar pieza anterior
        if (this.selectedPieceId) {
            const prevMarker = this.imageContainer.querySelector(`[data-piece-id="${this.selectedPieceId}"]`);
            prevMarker?.classList.remove('selected');
        }

        // Seleccionar nueva pieza
        this.selectedPieceId = pieceId;
        const marker = this.imageContainer.querySelector(`[data-piece-id="${pieceId}"]`);
        marker?.classList.add('selected');

        // Cargar datos en formulario de edición
        const piece = this.existingPieces.find(p => p.id == pieceId);
        if (piece) {
            this.loadPieceInEditForm(piece);
        }

        // Mostrar panel de edición
        this.showEditPiecePanel();
    }

    /**
     * Cargar pieza en formulario de edición
     */
    loadPieceInEditForm(piece) {
        const form = document.getElementById('editPieceForm');
        if (!form) return;

        const fields = {
            'editPieceId': piece.id,
            'editPieceNumber': piece.piece_number,
            'editPieceName': piece.piece_name || '',
            'editPositionX': piece.position_x,
            'editPositionY': piece.position_y,
            'displayPositionX': piece.position_x,
            'displayPositionY': piece.position_y
        };

        Object.entries(fields).forEach(([id, value]) => {
            const field = document.getElementById(id);
            if (field) field.value = value;
        });
    }

    /**
     * Métodos de utilidad para el UI
     */
    showAddPiecePanel() {
        const addPanel = document.getElementById('addPiecePanel');
        const editPanel = document.getElementById('editPiecePanel');
        
        if (addPanel) addPanel.style.display = 'block';
        if (editPanel) editPanel.style.display = 'none';
    }

    showEditPiecePanel() {
        const addPanel = document.getElementById('addPiecePanel');
        const editPanel = document.getElementById('editPiecePanel');
        
        if (addPanel) addPanel.style.display = 'none';
        if (editPanel) editPanel.style.display = 'block';
    }

    setFormPosition(x, y) {
        const posXField = document.getElementById('positionX');
        const posYField = document.getElementById('positionY');
        
        if (posXField) posXField.value = x;
        if (posYField) posYField.value = y;
    }

    enableAddButton() {
        const addBtn = document.getElementById('addPieceBtn');
        if (addBtn) addBtn.disabled = false;
        
        // Enfocar en el campo de número
        const pieceNumberInput = document.getElementById('pieceNumber');
        if (pieceNumberInput) pieceNumberInput.focus();
    }

    resetAddForm() {
        const form = document.getElementById('addPieceForm');
        if (form) form.reset();
        
        const tempMarker = document.getElementById('tempPieceMarker');
        if (tempMarker) tempMarker.style.display = 'none';
        
        const addBtn = document.getElementById('addPieceBtn');
        if (addBtn) addBtn.disabled = true;
        
        const pieceNumberInput = document.getElementById('pieceNumber');
        if (pieceNumberInput) {
            pieceNumberInput.classList.remove('is-valid', 'is-invalid');
        }
    }

    adjustImageSize() {
        if (!this.sectionImage) return;
        
        const container = this.sectionImage.parentElement;
        if (!container) return;
        
        const containerWidth = container.clientWidth;
        const imageWidth = this.sectionImage.naturalWidth;
        
        if (imageWidth > containerWidth) {
            const scale = containerWidth / imageWidth;
            this.sectionImage.style.width = containerWidth + 'px';
            this.sectionImage.style.height = (this.sectionImage.naturalHeight * scale) + 'px';
        }
    }
}

// Instancia global del editor
window.pieceEditor = new PieceEditor();
