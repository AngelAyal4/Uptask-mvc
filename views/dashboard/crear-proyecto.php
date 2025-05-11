<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
    <form class="formulario" method = "POST" action="/crear-proyecto">
        <legend>Crear Proyecto</legend>
        <div class="campo">
            <label for="nombre">Nombre Proyecto</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre del Proyecto">
        </div>
        <div class="campo">
            <label for="url">URL Proyecto</label>
            <input type="text" id="url" name="url" placeholder="URL del Proyecto">
        </div>
    <?php include_once __DIR__ . '/formulario-proyecto.php'; ?>
        <input type="submit" value = "Crear Proyecto" class="boton boton-verde">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>