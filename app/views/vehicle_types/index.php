<?php ob_start(); ?>

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderIndexView')) {
    require_once APP_PATH . '/helpers/autoload.php';
}

// Configurar la vista index usando la plantilla estándar
renderIndexView([
    'title' => 'Tipos de Vehículos',
    'subtitle' => 'Gestiona los diferentes tipos de vehículos del sistema',
    'icon' => 'fas fa-car',
    'module_name' => 'tipos_vehiculos',
    'show_stats' => true,
    'stats' => [
        [
            'title' => 'Carros',
            'value' => $statistics['carros'] ?? 0,
            'icon' => 'fas fa-car',
            'color' => 'info'
        ],
        [
            'title' => 'Motos',
            'value' => $statistics['motos'] ?? 0,
            'icon' => 'fas fa-motorcycle',
            'color' => 'warning'
        ],
        [
            'title' => 'Activos',
            'value' => $statistics['activos'] ?? 0,
            'icon' => 'fas fa-check-circle',
            'color' => 'success'
        ],
        [
            'title' => 'Inactivos',
            'value' => $statistics['inactivos'] ?? 0,
            'icon' => 'fas fa-times-circle',
            'color' => 'danger'
        ]
    ],
    'show_filters' => true,
    'filters' => [
        [
            'type' => 'search',
            'name' => 'search',
            'placeholder' => 'Buscar por nombre o descripción...',
            'value' => $search ?? '',
            'width' => '12'
        ]
    ],
    'pagination' => $pagination ?? null,
    'data' => $vehicleTypes ?? [],
    'columns' => [
        [
            'title' => 'ID',
            'field' => 'id',
            'type' => 'id'
        ],
        [
            'title' => 'Tipo',
            'field' => 'type',
            'type' => 'vehicle_type'
        ],
        [
            'title' => 'Nombre',
            'field' => 'name',
            'type' => 'text'
        ],
        [
            'title' => 'Descripción',
            'field' => 'description',
            'type' => 'description',
            'max_length' => 50
        ],
        [
            'title' => 'Estado',
            'field' => 'status',
            'type' => 'status',
            'status_map' => [
                'active' => ['class' => 'success', 'text' => 'Activo'],
                'inactive' => ['class' => 'danger', 'text' => 'Inactivo']
            ]
        ],
        [
            'title' => 'Fecha Creación',
            'field' => 'created_at',
            'type' => 'datetime'
        ],
        [
            'title' => 'Acciones',
            'field' => 'actions',
            'type' => 'actions',
            'buttons' => [
                [
                    'url' => APP_URL . 'vehicle-types/{id}/sections',
                    'class' => 'btn-outline-info',
                    'icon' => 'fas fa-cogs',
                    'title' => 'Configurar Secciones'
                ],
                [
                    'url' => APP_URL . 'vehicle-types/{id}/edit',
                    'class' => 'btn-outline-primary',
                    'icon' => 'fas fa-edit',
                    'title' => 'Editar'
                ],
                [
                    'class' => 'btn-outline-warning',
                    'icon' => 'fas fa-toggle-off',
                    'title' => 'Cambiar Estado',
                    'onclick' => 'toggleStatus({id}, \'{status}\')',
                    'condition' => 'status_toggle'
                ],
                [
                    'class' => 'btn-outline-danger',
                    'icon' => 'fas fa-trash',
                    'title' => 'Eliminar',
                    'onclick' => 'deleteVehicleType({id}, \'{name}\')'
                ]
            ]
        ]
    ],
    'actions' => [
        createHeaderAction('Nuevo Tipo de Vehículo', APP_URL . 'vehicle-types/create', [
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary'
        ])
    ],
    'empty_message' => 'No hay tipos de vehículos registrados',
    'empty_description' => 'Comienza creando tu primer tipo de vehículo',
    'create_url' => APP_URL . 'vehicle-types/create',
    'create_text' => 'Crear Primer Tipo',
    'table_id' => 'vehicleTypesTable',
    'pagination' => null,
    'custom_scripts' => '
    <script>
    // Función para cambiar estado
    function toggleStatus(vehicleTypeId, currentStatus) {
        const action = currentStatus === "active" ? "desactivar" : "activar";
        
        Swal.fire({
            title: "¿Estás seguro?",
            text: `¿Deseas ${action} este tipo de vehículo?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: currentStatus === "active" ? "#dc3545" : "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Sí, ${action}`,
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`' . APP_URL . 'vehicle-types/toggle-status/${vehicleTypeId}`, {
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
                            title: "¡Éxito!",
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
                        text: "Error al cambiar el estado"
                    });
                });
            }
        });
    }

    // Función para eliminar tipo de vehículo
    function deleteVehicleType(vehicleTypeId, vehicleTypeName) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: `¿Deseas eliminar el tipo de vehículo "${vehicleTypeName}"? Esta acción no se puede deshacer.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`' . APP_URL . 'vehicle-types/delete/${vehicleTypeId}`, {
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
                        text: "Error al eliminar el tipo de vehículo"
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
