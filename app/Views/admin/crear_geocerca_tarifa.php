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
    <style>
        #map { height: 500px; }
        form { max-width: 600px; margin: auto; }
        label { font-weight: bold; }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 6px;
            margin-top: 4px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #error-msg { color: red; display: none; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Crear nueva geocerca</h2>

<form id="form-geocerca" method="POST" novalidate>
    <label for="nombre">Nombre de zona:</label><br>
    <input 
        type="text" 
        id="nombre" 
        name="nombre" 
        required 
        maxlength="100" 
        minlength="3"
        placeholder="Ejemplo: Zona Centro"><br>

    <label for="tarifa">Tarifa fija (Bs):</label><br>
    <input 
        type="number" 
        id="tarifa" 
        name="tarifa" 
        step="0.01" 
        min="0" 
        max="9999.99"
        required 
        placeholder="Ejemplo: 5.00"><br>

    <input type="hidden" id="poligono_geojson" name="poligono_geojson">

    <div id="map" style="height: 500px; border: 1px solid #ccc;"></div><br>

    <button type="button" id="btn-clear">🗑️ Limpiar polígono</button>
    <button type="submit">Guardar geocerca</button>

    <p id="error-msg"></p>
</form>

<script>
    // Inicializar mapa
    const map = L.map('map').setView([-16.65, -68.3], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        draw: { polygon: true, marker: false, polyline: false, rectangle: false, circle: false },
        edit: { featureGroup: drawnItems }
    });
    map.addControl(drawControl);

    let poligonoGeoJSON = '';

    map.on(L.Draw.Event.CREATED, e => {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        poligonoGeoJSON = JSON.stringify(e.layer.toGeoJSON().geometry);
        document.getElementById('poligono_geojson').value = poligonoGeoJSON;
    });

    map.on('draw:edited', e => {
        e.layers.eachLayer(layer => {
            poligonoGeoJSON = JSON.stringify(layer.toGeoJSON().geometry);
            document.getElementById('poligono_geojson').value = poligonoGeoJSON;
        });
    });

    document.getElementById('btn-clear').addEventListener('click', () => {
        drawnItems.clearLayers();
        document.getElementById('poligono_geojson').value = '';
        poligonoGeoJSON = '';
    });

    // Validación antes de enviar
    document.getElementById('form-geocerca').addEventListener('submit', e => {
        const nombre = document.getElementById('nombre').value.trim();
        const tarifa = document.getElementById('tarifa').value.trim();
        const poligono = document.getElementById('poligono_geojson').value;
        const errorMsg = document.getElementById('error-msg');

        errorMsg.style.display = 'none';
        errorMsg.textContent = '';

        // Validar nombre
        if (nombre === '') {
            e.preventDefault();
            errorMsg.textContent = '⚠️ El nombre de la zona no puede estar vacío.';
            errorMsg.style.display = 'block';
            return;
        }
        if (nombre.length < 3 || nombre.length > 100) {
            e.preventDefault();
            errorMsg.textContent = '⚠️ El nombre debe tener entre 3 y 100 caracteres.';
            errorMsg.style.display = 'block';
            return;
        }

        // Validar tarifa
        if (tarifa === '' || isNaN(tarifa)) {
            e.preventDefault();
            errorMsg.textContent = '⚠️ La tarifa es obligatoria y debe ser numérica.';
            errorMsg.style.display = 'block';
            return;
        }
        const valor = parseFloat(tarifa);
        if (valor < 0 || valor > 9999.99) {
            e.preventDefault();
            errorMsg.textContent = '⚠️ La tarifa debe estar entre 0 y 9999.99 Bs.';
            errorMsg.style.display = 'block';
            return;
        }

        // Validar polígono
        if (!poligono) {
            e.preventDefault();
            errorMsg.textContent = '⚠️ Debes dibujar la geocerca en el mapa antes de guardar.';
            errorMsg.style.display = 'block';
            return;
        }

        // Si todo es válido, el formulario se envía
    });
</script>

</body>
</html>
