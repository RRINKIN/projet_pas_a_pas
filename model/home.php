<?php
include_once("lib/category_level_1.php");
include_once("lib/product.php");
include_once("lib/designer.php");

$page_view = "template_home";

$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) : "liste";

// fonction de récupération du menu. 
// Le parametre 1 permet d'utiliser la partie de la query qui fait appel à is_visible dans la DB.
$menu = getCategory_level_1(0, 1);

// récupération des éléments relatifs aux produits
$produits           = getProduct(0);

// pagination
$ad_total           = getProductsCount();

?>