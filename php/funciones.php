<?php
function conectarBBDD()
{
    $servidor = "localhost";
    $usuario = "ivanpuxito";
    $clave = "1234";
    $bbdd = "BD_FitFood";

    // Objeto conexión
    $conn = new mysqli($servidor, $usuario, $clave, $bbdd);

    if ($conn->connect_error) {
        die("Error de Conexión: " . $conn->connect_error);
    }

    return $conn;
}

function conectarBBDD_PDO()
{
    $servidor = "localhost";
    $usuario = "ivanpuxito";
    $clave = "1234";
    $bbdd = "BD_FitFood";

    try {
        // Objeto conexión PDO
        $conn = new PDO("mysql:host=$servidor;dbname=$bbdd", $usuario, $clave);
        // Configura PDO para que lance excepciones en caso de errores
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Captura cualquier excepción que ocurra durante la conexión
        die("Error de Conexión: " . $e->getMessage());
    }
}


// SESIONES PARA USUARIOS NORMALES
function sesionN0()
{
    // Iniciar la sesión si no está iniciada
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Salir de la sesión si se ha enviado la solicitud 'salir'
    if (isset($_REQUEST['cerses'])) {
        session_destroy();
        header("Location: /index.php");
        exit();
    }


    if (isset($_GET['cerses']) && $_GET['cerses'] == 'true') {
        echo "<script>alert('Tu sesión ha sido cerrada.');</script>";
    }

    // Verificar si el usuario ha iniciado sesión
    if (isset($_SESSION["correoElectronicoUsuario"])) {
        return true; // Usuario ha iniciado sesión
    } else {
        return false; // Usuario no ha iniciado sesión
    }
}


function sesionN1()
{
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Salir de la sesión si se ha enviado la solicitud 'salir'
    if (isset($_REQUEST['cerses'])) {
        session_destroy();
        header("Location: /index.php");
        exit();
    }


    if (isset($_GET['cerses']) && $_GET['cerses'] == 'true') {
        echo "<script>alert('Tu sesión ha sido cerrada.');</script>";
    }

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION["correoElectronicoUsuario"])) {
        // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión
        header("Location: ../php/login.php");
        exit();
    }
}

// SESIONES PARA USUARIOS ADMINISTRADORES
function sesionN2()
{
    session_start();

    // Salir de la sesión si se ha enviado la solicitud 'salir'
    if (isset($_REQUEST['cerses'])) {
        session_destroy();
        header("Location: /index.php");
        exit();
    }

    if (isset($_GET['cerses']) && $_GET['cerses'] == 'true') {
        echo "<script>alert('Tu sesión ha sido cerrada.');</script>";
    }

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION["correoElectronicoUsuario"])) {

        header("Location: ../php/login.php");
        exit();
    }



    // VERIFICAR USUARIO ADMINISTRADOR
    $conn = conectarBBDD();
    $correoElectronicoUsuario = $_SESSION["correoElectronicoUsuario"];
    $sql = "SELECT idRolFK FROM usuarios WHERE correoElectronicoUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correoElectronicoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    $rol = $fila["idRolFK"];
    $stmt->close();
    $conn->close();

    if ($rol != 1) {

        header("Location:../php/denegado.php");
        exit();
    }
}



// INICIO DE SESION
function inicioSesion($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Consulta SQL para buscar por correo electrónico
        $sql = "SELECT correoElectronicoUsuario, contraseña FROM usuarios WHERE correoElectronicoUsuario = ?";

        // Preparar la sentencia
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("s", $email);

        // Ejecutar la sentencia
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $fila = $result->fetch_assoc();
            $hash_almacenado = $fila["contraseña"];

            // Verificar si la contraseña coincide
            if (password_verify($password, $hash_almacenado)) {
                // Inicio de sesión exitoso
                session_start();
                $_SESSION["correoElectronicoUsuario"] = $fila["correoElectronicoUsuario"];
                // Redirigir a la página de inicio
                header("Location: ../index.php");
                echo "¡Inicio de sesión exitoso!";
                exit();
            } else {
                echo "Correo electrónico o contraseña incorrectos";
            }
        } else {
            echo "Correo electrónico o contraseña incorrectos.";
        }

        if ($stmt->error) {
            echo "Error en la ejecución de la consulta: " . $stmt->error;
            exit();
        }


        $stmt->close();
    }
}


// Función para obtener la ruta de la imagen del usuario actual
function obtenerRutaImagenUsuario()
{

    sesionN1();


    $conn = conectarBBDD();
    $correoElectronicoUsuario = $_SESSION["correoElectronicoUsuario"];
    $sql = "SELECT imagenUsuario FROM usuarios WHERE correoElectronicoUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correoElectronicoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    $ruta_imagen = $fila["imagenUsuario"];
    $stmt->close();
    $conn->close();

    return $ruta_imagen;
}

// Función para obtener el nombre del usuario actual
function obtenerNombreUsuario()
{

    sesionN0();


    $conn = conectarBBDD();
    $correoElectronicoUsuario = $_SESSION["correoElectronicoUsuario"];
    $sql = "SELECT nombreUsuario FROM usuarios WHERE correoElectronicoUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correoElectronicoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    $nombreUsuario = $fila["nombreUsuario"];
    $stmt->close();
    $conn->close();

    return $nombreUsuario;
}


function obtenerIDUsuario()
{
    sesionN0();
    $conn = conectarBBDD();
    $correoElectronicoUsuario = $_SESSION["correoElectronicoUsuario"];
    $sql = "SELECT idUsuario FROM usuarios WHERE correoElectronicoUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correoElectronicoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    $idUsuario = $fila["idUsuario"];
    $stmt->close();
    $conn->close();

    return $idUsuario;
}

function obtenerDatosUsuario()
{
    sesionN0();
    $conn = conectarBBDD();
    $correoElectronicoUsuario = $_SESSION["correoElectronicoUsuario"];
    $sql = "SELECT * FROM usuarios WHERE correoElectronicoUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correoElectronicoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    return $fila;
}

function obtenerIDUsuarioPorCorreo($correoElectronicoUsuario)
{
    $conn = conectarBBDD_PDO();
    $sql = "SELECT idUsuario FROM usuarios WHERE correoElectronicoUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$correoElectronicoUsuario]);
    $idUsuario = $stmt->fetchColumn();
    return $idUsuario;
}
function obtenerCorreoElectronicoUsuario()
{
    sesionN0();
    return $_SESSION["correoElectronicoUsuario"];
}

function getAgeForCurrentUser()
{
    global $conn;
    sesionN1();


    if (isset($_SESSION['correoElectronicoUsuario'])) {
        $correoElectronicoUsuario = $_SESSION['correoElectronicoUsuario'];


        $consulta = "SELECT fechaNacimientoUsuario FROM usuarios WHERE correoElectronicoUsuario = ?";


        $resultado = $conn->prepare($consulta);
        $resultado->bind_param("s", $correoElectronicoUsuario);
        $resultado->execute();
        $resultado->bind_result($fechaNacimiento);


        if ($resultado->fetch()) {

            $fechaActual = new DateTime();
            $fechaNacimientoObj = new DateTime($fechaNacimiento);


            if (($fechaActual->format('m') < $fechaNacimientoObj->format('m')) ||
                ($fechaActual->format('m') == $fechaNacimientoObj->format('m') &&
                    $fechaActual->format('d') < $fechaNacimientoObj->format('d'))
            ) {

                $edad = $fechaActual->format('Y') - $fechaNacimientoObj->format('Y') - 1;
            } else {

                $edad = $fechaActual->format('Y') - $fechaNacimientoObj->format('Y');
            }

            $resultado->close();

            return $edad;
        } else {

            $resultado->close();
            return "No se encontro la fecha";
        }
    } else {

        return "No se encontró el correo electrónico del usuario en la sesión";
    }
}

function obtenerComidasPorUsuario($idUsuario)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM comidas WHERE idUsuarioFK = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    return $stmt->get_result();
}

function agregarComida($nombreComida, $idUsuario)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comidas (nombreComida, idUsuarioFK) VALUES (?, ?)");
    $stmt->bind_param("si", $nombreComida, $idUsuario);
    return $stmt->execute();
}

function actualizarComida($idComida, $nombreComida)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE comidas SET nombreComida = ? WHERE idComida = ?");
    $stmt->bind_param("si", $nombreComida, $idComida);
    return $stmt->execute();
}

function eliminarComida($idComida)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM comidas WHERE idComida = ?");
    $stmt->bind_param("i", $idComida);
    return $stmt->execute();
}

function obtenerEvento()
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM eventos WHERE idUsuario = ?");
    $stmt->bind_param("i", $idEvento);
    return $stmt->execute();
}
