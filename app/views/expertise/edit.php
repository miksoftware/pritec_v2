<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i>
                        Editar Peritaje #<?= $expertise['id'] ?>
                    </h3>
                    <div class="card-tools">
                        <a href="<?= APP_URL ?>expertise/show/<?= $expertise['id'] ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Formulario -->
                    <form action="<?= APP_URL ?>expertise/update/<?= $expertise['id'] ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <!-- Pestañas -->
                        <ul class="nav nav-tabs" id="expertiseTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Información General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab" aria-controls="vehicle" aria-selected="false">Datos del Vehículo</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="expertiseTabsContent">
                            <!-- Pestaña de Información General -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client_id">Cliente <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="client_id" name="client_id" required>
                                                <option value="">Seleccione un cliente</option>
                                                <?php foreach ($clients as $client): ?>
                                                    <option value="<?= $client['id'] ?>" <?= $client['id'] == $expertise['client_id'] ? 'selected' : '' ?>>
                                                        <?= $client['name'] ?> - <?= $client['document'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_date">Fecha de Servicio <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="service_date" name="service_date" value="<?= $expertise['service_date'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_number">Número de Servicio <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="service_number" name="service_number" value="<?= $expertise['service_number'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_for">Servicio Para <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="service_for" name="service_for" value="<?= $expertise['service_for'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="agreement">Convenio</label>
                                            <input type="text" class="form-control" id="agreement" name="agreement" value="<?= $expertise['agreement'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pestaña de Datos del Vehículo -->
                            <div class="tab-pane fade" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">
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
                                                    <option value="<?= $type['id'] ?>" <?= $type['id'] == $expertise['vehicle_type_id'] ? 'selected' : '' ?>>
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
                        
                        <div class="form-group mt-4 text-right">
                            <a href="<?= APP_URL ?>expertise/show/<?= $expertise['id'] ?>" class="btn btn-default">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
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
