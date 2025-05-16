<?php
session_start();

// Verificar que el usuario está autenticado y es admin (rol id 4)
if (!isset($_SESSION['usuario_id']) || $_SESSION['id_rol'] !== 4) {
    header('Location: ../Login/login.php');
    exit();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "cadena_custodia");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Acciones permitidas para filtrar
$acciones_permitidas = ['Subida Evidencia', 'Subida Caso', 'Modificación Evidencia', 'Modificación Caso', 'Registro Análisis', 'Consulta Análisis', 'login'];
$filtro = isset($_GET['accion']) && in_array($_GET['accion'], $acciones_permitidas) ? $_GET['accion'] : '';

// Preparar consulta según filtro
if ($filtro) {
    $sql = "SELECT id_acceso, id_usuario, accion, fecha_acceso, direccion_ip
            FROM historial_accesos
            WHERE accion = ?
            ORDER BY fecha_acceso DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('s', $filtro);
} else {
    $sql = "SELECT id_acceso, id_usuario, accion, fecha_acceso, direccion_ip
            FROM historial_accesos
            ORDER BY fecha_acceso DESC";
    $stmt = $conexion->prepare($sql);
}
$stmt->execute();
$resultado = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de accesos</title>
    <link rel="stylesheet" href="../../css/modificar.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/forms.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <style>
      body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
      .container { max-width: 900px; margin: 40px auto; background: #fff; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
      h1 { margin-bottom: 20px; }
      form { margin-bottom: 20px; }
      table { width: 100%; border-collapse: collapse; }
      th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
      th { background: #f0f0f0; }
      select, button { padding: 6px 10px; margin-left: 8px; }
    </style>
</head>
<body>


  <div class="container">
    <h1>Historial de accesos</h1>

    <form method="GET" action="historial_accesos.php">
      <label for="accion">Filtrar por acción:</label>
      <select name="accion" id="accion">
        <option value="">-- Todas --</option>
        <?php foreach ($acciones_permitidas as $accion): ?>
          <option value="<?= $accion ?>" <?= ($accion === $filtro) ? 'selected' : '' ?>>
            <?= ucfirst($accion) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Filtrar</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>ID Acceso</th>
          <th>ID Usuario</th>
          <th>Acción</th>
          <th>Fecha/Hora</th>
          <th>IP</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($resultado->num_rows > 0): ?>
          <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id_acceso']) ?></td>
              <td><?= htmlspecialchars($row['id_usuario']) ?></td>
              <td><?= htmlspecialchars($row['accion']) ?></td>
              <td><?= htmlspecialchars($row['fecha_acceso']) ?></td>
              <td><?= htmlspecialchars($row['direccion_ip']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" style="text-align:center;">No hay registros.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
