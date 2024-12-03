<?php
session_start();
// Verifica si el usuario no está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php"); // Redirige al login
    exit;
}
?>
<?php
require_once ('../conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <!-- Agregar el CDN de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Enlazar el archivo CSS -->
    <link rel="stylesheet" href="estilos.css"> <!-- Asegúrate de poner la ruta correcta -->
</head>
<body>
    <div class="container mt-5">
        <center><h1>Lista de Tareas</h1></center><hr>
        
        <!-- Tabla de tareas con Bootstrap -->
        <table class="table table-bordered table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Título</th>
                    <th>Responsable</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Prioridad</th>
                    <th>Notas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tareas ORDER BY fecha";
                $result = $conexion->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['titulo'] . "</td>";
                        echo "<td>" . $row['responsable'] . "</td>";
                        echo "<td>" . $row['estado'] . "</td>";
                        echo "<td>" . $row['fecha'] . "</td>";
                        echo "<td>" . $row['prioridad'] . "</td>";
                        echo "<td>" . $row['notas'] . "</td>";
                        echo "<td>
                                <a href='editar.php?id=" . $row['id'] . "' class='btn btn-warning'>Editar</a>
                                <a href='funciones.php?action=eliminar&id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"¿Está seguro de eliminar esta tarea?\")'>Eliminar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No hay tareas registradas</td></tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>

        <!-- Enlace para agregar nueva tarea -->
        <a href="guardar.php" class="btn btn-outline-primary">Agregar nueva tarea</a>
    </div>

    <!-- Agregar el script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
