<?php
// ARCHIVOS
require("errores.php");
require("funciones.php");

// VARIABLES
$mensaje = '';

// Conexión con la BBDD-------
$conn = conectarBBDD();

// ---------INSERCIÓN DE REGISTROS--------- //

// SACAR DATOS DEL FORMULARIO
if (isset($_POST['registrarse'])) {
    $nombre = $_POST['nombreUsuario'];
    $apellidos = $_POST['apellidosUsuario'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $correo = $_POST['correoElectronicoUsuario'];
    $contraseña = $_POST['contraseña'];

    $contrasena_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // SENTENCIA PREPARADA 
    $consultaregistro = "INSERT INTO usuarios (nombreUsuario, apellidosUsuario, fechaNacimientoUsuario, correoElectronicoUsuario, contraseña, idRolFK)
    VALUES (?,?,?,?,?,3)";

    // PREPARACIÓN DE LA SENTENCIA
    $sentprep = $conn->prepare($consultaregistro);
    $sentprep->bind_param("sssss", $nombre, $apellidos, $fechaNacimiento, $correo, $contrasena_hash);

    // COMPROBACION DE LA CONSULTA
    if ($sentprep->execute()) {
        $mensaje = "<p class='alert alert-success' role='alert'>Usuario registrado correctamente</p>";

        header("location:../index.php");
        exit();
    } else {
        $mensaje = "<p class='alert alert-danger' role='alert'>No se pudo registrar";
    }
    $sentprep->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | FitFood</title>
    <link rel="icon" href="../media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #94e7ff;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #006691;">
        <div class="container-fluid">
            <a href="../index.php">
                <img class="rounded" src="../media/logoancho.png" alt="logo" width="155">
            </a>
        </div>
    </nav>
    <br>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Registro</h2>
                    </div>
                    <div class="card-body">
                        <form action="registro.php" method="POST">
                            <div class="mb-3">
                                <label for="nombreUsuario" class="form-label">Nombre</label>
                                <input type="text" id="nombreUsuario" name="nombreUsuario" class="form-control" placeholder="Ingrese su nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidosUsuario" class="form-label">Apellidos</label>
                                <input type="text" id="apellidosUsuario" name="apellidosUsuario" class="form-control" placeholder="Ingrese sus apellidos" required>
                            </div>
                            <div class="mb-3">
                                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="correoElectronicoUsuario" class="form-label">Correo electrónico</label>
                                <input type="email" id="correoElectronicoUsuario" name="correoElectronicoUsuario" class="form-control" placeholder="Ingrese su correo electrónico" required>
                            </div>
                            <div class="mb-3">
                                <label for="contraseña" class="form-label">Contraseña</label>
                                <input type="password" id="contraseña" name="contraseña" class="form-control" placeholder="Ingrese su contraseña" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar contraseña</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirme su contraseña" required>
                                <div id="password_error" style="color: red;"></div> <!-- Mensaje de error -->
                            </div>
                            <div class="d-grid">
                                <button name="registrarse" type="submit" class="btn btn-primary">Registrarse</button>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="login.php" class="btn btn-link">Inicia sesión aquí</a>
                            </div>
                        </form>
                        <?php echo $mensaje; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer bg-dark text-light p-2 fixed-bottom">
        <div class="container text-center">
            <p>&copy; 2024 FitFood. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var passwordInput = document.getElementById("contraseña");
            var confirmPasswordInput = document.getElementById("confirm_password");
            var passwordError = document.getElementById("password_error");

            function validatePassword() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    passwordError.textContent = "Las contraseñas no coinciden";
                } else {
                    passwordError.textContent = "";
                }
            }

            // Validar cuando se escriba en los campos de contraseña
            passwordInput.addEventListener("input", validatePassword);
            confirmPasswordInput.addEventListener("input", validatePassword);
        });
    </script>
</body>

</html>