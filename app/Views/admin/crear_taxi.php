<h2>Agregar Nuevo Taxi</h2>
<form method="POST" action="?controller=Taxis&action=crear">
    <label>Placa</label><br>
    <input type="text" name="placa" required><br>
    <label>Modelo</label><br>
    <input type="text" name="modelo" required><br>
    <label>Estado</label><br>
    <select name="estado">
        <option value="disponible">Disponible</option>
        <option value="no disponible">No Disponible</option>
    </select><br>
    <label>Conductor (opcional)</label><br>
    <select name="id_conductor">
        <option value="">-- Ninguno --</option>
        <?php foreach($conductores as $conductor): ?>
            <option value="<?= $conductor['id'] ?>"><?= htmlspecialchars($conductor['nombre_completo']) ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <button type="submit">Guardar</button>
</form>
