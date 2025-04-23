<?php
session_start();

// 1) Validar sesión
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol'])) {
    header("Location: ../Login/login.php");
    exit();
}

// 2) Conexión
$conn = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 3) Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Acceso no autorizado.";
    exit();
}

// Campos del form
$nombre_caso = trim($_POST['nombre_caso'] ?? '');
$descripcion  = trim($_POST['descripcion'] ?? '');
$estado       = trim($_POST['estado'] ?? 'abierto');  // ya viene oculto "abierto"

// VALIDACIÓN
if ($nombre_caso === '' || $estado === '') {
    echo "Los campos Nombre del Caso y Estado son requeridos.";
    exit;
}

// Insert en casos
$stmt = $conn->prepare(
    "INSERT INTO casos (nombre_caso, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)"
);
$id_usuario = $_SESSION['usuario_id'];
$stmt->bind_param("sssi", $nombre_caso, $descripcion, $estado, $id_usuario);

if ($stmt->execute()) {
    // INSERT en historial_accesos
    $ip = $_SERVER['REMOTE_ADDR'];
    $h = $conn->prepare("
        INSERT INTO historial_accesos 
            (id_usuario, id_evidencia, accion, direccion_ip)
        VALUES (?, NULL, 'Subida Caso', ?)
    ");
    $h->bind_param("is", $id_usuario, $ip);
    if (!$h->execute()) {
        error_log("Error al insertar historial: " . $h->error);
    }
    echo "Caso registrado exitosamente.";
} else {
    echo "Error en el registro del caso: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
