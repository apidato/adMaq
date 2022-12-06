<?php

require '../../includes/funciones.php';
$auth = estaAutenticado();


if(!$auth){
    header('location: /');
}


//Base de datos
require '../../includes/config/database.php';
$db = conectarDb();

//Consultar los vendedores

$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

//Arreglo con mensajes de errores

$errores = [];

$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';

//Ejecutar codigo despues de que usuario envia formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    //     var_dump($_POST);
    // echo "</pre>";

    echo "<pre>";
        var_dump($_FILES);
    echo "</pre>";

    // exit;

    $titulo = mysqli_real_escape_string ($db, $_POST['titulo']);
    $precio = mysqli_real_escape_string ($db, $_POST['precio']);
    $descripcion =mysqli_real_escape_string ($db,  $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string ($db, $_POST['habitaciones']);
    $wc =mysqli_real_escape_string ($db,  $_POST['wc']);
    $estacionamiento =mysqli_real_escape_string ($db,  $_POST['estacionamiento']);
    $vendedorId =mysqli_real_escape_string ($db,  $_POST['vendedor']);
    $creado = date('Y/m/d');
    
    //Asignar FILEs hacia una variable

    $imagen = $_FILES['imagen'];

    // var_dump($imagen['name']);
    // exit;


    if (!$titulo) {
        $errores[] = "Debes añadir un titulo";
    }

    if (!$precio) {
        $errores[] = "El precio es obligatorio";
    }

    if (strlen($descripcion) < 50) {
        $errores[] = "La descripcion es obligatoria y debe tener al menos 50 caracteres";
    }

    if (!$habitaciones) {
        $errores[] = "El numero de habitaciones es obligatorio";
    }

    if (!$wc) {
        $errores[] = "El numero de baños es obligatorio";
    }

    if (!$estacionamiento) {
        $errores[] = "El numero de estacionamientos es obligatorio";
    }

    if (!$vendedorId) {
        $errores[] = "Debe seleccionar un vendedor";
    }

    if (!$imagen['name']){
        $errores[] = "La imágen es obligatoria";
    }

    //Validar por tamaño, 1MB max
    $medida = 1000 * 1000;

    if ($imagen['size'] > $medida || $imagen['error']) {
        $errores[] = "La imágen es muy pesada";
    }

        // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";

    //Revisar que arreglo de errores este vacío
    if (empty($errores)) {

        // Subir Archivos

        //Crear carpeta
        $carpetaImagenes = '../../imagenes/';
        if(!is_dir($carpetaImagenes)){
            mkdir($carpetaImagenes);
        }

        //Generar nombre unico

        $nombreImagen = md5(uniqid(rand(), true)).".jpg";

        var_dump($nombreImagen);

        //Subir la imagen
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes. $nombreImagen);

        //Insertar en bd
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ('$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId' )";

        //echo $query;

        $resultado = mysqli_query($db, $query);
        if ($resultado) {

            //Redireccionar al usuario
            header ('location: /admin?resultado=1');
            //echo "Insertado correctamente";
        }
    }
}

incluirTemplate('header');
?>


<main class="contenedor seccion">
    <h1>Crear</h1>
    <a href="/admin/" class="boron boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach ?>


    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Información general</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo propiedad" value="<?php echo $titulo;?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio;?>">

            <label for="imagen">Imágen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcion" cols="30" rows="10" ><?php echo $descripcion;?></textarea>
        </fieldset>

        <fieldset>
            <legend>Información de la propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones;?>">

            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc;?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento;?>">
        </fieldset>
        <fieldset>
            <legend>Vendedor</legend>
            <select name="vendedor" id="vendedor">
                <option disabled selected>--Seleccione vendedor--</option>
                <?php 
                    while ($vendedor = mysqli_fetch_assoc($resultado)):?>
                        <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : '';?> value="<?php echo $vendedor['id'];?>"><?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></option>
                    <?php endwhile; ?>
            </select>
        </fieldset>
        <input type="submit" value="Crear propiedad" class="boton boton-verde">
    </form>

</main>


<?php
incluirTemplate('footer');
?>