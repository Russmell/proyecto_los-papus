<?php
session_start();

// Destruye la sesión
session_unset();
session_destroy();

// Envía un mensaje de confirmación al JavaScript
echo 'Se cerró tu sesión';
exit();
?>