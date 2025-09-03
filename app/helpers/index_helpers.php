<?php
/**
 * Helper para crear vistas index estandarizadas
 */

/**
 * Renderiza una vista index completa con plantilla estándar
 * 
 * @param array $config Configuración de la vista
 * @return void
 */
function renderIndexView($config) {
    // Configuración por defecto
    $defaults = [
        'title' => 'Lista de Elementos',
        'subtitle' => '',
        'icon' => 'fas fa-list',
        'module_name' => 'elementos',
        'show_stats' => false,
        'show_filters' => false,
        'stats' => [],
        'filters' => [],
        'data' => [],
        'columns' => [],
        'actions' => [],
        'empty_message' => 'No hay elementos registrados',
        'empty_description' => 'Comienza creando el primer elemento',
        'create_url' => '#',
        'create_text' => 'Nuevo Elemento',
        'table_id' => 'mainTable',
        'pagination' => null,
        'custom_scripts' => ''
    ];
    
    $config = array_merge($defaults, $config);
    
    // Incluir CSS común
    echo '<link href="' . ASSETS_URL . 'css/index-template.css" rel="stylesheet">';
    
    // Renderizar header
    renderContentHeader($config['title'], [
        'subtitle' => $config['subtitle'],
        'icon' => $config['icon'],
        'breadcrumbs' => createBreadcrumbs([
            ['text' => $config['title'], 'url' => null]
        ]),
        'actions' => $config['actions']
    ]);
    
    echo '<div class="content-body">';
    
    // Renderizar estadísticas si están habilitadas
    if ($config['show_stats'] && !empty($config['stats'])) {
        renderStatsCards($config['stats']);
    }
    
    // Renderizar filtros si están habilitados
    if ($config['show_filters'] && !empty($config['filters'])) {
        renderFiltersCard($config['filters']);
    }
    
    // Renderizar tabla principal
    renderMainTable($config);
    
    echo '</div>';
    
    // Renderizar scripts customizados al final
    if (!empty($config['custom_scripts'])) {
        echo $config['custom_scripts'];
    }
}

/**
 * Renderiza las tarjetas de estadísticas
 * 
 * @param array $stats Array de estadísticas
 * @return void
 */
function renderStatsCards($stats) {
    echo '<div class="row stats-row">';
    
    $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
    $icons = ['fas fa-chart-bar', 'fas fa-check-circle', 'fas fa-info-circle', 'fas fa-exclamation-triangle', 'fas fa-times-circle', 'fas fa-cog'];
    
    foreach ($stats as $index => $stat) {
        $color = $stat['color'] ?? $colors[$index % count($colors)];
        $icon = $stat['icon'] ?? $icons[$index % count($icons)];
        
        echo '<div class="col-xl-3 col-md-6">';
        echo '<div class="card stats-card bg-' . $color . ' text-white">';
        echo '<div class="card-body">';
        echo '<div class="stats-content">';
        echo '<h5 class="card-title">' . htmlspecialchars($stat['title']) . '</h5>';
        echo '<h2>' . number_format($stat['value']) . '</h2>';
        echo '</div>';
        echo '<div class="stats-icon">';
        echo '<i class="' . $icon . ' fa-2x"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Renderiza la tarjeta de filtros
 * 
 * @param array $filters Array de filtros
 * @return void
 */
function renderFiltersCard($filters) {
    echo '<div class="card filters-card">';
    echo '<div class="card-header">';
    echo '<h5 class="card-title mb-0">';
    echo '<i class="fas fa-filter me-2"></i>';
    echo 'Filtros';
    echo '</h5>';
    echo '</div>';
    echo '<div class="card-body">';
    echo '<form method="GET" class="filters-form">';
    
    echo '<div class="row">';
    foreach ($filters as $filter) {
        echo '<div class="col-md-' . ($filter['width'] ?? '4') . '">';
        echo '<div class="filter-group">';
        echo '<label class="form-label">' . htmlspecialchars($filter['label']) . '</label>';
        
        switch ($filter['type']) {
            case 'text':
                echo '<input type="text" name="' . $filter['name'] . '" class="form-control" placeholder="' . ($filter['placeholder'] ?? '') . '" value="' . ($_GET[$filter['name']] ?? '') . '">';
                break;
            case 'select':
                echo '<select name="' . $filter['name'] . '" class="form-select">';
                echo '<option value="">Todos</option>';
                foreach ($filter['options'] as $value => $text) {
                    $selected = ($_GET[$filter['name']] ?? '') == $value ? 'selected' : '';
                    echo '<option value="' . $value . '" ' . $selected . '>' . htmlspecialchars($text) . '</option>';
                }
                echo '</select>';
                break;
            case 'date':
                echo '<input type="date" name="' . $filter['name'] . '" class="form-control" value="' . ($_GET[$filter['name']] ?? '') . '">';
                break;
        }
        
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    
    echo '<div class="filter-actions">';
    echo '<button type="submit" class="btn btn-primary">';
    echo '<i class="fas fa-search me-2"></i>Filtrar';
    echo '</button>';
    echo '<a href="?" class="btn btn-outline-secondary">';
    echo '<i class="fas fa-times me-2"></i>Limpiar';
    echo '</a>';
    echo '</div>';
    
    echo '</form>';
    echo '</div>';
    echo '</div>';
}

/**
 * Renderiza la tabla principal
 * 
 * @param array $config Configuración de la tabla
 * @return void
 */
function renderMainTable($config) {
    echo '<div class="card main-table-card">';
    echo '<div class="card-header">';
    echo '<h5 class="card-title">';
    echo '<i class="fas fa-list me-2"></i>';
    echo 'Lista de ' . $config['title'];
    echo '</h5>';
    echo '</div>';
    
    if (!empty($config['data'])) {
        echo '<div class="card-body">';
        echo '<div class="table-responsive">';
        echo '<table class="table main-table table-hover" id="' . $config['table_id'] . '">';
        
        // Header de la tabla
        echo '<thead>';
        echo '<tr>';
        foreach ($config['columns'] as $column) {
            $class = '';
            switch ($column['type'] ?? 'text') {
                case 'id':
                    $class = 'table-id-col';
                    break;
                case 'status':
                    $class = 'table-status-col';
                    break;
                case 'date':
                    $class = 'table-date-col';
                    break;
                case 'actions':
                    $class = 'table-actions-col';
                    break;
            }
            
            echo '<th class="' . $class . '">' . htmlspecialchars($column['title']) . '</th>';
        }
        echo '</tr>';
        echo '</thead>';
        
        // Cuerpo de la tabla
        echo '<tbody>';
        foreach ($config['data'] as $row) {
            echo '<tr id="row-' . ($row['id'] ?? '') . '">';
            
            foreach ($config['columns'] as $column) {
                echo '<td>';
                renderTableCell($row, $column);
                echo '</td>';
            }
            
            echo '</tr>';
        }
        echo '</tbody>';
        
        echo '</table>';
        echo '</div>';
        echo '</div>';
        
        // Paginación
        if ($config['pagination']) {
            echo '<div class="pagination-wrapper">';
            echo $config['pagination'];
            echo '</div>';
        }
        
    } else {
        // Estado vacío
        echo '<div class="card-body">';
        echo '<div class="empty-state">';
        echo '<i class="' . $config['icon'] . ' empty-state-icon"></i>';
        echo '<h5>' . $config['empty_message'] . '</h5>';
        echo '<p>' . $config['empty_description'] . '</p>';
        echo '<a href="' . $config['create_url'] . '" class="btn btn-primary">';
        echo '<i class="fas fa-plus me-2"></i>';
        echo $config['create_text'];
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Renderiza una celda de la tabla según su tipo
 * 
 * @param array $row Datos de la fila
 * @param array $column Configuración de la columna
 * @return void
 */
function renderTableCell($row, $column) {
    $value = $row[$column['field']] ?? '';
    
    switch ($column['type'] ?? 'text') {
        case 'id':
            echo '<span class="text-muted">' . $value . '</span>';
            break;
            
        case 'user':
            $name = isset($column['subtitle_field']) 
                ? $value . ' ' . ($row[$column['subtitle_field']] ?? '') 
                : $value;
            $avatar = strtoupper(substr($name, 0, 1));
            echo '<div class="item-info">';
            echo '<div class="user-avatar">' . $avatar . '</div>';
            echo '<div>';
            echo '<div class="item-name">' . htmlspecialchars($name) . '</div>';
            if (isset($column['email_field']) && !empty($row[$column['email_field']])) {
                echo '<div class="item-description text-muted">' . htmlspecialchars($row[$column['email_field']]) . '</div>';
            }
            echo '</div>';
            echo '</div>';
            break;

        case 'contact':
            echo '<div>';
            if (!empty($value)) {
                echo '<div><i class="fas fa-phone me-1"></i>' . htmlspecialchars($value) . '</div>';
            }
            if (isset($column['address_field']) && !empty($row[$column['address_field']])) {
                $address = $row[$column['address_field']];
                $shortAddress = strlen($address) > 30 ? substr($address, 0, 30) . '...' : $address;
                echo '<small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>' . htmlspecialchars($shortAddress) . '</small>';
            }
            echo '</div>';
            break;

        case 'vehicle_type':
            $badge_class = $value === 'carro' ? 'primary' : 'info';
            $icon = $value === 'carro' ? 'car' : 'motorcycle';
            echo '<span class="badge bg-' . $badge_class . '">';
            echo '<i class="fas fa-' . $icon . ' me-1"></i>';
            echo ucfirst($value);
            echo '</span>';
            break;

        case 'description':
            if (!empty($value)) {
                $maxLength = $column['max_length'] ?? 50;
                if (strlen($value) > $maxLength) {
                    echo '<span title="' . htmlspecialchars($value) . '">';
                    echo htmlspecialchars(substr($value, 0, $maxLength)) . '...';
                    echo '</span>';
                } else {
                    echo htmlspecialchars($value);
                }
            } else {
                echo '<span class="text-muted">Sin descripción</span>';
            }
            break;
            
        case 'status':
            $statusClass = 'secondary';
            $statusText = 'Desconocido';
            
            if (isset($column['status_map'][$value])) {
                $statusConfig = $column['status_map'][$value];
                $statusClass = $statusConfig['class'];
                $statusText = $statusConfig['text'];
            }
            
            echo '<span class="badge bg-' . $statusClass . ' status-badge">' . $statusText . '</span>';
            break;
            
        case 'date':
            if ($value) {
                echo '<span class="text-muted">' . date('d/m/Y', strtotime($value)) . '</span>';
            } else {
                echo '<span class="text-muted">-</span>';
            }
            break;
            
        case 'datetime':
            if ($value) {
                echo '<span class="text-muted">' . date('d/m/Y H:i', strtotime($value)) . '</span>';
            } else {
                echo '<span class="text-muted">Nunca</span>';
            }
            break;
            
        case 'actions':
            echo '<div class="action-buttons">';
            if (isset($column['buttons'])) {
                foreach ($column['buttons'] as $button) {
                    renderActionButton($row, $button);
                }
            }
            echo '</div>';
            break;
            
        case 'currency':
            echo '<span class="text-success fw-bold">$' . number_format($value, 2) . '</span>';
            break;
            
        case 'number':
            echo '<span class="fw-bold">' . number_format($value) . '</span>';
            break;
            
        default:
            echo htmlspecialchars($value);
            break;
    }
}

/**
 * Renderiza un botón de acción
 * 
 * @param array $row Datos de la fila
 * @param array $button Configuración del botón
 * @return void
 */
function renderActionButton($row, $button) {
    $url = str_replace('{id}', $row['id'] ?? '', $button['url'] ?? '#');
    $class = $button['class'] ?? 'btn-outline-primary';
    $icon = $button['icon'] ?? 'fas fa-cog';
    $title = $button['title'] ?? '';
    
    // Procesar onclick con reemplazos de variables
    $onclick = '';
    if (isset($button['onclick'])) {
        $onclick = $button['onclick'];
        foreach ($row as $key => $value) {
            // Asegurar que $value no sea null para evitar deprecation warning
            $safeValue = $value ?? '';
            $onclick = str_replace('{' . $key . '}', $safeValue, $onclick);
        }
    }
    
    // Verificar condiciones especiales
    if (isset($button['condition'])) {
        // Si la condición es una función, ejecutarla
        if (is_callable($button['condition'])) {
            if (!$button['condition']($row)) {
                return; // No mostrar el botón si la condición falla
            }
        } else {
            // Condiciones predefinidas por string
            switch ($button['condition']) {
                case 'status_toggle':
                    if ($row['status'] === 'active') {
                        $icon = 'fas fa-user-slash';
                        $title = 'Desactivar';
                        $class = 'btn-outline-warning';
                    } else {
                        $icon = 'fas fa-user-check';
                        $title = 'Activar';
                        $class = 'btn-outline-success';
                    }
                    break;
                case 'not_current_user':
                    // No mostrar botón si es el usuario actual
                    if ($row['id'] == ($_SESSION['user_id'] ?? 0)) {
                        return;
                    }
                    break;
            }
        }
    }
    
    if ($onclick) {
        echo '<button type="button" class="btn btn-sm action-btn ' . $class . '" onclick="' . $onclick . '" title="' . $title . '">';
        echo '<i class="' . $icon . '"></i>';
        echo '</button>';
    } else {
        echo '<a href="' . $url . '" class="btn btn-sm action-btn ' . $class . '" title="' . $title . '">';
        echo '<i class="' . $icon . '"></i>';
        echo '</a>';
    }
}
?>
