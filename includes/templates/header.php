<?php 
    if(!isset($_SESSION)){
        session_start();
    }

    $auth = $_SESSION['login'] ?? false;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>|MAQ|</title>
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>


    
    
    <header class="header">
    <div class="contenedor">
    <div class="barra">
        
        Bienvenido <?php echo $_SESSION['nusuario']; ?> 

        <?php incluirTemplate('menu');?>

        <div class="mobile-menu">
            <img src="/build/img/barras.svg" alt="icono menu responsive">
        </div>

        <div class="derecha">
            <nav class="navegacion">
                <a href="blog.php">Blog</a>
                <a href="contacto.php">Contacto</a>
                <?php if ($auth) :?> <a href="/cerrar-sesion.php">Cerrar</a><?php endif?>
            </nav>
        </div>

        
    </div>
</div>      
    </header>