<?php
session_start();
// Verifica si el usuario no está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../login/login.php"); // Redirige al login
    exit;
}

require_once('../conexion.php');

// Procesar solo si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar datos del formulario
    $titulo = $_POST['titulo'] ?? null;
    $responsable = $_POST['responsable'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $prioridad = $_POST['prioridad'] ?? null;
    $notas = $_POST['notas'] ?? null;

    if ($titulo && $estado && $fecha && $prioridad) {
        // Realizar la consulta SQL con prevención de inyección SQL
        $sql = "INSERT INTO tareas (titulo, responsable, estado, fecha, prioridad, notas) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssss", $titulo, $responsable, $estado, $fecha, $prioridad, $notas);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            header("Location: index.php"); // Redirige a la lista de tareas después de agregar
            exit;
        } else {
            echo "Error al guardar la tarea: " . $stmt->error;
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
    <title>Guardar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <form method="post" class="position-absolute top-50 start-50 translate-middle">
        <center><h2>Guardar Tarea</h2></center><hr>
        <div class="row">
            <div class="col-6 mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" id="titulo" name="titulo" class="form-control" required>
            </div>
            <div class="col-6 mb-3">
                <label for="responsable" class="form-label">Responsable:</label>
                <input type="text" id="responsable" name="responsable" class="form-control">
            </div>
            <div class="col-4 mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="Listo">Listo</option>
                    <option value="En curso">En curso</option>
                    <option value="Detenido">Detenido</option>
                </select>
            </div>
            <div class="col-4 mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" id="fecha" name="fecha" class="form-control" required>
            </div>
            <div class="col-4 mb-3">
                <label for="prioridad" class="form-label">Prioridad:</label>
                <select id="prioridad" name="prioridad" class="form-select" required>
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label for="notas" class="form-label">Notas:</label>
                <textarea id="notas" name="notas" class="form-control"></textarea>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end"> 
                <button type="submit" class="btn btn-outline-primary">Guardar</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
