<?php
require '../../includes/funciones.php';
$auth = estaAutenticado();

if (!$auth) {
    header('location: /');
}

require '../../includes/config/database.php';
$db = conectarDb();

incluirTemplate('header');

//Escribir el Query
$query = "SELECT * FROM usuarios";

//Cosultar la BD
$resultadoUsuarios = mysqli_query($db, $query);

?>

<h1>
    Administrar usuarios
</h1>



<?php
mysqli_close($db);
incluirTemplate('footer');
?>