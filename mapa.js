mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlc3NpcyIsImEiOiJjbGcxbHBtbHQwdDU5M2RubDFodjY3a2x0In0.NXe43GdM4PJBj7ow0Dnkpw';

const map = new mapboxgl.Map({
  container: 'map',
  style: 'mapbox://styles/mapbox/streets-v11',
  center: [-66.1568, -17.3886],
  zoom: 12
});

// Añadir capa de superposición para mostrar tráfico
map.on('load', () => {
  map.addSource('traffic', {
    type: 'vector',
    url: 'mapbox://mapbox.mapbox-traffic-v1'
  });

  map.addLayer({
    id: 'traffic-layer',
    type: 'line',
    source: 'traffic',
    'source-layer': 'traffic',
    paint: {
      'line-color': [
        'interpolate',
        ['linear'],
        ['get', 'congestion'],
        0, 'green',
        0.4, 'yellow',
        0.6, 'orange',
        0.8, 'red'
      ],
      'line-width': 2
    }
  });
});
