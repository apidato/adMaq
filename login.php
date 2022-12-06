<?php
    //Autenticar el usuario
    require 'includes/config/database.php';
    $db = conectarDb();

    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // echo "<pre>";
        //     var_dump($_POST);
        // echo "</pre>";

        $email = mysqli_real_escape_string ($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));  
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if(!$email){
            $errores[] = "No se ha escrito un email o no es válido";
        }

        if(!$password){
            $errores[] = "El password es obligatorio";
        }

        // echo "<pre>";
        //     var_dump($errores);
        // echo "</pre>";

        if(empty($errores)){
            //Revisar si usuario existe
        $query = "SELECT * FROM usuarios WHERE email = '${email}'";
        $resultado = mysqli_query($db, $query);


        if($resultado->num_rows){
            //Revisar si password es correcto
            $usuario = mysqli_fetch_assoc($resultado);
            //Verificar si el password es correcto o no
            $auth = password_verify($password, $usuario['password']);

            if($auth){
                //El usuario esta autenticado
                session_start();

                //Llenar datos de sesion

                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;

                header('location: /admin');


            }else{
                $errores[] = "El password es incorrecto";
            }

        }else{
            $errores[] = "El usuario no existe";
         }

        }

    }

    //Incluir header
    require 'includes/funciones.php';
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error):?>
              <div class="alerta error">
                <?php echo $error;?>
              </div>  
        <?php endforeach; ?>    

        <form method="POST" class="formulario">
        <fieldset>
                <legend>Email y password</legend>
                <label for="email">E-mail</label>
                    <input type="email" name="email" placeholder="Tu Email" id="email">
                <label for="password">Tu password</label>
                    <input type="password" name="password" placeholder="Tu password" id="password">
            </fieldset>
            <input type="submit" value="Iniciar sesión" class="boton boton-azul">
        </form>

    </main>
<?php
    incluirTemplate('footer');
?>