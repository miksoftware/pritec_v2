<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-car mr-1"></i>
                        Detalles del Peritaje #<?= $expertise['id'] ?>
                    </h3>
                    <div class="card-tools">
                        <a href="<?= APP_URL ?>expertise" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="<?= APP_URL ?>expertise/edit/<?= $expertise['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= APP_URL ?>expertise/pdf/<?= $expertise['id'] ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                        <?php if ($expertise['status'] === 'in_progress'): ?>
                            <a href="<?= APP_URL ?>expertise/<?= $expertise['id'] ?>/step/<?= $expertise['current_step'] ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-tasks"></i> Continuar Peritaje
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="expertiseTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab" aria-controls="vehicle" aria-selected="false">Vehículo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bodywork-tab" data-toggle="tab" href="#bodywork" role="tab" aria-controls="bodywork" aria-selected="false">Carrocería</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="structure-tab" data-toggle="tab" href="#structure" role="tab" aria-controls="structure" aria-selected="false">Estructura</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="chassis-tab" data-toggle="tab" href="#chassis" role="tab" aria-controls="chassis" aria-selected="false">Chasis</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tires-tab" data-toggle="tab" href="#tires" role="tab" aria-controls="tires" aria-selected="false">Llantas/Amortiguadores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="scanner-tab" data-toggle="tab" href="#scanner" role="tab" aria-controls="scanner" aria-selected="false">Scanner/Batería</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="motor-tab" data-toggle="tab" href="#motor" role="tab" aria-controls="motor" aria-selected="false">Motor/Sistemas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="leaks-tab" data-toggle="tab" href="#leaks" role="tab" aria-controls="leaks" aria-selected="false">Fugas/Niveles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="photos-tab" data-toggle="tab" href="#photos" role="tab" aria-controls="photos" aria-selected="false">Fotos</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="expertiseTabsContent">
                        <!-- Pestaña de Información General -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-primary">
                                            <h3 class="card-title">Información del Peritaje</h3>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">ID:</dt>
                                                <dd class="col-sm-8"><?= $expertise['id'] ?></dd>
                                                
                                                <dt class="col-sm-4">Cliente:</dt>
                                                <dd class="col-sm-8"><?= $expertise['client_name'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Fecha de Servicio:</dt>
                                                <dd class="col-sm-8"><?= date('d/m/Y', strtotime($expertise['service_date'])) ?></dd>
                                                
                                                <dt class="col-sm-4">Número de Servicio:</dt>
                                                <dd class="col-sm-8"><?= $expertise['service_number'] ?></dd>
                                                
                                                <dt class="col-sm-4">Servicio Para:</dt>
                                                <dd class="col-sm-8"><?= $expertise['service_for'] ?></dd>
                                                
                                                <dt class="col-sm-4">Convenio:</dt>
                                                <dd class="col-sm-8"><?= $expertise['agreement'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Estado:</dt>
                                                <dd class="col-sm-8">
                                                    <?php if ($expertise['status'] === 'in_progress'): ?>
                                                        <span class="badge badge-warning">En Progreso</span>
                                                    <?php elseif ($expertise['status'] === 'completed'): ?>
                                                        <span class="badge badge-success">Completado</span>
                                                    <?php endif; ?>
                                                </dd>
                                                
                                                <dt class="col-sm-4">Progreso:</dt>
                                                <dd class="col-sm-8">
                                                    <?php
                                                        $progress = ($expertise['current_step'] / 11) * 100;
                                                        $progressClass = $progress < 50 ? 'bg-warning' : ($progress < 100 ? 'bg-info' : 'bg-success');
                                                    ?>
                                                    <div class="progress">
                                                        <div class="progress-bar <?= $progressClass ?>" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                                                            <?= round($progress) ?>%
                                                        </div>
                                                    </div>
                                                    <small>Paso <?= $expertise['current_step'] ?>/11</small>
                                                </dd>
                                                
                                                <dt class="col-sm-4">Creado por:</dt>
                                                <dd class="col-sm-8"><?= $expertise['created_by_name'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Fecha de Creación:</dt>
                                                <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($expertise['created_at'])) ?></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-info">
                                            <h3 class="card-title">Resumen</h3>
                                        </div>
                                        <div class="card-body">
                                            <?php if ($expertise['status'] === 'in_progress'): ?>
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> Este peritaje está en progreso. Actualmente se encuentra en el paso <?= $expertise['current_step'] ?> de 11.
                                                </div>
                                                
                                                <div class="text-center mt-3">
                                                    <a href="<?= APP_URL ?>expertise/<?= $expertise['id'] ?>/step/<?= $expertise['current_step'] ?>" class="btn btn-info">
                                                        <i class="fas fa-tasks"></i> Continuar Peritaje
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i> Este peritaje está completado.
                                                </div>
                                                
                                                <div class="text-center mt-3">
                                                    <a href="<?= APP_URL ?>expertise/pdf/<?= $expertise['id'] ?>" class="btn btn-primary" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> Generar PDF
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Lista de pasos -->
                                            <div class="mt-4">
                                                <h5>Pasos del Peritaje:</h5>
                                                <ul class="list-group">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 1 ? 'list-group-item-success' : '' ?>">
                                                        1. Información Básica
                                                        <?php if ($expertise['current_step'] >= 1): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 2 ? 'list-group-item-success' : '' ?>">
                                                        2. Datos del Vehículo
                                                        <?php if ($expertise['current_step'] >= 2): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 3 ? 'list-group-item-success' : '' ?>">
                                                        3. Inspección de Carrocería
                                                        <?php if ($expertise['current_step'] >= 3): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 4 ? 'list-group-item-success' : '' ?>">
                                                        4. Inspección de Estructura
                                                        <?php if ($expertise['current_step'] >= 4): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 5 ? 'list-group-item-success' : '' ?>">
                                                        5. Inspección de Chasis
                                                        <?php if ($expertise['current_step'] >= 5): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 6 ? 'list-group-item-success' : '' ?>">
                                                        6. Llantas y Amortiguadores
                                                        <?php if ($expertise['current_step'] >= 6): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 7 ? 'list-group-item-success' : '' ?>">
                                                        7. Scanner y Batería
                                                        <?php if ($expertise['current_step'] >= 7): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 8 ? 'list-group-item-success' : '' ?>">
                                                        8. Motor y Sistemas
                                                        <?php if ($expertise['current_step'] >= 8): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 9 ? 'list-group-item-success' : '' ?>">
                                                        9. Fugas y Niveles
                                                        <?php if ($expertise['current_step'] >= 9): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 10 ? 'list-group-item-success' : '' ?>">
                                                        10. Fijación Fotográfica
                                                        <?php if ($expertise['current_step'] >= 10): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center <?= $expertise['current_step'] >= 11 ? 'list-group-item-success' : '' ?>">
                                                        11. Finalización
                                                        <?php if ($expertise['current_step'] >= 11): ?>
                                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                                        <?php endif; ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pestaña de Vehículo -->
                        <div class="tab-pane fade" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h3 class="card-title">Información del Vehículo</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <dl class="row">
                                                <dt class="col-sm-4">Placa:</dt>
                                                <dd class="col-sm-8"><?= $expertise['plate'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Tipo:</dt>
                                                <dd class="col-sm-8"><?= $expertise['vehicle_type_name'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Clase:</dt>
                                                <dd class="col-sm-8"><?= $expertise['vehicle_class'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Marca:</dt>
                                                <dd class="col-sm-8"><?= $expertise['brand'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Línea:</dt>
                                                <dd class="col-sm-8"><?= $expertise['line'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Modelo:</dt>
                                                <dd class="col-sm-8"><?= $expertise['model'] ?? 'N/A' ?></dd>
                                            </dl>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <dl class="row">
                                                <dt class="col-sm-4">Color:</dt>
                                                <dd class="col-sm-8"><?= $expertise['color'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Servicio:</dt>
                                                <dd class="col-sm-8"><?= $expertise['service'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Cilindraje:</dt>
                                                <dd class="col-sm-8"><?= $expertise['cylinder'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Carrocería:</dt>
                                                <dd class="col-sm-8"><?= $expertise['body_type'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Kilometraje:</dt>
                                                <dd class="col-sm-8"><?= $expertise['mileage'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Organismo de Tránsito:</dt>
                                                <dd class="col-sm-8"><?= $expertise['transit_agency'] ?? 'N/A' ?></dd>
                                            </dl>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <dl class="row">
                                                <dt class="col-sm-4">Chasis:</dt>
                                                <dd class="col-sm-8"><?= $expertise['chassis_number'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Motor:</dt>
                                                <dd class="col-sm-8"><?= $expertise['motor_number'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Serie:</dt>
                                                <dd class="col-sm-8"><?= $expertise['series_number'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Código Fasecolda:</dt>
                                                <dd class="col-sm-8"><?= $expertise['fasecolda_code'] ?? 'N/A' ?></dd>
                                                
                                                <dt class="col-sm-4">Valor Fasecolda:</dt>
                                                <dd class="col-sm-8"><?= number_format($expertise['fasecolda_value'] ?? 0, 0, ',', '.') ?></dd>
                                                
                                                <dt class="col-sm-4">Valor Sugerido:</dt>
                                                <dd class="col-sm-8"><?= number_format($expertise['suggested_value'] ?? 0, 0, ',', '.') ?></dd>
                                                
                                                <dt class="col-sm-4">Valor Accesorios:</dt>
                                                <dd class="col-sm-8"><?= number_format($expertise['accessories_value'] ?? 0, 0, ',', '.') ?></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pestaña de Carrocería -->
                        <div class="tab-pane fade" id="bodywork" role="tabpanel" aria-labelledby="bodywork-tab">
                            <?php if ($expertise['current_step'] < 3): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se ha completado la inspección de carrocería.
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Inspección de Carrocería</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Aquí iría la tabla con los resultados de la inspección de carrocería -->
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Pieza</th>
                                                    <th>Concepto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Los datos se cargarían dinámicamente desde la base de datos -->
                                                <?php if (isset($expertise['bodywork_inspections']) && is_array($expertise['bodywork_inspections'])): ?>
                                                    <?php foreach ($expertise['bodywork_inspections'] as $inspection): ?>
                                                        <tr>
                                                            <td><?= $inspection['part_name'] ?></td>
                                                            <td><?= $inspection['concept_name'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No hay datos de inspección de carrocería</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        
                                        <div class="mt-3">
                                            <h5>Observaciones:</h5>
                                            <p><?= $expertise['bodywork_observation'] ?? 'Sin observaciones' ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pestaña de Estructura -->
                        <div class="tab-pane fade" id="structure" role="tabpanel" aria-labelledby="structure-tab">
                            <?php if ($expertise['current_step'] < 4): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se ha completado la inspección de estructura.
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Inspección de Estructura</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Aquí iría la tabla con los resultados de la inspección de estructura -->
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Pieza</th>
                                                    <th>Concepto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Los datos se cargarían dinámicamente desde la base de datos -->
                                                <?php if (isset($expertise['structure_inspections']) && is_array($expertise['structure_inspections'])): ?>
                                                    <?php foreach ($expertise['structure_inspections'] as $inspection): ?>
                                                        <tr>
                                                            <td><?= $inspection['part_name'] ?></td>
                                                            <td><?= $inspection['concept_name'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No hay datos de inspección de estructura</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        
                                        <div class="mt-3">
                                            <h5>Observaciones:</h5>
                                            <p><?= $expertise['structure_observation'] ?? 'Sin observaciones' ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pestaña de Chasis -->
                        <div class="tab-pane fade" id="chassis" role="tabpanel" aria-labelledby="chassis-tab">
                            <?php if ($expertise['current_step'] < 5): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se ha completado la inspección de chasis.
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Inspección de Chasis</h3>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tipo de Chasis:</strong> <?= $expertise['chassis_type'] ?? 'N/A' ?></p>
                                        
                                        <?php if ($expertise['chassis_type'] !== 'NO APLICA'): ?>
                                            <!-- Aquí iría la tabla con los resultados de la inspección de chasis -->
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Pieza</th>
                                                        <th>Concepto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Los datos se cargarían dinámicamente desde la base de datos -->
                                                    <?php if (isset($expertise['chassis_inspections']) && is_array($expertise['chassis_inspections'])): ?>
                                                        <?php foreach ($expertise['chassis_inspections'] as $inspection): ?>
                                                            <tr>
                                                                <td><?= $inspection['part_name'] ?></td>
                                                                <td><?= $inspection['concept_name'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="2" class="text-center">No hay datos de inspección de chasis</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                        
                                        <div class="mt-3">
                                            <h5>Observaciones:</h5>
                                            <p><?= $expertise['chassis_observation'] ?? 'Sin observaciones' ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pestaña de Llantas y Amortiguadores -->
                        <div class="tab-pane fade" id="tires" role="tabpanel" aria-labelledby="tires-tab">
                            <?php if ($expertise['current_step'] < 6): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se han registrado los datos de llantas y amortiguadores.
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary">
                                                <h3 class="card-title">Llantas</h3>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Posición</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Delantera Izquierda</td>
                                                            <td><?= $expertise['tire_front_left'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Delantera Derecha</td>
                                                            <td><?= $expertise['tire_front_right'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Trasera Izquierda</td>
                                                            <td><?= $expertise['tire_rear_left'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Trasera Derecha</td>
                                                            <td><?= $expertise['tire_rear_right'] ?? 'N/A' ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <div class="mt-3">
                                                    <h5>Observaciones:</h5>
                                                    <p><?= $expertise['tires_observation'] ?? 'Sin observaciones' ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary">
                                                <h3 class="card-title">Amortiguadores</h3>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Posición</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Delantero Izquierdo</td>
                                                            <td><?= $expertise['shock_front_left'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Delantero Derecho</td>
                                                            <td><?= $expertise['shock_front_right'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Trasero Izquierdo</td>
                                                            <td><?= $expertise['shock_rear_left'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Trasero Derecho</td>
                                                            <td><?= $expertise['shock_rear_right'] ?? 'N/A' ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <div class="mt-3">
                                                    <h5>Observaciones:</h5>
                                                    <p><?= $expertise['shocks_observation'] ?? 'Sin observaciones' ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pestaña de Scanner y Batería -->
                        <div class="tab-pane fade" id="scanner" role="tabpanel" aria-labelledby="scanner-tab">
                            <?php if ($expertise['current_step'] < 7): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se han registrado los datos de scanner y batería.
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary">
                                                <h3 class="card-title">Scanner</h3>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Código:</strong> <?= $expertise['scanner_code'] ?? 'N/A' ?></p>
                                                
                                                <div class="mt-3">
                                                    <h5>Observaciones:</h5>
                                                    <p><?= $expertise['scanner_observation'] ?? 'Sin observaciones' ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary">
                                                <h3 class="card-title">Batería</h3>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th>Prueba de Batería:</th>
                                                            <td><?= $expertise['battery_test'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Prueba de Arranque:</th>
                                                            <td><?= $expertise['start_test'] ?? 'N/A' ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Carga de Batería:</th>
                                                            <td><?= $expertise['battery_charge'] ?? 'N/A' ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <div class="mt-3">
                                                    <h5>Observaciones:</h5>
                                                    <p><?= $expertise['battery_observation'] ?? 'Sin observaciones' ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pestaña de Motor y Sistemas -->
                        <div class="tab-pane fade" id="motor" role="tabpanel" aria-labelledby="motor-tab">
                            <?php if ($expertise['current_step'] < 8): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se han registrado los datos de motor y sistemas.
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Motor y Sistemas</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="motorTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="motor-systems-tab" data-toggle="tab" href="#motor-systems" role="tab" aria-controls="motor-systems" aria-selected="true">Sistema Motor</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="brake-systems-tab" data-toggle="tab" href="#brake-systems" role="tab" aria-controls="brake-systems" aria-selected="false">Sistema de Frenos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="interior-systems-tab" data-toggle="tab" href="#interior-systems" role="tab" aria-controls="interior-systems" aria-selected="false">Interior</a>
                                            </li>
                                        </ul>
                                        
                                        <div class="tab-content mt-3" id="motorTabsContent">
                                            <!-- Sistema Motor -->
                                            <div class="tab-pane fade show active" id="motor-systems" role="tabpanel" aria-labelledby="motor-systems-tab">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Componente</th>
                                                            <th>Estado</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Los datos se cargarían dinámicamente desde la base de datos -->
                                                        <?php if (isset($expertise['motor_systems']) && is_array($expertise['motor_systems'])): ?>
                                                            <?php foreach ($expertise['motor_systems'] as $system): ?>
                                                                <tr>
                                                                    <td><?= $system['name'] ?></td>
                                                                    <td><?= $system['status'] ?></td>
                                                                    <td><?= $system['observation'] ?? '' ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="3" class="text-center">No hay datos de sistema motor</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                
                                                <div class="mt-3">
                                                    <h5>Observaciones:</h5>
                                                    <p><?= $expertise['motor_observation'] ?? 'Sin observaciones' ?></p>
                                                </div>
                                            </div>
                                            
                                            <!-- Sistema de Frenos -->
                                            <div class="tab-pane fade" id="brake-systems" role="tabpanel" aria-labelledby="brake-systems-tab">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Componente</th>
                                                            <th>Estado</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Los datos se cargarían dinámicamente desde la base de datos -->
                                                        <?php if (isset($expertise['brake_systems']) && is_array($expertise['brake_systems'])): ?>
                                                            <?php foreach ($expertise['brake_systems'] as $system): ?>
                                                                <tr>
                                                                    <td><?= $system['name'] ?></td>
                                                                    <td><?= $system['status'] ?></td>
                                                                    <td><?= $system['observation'] ?? '' ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="3" class="text-center">No hay datos de sistema de frenos</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <!-- Interior -->
                                            <div class="tab-pane fade" id="interior-systems" role="tabpanel" aria-labelledby="interior-systems-tab">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Componente</th>
                                                            <th>Estado</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Los datos se cargarían dinámicamente desde la base de datos -->
                                                        <?php if (isset($expertise['interior_systems']) && is_array($expertise['interior_systems'])): ?>
                                                            <?php foreach ($expertise['interior_systems'] as $system): ?>
                                                                <tr>
                                                                    <td><?= $system['name'] ?></td>
                                                                    <td><?= $system['status'] ?></td>
                                                                    <td><?= $system['observation'] ?? '' ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="3" class="text-center">No hay datos de interior</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                
                                                <div class="mt-3">
                                                    <h5>Observaciones:</h5>
                                                    <p><?= $expertise['interior_observation'] ?? 'Sin observaciones' ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pestaña de Fugas y Niveles -->
                        <div class="tab-pane fade" id="leaks" role="tabpanel" aria-labelledby="leaks-tab">
                            <?php if ($expertise['current_step'] < 9): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se han registrado los datos de fugas y niveles.
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Fugas y Niveles</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Ítem</th>
                                                    <th>Observación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Los datos se cargarían dinámicamente desde la base de datos -->
                                                <?php if (isset($expertise['leaks_levels']) && is_array($expertise['leaks_levels'])): ?>
                                                    <?php foreach ($expertise['leaks_levels'] as $item): ?>
                                                        <tr>
                                                            <td><?= $item['name'] ?></td>
                                                            <td><?= $item['observation'] ?? '' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No hay datos de fugas y niveles</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        
                                        <div class="mt-3">
                                            <h5>Prueba de Ruta:</h5>
                                            <p><?= $expertise['route_test'] ?? 'No registrada' ?></p>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <h5>Observaciones:</h5>
                                            <p><?= $expertise['leaks_observation'] ?? 'Sin observaciones' ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Pestaña de Fotos -->
                        <div class="tab-pane fade" id="photos" role="tabpanel" aria-labelledby="photos-tab">
                            <?php if ($expertise['current_step'] < 10): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No se han registrado fotos del peritaje.
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Fijación Fotográfica</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php for ($i = 1; $i <= 6; $i++): ?>
                                                <?php $photoKey = 'photo_' . $i; ?>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="card-title">Foto <?= $i ?></h5>
                                                        </div>
                                                        <div class="card-body text-center">
                                                            <?php if (isset($expertise[$photoKey]) && !empty($expertise[$photoKey])): ?>
                                                                <img src="<?= APP_URL ?>public/uploads/expertise/<?= $expertise['id'] ?>/<?= $expertise[$photoKey] ?>" class="img-fluid" alt="Foto <?= $i ?>">
                                                            <?php else: ?>
                                                                <p>No se ha subido una foto</p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
