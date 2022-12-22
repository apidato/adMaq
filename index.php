<?php

session_start();
//Autenticar el usuario
require 'includes/config/database.php';
$db = conectarDb();

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    //     var_dump($_POST);
    // echo "</pre>";

    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = "No se ha escrito un email o no es válido";
    }

    if (!$password) {
        $errores[] = "El password es obligatorio";
    }

    // echo "<pre>";
    //     var_dump($errores);
    // echo "</pre>";

    if (empty($errores)) {
        //Revisar si usuario existe
        $query = "SELECT * FROM usuarios WHERE email = '${email}'";
        $resultado = mysqli_query($db, $query);


        if ($resultado->num_rows) {
            //Revisar si password es correcto
            $usuario = mysqli_fetch_assoc($resultado);
            //Verificar si el password es correcto o no
            $auth = password_verify($password, $usuario['password']);

            if ($auth) {
                //El usuario esta autenticado
                session_start();

                //Llenar datos de sesion

                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['usuarioId'] = $usuario['id'];
                $_SESSION['login'] = true;
                $_SESSION['nusuario'] = $usuario['pnombre'] . " " . $usuario['papellido'];
                $_SESSION['cusuario'] = $usuario['usuario'];
                $_SESSION['rol'] = intval($usuario['roles_id']);


                header('location: /app');
            } else {
                $errores[] = "El password es incorrecto";
            }
        } else {
            $errores[] = "El usuario no existe";
        }
    }
}

//Incluir header
require 'includes/funciones.php';
//    incluirTemplate('header');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>|MAQ|</title>
    <link rel="stylesheet" href="/build/css/app.css">
</head>

<body class="degradado-azul">

    <main class="contenedor-login seccion fondo-imagen">

        <img src="/build/img/app/admaq.png" class="logo">

        <?php foreach ($errores as $error) : ?>
            <div class="alerta error-login">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form method="POST" class="formulario  formulario-login">

            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email" id="email">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" id="password">
            <div class="flex-end">
                <input type="submit" value="Iniciar sesión" class="boton-sesion">
            </div>
        </form>

    </main>

    <footer class="footer">
        <p class="copyright">Todos los derechos Reservados <?php echo date('Y'); ?> &copy;</p>
    </footer>
    <script src="/build/js/bundle.min.js"></script>
</body>

</html>