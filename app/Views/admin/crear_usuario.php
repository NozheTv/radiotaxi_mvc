<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="css/admin.css"> 
</head>
<body>
    <div class="login-container">
        <h2>Crear Usuario</h2>
        <a href="?controller=Admin&action=dashboard&section=usuarios" class="btn-crear">← Volver atrás</a>
        <form method="POST" action="?controller=Usuarios&action=crear">
            <label>Nombre completo</label>
            <input type="text" name="nombre_completo" required>

            <label>Correo</label>
            <input type="email" name="correo" required>

            <label>Contraseña</label>
            <input type="password" name="password" required>

            <label>Rol</label>
            <select name="rol" required>
                <option value="cliente">Cliente</option>
                <option value="conductor">Conductor</option>
            </select>

            <input type="submit" value="Crear">
        </form>
    </div>
</body>
</html>
