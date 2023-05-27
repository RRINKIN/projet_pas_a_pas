<?php
include_once("lib/category_level_1.php");
include_once("lib/product.php");
include_once("lib/designer.php");

$page_view = "template_detail";

$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) : "liste";
?>