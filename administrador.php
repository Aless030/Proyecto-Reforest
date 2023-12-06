<?php

class DB
{

    public $pdo = null;

    function __construct()
    {

        $this->pdo = new PDO(
            "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
}
define("DB_HOST", "localhost");
define("DB_NAME", "reforest");
define("DB_CHARSET", "utf8mb4");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_PORT", 3308);
$_DB = new DB();


?>
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
    <meta name="description" content="A growing collection of ready to use components for the CSS framework Bootstrap 5" />
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png" />
    <link rel="icon" type="image/png" sizes="96x96" href=".t/img/favicon.png" />
    <meta name="author" content="Holger Koenemann" />
    <meta name="generator" content="Eleventy v2.0.0" />
    <meta name="HandheldFriendly" content="true" />

    <link rel="stylesheet" href="css/theme.min.css" />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css' rel='stylesheet' />
    <link href='procesar_registro.php' rel='' />
    <link href='modelo.php' rel='' />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Agrega tu clave de acceso de Mapbox -->

    <!-- Agrega tus estilos CSS personalizados -->

    <script>
        function mostrarModal() {
            var modal = document.getElementById("modal");
            modal.style.display = "block";
        }

        function cerrarModal() {
            var modal = document.getElementById("modal");
            modal.style.display = "none";
        }
    </script>
    <style>
        /* inter-300 - latin */
        @font-face {
            font-family: "Inter";
            font-style: normal;
            font-weight: 300;
            font-display: swap;
            src: local(""), url("./fonts/inter-v12-latin-300.woff2") format("woff2"),
                /* Chrome 26+, Opera 23+, Firefox 39+ */
                url("./fonts/inter-v12-latin-300.woff") format("woff");
            /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        @font-face {
            font-family: "Inter";
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: local(""), url("./fonts/inter-v12-latin-500.woff2") format("woff2"),
                /* Chrome 26+, Opera 23+, Firefox 39+ */
                url("./fonts/inter-v12-latin-500.woff") format("woff");
            /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        @font-face {
            font-family: "Inter";
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: local(""), url("./fonts/inter-v12-latin-700.woff2") format("woff2"),
                /* Chrome 26+, Opera 23+, Firefox 39+ */
                url("./fonts/inter-v12-latin-700.woff") format("woff");
            /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        /* Estilos generales para el carrusel y los formularios */
        .carousel-container {
            width: 550px;
            overflow: hidden;
            margin: 0 auto;
        }

        .carousel {
            display: flex;
            transition: transform 0.5s;
        }

        .slide {
            flex: 0 0 100%;
            width: 500px;
            padding: 20px;

            background-color: #f9f9f9;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            display: none;
        }

        .titulo-pagina {
            font-size: 80px;
            font-weight: bold;

            /* Cambia el color según tu preferencia */
            text-align: left;
            /* Otros estilos adicionales según tus necesidades */
        }

        #modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            /* Ajusta este valor según sea necesario */
        }

        .modal-contenido {
            background-color: white;
            margin: 5% auto;
            /* Ajusta el margen superior según sea necesario */
            padding: 20px;
            border: 1px solid #888;
            max-width: 100%;
            /* Ajusta el ancho máximo del modal según sea necesario */
            border-radius: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1001;
            /* Asegura que el contenido del modal esté por encima del fondo del modal */
        }


        .cerrar {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1002;
            /* Asegura que el botón de cerrar esté por encima del contenido del modal */
        }

        .cerrar:hover,
        .cerrar:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Estilos personalizados para el mapa */
        #map {
            width: 100%;

        }
    </style>

</head>

<body data-bs-spy="scroll" data-bs-target="#navScroll">
    <nav id="navScroll" class="navbar navbar-expand-lg navbar-light fixed-top" tabindex="0" style="background-color: #f9f9f9e0;">
        <div class="container">
            <a class="navbar-brand pe-4 fs-4" href="#top">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-layers-half" viewbox="0 0 16 16">


                </svg>

                <span class="ms-1 fw-bolde">ReforestLife<i class='bx bxs-tree-alt'></i></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#aboutus"> Registrados </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#numbers"> Interesados </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#map"> ----</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#workwithus"> ------ </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials"> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-dark  shadow rounded-0" href="http://localhost/Proyecto%20Reforest/index.html" style="color:white ;">
                            Salir
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="w-100 overflow-hidden bg-gray-100" id="top">
        <div class="container position-relative">
            <div class="col-12 col-lg-8 mt-0 h-100 position-absolute top-0 end-0 bg-cover" data-aos="fade-left" style="background-image: url(img/pla.jpg)"></div>
            <div class="row">
                <div class="col-lg-7 py-vh-6 position-relative" data-aos="fade-right">
                    <h1 class="display-1 fw-bold mt-5">Bienvenido <br> Administrador</h1>
                    <p class="lead">
                        Aca Controlaremos todo respecto a nuestro voluntariado
                    </p>

                </div>
            </div>
        </div>
    </div>
    <div class="py-vh-4 bg-gray-100 w-100 overflow-hidden" id="aboutus">

        <div class="container" >
            <h2 style="text-align: center;">Lista de Voluntarios</h2>
            <br>
            <button type="button" onclick="mostrarModal()" class="btn btn-primary">Nuevo</button>
            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Género</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Correo Electrónico</th>
                        <th>Número de Identificación</th>
                        <th>Foto de Perfil</th>
                        <th>Documentos de Identificación</th>
                        <th>Certificado de Nacimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $_DB->pdo->query("SELECT * FROM voluntarios");
                    $results = $query->fetchAll();

                    foreach ($results as $voluntario) {
                        echo "<tr>";
                        echo "<td>" . $voluntario['nombre'] . "</td>";
                        echo "<td>" . $voluntario['fecha_nacimiento'] . "</td>";
                        echo "<td>" . $voluntario['genero'] . "</td>";
                        echo "<td>" . $voluntario['telefono'] . "</td>";
                        echo "<td>" . $voluntario['direccion'] . "</td>";
                        echo "<td>" . $voluntario['correo'] . "</td>";
                        echo "<td>" . $voluntario['numero_identificacion'] . "</td>";
                        echo "<td>" . $voluntario['foto_perfil'] . "</td>";
                        echo "<td>" . $voluntario['documentos_identificacion'] . "</td>";
                        echo "<td>" . $voluntario['certificado_nacimiento'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
        <div id="modal" class="modal" >
            <div class="modal-contenido">
                <span class="cerrar" onclick="cerrarModal()">&times;</span>
                <h2 style="color: black">Ingresa Nuevo Voluntario</h2>

                <!-- Agrega un contenedor para mostrar el mensaje de error -->
                <div class="carousel">
                    <form id="fullForm" action="controlador.php" method="post" onsubmit="return validarFormularioActual()">
                        <div class="slide card p-4 custom-width" id="slide1" style="
                        display: block;

                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
                        background-color: #202529;
                        color: #e0e0e0;
                      ">
                            <!-- Contenido del primer formulario -->
                            <div class="form-group">
                                <label for="nombre">Nombre Completo:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required />
                            </div>
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required />
                            </div>
                            <div class="form-group">
                                <label for="genero">Género:</label>
                                <select class="form-control" id="genero" name="genero" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Número de Teléfono:</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required />
                            </div>
                            <div class="form-group">
                                <label for="telefono">Direccion:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required />
                            </div>
                            <div class="form-group">
                                <label for="telefono">Correo Electronico:</label>
                                <input type="email" class="form-control" id="correo" name="correo" required />
                            </div>




                        </div>
                        <div class="slide card p-4 custom-width" id="slide2" style="
                        display: none;

                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
                        background-color: #202529;
                        color: #e0e0e0;
                      ">
                            <!-- Contenido del segundo formulario -->
                            <div class="form-group">
                                <label for="numero_identificacion">Número de Identificación:</label>
                                <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" required />
                            </div>
                            <div class="form-group">
                                <label for="foto_perfil">Foto de Perfil:</label>
                                <input type="file" class="form-control-file" id="foto_perfil" name="foto_perfil" accept=".jpg, .jpeg, .png" required />
                                <small>(Subir una foto de perfil)</small>
                            </div>
                            <!-- Resto de campos del segundo formulario -->
                        </div>
                        <!-- ... (resto de formularios) ... -->
                        <div class="slide card p-4 custom-width" id="slide3" style="
                        display: none;

                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
                        background-color: #202529;
                        color: #e0e0e0;
                      ">
                            <!-- Contenido del segundo formulario -->
                            <div class="form-group">
                                <label for="documentos_identificacion">Carnet de Identificación:</label>
                                <input type="file" class="form-control-file" id="documentos_identificacion" name="documentos_identificacion" accept=".pdf, .jpg, .png" required />
                                <small>(Subir copia de cédula)</small>
                            </div>
                            <!-- Nuevos Campos de Documentación -->
                            <div class="form-group">
                                <label for="certificado_nacimiento">Certificado de Nacimiento:</label>
                                <input type="file" class="form-control-file" id="certificado_nacimiento" name="certificado_nacimiento" accept=".pdf, .jpg, .png" required />
                                <small>(Subir copia del certificado de nacimiento Opcional)</small>
                            </div>

                            <!-- Resto de campos del segundo formulario -->
                        </div>
                        <div class="text-center">
                            <button type="button" id="prev" class="btn btn-primary" style="
                          background-color: orange;
                          width: 20%;
                          display: none;
                        ">
                                Anterior
                            </button>
                            <button type="button" id="next" class="btn btn-primary" style="background-color: orange; width: 20%">
                                Siguiente
                            </button>
                            <button type="submit" class="btn btn-primary" style="background-color: orange; display: none">
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class=" bg-gray-100 w-100 overflow-hidden" id="numbers">
        <!-- Nuevo contenedor para mostrar correos de interesados -->
        <div class="container">
            <h2 style="text-align: center;">Lista de Interesados</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Correo Electrónico</th>
                        <!-- Agrega más columnas si es necesario -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Recupera los correos de los interesados
                    $queryInteresados = $_DB->pdo->query("SELECT * FROM usuarios");
                    $resultsInteresados = $queryInteresados->fetchAll();

                    foreach ($resultsInteresados as $interesado) {
                        echo "<tr>";
                        echo "<td>" . $interesado['email'] . "</td>";
                        // Agrega más columnas si es necesario
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <footer>
            <div class="container small border-top">
                <div class="row py-2 d-flex justify-content-between">

                    <div class="text-secondary mt-3 col-12 col-lg-6 col-xl-3 border-end p-5">
                        <strong class="h6 mb-3">ReforestLife<i class='bx bxs-tree-alt'></i></strong><br />

                        <address class="text-secondary mt-3">

                            "Cambiando la vida del mundo"
                        </address>
                        <ul class="nav flex-column"></ul>
                    </div>
                    <div class="text-secondary mt-3 col-12 col-lg-6 col-xl-3 border-end p-5">
                        <h3 class="h6 mb-3">Facebook</h3>
                        <address class="text-secondary mt-3">

                            Siguenos en facebook:
                        </address>
                        <ul class="nav flex-column"></ul>
                    </div>
                    <div class="text-secondary mt-3 col-12 col-lg-6 col-xl-3 border-end p-5">
                        <h3 class="h6 mb-3">Instagram</h3>
                        <address class="text-secondary mt-3">

                            Siguenos en instagram:
                        </address>
                        <ul class="nav flex-column"></ul>
                    </div>
                    <div class="text-secondary mt-3 col-12 col-lg-6 col-xl-3 p-5">
                        <h3 class="h6 mb-3">Whatsapp</h3>
                        <address class="text-secondary mt-3">

                            Siguenos en WhatsApp:
                        </address>
                        <ul class="nav flex-column"></ul>
                    </div>
                </div>
            </div>

            <div class="container text-center py-3 small">
                By
                <a href="https://github.com/Aless030" class="link-fancy" target="_blank">ReforestLife.com</a>
            </div>
        </footer>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/aos.js"></script>

        <script>
            AOS.init({
                duration: 800, // values from 0 to 3000, with step 50ms
            });

            const formCount = 3;
            let currentForm = 1;

            // EventListeners para el botón "Siguiente" y "Anterior"
            document.getElementById("next").addEventListener("click", function() {
                if (validarFormularioActual()) {
                    if (currentForm < formCount) {
                        document.getElementById("slide" + currentForm).style.display =
                            "none";
                        currentForm++;
                        document.getElementById("slide" + currentForm).style.display =
                            "block";

                        document.getElementById("prev").style.display = "block";
                        if (currentForm === formCount) {
                            document.getElementById("next").style.display = "none";
                            document.querySelector(
                                'form button[type="submit"]'
                            ).style.display = "block";
                        }
                    }
                }
            });

            document.getElementById("prev").addEventListener("click", function() {
                if (currentForm > 1) {
                    document.getElementById("slide" + currentForm).style.display = "none";
                    currentForm--;
                    document.getElementById("slide" + currentForm).style.display =
                        "block";

                    document.getElementById("next").style.display = "block";
                    if (currentForm === 1) {
                        document.getElementById("prev").style.display = "none";
                    }
                }
            });

            function validarFormularioActual() {
                const currentSlide = document.getElementById("slide" + currentForm);
                const requiredFields = currentSlide.querySelectorAll("[required]");
                let isValid = true;

                requiredFields.forEach((field) => {
                    if (field.value.trim() === "") {
                        isValid = false;
                        field.classList.add("campo-invalido");
                    } else {
                        field.classList.remove("campo-invalido");
                    }
                });

                if (!isValid) {
                    alert("Por favor, complete todos los campos requeridos.");
                }

                return isValid;
            }
        </script>

        <script>
            let scrollpos = window.scrollY;
            const header = document.querySelector(".navbar");
            const header_height = header.offsetHeight;

            const add_class_on_scroll = () =>
                header.classList.add("scrolled", "shadow-sm");
            const remove_class_on_scroll = () =>
                header.classList.remove("scrolled", "shadow-sm");

            window.addEventListener("scroll", function() {
                scrollpos = window.scrollY;

                if (scrollpos >= header_height) {
                    add_class_on_scroll();
                } else {
                    remove_class_on_scroll();
                }

                console.log(scrollpos);
            });
        </script>


</body>

</html>