<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-car mr-1"></i>
                        Nuevo Peritaje
                    </h3>
                    <div class="card-tools">
                        <a href="<?= APP_URL ?>expertise" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Pasos del wizard -->
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <div class="step active" data-target="#step1">
                                <button type="button" class="step-trigger" role="tab" id="step1-trigger" aria-selected="true">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Información Básica</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form action="<?= APP_URL ?>expertise/store" method="post" class="mt-4">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_id">Cliente <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="client_id" name="client_id" required>
                                        <option value="">Seleccione un cliente</option>
                                        <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['id'] ?>"><?= $client['name'] ?> - <?= $client['document'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_date">Fecha de Servicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="service_date" name="service_date" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_number">Número de Servicio <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="service_number" name="service_number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_for">Servicio Para <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="service_for" name="service_for" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_type_id">Tipo de Vehículo</label>
                                    <select class="form-control select2" id="vehicle_type_id" name="vehicle_type_id">
                                        <option value="">Seleccione un tipo de vehículo</option>
                                        <?php foreach ($vehicleTypes as $type): ?>
                                            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agreement">Convenio</label>
                                    <input type="text" class="form-control" id="agreement" name="agreement">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Complete la información básica para iniciar el peritaje. Luego podrá continuar con los demás pasos.
                            </div>
                        </div>
                        
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar e Iniciar Peritaje
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
