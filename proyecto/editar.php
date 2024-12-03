<?php
session_start();
// Verifica si el usuario no está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../login/login.php"); // Redirige al login
    exit;
}

require_once('conexion.php');

// Verificar si se recibió el ID de la tarea
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID de tarea no especificado.";
    exit;
}

// Obtener los datos actuales de la tarea
$sql = "SELECT * FROM tareas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$tarea = $resultado->fetch_assoc();

if (!$tarea) {
    echo "Tarea no encontrada.";
    exit;
}

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar los datos del formulario
    $titulo = $_POST['titulo'] ?? null;
    $responsable = $_POST['responsable'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $prioridad = $_POST['prioridad'] ?? null;
    $notas = $_POST['notas'] ?? null;

    if ($titulo && $estado && $fecha && $prioridad) {
        // Actualizar la tarea en la base de datos
        $sql = "UPDATE tareas SET titulo = ?, responsable = ?, estado = ?, fecha = ?, prioridad = ?, notas = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssi", $titulo, $responsable, $estado, $fecha, $prioridad, $notas, $id);

        if ($stmt->execute()) {
            header("Location: index.php"); // Redirige a la lista de tareas después de editar
            exit;
        } else {
            echo "Error al actualizar la tarea: " . $stmt->error;
        }
    } else {
        echo "Por favor, completa todos los campos obligatorios.";
    }
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <form method="post" class="position-absolute top-50 start-50 translate-middle">
        <center><h2>Editar Tarea</h2></center><hr>
        <div class="row">
            <div class="col-6 mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($tarea['titulo']) ?>" required>
            </div>
            <div class="col-6 mb-3">
                <label for="responsable" class="form-label">Responsable:</label>
                <input type="text" id="responsable" name="responsable" class="form-control" value="<?= htmlspecialchars($tarea['responsable']) ?>">
            </div>
            <div class="col-4 mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="Listo" <?= $tarea['estado'] == 'Listo' ? 'selected' : '' ?>>Listo</option>
                    <option value="En curso" <?= $tarea['estado'] == 'En curso' ? 'selected' : '' ?>>En curso</option>
                    <option value="Detenido" <?= $tarea['estado'] == 'Detenido' ? 'selected' : '' ?>>Detenido</option>
                </select>
            </div>
            <div class="col-4 mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?= htmlspecialchars($tarea['fecha']) ?>" required>
            </div>
            <div class="col-4 mb-3">
                <label for="prioridad" class="form-label">Prioridad:</label>
                <select id="prioridad" name="prioridad" class="form-select" required>
                    <option value="Alta" <?= $tarea['prioridad'] == 'Alta' ? 'selected' : '' ?>>Alta</option>
                    <option value="Media" <?= $tarea['prioridad'] == 'Media' ? 'selected' : '' ?>>Media</option>
                    <option value="Baja" <?= $tarea['prioridad'] == 'Baja' ? 'selected' : '' ?>>Baja</option>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label for="notas" class="form-label">Notas:</label>
                <textarea id="notas" name="notas" class="form-control"><?= htmlspecialchars($tarea['notas']) ?></textarea>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end"> 
                <button type="submit" class="btn btn-outline-primary">Actualizar</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
