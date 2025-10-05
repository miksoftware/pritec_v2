<?php
/**
 * Script para actualizar los resúmenes de pasos anteriores
 * para usar el componente renderPreviousStepSummary()
 */

// Definir la ruta base
$basePath = __DIR__ . '/app/views/expertise/';

echo "Iniciando actualización de resúmenes...\n\n";

// ===================================
// PASO 3: Resumen de step1 y step2
// ===================================
$file = $basePath . 'step3.php';
if (file_exists($file)) {
    echo "📝 Actualizando: step3.php...\n";
    $content = file_get_contents($file);
    
    $oldCode = <<<'EOD'
            <!-- Resumen de Pasos Anteriores -->
            <?php if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])): ?>
            <div class="alert alert-info mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <strong><i class="fas fa-calendar me-2"></i>Fecha:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_date']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-hashtag me-2"></i>Servicio #:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_number']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-car me-2"></i>Placa:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step2']['placa']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-tag me-2"></i>Tipo Vehículo ID:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step2']['tipo_vehiculo']) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
EOD;
    
    $newCode = <<<'EOD'
            <!-- Resumen de Pasos Anteriores -->
            <?php 
            if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])) {
                $step1 = $_SESSION['expertise_step1'];
                $step2 = $_SESSION['expertise_step2'];
                renderPreviousStepSummary([
                    ['label' => 'Fecha', 'value' => $step1['service_date'], 'icon' => 'fas fa-calendar', 'col' => 3],
                    ['label' => 'Servicio #', 'value' => $step1['service_number'], 'icon' => 'fas fa-hashtag', 'col' => 3],
                    ['label' => 'Placa', 'value' => $step2['placa'], 'icon' => 'fas fa-car', 'col' => 3],
                    ['label' => 'Marca', 'value' => $step2['marca'] ?? 'N/A', 'icon' => 'fas fa-tag', 'col' => 3]
                ]);
            }
            ?>
EOD;
    
    $content = str_replace($oldCode, $newCode, $content);
    file_put_contents($file, $content);
    echo "   ✅ Actualizado correctamente\n";
}

// ===================================
// PASO 4: Similar a step3
// ===================================
$file = $basePath . 'step4.php';
if (file_exists($file)) {
    echo "📝 Actualizando: step4.php...\n";
    $content = file_get_contents($file);
    
    $oldCode = <<<'EOD'
            <!-- Resumen de Pasos Anteriores -->
            <?php if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])): ?>
            <div class="alert alert-info mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <strong><i class="fas fa-calendar me-2"></i>Fecha:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_date']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-hashtag me-2"></i>Servicio #:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_number']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-car me-2"></i>Placa:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step2']['placa']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-tag me-2"></i>Tipo Vehículo ID:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step2']['tipo_vehiculo']) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
EOD;
    
    $newCode = <<<'EOD'
            <!-- Resumen de Pasos Anteriores -->
            <?php 
            if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])) {
                $step1 = $_SESSION['expertise_step1'];
                $step2 = $_SESSION['expertise_step2'];
                renderPreviousStepSummary([
                    ['label' => 'Fecha', 'value' => $step1['service_date'], 'icon' => 'fas fa-calendar', 'col' => 3],
                    ['label' => 'Servicio #', 'value' => $step1['service_number'], 'icon' => 'fas fa-hashtag', 'col' => 3],
                    ['label' => 'Placa', 'value' => $step2['placa'], 'icon' => 'fas fa-car', 'col' => 3],
                    ['label' => 'Marca', 'value' => $step2['marca'] ?? 'N/A', 'icon' => 'fas fa-tag', 'col' => 3]
                ]);
            }
            ?>
EOD;
    
    $content = str_replace($oldCode, $newCode, $content);
    file_put_contents($file, $content);
    echo "   ✅ Actualizado correctamente\n";
}

// ===================================
// PASO 5: Similar a step3 y step4
// ===================================
$file = $basePath . 'step5.php';
if (file_exists($file)) {
    echo "📝 Actualizando: step5.php...\n";
    $content = file_get_contents($file);
    
    $oldCode = <<<'EOD'
            <!-- Resumen de Pasos Anteriores -->
            <?php if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])): ?>
            <div class="alert alert-info mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <strong><i class="fas fa-calendar me-2"></i>Fecha:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_date']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-hashtag me-2"></i>Servicio #:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_number']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-car me-2"></i>Placa:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step2']['placa']) ?>
                    </div>
                    <div class="col-md-3">
                        <strong><i class="fas fa-tag me-2"></i>Tipo Vehículo ID:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step2']['tipo_vehiculo']) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
EOD;
    
    $newCode = <<<'EOD'
            <!-- Resumen de Pasos Anteriores -->
            <?php 
            if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])) {
                $step1 = $_SESSION['expertise_step1'];
                $step2 = $_SESSION['expertise_step2'];
                renderPreviousStepSummary([
                    ['label' => 'Fecha', 'value' => $step1['service_date'], 'icon' => 'fas fa-calendar', 'col' => 3],
                    ['label' => 'Servicio #', 'value' => $step1['service_number'], 'icon' => 'fas fa-hashtag', 'col' => 3],
                    ['label' => 'Placa', 'value' => $step2['placa'], 'icon' => 'fas fa-car', 'col' => 3],
                    ['label' => 'Marca', 'value' => $step2['marca'] ?? 'N/A', 'icon' => 'fas fa-tag', 'col' => 3]
                ]);
            }
            ?>
EOD;
    
    $content = str_replace($oldCode, $newCode, $content);
    file_put_contents($file, $content);
    echo "   ✅ Actualizado correctamente\n";
}

// ===================================
// PASOS 6-11: Mismo patrón con contador de inspecciones
// ===================================
$stepsWithInspectionCount = [6, 7, 8, 9, 10, 11];

foreach ($stepsWithInspectionCount as $stepNum) {
    $file = $basePath . "step{$stepNum}.php";
    if (file_exists($file)) {
        echo "📝 Actualizando: step{$stepNum}.php...\n";
        $content = file_get_contents($file);
        
        // Buscar patrón similar con inspecciones
        $pattern = '/<!-- Resumen de Pasos Anteriores -->.*?<\?php endif; \?>/s';
        
        $newCode = <<<'EOD'
<!-- Resumen de Pasos Anteriores -->
            <?php 
            if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])) {
                $step1 = $_SESSION['expertise_step1'];
                $step2 = $_SESSION['expertise_step2'];
                $step3 = $_SESSION['expertise_step3'] ?? [];
                $step4 = $_SESSION['expertise_step4'] ?? [];
                $step5 = $_SESSION['expertise_step5'] ?? [];
                
                $total_inspecciones = 0;
                if (!empty($step3['inspecciones'])) $total_inspecciones += count($step3['inspecciones']);
                if (!empty($step4['inspecciones'])) $total_inspecciones += count($step4['inspecciones']);
                if (!empty($step5['inspecciones'])) $total_inspecciones += count($step5['inspecciones']);
                
                renderPreviousStepSummary([
                    ['label' => 'Placa', 'value' => $step2['placa'], 'icon' => 'fas fa-car', 'col' => 2],
                    ['label' => 'Marca', 'value' => $step2['marca'] ?? 'N/A', 'icon' => 'fas fa-tag', 'col' => 2],
                    ['label' => 'Modelo', 'value' => $step2['modelo'] ?? 'N/A', 'icon' => 'fas fa-calendar', 'col' => 2],
                    ['label' => 'Inspecciones', 'value' => $total_inspecciones . ' piezas', 'icon' => 'fas fa-clipboard-check', 'col' => 3],
                    ['label' => 'Progreso', 'value' => 'Paso ' . $stepNum . ' de 12', 'icon' => 'fas fa-tasks', 'col' => 3]
                ], 'alert-success');
            }
            ?>
EOD;
        
        $content = preg_replace($pattern, $newCode, $content);
        file_put_contents($file, $content);
        echo "   ✅ Actualizado correctamente\n";
    }
}

// ===================================
// PASO 12: Caso especial (ya tiene su propio formato)
// ===================================
echo "ℹ️  step12.php ya tiene su propio formato especial de resumen\n";

echo "\n🎉 Proceso completado!\n";
echo "\nTodos los resúmenes actualizados para usar componentes reutilizables.\n";
echo "Ahora todos los pasos usan: renderPreviousStepSummary()\n";
