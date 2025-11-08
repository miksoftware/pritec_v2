
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
            html, body {
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
            font-size: 2.5rem;
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
        }

        @media screen {
            .page {
                border: 1px solid #ddd;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                max-width: 26cm;
                min-height: 33cm;
                margin: 1rem auto 2rem auto;
            }
        }

        @media print {
            .page {
                margin: 0;
                padding: 0.5cm;
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
        h1, h2, h3, h4, h5, h6 {
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
            text-align: center;
            font-size: 0.75rem;
            color: #666;
            padding: 0.5rem 0;
            border-top: 1px solid #ddd;
            margin-top: auto;
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
        <div class="d-flex flex-column gap-3">
            <section class="d-flex flex-column">
                <h1 class="text-center">SALA TÉCNICA EN AUTOMOTORES</h1>
                <h3 class="text-center mb-3">CERIFICACIÓN TÉCNICA EN IDENTIFICACIÓN DE AUTOMOTORES</h3>
                <header class="d-flex gap-4 align-items-center mx-auto">
                    <img src="/pritec_v2/public/assets/img/pritec.png" style="width: 150px;object-fit: contain;"/>
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
                            <div class="yellow-background label">Organismo de <br/> tránsito</div>
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
                            <div class="yellow-background label">Nombres y <br/> apellidos</div>
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
                                <div style="position: relative; ">
                                    <img src="<?php echo $BASE_URL . $carroceria['image']; ?>" style="width: 100%; height: 100%; object-fit: contain;" onerror="this.style.display='none'">
                                    <?php if (!empty($carroceria['pieces'])): ?>
                                        <?php foreach ($carroceria['pieces'] as $pieza): ?>
                                            <?php if (!empty($pieza['position_x']) && !empty($pieza['position_y'])): ?>
                                                <div style="position: absolute; left: <?php echo $pieza['position_x']; ?>px; top: <?php echo $pieza['position_y']; ?>px; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #ff0000; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
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
                            OBSERVACIONES: <br/> <?php echo htmlspecialchars($peritaje["observaciones_inspeccion"]); ?>
                        </div>
                    </div>
                </div>
            </section>
            
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
                                <div style="position: relative; ">
                                    <img src="<?php echo $BASE_URL . $estructura['image']; ?>" style="width: 100%; height: 100%; object-fit: contain;" onerror="this.style.display='none'">
                                    <?php if (!empty($estructura['pieces'])): ?>
                                        <?php foreach ($estructura['pieces'] as $pieza): ?>
                                            <?php if (!empty($pieza['position_x']) && !empty($pieza['position_y'])): ?>
                                                <div style="position: absolute; left: <?php echo $pieza['position_x']; ?>px; top: <?php echo $pieza['position_y']; ?>px; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #ff0000; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
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
                            OBSERVACIONES: <br/> <?php echo htmlspecialchars($peritaje["observaciones_inspeccion"]); ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="footer">LA MEJOR FORMA DE COMPRAR UN CARRO USADO</div>
    </div>
    
    <div class="page">
        <div class="d-flex flex-column gap-3 w-100">
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
                                <div class="w-50" style="position: relative; ">
                                    <img src="<?php echo $BASE_URL . $chasis['image']; ?>" style="width: 100%; height: 100%; object-fit: contain;" onerror="this.style.display='none'">
                                    <?php if (!empty($chasis['pieces'])): ?>
                                        <?php foreach ($chasis['pieces'] as $pieza): ?>
                                            <?php if (!empty($pieza['position_x']) && !empty($pieza['position_y'])): ?>
                                                <div style="position: absolute; left: <?php echo $pieza['position_x']; ?>px; top: <?php echo $pieza['position_y']; ?>px; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #ff0000; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
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
                            OBSERVACIONES: <br/> <?php echo $peritaje["observaciones_estructura"]; ?>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="p-2 rounded my-2 simple-border">
                <div class="d-flex gap-2">
                    <div class="yellow-background sub-title-vertical">LLANTAS Y AMORTIGUADORES</div>
                    <div class="d-flex flex-column w-100">
                        <div class="yellow-background sub-title w-100">CONVENCIONES LLANTA</div>
                        <div class="mx-auto d-flex flex-column gap-2" style="width: 95%; font-size: 0.9rem">
                            <div class="d-flex">
                                <div style="height: 20px; width: 25%; background-color:#ff3933;"></div>
                                <div style="height: 20px; width: 25%; background-color:#ff8a33;"></div>
                                <div style="height: 20px; width: 25%; background-color:#ffff33;"></div>
                                <div style="height: 20px; width: 25%; background-color:#36d048;"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>0%</p>
                                <p>100%</p>
                            </div>
                        </div>
                        <div class="remarks" style="height: fit-content">
                            OBSERVACIONES: <br/> <?php echo $peritaje["observaciones_estructura"]; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="footer">LA MEJOR FORMA DE COMPRAR UN CARRO USADO</div>
    </div>
    
    <div class="page">
        <section class="p-2 rounded my-2 simple-border">
            <div class="d-flex gap-2">
                <div class="yellow-background sub-title-vertical">ACTUACIÓN DE LA BATERIA</div>
                <div class="d-flex flex-column w-100">
                    <div class="d-flex gap-2 w-100">
                        <div class="d-flex flex-column gap-3" style="width: 30%">
                            <div class="yellow-background label text-center w-100">BATERÍA</div>
                            <img src="<?php echo $BASE_URL; ?>/public/assets/img/BATERIA.png" style="object-fit: contain" onerror="this.style.display='none'">
                        </div>
                        <div class="d-flex flex-column gap-2 h-100" style="width: 70%;">
                            <div class="d-flex gap-2">
                                <div class="yellow-background label text-center">Ítem</div>
                                <div class="yellow-background label text-center">Concepto</div>
                                <div class="yellow-background label text-center">Porcentaje</div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="input text-center">Prueba de batería</div>
                                <div class="input text-center"><?php echo $peritaje["prueba_bateria"]; ?></div>
                                <div class="input text-center"><?php echo $peritaje["prueba_bateria"]; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="p-2 rounded my-2 simple-border">
            <div class="d-flex flex-column gap-2">
                <div class="yellow-background sub-title w-100">PRUEBA DE OBSERVACIÓN Y DIAGNÓSTICO SCANNER</div>
                <div class="d-flex flex-column w-100">
                    <p>El scanner automotriz es una herramienta que se utiliza para diagnosticar las fallas registradas
                        en la computadora del vehículo. La computadora se encarga de regular las funciones del auto
                        a través de distintos sensores que monitorean y registran todos los errores con un código:</p>
                    <?php echo $peritaje["prueba_escaner"]; ?>
                </div>
            </div>
        </section>
        <div class="footer">LA MEJOR FORMA DE COMPRAR UN CARRO USADO</div>
    </div>
</main>
</body>
</html>