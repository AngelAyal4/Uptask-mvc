<?php include_once __DIR__ . '/header-dashboard.php'; ?>
<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al perfil</a>

    <form method="POST" class="formulario" action="/cambiar-password">
        <div class="campo">
            <label for="nombre">Password Actual</label>
            <input 
            type="password"
            name="password_actual"
            placerholder="Tu Password Actual"
            />
        </div>
        <div class="campo">
            <label for="nombre">Nuevo Password</label>
            <input 
            type="password"
            name="password_nuevo"
            placerholder="Tu Password Nuevo"
            />
        </div>
        <input type="submit" class="boton" value="Guardar Cambios"/>
    </form>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php'; ?>