<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Geocercas y Taxis</title>
  <link rel="stylesheet" href="css/admin.css" /> 
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map { height: 500px; }
    body { font-family: Arial, sans-serif; }
    .btn-crear {
      display:inline-block;
      margin-bottom:10px;
      padding:8px 12px;
      background:#007bff;
      color:#fff;
      text-decoration:none;
      border-radius:4px;
    }
  </style>
</head>
<body>

<h2>Mapa de Geocercas y Taxis</h2>
<a href="?controller=GeocercasTarifa&action=crear" class="btn-crear">➕ Nueva geocerca</a>
<a href="?controller=Admin&action=dashboard&section=usuarios" class="btn-crear">← Volver atrás</a>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-pip/leaflet-pip.min.js"></script>

<script>
  const map = L.map('map').setView([-16.65, -68.3], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // --- Mostrar geocercas con colores y bordes --- 
  const geocercas = <?= json_encode($geocercas) ?>;
  const zonas = [];

  // Grupo para todas las geocercas
  const groupZonas = L.layerGroup().addTo(map);

  // Paleta de colores suaves (puedes agregar más)
  const colores = [
      '#1E90FF', '#FF6347', '#32CD32', '#FFD700', '#BA55D3', '#FF8C00', '#00CED1', '#DC143C'
  ];

  geocercas.forEach((g, i) => {
      try {
          const poligono = JSON.parse(g.poligono_geojson);
          const color = colores[i % colores.length]; // alterna colores

          const polygon = L.polygon(
              poligono.coordinates[0].map(p => [p[1], p[0]]),
              {
                  color: 'white',          // borde blanco
                  weight: 2,               // grosor del borde
                  fillColor: color,        // color de relleno
                  fillOpacity: 0.4,        // transparencia del color
              }
          ).addTo(map);

          polygon.bindPopup(`
              <b>${g.nombre_zona}</b><br>
              Tarifa fija: <b>Bs ${g.tarifa_fija}</b>
          `);

          zonas.push({
              nombre: g.nombre_zona,
              tarifa: g.tarifa_fija,
              shape: polygon
          });

          groupZonas.addLayer(polygon); // Añadimos al grupo
      } catch (e) {
          console.error("Error cargando geocerca:", g.nombre_zona, e);
      }
  });

  const iconoLibre = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/3097/3097144.png',
      iconSize: [30, 30],
      iconAnchor: [15, 30],
      popupAnchor: [0, -30]
  });
  const iconoOcupado = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/3097/3097195.png',
      iconSize: [30, 30],
      iconAnchor: [15, 30],
      popupAnchor: [0, -30]
  });
  const iconoFuera = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/3097/3097138.png',
      iconSize: [30, 30],
      iconAnchor: [15, 30],
      popupAnchor: [0, -30]
  });
  const iconoEspecial = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/190/190411.png', // Icono para taxi más cercano
      iconSize: [35, 35],
      iconAnchor: [17, 35],
      popupAnchor: [0, -35]
  });

  // Estructuras de datos
  class Pila {
    constructor() {
      this.items = [];
    }
    push(element) {
      this.items.push(element);
    }
    pop() {
      return this.items.pop();
    }
    peek() {
      return this.items[this.items.length - 1];
    }
    isEmpty() {
      return this.items.length === 0;
    }
    size() {
      return this.items.length;
    }
  }

  class Cola {
    constructor() {
      this.items = [];
    }
    enqueue(element) {
      this.items.push(element);
    }
    dequeue() {
      return this.items.shift();
    }
    front() {
      return this.items[0];
    }
    isEmpty() {
      return this.items.length === 0;
    }
    size() {
      return this.items.length;
    }
    reverse() {
      this.items.reverse();
    }
  }

  const markers = {};

  // Función para calcular distancia Euclidiana (puedes mejorar con Haversine)
  function obtenerDistancia(coord1, coord2) {
    const latDiff = coord1[0] - coord2[0];
    const lngDiff = coord1[1] - coord2[1];
    return Math.sqrt(latDiff * latDiff + lngDiff * lngDiff);
  }

  async function cargarTaxis() {
    console.log("Cargando taxis...");
    try {
      const response = await fetch('?controller=ApiTaxis&action=obtenerTaxis');
      const taxis = await response.json();
      console.log("Taxis recibidos:", taxis);

      // Limpiar markers anteriores del mapa y objeto markers
      for (const id in markers) {
        map.removeLayer(markers[id]);
        delete markers[id];
      }

      // Posición del usuario fija para este ejemplo (puedes actualizarla dinámicamente)
      const ubicacionUsuario = [-16.65, -68.3];

      // Separar taxis
      const taxisLibres = taxis.filter(t => t.estado && t.estado.toLowerCase() === 'libre');
      const taxisEnEspera = taxis.filter(t => t.estado && t.estado.toLowerCase() === 'espera');
      const taxisOtros = taxis.filter(t => !['libre', 'espera'].includes(t.estado?.toLowerCase()));

      // Crear pila para taxis libres ordenados por cercanía
      const pilaTaxis = new Pila();
      taxisLibres
        .sort((a, b) => obtenerDistancia([a.gps_latitud, a.gps_longitud], ubicacionUsuario) -
                       obtenerDistancia([b.gps_latitud, b.gps_longitud], ubicacionUsuario))
        .forEach(taxi => pilaTaxis.push(taxi));

      // Crear cola para taxis en espera
      const colaTaxis = new Cola();
      taxisEnEspera.forEach(taxi => colaTaxis.enqueue(taxi));
      colaTaxis.reverse(); // Invertimos para que el último en espera sea el primero recomendado

      // Mostrar taxi más cercano (último en la pila)
      if (!pilaTaxis.isEmpty()) {
        const taxiCercano = pilaTaxis.pop();
        const pos = [parseFloat(taxiCercano.gps_latitud), parseFloat(taxiCercano.gps_longitud)];

        // Determinar zona geográfica y tarifa
        let zonaActual = 'Fuera de zona';
        let tarifaZona = 0;
        const capas = leafletPip.pointInLayer(pos, groupZonas);
        if (capas.length > 0) {
          const poligonoEncontrado = capas[0];
          const zonaEncontrada = zonas.find(z => z.shape === poligonoEncontrado);
          if (zonaEncontrada) {
            zonaActual = zonaEncontrada.nombre;
            tarifaZona = zonaEncontrada.tarifa;
          }
        }

        const popup = `
          <b>Taxi más cercano</b><br>
          <b>Taxi:</b> ${taxiCercano.placa}<br>
          <b>Conductor:</b> ${taxiCercano.conductor}<br>
          <b>Modelo:</b> ${taxiCercano.modelo}<br>
          <b>Estado:</b> ${taxiCercano.estado}<br>
          <b>Zona:</b> ${zonaActual}<br>
          <b>Tarifa fija:</b> Bs ${tarifaZona}<br>
        `;

        const marker = L.marker(pos, { icon: iconoEspecial }).addTo(map).bindPopup(popup);
        markers[taxiCercano.id] = marker;
      }

      // Mostrar taxis recomendados (cola invertida)
      colaTaxis.items.forEach(taxi => {
        const pos = [parseFloat(taxi.gps_latitud), parseFloat(taxi.gps_longitud)];

        // Zona y tarifa
        let zonaActual = 'Fuera de zona';
        let tarifaZona = 0;
        const capas = leafletPip.pointInLayer(pos, groupZonas);
        if (capas.length > 0) {
          const poligonoEncontrado = capas[0];
          const zonaEncontrada = zonas.find(z => z.shape === poligonoEncontrado);
          if (zonaEncontrada) {
            zonaActual = zonaEncontrada.nombre;
            tarifaZona = zonaEncontrada.tarifa;
          }
        }

        const popup = `
          <b>Taxi recomendado (en espera)</b><br>
          <b>Taxi:</b> ${taxi.placa}<br>
          <b>Conductor:</b> ${taxi.conductor}<br>
          <b>Modelo:</b> ${taxi.modelo}<br>
          <b>Estado:</b> ${taxi.estado}<br>
          <b>Zona:</b> ${zonaActual}<br>
          <b>Tarifa fija:</b> Bs ${tarifaZona}<br>
        `;

        const marker = L.marker(pos, { icon: iconoLibre }).addTo(map).bindPopup(popup);
        markers[taxi.id] = marker;
      });

      // Mostrar el resto de taxis (ocupados o fuera de zona)
      taxisOtros.forEach(taxi => {
        const pos = [parseFloat(taxi.gps_latitud), parseFloat(taxi.gps_longitud)];

        // Zona y tarifa
        let zonaActual = 'Fuera de zona';
        let tarifaZona = 0;
        const capas = leafletPip.pointInLayer(pos, groupZonas);
        if (capas.length > 0) {
          const poligonoEncontrado = capas[0];
          const zonaEncontrada = zonas.find(z => z.shape === poligonoEncontrado);
          if (zonaEncontrada) {
            zonaActual = zonaEncontrada.nombre;
            tarifaZona = zonaEncontrada.tarifa;
          }
        }

        // Icono según estado
        let icono = iconoFuera;
        if (taxi.estado?.toLowerCase() === 'ocupado') icono = iconoOcupado;

        const popup = `
          <b>Taxi:</b> ${taxi.placa}<br>
          <b>Conductor:</b> ${taxi.conductor}<br>
          <b>Modelo:</b> ${taxi.modelo}<br>
          <b>Estado:</b> ${taxi.estado}<br>
          <b>Zona:</b> ${zonaActual}<br>
          <b>Tarifa fija:</b> Bs ${tarifaZona}<br>
        `;

        const marker = L.marker(pos, { icon: icono }).addTo(map).bindPopup(popup);
        markers[taxi.id] = marker;
      });

    } catch (error) {
      console.error('Error al cargar taxis:', error);
    }
  }

  cargarTaxis();
  setInterval(cargarTaxis, 5000);
</script>


</body>
</html>
