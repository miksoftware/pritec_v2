<?php ob_start(); ?>

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderIndexView')) {
    require_once APP_PATH . '/helpers/autoload.php';
}

// Configurar la vista index usando la plantilla estándar
renderIndexView([
    'title' => 'Gestión de Usuarios',
    'subtitle' => 'Administra todos los usuarios del sistema',
    'icon' => 'fas fa-users',
    'module_name' => 'usuarios',
    'show_stats' => true,
    'stats' => [
        [
            'title' => 'Total Usuarios',
            'value' => $totalRecords ?? 0,
            'icon' => 'fas fa-users',
            'color' => 'primary'
        ]
    ],
    'show_filters' => true,
    'filters' => [
        [
            'type' => 'search',
            'name' => 'search',
            'placeholder' => 'Buscar por usuario, email o nombre...',
            'value' => $search ?? '',
            'width' => '12'
        ]
    ],
    'pagination' => $pagination ?? null,
    'data' => $users ?? [],
    'columns' => [
        [
            'title' => 'ID',
            'field' => 'id',
            'type' => 'id'
        ],
        [
            'title' => 'Usuario',
            'field' => 'username',
            'type' => 'user',
            'subtitle_field' => 'full_name'
        ],
        [
            'title' => 'Email',
            'field' => 'email',
            'type' => 'text'
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
            'title' => 'Fecha Registro',
            'field' => 'created_at',
            'type' => 'date'
        ],
        [
            'title' => 'Último Acceso',
            'field' => 'last_login',
            'type' => 'datetime'
        ],
        [
            'title' => 'Acciones',
            'field' => 'actions',
            'type' => 'actions',
            'buttons' => [
                [
                    'url' => APP_URL . 'users/edit/{id}',
                    'class' => 'btn-outline-primary',
                    'icon' => 'fas fa-edit',
                    'title' => 'Editar'
                ],
                [
                    'class' => 'btn-outline-warning',
                    'icon' => 'fas fa-ban',
                    'title' => 'Cambiar Estado',
                    'onclick' => 'toggleUserStatus({id}, \'{status}\')',
                    'condition' => 'not_current_user'
                ],
                [
                    'class' => 'btn-outline-danger',
                    'icon' => 'fas fa-trash',
                    'title' => 'Eliminar',
                    'onclick' => 'deleteUser({id}, \'{username}\')',
                    'condition' => 'not_current_user'
                ]
            ]
        ]
    ],
    'actions' => [
        createHeaderAction('Nuevo Usuario', APP_URL . 'users/create', [
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary'
        ])
    ],
    'empty_message' => 'No hay usuarios registrados',
    'empty_description' => 'Comienza creando el primer usuario del sistema',
    'create_url' => APP_URL . 'users/create',
    'create_text' => 'Crear Primer Usuario',
    'table_id' => 'usersTable',
    'custom_scripts' => '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Animación de entrada para las filas
        const tableRows = document.querySelectorAll("#usersTable tbody tr");
        tableRows.forEach((row, index) => {
            setTimeout(() => {
                row.style.opacity = "0";
                row.style.transform = "translateX(-20px)";
                row.style.transition = "all 0.3s ease";
                
                setTimeout(() => {
                    row.style.opacity = "1";
                    row.style.transform = "translateX(0)";
                }, 50);
            }, index * 50);
        });
    });

    // Función para cambiar estado del usuario
    function toggleUserStatus(userId, currentStatus) {
        const newStatus = currentStatus === "active" ? "inactive" : "active";
        const action = newStatus === "active" ? "activar" : "inactivar";
        
        Swal.fire({
            title: "¿Confirmar acción?",
            text: `¿Estás seguro de que quieres ${action} este usuario?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: newStatus === "active" ? "#28a745" : "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Sí, ${action}`,
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`' . APP_URL . 'users/toggle-status/${userId}`, {
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
                        text: "Error al cambiar el estado del usuario"
                    });
                });
            }
        });
    }

    // Función para eliminar usuario
    function deleteUser(userId, username) {
        Swal.fire({
            title: "¿Eliminar usuario?",
            text: `¿Estás seguro de que quieres eliminar al usuario "${username}"? Esta acción no se puede deshacer.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`' . APP_URL . 'users/delete/${userId}`, {
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
                        text: "Error al eliminar el usuario"
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
