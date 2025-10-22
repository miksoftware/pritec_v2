<?php ob_start(); ?>

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderIndexView')) {
    require_once APP_PATH . '/helpers/autoload.php';
}
?>

<!-- Sección de Peritajes en Progreso -->
<?php if (!empty($inProgress)): ?>
<div class="container-fluid mb-4">
    <div class="alert alert-warning border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-hourglass-half me-2"></i>
            <strong>Tienes <?= count($inProgress) ?> peritaje<?= count($inProgress) > 1 ? 's' : '' ?> en progreso</strong>
        </div>
        <small class="text-muted">Continúa donde lo dejaste para finalizarlos</small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th width="100">Paso</th>
                    <th>Vehículo</th>
                    <th>Cliente</th>
                    <th width="120" class="text-center">Progreso</th>
                    <th width="100" class="text-center">Datos</th>
                    <th width="200">Última Actualización</th>
                    <th width="200" class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inProgress as $peritaje): ?>
                <tr class="align-middle">
                    <!-- Paso actual -->
                    <td>
                        <span class="badge bg-warning text-dark fs-6">
                            <?= $peritaje['current_step'] ?>/12
                        </span>
                    </td>

                    <!-- Información del vehículo -->
                    <td>
                        <div>
                            <strong class="text-dark"><?= htmlspecialchars($peritaje['placa'] ?: 'Sin placa') ?></strong>
                            <?php if ($peritaje['marca']): ?>
                            <br>
                            <small class="text-muted">
                                <?= htmlspecialchars($peritaje['marca']) ?> 
                                <?= htmlspecialchars($peritaje['linea'] ?? '') ?>
                                <?= htmlspecialchars($peritaje['modelo'] ?? '') ?>
                            </small>
                            <?php endif; ?>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-hashtag"></i> <?= htmlspecialchars($peritaje['service_number']) ?>
                            </small>
                        </div>
                    </td>

                    <!-- Cliente -->
                    <td>
                        <?php if ($peritaje['cliente_nombre']): ?>
                        <small>
                            <i class="fas fa-user text-muted me-1"></i>
                            <?= htmlspecialchars($peritaje['cliente_nombre'] . ' ' . $peritaje['cliente_apellido']) ?>
                        </small>
                        <?php else: ?>
                        <small class="text-muted">—</small>
                        <?php endif; ?>
                    </td>

                    <!-- Progreso -->
                    <td class="text-center">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-warning" 
                                 role="progressbar" 
                                 style="width: <?= ($peritaje['current_step'] / 12) * 100 ?>%" 
                                 aria-valuenow="<?= $peritaje['current_step'] ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="12">
                                <small class="fw-bold"><?= round(($peritaje['current_step'] / 12) * 100) ?>%</small>
                            </div>
                        </div>
                    </td>

                    <!-- Datos (Inspecciones y Fotos) -->
                    <td class="text-center">
                        <small class="d-block">
                            <i class="fas fa-search text-info"></i> <?= $peritaje['total_inspecciones'] ?>
                        </small>
                        <small class="d-block">
                            <i class="fas fa-camera text-warning"></i> <?= $peritaje['total_fotos'] ?>
                        </small>
                    </td>

                    <!-- Última actualización -->
                    <td>
                        <small class="text-muted">
                            <?= date('d/m/Y H:i', strtotime($peritaje['updated_at'])) ?>
                        </small>
                    </td>

                    <!-- Acciones -->
                    <td class="text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="<?= APP_URL ?>expertise/step12?id=<?= $peritaje['id'] ?>" 
                               class="btn btn-warning"
                               title="Ver resumen completo">
                                <i class="fas fa-clipboard-list"></i>
                            </a>
                            <a href="<?= APP_URL ?>expertise/edit/<?= $peritaje['id'] ?>/<?= $peritaje['current_step'] ?>" 
                               class="btn btn-outline-secondary"
                               title="Continuar en paso <?= $peritaje['current_step'] ?>">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php
renderIndexView([
    'title' => 'Peritajes Completos',
    'subtitle' => 'Gestión y consulta de peritajes finalizados',
    'icon' => 'fas fa-clipboard-check',
    'module_name' => 'peritajes_completos',
    'show_stats' => true,
    'stats' => [
        [
            'title' => 'Completados',
            'value' => count($expertises),
            'icon' => 'fas fa-check-circle',
            'color' => 'success'
        ],
        [
            'title' => 'En Progreso',
            'value' => count($inProgress ?? []),
            'icon' => 'fas fa-hourglass-half',
            'color' => 'warning'
        ],
        [
            'title' => 'Total Inspecciones',
            'value' => number_format(array_sum(array_column($expertises, 'total_inspecciones'))),
            'icon' => 'fas fa-search',
            'color' => 'info'
        ],
        [
            'title' => 'Total Fotos',
            'value' => number_format(array_sum(array_column($expertises, 'total_fotos'))),
            'icon' => 'fas fa-camera',
            'color' => 'primary'
        ]
    ],
    'show_filters' => true,
    'data' => $expertises ?? [],
    'columns' => [
        [
            'title' => 'ID',
            'field' => 'id',
            'type' => 'id'
        ],
        [
            'title' => 'Fecha',
            'field' => 'service_date',
            'type' => 'date',
            'format' => 'd/m/Y'
        ],
        [
            'title' => 'Servicio #',
            'field' => 'service_number',
            'type' => 'badge',
            'badge_class' => 'bg-secondary'
        ],
        [
            'title' => 'Vehículo',
            'field' => 'vehiculo',
            'type' => 'custom',
            'render' => function($row) {
                return '
                    <div>
                        <strong class="text-dark">' . htmlspecialchars($row['placa']) . '</strong>
                        <br>
                        <small class="text-muted">
                            ' . htmlspecialchars($row['marca'] ?? 'N/A') . ' 
                            ' . htmlspecialchars($row['linea'] ?? '') . '
                            ' . htmlspecialchars($row['modelo'] ?? '') . '
                        </small>
                        <br>
                        <small class="badge bg-light text-dark">
                            ' . htmlspecialchars($row['tipo_vehiculo_nombre'] ?? 'N/A') . '
                        </small>
                    </div>
                ';
            }
        ],
        [
            'title' => 'Cliente',
            'field' => 'cliente',
            'type' => 'custom',
            'render' => function($row) {
                $html = '<div><i class="fas fa-user text-muted me-1"></i>';
                $html .= htmlspecialchars($row['cliente_nombre'] . ' ' . $row['cliente_apellido']);
                $html .= '<br>';
                if ($row['cliente_telefono']) {
                    $html .= '<small class="text-muted">';
                    $html .= '<i class="fas fa-phone me-1"></i>';
                    $html .= htmlspecialchars($row['cliente_telefono']);
                    $html .= '</small>';
                }
                $html .= '</div>';
                return $html;
            }
        ],
        [
            'title' => 'Inspecciones',
            'field' => 'total_inspecciones',
            'type' => 'badge',
            'badge_class' => 'bg-info',
            'align' => 'center'
        ],
        [
            'title' => 'Fotos',
            'field' => 'total_fotos',
            'type' => 'badge',
            'badge_class' => 'bg-warning text-dark',
            'align' => 'center'
        ],
        [
            'title' => 'Acciones',
            'field' => 'actions',
            'type' => 'actions',
            'buttons' => [
                [
                    'url' => APP_URL . 'expertise/show/{id}',
                    'class' => 'btn-outline-info',
                    'icon' => 'fas fa-eye',
                    'title' => 'Ver detalles'
                ],
                [
                    'url' => APP_URL . 'expertise/pdf/{id}',
                    'class' => 'btn-outline-danger',
                    'icon' => 'fas fa-file-pdf',
                    'title' => 'Generar PDF',
                    'target' => '_blank'
                ],
                [
                    'url' => APP_URL . 'expertise/edit/{id}',
                    'class' => 'btn-outline-warning',
                    'icon' => 'fas fa-edit',
                    'title' => 'Editar'
                ],
                [
                    'class' => 'btn-outline-danger',
                    'icon' => 'fas fa-trash',
                    'title' => 'Eliminar',
                    'onclick' => 'deleteExpertise({id})'
                ]
            ]
        ]
    ],
    'actions' => [
        createHeaderAction('Nuevo Peritaje Completo', APP_URL . 'expertise/create', [
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary'
        ])
    ],
    'empty_message' => 'No hay peritajes completos registrados',
    'empty_description' => 'Comienza creando tu primer peritaje completo',
    'create_url' => APP_URL . 'expertise/create',
    'create_text' => 'Crear Primer Peritaje',
    'table_id' => 'expertisesTable',
    'pagination' => $pagination ?? null,
    'custom_scripts' => '
    <script>
    // Función para eliminar peritaje
    function deleteExpertise(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            html: `
                <p>¿Deseas eliminar este peritaje completo?</p>
                <p class="text-danger">
                    <strong>Esta acción no se puede deshacer.</strong><br>
                    Se eliminarán también todas las inspecciones y fotos asociadas.
                </p>
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`' . APP_URL . 'expertise/delete/${id}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        csrf_token: "' . ($csrf_token ?? '') . '"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "¡Eliminado!",
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Error al eliminar el peritaje"
                    });
                });
            }
        });
    }
    </script>
    '
]);
?>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
