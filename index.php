<?php
session_start();
include_once('base/config.php');

// initialisation des variables
$page = isset($_GET["p"]) ? $_GET["p"] : "home"; // "default" est la page affichée par défaut.

if(file_exists("model/".$page.".php")){
    include_once("model/".$page.".php");
}else{
    echo "<b>ERREUR !</b><br />Le model \"<b>".$page."</b>\" n'existe pas";
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

    <head>
        <title></title>
        <meta name="description" content="" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="language" content="fr" />
        <meta name="revisit-after" content="7 days" />
        <meta name="robots" content="index, follow" />
        <link rel="stylesheet" type="text/css" href="css/normalize.css" />
        <link rel="stylesheet" type="text/css" href="css/skeleton.css" />
        <link rel="stylesheet" type="text/css" href="css/skeleton_collapse.css" />
        <link rel="stylesheet" type="text/css" href="css/custom.css" />
        <script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js"></script>

    </head>
    <body>
        <div class="row">
            <?php
                if(isset($_SESSION["admin_id"])){
            ?>
            <nav class="nav-show">
                <div class="container">
                    <ul>
                        <li><a href="index.php?p=admin_item">Gestion des pages</a></li>
                        <li><a href="index.php?p=admin_admin_user">Gestion des utilisateurs</a></li>
                        <li>
                            <a>Gestion du shop</a>
                            <ul>
                                <li><a href="index.php?p=admin_designer">Designer</a></li>
                                <li><a href="index.php?p=admin_manufacturer">Manufacturier</a></li>
                                <li><a href="index.php?p=admin_shape">Etat</a></li>
                                <li><a href="index.php?p=admin_delivery">Livraison</a></li>
                                <li><a href="index.php?p=admin_category_level_1">Catégorie</a></li>
                                <li><a href="index.php?p=admin_category_level_2">Sous-catégorie</a></li>
                                <li><a href="index.php?p=admin_product">Fiche produit</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>Mon compte</a>
                            <ul>
                                <li><a href="index.php?p=admin_password">Modifier mon mot-de-passe</a></li>
                                <li><a href="index.php?p=admin_unlog">D&eacute;connexion</a></li>
                            </ul>
                        </li>
                        <li class="u-pull-right"><a href="index.php?p=login">Login</a></li>
                    </ul>
                </div>
            </nav>
            <?php
                }
            ?>
        </div>
        <div class="container" id="content">
            <?php
            if(file_exists("view/".$page_view.".php")){
                include_once("view/".$page_view.".php");
            }else{
                exit("view non définie ou inexistante");
            }
            ?>
        </div>
    </body>
</html>
