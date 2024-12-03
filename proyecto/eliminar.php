<?php
session_start();
// Verifica si el usuario no está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../login.php"); // Redirige al login si no está autenticado
    exit;
}

require_once('conexion.php');

// Verificar la acción y el ID
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

if ($action === 'eliminar' && $id) {
    // Preparar y ejecutar la consulta para eliminar la tarea
    $sql = "DELETE FROM tareas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirige a la lista de tareas después de eliminar
        exit;
    } else {
        echo "Error al eliminar la tarea: " . $stmt->error;
    }
} else {
    echo "Acción o ID no válidos.";
}

$conexion->close();
?>
