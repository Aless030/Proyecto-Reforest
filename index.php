<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "reforest", 3308);
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener los árboles
$sql = "SELECT especie, edad, cuidados, estado, fotoUrl, altura, diametroTronco, ST_AsText(coordenadas) as coordenadas,qrUrl FROM arboles";
$result = $conn->query($sql);

// Array para almacenar los árboles
$arboles = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $arboles[] = $row;
  }
}
$conn->close();
?>
<!DOCTYPE html>
<html class="h-100" lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
  <meta name="description" content="A growing collection of ready to use components for the CSS framework Bootstrap 5" />
  <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <link rel="icon" type="image/png" sizes="96x96" href=".t/img/favicon.png" />
  <meta name="author" content="Holger Koenemann" />
  <meta name="generator" content="Eleventy v2.0.0" />
  <meta name="HandheldFriendly" content="true" />

  <link rel="stylesheet" href="css/theme.min.css" />
  <script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet" />
  <!-- Agrega tu clave de acceso de Mapbox -->
  <script>
    mapboxgl.accessToken =
      "pk.eyJ1IjoiYWxlc3NpcyIsImEiOiJjbGcxbHBtbHQwdDU5M2RubDFodjY3a2x0In0.NXe43GdM4PJBj7ow0Dnkpw";
  </script>
  <!-- Agrega tus estilos CSS personalizados -->
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

    .tree-marker {
      border-radius: 50%;

      background-color: cover;
    }

    .map-legend {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: rgba(255, 255, 255, 0.8);
      /* Fondo semitransparente */
      padding: 10px;
      border-radius: 5px;
      font-family: Arial, sans-serif;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      /* Sombra para resaltar la leyenda */
      z-index: 1000;
      /* Z-index alto para asegurar que esté sobre el mapa */
    }

    .map-legend h4 {
      margin: 0 0 10px;
      font-size: 14px;
    }

    .legend-icon {
      display: inline-block;
      width: 12px;
      height: 12px;
      margin-right: 8px;
      border-radius: 2px;
    }

    .legend-icon.protected {
      background-color: #ff0000;
      /* Color rojo para Árboles Protegidos */
    }

    .legend-icon.native {
      background-color: #00ff00;
      /* Color verde para Árboles Nativos */
    }

    .legend-icon.dangerous {
      background-color: #ffcc00;
      /* Color amarillo para Árboles Peligrosos */
    }

    .container-mapa {
      max-width: 1000px;
      /* Cambia este valor según el ancho que prefieras */
      margin: 0 auto;
      /* Centra el contenedor horizontalmente */

      /* Opcional: añade espacio alrededor del mapa */
    }
  </style>
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
</head>

<body data-bs-spy="scroll" data-bs-target="#navScroll">
  <nav id="navScroll" class="navbar navbar-expand-lg navbar-light fixed-top" tabindex="0" style="background-color: #f9f9f9e0">
    <div class="container">
      <a class="navbar-brand pe-4 fs-4" href="#top">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-layers-half" viewbox="0 0 16 16"></svg>

        <span class="ms-1 fw-bolde">SkyGreen<i class="bx bxs-tree-alt"></i></span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="#aboutus"> ¿Que hacemos? </a>
          </li>
          <!--<li class="nav-item">
              <a class="nav-link" href="#numbers"></a>
            </li>-->
          <li class="nav-item">
            <a class="nav-link" href="#map"> Árboles Registrados </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#workwithus"> Registrate </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#testimonials"> Más Información </a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-dark shadow rounded-0" style="color: white" href="#!" onclick="mostrarModal()">
              Login
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  <div id="modal" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal()">&times;</span>
      <h2 style="color: black">Login</h2>
      <div id="error-message" style="color: red; text-align: center; display: none;">Usuario o contraseña incorrectos</div>
      <form id="login-form" method="POST" action="login.php" onsubmit="return validarFormulario()">
        <label for="usuario" style="color: black">Usuario:</label>
        <input type="text" class="form-control" name="usuario" required /><br /><br />
        <label for="contrasena" style="color: black">Contraseña:</label>
        <input type="password" class="form-control" name="contrasena" required /><br /><br />
        <input type="submit" class="btn btn-primary" name="submit" value="Iniciar sesión" />
      </form>
    </div>
  </div>


  <div class="w-100 overflow-hidden bg-gray-100" id="top">
    <div class="container position-relative">
      <div class="col-12 col-lg-8 mt-0 h-100 position-absolute top-0 end-0 bg-cover" data-aos="fade-left" style="background-image: url(img/pla.jpg)"></div>
      <div class="row">
        <div class="col-lg-7 py-vh-6 position-relative" data-aos="fade-right">
          <h1 class="display-1 fw-bold mt-5">
            Bienvenido a SkyGreen
          </h1>
          <p class="lead">
            Transformamos la Zona Norte de Cochabamba, árbol por árbol.
            Nuestra misión es construir un futuro más verde y sostenible.
            Nos complace presentar la Plataforma Web Ambiental, una innovadora iniciativa en colaboración con la Empresa Municipal de Áreas Verdes. Aquí, la comunidad y la naturaleza se unen.
            ¡Explora y participa en la transformación verde!
          </p>
          <a href="#aboutus" class="btn btn-dark btn-xl shadow me-3 rounded-0 my-5">Conoce mas sobre nosotros</a>
        </div>
      </div>
    </div>
  </div>

  <div class="py-vh-4 bg-gray-100 w-100 overflow-hidden" id="aboutus">
    <div class="container">
      <div class="row d-flex justify-content-between align-items-center">
        <div class="col-lg-6">
          <div class="row gx-5 d-flex">
            <div class="col-md-11">
              <div class="shadow ratio ratio-16x9 rounded bg-cover bp-center align-self-end" data-aos="fade-right" style="
                      background-image: url(img/mace.jpg);
                      --bs-aspect-ratio: 50%;
                    "></div>
            </div>
            <div class="col-md-5 offset-md-1">
              <div class="shadow ratio ratio-1x1 rounded bg-cover mt-5 bp-center float-end" data-aos="fade-up" style="background-image: url(img/mace2.jpg)"></div>
            </div>
            <div class="col-md-6">
              <div class="col-12 shadow ratio rounded bg-cover mt-5 bp-center" data-aos="fade-left" style="
                      background-image: url(img/mac4.webp);
                      --bs-aspect-ratio: 150%;
                    "></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <h3 class="py-5 border-top border-dark" data-aos="fade-left">
            ¿Qué Hacemos?
          </h3>
          <p data-aos="fade-left" data-aos-delay="200">
            Mapa Interactivo: Descubre cada rincón verde de la Zona Norte, identificando árboles, parques y áreas verdes. Explora un mapa detallado que clasifica los árboles según su estado: protegidos, nativos y peligrosos.
            <br>
            Información Detallada: Aprende sobre las especies de árboles, su historia y los cuidados necesarios para su florecimiento. Accede a datos específicos como la edad de los árboles y consejos para su mantenimiento.
            <br>
            Eventos y Talleres: Únete a nuestras actividades comunitarias, aprende sobre jardinería sostenible y participa en la siembra de árboles. Promovemos la reforestación y te invitamos a registrar tus plantaciones en nuestra plataforma web.
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="small py-vh-3 w-100 overflow-hidden">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-4 border-end" data-aos="fade-up">
          <div class="d-flex">
            <div class="col-md-3 flex-fill pt-3 pe-3 pe-md-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" class="bi bi-box-seam" viewbox="0 0 16 16">
                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z" />
              </svg>
            </div>
            <div class="col-md-9 flex-fill">
              <h3 class="h5 my-2">Registro de Árboles:</h3>
              <p>
                Registra tu árbol y conviértete en un guardián del verde en tu vecindario.
              </p>
              <h3 class="h5 my-2">Guía del arbolado urbano en Cochabamba</h3>
              <p>
                <a href="https://www.lostiempos.com/sites/default/files/ayma2021guiadeselecciondeespeciesparaelarboladourbanodecochabambaparacompartir_1_0.pdf" target="_blank">Informate aquí....</a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 border-end" data-aos="fade-up" data-aos-delay="200">
          <div class="d-flex">
            <div class="col-md-3 flex-fill pt-3 pt-3 pe-3 pe-md-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" class="bi bi-card-checklist" viewbox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z" />
              </svg>
            </div>
            <div class="col-md-9 flex-fill">
              <h3 class="h5 my-2">Especies de Árboles Legales y Recomendadas para Plantar en Cochabamba</h3>
              <p>
                <a href="https://es.wikipedia.org/wiki/Polylepis" target="_blank">Kewiña (Polylepis spp.)</a>
                <br>
                <a href="https://es.wikipedia.org/wiki/Alnus_acuminata" target="_blank">Aliso (Alnus acuminata)</a>
                <br>
                <a href="https://www.minsal.cl/portal/url/item/7d99ff5a580fdbd7e04001011f016dc3.pdf" target="_blank">Molle (Schinus molle)</a>
                <br>
                <a href="https://es.wikipedia.org/wiki/Cinchona_officinalis" target="_blank">Quina (Cinchona officinalis)</a>
                <br>
                <a href="https://ciudadesverdes.com/arboles/jacaranda-mimosifolia/" target="_blank">Tarco (Jacaranda mimosifolia)</a>
                <br>
                <a href="https://es.wikipedia.org/wiki/Buddleja_coriacea" target="_blank">Kari Kari (Buddleja coriacea)</a>
                <br>
                <a href="https://sib.gob.ar/especies/tipuana-tipu" target="_blank">Tipa (Tipuana tipu)</a>
              </p>
            </div>

          </div>
        </div>

        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
          <div class="d-flex">
            <div class="col-md-3 flex-fill pt-3 pt-3 pe-3 pe-md-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" class="bi bi-window-sidebar" viewbox="0 0 16 16">
                <path d="M2.5 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zm2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1 .5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v2H1V3a1 1 0 0 1 1-1h12zM1 13V6h4v8H2a1 1 0 0 1-1-1zm5 1V6h9v7a1 1 0 0 1-1 1H6z" />
              </svg>
            </div>
            <div class="col-md-9 flex-fill">
              <h3 class="h5 my-2">Seguimiento y Transparencia</h3>
              <p>
                Ofrecemos un sistema transparente donde pueden hacer un seguimiento del progreso de las
                plantaciones, proporcionando actualizaciones periódicas y
                datos detallados sobre las plantaciones realizadas.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="py-vh-5 w-100 overflow-hidden" id="numbers">
    <div class="container">
      <div class="row d-flex justify-content-between align-items-center">
        <div class="col-lg-5">
          <h3 class="py-5 border-top border-dark" data-aos="fade-right">
            Información Educativa
          </h3>
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-12">
              <h2 class="display-6 mb-5" data-aos="fade-down">
                Importancia de la Reforestación
              </h2>
            </div>
            <div class="col-lg-6" data-aos="fade-up">
              <div class="display-1 fw-bold py-4">80%</div>
              <p class="text-black-50">
                Los bosques albergan al menos el 80% de la biodiversidad
                terrestre, proporcionando hogar y refugio para innumerables
                especies de plantas y animales.
              </p>
            </div>
            <div class="col-lg-6" data-aos="fade-up">
              <div class="display-1 fw-bold py-4">26,000 millas</div>
              <p class="text-black-50">
                Un acre de árboles puede absorber el dióxido de carbono
                producido por un automóvil que recorre 26,000 millas.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-mapa">
    <div class="position-relative overflow-hidden bg-light" id="map" style="height: 500px; width: 100%;">
      <div class="map-legend">
        <h4>Categorías</h4>
        <div><span class="legend-icon protected"></span> Árboles Peligrosos</div>
        <div><span class="legend-icon native"></span> Árboles Nativos</div>
        <div><span class="legend-icon dangerous"></span> Árboles Protegidos</div>
      </div>
    </div>
  </div>




  <div class="container py-vh-4 w-100 overflow-hidden">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-lg-5">
        <h3 class="py-5 border-top border-dark" data-aos="fade-right">
          Completa el Formulario de Registro a continuacion:
        </h3>
      </div>
      <div class="col-md-7" data-aos="fade-left">
        <blockquote>
          <div class="fs-4 my-3 fw-light pt-4 border-bottom pb-3">
            ¿Quieres ser parte del cambio? Únete a
            ReforestLife. Tu participación es fundamental para hacer crecer
            la sostenibilidad y restaurar nuestro ecosistema. Completa
            nuestro formulario de registro para comenzar a marcar la
            diferencia
          </div>
          <img src="img/Plantar.jpg" width="94" height="64" class="img-fluid rounded-circle me-3" alt="" data-aos="fade" />
          <span><span class="fw-bold">SkyGreen</span> </span>
        </blockquote>
      </div>
    </div>
  </div>

  <div class="py-vh-6 bg-gray-900 text-light w-100 overflow-hidden" id="workwithus">
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="row d-flex justify-content-center text-center">
          <div class="carousel-container pt-2 pt-md-2" id="service">
            <h2 class="text-center" style="color: #e0e0e0">
              Formulario De Registro
            </h2>
            <p class="text-center" style="color: #e0e0e0">
              Por favor, complete el formulario a
              continuación:
            </p>
            <div class="carousel">
              <form id="fullForm" action="procesar_registro.php" method="post" onsubmit="return validarFormularioActual()">
                <div class="slide card p-4 custom-width" id="slide1" style="
                        display: block;

                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
                        background-color: #202529;
                        color: #e0e0e0;
                      ">
                  <!-- Contenido del primer formulario -->
                  <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required />
                  </div>
                  <div class="form-group">
                    <label for="apellido_paterno">Apellido Paterno:</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required />
                  </div>
                  <div class="form-group">
                    <label for="apellido_materno">Apellido Materno:</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required />
                  </div>
                  <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required />
                  </div>
                  <div class="form-group">
                    <label for="genero">Género:</label>
                    <select class="form-control" id="genero" name="genero" required>
                      <option value="masculino">Masculino</option>
                      <option value="femenino">Femenino</option>
                      <option value="otro">otro</option>
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
                  <!-- Contenido del segundo formulario -->
                  <div class="form-group">
                    <label for="documentos_identificacion">Carnet de Identificación:</label>
                    <input type="file" class="form-control-file" id="documentos_identificacion" name="documentos_identificacion" accept=".pdf, .jpg, .png" required />
                    <small>(Subir copia de cédula)</small>
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

                  <!-- Nuevos Campos de Documentación -->
                  <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required />
                  </div>
                  <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="text" class="form-control" id="contrasena" name="contrasena" required />
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
    </div>
  </div>

  <div class="container py-vh-3 border-top" data-aos="fade" data-aos-delay="200" id="testimonials">
    <div class="row d-flex justify-content-center">
      <div class="col-12 col-lg-8 text-center">
        <h3 class="fs-2 fw-light">
          Ingresa tu<span class="fw-bold"> correo electrónico</span> para
          proporcionarte más información
        </h3>
      </div>
      <div class="col-12 col-lg-8 text-center">
        <div class="row">
          <div class="grouped-inputs border bg-light p-2">
            <div class="row">
              <div class="col">
                <form action="interesados.php" method="post" class="form-floating">
                  <input type="email" name="email" class="form-control p-3" id="email" placeholder="name@example.com" required />
                  <div class="col-auto">
                    <br />
                    <button type="submit" class="btn btn-dark py-3 px-5">
                      Enviar
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <footer>
    <div class="container small border-top">
      <div class="row py-2 d-flex justify-content-between">
        <div class="text-secondary mt-3 col-12 col-lg-6 col-xl-3 border-end p-5">
          <strong class="h6 mb-3">SkyGreen<i class="bx bxs-tree-alt"></i></strong><br />

          <address class="text-secondary mt-3">
            "Cambiando la vida del mundo"
          </address>
          <ul class="nav flex-column"></ul>
        </div>
        <div class="text-secondary mt-3 col-12 col-lg-6 col-xl-3 border-end p-5">
          <h3 class="h6 mb-3">Facebook</h3>
          <address class="text-secondary mt-3">Siguenos en facebook:</address>
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
          <address class="text-secondary mt-3">Siguenos en WhatsApp:</address>
          <ul class="nav flex-column"></ul>
        </div>
      </div>
    </div>

    <div class="container text-center py-3 small">
      By
      <a href="https://github.com/Aless030" class="link-fancy" target="_blank">SkyGreen.com</a>
    </div>
  </footer>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/aos.js"></script>

  <!-- Script Mapbox -->
  <script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NpcyIsImEiOiJjbGcxbHBtbHQwdDU5M2RubDFodjY3a2x0In0.NXe43GdM4PJBj7ow0Dnkpw';

    // Configuración del mapa
    const map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/outdoors-v11', // Estilo del mapa
      center: [-66.158468, -17.374908],
      zoom: 17, // Nivel de zoom inicial
      pitch: 50, // Inclinación para vista en 3D
      bearing: -17.6 // Rotación del mapa
    });

    map.on('load', function() {
      // Añadir capa del parque Lincoln
      map.addLayer({
        'id': 'Unifranz',
        'type': 'fill',
        'source': {
          'type': 'geojson',
          'data': {
            'type': 'Feature',
            'geometry': {
              'type': 'Polygon',
              'coordinates': [
                [
                  [-66.157795, -17.374501], // Esquina superior izquierda (Cuarta)
                  [-66.159077, -17.374442], // Esquina superior derecha (Tercera)
                  [-66.159136, -17.375289], // Esquina inferior derecha (Segunda)
                  [-66.157803, -17.375348], // Esquina inferior izquierda (Primera)
                  [-66.157773, -17.374501] // Cerrar el polígono (repetir primera coordenada)
                ]
              ]
            }
          }
        },
        'layout': {},
        'paint': {
          'fill-color': '#617c1f', // Color del área del parque
          'fill-opacity': 0.2
        }
      });

      // Añadir edificios en 3D
      map.addLayer({
        'id': '3d-buildings',
        'source': 'composite',
        'source-layer': 'building',
        'filter': ['==', 'extrude', 'true'],
        'type': 'fill-extrusion',
        'minzoom': 15,
        'paint': {
          'fill-extrusion-color': '#ffffff', // Color de los edificios
          'fill-extrusion-height': [
            'interpolate', ['linear'],
            ['zoom'],
            15, 0,
            15.05, ['get', 'height']
          ],
          'fill-extrusion-opacity': 0.5
        }
      });

      // Ajuste de la iluminación
      map.setLight({
        anchor: 'viewport',
        color: '#ffffff', // Luz cálida
        intensity: 0.2
      });
    });

    // Obtener los datos de los árboles desde PHP
    const arboles = <?php echo json_encode($arboles); ?>;

    arboles.forEach(arbol => {
      // Convertir las coordenadas a formato [lng, lat]
      const coordinates = arbol.coordenadas.replace('POINT(', '').replace(')', '').split(' ');

      // Crear marcador personalizado con borde según el estado
      const el = document.createElement('div');
      el.className = 'tree-marker';

      // Asignar estilo de borde según el estado
      switch (arbol.estado.toLowerCase()) {
        case 'peligrosos':
          el.style.border = '3px solid red';
          break;
        case 'protegido':
          el.style.border = '3px solid yellow';
          break;
        case 'nativo':
          el.style.border = '3px solid green';
          break;
        default:
          el.style.border = '3px solid gray';
      }

      // Estilos del ícono del árbol
      el.style.backgroundImage = 'url("https://cdn2.iconfinder.com/data/icons/miscellaneous-iii-glyph-style/150/tree-512.png")';
      el.style.width = '30px';
      el.style.height = '30px';
      el.style.backgroundSize = 'cover';

      // Crear marcador en el mapa
      const marker = new mapboxgl.Marker(el)
        .setLngLat([parseFloat(coordinates[0]), parseFloat(coordinates[1])])
        .addTo(map);

      // Crear popup con información del árbol
      const popup = new mapboxgl.Popup({
          offset: 25
        })
        .setHTML(`
                <h3>${arbol.especie}</h3>
                <img src="${arbol.fotoUrl}" alt="Foto del árbol" style="width: 150px; height: 150px; object-fit: cover;"/>
                <p>Edad del Árbol: ${arbol.edad} años</p>
                <p>Altura: ${arbol.altura} metros</p>
                <p>Diámetro del Tronco: ${arbol.diametroTronco} cm</p>
                <p>Cuidados Necesarios: ${arbol.cuidados}</p>
                <p>Estado del Árbol: ${arbol.estado}</p>
                <img src="${arbol.qrUrl}" alt="QR del árbol" style="width: 100px; height: 100px;"/>
            `);

      // Asignar el popup al marcador
      marker.setPopup(popup);
    });
  </script>




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
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>