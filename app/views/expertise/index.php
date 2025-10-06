<?php ob_start(); ?>

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderIndexView')) {
    require_once APP_PATH . '/helpers/autoload.php';
}

// Configurar la vista index usando la plantilla estándar
renderIndexView([
    'title' => 'Peritajes Completos',
    'subtitle' => 'Gestión y consulta de peritajes completos realizados',
    'icon' => 'fas fa-clipboard-check',
    'module_name' => 'peritajes_completos',
    'show_stats' => true,
    'stats' => [
        [
            'title' => 'Total Peritajes',
            'value' => count($expertises),
            'icon' => 'fas fa-clipboard-check',
            'color' => 'primary'
        ],
        [
            'title' => 'Este Mes',
            'value' => count(array_filter($expertises, function($e) {
                return strpos($e['created_at'], date('Y-m')) === 0;
            })),
            'icon' => 'fas fa-calendar',
            'color' => 'success'
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
            'color' => 'warning'
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
                    'url' => APP_URL . 'expertise/view/{id}',
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
