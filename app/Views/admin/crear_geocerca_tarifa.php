<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Geocerca</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <link rel="stylesheet" href="css/admin.css"> 
    <style>#map { height: 500px; }</style>
</head>
<body>
    <h2>Crear nueva geocerca</h2>
    <form id="form-geocerca" method="POST" novalidate>
    <label for="nombre">Nombre de zona:</label><br>
    <input type="text" id="nombre" name="nombre" required placeholder="Ejemplo: Zona Centro"><br><br>

    <label for="tarifa">Tarifa fija (Bs):</label><br>
    <input type="number" id="tarifa" name="tarifa" step="0.01" min="0" required placeholder="Ejemplo: 5.00"><br><br>

    <input type="hidden" id="poligono_geojson" name="poligono_geojson">

    <div id="map" style="height: 500px; border: 1px solid #ccc;"></div><br>

    <button type="button" id="btn-clear">🗑️ Limpiar polígono</button>
    <button type="submit">Guardar geocerca</button>

    <p id="error-msg" style="color: red; display: none; margin-top: 10px;"></p>
</form>

    <script>
    const map = L.map('map').setView([-16.65, -68.3], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        draw: { polygon: true, marker: false, polyline: false, rectangle: false, circle: false },
        edit: { featureGroup: drawnItems }
    });
    map.addControl(drawControl);

    // Almacena el polígono actual (para validar)
    let poligonoGeoJSON = '';

    map.on(L.Draw.Event.CREATED, e => {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);

        poligonoGeoJSON = JSON.stringify(e.layer.toGeoJSON().geometry);
        document.getElementById('poligono_geojson').value = poligonoGeoJSON;
    });

    // También actualizar el campo si el usuario edita el polígono
    map.on('draw:edited', e => {
        e.layers.eachLayer(layer => {
            poligonoGeoJSON = JSON.stringify(layer.toGeoJSON().geometry);
            document.getElementById('poligono_geojson').value = poligonoGeoJSON;
        });
    });

    // Validar al enviar formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', e => {
        if (!document.getElementById('poligono_geojson').value) {
            e.preventDefault();
            alert('Por favor, dibuja la geocerca en el mapa antes de guardar.');
        }
    });
</script>

</body>
</html>
