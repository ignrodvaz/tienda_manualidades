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

            <!-- FORMULARIO DE REGISTRO -->
            <form id="formRegistro">
                <!-- Sección del formulario de registro oculta por defecto -->
                <div id="registro-section" class="d-flex justify-content-center align-items-center vh-100 d-none">
                    <div class="card w-25">
                        <!-- Encabezado del formulario -->
                        <div class="card-header bg-primary text-center">
                            <h1 id="registro">Registro</h1>
                        </div>
                        <div class="card-body">
                            <!-- Campo de correo electrónico -->
                            <label class="form-label" for="email-registro">Correo Electrónico</label>
                            <input class="w-100 mb-3 form-control" type="email" placeholder="Ejemplo@correo.com" id="email-registro" required>
                            <!-- Mensaje de error para el correo -->
                            <div id="emailError" class="text-danger mt-2"></div>

                            <!-- Campo de contraseña -->
                            <label class="form-label" for="password-registro">Contraseña</label>
                            <input class="w-100 mb-3 form-control" type="password" placeholder="Tu contraseña" id="password-registro" required>

                            <!-- Campo para confirmar la contraseña -->
                            <label class="form-label" for="confirmar-password">Confirmar contraseña</label>
                            <input class="w-100 mb-3 form-control" type="password" placeholder="Repite tu contraseña" id="confirmar-password" required>
                            <!-- Mensaje de error para las contraseñas -->
                            <div id="passwordError" class="text-danger mt-2"></div>

                            <!-- Botón para registrar -->
                            <button id="btnRegistro" type="submit" class="btn btn-success text-center w-100 mb-2">Registrar</button>

                            <!-- Enlace para cambiar al formulario de inicio de sesión -->
                            <p class="text-center">¿Ya tienes cuenta? <a id="showLogin" href="">Inicia sesión</a></p>
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
