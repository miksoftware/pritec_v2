<?php ob_start(); ?>

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderIndexView')) {
    require_once APP_PATH . '/helpers/autoload.php';
}

// Configurar la vista index usando la plantilla estándar
renderIndexView([
    'title' => 'Gestión de Clientes',
    'subtitle' => 'Administra la información de todos los clientes',
    'icon' => 'fas fa-users',
    'module_name' => 'clientes',
    'show_stats' => true,
    'show_filters' => true,
    'stats' => [
        [
            'title' => 'Total Clientes',
            'value' => $stats['total'] ?? 0,
            'color' => 'primary',
            'icon' => 'fas fa-users'
        ],
        [
            'title' => 'Clientes Activos',
            'value' => $stats['active'] ?? 0,
            'color' => 'success',
            'icon' => 'fas fa-user-check'
        ],
        [
            'title' => 'Nuevos Este Mes',
            'value' => $stats['this_month'] ?? 0,
            'color' => 'info',
            'icon' => 'fas fa-calendar-plus'
        ],
        [
            'title' => 'Nuevos Hoy',
            'value' => $stats['today'] ?? 0,
            'color' => 'warning',
            'icon' => 'fas fa-user-plus'
        ]
    ],
    'filters' => [
        [
            'name' => 'search',
            'label' => 'Buscar',
            'type' => 'text',
            'placeholder' => 'Nombre, identificación, email...',
            'width' => '6'
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'width' => '3',
            'options' => [
                'active' => 'Activo',
                'inactive' => 'Inactivo'
            ]
        ]
    ],
    'data' => $clients ?? [],
    'columns' => [
        [
            'title' => 'ID',
            'field' => 'id',
            'type' => 'id'
        ],
        [
            'title' => 'Cliente',
            'field' => 'first_name',
            'type' => 'user',
            'subtitle_field' => 'last_name',
            'email_field' => 'email'
        ],
        [
            'title' => 'Identificación',
            'field' => 'identification',
            'type' => 'text'
        ],
        [
            'title' => 'Contacto',
            'field' => 'phone',
            'type' => 'contact',
            'address_field' => 'address'
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
            'title' => 'Acciones',
            'field' => 'actions',
            'type' => 'actions',
            'buttons' => [
                [
                    'url' => APP_URL . 'clients/show/{id}',
                    'class' => 'btn-outline-info',
                    'icon' => 'fas fa-eye',
                    'title' => 'Ver Detalles'
                ],
                [
                    'url' => APP_URL . 'clients/edit/{id}',
                    'class' => 'btn-outline-primary',
                    'icon' => 'fas fa-edit',
                    'title' => 'Editar'
                ],
                [
                    'class' => 'btn-outline-warning',
                    'icon' => 'fas fa-user-slash',
                    'title' => 'Cambiar Estado',
                    'onclick' => 'toggleClientStatus({id}, \'{status}\')',
                    'condition' => 'status_toggle'
                ],
                [
                    'class' => 'btn-outline-danger',
                    'icon' => 'fas fa-trash',
                    'title' => 'Eliminar',
                    'onclick' => 'deleteClient({id}, \'{first_name} {last_name}\')'
                ]
            ]
        ]
    ],
    'actions' => [
        createHeaderAction('Exportar CSV', APP_URL . 'clients/export' . (!empty($search) || !empty($status) ? '?' . http_build_query(['search' => $search ?? '', 'status' => $status ?? '']) : ''), [
            'icon' => 'fas fa-download',
            'class' => 'btn-outline-success'
        ]),
        createHeaderAction('Nuevo Cliente', APP_URL . 'clients/create', [
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary'
        ])
    ],
    'empty_message' => 'No hay clientes registrados',
    'empty_description' => 'Comienza registrando el primer cliente',
    'create_url' => APP_URL . 'clients/create',
    'create_text' => 'Registrar Primer Cliente',
    'table_id' => 'clientsTable',
    'pagination' => $pagination ?? null,
    'custom_scripts' => "
    <script>
    // Esperar a que el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar que SweetAlert esté disponible
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert no está disponible. Asegúrate de incluir la librería.');
            return;
        }
        
        console.log('SweetAlert disponible, funciones de cliente listas');
    });

    // Función para alternar estado del cliente
    function toggleClientStatus(clientId, currentStatus) {
        console.log('toggleClientStatus called:', clientId, currentStatus);
        
        if (typeof Swal === 'undefined') {
            alert('Sistema no disponible. Recarga la página.');
            return;
        }
        
        const action = currentStatus === 'active' ? 'deactivate' : 'activate';
        const title = action === 'activate' ? '¿Activar cliente?' : '¿Desactivar cliente?';
        const text = action === 'activate' ? 'El cliente podrá ser utilizado normalmente.' : 'El cliente será marcado como inactivo.';
        const confirmText = action === 'activate' ? 'Sí, activar' : 'Sí, desactivar';
        
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'activate' ? '#28a745' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('" . CSRF_TOKEN_NAME . "', '" . ($csrf_token ?? '') . "');
                
                const url = action === 'activate' 
                    ? '" . APP_URL . "clients/activate/' + clientId
                    : '" . APP_URL . "clients/deactivate/' + clientId;
                
                console.log('Sending request to:', url);
                
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error desconocido'
                        });
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: 'No se pudo conectar con el servidor'
                    });
                });
            }
        });
    }

    // Función para eliminar cliente
    function deleteClient(clientId, clientName) {
        console.log('deleteClient called:', clientId, clientName);
        
        if (typeof Swal === 'undefined') {
            if (confirm('¿Eliminar cliente ' + clientName + '?')) {
                // Fallback si SweetAlert no está disponible
                window.location.href = '" . APP_URL . "clients/destroy/' + clientId;
            }
            return;
        }
        
        Swal.fire({
            title: '¿Eliminar cliente?',
            text: 'El cliente será eliminado y no aparecerá en la lista principal. Esta acción se puede revertir desde el área de administración.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('" . CSRF_TOKEN_NAME . "', '" . ($csrf_token ?? '') . "');
                
                const url = '" . APP_URL . "clients/destroy/' + clientId;
                console.log('Sending delete request to:', url);
                
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Delete response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response data:', data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error desconocido'
                        });
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: 'No se pudo conectar con el servidor'
                    });
                });
            }
        });
    }
    </script>
    "
]);
?>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
