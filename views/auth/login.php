<div class="contenedor">
    <h1>Uptask</h1>
    <p>Crea y adminstra tus proyecto</p>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Inicia Sesion</p>
        <form action="/" class="formulario" method="POST">
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
            <input type="submit" class="boton" value="Iniciar Sesion" />
        </form>
        <div class="acciones">
            <a href="/crear">Aun no tienes una cuenta? Crea una nueva</a>
            <a href="/olvide">Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>