<?php
verifConnexion();
include_once("lib/product.php");
include_once("lib/category_level_2.php");
include_once("lib/shape.php");
include_once("lib/designer.php");
include_once("lib/manufacturer.php");
include_once("lib/product.php");

$url_page = "admin_product";

// récupération/initialisation du paramètre "action" qui va permettre de diriger dans le switch vers la partie de code qui sera a exécuter
// si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut
$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) : "liste";

// récupération des informations passée en _GET pour cette partie du code (affichage de la liste + détail d'un manufacturer)
$get_id     = isset($_GET["id"])    ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS)      : null;
$get_alpha  = isset($_GET["alpha"]) ? filter_input(INPUT_GET, 'alpha', FILTER_SANITIZE_SPECIAL_CHARS)   : "A";

// switch sur la variable action afin d'exécuter telle ou telle partie de code
switch($get_action){
    // dans ce cas-ci, on désire générer la liste des products ordonnée par ordre alphabétique et filtré par initiale
    case "liste":
        // définition de la variable view qui sera utilisée dans la partie <body> du html pour afficher une certaine partie du code
        $page_view = "product_liste";
        // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
        $alphabet = range('A', 'Z');
        $result = getProduct(0, $get_alpha);
    break;

    // dans ce cas-ci, on désire ajouter un product
    // deux cas de figure :
    // 1) présentation du formulaire (en utilisant les fonctions de form)
    // 2) insertion des données dans la DB et affichage d'un message de succès ou d'erreur à l'utilisateur
    case "add":
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "category_level_2_id" => ["int", null],            
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
            // on demande à afficher la liste des products
            $page_view = "product_liste";
            // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
            $result = getProduct(0, $get_alpha);
            // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
            $alphabet = range('A', 'Z');
        }
        break;

    case "update":
        // récupération avec filtre de nettoyage FILTER_SANITIZE_NUMBER_INT
        $get_product_id = isset($_GET["product_id"]) ? filter_input(INPUT_GET, 'product_id', FILTER_SANITIZE_NUMBER_INT) : null;
    
        // récupération des données correspondant uniquement dans le cas du premier affichage du formulaire
        if(empty($_POST)){
            // récupération des infos dans la DB en utilisant l'id récupéré en GET
            $result                = getProduct($get_product_id);
            $category_level_2_id   = $result[0]["category_level_2_id"];
            $shape_id              = $result[0]["shape_id"];
            $designer_id           = $result[0]["designer_id"];
            $manufacturer_id       = $result[0]["manufacturer_id"];
            $ad_title              = $result[0]["ad_title"];
            $ad_description        = $result[0]["ad_description"];
            $ad_description_detail = $result[0]["ad_description_detail"];
            $price_htva            = $result[0]["price_htva"];
            $price_delivery        = $result[0]["price_delivery"];
        }else {
            $category_level_2_id   = null;
            $shape_id              = null;
            $designer_id           = null;
            $manufacturer_id       = null;
            $ad_title              = null;
            $ad_description        = null;
            $ad_description_detail = null;
            $price_htva            = null;
            $price_delivery        = null;
        }
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "category_level_2_id" => ["int", $category_level_2_id],             
            "shape_id" => ["int", $shape_id],             
            "designer_id" => ["int", $designer_id],          
            "manufacturer_id" => ["int", $manufacturer_id],      
            "ad_title" => ["string", $ad_title],             
            "ad_description" => ["string", $ad_description],       
            "ad_description_detail" => ["string", $ad_description_detail],
            "price_htva" => ["float", $price_htva],           
            "price_delivery" => ["string", $price_delivery],
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

        // création des options du select product
        $getManufacturer_for_select = getManufacturer();
        $option_manufacturer = [0 => '=== Choix ==='];
        foreach ($getManufacturer_for_select as $key => $value) {
            $option_manufacturer[$value['id']] = $value['manufacturer'];
        }

        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Modification d'un product</h4>");
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
        // Les numbers ci-dessous sont limités à la taille autorisée des champs dans la DB "float(10,2)"
        // erreur si on dépasse cette valeur.
        // Un if pourrait être fait avant "updateProduct". 
        $input[] = addInput('Prix htva', ["type" => "number", "value" => $post_price_htva, "name" => "price_htva", "class" => "u-full-width"], true, "six columns");
        $input[] = addInput('Prix de la livraison', ["type" => "number", "value" => $post_price_delivery, "name" => "price_delivery", "class" => "u-full-width"], true, "six columns");
        $input[] = addLayout("</div>");
        //addFileMulti($label, $properties, $required = false, $div_class = "", $copy_dir = "", $qty = 2, $new_name = "")
        $input[] = addLayout("<div class='row'>");
        $input[] = addFileMulti("Photo de l'article", ["name" => "photo[]", "id" => "photo"], false, "u-full-width", "../upload/normal/", 4, "[$get_product_id]-[numéro de la photo].jpg");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&product_id=".$get_product_id."&id=".$get_id."&alpha=".$get_alpha, "post", $input);

        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "product_form";
        }else {
            $data_values                            = array();         
            $data_values["category_level_2_id"]     = $post_category_level_2_id; 
            $data_values["admin_id"]                = $_SESSION["admin_id"]; 
            $data_values["shape_id"]                = $post_shape_id;            
            $data_values["designer_id"]             = $post_designer_id;          
            $data_values["manufacturer_id"]         = $post_manufacturer_id;      
            $data_values["ad_title"]                = $post_ad_title;     
            $data_values["ad_description"]          = $post_ad_description;       
            $data_values["ad_description_detail"]   = $post_ad_description_detail;
            $data_values["price_htva"]              = $post_price_htva;
            $data_values["price_delivery"]          = $post_price_delivery;       

            if(updateProduct($get_product_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiée avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }
            // on demande à afficher la liste des products
            $page_view = "product_liste";
            // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
            $result = getProduct(0, $get_alpha);
            // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
            $alphabet = range('A', 'Z');
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

        // on demande à afficher la liste des products
        $page_view = "product_liste";
        // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
        $result = getProduct(0, $get_alpha);
        // génération du tableau contenant toutes les lettres de l'alphabet et qui sera utilisée dans la partie affichage (switch(view) => pagination)
        $alphabet = range('A', 'Z');
        break;
}

?>