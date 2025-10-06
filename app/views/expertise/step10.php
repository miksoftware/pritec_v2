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

// Configurar el header de contenido
renderContentHeader('Nuevo Peritaje Completo', [
    'subtitle' => 'Paso 10 de 12: Fugas y Niveles',
    'icon' => 'fas fa-tint',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);
?>

<?php renderExpertiseProgressIndicator(10); ?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Formulario del Paso 10 -->
            <form id="step10Form" method="POST" action="<?= APP_URL ?>expertise/save-step10">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-tint"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Fugas y Niveles</h5>
                                <small class="opacity-75">Evaluación de fugas y niveles de fluidos del vehículo</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Tabla de Fugas y Niveles -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50%">Sistema</th>
                                        <th width="50%">Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $fugas = [
                                        ['Fuga aceite motor', 'respuesta_fuga_aceite_motor'],
                                        ['Fuga aceite caja de velocidades', 'respuesta_fuga_aceite_caja_velocidades'],
                                        ['Fuga aceite caja de transmisión', 'respuesta_fuga_aceite_caja_transmision'],
                                        ['Fuga líquido de frenos', 'respuesta_fuga_liquido_frenos'],
                                        ['Fuga aceite dirección hidraulica', 'respuesta_fuga_aceite_direccion_hidraulica'],
                                        ['Fuga liquido bomba embrague', 'respuesta_fuga_liquido_bomba_embrague'],
                                        ['Fuga tanque de combustible', 'respuesta_fuga_tanque_combustible'],
                                        ['Estado tanque silenciador', 'respuesta_estado_tanque_silenciador'],
                                        ['Estado tubo exhosto', 'respuesta_estado_tubo_exhosto'],
                                        ['Estado tanque catalizador de gases', 'respuesta_estado_tanque_catalizador_gases'],
                                        ['Estado guardapolvo caja dirección', 'respuesta_estado_guardapolvo_caja_direccion'],
                                        ['Estado tuberia frenos', 'respuesta_estado_tuberia_frenos'],
                                        ['Viscosidad aceite motor', 'respuesta_viscosidad_aceite_motor'],
                                        ['Nivel refrigerante motor', 'respuesta_nivel_refrigerante_motor'],
                                        ['Nivel liquido de frenos', 'respuesta_nivel_liquido_frenos'],
                                        ['Nivel agua limpiavidrios', 'respuesta_nivel_agua_limpiavidrios'],
                                        ['Nivel aceite dirección hidraulica', 'respuesta_nivel_aceite_direccion_hidraulica'],
                                        ['Nivel liquido embrague', 'respuesta_nivel_liquido_embrague'],
                                        ['Nivel aceite motor', 'respuesta_nivel_aceite_motor'],
                                    ];
                                    
                                    foreach ($fugas as $fuga) {
                                        echo '<tr>';
                                        echo '<td><strong>' . htmlspecialchars($fuga[0]) . '</strong></td>';
                                        echo '<td><input type="text" class="form-control observacion-input" name="' . $fuga[1] . '" placeholder="Ingrese observación"></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Campos Adicionales -->
                        <div class="row mt-4">
                            <div class="col-md-12 mb-3">
                                <label for="prueba_ruta" class="form-label fw-bold">
                                    <i class="fas fa-road me-2"></i>
                                    Prueba de Ruta
                                </label>
                                <textarea 
                                    id="prueba_ruta" 
                                    class="form-control" 
                                    name="prueba_ruta" 
                                    rows="3"
                                    placeholder="Describa el comportamiento del vehículo durante la prueba de ruta (aceleración, frenado, dirección, ruidos, vibraciones, etc.)..."></textarea>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="observaciones_fugas" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-2"></i>
                                    Observaciones Generales
                                </label>
                                <textarea 
                                    id="observaciones_fugas" 
                                    class="form-control" 
                                    name="observaciones_fugas" 
                                    rows="4"
                                    placeholder="Ingrese observaciones generales sobre fugas, niveles y estado de los fluidos..."></textarea>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step9" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 9
                            </a>
                            
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-camera me-2"></i>
                                Continuar a Fijación Fotográfica
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Script para validación de fugas -->
<script src="<?= ASSETS_URL ?>js/expertise-step10.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
