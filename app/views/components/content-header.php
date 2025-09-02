<?php
/**
 * Componente de Header de Contenido con Breadcrumbs
 * 
 * @param array $config Configuración del header
 * Estructura del $config:
 * [
 *     'title' => 'Título principal',
 *     'subtitle' => 'Subtítulo opcional',
 *     'icon' => 'fas fa-users', // Clase del icono
 *     'breadcrumbs' => [
 *         ['text' => 'Inicio', 'url' => APP_URL . 'dashboard'],
 *         ['text' => 'Usuarios', 'url' => null] // null = activo
 *     ],
 *     'actions' => [
 *         [
 *             'text' => 'Nuevo Usuario',
 *             'url' => APP_URL . 'users/create',
 *             'icon' => 'fas fa-plus',
 *             'class' => 'btn-primary'
 *         ]
 *     ]
 * ]
 */

// Configuración por defecto
$defaultConfig = [
    'title' => 'Título',
    'subtitle' => '',
    'icon' => 'fas fa-home',
    'breadcrumbs' => [
        ['text' => 'Inicio', 'url' => APP_URL . 'dashboard']
    ],
    'actions' => []
];

// Fusionar configuración
$config = array_merge($defaultConfig, $config ?? []);
?>

<!-- Content Header Component -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="content-header-left">
            <h1 class="h3 mb-1">
                <?php if (!empty($config['icon'])): ?>
                    <i class="<?= $config['icon'] ?> me-2"></i>
                <?php endif; ?>
                <?= htmlspecialchars($config['title']) ?>
            </h1>
            
            <?php if (!empty($config['subtitle'])): ?>
                <p class="text-muted mb-2"><?= htmlspecialchars($config['subtitle']) ?></p>
            <?php endif; ?>
            
            <?php if (!empty($config['breadcrumbs'])): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <?php foreach ($config['breadcrumbs'] as $index => $breadcrumb): ?>
                            <?php if ($breadcrumb['url'] === null || $index === count($config['breadcrumbs']) - 1): ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?= htmlspecialchars($breadcrumb['text']) ?>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item">
                                    <a href="<?= $breadcrumb['url'] ?>">
                                        <?= htmlspecialchars($breadcrumb['text']) ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($config['actions'])): ?>
            <div class="content-header-actions">
                <div class="btn-group" role="group">
                    <?php foreach ($config['actions'] as $action): ?>
                        <?php if (isset($action['url'])): ?>
                            <a href="<?= $action['url'] ?>" 
                               class="btn <?= $action['class'] ?? 'btn-primary' ?>"
                               <?= isset($action['title']) ? 'title="' . htmlspecialchars($action['title']) . '"' : '' ?>>
                                <?php if (!empty($action['icon'])): ?>
                                    <i class="<?= $action['icon'] ?> me-2"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($action['text']) ?>
                            </a>
                        <?php else: ?>
                            <button type="button" 
                                    class="btn <?= $action['class'] ?? 'btn-primary' ?>"
                                    <?= isset($action['onclick']) ? 'onclick="' . $action['onclick'] . '"' : '' ?>
                                    <?= isset($action['title']) ? 'title="' . htmlspecialchars($action['title']) . '"' : '' ?>>
                                <?php if (!empty($action['icon'])): ?>
                                    <i class="<?= $action['icon'] ?> me-2"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($action['text']) ?>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Estilos específicos para el componente content-header */
.content-header {
    background: white;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e0e6ed;
    margin-bottom: 1.5rem;
    border-radius: 8px 8px 0 0;
}

.content-header-left h1 {
    color: #2c3e50;
    font-weight: 600;
}

.content-header-actions .btn-group .btn {
    margin-left: 0.25rem;
}

.content-header-actions .btn-group .btn:first-child {
    margin-left: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .content-header {
        padding: 1rem;
    }
    
    .content-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .content-header-actions {
        width: 100%;
    }
    
    .content-header-actions .btn-group {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .content-header-actions .btn {
        flex: 1;
        margin: 0 !important;
    }
}
</style>
