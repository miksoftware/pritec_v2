<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-car mr-1"></i>
                        Gestión de Peritajes
                    </h3>
                    <div class="card-tools">
                        <a href="<?= APP_URL ?>expertise/create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Peritaje
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="get" action="<?= APP_URL ?>expertise" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">Buscar:</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Placa, Cliente, etc." value="<?= $search ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Estado:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Todos</option>
                                        <option value="in_progress" <?= ($status ?? '') === 'in_progress' ? 'selected' : '' ?>>En Progreso</option>
                                        <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Completado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_from">Desde:</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?= $dateFrom ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_to">Hasta:</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?= $dateTo ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filtrar
                                        </button>
                                        <a href="<?= APP_URL ?>expertise" class="btn btn-default">
                                            <i class="fas fa-times"></i> Limpiar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Estadísticas -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $stats['total'] ?? 0 ?></h3>
                                    <p>Total Peritajes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-car"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $stats['in_progress'] ?? 0 ?></h3>
                                    <p>En Progreso</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $stats['completed'] ?? 0 ?></h3>
                                    <p>Completados</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3><?= $stats['this_month'] ?? 0 ?></h3>
                                    <p>Este Mes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de peritajes -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Placa</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Estado</th>
                                    <th>Progreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($expertise) > 0): ?>
                                    <?php foreach ($expertise as $exp): ?>
                                        <tr>
                                            <td><?= $exp['id'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($exp['service_date'])) ?></td>
                                            <td><?= $exp['plate'] ?? 'N/A' ?></td>
                                            <td><?= $exp['client_name'] ?? 'N/A' ?></td>
                                            <td>
                                                <?= $exp['vehicle_type_name'] ?? 'N/A' ?>
                                                <?php if (!empty($exp['brand']) && !empty($exp['line'])): ?>
                                                    <br><small><?= $exp['brand'] ?> <?= $exp['line'] ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($exp['status'] === 'in_progress'): ?>
                                                    <span class="badge badge-warning">En Progreso</span>
                                                <?php elseif ($exp['status'] === 'completed'): ?>
                                                    <span class="badge badge-success">Completado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $progress = ($exp['current_step'] / 11) * 100;
                                                    $progressClass = $progress < 50 ? 'bg-warning' : ($progress < 100 ? 'bg-info' : 'bg-success');
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar <?= $progressClass ?>" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <?= round($progress) ?>%
                                                    </div>
                                                </div>
                                                <small>Paso <?= $exp['current_step'] ?>/11</small>
                                            </td>
                                            <td>
                                                <?php if ($exp['status'] === 'in_progress'): ?>
                                                    <a href="<?= APP_URL ?>expertise/<?= $exp['id'] ?>/step/<?= $exp['current_step'] ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-edit"></i> Continuar
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <a href="<?= APP_URL ?>expertise/show/<?= $exp['id'] ?>" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                
                                                <a href="<?= APP_URL ?>expertise/edit/<?= $exp['id'] ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-pencil-alt"></i> Editar
                                                </a>
                                                
                                                <a href="<?= APP_URL ?>expertise/pdf/<?= $exp['id'] ?>" class="btn btn-default btn-sm" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                                
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $exp['id'] ?>)">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No se encontraron registros</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <?php if ($totalPages > 1): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <ul class="pagination">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= APP_URL ?>expertise?page=<?= $currentPage - 1 ?>&search=<?= $search ?>&status=<?= $status ?>&date_from=<?= $dateFrom ?>&date_to=<?= $dateTo ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= APP_URL ?>expertise?page=<?= $i ?>&search=<?= $search ?>&status=<?= $status ?>&date_from=<?= $dateFrom ?>&date_to=<?= $dateTo ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= APP_URL ?>expertise?page=<?= $currentPage + 1 ?>&search=<?= $search ?>&status=<?= $status ?>&date_from=<?= $dateFrom ?>&date_to=<?= $dateTo ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar este peritaje? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="deleteForm" action="" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        document.getElementById('deleteForm').action = '<?= APP_URL ?>expertise/delete/' + id;
        $('#deleteModal').modal('show');
    }
    
    // Mostrar mensaje de éxito si viene de guardar un peritaje
    <?php if (isset($_GET['saved']) && $_GET['saved'] === 'true' && isset($_SESSION['expertise_id'])): ?>
        $(document).ready(function() {
            Swal.fire({
                title: 'Peritaje completado',
                text: 'El peritaje #<?= $_SESSION['expertise_id'] ?> ha sido completado exitosamente',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['expertise_id']); ?>
        });
    <?php endif; ?>
</script>
