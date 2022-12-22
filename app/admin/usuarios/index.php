<?php
require '../../includes/funciones.php';
$auth = estaAutenticado();

//Validar rol
$rolUsuario = $_SESSION['rol'];

if (!$auth) {
    header('location: /');
}

require '../../includes/config/database.php';
$db = conectarDb();

incluirTemplate('header');

//Escribir el Query
$query = "SELECT * FROM usuarios WHERE id >= $rolUsuario";

//Cosultar la BD
$resultadoUsuarios = mysqli_query($db, $query);

var_dump($resultadoUsuarios);

?>

<h1>
    Admin usuarios
</h1>

<div class="contenedor">
    <a href="/app" class="boton boton-verde">Regresar</a>
    <a href="crear.php" class="boton boton-verde">Crear Nuevo usuario</a>
    <table class="informe">
        <thead>
            <tr>
                <th>Fecha de creacón</th>
                <th>Nombres</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Rol</th>
                <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
            <!-- Mostrar los resultados -->
            <?php while ($usuarios = mysqli_fetch_assoc($resultadoUsuarios)) : ?>
                <tr>
                    <td>
                        <p><?php echo $usuarios['fecha'] ?></p>
                    </td>
                    <td>
                        <p><?php
                            echo $usuarios['pnombre'] . " " .
                                $usuarios['snombre'] . " " .
                                $usuarios['papellido'] . " " .
                                $usuarios['sapellido']
                            ?>
                        </p>
                    </td>
                    <td>
                        <p><?php echo $usuarios['usuario'] ?></p>
                    </td>
                    <td>
                        <p><?php echo $usuarios['email'] ?></p>
                    </td>
                    <td><?php
                        if ($usuarios['estado'] == 0) {
                            echo "<p>Inactivo</p>";
                        } elseif ($usuarios['estado'] == 1) {
                            echo "<p>Activo</p>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php switch ($usuarios['roles_id']) {
                            case 1:
                                echo "<p>Root</p>";
                                break;
                            case 2:
                                echo "<p>Auditor</p>";
                                break;
                            case 3:
                                echo "<p>Administrador</p>";
                                break;
                            case 4:
                                echo "<p>Supervisor</p>";
                                break;
                            case 5:
                                echo "<p>Auxiliar</p>";
                                break;
                            case 6:
                                echo "<p>Operario</p>";
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <a href="/admin/usuarios/actualizar.php?id=<?php echo $usuarios['id'] ?>" class="boton boton-verde-block">Editar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
mysqli_close($db);
incluirTemplate('footer');
?>