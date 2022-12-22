<?php
function conectarDb(): mysqli{
    $db = mysqli_connect('localhost', 'root', 'root', 'admaquin');
    if (!$db) {
        echo "Error: no se pudo conectar";
        exit;
    }else
    return $db;
}
