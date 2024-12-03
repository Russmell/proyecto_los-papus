<?php
class Usuario {
    public $conexion;
    public $nombre;
    public $email;
    public $password;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Método para registrar un usuario
    public function registrarUsuario($nombre, $email, $password) {
        // Primero, verificar si el email ya existe
        $verificar_email = "SELECT * FROM usuarios WHERE email = ?";
        $stmt_verificar = mysqli_prepare($this->conexion, $verificar_email);
        mysqli_stmt_bind_param($stmt_verificar, "s", $email);
        mysqli_stmt_execute($stmt_verificar);
        $resultado = mysqli_stmt_get_result($stmt_verificar);

        if (mysqli_num_rows($resultado) > 0) {
            // El email ya existe
            return "El correo electrónico ya está registrado.";
        }

        // Si el email no existe, proceder con el registro
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Encripta la contraseña

        // Usar consulta preparada para prevenir inyección SQL
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return "Error al preparar la consulta: " . mysqli_error($this->conexion);
        }

        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $hashed_password);
        
        if (mysqli_stmt_execute($stmt)) {
            return "Usuario registrado exitosamente.";
        } else {
            return "Error al registrar el usuario: " . mysqli_stmt_error($stmt);
        }
    }

    // Método para iniciar sesión (mantener como estaba)
    public function iniciarSesion($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);

            if (password_verify($password, $usuario['password'])) {
                return "Bienvenido, " . $usuario['nombre'];
            } else {
                return "Contraseña incorrecta.";
            }
        } else {
            return "El usuario no existe.";
        }
    }
}
?>