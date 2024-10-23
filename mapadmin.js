mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NpcyIsImEiOiJjbGcxbHBtbHQwdDU5M2RubDFodjY3a2x0In0.NXe43GdM4PJBj7ow0Dnkpw';

const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [-66.1704015, -17.3761244],
    zoom: 12
});

const arboles = [];

// Evento para agregar árboles al mapa
map.on('click', (e) => {
    const newArbol = {
        especie: prompt("Especie del Árbol"),
        edad: parseInt(prompt("Edad del Árbol")),
        cuidados: prompt("Cuidados Necesarios"),
        estado: prompt("Estado del Árbol (protegido, nativo, peligroso)"),
        fotoUrl: prompt("URL de la foto del Árbol"),
        altura: parseFloat(prompt("Altura del Árbol en metros")),
        diametroTronco: parseFloat(prompt("Diámetro del Tronco en cm")),
        coordenadas: [e.lngLat.lng, e.lngLat.lat]
    };

    arboles.push(newArbol);

    const el = document.createElement('div');
    el.className = 'marker';

    const marker = new mapboxgl.Marker(el)
        .setLngLat(newArbol.coordenadas)
        .addTo(map);

    const popup = new mapboxgl.Popup({ offset: 25, closeOnClick: false })
        .setHTML(`
            <h3>${newArbol.especie}</h3>
            <img src="${newArbol.fotoUrl}" alt="Foto del árbol" style="width: 150px; height: 150px; object-fit: cover;"/>
            <p>Edad del Árbol: ${newArbol.edad} años</p>
            <p>Altura: ${newArbol.altura} metros</p>
            <p>Diámetro del Tronco: ${newArbol.diametroTronco} cm</p>
            <p>Cuidados Necesarios: ${newArbol.cuidados}</p>
            <p>Estado del Árbol: ${newArbol.estado}</p>
        `);

    marker.setPopup(popup);

    marker.getElement().addEventListener('mouseenter', () => {
        marker.togglePopup();
    });
});
