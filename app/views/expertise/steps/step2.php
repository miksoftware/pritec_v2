<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-car mr-1"></i>
                        Peritaje #<?= $expertise['id'] ?> - Paso 2: Datos del Vehículo
                    </h3>
                    <div class="card-tools">
                        <a href="<?= APP_URL ?>expertise" class="btn btn-default btn-sm">
                            <i class="fas fa-list"></i> Ver Listado
                        </a>
                        <a href="<?= APP_URL ?>expertise/show/<?= $expertise['id'] ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver Peritaje
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Pasos del wizard -->
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- Paso 1 -->
                            <div class="step completed" data-target="#step1">
                                <button type="button" class="step-trigger" role="tab" id="step1-trigger" aria-selected="false">
                                    <span class="bs-stepper-circle"><i class="fas fa-check"></i></span>
                                    <span class="bs-stepper-label">Información Básica</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <!-- Paso 2 -->
                            <div class="step active" data-target="#step2">
                                <button type="button" class="step-trigger" role="tab" id="step2-trigger" aria-selected="true">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Datos del Vehículo</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <!-- Paso 3 -->
                            <div class="step" data-target="#step3">
                                <button type="button" class="step-trigger" role="tab" id="step3-trigger" aria-selected="false">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label">Carrocería</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <!-- Resto de pasos (colapsados para mayor claridad) -->
                            <div class="step" data-target="#step4">
                                <button type="button" class="step-trigger" role="tab" id="step4-trigger">
                                    <span class="bs-stepper-circle">4</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <div class="step" data-target="#step5">
                                <button type="button" class="step-trigger" role="tab" id="step5-trigger">
                                    <span class="bs-stepper-circle">5</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <div class="step" data-target="#step6">
                                <button type="button" class="step-trigger" role="tab" id="step6-trigger">
                                    <span class="bs-stepper-circle">6</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <div class="step" data-target="#step7">
                                <button type="button" class="step-trigger" role="tab" id="step7-trigger">
                                    <span class="bs-stepper-circle">7</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <div class="step" data-target="#step8">
                                <button type="button" class="step-trigger" role="tab" id="step8-trigger">
                                    <span class="bs-stepper-circle">8</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <div class="step" data-target="#step9">
                                <button type="button" class="step-trigger" role="tab" id="step9-trigger">
                                    <span class="bs-stepper-circle">9</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <div class="step" data-target="#step10">
                                <button type="button" class="step-trigger" role="tab" id="step10-trigger">
                                    <span class="bs-stepper-circle">10</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            
                            <div class="step" data-target="#step11">
                                <button type="button" class="step-trigger" role="tab" id="step11-trigger">
                                    <span class="bs-stepper-circle">11</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Peritaje -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cliente</span>
                                    <span class="info-box-number"><?= $expertise['client_name'] ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Fecha de Servicio</span>
                                    <span class="info-box-number"><?= date('d/m/Y', strtotime($expertise['service_date'])) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hashtag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Número de Servicio</span>
                                    <span class="info-box-number"><?= $expertise['service_number'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form action="<?= APP_URL ?>expertise/<?= $expertise['id'] ?>/save-vehicle-data" method="post" class="mt-4">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Datos del Vehículo</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="plate">Placa <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="plate" name="plate" value="<?= $expertise['plate'] ?? '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="vehicle_type_id">Tipo de Vehículo <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="vehicle_type_id" name="vehicle_type_id" required>
                                                <option value="">Seleccione un tipo de vehículo</option>
                                                <?php foreach ($vehicleTypes as $type): ?>
                                                    <option value="<?= $type['id'] ?>" <?= ($expertise['vehicle_type_id'] ?? '') == $type['id'] ? 'selected' : '' ?>>
                                                        <?= $type['name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="vehicle_class">Clase</label>
                                            <input type="text" class="form-control" id="vehicle_class" name="vehicle_class" value="<?= $expertise['vehicle_class'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="brand">Marca</label>
                                            <input type="text" class="form-control" id="brand" name="brand" value="<?= $expertise['brand'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="line">Línea</label>
                                            <input type="text" class="form-control" id="line" name="line" value="<?= $expertise['line'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="model">Modelo</label>
                                            <input type="text" class="form-control" id="model" name="model" value="<?= $expertise['model'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="color">Color</label>
                                            <input type="text" class="form-control" id="color" name="color" value="<?= $expertise['color'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="service">Servicio</label>
                                            <input type="text" class="form-control" id="service" name="service" value="<?= $expertise['service'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="cylinder">Cilindraje</label>
                                            <input type="text" class="form-control" id="cylinder" name="cylinder" value="<?= $expertise['cylinder'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="body_type">Tipo de Carrocería</label>
                                            <input type="text" class="form-control" id="body_type" name="body_type" value="<?= $expertise['body_type'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chassis_number">Número de Chasis</label>
                                            <input type="text" class="form-control" id="chassis_number" name="chassis_number" value="<?= $expertise['chassis_number'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="motor_number">Número de Motor</label>
                                            <input type="text" class="form-control" id="motor_number" name="motor_number" value="<?= $expertise['motor_number'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="series_number">Número de Serie</label>
                                            <input type="text" class="form-control" id="series_number" name="series_number" value="<?= $expertise['series_number'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="transit_agency">Organismo de Tránsito</label>
                                            <input type="text" class="form-control" id="transit_agency" name="transit_agency" value="<?= $expertise['transit_agency'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mileage">Kilometraje</label>
                                            <input type="number" class="form-control" id="mileage" name="mileage" value="<?= $expertise['mileage'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Datos de Valor</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fasecolda_code">Código Fasecolda</label>
                                            <input type="text" class="form-control" id="fasecolda_code" name="fasecolda_code" value="<?= $expertise['fasecolda_code'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fasecolda_value">Valor Fasecolda</label>
                                            <input type="number" class="form-control" id="fasecolda_value" name="fasecolda_value" value="<?= $expertise['fasecolda_value'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="suggested_value">Valor Sugerido</label>
                                            <input type="number" class="form-control" id="suggested_value" name="suggested_value" value="<?= $expertise['suggested_value'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="accessories_value">Valor Accesorios</label>
                                            <input type="number" class="form-control" id="accessories_value" name="accessories_value" value="<?= $expertise['accessories_value'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar y Continuar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inicializar select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>
