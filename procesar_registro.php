<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reforest";
$port = "3308";
require_once 'modelo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST["apellido_paterno"];
    $apellido_materno = $_POST["apellido_materno"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $genero = $_POST["genero"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $correo = $_POST["correo"];
    $numero_identificacion = $_POST["numero_identificacion"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

    // Manejo de archivos
    $foto_perfil = '';
    $documentos_identificacion = '';

    // Ruta del directorio de subidas
    $upload_dir = 'C:/wamp64/www/Proyecto Reforest/uploads/';

    // Crear directorio 'uploads' si no existe
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Subir foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $foto_perfil = basename($_FILES['foto_perfil']['name']);
        $foto_perfil_path = $upload_dir . $foto_perfil;
        if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil_path)) {
            echo "Error al mover el archivo de foto de perfil.";
            exit();
        }
    }

    // Subir documentos de identificación
    if (isset($_FILES['documentos_identificacion']) && $_FILES['documentos_identificacion']['error'] == UPLOAD_ERR_OK) {
        $documentos_identificacion = basename($_FILES['documentos_identificacion']['name']);
        $documentos_identificacion_path = $upload_dir . $documentos_identificacion;
        if (!move_uploaded_file($_FILES['documentos_identificacion']['tmp_name'], $documentos_identificacion_path)) {
            echo "Error al mover el archivo de documentos de identificación.";
            exit();
        }
    }

    try {
        // Realiza la conexión a la base de datos
        $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepara y ejecuta la consulta
        $sql = "INSERT INTO usuarios_registrados (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, genero, telefono, direccion, correo, numero_identificacion, foto_perfil, documentos_identificacion, usuario, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $apellido_paterno, $apellido_materno, $fecha_nacimiento, $genero, $telefono, $direccion, $correo, $numero_identificacion, $foto_perfil, $documentos_identificacion, $usuario, $contrasena_hashed]);

        // Redirige después del registro exitoso
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        // Maneja los errores de manera más detallada
        echo "Error al registrar usuario: " . $e->getMessage();
    } finally {
        // Cierra la conexión
        $conn = null;
    }
} else {
    // Muestra un mensaje de error más amigable si no se reciben datos del formulario
    echo "No se han recibido datos del formulario";
}
?>
