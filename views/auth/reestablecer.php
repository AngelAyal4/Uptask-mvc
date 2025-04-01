<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if ($mostrar) : ?>
            <form class="formulario" method="POST">
                <div class="campo">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        placeholder="Tu Password"
                        name="password" />
                </div>
                <input type="submit" class="boton" value="Iniciar Sesion" />
            </form>
        <?php endif?>

        
        <div class="acciones">
            <a href="/crear">Aun no tienes una cuenta? Crea una nueva</a>
            <a href="/olvide">Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>