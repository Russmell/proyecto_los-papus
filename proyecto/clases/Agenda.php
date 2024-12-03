<?php

require_once('conexion.php');

class Eventos {
    public $titulo, $descripcion, $fecha;
    public $conexion;

    public function __construct($conexion, $titulo = null, $descripcion = null, $fecha = null) {
        $this->conexion = $conexion;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
    }

    public function registrarEvento() {
        $sql = "INSERT INTO Eventos (titulo, descripcion, fecha) VALUES ('$this->titulo', '$this->descripcion', '$this->fecha')";
        
        if (mysqli_query($this->conexion, $sql)) {
            return "Evento registrado exitosamente.";
        } else {
            return "Error al registrar el Evento: " . mysqli_error($this->conexion);
        }        
    }

    public static function mostrarEventos($conexion) {
        $sql = "SELECT * FROM eventos";
        $resultado = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo "ID: " . $fila["id"] . " - Titulo: " . $fila["titulo"] . " - Descripcion: " . $fila["descripcion"] . " - Fecha: " . $fila["fecha"] . "<br>";
            }
        } else {
            echo "0 resultados";
        }
    }

    public function actualizarEventos($id) {
        $sql = "UPDATE eventos SET titulo='$this->titulo', descripcion='$this->descripcion', fecha='$this->fecha' WHERE id=$id";
        if (mysqli_query($this->conexion, $sql)) {
            echo "Evento actualizado correctamente";
        } else {
            echo "Error al actualizar el evento: " . mysqli_error($this->conexion);
        }
    }

    public function eliminarEventos($id) {
        $sql = "DELETE FROM eventos WHERE id=$id";
        if (mysqli_query($this->conexion, $sql)) {
            echo "Evento eliminado correctamente";
        } else {
            echo "Error al eliminar el evento: " . mysqli_error($this->conexion);
        }
    }
}
?>
