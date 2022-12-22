<?php
require '../../includes/funciones.php';
$auth = estaAutenticado();

//Variables de inicio de sesion
$rolUsuario = $_SESSION['rol'];
$nombreUsuario = $_SESSION['cusuario'];
$usuarioId = $_SESSION['usuarioId'];
$empresaUsuario = 1;
$sucursalUsuario = 1;

if (!$auth) {
    header('location: /');
}

require '../../includes/config/database.php';
$db = conectarDb();

incluirTemplate('header');

//Arreglo con mensajes de errores

$errores = [];

$nombre = '';
$identificacion = '';
$direccion = '';
$logo = '';
$telefono = '';
$ciudad = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $identificacion = mysqli_real_escape_string($db, $_POST['identificacion']);
    $direccion = mysqli_real_escape_string($db,  $_POST['direccion']);
    $logo = mysqli_real_escape_string($db, $_POST['logo']);
    $telefono = mysqli_real_escape_string($db,  $_POST['telefono']);
    $ciudad = mysqli_real_escape_string($db,  $_POST['ciudad']);


    //$vendedorId =mysqli_real_escape_string ($db,  $_POST['vendedor']);
    //$creado = date('Y/m/d');

    //Asignar FILEs hacia una variable

    $imagen = $_FILES['logo'];

    // var_dump($imagen['name']);
    // exit;


    if (!$nombre) {
        $errores[] = "Incluir un nombre";
    }

    if (!$identificacion) {
        $errores[] = "Incluir una identificación";
    }

    if (!$direccion) {
        $errores[] = "Incluir dirección";
    }

    if (!$telefono) {
        $errores[] = "Incluir numero de teléfono";
    }

    if (!$ciudad) {
        $errores[] = "Incluir una ciudad";
    }

    if (!$imagen['name']) {
        $errores[] = "La imágen es obligatoria";
    }

    //Validar por tamaño, 1MB max
    $medida = 1000 * 1000;

    if ($imagen['size'] > $medida || $imagen['error']) {
        $errores[] = "El tamaño de la imágen no es adecuado";
    }

    //Revisar que arreglo de errores este vacío
    if (empty($errores)) {

        // Subir Archivos

        //Crear carpeta
        $carpetaImagenes = '../../build/img/empresa/';
        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        //Generar nombre unico

        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        //var_dump($nombreImagen);

        //Subir la imagen
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);

        //Insertar en base de datos
        $query = "INSERT INTO empresas (nombre, identificacion, logo, direccion, telefono, ciudad) VALUES ('$nombre', '$identificacion', '$nombreImagen', '$direccion', '$telefono', '$ciudad' )";
        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            $movimiento = "Creación de empresa " . $nombre;
            $query = "INSERT INTO auditoria (movimiento, usuarios_id, empresas_id, sucursales_id) VALUES ('$movimiento', $usuarioId, $empresaUsuario, $sucursalUsuario)";
            $resultado = mysqli_query($db, $query);

            //Redireccionar al usuario
            header('location: /app/empresa?conf=1');
        }
    }
}

?>


<main class="contenedor seccion">
    <a href="/app/empresa" class="boton boton-verde">Regresar</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach ?>


    <form class="formulario" method="POST" action="/app/empresa/crear.php" enctype="multipart/form-data">

        <input type="text" id="nombre" name="nombre" placeholder="Nombre empresa" value="<?php echo $nombre; ?>">
        <input type="text" id="identificacion" name="identificacion" placeholder="Identificacion o Nit" value="<?php echo $identificacion; ?>">
        <input type="text" id="direccion" name="direccion" placeholder="Dirección" value="<?php echo $direccion; ?>">
        <input type="file" id="logo" accept="image/jpeg, image/png" name="logo">
        <input type="number" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $telefono; ?>">
        <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad" value="<?php echo $ciudad; ?>">

        <input type="submit" value="Crear empresa" class="boton boton-verde">
    </form>

</main>


<?php
incluirTemplate('footer');
?>