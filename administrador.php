<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "reforest", 3308);

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

    // Insertar en la base de datos
    $sql = "INSERT INTO arboles (especie, edad, cuidados, estado, fotoUrl, altura, diametroTronco, coordenadas) 
            VALUES ('$especie', $edad, '$cuidados', '$estado', '$fotoUrl', $altura, $diametroTronco, ST_GeomFromText('$coordenadas'))";
    $conn->query($sql);
}

// Obtener todos los árboles de la base de datos para mostrarlos en el mapa
$sql = "SELECT especie, edad, cuidados, estado, fotoUrl, altura, diametroTronco, ST_AsText(coordenadas) as coordenadas FROM arboles";
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
        <input type="text" name="estado" placeholder="Estado del Árbol" required />
        <input type="text" name="fotoUrl" placeholder="URL de la foto" required />
        <input type="number" step="0.1" name="altura" placeholder="Altura en metros" required />
        <input type="number" step="0.1" name="diametroTronco" placeholder="Diámetro en cm" required />
        <button type="button" onclick="confirmarUbicacion()">Confirmar Ubicación</button>
        <button type="button" id="agregarArbolBtn" disabled onclick="obtenerCoordenadas()">Agregar Árbol</button>
    </form>

    <div id="map" style="width: 100%; height: 500px;"></div>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NpcyIsImEiOiJjbGcxbHBtbHQwdDU5M2RubDFodjY3a2x0In0.NXe43GdM4PJBj7ow0Dnkpw';

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [-66.1704015, -17.3761244],
            zoom: 12
        });

        let marker, lng, lat;

        // Enmarcar la zona del parque Lincoln
        map.on('load', function() {
            map.addLayer({
                'id': 'parque-lincoln',
                'type': 'fill',
                'source': {
                    'type': 'geojson',
                    'data': {
                        'type': 'Feature',
                        'geometry': {
                            'type': 'Polygon',
                            'coordinates': [
                                [
                                    [-66.175513, -17.369674],
                                    [-66.175535, -17.369234],
                                    [-66.165085, -17.370074],
                                    [-66.165106, -17.370586],
                                    [-66.175513, -17.369674]
                                ]
                            ]
                        }
                    }
                },
                'layout': {},
                'paint': {
                    'fill-color': '#008000',
                    'fill-opacity': 0.5
                }
            });

            // Mostrar los árboles existentes en el mapa
            const arboles = <?php echo json_encode($arboles); ?>;
            arboles.forEach(arbol => {
                // Convertir las coordenadas de formato 'POINT(lng lat)' a un array [lng, lat]
                const coordinates = arbol.coordenadas.replace('POINT(', '').replace(')', '').split(' ');

                // Crear un div personalizado para el ícono de árbol
                const el = document.createElement('div');
                el.className = 'tree-marker';

                // Crear marcador en el mapa con el ícono personalizado
                const marker = new mapboxgl.Marker(el)
                    .setLngLat([parseFloat(coordinates[0]), parseFloat(coordinates[1])])
                    .addTo(map);

                // Crear popup con los datos del árbol
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
                    `);

                // Asignar popup al marcador
                marker.setPopup(popup);
            });
        });

        map.on('click', (e) => {
            lng = e.lngLat.lng;
            lat = e.lngLat.lat;

            // Si ya hay un marcador, lo movemos
            if (marker) {
                marker.setLngLat(e.lngLat);
            } else {
                // Crear nuevo marcador y hacerlo movible
                marker = new mapboxgl.Marker({
                    draggable: true
                }).setLngLat(e.lngLat).addTo(map);
            }

            // Actualizar las coordenadas cuando se mueva el marcador
            marker.on('dragend', function() {
                const lngLat = marker.getLngLat();
                lng = lngLat.lng;
                lat = lngLat.lat;
            });
        });

        // Función para confirmar la ubicación
        function confirmarUbicacion() {
            if (lng && lat) {
                document.getElementById('agregarArbolBtn').disabled = false;
                alert('Ubicación confirmada');
            } else {
                alert('Por favor selecciona una ubicación en el mapa.');
            }
        }

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
                form.reset();
                document.getElementById('agregarArbolBtn').disabled = true;
                if (marker) {
                    marker.remove(); // Remover el marcador del mapa
                    marker = null; // Resetear la variable
                }
            });
        }
    </script>
</body>

</html>
