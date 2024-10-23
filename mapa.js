mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NpcyIsImEiOiJjbGcxbHBtbHQwdDU5M2RubDFodjY3a2x0In0.NXe43GdM4PJBj7ow0Dnkpw';

const map = new mapboxgl.Map({
  container: 'map',
  style: 'mapbox://styles/mapbox/streets-v11',
  center: [-66.1704015, -17.3761244],
  zoom: 12
});

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
          'coordinates': [[
            [-66.175513, -17.369674],
            [-66.175535, -17.369234],
            [-66.165085, -17.370074],
            [-66.165106, -17.370586],
            [-66.175513, -17.369674]
          ]]
        }
      }
    },
    'layout': {},
    'paint': {
      'fill-color': '#008000',
      'fill-opacity': 0.5
    }
  });
});

const arboles = [];

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

  const popup = new mapboxgl.Popup({ offset: 25, closeOnClick: false }) // No se cierra al hacer click fuera
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

  // Mostrar popup al pasar el cursor sobre el marcador
  marker.getElement().addEventListener('mouseenter', () => {
    marker.togglePopup();
  });

  // No se cierra el popup automáticamente
});

const markerStyles = `
  .marker {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #008000;
    border: 2px solid #fff;
    cursor: pointer;
  }

  .marker:hover {
    background-color: #006400;
  }
`;

const style = document.createElement('style');
style.textContent = markerStyles;
document.head.appendChild(style);
