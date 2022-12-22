
//Escribir el Query
$query = "SELECT * FROM usuarios";

//Cosultar la BD
$resultadoConsulta = mysqli_query($db, $query);


// Mensaje condicional
$resultado = $_GET['resultado'] ?? null;

if($_SERVER['REQUEST_METHOD']==='POST'){
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id){
        //Eliminar e archivo

        $query = "SELECT imagen FROM propiedades WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);
        unlink('../imagenes/'. $propiedad['imagen']);
        var_dump($propiedad);

        //Elimina la propiedad
        $query = "DELETE FROM propiedades WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);

        if ($resultado){
            header ('location: /admin?resultado=3');
        }
        echo $query;
    }
}

incluirTemplate('header');
?>


<main class="contenedor seccion">
    <h1>Administrador de bienes raíces</h1>
    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Anuncio creado correctamente</p>
    <?php elseif (intval($resultado) === 2) : ?>
        <p class="alerta exito">Anuncio actualizado correctamente</p>
    <?php elseif (intval($resultado) === 3) : ?>
        <p class="alerta exito">Anuncio eliminado correctamente</p>
    <?php endif; ?>

    <a href="/admin/usuarios/" class="boton boton-azul">Nuevo usuario</a>
    <a href="#" class="boton boton-azul" id="crea-usuario">Nuevo usuario</a>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody><!-- Mostrar los resultados -->
        <?php while( $propiedad = mysqli_fetch_assoc($resultadoConsulta)) :?>
            <tr>
                <td><?php echo $propiedad['id']?></td>
                <td><?php echo $propiedad['titulo']?></td>
                <td><img src="../imagenes/<?php echo $propiedad['imagen']?>" class="imagen-tabla"></td>
                <td><?php echo $propiedad['precio']?></td>
                <td>
                    <form method="POST" class="w-100">
                        <input type="hidden" name="id" value="<?php echo $propiedad['id'];?>">
                        <input type="submit" value="Eliminar" class="boton-rojo-block">
                    </form>
                    <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']?>" class="boton-amarillo-block">Actualizar</a>
                </td>
            </tr>
        <?php endwhile;?>
        </tbody>
    </table>

</main>

<div class="fondo_transparente">
        <div class="modal">
            <div class="modal_cerrar">
                <span>x</span>
            </div>
            <div class="modal_titulo">Crear nuevo usuario</div>
            <div class="modal_mensaje">

                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit, nam? Minus nihil temporibus, minima reprehenderit, rem explicabo earum nemo debitis, maxime deserunt quidem. Quia odit quae voluptate nobis sit beatae!</p>
            </div>
            <div class="modal_botones">
                <a href="" class="boton boton-azul">Cancelar</a>
                <input type="submit" class="boton-azul" value="Crear">
            </div>
        </div>
    </div>

<?php