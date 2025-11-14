<?php
// Funciones helper para llantas y amortiguadores
function obtenerEstadoPorPorcentaje($porcentaje)
{
    $porcentaje = intval($porcentaje);
    if ($porcentaje >= 0 && $porcentaje <= 24) {
        return 'Peligroso';
    } elseif ($porcentaje >= 25 && $porcentaje <= 49) {
        return 'Precaución';
    } elseif ($porcentaje >= 50 && $porcentaje <= 74) {
        return 'Seguro';
    } elseif ($porcentaje >= 75 && $porcentaje <= 100) {
        return 'Nuevas';
    }
    return 'N/A';
}

function obtenerClasePorPorcentaje($porcentaje)
{
    $porcentaje = intval($porcentaje);
    if ($porcentaje >= 0 && $porcentaje <= 24) {
        return 'estado-peligroso';
    } elseif ($porcentaje >= 25 && $porcentaje <= 49) {
        return 'estado-precaucion';
    } elseif ($porcentaje >= 50 && $porcentaje <= 74) {
        return 'estado-seguro';
    } elseif ($porcentaje >= 75 && $porcentaje <= 100) {
        return 'estado-nuevas';
    }
    return '';
}

function obtenerEstadoBateriaPorPorcentaje($porcentaje)
{
    $porcentaje = intval($porcentaje);
    if ($porcentaje >= 0 && $porcentaje <= 24) {
        return 'Crítico';
    } elseif ($porcentaje >= 25 && $porcentaje <= 49) {
        return 'Bajo';
    } elseif ($porcentaje >= 50 && $porcentaje <= 74) {
        return 'Bueno';
    } elseif ($porcentaje >= 75 && $porcentaje <= 100) {
        return 'Excelente';
    }
    return 'N/A';
}

// Función para renderizar el footer con número de página
function renderFooter($pageNumber) {
    return '
    <div class="footer">
        <span class="footer-text">LA MEJOR FORMA DE COMPRAR UN CARRO USADO</span>
        <span class="footer-page">Página ' . $pageNumber . '</span>
    </div>';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peritaje Completo - <?php echo htmlspecialchars($peritaje['placa']); ?></title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                size: legal;
                margin: 1cm 0.8cm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .page {
                page-break-after: always;
                min-height: auto;
            }

            .page:last-child {
                page-break-after: auto;
            }
        }
    </style>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-size: 13px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f5f5;
        }

        @media print {

            html,
            body {
                font-size: 12px;
            }

            body {
                background-color: white;
            }
        }

        :root {
            --main-color: #fff280;
            --gray-color: #d8d8d8;
        }

        p {
            margin: 0;
            font-size: 14px;
        }

        .plate {
            background-color: var(--gray-color);
            border: 1px solid var(--main-color);
            text-align: center;
            width: fit-content;
            padding: 2px 1.5rem;
            border-radius: 4px;
            margin: auto;
            letter-spacing: 3px;
            font-weight: bold;
            font-size: 2rem;
            -webkit-text-stroke: .8px var(--main-color);
        }

        .yellow-background {
            background: var(--main-color);
            border-radius: 8px;
            text-wrap: nowrap;
            font-size: 1rem;

        }

        .sub-title {
            text-align: center;
            margin: 1rem auto;
            width: fit-content;
            padding: .2rem 1.5rem;
        }

        .sub-title-vertical {
            text-align: center;
            margin: 0 1rem;
            width: fit-content;
            padding: 1.5rem .2rem;
            writing-mode: sideways-lr;
        }

        .label {
            min-width: 40%;
            width: fit-content;
            padding: .2rem .5rem;
            align-self: center;
        }

        .input {
            width: 50%;
            padding: .2rem .5rem;
            border: 1px var(--main-color) solid;
            border-radius: 8px;
            font-size: 10px;
        }

        .remarks {
            border: 1px solid var(--main-color);
            padding: .5rem 1rem;
            border-radius: 8px;
            height: 50px;
            font-size: .8rem
        }

        .page {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            background: white;
            min-height: 35.56cm; /* Altura legal/oficio */
            justify-content: space-between; /* Separa contenido del footer */
        }

        @media screen {
            .page {
                border: 1px solid #ddd;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                max-width: 26cm;
                min-height: 35.56cm;
                margin: 1rem auto 2rem auto;
            }
        }

        @media print {
            .page {
                min-height: 35.56cm; /* Altura legal/oficio en impresión */
            }
        }

        .simple-border {
            border: 1px solid var(--main-color)
        }

        .w-100 {
            width: 100%;
        }

        .w-50 {
            width: 50%;
        }

        .d-flex {
            display: flex;
        }

        .flex-column {
            flex-direction: column;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        .gap-4 {
            gap: 1.5rem;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .justify-content-center {
            justify-content: center;
        }

        .text-center {
            text-align: center;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .my-2 {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .me-4 {
            margin-right: 1.5rem;
        }

        .me-5 {
            margin-right: 3rem;
        }

        .ms-4 {
            margin-left: 1.5rem;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .rounded {
            border-radius: 8px;
        }

        .h-100 {
            height: 100%;
        }

        /* Estilos para la cabecera */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 1.3rem;
            font-weight: bold;
        }

        h3 {
            font-size: 0.9rem;
            font-weight: normal;
        }

        .header-title {
            font-size: 1.3rem;
            font-weight: bold;
            text-align: center;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 0.9rem;
            font-weight: normal;
            text-align: center;
            margin: 0.2rem 0 0.5rem 0;
        }

        .header-container {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            border: 2px solid #000;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }

        .header-logo {
            width: 120px;
            flex-shrink: 0;
        }

        .header-info {
            flex: 1;
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .header-service {
            width: 180px;
            flex-shrink: 0;
            background-color: var(--main-color);
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .header-service p {
            margin: 0.1rem 0;
        }

        /* Pie de página */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #666;
            padding: 0.5rem 0;
            border-top: 1px solid #ddd;
            margin-top: auto; /* Empuja el footer al fondo */
            flex-shrink: 0; /* Evita que se contraiga */
        }

        .footer-text {
            font-weight: normal;
        }

        .footer-page {
            font-weight: bold;
        }

        /* Contenedor de contenido de página */
        .page-content {
            flex: 1; /* Ocupa el espacio disponible */
            display: flex;
            flex-direction: column;
        }

        /* Estilos para llantas y amortiguadores */
        .estado-peligroso {
            background-color: #ff3933 !important;
            color: white;
        }

        .estado-precaucion {
            background-color: #ff8a33 !important;
            color: white;
        }

        .estado-seguro {
            background-color: #ffff33 !important;
            color: #333;
        }

        .estado-nuevas {
            background-color: #36d048 !important;
            color: white;
        }

        .tabla-llantas {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-llantas th {
            background-color: var(--main-color);
            padding: 0.3rem 0.5rem;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid var(--main-color);
        }

        .tabla-llantas td {
            padding: 0.3rem 0.5rem;
            font-size: 9px;
            border: 1px solid var(--main-color);
            text-align: center;
        }

        .imagen-llantas {
            width: 200px;
            height: auto;
            object-fit: contain;
        }

        @media print {
            .imagen-llantas {
                max-height: 180px;
            }
        }

        /* Estilos para Fijación Fotográfica */
        .descripcion-fotografica {
            font-size: 0.75rem;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: normal;
        }

        .grid-fotografias {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .contenedor-imagen {
            width: 100%;
            height: 200px;
            border: 1px solid var(--main-color);
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
        }

        .imagen-fijacion {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .contenedor-vacio {
            background-color: #f0f0f0;
        }

        .placeholder-imagen {
            color: #999;
            font-size: 0.75rem;
            text-align: center;
        }

        .prueba-ruta {
            border: 1px solid var(--main-color);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            min-height: 80px;
            font-size: 0.8rem;
            margin-top: 1rem;
        }

        @media print {
            .grid-fotografias {
                break-inside: avoid;
            }
            
            .contenedor-imagen {
                height: 180px;
            }
        }

        /* Estilos para sección de firmas */
        .seccion-firmas {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            padding: 1rem;
        }

        .contenedor-firmas {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
        }

        .firma-izquierda,
        .firma-derecha {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .contenedor-firma-mecanico {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .firma-mecanico {
            width: 50%;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .titulo-firma {
            font-size: 0.85rem;
            font-weight: bold;
            margin: 0;
            text-align: center;
        }

        .linea-firma {
            border-bottom: 2px solid #000;
            margin: 2rem 0 0.5rem 0;
            min-height: 60px;
        }

        .campo-cc {
            font-size: 0.85rem;
            margin: 0;
            text-align: left;
        }

        .aviso-legal {
            background-color: #f9f9f9;
            border: 1px solid var(--main-color);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .aviso-legal p {
            font-size: 0.7rem;
            line-height: 1.4;
            text-align: justify;
            margin: 0;
        }

        .aviso-legal strong {
            color: #d32f2f;
            font-weight: bold;
        }

        @media print {
            .seccion-firmas {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <!-- BOTONES DE ACCIÓN (se ocultan al imprimir) -->
    <div class="no-print" style="position: fixed; top: 10px; right: 10px; z-index: 1000; display: flex; gap: 10px;">
        <button onclick="window.print()" style="background-color: #FFD700; border: 2px solid #000; padding: 10px 20px; cursor: pointer; font-weight: bold; border-radius: 5px;">
            🖨️ Imprimir / Guardar PDF
        </button>
        <button onclick="window.close()" style="background-color: #f44336; color: white; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; border-radius: 5px;">
            ✖ Cerrar
        </button>
    </div>

    <main class="w-100">
        <div class="page">
            <div class="page-content">
                <div class="d-flex flex-column gap-3">
                    <section class="d-flex flex-column">
                    <h1 class="text-center">SALA TÉCNICA EN AUTOMOTORES</h1>
                    <h3 class="text-center mb-3">CERIFICACIÓN TÉCNICA EN IDENTIFICACIÓN DE AUTOMOTORES</h3>
                    <header class="d-flex gap-4 align-items-center mx-auto">
                        <img src="/pritec_v2/public/assets/img/pritec.png" style="width: 120px;object-fit: contain;" />
                        <div class="me-3">
                            <p>Dirección: Carrera 16 No. 18-197 Barrio Tenerife</p>
                            <p>Teléfono: 3132049245-3158928492</p>
                            <p>Web: peritos.pritec.co</p>
                            <p>Peritos e inspecciones técnicas vehiculares Neiva-Huila</p>
                        </div>
                        <div>
                            <p>Fecha: <?php echo $peritaje["fecha"]; ?></p>
                            <p>No. Servicio: <?php echo $peritaje["no_servicio"]; ?></p>
                            <p>Servicio para: <?php echo $peritaje["servicio_para"]; ?></p>
                            <p>Convenio: <?php echo $peritaje["convenio"]; ?></p>
                        </div>
                    </header>
                </section>
                <section class="d-flex gap-2 rounded p-2 simple-border">
                    <div class="d-flex" style="width: 33%;">
                        <div class="yellow-background sub-title-vertical">
                            DATOS DEL VEHÍCULO
                        </div>
                        <div class="d-flex flex-column gap-2 w-100">
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Clase</div>
                                <div class="input">
                                    <?php echo $peritaje["clase"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Marca</div>
                                <div class="input">
                                    <?php echo $peritaje["marca"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Línea</div>
                                <div class="input">
                                    <?php echo $peritaje["linea"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Cilindraje</div>
                                <div class="input">
                                    <?php echo $peritaje["cilindraje"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Kilometraje</div>
                                <div class="input">
                                    <?php echo $peritaje["kilometraje"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Servicio</div>
                                <div class="input">
                                    <?php echo $peritaje["servicio"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Modelo</div>
                                <div class="input">
                                    <?php echo $peritaje["modelo"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Color</div>
                                <div class="input">
                                    <?php echo $peritaje["color"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">No. de chasis</div>
                                <div class="input">
                                    <?php echo $peritaje["no_chasis"]; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex" style="width: 33%;">
                        <div class="d-flex flex-column gap-2 w-100">
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">No. de motor</div>
                                <div class="input">
                                    <?php echo $peritaje["no_motor"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">No. de serie</div>
                                <div class="input">
                                    <?php echo $peritaje["no_serie"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Tipo de carrocería</div>
                                <div class="input">
                                    <?php echo $peritaje["tipo_carroceria"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Organismo de <br /> tránsito</div>
                                <div class="input">
                                    <?php echo $peritaje["organismo_transito"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Código fasecolda</div>
                                <div class="input">
                                    <?php echo $peritaje["codigo_fasecolda"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Valor fasecolda</div>
                                <div class="input">
                                    <?php echo $peritaje["valor_fasecolda"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Valor sugerido</div>
                                <div class="input">
                                    <?php echo $peritaje["valor_sugerido"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Valor accesorios</div>
                                <div class="input">
                                    <?php echo $peritaje["valor_accesorios"]; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="me-2" style="width: 33%;">
                        <div class="plate"><?php echo $peritaje["placa"]; ?></div>
                        <div class="yellow-background sub-title">DATOS DEL SOLICITANTE</div>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Nombres y <br /> apellidos</div>
                                <div class="input">
                                    <?php echo $peritaje["nombre_apellidos"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Identificación</div>
                                <div class="input">
                                    <?php echo $peritaje["identificacion"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Teléfono</div>
                                <div class="input">
                                    <?php echo $peritaje["telefono"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Dirección</div>
                                <div class="input">
                                    <?php echo $peritaje["direccion"]; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="yellow-background label">Correo</div>
                                <div class="input" style="font-size: 10px;">
                                    <?php echo $peritaje["email"]; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <?php
                // Determinar si es carro o moto
                $esCarro = (isset($peritaje['type']) && $peritaje['type'] === 'carro');
                ?>

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
                <div class="yellow-background sub-title w-100 text-center" style="margin-bottom: 0.5rem;">
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
                    <div class="yellow-background sub-title-vertical">TREN MOTRIZ Y DIRECCIÓN</div>
                    
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
                    <div class="yellow-background sub-title-vertical">NIVEL DE LÍQUIDOS</div>
                    
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
                            <div class="yellow-background sub-title-vertical">MOTOR</div>
                            
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
                            <div class="yellow-background sub-title-vertical">MOTOR</div>
                            
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
                        <div class="yellow-background sub-title-vertical">INTERIOR DEL AUTOMOTOR</div>
                        
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
                        <div class="yellow-background sub-title-vertical">FUGAS</div>
                        
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
                        <div class="yellow-background sub-title-vertical">COMPONENTES</div>
                        
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
                        <div class="yellow-background sub-title-vertical">FIJACIÓN FOTOGRÁFICA</div>
                        
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
                            <p class="titulo-firma">Firma Mecánico Automotriz</p>
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