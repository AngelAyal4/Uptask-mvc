<?php include_once __DIR__ . '/header-dashboard.php'; ?>
<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar Password</a>

    <form method="POST" class="formulario" action="/perfil">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input 
            type="text"
            value="<?php echo s($usuario->nombre); ?>"
            name="nombre"
            placerholder="Tu Nombre"
            />
        </div>
        <div class="campo">
            <label for="nombre">Email</label>
            <input 
            type="email"
            value="<?php echo s($usuario->email); ?>"
            name="email"
            placerholder="Tu Email"
            />
        </div>
        <input type="submit" class="boton" value="Guardar Cambios"/>
    </form>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php'; ?>