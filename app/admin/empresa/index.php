<?php
require '../../../includes/funciones.php';
$auth = estaAutenticado();

//Validar rol
$rolUsuario = $_SESSION['rol'];

if (!$auth) {
    header('location: /');
}

require '../../../includes/config/database.php';
$db = conectarDb();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {
        //Elimina la propiedad
        $query = "DELETE FROM empresas WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);

        //Eliminar e archivo
        $query = "SELECT logo FROM empresas WHERE id = ${id}";
        $resultado = mysqli_query($db, $query);
        $empresa = mysqli_fetch_assoc($resultado);
        unlink('../../build/img/empresa/' . $empresa['logo']);

        if ($resultado) {
            header('location: /app/empresa?conf=3');
        }
        echo $query;
    }
}


incluirTemplate('header');

//Escribir el Query
$query = "SELECT * FROM empresas";

//Cosultar la BD
$resultadoEmpresas = mysqli_query($db, $query);

// Mensaje condicional
$conf = $_GET['conf'] ?? null;

?>

<?php if (intval($conf) === 1) : ?>
    <p class="alerta exito">Registro correcto</p>
<?php elseif (intval($conf) === 2) : ?>
    <p class="alerta exito">Registro actualizado</p>
<?php elseif (intval($conf) === 3) : ?>
    <p class="alerta exito">Registro eliminado</p>
<?php endif; ?>

<div class="contenedor">
    <a href="/app" class="boton boton-verde">Atrás </a>
    <a href="/app/empresa/crear.php" class="boton boton-verde">Nueva empresa +</a>

    <table class="informe">
        <thead>
            <tr>
                <th>Nombre empresa</th>
                <th>Documento - Nit</th>
                <th>Dirección</th>
                <th>Logo</th>
                <th>Teléfono</th>
                <th>Ciudad</th>
                <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
            <!-- Mostrar los resultados -->
            <?php while ($empresas = mysqli_fetch_assoc($resultadoEmpresas)) : ?>
                <tr>
                    <td>
                        <?php echo $empresas['nombre'] ?>
                    </td>
                    <td>
                        <?php echo $empresas['identificacion'] ?>
                    </td>
                    <td>
                        <?php echo $empresas['direccion'] ?>
                    </td>
                    <td>
                        <img src="<?php echo "/build/img/empresa/" . $empresas['logo'] ?>"></img>
                    </td>
                    <td>
                        <?php echo $empresas['telefono'] ?>
                    </td>
                    <td>
                        <?php echo $empresas['ciudad'] ?>
                    </td>
                    <td>
                        <div class="flex-around">
                            <form method="POST" id="elimina">
                                <input type="hidden" name="id" value="<?php echo $empresas['id']; ?>">
                                <button type="submit" value="Eliminar" class="boton-eliminar">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                    </svg>

                                </button>

                            </form>

                            <button class="boton-editar" onclick="location.href='/app/empresa/actualizar.php?id=<?php echo $empresas['id'] ?>'">

                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                </svg>

                            </button>

                        </div>

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