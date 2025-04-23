<?php
session_start();

// 1) Verificar sesión
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol'])) {
    header("Location: ../Login/login.php");
    exit();
}

// 2) Conexión
$conn = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 3) Solo vía POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Acceso no autorizado.";
    exit();
}

// 4) Recoger datos del formulario
$id_caso         = trim($_POST['id_caso'] ?? '');
$tipo_evidencia  = strtolower(trim($_POST['tipo_evidencia'] ?? ''));
$descripcion     = trim($_POST['Descripcion'] ?? '');

// 5) Validar campos
if ($id_caso === '' || $tipo_evidencia === '' || !isset($_FILES['archivo'])) {
    echo "Todos los campos son requeridos y debes adjuntar un archivo.";
    exit;
}

// 6) Validar tipo
$tipos_permitidos = ['pdf','imagen','video','audio','otro'];
if (!in_array($tipo_evidencia, $tipos_permitidos)) {
    echo "El tipo de evidencia no es válido.";
    exit;
}

// 7) Procesar carga de archivo
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$file = $_FILES['archivo'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo "Error en la carga del archivo.";
    exit;
}
$originalName   = basename($file['name']);
$extension      = pathinfo($originalName, PATHINFO_EXTENSION);
$newFileName    = uniqid('evid_', true) . '.' . $extension;
$targetFilePath = $uploadDir . $newFileName;
if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    echo "Error al guardar el archivo.";
    exit;
}

// 8) Hash y tamaño
$hash_sha3     = hash_file('sha3-256', $targetFilePath);
$tamano_archivo = filesize($targetFilePath);

// 9) Insert en `evidencias`
$stmt = $conn->prepare("
    INSERT INTO evidencias 
      (id_caso, id_usuario, tipo_evidencia, nombre_archivo, ruta_archivo, hash_sha3, tamano_archivo)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$id_usuario = $_SESSION['usuario_id'];
$stmt->bind_param(
    "iissssi",
    $id_caso,
    $id_usuario,
    $tipo_evidencia,
    $originalName,
    $targetFilePath,
    $hash_sha3,
    $tamano_archivo
);

if ($stmt->execute()) {
    // 10) Registrar en `historial_accesos`
    $evidencia_id = $stmt->insert_id;
    $ip           = $_SERVER['REMOTE_ADDR'];
    $h = $conn->prepare("
        INSERT INTO historial_accesos 
          (id_usuario, id_evidencia, accion, direccion_ip)
        VALUES (?, ?, 'Subida Evidencia', ?)
    ");
    $h->bind_param("iis", $id_usuario, $evidencia_id, $ip);
    if (!$h->execute()) {
        error_log("Error al insertar historial de accesos: " . $h->error);
    }

    echo "Registro de evidencia exitoso.";
} else {
    echo "Error en el registro de evidencia: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
