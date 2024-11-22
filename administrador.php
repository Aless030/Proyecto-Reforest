<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "reforest", 3308);

// Verifica si la biblioteca phpqrcode está disponible
require_once 'phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar datos del formulario
    $especie = $_POST['especie'];
    $edad = $_POST['edad'];
    $cuidados = $_POST['cuidados'];
    $estado = $_POST['estado'];
    $fotoUrl = $_POST['fotoUrl'];
    $altura = $_POST['altura'];
    $diametroTronco = $_POST['diametroTronco'];
    $coordenadas = "POINT(" . $_POST['lng'] . " " . $_POST['lat'] . ")";

    // Insertar el registro inicial sin QR
    $sql = "INSERT INTO arboles (especie, edad, cuidados, estado, fotoUrl, altura, diametroTronco, coordenadas, qrUrl) 
            VALUES ('$especie', $edad, '$cuidados', '$estado', '$fotoUrl', $altura, $diametroTronco, ST_GeomFromText('$coordenadas'), NULL)";

    if ($conn->query($sql)) {
        $lastId = $conn->insert_id; // Obtener el último ID insertado

        // Generar la URL única para este árbol
        $treeUrl = "https://es.wikipedia.org/wiki/Schinus_molle" . $lastId;

        // Generar el código QR y guardarlo en el servidor
        $qrFilename = 'qr_codes/qr_' . $lastId . '.png';
        QRcode::png($treeUrl, $qrFilename, QR_ECLEVEL_L, 4);

        // Actualizar el registro con la URL del QR
        $updateSql = "UPDATE arboles SET qrUrl = '$qrFilename' WHERE id = $lastId";
        $conn->query($updateSql);
    }
}

// Obtener todos los árboles de la base de datos para mostrarlos en el mapa
$sql = "SELECT especie, edad, cuidados, estado, fotoUrl, altura, diametroTronco, ST_AsText(coordenadas) as coordenadas, qrUrl FROM arboles";
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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css" rel="stylesheet" />
    <style>
        .tree-marker {
            background-image: url('https://cdn2.iconfinder.com/data/icons/miscellaneous-iii-glyph-style/150/tree-512.png');
            background-size: cover;
            width: 30px;
            height: 30px;
        }
    </style>
</head>

<body>
    <h1>Agregar Árbol</h1>
    <form id="arbolForm">
        <input type="text" name="especie" placeholder="Especie del Árbol" required />
        <input type="number" name="edad" placeholder="Edad del Árbol" required />
        <input type="text" name="cuidados" placeholder="Cuidados Necesarios" required />
        <select name="estado" required>
            <option value="">Selecciona una categoría</option>
            <option value="peligrosos">Peligrosos</option>
            <option value="protegido">Protegido</option>
            <option value="nativo">Nativo</option>
        </select>
        <input type="text" name="fotoUrl" placeholder="URL de la foto" required />
        <input type="number" step="0.1" name="altura" placeholder="Altura en metros" required />
        <input type="number" step="0.1" name="diametroTronco" placeholder="Diámetro en cm" required />
        <button type="button" onclick="confirmarUbicacion()">Confirmar Ubicación</button>
        <button type="button" id="agregarArbolBtn" disabled onclick="obtenerCoordenadas()">Agregar Árbol</button>
    </form>

    <div id="map" style="width: 100%; height: 500px;"></div>

    <script>
        // Mapbox token
        mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NpcyIsImEiOiJjbGcxbHBtbHQwdDU5M2RubDFodjY3a2x0In0.NXe43GdM4PJBj7ow0Dnkpw';

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [-66.158468, -17.374908],
            zoom: 17, // Nivel de zoom inicial
            pitch: 50, // Inclinación para vista en 3D
            bearing: -17.6 // Rotación del mapa
        });

        let marker, lng, lat;

        map.on('load', function() {
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
                    'fill-color': '#a3dde8', // Color del área del parque
                    'fill-opacity': 0.5
                }
            });
            const arboles = <?php echo json_encode($arboles); ?>;
            arboles.forEach(arbol => {
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

                // Crear marcador en el mapa
                const marker = new mapboxgl.Marker(el)
                    .setLngLat([parseFloat(coordinates[0]), parseFloat(coordinates[1])])
                    .addTo(map);

                // Popup con información del árbol
                const popup = new mapboxgl.Popup({
                        offset: 25
                    })
                    .setHTML(`
                    <h3>${arbol.especie}</h3>
                    <img src="${arbol.fotoUrl}" alt="Foto del árbol" style="width: 150px; height: 150px; object-fit: cover;" />
                    <p>Edad del Árbol: ${arbol.edad} años</p>
                    <p>Altura: ${arbol.altura} metros</p>
                    <p>Diámetro del Tronco: ${arbol.diametroTronco} cm</p>
                    <p>Cuidados Necesarios: ${arbol.cuidados}</p>
                    <p>Categoría: ${arbol.estado}</p>
                    <img src="${arbol.qrUrl}" alt="QR del árbol" style="width: 100px; height: 100px;" />
                `);

                marker.setPopup(popup);
            });
        });
        map.on('click', (e) => {
            // Obtener las coordenadas del clic
            lng = e.lngLat.lng;
            lat = e.lngLat.lat;

            // Crear o mover el marcador
            if (marker) {
                marker.setLngLat(e.lngLat); // Mover el marcador existente
            } else {
                marker = new mapboxgl.Marker({
                        draggable: true
                    }) // Crear un marcador nuevo
                    .setLngLat(e.lngLat)
                    .addTo(map);

                // Habilitar la funcionalidad de arrastrar y actualizar las coordenadas
                marker.on('dragend', function() {
                    const lngLat = marker.getLngLat();
                    lng = lngLat.lng;
                    lat = lngLat.lat;
                });
            }


        });

        // Confirmar la ubicación seleccionada
        function confirmarUbicacion() {
            if (lng && lat) {
                document.getElementById('agregarArbolBtn').disabled = false; // Habilitar el botón
                alert('Ubicación confirmada');
            } else {
                alert('Por favor selecciona una ubicación en el mapa.');
            }
        }

        // Confirmar ubicación
        function confirmarUbicacion() {
            if (lng && lat) {
                document.getElementById('agregarArbolBtn').disabled = false;
                alert('Ubicación confirmada');
            } else {
                alert('Por favor selecciona una ubicación en el mapa.');
            }
        }

        // Enviar datos del formulario
        function obtenerCoordenadas() {
            const form = document.getElementById('arbolForm');
            const formData = new FormData(form);
            formData.append('lng', lng);
            formData.append('lat', lat);

            fetch('administrador.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                alert('Árbol agregado con éxito');
                location.reload();
            }).catch(error => {
                console.error(error);
                alert('Error al agregar el árbol');
            });
        }
    </script>
</body>

</html>