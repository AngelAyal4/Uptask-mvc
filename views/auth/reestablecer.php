<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <form action="/reestablecer" class="formulario" method="POST">
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
            <input type="submit" class="boton" value="Iniciar Sesion" />
        </form>
        <div class="acciones">
            <a href="/crear">Aun no tienes una cuenta? Crea una nueva</a>
            <a href="/olvide">Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>