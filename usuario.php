<?php
    //Importar la conexion
    require 'includes/config/database.php';
    $db = conectarDb();

    //Crear un email y password
    $email = "correo@correo.com";
    $password = "123456";
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    //Query para crear usuario

    $query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}');";
    echo $query;
    //Ingresarlo a la base de datos
    mysqli_query($db, $query);

    mysqli_close($db);
?>