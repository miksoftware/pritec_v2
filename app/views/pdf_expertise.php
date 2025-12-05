<?php
/**
 * Vista PDF de Peritaje Completo - Pritec v2.0
 * 
 * Esta vista genera el documento PDF/imprimible del peritaje vehicular.
 * Incluye todas las secciones de inspección, fotografías y firmas.
 */

// Funciones helper para determinar estados según porcentaje
if (!function_exists('obtenerEstadoPorPorcentaje')) {
    function obtenerEstadoPorPorcentaje($porcentaje) {
        $porcentaje = intval($porcentaje);
        if ($porcentaje <= 24) return 'Peligroso';
        if ($porcentaje <= 49) return 'Precaución';
        if ($porcentaje <= 74) return 'Seguro';
        if ($porcentaje <= 100) return 'Nuevas';
        return 'N/A';
    }
}

if (!function_exists('obtenerClasePorPorcentaje')) {
    function obtenerClasePorPorcentaje($porcentaje) {
        $porcentaje = intval($porcentaje);
        if ($porcentaje <= 24) return 'estado-peligroso';
        if ($porcentaje <= 49) return 'estado-precaucion';
        if ($porcentaje <= 74) return 'estado-seguro';
        if ($porcentaje <= 100) return 'estado-nuevas';
        return '';
    }
}

if (!function_exists('obtenerEstadoBateriaPorPorcentaje')) {
    function obtenerEstadoBateriaPorPorcentaje($porcentaje) {
        $porcentaje = intval($porcentaje);
        if ($porcentaje <= 24) return 'Crítico';
        if ($porcentaje <= 49) return 'Bajo';
        if ($porcentaje <= 74) return 'Bueno';
        if ($porcentaje <= 100) return 'Excelente';
        return 'N/A';
    }
}

// Función para renderizar el footer con número de página
if (!function_exists('renderFooter')) {
    function renderFooter($pageNumber) {
        return '<div class="footer">
            <span class="footer-text">LA MEJOR FORMA DE COMPRAR UN CARRO USADO</span>
            <span class="footer-page">Página ' . $pageNumber . '</span>
        </div>';
    }
}

// Verificar que los datos necesarios existan
if (!isset($peritaje) || !is_array($peritaje)) {
    die('Error: Datos del peritaje no disponibles');
}

// Determinar si es carro o moto
$esCarro = (isset($peritaje['type']) && $peritaje['type'] === 'carro');

// Asegurar que BASE_URL esté definida
if (!isset($BASE_URL)) {
    $BASE_URL = defined('APP_URL') ? APP_URL : '/';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peritaje - <?= htmlspecialchars($peritaje['placa']) ?></title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>/public/assets/css/pdf-expertise.css">
</head>
<body>

<!-- Botones de acción (se ocultan al imprimir) -->
<div class="action-buttons no-print">
    <button onclick="window.print()" class="btn-print">🖨️ Imprimir / Guardar PDF</button>
    <button onclick="window.close()" class="btn-close-pdf">✖ Cerrar</button>
</div>

<main class="w-100">

    <!-- ================== PÁGINA 1 ================== -->
    <div class="page">
        <div class="page-content">
            <div class="d-flex flex-column gap-3">
                
                <!-- Encabezado -->
                <section class="d-flex flex-column">
                    <h1 class="text-center">SALA TÉCNICA EN AUTOMOTORES</h1>
                    <h3 class="text-center mb-3">CERTIFICACIÓN TÉCNICA EN IDENTIFICACIÓN DE AUTOMOTORES</h3>
                    <header class="d-flex gap-4 align-items-center mx-auto">
                        <img src="/pritec_v2/public/assets/img/pritec.png" style="width: 120px; object-fit: contain;" alt="Pritec Logo">
                        <div class="me-3">
                            <p>Dirección: Carrera 16 No. 18-197 Barrio Tenerife</p>
                            <p>Teléfono: 3132049245-3158928492</p>
                            <p>Web: peritos.pritec.co</p>
                            <p>Peritos e inspecciones técnicas vehiculares Neiva-Huila</p>
                        </div>
                        <div>
                            <p>Fecha: <?= $peritaje["fecha"] ?></p>
                            <p>No. Servicio: <?= $peritaje["no_servicio"] ?></p>
                            <p>Servicio para: <?= $peritaje["servicio_para"] ?></p>
                            <p>Convenio: <?= $peritaje["convenio"] ?></p>
                        </div>
                    </header>
                </section>

                <!-- Datos del Vehículo y Solicitante -->
                <section class="d-flex gap-2 rounded p-2 simple-border">
                    <div class="d-flex" style="width: 33%;">
                        <div class="yellow-background sub-title-vertical">DATOS DEL VEHÍCULO</div>
                        <div class="d-flex flex-column gap-2 w-100">
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Clase</div>
                                <div class="input"><?= $peritaje["clase"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Marca</div>
                                <div class="input"><?= $peritaje["marca"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Línea</div>
                                <div class="input"><?= $peritaje["linea"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Cilindraje</div>
                                <div class="input"><?= $peritaje["cilindraje"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Kilometraje</div>
                                <div class="input"><?= $peritaje["kilometraje"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Servicio</div>
                                <div class="input"><?= $peritaje["servicio"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Modelo</div>
                                <div class="input"><?= $peritaje["modelo"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Color</div>
                                <div class="input"><?= $peritaje["color"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">No. de chasis</div>
                                <div class="input"><?= $peritaje["no_chasis"] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex" style="width: 33%;">
                        <div class="d-flex flex-column gap-2 w-100">
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">No. de motor</div>
                                <div class="input"><?= $peritaje["no_motor"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">No. de serie</div>
                                <div class="input"><?= $peritaje["no_serie"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Tipo de carrocería</div>
                                <div class="input"><?= $peritaje["tipo_carroceria"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Organismo de<br>tránsito</div>
                                <div class="input"><?= $peritaje["organismo_transito"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Código fasecolda</div>
                                <div class="input"><?= $peritaje["codigo_fasecolda"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Valor fasecolda</div>
                                <div class="input"><?= $peritaje["valor_fasecolda"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Valor sugerido</div>
                                <div class="input"><?= $peritaje["valor_sugerido"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Valor accesorios</div>
                                <div class="input"><?= $peritaje["valor_accesorios"] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="me-2" style="width: 33%;">
                        <div class="plate"><?= $peritaje["placa"] ?></div>
                        <div class="yellow-background sub-title">DATOS DEL SOLICITANTE</div>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Nombres y<br>apellidos</div>
                                <div class="input"><?= $peritaje["nombre_apellidos"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Identificación</div>
                                <div class="input"><?= $peritaje["identificacion"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Teléfono</div>
                                <div class="input"><?= $peritaje["telefono"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Dirección</div>
                                <div class="input"><?= $peritaje["direccion"] ?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Correo</div>
                                <div class="input" style="font-size: 10px;"><?= $peritaje["email"] ?></div>
                            </div>
                        </div>
                    </div>
                </section>

                <?php if ($esCarro): ?>
                    <!-- CARROCERÍA - Solo para carros -->
                    <section class="p-2 rounded simple-border">
                        <div class="d-flex gap-2">
                            <div class="yellow-background sub-title-vertical">INSPECCIÓN VISUAL EXTERNA</div>
                            <div class="d-flex flex-column w-100">
                                <div class="yellow-background sub-title w-100">
                                    VEHÍCULO: <?php echo $peritaje["tipo_vehiculo"]; ?>
                                </div>
                                <p style="font-size: 11px; margin-bottom: 0.3rem;">Indique con un círculo en que parte del vehículo tiene alguna condición.</p>
                                <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">CARROCERÍA</div>
                                <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                    <?php if (!empty($carroceria['image'])): ?>
                                        <div style="position: relative; width: 300px; height: 300px; flex-shrink: 0;">
                                            <img src="<?php echo $BASE_URL . $carroceria['image']; ?>" style="width: 300px; height: 300px; object-fit: contain;" onerror="this.style.display='none'">
                                            <?php if (!empty($carroceria['pieces'])): ?>
                                                <?php foreach ($carroceria['pieces'] as $pieza): ?>
                                                    <?php if (!empty($pieza['position_x']) && !empty($pieza['position_y'])): ?>
                                                        <?php
                                                        // Escalar coordenadas de 400x400 a 300x300 (factor 0.75)
                                                        $scaledX = $pieza['position_x'] * 0.75;
                                                        $scaledY = $pieza['position_y'] * 0.75;
                                                        ?>
                                                        <div style="position: absolute; left: <?php echo $scaledX; ?>px; top: <?php echo $scaledY; ?>px; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #ff0000; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                            <?php echo htmlspecialchars($pieza['piece_number']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-50" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 180px; border: 1px dashed #ccc;">
                                            <p style="color: #999;">Sin imagen</p>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex flex-column w-50" style="gap: 0.2rem; overflow: hidden;">
                                        <div class="d-flex" style="gap: 0.2rem;">
                                            <div class="yellow-background text-center" style="width: 10%; padding: 0.2rem; font-size: 11px; font-weight: bold;">No.</div>
                                            <div class="yellow-background text-center" style="width: 60%; padding: 0.2rem; font-size: 11px; font-weight: bold;">Descripción pieza</div>
                                            <div class="yellow-background text-center" style="width: 30%; padding: 0.2rem; font-size: 11px; font-weight: bold;">Concepto</div>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.2rem; max-height: 160px; overflow-y: auto;">
                                            <?php if (!empty($carroceria['pieces'])): ?>
                                                <?php foreach ($carroceria['pieces'] as $pieza): ?>
                                                    <div class="d-flex" style="gap: 0.2rem;">
                                                        <div class="input text-center" style="width: 10%; padding: 0.15rem 0.2rem; font-size: 10px; border: 1px solid var(--main-color); border-radius: 4px;">
                                                            <?php echo htmlspecialchars($pieza["piece_number"]); ?>
                                                        </div>
                                                        <div class="input" style="width: 60%; padding: 0.15rem 0.3rem; font-size: 10px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            <?php echo htmlspecialchars($pieza["piece_name"]); ?>
                                                        </div>
                                                        <div class="input text-center" style="width: 30%; padding: 0.15rem 0.2rem; font-size: 10px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            <?php echo htmlspecialchars($pieza["concept_name"]); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div style="width: 100%; padding: 1rem; text-align: center; color: #999; font-size: 10px; border: 1px dashed #ccc; border-radius: 4px;">
                                                    No se registraron inspecciones
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="remarks" style="margin-top: 0.5rem;">
                                    OBSERVACIONES: <br /> <?php echo htmlspecialchars($peritaje["observaciones_inspeccion"]); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- ESTRUCTURA - Para carros y motos -->
                <section class="p-2 rounded simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">INSPECCIÓN VISUAL EXTERNA</div>
                        <div class="d-flex flex-column w-100">
                            <div class="yellow-background sub-title w-100">
                                VEHÍCULO: <?php echo $peritaje["tipo_vehiculo"]; ?>
                            </div>
                            <p style="font-size: 11px; margin-bottom: 0.3rem;">Indique con un círculo en que parte del vehículo tiene alguna condición.</p>
                            <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">ESTRUCTURA</div>
                            <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                <?php if (!empty($estructura['image'])): ?>
                                    <div style="position: relative; width: 300px; height: 300px; flex-shrink: 0;">
                                        <img src="<?php echo $BASE_URL . $estructura['image']; ?>" style="width: 300px; height: 300px; object-fit: contain;" onerror="this.style.display='none'">
                                        <?php if (!empty($estructura['pieces'])): ?>
                                            <?php foreach ($estructura['pieces'] as $pieza): ?>
                                                <?php if (!empty($pieza['position_x']) && !empty($pieza['position_y'])): ?>
                                                    <?php
                                                    // Escalar coordenadas de 400x400 a 300x300 (factor 0.75)
                                                    $scaledX = $pieza['position_x'] * 0.75;
                                                    $scaledY = $pieza['position_y'] * 0.75;
                                                    ?>
                                                    <div style="position: absolute; left: <?php echo $scaledX; ?>px; top: <?php echo $scaledY; ?>px; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #ff0000; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                        <?php echo htmlspecialchars($pieza['piece_number']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="w-50" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 180px; border: 1px dashed #ccc;">
                                        <p style="color: #999;">Sin imagen</p>
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex flex-column w-50" style="gap: 0.2rem; overflow: hidden;">
                                    <div class="d-flex" style="gap: 0.2rem;">
                                        <div class="yellow-background text-center" style="width: 10%; padding: 0.2rem; font-size: 9px; font-weight: bold;">No.</div>
                                        <div class="yellow-background text-center" style="width: 60%; padding: 0.2rem; font-size: 9px; font-weight: bold;">Descripción pieza</div>
                                        <div class="yellow-background text-center" style="width: 30%; padding: 0.2rem; font-size: 9px; font-weight: bold;">Concepto</div>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.2rem; max-height: 160px; overflow-y: auto;">
                                        <?php if (!empty($estructura['pieces'])): ?>
                                            <?php foreach ($estructura['pieces'] as $pieza): ?>
                                                <div class="d-flex" style="gap: 0.2rem;">
                                                    <div class="input text-center" style="width: 10%; padding: 0.15rem 0.2rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px;">
                                                        <?php echo htmlspecialchars($pieza["piece_number"]); ?>
                                                    </div>
                                                    <div class="input" style="width: 60%; padding: 0.15rem 0.3rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?php echo htmlspecialchars($pieza["piece_name"]); ?>
                                                    </div>
                                                    <div class="input text-center" style="width: 30%; padding: 0.15rem 0.2rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?php echo htmlspecialchars($pieza["concept_name"]); ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div style="width: 100%; padding: 1rem; text-align: center; color: #999; font-size: 10px; border: 1px dashed #ccc; border-radius: 4px;">
                                                No se registraron inspecciones
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="remarks" style="margin-top: 0.5rem;">
                                OBSERVACIONES: <br /> <?php echo htmlspecialchars($peritaje["observaciones_inspeccion"]); ?>
                            </div>
                        </div>
                    </div>
                </section>

                <?php if (!$esCarro): ?>
                    <!-- CHASIS - En página 1 para motos (movido aquí desde página 2) -->
                    <section class="p-2 rounded my-2 simple-border">
                        <div class="d-flex gap-2">
                            <div class="yellow-background sub-title-vertical">INSPECCIÓN VISUAL INTERNA</div>
                            <div class="d-flex flex-column w-100">
                                <div class="yellow-background sub-title w-100">
                                    VEHÍCULO: <?php echo $peritaje["tipo_vehiculo"]; ?>
                                </div>
                                <p style="font-size: 11px; margin-bottom: 0.3rem;">Indique con un círculo en que parte del vehículo tiene alguna condición.</p>
                                <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">CHASIS</div>
                                <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                    <?php if (!empty($chasis['image'])): ?>
                                        <div style="position: relative; width: 300px; height: 300px; flex-shrink: 0;">
                                            <img src="<?php echo $BASE_URL . $chasis['image']; ?>" style="width: 300px; height: 300px; object-fit: contain;" onerror="this.style.display='none'">
                                            <?php if (!empty($chasis['pieces'])): ?>
                                                <?php foreach ($chasis['pieces'] as $pieza): ?>
                                                    <?php if (!empty($pieza['position_x']) && !empty($pieza['position_y'])): ?>
                                                        <?php
                                                        // Escalar coordenadas de 400x400 a 300x300 (factor 0.75)
                                                        $scaledX = $pieza['position_x'] * 0.75;
                                                        $scaledY = $pieza['position_y'] * 0.75;
                                                        ?>
                                                        <div style="position: absolute; left: <?php echo $scaledX; ?>px; top: <?php echo $scaledY; ?>px; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #ff0000; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                            <?php echo htmlspecialchars($pieza['piece_number']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-50" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 180px; border: 1px dashed #ccc;">
                                            <p style="color: #999;">Sin imagen</p>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex flex-column w-50" style="gap: 0.2rem; overflow: hidden;">
                                        <div class="d-flex" style="gap: 0.2rem;">
                                            <div class="yellow-background text-center" style="width: 10%; padding: 0.2rem; font-size: 9px; font-weight: bold;">No.</div>
                                            <div class="yellow-background text-center" style="width: 60%; padding: 0.2rem; font-size: 9px; font-weight: bold;">Descripción pieza</div>
                                            <div class="yellow-background text-center" style="width: 30%; padding: 0.2rem; font-size: 9px; font-weight: bold;">Concepto</div>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.2rem; max-height: 160px; overflow-y: auto;">
                                            <?php if (!empty($chasis['pieces'])): ?>
                                                <?php foreach ($chasis['pieces'] as $pieza): ?>
                                                    <div class="d-flex" style="gap: 0.2rem;">
                                                        <div class="input text-center" style="width: 10%; padding: 0.15rem 0.2rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px;">
                                                            <?php echo htmlspecialchars($pieza["piece_number"]); ?>
                                                        </div>
                                                        <div class="input" style="width: 60%; padding: 0.15rem 0.3rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            <?php echo htmlspecialchars($pieza["piece_name"]); ?>
                                                        </div>
                                                        <div class="input text-center" style="width: 30%; padding: 0.15rem 0.2rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            <?php echo htmlspecialchars($pieza["concept_name"]); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div style="width: 100%; padding: 1rem; text-align: center; color: #999; font-size: 10px; border: 1px dashed #ccc; border-radius: 4px;">
                                                    No se registraron inspecciones
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                    OBSERVACIONES: <br /> <?php echo $peritaje["observaciones_estructura"]; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                </div>
            </div>
            <?php echo renderFooter(1); ?>
        </div>

        <div class="page">
            <div class="page-content">
                <div class="d-flex flex-column gap-3 w-100">
                <?php if ($esCarro): ?>
                    <!-- CHASIS - En página 2 para carros (posición original) -->
                    <section class="p-2 rounded my-2 simple-border">
                        <div class="d-flex gap-2">
                            <div class="yellow-background sub-title-vertical">INSPECCIÓN VISUAL INTERNA</div>
                            <div class="d-flex flex-column w-100">
                                <div class="yellow-background sub-title w-100">
                                    VEHÍCULO: <?php echo $peritaje["tipo_vehiculo"]; ?>
                                </div>
                                <p style="font-size: 11px; margin-bottom: 0.3rem;">Indique con un círculo en que parte del vehículo tiene alguna condición.</p>
                                <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">CHASIS</div>
                                <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                    <?php if (!empty($chasis['image'])): ?>
                                        <div style="position: relative; width: 300px; height: 300px; flex-shrink: 0;">
                                            <img src="<?php echo $BASE_URL . $chasis['image']; ?>" style="width: 300px; height: 300px; object-fit: contain;" onerror="this.style.display='none'">
                                            <?php if (!empty($chasis['pieces'])): ?>
                                                <?php foreach ($chasis['pieces'] as $pieza): ?>
                                                    <?php if (!empty($pieza['position_x']) && !empty($pieza['position_y'])): ?>
                                                        <?php
                                                        // Escalar coordenadas de 400x400 a 300x300 (factor 0.75)
                                                        $scaledX = $pieza['position_x'] * 0.75;
                                                        $scaledY = $pieza['position_y'] * 0.75;
                                                        ?>
                                                        <div style="position: absolute; left: <?php echo $scaledX; ?>px; top: <?php echo $scaledY; ?>px; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #ff0000; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                            <?php echo htmlspecialchars($pieza['piece_number']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-50" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 180px; border: 1px dashed #ccc;">
                                            <p style="color: #999;">Sin imagen</p>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex flex-column w-50" style="gap: 0.2rem; overflow: hidden;">
                                        <div class="d-flex" style="gap: 0.2rem;">
                                            <div class="yellow-background text-center" style="width: 10%; padding: 0.2rem; font-size: 9px; font-weight: bold;">No.</div>
                                            <div class="yellow-background text-center" style="width: 60%; padding: 0.2rem; font-size: 9px; font-weight: bold;">Descripción pieza</div>
                                            <div class="yellow-background text-center" style="width: 30%; padding: 0.2rem; font-size: 9px; font-weight: bold;">Concepto</div>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.2rem; max-height: 160px; overflow-y: auto;">
                                            <?php if (!empty($chasis['pieces'])): ?>
                                                <?php foreach ($chasis['pieces'] as $pieza): ?>
                                                    <div class="d-flex" style="gap: 0.2rem;">
                                                        <div class="input text-center" style="width: 10%; padding: 0.15rem 0.2rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px;">
                                                            <?php echo htmlspecialchars($pieza["piece_number"]); ?>
                                                        </div>
                                                        <div class="input" style="width: 60%; padding: 0.15rem 0.3rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            <?php echo htmlspecialchars($pieza["piece_name"]); ?>
                                                        </div>
                                                        <div class="input text-center" style="width: 30%; padding: 0.15rem 0.2rem; font-size: 8px; border: 1px solid var(--main-color); border-radius: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            <?php echo htmlspecialchars($pieza["concept_name"]); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div style="width: 100%; padding: 1rem; text-align: center; color: #999; font-size: 10px; border: 1px dashed #ccc; border-radius: 4px;">
                                                    No se registraron inspecciones
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                    OBSERVACIONES: <br /> <?php echo $peritaje["observaciones_estructura"]; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- LLANTAS - Para carros y motos -->
                <section class="p-2 rounded my-2 simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">LLANTAS Y AMORTIGUADORES</div>
                        <div class="d-flex flex-column w-100">
                            <!-- Convenciones de Llantas -->
                            <div class="yellow-background sub-title w-100">CONVENCIONES LLANTA</div>

                            <!-- Barra de colores -->
                            <div class="mx-auto" style="width: 95%; margin-bottom: 0.5rem;">
                                <div class="d-flex" style="margin-bottom: 3px;">
                                    <div style="height: 15px; width: 25%; background-color:#ff3933;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#ff8a33;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#ffff33;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#36d048;"></div>
                                </div>

                                <!-- Porcentajes -->
                                <div class="d-flex justify-content-between" style="font-size: 0.7rem; margin-bottom: 5px;">
                                    <p style="margin: 0;">0%</p>
                                    <p style="margin: 0;">100%</p>
                                </div>

                                <!-- Leyendas -->
                                <div class="d-flex justify-content-center gap-2" style="font-size: 0.65rem; margin-bottom: 5px;">
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ff3933;"></div>
                                        <span>0-24% Peligroso</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ff8a33;"></div>
                                        <span>25-49% Precaución</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ffff33;"></div>
                                        <span>50-74% Seguro</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#36d048;"></div>
                                        <span>75-100% Nuevas</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Llantas con imagen -->
                            <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">LLANTAS</div>
                            <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                <div style="width: 35%; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo $BASE_URL; ?>/public/assets/img/<?php echo ($peritaje['tipo_vehiculo_type'] === 'moto') ? 'LLANTAS MOTO.png' : 'llantas.png'; ?>"
                                        class="imagen-llantas"
                                        alt="Llantas del vehículo"
                                        onerror="this.style.display='none'">
                                </div>

                                <div style="width: 65%;">
                                    <table class="tabla-llantas">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">ITEM</th>
                                                <th style="width: 30%;">CONCEPTO</th>
                                                <th style="width: 20%;">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0.5rem;">Llanta anterior izquierda</td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_anterior_izquierda'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['llanta_anterior_izquierda'] ?? 0)); ?>
                                                </td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_anterior_izquierda'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars($peritaje['llanta_anterior_izquierda'] ?? '0'); ?>%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0.5rem;">Llanta anterior derecha</td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_anterior_derecha'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['llanta_anterior_derecha'] ?? 0)); ?>
                                                </td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_anterior_derecha'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars($peritaje['llanta_anterior_derecha'] ?? '0'); ?>%
                                                </td>
                                            </tr>
                                            <?php if ($peritaje['tipo_vehiculo_type'] !== 'moto'): ?>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 0.5rem;">Llanta posterior izquierda</td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_posterior_izquierda'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['llanta_posterior_izquierda'] ?? 0)); ?>
                                                    </td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_posterior_izquierda'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars($peritaje['llanta_posterior_izquierda'] ?? '0'); ?>%
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 0.5rem;">Llanta posterior derecha</td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_posterior_derecha'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['llanta_posterior_derecha'] ?? 0)); ?>
                                                    </td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['llanta_posterior_derecha'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars($peritaje['llanta_posterior_derecha'] ?? '0'); ?>%
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                OBSERVACIONES: <br /> <?php echo htmlspecialchars($peritaje['observaciones_llantas'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- AMORTIGUADORES - Para carros y motos -->
                <section class="p-2 rounded my-2 simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">AMORTIGUADORES</div>
                        <div class="d-flex flex-column w-100">
                            <!-- Convenciones de Amortiguadores -->
                            <div class="yellow-background sub-title w-100">CONVENCIONES AMORTIGUADORES</div>

                            <!-- Barra de colores -->
                            <div class="mx-auto" style="width: 95%; margin-bottom: 0.5rem;">
                                <div class="d-flex" style="margin-bottom: 3px;">
                                    <div style="height: 15px; width: 25%; background-color:#ff3933;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#ff8a33;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#ffff33;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#36d048;"></div>
                                </div>

                                <div class="d-flex justify-content-between" style="font-size: 0.7rem; margin-bottom: 5px;">
                                    <p style="margin: 0;">0%</p>
                                    <p style="margin: 0;">100%</p>
                                </div>

                                <div class="d-flex justify-content-center gap-2" style="font-size: 0.65rem; margin-bottom: 5px;">
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ff3933;"></div>
                                        <span>0-24% Pésimo</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ff8a33;"></div>
                                        <span>25-49% Supervisión</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ffff33;"></div>
                                        <span>50-74% Bueno</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#36d048;"></div>
                                        <span>75-100% Nuevos</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Amortiguadores con imagen -->
                            <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">AMORTIGUADORES</div>
                            <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                <div style="width: 35%; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo $BASE_URL; ?>/public/assets/img/<?php echo ($peritaje['tipo_vehiculo_type'] === 'moto') ? 'AMORTIGUADORES MOTO.png' : 'amortiguadores.png'; ?>"
                                        class="imagen-llantas"
                                        alt="Amortiguadores del vehículo"
                                        onerror="this.style.display='none'">
                                </div>

                                <div style="width: 65%;">
                                    <table class="tabla-llantas">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">ITEM</th>
                                                <th style="width: 30%;">CONCEPTO</th>
                                                <th style="width: 20%;">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0.5rem;">Amortiguador anterior izquierdo</td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_anterior_izquierdo'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['amortiguador_anterior_izquierdo'] ?? 0)); ?>
                                                </td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_anterior_izquierdo'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars($peritaje['amortiguador_anterior_izquierdo'] ?? '0'); ?>%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0.5rem;">Amortiguador anterior derecho</td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_anterior_derecho'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['amortiguador_anterior_derecho'] ?? 0)); ?>
                                                </td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_anterior_derecho'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars($peritaje['amortiguador_anterior_derecho'] ?? '0'); ?>%
                                                </td>
                                            </tr>
                                            <?php if ($peritaje['tipo_vehiculo_type'] !== 'moto'): ?>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 0.5rem;">Amortiguador posterior izquierdo</td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_posterior_izquierdo'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['amortiguador_posterior_izquierdo'] ?? 0)); ?>
                                                    </td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_posterior_izquierdo'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars($peritaje['amortiguador_posterior_izquierdo'] ?? '0'); ?>%
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 0.5rem;">Amortiguador posterior derecho</td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_posterior_derecho'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars(obtenerEstadoPorPorcentaje($peritaje['amortiguador_posterior_derecho'] ?? 0)); ?>
                                                    </td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['amortiguador_posterior_derecho'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars($peritaje['amortiguador_posterior_derecho'] ?? '0'); ?>%
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                OBSERVACIONES: <br /> <?php echo htmlspecialchars($peritaje['observaciones_amortiguadores'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php if (!$esCarro): ?>
                    <section class="p-2 rounded my-2 simple-border">
                        <div class="d-flex gap-2">
                            <div class="yellow-background sub-title-vertical">ACTUACIÓN DE LA BATERÍA</div>
                            <div class="d-flex flex-column w-100">
                                <!-- Convenciones de Batería -->
                                <div class="yellow-background sub-title w-100">CONVENCIONES BATERÍA</div>

                                <!-- Barra de colores -->
                                <div class="mx-auto" style="width: 95%; margin-bottom: 0.5rem;">
                                    <div class="d-flex" style="margin-bottom: 3px;">
                                        <div style="height: 15px; width: 25%; background-color:#ff3933;"></div>
                                        <div style="height: 15px; width: 25%; background-color:#ff8a33;"></div>
                                        <div style="height: 15px; width: 25%; background-color:#ffff33;"></div>
                                        <div style="height: 15px; width: 25%; background-color:#36d048;"></div>
                                    </div>

                                    <!-- Porcentajes -->
                                    <div class="d-flex justify-content-between" style="font-size: 0.7rem; margin-bottom: 5px;">
                                        <p style="margin: 0;">0%</p>
                                        <p style="margin: 0;">100%</p>
                                    </div>

                                    <!-- Leyendas -->
                                    <div class="d-flex justify-content-center gap-2" style="font-size: 0.65rem; margin-bottom: 5px;">
                                        <div class="d-flex align-items-center" style="gap: 3px;">
                                            <div style="width: 10px; height: 10px; background-color:#ff3933;"></div>
                                            <span>0-24% Crítico</span>
                                        </div>
                                        <div class="d-flex align-items-center" style="gap: 3px;">
                                            <div style="width: 10px; height: 10px; background-color:#ff8a33;"></div>
                                            <span>25-49% Bajo</span>
                                        </div>
                                        <div class="d-flex align-items-center" style="gap: 3px;">
                                            <div style="width: 10px; height: 10px; background-color:#ffff33;"></div>
                                            <span>50-74% Bueno</span>
                                        </div>
                                        <div class="d-flex align-items-center" style="gap: 3px;">
                                            <div style="width: 10px; height: 10px; background-color:#36d048;"></div>
                                            <span>75-100% Excelente</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección de Batería con imagen -->
                                <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">BATERÍA</div>
                                <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                    <div style="width: 35%; display: flex; align-items: center; justify-content: center;">
                                        <img src="<?php echo $BASE_URL; ?>/public/assets/img/BATERIA.png"
                                            class="imagen-llantas"
                                            alt="Batería del vehículo"
                                            onerror="this.style.display='none'">
                                    </div>

                                    <div style="width: 65%;">
                                        <table class="tabla-llantas">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%;">ITEM</th>
                                                    <th style="width: 30%;">CONCEPTO</th>
                                                    <th style="width: 20%;">%</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 0.5rem;">Prueba de batería</td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_bateria'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars(obtenerEstadoBateriaPorPorcentaje($peritaje['prueba_bateria'] ?? 0)); ?>
                                                    </td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_bateria'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars($peritaje['prueba_bateria'] ?? '0'); ?>%
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 0.5rem;">Prueba de arranque</td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_arranque'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars(obtenerEstadoBateriaPorPorcentaje($peritaje['prueba_arranque'] ?? 0)); ?>
                                                    </td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_arranque'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars($peritaje['prueba_arranque'] ?? '0'); ?>%
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; padding-left: 0.5rem;">Carga de batería</td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['carga_bateria'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars(obtenerEstadoBateriaPorPorcentaje($peritaje['carga_bateria'] ?? 0)); ?>
                                                    </td>
                                                    <td class="<?php echo obtenerClasePorPorcentaje($peritaje['carga_bateria'] ?? 0); ?>">
                                                        <?php echo htmlspecialchars($peritaje['carga_bateria'] ?? '0'); ?>%
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                    OBSERVACIONES: <br /> <?php echo htmlspecialchars($peritaje['observaciones_bateria'] ?? ''); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                </div>
            </div>
            <?php echo renderFooter(2); ?>
        </div>

        <div class="page">
            <div class="page-content">
                <?php if ($esCarro): ?>
                <section class="p-2 rounded my-2 simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">ACTUACIÓN DE LA BATERÍA</div>
                        <div class="d-flex flex-column w-100">
                            <!-- Convenciones de Batería -->
                            <div class="yellow-background sub-title w-100">CONVENCIONES BATERÍA</div>

                            <!-- Barra de colores -->
                            <div class="mx-auto" style="width: 95%; margin-bottom: 0.5rem;">
                                <div class="d-flex" style="margin-bottom: 3px;">
                                    <div style="height: 15px; width: 25%; background-color:#ff3933;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#ff8a33;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#ffff33;"></div>
                                    <div style="height: 15px; width: 25%; background-color:#36d048;"></div>
                                </div>

                                <!-- Porcentajes -->
                                <div class="d-flex justify-content-between" style="font-size: 0.7rem; margin-bottom: 5px;">
                                    <p style="margin: 0;">0%</p>
                                    <p style="margin: 0;">100%</p>
                                </div>

                                <!-- Leyendas -->
                                <div class="d-flex justify-content-center gap-2" style="font-size: 0.65rem; margin-bottom: 5px;">
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ff3933;"></div>
                                        <span>0-24% Crítico</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ff8a33;"></div>
                                        <span>25-49% Bajo</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#ffff33;"></div>
                                        <span>50-74% Bueno</span>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 3px;">
                                        <div style="width: 10px; height: 10px; background-color:#36d048;"></div>
                                        <span>75-100% Excelente</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Batería con imagen -->
                            <div class="yellow-background sub-title ms-4 mb-0" style="margin: 0.3rem 0 0.3rem 1rem;">BATERÍA</div>
                            <div class="d-flex gap-2 w-100" style="min-height: 180px;">
                                <div style="width: 35%; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo $BASE_URL; ?>/public/assets/img/BATERIA.png"
                                        class="imagen-llantas"
                                        alt="Batería del vehículo"
                                        onerror="this.style.display='none'">
                                </div>

                                <div style="width: 65%;">
                                    <table class="tabla-llantas">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">ITEM</th>
                                                <th style="width: 30%;">CONCEPTO</th>
                                                <th style="width: 20%;">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0.5rem;">Prueba de batería</td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_bateria'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars(obtenerEstadoBateriaPorPorcentaje($peritaje['prueba_bateria'] ?? 0)); ?>
                                                </td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_bateria'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars($peritaje['prueba_bateria'] ?? '0'); ?>%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0.5rem;">Prueba de arranque</td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_arranque'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars(obtenerEstadoBateriaPorPorcentaje($peritaje['prueba_arranque'] ?? 0)); ?>
                                                </td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['prueba_arranque'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars($peritaje['prueba_arranque'] ?? '0'); ?>%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0.5rem;">Carga de batería</td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['carga_bateria'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars(obtenerEstadoBateriaPorPorcentaje($peritaje['carga_bateria'] ?? 0)); ?>
                                                </td>
                                                <td class="<?php echo obtenerClasePorPorcentaje($peritaje['carga_bateria'] ?? 0); ?>">
                                                    <?php echo htmlspecialchars($peritaje['carga_bateria'] ?? '0'); ?>%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                OBSERVACIONES: <br /> <?php echo htmlspecialchars($peritaje['observaciones_bateria'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <section class="p-2 rounded my-2 simple-border">
                <div class="yellow-background sub-title w-100 text-center seccion-titulo-con-icono" style="margin-bottom: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                        <path d="M7 8h2m2 0h2m2 0h2"></path>
                        <path d="M7 11h10"></path>
                    </svg>
                    PRUEBA DE OBSERVACIÓN Y DIAGNÓSTICO SCANNER
                </div>

                <div style="padding: 0.5rem 1rem; font-size: 0.75rem; line-height: 1.4; text-align: justify; color: #000 !important;">
                    El scanner automotriz es una herramienta que se utiliza para diagnosticar las fallas registradas en la computadora del vehículo. La computadora se encarga de regular las funciones del auto a través de distintos sensores que monitorean y registran todos los errores con un código.
                </div>

                <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                    OBSERVACIONES: <br />
                    <?php echo htmlspecialchars($peritaje["prueba_escaner"] ?? 'Sin observaciones'); ?>
                </div>
            </section>

            <!-- TREN MOTRIZ Y DIRECCIÓN -->
            <section class="p-2 rounded my-2 simple-border">
                <div class="d-flex gap-2">
                    <div class="yellow-background sub-title-vertical">
                        <span class="vertical-icon-text">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v4m0 14v4M4.22 4.22l2.83 2.83m9.9 9.9l2.83 2.83M1 12h4m14 0h4M4.22 19.78l2.83-2.83m9.9-9.9l2.83-2.83"></path>
                            </svg>
                            TREN MOTRIZ Y DIRECCIÓN
                        </span>
                    </div>
                    
                    <div class="d-flex flex-column w-100">
                        <!-- Tabla de inspección -->
                        <div style="width: 100%;">
                            <!-- Header de la tabla -->
                            <div class="d-flex" style="background-color: var(--main-color); border: 1px solid var(--main-color);">
                                <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">SISTEMA</div>
                                <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">ESTADO</div>
                                <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center;">RESPUESTA</div>
                            </div>
                            
                            <!-- Filas de datos -->
                            <?php foreach ($campos_tren_motriz as $campo => $etiqueta): ?>
                                <div class="d-flex" style="border: 1px solid var(--main-color); border-top: none;">
                                    <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 9px; border-right: 1px solid var(--main-color);">
                                        <?php echo htmlspecialchars($etiqueta); ?>
                                    </div>
                                    <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center; border-right: 1px solid var(--main-color);">
                                        <?php echo htmlspecialchars($peritaje[$campo] ?? 'N/A'); ?>
                                    </div>
                                    <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center;">
                                        <?php echo htmlspecialchars($peritaje[str_replace("estado_", "respuesta_", $campo)] ?? ''); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Observaciones -->
                        <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                            OBSERVACIONES: <br />
                            <?php echo htmlspecialchars($peritaje["observaciones_motor"] ?? 'Sin observaciones'); ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- NIVEL DE LÍQUIDOS -->
            <section class="p-2 rounded my-2 simple-border">
                <div class="d-flex gap-2">
                    <div class="yellow-background sub-title-vertical">
                        <span class="vertical-icon-text">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                            </svg>
                            NIVEL DE LÍQUIDOS
                        </span>
                    </div>
                    
                    <div class="d-flex flex-column w-100">
                        <!-- Tabla de inspección -->
                        <div style="width: 100%;">
                            <!-- Header de la tabla -->
                            <div class="d-flex" style="background-color: var(--main-color); border: 1px solid var(--main-color);">
                                <div style="width: 70%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">SISTEMA</div>
                                <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center;">RESPUESTA</div>
                            </div>
                            
                            <!-- Filas de datos -->
                            <?php foreach ($campos_liquidos as $campo => $etiqueta): ?>
                                <div class="d-flex" style="border: 1px solid var(--main-color); border-top: none;">
                                    <div style="width: 70%; padding: 0.3rem 0.5rem; font-size: 9px; border-right: 1px solid var(--main-color);">
                                        <?php echo htmlspecialchars($etiqueta); ?>
                                    </div>
                                    <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center;">
                                        <?php echo htmlspecialchars($peritaje[$campo] ?? 'N/A'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Observaciones -->
                        <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                            OBSERVACIONES: <br />
                            <?php echo htmlspecialchars($peritaje["observaciones_liquidos"] ?? 'Sin observaciones'); ?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- MOTOR -->
                <?php if (!$esCarro): ?>
                    <section class="p-2 rounded my-2 simple-border">
                        <div class="d-flex gap-2">
                            <div class="yellow-background sub-title-vertical">
                                <span class="vertical-icon-text">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="6" width="4" height="12" rx="1"></rect>
                                        <rect x="18" y="6" width="4" height="12" rx="1"></rect>
                                        <path d="M6 8h12v8H6z"></path>
                                        <path d="M10 8v8m4-8v8"></path>
                                    </svg>
                                    MOTOR
                                </span>
                            </div>
                            
                            <div class="d-flex flex-column w-100">
                                <!-- Tabla de inspección -->
                                <div style="width: 100%;">
                                    <!-- Header de la tabla -->
                                    <div class="d-flex" style="background-color: var(--main-color); border: 1px solid var(--main-color);">
                                        <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">SISTEMA</div>
                                        <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">ESTADO</div>
                                        <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center;">RESPUESTA</div>
                                    </div>
                                    
                                    <!-- Filas de datos -->
                                    <?php foreach ($campos_motor as $campo => $etiqueta): ?>
                                        <div class="d-flex" style="border: 1px solid var(--main-color); border-top: none;">
                                            <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 9px; border-right: 1px solid var(--main-color);">
                                                <?php echo htmlspecialchars($etiqueta); ?>
                                            </div>
                                            <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center; border-right: 1px solid var(--main-color);">
                                                <?php echo htmlspecialchars($peritaje[$campo] ?? 'N/A'); ?>
                                            </div>
                                            <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center;">
                                                <?php echo htmlspecialchars($peritaje[str_replace("estado_", "respuesta_", $campo)] ?? ''); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Observaciones -->
                                <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                    OBSERVACIONES: <br />
                                    <?php echo htmlspecialchars($peritaje["observaciones_motor"] ?? 'Sin observaciones'); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
            <?php echo renderFooter(3); ?>
        </div>

        <div class="page">
            <div class="page-content">
                <div class="d-flex flex-column gap-3 w-100">
                <!-- MOTOR -->
                <?php if ($esCarro): ?>
                    <section class="p-2 rounded my-2 simple-border">
                        <div class="d-flex gap-2">
                            <div class="yellow-background sub-title-vertical">
                                <span class="vertical-icon-text">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="6" width="4" height="12" rx="1"></rect>
                                        <rect x="18" y="6" width="4" height="12" rx="1"></rect>
                                        <path d="M6 8h12v8H6z"></path>
                                        <path d="M10 8v8m4-8v8"></path>
                                    </svg>
                                    MOTOR
                                </span>
                            </div>
                            
                            <div class="d-flex flex-column w-100">
                                <!-- Tabla de inspección -->
                                <div style="width: 100%;">
                                    <!-- Header de la tabla -->
                                    <div class="d-flex" style="background-color: var(--main-color); border: 1px solid var(--main-color);">
                                        <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">SISTEMA</div>
                                        <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">ESTADO</div>
                                        <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center;">RESPUESTA</div>
                                    </div>
                                    
                                    <!-- Filas de datos -->
                                    <?php foreach ($campos_motor as $campo => $etiqueta): ?>
                                        <div class="d-flex" style="border: 1px solid var(--main-color); border-top: none;">
                                            <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 9px; border-right: 1px solid var(--main-color);">
                                                <?php echo htmlspecialchars($etiqueta); ?>
                                            </div>
                                            <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center; border-right: 1px solid var(--main-color);">
                                                <?php echo htmlspecialchars($peritaje[$campo] ?? 'N/A'); ?>
                                            </div>
                                            <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center;">
                                                <?php echo htmlspecialchars($peritaje[str_replace("estado_", "respuesta_", $campo)] ?? ''); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Observaciones -->
                                <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                    OBSERVACIONES: <br />
                                    <?php echo htmlspecialchars($peritaje["observaciones_motor"] ?? 'Sin observaciones'); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- INTERIOR DEL AUTOMOTOR -->
                <section class="p-2 rounded my-2 simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">
                            <span class="vertical-icon-text">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M12 3v2m0 14v2M3 12h2m14 0h2"></path>
                                </svg>
                                INTERIOR DEL AUTOMOTOR
                            </span>
                        </div>
                        
                        <div class="d-flex flex-column w-100">
                            <!-- Tabla de inspección -->
                            <div style="width: 100%;">
                                <!-- Header de la tabla -->
                                <div class="d-flex" style="background-color: var(--main-color); border: 1px solid var(--main-color);">
                                    <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">SISTEMA</div>
                                    <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">ESTADO</div>
                                    <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center;">RESPUESTA</div>
                                </div>
                                
                                <!-- Filas de datos -->
                                <?php foreach ($campos_interior as $campo => $etiqueta): ?>
                                    <div class="d-flex" style="border: 1px solid var(--main-color); border-top: none;">
                                        <div style="width: 40%; padding: 0.3rem 0.5rem; font-size: 9px; border-right: 1px solid var(--main-color);">
                                            <?php echo htmlspecialchars($etiqueta); ?>
                                        </div>
                                        <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center; border-right: 1px solid var(--main-color);">
                                            <?php echo htmlspecialchars($peritaje[$campo] ?? 'N/A'); ?>
                                        </div>
                                        <div style="width: 30%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center;">
                                            <?php echo htmlspecialchars($peritaje[str_replace("estado_", "respuesta_", $campo)] ?? ''); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Observaciones -->
                            <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                OBSERVACIONES: <br />
                                <?php echo htmlspecialchars($peritaje["observaciones_interior"] ?? 'Sin observaciones'); ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- FUGAS -->
                <section class="p-2 rounded my-2 simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">
                            <span class="vertical-icon-text">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                                    <path d="M12 8v4m0 4h.01"></path>
                                </svg>
                                FUGAS
                            </span>
                        </div>
                        
                        <div class="d-flex flex-column w-100">
                            <!-- Tabla de inspección -->
                            <div style="width: 100%;">
                                <!-- Header de la tabla -->
                                <div class="d-flex" style="background-color: var(--main-color); border: 1px solid var(--main-color);">
                                    <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">SISTEMA</div>
                                    <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center;">RESPUESTA</div>
                                </div>
                                
                                <!-- Filas de datos -->
                                <?php foreach ($campos_fugas as $campo => $etiqueta): ?>
                                    <div class="d-flex" style="border: 1px solid var(--main-color); border-top: none;">
                                        <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 9px; border-right: 1px solid var(--main-color);">
                                            <?php echo htmlspecialchars($etiqueta); ?>
                                        </div>
                                        <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center;">
                                            <?php echo htmlspecialchars($peritaje[$campo] ?? ''); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Observaciones -->
                            <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                OBSERVACIONES: <br />
                                <?php echo htmlspecialchars($peritaje["observaciones_fugas"] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- COMPONENTES -->
                <section class="p-2 rounded my-2 simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">
                            <span class="vertical-icon-text">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                                </svg>
                                COMPONENTES
                            </span>
                        </div>
                        
                        <div class="d-flex flex-column w-100">
                            <!-- Tabla de inspección -->
                            <div style="width: 100%;">
                                <!-- Header de la tabla -->
                                <div class="d-flex" style="background-color: var(--main-color); border: 1px solid var(--main-color);">
                                    <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center; border-right: 1px solid var(--main-color);">SISTEMA</div>
                                    <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 10px; font-weight: bold; text-align: center;">RESPUESTA</div>
                                </div>
                                
                                <!-- Filas de datos -->
                                <?php foreach ($campos_estado_componentes as $campo => $etiqueta): ?>
                                    <div class="d-flex" style="border: 1px solid var(--main-color); border-top: none;">
                                        <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 9px; border-right: 1px solid var(--main-color);">
                                            <?php echo htmlspecialchars($etiqueta); ?>
                                        </div>
                                        <div style="width: 50%; padding: 0.3rem 0.5rem; font-size: 9px; text-align: center;">
                                            <?php echo htmlspecialchars($peritaje[$campo] ?? ''); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Observaciones -->
                            <div class="remarks" style="margin-top: 0.5rem; height: fit-content;">
                                OBSERVACIONES: <br />
                                <?php echo htmlspecialchars($peritaje["observaciones_estado_componentes"] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                </section>
                </div>
            </div>
            <?php echo renderFooter(4); ?>
        </div>

        <!-- PÁGINA 5 - FIJACIÓN FOTOGRÁFICA -->
        <div class="page">
            <div class="page-content">
                <section class="p-2 rounded my-2 simple-border">
                    <div class="d-flex gap-2">
                        <div class="yellow-background sub-title-vertical">
                            <span class="vertical-icon-text">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                                FIJACIÓN FOTOGRÁFICA
                            </span>
                        </div>
                        
                        <div class="d-flex flex-column w-100">
                            <p class="descripcion-fotografica">
                                Observación y clasificación de las características del automotor de acuerdo al punto 1
                            </p>
                            
                            <!-- Grid de imágenes -->
                            <div class="grid-fotografias">
                                <?php
                                // Mostrar hasta 6 fotos de la tabla expertise_photos
                                $maxFotos = 6;
                                $totalFotos = count($photos);
                                
                                for ($i = 0; $i < $maxFotos; $i++) {
                                    if ($i < $totalFotos && !empty($photos[$i]['ruta'])) {
                                        echo '<div class="contenedor-imagen">';
                                        echo '<img src="' . $BASE_URL . htmlspecialchars($photos[$i]['ruta']) . '" class="imagen-fijacion" alt="Foto ' . ($i + 1) . '">';
                                        echo '</div>';
                                    } else {
                                        echo '<div class="contenedor-imagen contenedor-vacio">';
                                        echo '<div class="placeholder-imagen">Sin imagen</div>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            
                            <!-- Prueba de ruta -->
                            <div class="prueba-ruta">
                                <strong>PRUEBA DE RUTA:</strong><br>
                                <?php echo htmlspecialchars($peritaje["prueba_ruta"] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECCIÓN DE FIRMAS Y AVISO LEGAL -->
                <section class="seccion-firmas">
                    <!-- Firmas principales -->
                    <div class="contenedor-firmas">
                        <div class="firma-izquierda">
                            <p class="titulo-firma">Firma Inspector estructura vehicular:</p>
                            <div class="linea-firma"></div>
                            <p class="campo-cc">CC:</p>
                        </div>
                        
                        <div class="firma-derecha">
                            <p class="titulo-firma">Firma Cliente:</p>
                            <div class="linea-firma"></div>
                            <p class="campo-cc">CC:</p>
                        </div>
                    </div>
                    
                    <!-- Firma del mecánico (centrada) -->
                    <div class="contenedor-firma-mecanico">
                        <div class="firma-mecanico">
                            <p class="titulo-firma">Firma Perito</p>
                            <div class="linea-firma"></div>
                            <p class="campo-cc">CC:</p>
                        </div>
                    </div>
                    
                    <!-- Aviso legal -->
                    <div class="aviso-legal">
                        <p><strong>AVISO LEGAL:</strong> Pritec Informa que la revisión realizada corresponde al estado del vehículo en la fecha y hora de la misma y con el recorrido del kilometraje que revela el odómetro en el momento, se advierte que, debido a la vulnerabilidad a que se ven expuestos este tipo de bienes, en cuanto a la afectación, modificación, avería, deterioro y desgaste de cualquiera de sus componentes, el informe que se pone de presente no garantiza de ningún modo que el estado del vehículo sea el mismo en fechas posteriores a la fecha de la revisión.</p>
                    </div>
                </section>
            </div>
            <?php echo renderFooter(5); ?>
        </div>

    </main>
</body>

</html>