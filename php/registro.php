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
    <title>Registro</title>
    <link rel="icon" href="media/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="../estilos/registrostyle.css">

</head>

<body>
    <header>
        <div>
            <a href="../index.php"><img src="../media/logoancho.png"></a>
        </div>
    </header>
    <div class="bg-light w-25 p-3 mt-1 mx-auto rounded shadow">
        <form action="registro.php" method="POST">
            <div class="center-content">
                <h2>Registro</h2>
                <img src="../media/logo.png" alt="Logo de la página web">
            </div>
            <div class="input-container">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingrese su nombre" required>
            </div>
            <div class="input-container">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidosUsuario" name="apellidosUsuario" placeholder="Ingrese sus apellidos" required>
            </div>
            <div class="input-container">
                <label for="fechaNacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fechaNacimiento" name="fechaNacimiento" required>
            </div>
            <div class="input-container">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correoElectronicoUsuario" name="correoElectronicoUsuario" placeholder="Ingrese su correo electrónico" required>
            </div>
            <div class="input-container">
                <label for="contraseña">Contraseña</label>
                <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" required>
            </div>
            <div class="input-container">
                <label for="confirm_password">Confirmar contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme su contraseña" required>
                <div id="password_error" style="color: red;"></div> <!-- Mensaje de error -->
            </div>
            <button name="registrarse" type="submit" class="btn btn-primary">Registrarse</button>
            <br>
        </form>
        <p class="cuenta">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
    </div>
    <footer>
        <p>&copy; 2024 FitFood. Todos los derechos reservados.</p>
    </footer>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var passwordInput = document.getElementById("password");
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