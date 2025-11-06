
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
        }

        html {
            font-size: 13px;
        }

        :root {
            --main-color: #fff280;
            --gray-color: #d8d8d8;
        }

        p {
            margin: 0;
            font-size: 15px;
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
            font-size: 1.2rem;
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
            min-width: 50%;
            width: fit-content;
            padding: .2rem .5rem;
            align-self: center;
        }

        .input {
            width: 50%;
            padding: .2rem .5rem;
            border: 1px var(--main-color) solid;
            border-radius: 8px;
        }

        .remarks {
            border: 1px solid var(--main-color);
            padding: .5rem 1rem;
            border-radius: 8px;
            height: 50px;
            font-size: .8rem
        }

        .page {
            min-height: 27cm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        @media screen {
            .page {
                border: 1px solid #ddd;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                background: white;
                max-width: 21cm;
                margin: 1rem auto;
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
                <div class="me-4" style="width: 33%;">
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
                            <div class="input">
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
                        <p>Indique con un círculo en que parte del vehículo tiene alguna condición.</p>
                        <div class="yellow-background sub-title ms-4 mb-0">CARROCERÍA</div>
                        <div class="d-flex gap-2 w-100" style="height: 200px">
                            <img src="" class="w-50" style="object-fit: contain;">
                            <div class="d-flex flex-column gap-2 w-50 h-100">
                                <div class="d-flex gap-2">
                                    <div class="yellow-background label text-center" style="width: 70%">Descripción pieza</div>
                                    <div class="input text-center">Concepto</div>
                                </div>
                                <?php foreach ($carroceria as $fila): ?>
                                    <div class="d-flex gap-2">
                                        <div class="yellow-background label" style="width: 70%">
                                            <?php echo htmlspecialchars($fila["descripcion_pieza"]); ?>
                                        </div>
                                        <div class="input"><?php echo htmlspecialchars($fila["concepto"]); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="remarks">
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
                        <p>Indique con un círculo en que parte del vehículo tiene alguna condición.</p>
                        <div>
                            <div class="yellow-background sub-title ms-4 mb-0">ESTRUCTURA</div>
                            <div class="d-flex gap-2 w-100">
                                <img src="" class="w-50" style="object-fit: contain; max-height: 200px">
                                <div class="d-flex flex-column gap-2 w-50 h-100">
                                    <div class="d-flex gap-2">
                                        <div class="yellow-background label text-center" style="width: 70%">Descripción pieza</div>
                                        <div class="input text-center">Concepto</div>
                                    </div>
                                    <?php foreach ($estructura as $fila): ?>
                                        <div class="d-flex gap-2">
                                            <div class="yellow-background label" style="width: 70%">
                                                <?php echo htmlspecialchars($fila["descripcion_pieza"]); ?>
                                            </div>
                                            <div class="input"><?php echo htmlspecialchars($fila["concepto"]); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="remarks">
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
                        <p>Indique con un círculo en que parte del vehículo tiene alguna condición.</p>
                        <div>
                            <div class="yellow-background sub-title mb-0">CHASIS</div>
                            <div class="d-flex gap-2 w-100">
                                <img src="" class="w-50" style="object-fit: contain; max-height: 200px">
                                <div class="d-flex flex-column gap-2 w-50 h-100">
                                    <div class="d-flex gap-2">
                                        <div class="yellow-background label text-center" style="width: 70%">Descripción pieza</div>
                                        <div class="input text-center">Concepto</div>
                                    </div>
                                    <?php foreach ($chasis as $fila): ?>
                                        <div class="d-flex gap-2">
                                            <div class="yellow-background label" style="width: 70%">
                                                <?php echo htmlspecialchars($fila["descripcion_pieza"]); ?>
                                            </div>
                                            <div class="input">
                                                <?php echo htmlspecialchars($fila["concepto"]); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="remarks" style="height: fit-content">
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