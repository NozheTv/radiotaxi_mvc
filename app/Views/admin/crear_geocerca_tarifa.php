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
    <form method="POST">
        <label>Nombre de zona:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Tarifa fija (Bs):</label><br>
        <input type="number" step="0.01" name="tarifa" required><br><br>

        <input type="hidden" id="poligono_geojson" name="poligono_geojson">

        <div id="map"></div><br>
        <button type="submit">Guardar geocerca</button>
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

        map.on(L.Draw.Event.CREATED, e => {
            drawnItems.clearLayers();
            drawnItems.addLayer(e.layer);
            document.getElementById('poligono_geojson').value = JSON.stringify(e.layer.toGeoJSON().geometry);
        });
    </script>
</body>
</html>
