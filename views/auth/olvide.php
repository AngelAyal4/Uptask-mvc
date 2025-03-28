<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Introduce tu E-mail y Recupera Tu Contraseña</p>
        <form action="/olvide" class="formulario" method="POST">
            <div class="campo">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    placeholder="Tu E-mail"
                    name="email" />
            </div>
            <input type="submit" class="boton" value="Recuperar Contraseña" />
        </form>
        <div class="acciones">
            <a href="/crear">Aun no tienes una cuenta? Crea una nueva</a>
        </div>
    </div>
</div>