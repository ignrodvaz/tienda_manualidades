<!doctype html>
<html lang="en">
    <head>
        <title>Panel Login</title>
        <!-- Configuración básica de la página -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Enlace a Bootstrap CSS v5.3.2 para estilos predefinidos -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <!-- Enlace al archivo CSS personalizado -->
        <link rel="stylesheet" href="./css/style.css">
        <!-- Enlace al archivo JavaScript personalizado -->
        <script src="./js/main.js"></script>
    </head>

    <body>
        <header>
            <!-- Espacio reservado para la barra de navegación -->
        </header>
        <main>
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>
            <!-- FORMULARIO DE INICIO DE SESIÓN -->
            <form action="<?= base_url('login/authenticate') ?>" method="post">
                <!-- Sección del formulario de inicio de sesión visible por defecto -->
                <div id="login-section" class="d-flex justify-content-center align-items-center vh-100">
                    <div class="card w-25">
                        <!-- Encabezado del formulario -->
                        <div class="card-header bg-primary text-center">
                            <h1 id="inicioSesion">Inicio de Sesión</h1>
                        </div>
                        <div class="card-body">
                            <!-- Campo de correo electrónico -->
                            <label class="form-label" for="email">Correo Electrónico</label>
                            <input class="w-100 mb-3 form-control" type="email" placeholder="Ejemplo@correo.com" name="email" id="email" required>

                            <!-- Campo de contraseña -->
                            <label class="form-label" for="password">Contraseña</label>
                            <input class="w-100 mb-3 form-control" type="password" placeholder="Tu contraseña" name="password" id="password" required>

                            <!-- Botón para iniciar sesión -->
                            <button id="btnInicioSesion" type="submit" class="btn btn-success text-center w-100 mb-2">Iniciar sesión</button>

                            <!-- Enlace para cambiar al formulario de registro -->
                            <p class="text-center">¿Aun no tienes cuenta? <a id="showRegistro" href="register">Regístrate</a></p>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Botón para limpiar el almacenamiento local -->
            <button id="clearLocalStorage">Limpiar</button>
        </main>
        <footer>
            <!-- Espacio reservado para el pie de página -->
        </footer>

        <!-- Bootstrap JavaScript Libraries -->
        <!-- Biblioteca Popper.js, necesaria para elementos dinámicos de Bootstrap -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <!-- Bootstrap JavaScript -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
