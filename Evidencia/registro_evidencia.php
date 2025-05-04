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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Acceso no autorizado.";
    exit();
}

$id_rol = $_SESSION['id_rol'];
$redireccion = ($id_rol == 4) ? '../Admin/Evidencia/agregar_evidencia_admin.php' : 'agregar_evidencia.php';

// FUNCIÓN para mostrar popup
function mostrar_alerta($tipo, $titulo, $texto, $redireccion) {
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
                text: '$texto',
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

// 1. Recoger datos
$id_usuario_form = trim($_POST['id_usuario'] ?? '');
$id_caso         = trim($_POST['id_caso'] ?? '');
$tipo_evidencia  = strtolower(trim($_POST['tipo_evidencia'] ?? ''));
$descripcion     = trim($_POST['Descripcion'] ?? '');

// 2. Validaciones
if ($id_caso === '' || $id_usuario_form === '' || $tipo_evidencia === '' || !isset($_FILES['archivo'])) {
    mostrar_alerta('error', 'Campos Requeridos', 'Todos los campos son requeridos.', $redireccion);
}

if (!ctype_digit($id_usuario_form)) {
    mostrar_alerta('error', 'ID Usuario Inválido', 'El ID del usuario debe ser numérico.', $redireccion);
}

$id_usuario = intval($id_usuario_form);

$tipos_permitidos = ['pdf','imagen','video','audio','otro'];
if (!in_array($tipo_evidencia, $tipos_permitidos)) {
    mostrar_alerta('error', 'Tipo Inválido', 'El tipo de evidencia no es válido.', $redireccion);
}

// 3. Procesar archivo
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$file = $_FILES['archivo'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    mostrar_alerta('error', 'Error Archivo', 'Error en la carga del archivo.', $redireccion);
}

$originalName   = basename($file['name']);
$extension      = pathinfo($originalName, PATHINFO_EXTENSION);
$newFileName    = uniqid('evid_', true) . '.' . $extension;
$targetFilePath = $uploadDir . $newFileName;

if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    mostrar_alerta('error', 'Error al Guardar', 'Error al guardar el archivo.', $redireccion);
}

// 4. Insertar
$hash_sha3     = hash_file('sha3-256', $targetFilePath);
$tamano_archivo = filesize($targetFilePath);

$stmt = $conn->prepare("
    INSERT INTO evidencias 
      (id_caso, id_usuario, tipo_evidencia, nombre_archivo, ruta_archivo, hash_sha3, tamano_archivo)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
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

try {
    if ($stmt->execute()) {
        $evidencia_id = $stmt->insert_id;
        $ip           = $_SERVER['REMOTE_ADDR'];
        $h = $conn->prepare("
            INSERT INTO historial_accesos 
              (id_usuario, id_evidencia, accion, direccion_ip)
            VALUES (?, ?, 'Registro Evidencia', ?)
        ");
        $h->bind_param("iis", $id_usuario, $evidencia_id, $ip);
        $h->execute();

        mostrar_alerta('success', '¡Registro Exitoso!', 'La evidencia ha sido registrada correctamente.', $redireccion);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        mostrar_alerta('warning', 'Evidencia Existente', 'La evidencia ya está en el sistema.', $redireccion);
    } else {
        mostrar_alerta('error', 'Error en Registro', 'Hubo un problema al registrar la evidencia.', $redireccion);
    }
}

$stmt->close();
$conn->close();
?>
