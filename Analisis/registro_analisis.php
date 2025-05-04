<?php
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol'])) {
    header("Location: ../Login/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id_usuario = $_SESSION['usuario_id'];
$id_rol = $_SESSION['id_rol'];
$redireccion = ($id_rol == 4) ? '../Admin/Analisis/formulario_analisis_admin.php' : 'formulario_analisis.php';

function mostrar_alerta($tipo, $titulo, $texto, $redireccion) {
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head><meta charset='UTF-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
    <body>
    <script>
        Swal.fire({
            icon: '$tipo',
            title: '$titulo',
            text: '$texto',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = '$redireccion';
        });
    </script>
    </body>
    </html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    mostrar_alerta('error', 'Acceso no autorizado', 'Solo se permite el método POST.', $redireccion);
}

$id_caso     = trim($_POST['id_caso'] ?? '');
$id_evidencia = trim($_POST['id_evidencia'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

if (empty($id_caso) || empty($id_evidencia) || empty($descripcion) || !isset($_FILES['archivo'])) {
    mostrar_alerta('error', 'Campos incompletos', 'Todos los campos son obligatorios.', $redireccion);
}

if (!ctype_digit($id_caso) || !ctype_digit($id_evidencia)) {
    mostrar_alerta('error', 'IDs inválidos', 'ID de caso y evidencia deben ser numéricos.', $redireccion);
}

// Verificar existencia del caso
$check_caso = $conn->prepare("SELECT id_caso FROM casos WHERE id_caso = ?");
$check_caso->bind_param("i", $id_caso);
$check_caso->execute();
$check_caso->store_result();
if ($check_caso->num_rows === 0) {
    mostrar_alerta('error', 'Caso no encontrado', 'El ID del caso no existe.', $redireccion);
}
$check_caso->close();

// Verificar existencia de la evidencia
$check_evidencia = $conn->prepare("SELECT id_evidencia FROM evidencias WHERE id_evidencia = ?");
$check_evidencia->bind_param("i", $id_evidencia);
$check_evidencia->execute();
$check_evidencia->store_result();
if ($check_evidencia->num_rows === 0) {
    mostrar_alerta('error', 'Evidencia no encontrada', 'El ID de la evidencia no existe.', $redireccion);
}
$check_evidencia->close();

// Subida del archivo
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$file = $_FILES['archivo'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    mostrar_alerta('error', 'Error de archivo', 'No se pudo subir el archivo.', $redireccion);
}

$originalName = basename($file['name']);
$extension = pathinfo($originalName, PATHINFO_EXTENSION);
$newFileName = uniqid('analisis_', true) . '.' . $extension;
$targetFilePath = $uploadDir . $newFileName;

if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    mostrar_alerta('error', 'Error al guardar', 'No se pudo guardar el archivo en el servidor.', $redireccion);
}

$tamano = filesize($targetFilePath);

// Registro en base de datos
$stmt = $conn->prepare("
    INSERT INTO analisis (id_usuario, id_evidencia, id_caso, descripcion, archivo, ruta_archivo, tamaño_archivo)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("iissssi", $id_usuario, $id_evidencia, $id_caso, $descripcion, $originalName, $targetFilePath, $tamano);

if ($stmt->execute()) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $h = $conn->prepare("INSERT INTO historial_accesos (id_usuario, accion, direccion_ip) VALUES (?, 'Registro Análisis', ?)");
    $h->bind_param("is", $id_usuario, $ip);
    $h->execute();

    mostrar_alerta('success', 'Éxito', 'Análisis registrado correctamente.', $redireccion);
} else {
    mostrar_alerta('error', 'Error en registro', $stmt->error, $redireccion);
}

$stmt->close();
$conn->close();
?>
