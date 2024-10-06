<?php
    require "../autoloader.php";
    use App\Funciones\classFunciones;
    session_start();
    $cCfn = new classFunciones();
    $cCfn->checkSession();
    $favicon=$cCfn->favicon();
    $title=$cCfn->title();
?>
<!DOCTYPE html>
<html >
    <head>
        <title><?php echo $title; ?> - Entel</title>
        <?php include("./lib/header.php"); ?> 
    </head>
    <body >
        <!-- Navbar -->
        <nav class="navbar navbar-light " style="background-color: #e6e6e6;" >
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="btn btn-primary navbar-btn" onclick="toggleSidebar()">
                        <span class="glyphicon glyphicon-menu-hamburger"></span>
                    </button>
                    <h6 class="navbar-brand" >Hola <?php echo $_SESSION["user"]; ?></h6>
                </div>
                <ul class="nav navbar-nav"></ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../logout.php" class="nav-link"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
                </ul>
            </div>
        </nav>

        <?php include("sidebar.html"); ?>

        <div id="mainContent" class="main-content">
            
            <h2>Contenido Principal</h2>
            <p>Este es el contenido principal de la página.</p>
        </div>
    </body>
</html>