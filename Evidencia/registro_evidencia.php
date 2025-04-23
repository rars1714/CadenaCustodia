<?php
// registro_evidencia.php

// Configuración de la conexión a la base de datos
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "cadena_custodia";

// Conexión a la base de datos
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que se envíe el formulario mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar los datos del formulario
    $id_caso = trim($_POST['id_caso'] ?? '');
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $tipo_evidencia = strtolower(trim($_POST['tipo_evidencia'] ?? ''));
    $descripcion = trim($_POST['Descripcion'] ?? '');  // Se recoge la descripción, aunque la tabla no la incluya

    // Validar que los campos requeridos estén completos y que se haya adjuntado un archivo
    if (empty($id_caso) || empty($id_usuario) || empty($tipo_evidencia) || !isset($_FILES['archivo'])) {
        echo "Todos los campos son requeridos y se debe adjuntar un archivo.";
        exit;
    }

    // Validar que el tipo de evidencia sea uno de los permitidos
    $tipos_permitidos = ['documento', 'imagen', 'video', 'audio', 'otro'];
    if (!in_array($tipo_evidencia, $tipos_permitidos)) {
        echo "El tipo de evidencia no es válido.";
        exit;
    }

    // Procesar la carga del archivo
    $uploadDir = 'uploads/'; // Directorio de destino (asegúrate de que exista y tenga permisos de escritura)
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Se procesa el primer archivo en caso de que se hayan enviado múltiples
    $file = $_FILES['archivo'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Error en la carga del archivo.";
        exit;
    }

    // Nombre original y extensión
    $originalName = basename($file['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    // Generar un nombre único para evitar colisiones
    $newFileName = uniqid('evid_', true) . '.' . $extension;
    $targetFilePath = $uploadDir . $newFileName;

    // Mover el archivo subido al directorio destino
    if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        echo "Error al guardar el archivo.";
        exit;
    }

    // Calcular el hash SHA-3 del archivo para garantizar su integridad
    $hash_sha3 = hash_file('sha3-256', $targetFilePath);

    // Obtener el tamaño del archivo
    $tamano_archivo = filesize($targetFilePath);

    // Preparar la sentencia SQL para insertar la evidencia en la base de datos
    $stmt = $conn->prepare("INSERT INTO evidencias (id_caso, id_usuario, tipo_evidencia, nombre_archivo, ruta_archivo, hash_sha3, tamano_archivo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Error en la preparación: " . $conn->error;
        exit;
    }

    // Asociar los parámetros (se asume que id_caso e id_usuario son enteros)
    $stmt->bind_param("iissssi", $id_caso, $id_usuario, $tipo_evidencia, $originalName, $targetFilePath, $hash_sha3, $tamano_archivo);



    // Ejecutar la sentencia y verificar el resultado
    if ($stmt->execute()) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $conexion->prepare("
        INSERT INTO historial_accesos 
            (id_usuario, accion, direccion_ip)
        VALUES (?, 'subida', ?)
        ");
        $stmt->execute([ $user_id, $ip ]);
        echo "Registro de evidencia exitoso.";
    } else {
        echo "Error en el registro: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Acceso no autorizado.";
}

$conn->close();
?>
