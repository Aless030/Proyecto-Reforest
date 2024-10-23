<?php
session_start();


if (isset($_POST['submit'])) {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Realizar consulta a la base de datos para buscar el usuario
    // y verificar la contraseña

    if ($usuario == "admin" && $contraseña == "admin123") {
        // Iniciar sesión y redirigir al administrador a la página de reportes
        $_SESSION['admin'] = true;
        header("Location: http://localhost/Proyecto%20Reforest/administrador.php");
        exit();
    } else {
        // Establecer mensaje de error si las credenciales son incorrectas
        $error = "Credenciales inválidas";

    }
}
?>

