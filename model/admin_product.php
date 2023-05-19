<?php
verifConnexion();
include_once("lib/product.php");
include_once("lib/category_level_2.php");
include_once("lib/shape.php");
include_once("lib/designer.php");
include_once("lib/manufacturer.php");

$url_page = "admin_product";

// récupération/initialisation du paramètre "action" qui va permettre de diriger dans le switch vers la partie de code qui sera a exécuter
// si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut
$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) : "liste";

// récupération des informations passée en _GET pour cette partie du code (affichage de la liste + détail d'un manufacturer)
$get_id     = isset($_GET["id"])    ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS)      : null;
$get_alpha  = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";

// switch sur la variable action afin d'exécuter telle ou telle partie de code
switch($get_action){
    // dans ce cas-ci, on désire générer la liste des manufacturer ordonnée par ordre alphabétique et filtré par initiale
    case "liste":
        // définition de la variable view qui sera utilisée dans la partie <body> du html pour afficher une certaine partie du code
        $page_view = "product_liste";
        // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
        $alphabet = range('A', 'Z');
        $result = getProduct(0, $get_alpha);
    break;

    // dans ce cas-ci, on désire ajouter un manufacturer
    // deux cas de figure :
    // 1) présentation du formulaire (en utilisant les fonctions de form)
    // 2) insertion des données dans la DB et affichage d'un message de succès ou d'erreur à l'utilisateur
    case "add":
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "category_level_2_id" => ["int", null],  
            "admin_id" => ["int", null],             
            "shape_id" => ["int", null],             
            "designer_id" => ["int", null],          
            "manufacturer_id" => ["int", null],      
            "ad_title" => ["string", null],             
            "ad_description" => ["string", null],       
            "ad_description_detail" => ["string", null],
            "price_htva" => ["float", null],           
            "price_delivery" => ["string", null],
        ];
        // appel de la fonction qui est chargée d'initialiser et récupérer les données provenant du formulaire sur base du array $array_name
        init_tagname($array_name);
        /*
        // récupération / initialisation des données qui transitent via le formulaire via la méthode "classique"
        $post_nom           = isset($_POST["nom"])      ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)                : null;
        $post_prenom        = isset($_POST["prenom"])   ? filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)             : null;
        $post_description   = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)    : null;
        */

        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];

        // création des options du select catégories
        $getCategory_level_2_for_select = getCategory_level_2_for_select();
        $option = [0 => '=== Choix ==='];
        foreach ($getCategory_level_2_for_select as $key => $value) {
            $option[$value['category_level_2_id']] = $value['level_1'].' > '.$value['level_2'];
        }

        // création des options du select Shapes
        $getShape_for_select = getShape();
        $option_shape = [0 => '=== Choix ==='];
        foreach ($getShape_for_select as $key => $value) {
            $option_shape[$value['id']] = $value['shape_title'];
        }

        // création des options du select Designers
        $getDesigner_for_select = getDesigner();
        $option_designer = [0 => '=== Choix ==='];
        foreach ($getDesigner_for_select as $key => $value) {
            $option_designer[$value['id']] = $value['nom'].' '.$value['prenom'];
        }

        // création des options du select Manufacturer
        $getManufacturer_for_select = getManufacturer();
        $option_manufacturer = [0 => '=== Choix ==='];
        foreach ($getManufacturer_for_select as $key => $value) {
            $option_manufacturer[$value['id']] = $value['manufacturer'];
        }

        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter d'un product</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect('Categorie associées', ['name' => 'category_level_2_id'], $option, $post_category_level_2_id, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect("Etat de l'objet", ['name' => 'shape_id'], $option_shape, $post_shape_id, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect("Designer", ['name' => 'designer_id'], $option_designer, $post_designer_id, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addSelect("Manufacturer", ['name' => 'manufacturer_id'], $option_manufacturer, $post_manufacturer_id, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput("Nom de l'objet", ["type" => "text", "value" => $post_ad_title, "name" => "ad_title", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addTextarea('Brève déscription', array("name" => "ad_description", "class" => "u-full-width"), $post_ad_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addTextarea('Déscription complète', array("name" => "ad_description_detail", "class" => "u-full-width"), $post_ad_description_detail, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Prix htva', ["type" => "number", "value" => $post_price_htva, "name" => "price_htva", "class" => "u-full-width"], true, "six columns");
        $input[] = addInput('Prix de la livraison', ["type" => "number", "value" => $post_price_delivery, "name" => "price_delivery", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);
        // si form() ne retourne pas false et retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "product_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            // création d'un tableau qui contiendra les données à passer à la fonction d'insert
            $data_values                           = array();
            $data_values["category_level_2_id"]    = $post_category_level_2_id;
            $data_values["admin_id"]               = $_SESSION["admin_id"];
            $data_values["shape_id"]               = $post_shape_id;
            $data_values["designer_id"]            = $post_designer_id;
            $data_values["manufacturer_id"]        = $post_manufacturer_id;
            $data_values["ad_title"]               = $post_ad_title;
            $data_values["ad_description"]         = $post_ad_description;
            $data_values["ad_description_detail"]  = $post_ad_description_detail;
            $data_values["price_htva"]             = $post_price_htva;
            $data_values["price_delivery"]         = $post_price_delivery;

            // exécution de la requête
            if(insertProduct($data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }
            // on demande à afficher la liste des manufacturers
            $page_view = "product_liste";
            // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
            $result = getProduct(0, $get_alpha);
            // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
            $alphabet = range('A', 'Z');
        }
        break;

    case "update":
        // récupération avec filtre de netoyage FILTER_SANITIZE_NUMBER_INT
        $get_manufacturer_id = isset($_GET["manufacturer_id"]) ? filter_input(INPUT_GET, 'manufacturer_id', FILTER_SANITIZE_NUMBER_INT) : null;

        // récupération des données correspondant uniquement dans le cas du premier affichage du formulaire
        if(empty($_POST)){
            // récupération des infos dans la DB en utilisant l'id récupéré en GET
            $result = getManufacturer($get_manufacturer_id);

            $manufacturer   = $result[0]["manufacturer"];
            $description    = $result[0]["description"];
        }else{
            $manufacturer       = null;
            $description    = null;
        }
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "manufacturer" => ["string", $manufacturer],
            "description" => ["string", $description]
        ];
        // appel de la fonction qui est chargée d'initialiser et récupérer les données provenant du formulaire sur base du array $array_name
        init_tagname($array_name);

        /*
        // récupération / initialisation des données qui transitent via le formulaire via la méthode classique
        $post_nom           = isset($_POST["nom"])      ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)                : $lastname;
        $post_prenom        = isset($_POST["prenom"])   ? filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)             : $firstname;
        $post_description   = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)    : $description;
        */

        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Modifier un manufacturer</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Manufacturer', ["type" => "text", "value" => $post_manufacturer, "name" => "manufacturer", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addTextarea('Parcours / profil', array("name" => "description", "class" => "u-full-width"), $post_description, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&manufacturer_id=".$get_manufacturer_id."&id=".$get_id."&alpha=".$get_alpha, "post", $input);

        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "manufacturer_form";
        }else{
            $data_values                = array();
            $data_values["manufacturer"]= $post_manufacturer;
            $data_values["description"] = $post_description;

            if(updateManufacturer($get_manufacturer_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiée avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }
            // on demande à afficher la liste des manufacturers
            $page_view = "manufacturer_liste";
            // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
            $result = getManufacturer(0, $get_alpha);
            // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
            $alphabet = range('A', 'Z');
            //
            // si le paramètre id n'est pas null et qu'il est numérique alors cela veut dire qu'il est demandé d'afficher le détail d'un manufacturer en particulier
            if(!is_null($get_id) && is_numeric($get_id)){
                // utilisation de la fonction getManufacturer pour récupérer l'info du manufacturer précédemment sélectionné
                $result_detail = getManufacturer($get_id);
                // interrogation de la variable (array) result_detail pour en extraire les données récupérées et les attribuer à des variables qui seront utilisée dans la partie affichage (switch(view) => pagination)
                $detail_manufacturer   = $result_detail[0]["manufacturer"];
                $detail_description    = $result_detail[0]["description"];
                // paramètre permettant de "dire" si il faut oui ou non afficher un détail dans la partie affichage
                $show_description = true;
            }
        }

        break;

    case "showHide":
        // récupération avec filtre de netoyage FILTER_SANITIZE_NUMBER_INT
        $get_product_id = isset($_GET["product_id"]) ? filter_input(INPUT_GET, 'product_id', FILTER_SANITIZE_NUMBER_INT) : null;

        if(showHideProduct($get_product_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        // on demande à afficher la liste des manufacturers
        $page_view = "product_liste";
        // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
        $result = getProduct(0, $get_alpha);
        // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
        $alphabet = range('A', 'Z');
        break;
}

?>