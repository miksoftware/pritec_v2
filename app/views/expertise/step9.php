<?php 
ob_start(); 

// Función para generar select de estado
function generarSelectEstado($name) {
    $estados = ['Bueno', 'Regular', 'Malo', 'N/A'];
    $html = '<select class="form-select" name="' . $name . '" required>';
    $html .= '<option value="">Seleccione...</option>';
    foreach ($estados as $estado) {
        $html .= '<option value="' . htmlspecialchars($estado) . '">' . htmlspecialchars($estado) . '</option>';
    }
    $html .= '</select>';
    return $html;
}
?>

<link href="<?= ASSETS_URL ?>css/expertise.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}
if (!function_exists('renderExpertiseProgressIndicator')) {
    require_once APP_PATH . '/helpers/expertise_components.php';
}

// Configurar el header de contenido
renderContentHeader('Nuevo Peritaje Completo', [
    'subtitle' => 'Paso 9 de 12: Motor y Sistemas',
    'icon' => 'fas fa-engine',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);
?>

<?php renderExpertiseProgressIndicator(9); ?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Formulario del Paso 9 -->
            <form id="step9Form" method="POST" action="<?= APP_URL ?>expertise/save-step9">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-engine"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Motor y Sistemas del Vehículo</h5>
                                <small class="opacity-75">Evaluación completa de todos los sistemas mecánicos y de confort</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Tabla de Inspección -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="40%">Sistema</th>
                                        <th width="25%">Estado</th>
                                        <th width="35%">Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Sección 1: Motor
                                    $sistemas = [
                                        ['Arranque', 'estado_arranque', 'respuesta_arranque'],
                                        ['Radiador', 'estado_radiador', 'respuesta_radiador'],
                                        ['Carter motor', 'estado_carter_motor', 'respuesta_carter_motor'],
                                        ['Carter caja', 'estado_carter_caja', 'respuesta_carter_caja'],
                                        ['Caja de velocidades', 'estado_caja_velocidades', 'respuesta_caja_velocidades'],
                                        ['Soporte caja', 'estado_soporte_caja', 'respuesta_soporte_caja'],
                                        ['Soporte Motor', 'estado_soporte_motor', 'respuesta_soporte_motor'],
                                        ['Estado mangueras radiador', 'estado_mangueras_radiador', 'respuesta_mangueras_radiador'],
                                        ['Estado correas', 'estado_correas', 'respuesta_correas'],
                                        ['Tensión correas', 'tension_correas', 'respuesta_tension_correas'],
                                        ['Estado filtro de aire', 'estado_filtro_aire', 'respuesta_filtro_aire'],
                                        ['Estado externo baterías', 'estado_externo_bateria', 'respuesta_externo_bateria'],
                                    ];

                                    foreach ($sistemas as $sis) {
                                        echo '<tr>';
                                        echo '<td><strong>' . htmlspecialchars($sis[0]) . '</strong></td>';
                                        echo '<td>' . generarSelectEstado($sis[1]) . '</td>';
                                        echo '<td><input type="text" class="form-control" name="' . $sis[2] . '" placeholder="Ingrese observación"></td>';
                                        echo '</tr>';
                                    }

                                    // Sección 2: Sistema de Frenos y Suspensión
                                    echo '<tr><td class="table-secondary fw-bold text-center" colspan="3">';
                                    echo '<i class="fas fa-car-crash me-2"></i>SISTEMA DE FRENOS Y SUSPENSIÓN';
                                    echo '</td></tr>';

                                    $frenos_suspension = [
                                        ['Pastilla freno', 'estado_pastilla_freno', 'respuesta_pastilla_freno'],
                                        ['Discos freno', 'estado_discos_freno', 'respuesta_discos_freno'],
                                        ['Punta eje', 'estado_punta_eje', 'respuesta_punta_eje'],
                                        ['Axiales', 'estado_axiales', 'respuesta_axiales'],
                                        ['Terminales', 'estado_terminales', 'respuesta_terminales'],
                                        ['Rotulas', 'estado_rotulas', 'respuesta_rotulas'],
                                        ['Tijeras', 'estado_tijeras', 'respuesta_tijeras'],
                                        ['Caja dirección', 'estado_caja_direccion', 'respuesta_caja_direccion'],
                                        ['Rodamientos', 'estado_rodamientos', 'respuesta_rodamientos'],
                                        ['Cardan', 'estado_cardan', 'respuesta_cardan'],
                                        ['Crucetas', 'estado_crucetas', 'respuesta_crucetas'],
                                    ];

                                    foreach ($frenos_suspension as $item) {
                                        echo '<tr>';
                                        echo '<td><strong>' . htmlspecialchars($item[0]) . '</strong></td>';
                                        echo '<td>' . generarSelectEstado($item[1]) . '</td>';
                                        echo '<td><input type="text" class="form-control" name="' . $item[2] . '" placeholder="Ingrese observación"></td>';
                                        echo '</tr>';
                                    }

                                    // Sección 3: Interior del Automotor
                                    echo '<tr><td class="table-secondary fw-bold text-center" colspan="3">';
                                    echo '<i class="fas fa-couch me-2"></i>INTERIOR DEL AUTOMOTOR';
                                    echo '</td></tr>';

                                    $interior = [
                                        ['Calefacción', 'estado_calefaccion', 'respuesta_calefaccion'],
                                        ['Aire acondicionado', 'estado_aire_acondicionado', 'respuesta_aire_acondicionado'],
                                        ['Cinturones', 'estado_cinturones', 'respuesta_cinturones'],
                                        ['Tapicería asientos', 'estado_tapiceria_asientos', 'respuesta_tapiceria_asientos'],
                                        ['Tapicería techo', 'estado_tapiceria_techo', 'respuesta_tapiceria_techo'],
                                        ['Millaret', 'estado_millaret', 'respuesta_millaret'],
                                        ['Alfombra', 'estado_alfombra', 'respuesta_alfombra'],
                                        ['Chapas', 'estado_chapas', 'respuesta_chapas'],
                                    ];

                                    foreach ($interior as $item) {
                                        echo '<tr>';
                                        echo '<td><strong>' . htmlspecialchars($item[0]) . '</strong></td>';
                                        echo '<td>' . generarSelectEstado($item[1]) . '</td>';
                                        echo '<td><input type="text" class="form-control" name="' . $item[2] . '" placeholder="Ingrese observación"></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Observaciones Generales -->
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <label for="observaciones_motor" class="form-label fw-bold">
                                    <i class="fas fa-engine me-2"></i>
                                    Observaciones de Motor
                                </label>
                                <textarea 
                                    id="observaciones_motor" 
                                    class="form-control" 
                                    name="observaciones_motor" 
                                    rows="4"
                                    placeholder="Ingrese observaciones generales sobre el motor y sistemas mecánicos..."></textarea>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="observaciones_interior" class="form-label fw-bold">
                                    <i class="fas fa-couch me-2"></i>
                                    Observaciones del Interior del Automotor
                                </label>
                                <textarea 
                                    id="observaciones_interior" 
                                    class="form-control" 
                                    name="observaciones_interior" 
                                    rows="4"
                                    placeholder="Ingrese observaciones generales sobre el interior del vehículo..."></textarea>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step8" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 8
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Continuar al Paso 10
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Script para validación de motor -->
<script src="<?= ASSETS_URL ?>js/expertise-step9.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
