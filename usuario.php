<?php
    //Importar la conexion
    require 'includes/config/database.php';
    $db = conectarDb();

    //Crear un email y password
    $pnombre = "Admin";
    $snombre = "";
    $papellido = "System";
    $sapellido = "";
    $identificacion = 123456789;
    $usuario = "system";
    $password = "santana";
    $email = "system@correo.com";
    $estado = 1;
    $roles_id = 1;
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO usuarios (pnombre, snombre, papellido, sapellido, identificacion, usuario, password, email, estado, roles_id) VALUES ('${pnombre}', '${snombre}', '${papellido}', '${sapellido}', ${identificacion},'${usuario}', '${passwordHash}', '${email}', ${estado}, ${roles_id});";

    //Query para crear usuario

    // $query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}');";
    echo $query;
    //Ingresarlo a la base de datos
    mysqli_query($db, $query);

    mysqli_close($db);
?>