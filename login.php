<?php
require_once 'modelo.php'; // Incluye el archivo modelo.php que contiene la clase DB

// Recibir datos del formulario
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

try {
    // Consulta para verificar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios_registrados WHERE usuario = :usuario";

    // Preparamos la consulta
    $stmt = $_DB->pdo->prepare($sql);

    // Bind de parámetros
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

    // Ejecutamos la consulta
    $stmt->execute();

    // Obtenemos el resultado
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Verificar contraseña
        if (password_verify($contrasena, $row['contrasena'])) {
            // Contraseña correcta, redirigir al usuario
            header("Location: administrador.php");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: index.php?error=1");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: index.php?error=1");
        exit();
    }
} catch (PDOException $e) {
    // Manejo de errores de la base de datos
    die("Error en la consulta: " . $e->getMessage());
}
?>
