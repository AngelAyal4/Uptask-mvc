<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
        <form action="/" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    placeholder="Tu Nombre"
                    name="nombre" />
            </div>
            <div class="campo">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    placeholder="Tu E-mail"
                    name="email" />
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    placeholder="Tu Password"
                    name="password" />
            </div>
            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input
                    type="password"
                    id="password2"
                    placeholder="Nuevamente Tu Password"
                    name="password" />
            </div>
            <input type="submit" class="boton" value="Crear Cuenta" />
        </form>
        <div class="acciones">
            <a href="/">Ya tienes cuenta? Inicia Sesion</a>
            <a href="/olvide">Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>