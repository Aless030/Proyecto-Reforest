<?php
require_once 'modelo.php';

// Verifica si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se recibió el campo "email" en el formulario
    if (isset($_POST["email"])) {
        // Datos del formulario
        $email = $_POST["email"];

        // Realiza la conexión a la base de datos con el puerto especificado
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "reforest";
        $port = "3308"; // Por ejemplo, 3306 es el puerto predeterminado para MySQL

        try {
            $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Utiliza consultas preparadas para evitar inyección SQL
            $sql = "INSERT INTO usuarios (email) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email]);

            // Redirige a la página de éxito
            header("Location: http://localhost/Proyecto%20Reforest/index.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            // Cierra la conexión
            $conn = null;
        }
    } else {
        // Si no se recibió el campo "email"
        echo "Error: No se proporcionó un correo electrónico.";
    }
} else {
    // Maneja el caso en el que no se recibieron datos del formulario de manera adecuada
    echo "Acceso no autorizado";
}
?>
