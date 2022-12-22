<?php
require '../includes/funciones.php';
$auth = estaAutenticado();

if (!$auth) {
    header('location: /');
}

//Importar conexion
require '../includes/config/database.php';
$db = conectarDb();

incluirTemplate('header');
var_dump($_SESSION);
?>

<main class="contenedor seccion">

    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Anuncio creado correctamente</p>
    <?php elseif (intval($resultado) === 2) : ?>
        <p class="alerta exito">Anuncio actualizado correctamente</p>
    <?php elseif (intval($resultado) === 3) : ?>
        <p class="alerta exito">Anuncio eliminado correctamente</p>
    <?php endif; ?>
    </div>
</main>


<p>Bienvenido <?php echo $_SESSION['nusuario']; ?></p>

<div class="contenedor">
    <table class="informe">
        <thead>
            <th>Usuarios</th>
            <th>Empresas</th>
            <th>Sucursales</th>
            <th>Equipos</th>
        </thead>

        <tbody>
            <tr>
                <td>
                    <p>Root: <span>20</span></p>
                    <p>Administradores: <span>20</span></p>
                    <p>Supervisores: <span>20</span></p>
                    <p>Auxiliares: <span>20</span></p>
                </td>
                <td>
                    <p>Root: <span>20</span></p>
                </td>
                <td>
                    <p>Root: <span>20</span></p>
                </td>
                <td>
                    <p>Root: <span>20</span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="/app/usuarios" class="boton boton-azul-block">Administrar</a>
                </td>
                <td>
                    <a href="crear.php" class="boton boton-azul-block">Administrar</a>
                </td>
                <td>
                    <a href="crear.php" class="boton boton-azul-block">Administrar</a>
                </td>
                <td>
                    <a href="crear.php" class="boton boton-azul-block">Administrar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php
//Cerrarla conexion
mysqli_close($db);

incluirTemplate('footer');
?>