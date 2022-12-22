<?php

require '../../includes/funciones.php';
$auth = estaAutenticado();

if(!$auth){
    header('location: /app');
}

//Variables de inicio de sesion
$rolUsuario = $_SESSION['rol'];
$nombreUsuario = $_SESSION['cusuario'];
$usuarioId = $_SESSION['usuarioId'];
$empresaUsuario = 1;
$sucursalUsuario = 1;

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id){
    header ('location: /app');
}

//Base de datos
require '../../includes/config/database.php';
$db = conectarDb();

//Consultar los datos de la propiedad
$consulta = "SELECT * FROM empresas WHERE id = ${id}";
$resultado = mysqli_query($db, $consulta);
$empresa = mysqli_fetch_assoc($resultado);


// echo "<pre>";
//     var_dump($empresa);
// echo "</pre>";

//Arreglo con mensajes de errores

$errores = [];

$nombre = $empresa['nombre'];
$identificacion = $empresa['identificacion'];
$direccion = $empresa['direccion'];
$logo =$empresa['logo'];
$telefono = $empresa['telefono'];
$ciudad = $empresa['ciudad'];
$logo = $empresa ['logo'];


//Ejecutar codigo despues de que usuario envia formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    //     var_dump($_POST);
    // echo "</pre>";

    // echo "<pre>";
    //     var_dump($_FILES);
    // echo "</pre>";

    //exit;

    $nombre = mysqli_real_escape_string ($db, $_POST['nombre']);
    $identificacion = mysqli_real_escape_string ($db, $_POST['identificacion']);
    $direccion =mysqli_real_escape_string ($db,  $_POST['direccion']);
    $logo = mysqli_real_escape_string ($db, $_POST['logo']);
    $telefono =mysqli_real_escape_string ($db,  $_POST['telefono']);
    $ciudad =mysqli_real_escape_string ($db,  $_POST['ciudad']);
    
    //Asignar FILEs hacia una variable

    $imagen = $_FILES['logo'];

    // var_dump($imagen['name']);
    // exit;



    if (!$nombre) {
        $errores[] = "Debes añadir un nombre de empresa";
    }

    if (!$identificacion) {
        $errores[] = "Añadir una identificación";
    }

    if (!$direccion) {
        $errores[] = "Añadir direccion de la empresa";
    }

    if (!$telefono) {
        $errores[] = "El numero de telefono es obligatorio";
    }

    if (!$ciudad) {
        $errores[] = "Añadir una ciudad";
    }

    // if (!$imagen['name']){
    //     $errores[] = "La imágen es obligatoria";
    // }



    //Validar por tamaño, 1MB max
    $medida = 1000 * 1000;

    // if ($imagen['size'] > $medida || $imagen['error']) {
    //     $errores[] = "La imágen es muy pesada";
    // }


        // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";

    //Revisar que arreglo de errores este vacío
    if (empty($errores)) {

        // Subir Archivos
        //Crear carpeta
        $carpetaImagenes = '../../build/img/empresa/';
        if(!is_dir($carpetaImagenes)){
            mkdir($carpetaImagenes);
        }

        $nombreImagen  = '';

        if ($imagen['name']){
            unlink($carpetaImagenes . $empresa['logo']);
            //Generar nombre unico
            $nombreImagen = md5(uniqid(rand(), true)).".jpg";
            // //Subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes. $nombreImagen);
        }else{
            $nombreImagen = $empresa['logo'];        }


        //Insertar en bd
        $query = "UPDATE empresas SET nombre = '${nombre}', identificacion = '${identificacion}', logo='${nombreImagen}',telefono = '${telefono}', ciudad = '${ciudad}' WHERE id = ${id}";

        //echo $query;

        //exit;

        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            $movimiento = "Edición de empresa " . $nombre;
            $query = "INSERT INTO auditoria (movimiento, usuarios_id, empresas_id, sucursales_id) VALUES ('$movimiento', $usuarioId, $empresaUsuario, $sucursalUsuario)";
            $resultado = mysqli_query($db, $query);


            //Redireccionar al usuario
            header ('location: /app/empresa?conf=2');
            //echo "Insertado correctamente";
        }
    }
}

incluirTemplate('header');
?>


<main class="contenedor seccion">
    <a href="/app/empresa" class="boron boton-verde">Regresar</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach ?>


    <form class="formulario" method="POST" enctype="multipart/form-data">

    <input type="text" id="nombre" name="nombre" placeholder="Nombre empresa" value="<?php echo $nombre;?>">
            <input type="text" id="identificacion" name="identificacion" placeholder="Identificacion o Nit" value="<?php echo $identificacion;?>">
            <input type="text" id="direccion" name="direccion" placeholder="Dirección" value="<?php echo $direccion;?>">
            <input type="file" id="logo" accept="image/jpeg, image/png" name="logo">
            <img src="/build/img/empresa/<?php echo $logo?>" class="imagen-small">
            <input type="number" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $telefono;?>">
            <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad" value="<?php echo $ciudad;?>">
    
        <input type="submit" value="Actualizar empresa" class="boton boton-verde">
    </form>

</main>


<?php
incluirTemplate('footer');
?>