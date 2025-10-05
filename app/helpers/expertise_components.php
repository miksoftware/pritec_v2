<?php
/**
 * Componente: Indicador de Progreso del Peritaje
 * Renderiza el indicador de progreso visual de los 12 pasos del peritaje
 * 
 * @param int $current_step El paso actual (1-12)
 * @return void
 */
function renderExpertiseProgressIndicator($current_step = 1) {
    // Validar que el paso esté en el rango correcto
    $current_step = max(1, min(12, (int)$current_step));
    
    // Calcular porcentaje
    $percentage = round(($current_step / 12) * 100);
    
    // Definir los pasos
    $steps = [
        1 => ['name' => 'Info', 'icon' => 'fas fa-info-circle'],
        2 => ['name' => 'Vehículo', 'icon' => 'fas fa-car'],
        3 => ['name' => 'Carrocería', 'icon' => 'fas fa-car-side'],
        4 => ['name' => 'Estructura', 'icon' => 'fas fa-building'],
        5 => ['name' => 'Chasis', 'icon' => 'fas fa-cogs'],
        6 => ['name' => 'Llantas', 'icon' => 'fas fa-circle'],
        7 => ['name' => 'Amortiguadores', 'icon' => 'fas fa-compress-arrows-alt'],
        8 => ['name' => 'Batería', 'icon' => 'fas fa-battery-full'],
        9 => ['name' => 'Motor', 'icon' => 'fas fa-cog'],
        10 => ['name' => 'Fugas', 'icon' => 'fas fa-tint'],
        11 => ['name' => 'Fotos', 'icon' => 'fas fa-camera'],
        12 => ['name' => 'Resumen', 'icon' => 'fas fa-check-circle']
    ];
    
    // Determinar color del badge según el progreso
    $badge_class = 'bg-primary';
    if ($current_step >= 11) {
        $badge_class = 'bg-success';
    } elseif ($current_step >= 7) {
        $badge_class = 'bg-info';
    }
    
    // Determinar color de la barra de progreso
    $progress_class = 'bg-primary';
    if ($current_step >= 11) {
        $progress_class = 'bg-success';
    } elseif ($current_step >= 7) {
        $progress_class = 'bg-info';
    }
    
    ?>
    <!-- Indicador de Progreso -->
    <div class="mb-4">
        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">
                        <i class="fas fa-tasks me-2"></i>Progreso del Peritaje
                    </span>
                    <span class="badge <?= $badge_class ?>">
                        Paso <?= $current_step ?> de 12
                    </span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar <?= $progress_class ?>" 
                         role="progressbar" 
                         style="width: <?= $percentage ?>%" 
                         aria-valuenow="<?= $percentage ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2 small">
                    <?php foreach ($steps as $step_num => $step_info): ?>
                        <?php
                        // Determinar el estado del paso
                        if ($step_num < $current_step) {
                            // Paso completado
                            $class = 'text-success';
                            $icon = 'fas fa-check-circle';
                        } elseif ($step_num == $current_step) {
                            // Paso actual
                            $class = 'text-primary';
                            $icon = 'fas fa-circle';
                        } else {
                            // Paso pendiente
                            $class = 'text-muted';
                            $icon = 'far fa-circle';
                        }
                        ?>
                        <small class="<?= $class ?>" title="<?= htmlspecialchars($step_info['name']) ?>">
                            <i class="<?= $icon ?> me-1"></i><?= htmlspecialchars($step_info['name']) ?>
                        </small>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Componente: Resumen de Paso Anterior (Información compacta)
 * Renderiza un resumen visual del paso completado anteriormente
 * 
 * @param array $data Datos a mostrar
 * @param string $alert_class Clase de Bootstrap para el alert (por defecto 'alert-info')
 * @return void
 */
function renderPreviousStepSummary($data, $alert_class = 'alert-info') {
    if (empty($data)) {
        return;
    }
    ?>
    <div class="alert <?= $alert_class ?> mb-4">
        <div class="row">
            <?php foreach ($data as $item): ?>
                <div class="col-md-<?= $item['col'] ?? 3 ?>">
                    <strong>
                        <?php if (!empty($item['icon'])): ?>
                            <i class="<?= $item['icon'] ?> me-2"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($item['label']) ?>:
                    </strong>
                    <?= htmlspecialchars($item['value']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Componente: Card Header para Secciones
 * Renderiza un header consistente para las cards de cada paso
 * 
 * @param string $title Título de la sección
 * @param string $subtitle Subtítulo (opcional)
 * @param string $icon Clase del icono Font Awesome
 * @param string $bg_class Clase de color de fondo (por defecto 'bg-dark')
 * @return void
 */
function renderSectionHeader($title, $subtitle = '', $icon = 'fas fa-clipboard-check', $bg_class = 'bg-dark') {
    ?>
    <div class="card-header <?= $bg_class ?> text-white">
        <div class="d-flex align-items-center">
            <div class="icon-circle bg-white text-dark me-3">
                <i class="<?= $icon ?>"></i>
            </div>
            <div>
                <h5 class="mb-0"><?= htmlspecialchars($title) ?></h5>
                <?php if (!empty($subtitle)): ?>
                    <small class="opacity-75"><?= htmlspecialchars($subtitle) ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Componente: Botones de Navegación de Pasos
 * Renderiza los botones de navegación estándar (Volver / Continuar)
 * 
 * @param int $current_step Paso actual
 * @param string $next_text Texto del botón siguiente (opcional)
 * @param bool $submit_disabled Si el botón de envío debe estar deshabilitado inicialmente
 * @return void
 */
function renderStepNavigation($current_step, $next_text = null, $submit_disabled = false) {
    // URLs de navegación
    $prev_url = APP_URL . 'expertise/' . ($current_step == 1 ? '' : 'step' . ($current_step - 1));
    if ($current_step == 2) {
        $prev_url = APP_URL . 'expertise/create';
    }
    
    // Texto del botón siguiente
    if ($next_text === null) {
        if ($current_step == 11) {
            $next_text = 'Finalizar y Ver Resumen';
        } elseif ($current_step == 12) {
            $next_text = 'Guardar Peritaje Completo';
        } else {
            $next_text = 'Continuar al Paso ' . ($current_step + 1);
        }
    }
    
    // Texto del botón anterior
    $prev_text = 'Volver al Paso ' . ($current_step - 1);
    if ($current_step == 1) {
        $prev_text = 'Cancelar';
    }
    
    // Icono y clase del botón siguiente
    $next_icon = $current_step >= 11 ? 'fas fa-check-double' : 'fas fa-arrow-right';
    $next_class = $current_step >= 11 ? 'btn-success' : 'btn-primary';
    
    ?>
    <div class="card-footer bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= $prev_url ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                <?= $prev_text ?>
            </a>
            
            <button type="submit" 
                    class="btn <?= $next_class ?> btn-lg px-5" 
                    id="submitBtn"
                    <?= $submit_disabled ? 'disabled' : '' ?>>
                <?php if ($current_step < 11): ?>
                    <?= $next_text ?>
                    <i class="<?= $next_icon ?> ms-2"></i>
                <?php else: ?>
                    <i class="<?= $next_icon ?> me-2"></i>
                    <?= $next_text ?>
                <?php endif; ?>
            </button>
        </div>
    </div>
    <?php
}
