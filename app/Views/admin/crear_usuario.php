<h2>Crear Usuario</h2>
<form method="POST" action="?controller=Usuarios&action=crear">
    <label>Nombre completo</label><br>
    <input type="text" name="nombre_completo" required><br>
    <label>Correo</label><br>
    <input type="email" name="correo" required><br>
    <label>Contraseña</label><br>
    <input type="password" name="password" required><br>
    <label>Rol</label><br>
    <select name="rol" required>
        <option value="cliente">Cliente</option>
        <option value="conductor">Conductor</option>
    </select><br><br>
    <button type="submit">Crear</button>
</form>
