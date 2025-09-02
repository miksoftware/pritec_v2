<?php
/**
 * Helpers para componentes de vistas
 */

/**
 * Renderiza el header de contenido con breadcrumbs
 * 
 * @param string $title Título principal
 * @param array $options Opciones adicionales
 * @return void
 */
function renderContentHeader($title, $options = []) {
    $config = [
        'title' => $title,
        'subtitle' => $options['subtitle'] ?? '',
        'icon' => $options['icon'] ?? 'fas fa-home',
        'breadcrumbs' => $options['breadcrumbs'] ?? [
            ['text' => 'Inicio', 'url' => APP_URL . 'dashboard']
        ],
        'actions' => $options['actions'] ?? []
    ];
    
    include APP_PATH . '/views/components/content-header.php';
}

/**
 * Crea un breadcrumb de forma rápida
 * 
 * @param array $items Array de elementos ['text' => '', 'url' => '']
 * @return array
 */
function createBreadcrumbs($items) {
    $breadcrumbs = [
        ['text' => 'Inicio', 'url' => APP_URL . 'dashboard']
    ];
    
    foreach ($items as $item) {
        $breadcrumbs[] = [
            'text' => $item['text'],
            'url' => $item['url'] ?? null
        ];
    }
    
    return $breadcrumbs;
}

/**
 * Crea una acción para el header
 * 
 * @param string $text Texto del botón
 * @param string $url URL del enlace
 * @param array $options Opciones adicionales
 * @return array
 */
function createHeaderAction($text, $url = null, $options = []) {
    $action = [
        'text' => $text,
        'url' => $url,
        'icon' => $options['icon'] ?? '',
        'class' => $options['class'] ?? 'btn-primary',
        'title' => $options['title'] ?? '',
        'onclick' => $options['onclick'] ?? ''
    ];
    
    return $action;
}

/**
 * Renderiza una tarjeta estándar de contenido
 * 
 * @param string $title Título de la tarjeta
 * @param string $content Contenido HTML
 * @param array $options Opciones adicionales
 * @return void
 */
function renderContentCard($title, $content, $options = []) {
    $icon = $options['icon'] ?? 'fas fa-list';
    $headerActions = $options['header_actions'] ?? [];
    $cardClass = $options['card_class'] ?? '';
    
    echo '<div class="card ' . $cardClass . '">';
    echo '<div class="card-header">';
    echo '<div class="d-flex justify-content-between align-items-center">';
    echo '<h5 class="card-title mb-0">';
    if ($icon) {
        echo '<i class="' . $icon . ' me-2"></i>';
    }
    echo htmlspecialchars($title);
    echo '</h5>';
    
    if (!empty($headerActions)) {
        echo '<div class="card-header-actions">';
        foreach ($headerActions as $action) {
            if (isset($action['url'])) {
                echo '<a href="' . $action['url'] . '" class="btn ' . ($action['class'] ?? 'btn-primary') . '">';
            } else {
                echo '<button type="button" class="btn ' . ($action['class'] ?? 'btn-primary') . '"';
                if (isset($action['onclick'])) {
                    echo ' onclick="' . $action['onclick'] . '"';
                }
                echo '>';
            }
            
            if (!empty($action['icon'])) {
                echo '<i class="' . $action['icon'] . ' me-2"></i>';
            }
            echo htmlspecialchars($action['text']);
            
            if (isset($action['url'])) {
                echo '</a>';
            } else {
                echo '</button>';
            }
        }
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '<div class="card-body">';
    echo $content;
    echo '</div>';
    echo '</div>';
}
?>
