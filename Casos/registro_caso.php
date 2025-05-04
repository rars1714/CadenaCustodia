<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol'])) {
    header("Location: ../Login/login.php");
    exit();
}

// Conexión
$conn = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conn->connect_error) {
    mostrar_alerta('error', 'Error de conexión', $conn->connect_error);
}

// Obtener variables de sesión
$id_usuario = $_SESSION['usuario_id'];
$id_rol = $_SESSION['id_rol'];

// Redirección según rol
$redireccion = ($id_rol == 4) ? '../Admin/Casos/agregar_caso_admin.php' : 'agregar_caso.php';

// Función de SweetAlert2
function mostrar_alerta($tipo, $titulo, $mensaje) {
    global $redireccion;
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$tipo',
                title: '$titulo',
                text: '$mensaje',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = '$redireccion';
            });
        </script>
    </body>
    </html>
    ";
    exit;
}

// Validar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    mostrar_alerta('error', 'Acceso no autorizado', 'Este archivo solo acepta solicitudes POST.');
}

// Validar datos
$nombre_caso = trim($_POST['nombre_caso'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$estado = 'abierto';

if (empty($nombre_caso) || empty($descripcion)) {
    mostrar_alerta('error', 'Campos incompletos', 'Nombre del caso y descripción son requeridos.');
}

// Insertar caso
$stmt = $conn->prepare("INSERT INTO casos (nombre_caso, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nombre_caso, $descripcion, $estado, $id_usuario);

if ($stmt->execute()) {
    // Historial
    $ip = $_SERVER['REMOTE_ADDR'];
    $h = $conn->prepare("INSERT INTO historial_accesos (id_usuario, accion, direccion_ip) VALUES (?, 'Registro Caso', ?)");
    $h->bind_param("is", $id_usuario, $ip);
    $h->execute();

    mostrar_alerta('success', 'Registro exitoso', 'El caso ha sido registrado correctamente.');
} else {
    mostrar_alerta('error', 'Error en registro', $stmt->error);
}

$stmt->close();
$conn->close();
?>
