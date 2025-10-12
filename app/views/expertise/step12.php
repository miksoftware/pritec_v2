<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/expertise.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}
if (!function_exists('renderExpertiseProgressIndicator')) {
    require_once APP_PATH . '/helpers/expertise_components.php';
}

// Detectar si estamos en modo visualización
$view_mode = isset($view_mode) && $view_mode === true;
$expertise_id = $expertise_id ?? null;

// Configurar el header de contenido
if ($view_mode) {
    renderContentHeader('Detalles del Peritaje', [
        'subtitle' => 'Resumen completo del peritaje',
        'icon' => 'fas fa-eye',
        'breadcrumbs' => createBreadcrumbs([
            ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
            ['text' => 'Detalles', 'url' => null]
        ])
    ]);
} else {
    renderContentHeader('Nuevo Peritaje Completo', [
        'subtitle' => 'Paso 12 de 12: Resumen Final',
        'icon' => 'fas fa-check-circle',
        'breadcrumbs' => createBreadcrumbs([
            ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
            ['text' => 'Nuevo Peritaje Completo', 'url' => null]
        ])
    ]);
}
?>

<?php if (!$view_mode): ?>
    <?php renderExpertiseProgressIndicator(12); ?>
<?php endif; ?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            <?php if ($view_mode): ?>
                <!-- Alerta de información (modo visualización) -->
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Visualización de Peritaje</h5>
                    <p class="mb-0">Está viendo los detalles completos del peritaje. Puede generar el PDF o volver al listado.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                
                <!-- Botones de acción en modo visualización -->
                <div class="mb-4 d-flex gap-2">
                    <a href="<?= APP_URL ?>expertise" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                    </a>
                    <a href="<?= APP_URL ?>expertise/pdf/<?= $expertise_id ?>" class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Generar PDF
                    </a>
                    <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar Peritaje
                    </a>
                </div>
            <?php else: ?>
                <!-- Alerta de éxito (modo creación) -->
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-check-circle me-2"></i>¡Todos los pasos completados!</h5>
                    <p class="mb-0">Ha completado exitosamente todos los 11 pasos del peritaje. Revise el resumen a continuación y haga clic en "Guardar Peritaje" para finalizar.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Formulario de confirmación -->
            <form id="step12Form" method="POST" action="<?= APP_URL ?>expertise/save-final">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <?php
                // Obtener datos de la sesión
                $step1 = $_SESSION['expertise_step1'] ?? [];
                $step2 = $_SESSION['expertise_step2'] ?? [];
                $step3 = $_SESSION['expertise_step3'] ?? [];
                $step4 = $_SESSION['expertise_step4'] ?? [];
                $step5 = $_SESSION['expertise_step5'] ?? [];
                $step6 = $_SESSION['expertise_step6'] ?? [];
                $step7 = $_SESSION['expertise_step7'] ?? [];
                $step8 = $_SESSION['expertise_step8'] ?? [];
                $step9 = $_SESSION['expertise_step9'] ?? [];
                $step10 = $_SESSION['expertise_step10'] ?? [];
                $step11 = $_SESSION['expertise_step11'] ?? [];
                ?>
                
                <!-- Paso 1: Información del Servicio -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Paso 1: Información del Servicio</strong>
                        </div>
                        <?php if ($view_mode && isset($expertise_id)): ?>
                        <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/1" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php elseif (!$view_mode): ?>
                        <a href="<?= APP_URL ?>expertise/create" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Fecha:</strong><br>
                                <?= htmlspecialchars($step1['service_date'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3">
                                <strong>N° Servicio:</strong><br>
                                <?= htmlspecialchars($step1['service_number'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Servicio Para:</strong><br>
                                <?= htmlspecialchars($step1['service_for'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Convenio:</strong><br>
                                <?= htmlspecialchars($step1['agreement'] ?? 'N/A') ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <strong>Cliente:</strong><br>
                                <?= htmlspecialchars($step1['client_name'] ?? 'N/A') ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Paso 2: Datos del Vehículo -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-car me-2"></i>
                            <strong>Paso 2: Datos del Vehículo</strong>
                        </div>
                        <?php if ($view_mode && isset($expertise_id)): ?>
                        <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/2" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php elseif (!$view_mode): ?>
                        <a href="<?= APP_URL ?>expertise/step2" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <strong>Placa:</strong><br>
                                <span class="badge bg-dark"><?= htmlspecialchars($step2['placa'] ?? 'N/A') ?></span>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Marca:</strong><br>
                                <?= htmlspecialchars($step2['marca'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Línea:</strong><br>
                                <?= htmlspecialchars($step2['linea'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Modelo:</strong><br>
                                <?= htmlspecialchars($step2['modelo'] ?? 'N/A') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <strong>Color:</strong><br>
                                <?= htmlspecialchars($step2['color'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>Kilometraje:</strong><br>
                                <?= htmlspecialchars($step2['kilometraje'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>N° Motor:</strong><br>
                                <?= htmlspecialchars($step2['numero_motor'] ?? 'N/A') ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <strong>N° Chasis:</strong><br>
                                <?= htmlspecialchars($step2['numero_chasis'] ?? 'N/A') ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pasos 3, 4, 5: Inspecciones -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-clipboard-check me-2"></i>
                        <strong>Pasos 3, 4, 5: Inspecciones (Carrocería, Estructura, Chasis)</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <h5 class="text-primary">
                                    <i class="fas fa-car me-2"></i>Carrocería
                                </h5>
                                <h2 class="text-success">
                                    <?= $total_inspeccionesCarroceria ?? count($step3['inspecciones'] ?? []) ?>
                                </h2>
                                <p class="mb-0">piezas inspeccionadas</p>
                                <?php if ($view_mode && isset($expertise_id)): ?>
                                <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/3" class="btn btn-sm btn-warning mt-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <?php elseif (!$view_mode): ?>
                                <a href="<?= APP_URL ?>expertise/step3" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 text-center">
                                <h5 class="text-primary">
                                    <i class="fas fa-building me-2"></i>Estructura
                                </h5>
                                <h2 class="text-success">
                                    <?= $total_inspeccionesEstructura ?? count($step4['inspecciones'] ?? []) ?>
                                </h2>
                                <p class="mb-0">piezas inspeccionadas</p>
                                <?php if ($view_mode && isset($expertise_id)): ?>
                                <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/4" class="btn btn-sm btn-warning mt-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <?php elseif (!$view_mode): ?>
                                <a href="<?= APP_URL ?>expertise/step4" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 text-center">
                                <h5 class="text-primary">
                                    <i class="fas fa-cogs me-2"></i>Chasis
                                </h5>
                                <h2 class="text-success">
                                    <?= $total_inspeccionesChasis ?? count($step5['inspecciones'] ?? []) ?>
                                </h2>
                                <p class="mb-0">piezas inspeccionadas</p>
                                <?php if ($view_mode && isset($expertise_id)): ?>
                                <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/5" class="btn btn-sm btn-warning mt-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <?php elseif (!$view_mode): ?>
                                <a href="<?= APP_URL ?>expertise/step5" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pasos 6, 7, 8: Porcentajes -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-percentage me-2"></i>
                        <strong>Pasos 6, 7, 8: Evaluaciones de Porcentaje</strong>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary"><i class="fas fa-circle me-2"></i>Llantas</h6>
                                <small>Ant. Izq: <strong><?= htmlspecialchars($step6['llanta_anterior_izquierda'] ?? '0') ?>%</strong></small><br>
                                <small>Ant. Der: <strong><?= htmlspecialchars($step6['llanta_anterior_derecha'] ?? '0') ?>%</strong></small><br>
                                <small>Post. Izq: <strong><?= htmlspecialchars($step6['llanta_posterior_izquierda'] ?? '0') ?>%</strong></small><br>
                                <small>Post. Der: <strong><?= htmlspecialchars($step6['llanta_posterior_derecha'] ?? '0') ?>%</strong></small>
                                <div class="mt-2">
                                    <?php if ($view_mode && isset($expertise_id)): ?>
                                    <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/6" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <?php elseif (!$view_mode): ?>
                                    <a href="<?= APP_URL ?>expertise/step6" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary"><i class="fas fa-compress-arrows-alt me-2"></i>Amortiguadores</h6>
                                <small>Ant. Izq: <strong><?= htmlspecialchars($step7['amortiguador_anterior_izquierdo'] ?? '0') ?>%</strong></small><br>
                                <small>Ant. Der: <strong><?= htmlspecialchars($step7['amortiguador_anterior_derecho'] ?? '0') ?>%</strong></small><br>
                                <small>Post. Izq: <strong><?= htmlspecialchars($step7['amortiguador_posterior_izquierdo'] ?? '0') ?>%</strong></small><br>
                                <small>Post. Der: <strong><?= htmlspecialchars($step7['amortiguador_posterior_derecho'] ?? '0') ?>%</strong></small>
                                <div class="mt-2">
                                    <?php if ($view_mode && isset($expertise_id)): ?>
                                    <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/7" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <?php elseif (!$view_mode): ?>
                                    <a href="<?= APP_URL ?>expertise/step7" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary"><i class="fas fa-battery-full me-2"></i>Batería</h6>
                                <small>Prueba Batería: <strong><?= htmlspecialchars($step8['prueba_bateria'] ?? '0') ?>%</strong></small><br>
                                <small>Prueba Arranque: <strong><?= htmlspecialchars($step8['prueba_arranque'] ?? '0') ?>%</strong></small><br>
                                <small>Carga Batería: <strong><?= htmlspecialchars($step8['carga_bateria'] ?? '0') ?>%</strong></small>
                                <div class="mt-2">
                                    <?php if ($view_mode && isset($expertise_id)): ?>
                                    <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/8" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <?php elseif (!$view_mode): ?>
                                    <a href="<?= APP_URL ?>expertise/step8" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Paso 9: Motor y Sistemas -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-cog me-2"></i>
                            <strong>Paso 9: Motor y Sistemas</strong>
                        </div>
                        <?php if ($view_mode && isset($expertise_id)): ?>
                        <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/9" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php elseif (!$view_mode): ?>
                        <a href="<?= APP_URL ?>expertise/step9" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h2 class="text-success">31</h2>
                            <p class="mb-0">Sistemas evaluados (Motor, Frenos y Suspensión, Interior)</p>
                        </div>
                    </div>
                </div>
                
                <!-- Paso 10: Fugas y Niveles -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-tint me-2"></i>
                            <strong>Paso 10: Fugas y Niveles</strong>
                        </div>
                        <?php if ($view_mode && isset($expertise_id)): ?>
                        <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/10" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php elseif (!$view_mode): ?>
                        <a href="<?= APP_URL ?>expertise/step10" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h2 class="text-success">19</h2>
                            <p class="mb-0">Sistemas de fugas y niveles evaluados</p>
                        </div>
                    </div>
                </div>
                
                <!-- Paso 11: Fijación Fotográfica -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-camera me-2"></i>
                            <strong>Paso 11: Fijación Fotográfica</strong>
                        </div>
                        <?php if ($view_mode && isset($expertise_id)): ?>
                        <a href="<?= APP_URL ?>expertise/edit/<?= $expertise_id ?>/11" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php elseif (!$view_mode): ?>
                        <a href="<?= APP_URL ?>expertise/step11" class="btn btn-sm btn-light">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h2 class="text-success">
                                <?= $step11['total_fotos'] ?? 0 ?>
                            </h2>
                            <p class="mb-0">Fotografías adjuntas</p>
                        </div>
                        
                        <?php if (!empty($step11['fotos'])): ?>
                        <div class="row g-2">
                            <?php foreach (array_slice($step11['fotos'], 0, 8) as $foto): ?>
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="position-relative">
                                    <img src="<?= APP_URL . $foto['ruta'] ?>" 
                                         class="img-thumbnail" 
                                         alt="<?= htmlspecialchars($foto['nombre_original']) ?>"
                                         style="height: 120px; width: 100%; object-fit: cover; cursor: pointer;"
                                         onclick="window.open('<?= APP_URL . $foto['ruta'] ?>', '_blank')">
                                    <small class="d-block text-center text-truncate mt-1" style="font-size: 0.7rem;">
                                        <?= htmlspecialchars($foto['nombre_original']) ?>
                                    </small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($step11['fotos']) > 8): ?>
                        <p class="text-center text-muted mt-2 mb-0">
                            <small>+ <?= count($step11['fotos']) - 8 ?> fotografías más</small>
                        </p>
                        <?php endif; ?>
                        <?php else: ?>
                        <div class="alert alert-warning text-center mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No se encontraron fotografías adjuntas
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!$view_mode): ?>
                <!-- Botones Finales (solo en modo creación) -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step11" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 11
                            </a>
                            
                            <button type="submit" class="btn btn-success btn-lg px-5" id="btnGuardar">
                                <i class="fas fa-save me-2"></i>
                                Guardar Peritaje Completo
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
            </form>
            
        </div>
    </div>
</div>

<?php if (!$view_mode): ?>
<!-- Script para confirmación (solo en modo creación) -->
<script>
document.getElementById('step12Form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (confirm('¿Está seguro de guardar este peritaje? Una vez guardado, los datos se almacenarán en la base de datos.')) {
        const btn = document.getElementById('btnGuardar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        this.submit();
    }
});
</script>
<?php endif; ?>

<?php
// Restaurar sesiones originales si estamos en modo visualización
if ($view_mode && isset($backup_sessions)) {
    // Limpiar sesiones temporales
    unset($_SESSION['expertise_view_mode']);
    unset($_SESSION['expertise_view_id']);
    
    // Restaurar sesiones originales
    foreach ($backup_sessions as $key => $value) {
        $_SESSION[$key] = $value;
    }
}
?>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
