<?php
verifConnexion();
include_once("lib/category_level_2.php");
include_once("lib/category_level_1.php");

$url_page = "admin_category_level_2";

// récupération/initialisation du paramètre "action" qui va permettre de diriger dans le switch vers la partie de code qui sera a exécuter
// si aucune action n'est définie via le paramètre _GET alors l'action "liste" sera attribuée par défaut
$get_action = isset($_GET["action"]) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) : "liste";

// récupération des informations passée en _GET pour cette partie du code (affichage de la liste + détail d'un category_level_1)
$get_id     = isset($_GET["category_level_id"])    ? filter_input(INPUT_GET, 'category_level_id', FILTER_SANITIZE_SPECIAL_CHARS)      : null;

// switch sur la variable action afin d'exécuter telle ou telle partie de code
switch($get_action){
    // dans ce cas-ci, on désire générer la liste des category_level_1 ordonnée par ordre alphabétique et filtré par initiale
    case "liste":

        // définition de la variable view qui sera utilisée dans la partie <body> du html pour afficher une certaine partie du code
        $page_view = "category_level_2_liste";

        // definir une boucle qui imbrique les "categ_lvl2" dans les "categ_lvl_1"
        $result = getCategory_level_1(0);
        foreach ($result as $key => $level1_value) {
            $result[$key]['children'] = getCategory_level_2_by_level_1($level1_value["category_level_1_id"]);
        }
        //print_q($result);
    break;

    // dans ce cas-ci, on désire ajouter un category_level_2
    // deux cas de figure :
    // 1) présentation du formulaire (en utilisant les fonctions de form)
    // 2) insertion des données dans la DB et affichage d'un message de succès ou d'erreur à l'utilisateur
    case "add":
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "category_level_2" => ["string", null],
            "category_level_1_id" => ["int", null],
        ];
        // appel de la fonction qui est chargée d'initialiser et récupérer les données provenant du formulaire sur base du array $array_name
        init_tagname($array_name);
        /*
        // récupération / initialisation des données qui transitent via le formulaire via la méthode "classique"
        $post_nom           = isset($_POST["nom"])      ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)                : null;
        $post_prenom        = isset($_POST["prenom"])   ? filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)             : null;
        $post_description   = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)    : null;
        */

        // création du menu select sur base de la catégorie Level_1
        $option = [0 => '=== choix ==='];
        $category_level_1 = getCategory_level_1(0);
        foreach ($category_level_1 as $key => $value) {
            $option[$value['category_level_1_id']] = $value['level_1']; 
        }

        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter une sous-catégorie</h4>");
        $input[] = addLayout("<div class='row'>");
        //addSelect($label, $properties, $option, $defaultValue, $required = false, $div_class = "")
        $input[] = addSelect('Category_level_1', ['name' => 'category_level_1_id'], $option, $post_category_level_1_id, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Category_level_2', ["type" => "text", "value" => $post_category_level_2, "name" => "category_level_2", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=add", "post", $input);
        // si form() ne retourne pas false et retourne un string alors le formulaire doit être affiché
        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "category_level_2_form";

            // si form() retourne false, l'insertion peut avoir lieu
        }else{
            // création d'un tableau qui contiendra les données à passer à la fonction d'insert
            $data_values                        = array();
            $data_values["category_level_1_id"] = $post_category_level_1_id;
            $data_values["category_level_2"]    = $post_category_level_2;
            
            // exécution de la requête
            if(insertCategory_level_2($data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données insérées avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de l'insertion des données</p>";
                $msg_class = "error";
            }
            // on demande à afficher la liste des category_level_1s
            $page_view = "category_level_2_liste";
            // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
            $result = getCategory_level_2(0);
        }
    break;

    case "update":
        // récupération avec filtre de nettoyage FILTER_SANITIZE_NUMBER_INT
        $get_category_level_2_id = isset($_GET["category_level_2_id"]) ? filter_input(INPUT_GET, 'category_level_2_id', FILTER_SANITIZE_NUMBER_INT) : null;
        // récupération des données correspondant uniquement dans le cas du premier affichage du formulaire
        if(empty($_POST)){
            $result             = getCategory_level_2($get_category_level_2_id);
            $category_level_1   = $result[0]["category_level_1_id"];
            $category_level_2   = $result[0]["level_2"];
        }else {
            $category_level_1   = 0;
            $category_level_2   = 0;
        }
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "category_level_1_id" => ["int", $category_level_1],
            "category_level_2" => ["string", $category_level_2],
        ];
        // appel de la fonction qui est chargée d'initialiser et récupérer les données provenant du formulaire sur base du array $array_name
        init_tagname($array_name);

        /*
        // récupération / initialisation des données qui transitent via le formulaire via la méthode classique
        $post_nom           = isset($_POST["nom"])      ? filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS)                : $lastname;
        $post_prenom        = isset($_POST["prenom"])   ? filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS)             : $firstname;
        $post_description   = isset($_POST["description"])  ? filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)    : $description;
        */

        // création du menu select sur base de la catégorie Level_1
        $option = [0 => '=== choix ==='];
        $category_level_1 = getCategory_level_1(0);
        foreach ($category_level_1 as $key => $value) {
            $option[$value['category_level_1_id']] = $value['level_1']; 
        }

        // initialisation du array qui contiendra la définitions des différents champs du formulaire
        $input = [];
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Modifier une catégorie level 2</h4>");
        $input[] = addLayout("<div class='row'>");
        //addSelect($label, $properties, $option, $defaultValue, $required = false, $div_class = "")
        $input[] = addSelect('Category_level_1', ['name' => 'category_level_1_id'], $option, $post_category_level_1_id, true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Category_level_2', ["type" => "text", "value" => $post_category_level_2, "name" => "category_level_2", "class" => "u-full-width"], true, "twelve columns");
        $input[] = addLayout("</div>");
        $input[] = addSubmit(["value" => "envoyer", "name" => "submit"], "\t\t<br />\n");
        // appel de la fonction form qui est chargée de générer le formulaire à partir du array de définition des champs $input ainsi que de la vérification de la validitée des données si le formulaire été soumis
        $show_form = form("form_contact", "index.php?p=".$url_page."&action=update&category_level_2_id=".$get_category_level_2_id."&id=".$get_id, "post", $input);

        if($show_form != false){
            // définition de la variable view qui sera utilisée pour afficher la partie du <body> du html nécessaire à l'affichage du formulaire
            $page_view = "category_level_2_form";
        }else{
            $data_values                        = array();
            $data_values["category_level_1_id"] = $post_category_level_1_id;
            $data_values["category_level_2"]    = $post_category_level_2;

            if(updateCategory_level_2($get_category_level_2_id, $data_values)){
                // message de succes qui sera affiché dans le <body>
                $msg = "<p>données modifiée avec succès</p>";
                $msg_class = "success";
            }else{
                // message d'erreur qui sera affiché dans le <body>
                $msg = "<p>erreur lors de la modification des données</p>";
                $msg_class = "error";
            }
            // on demande à afficher la liste des category_level_1s
            $page_view = "category_level_2_liste";
            // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
            $result = getCategory_level_2(0);
        }

    break;

    case "showHide":
        // récupération avec filtre de netoyage FILTER_SANITIZE_NUMBER_INT
        $get_category_level_2_id = isset($_GET["category_level_2_id"]) ? filter_input(INPUT_GET, 'category_level_2_id', FILTER_SANITIZE_NUMBER_INT) : null;

        if(showHideCategory_level_2($get_category_level_2_id)){
            // message de succes qui sera affiché dans le <body>
            $msg = "<p>mise à jour de l'état réalisée avec succès</p>";
            $msg_class = "success";
        }else{
            // message d'erreur qui sera affiché dans le <body>
            $msg = "<p>erreur lors de la mise à jour de l'état</p>";
            $msg_class = "error";
        }

        // on demande à afficher la liste des category_level_2
        $page_view = "category_level_2_liste";
        // pour afficher cette liste, on a besoin de les récupérer au préalable dans la db
        $result = getCategory_level_1(0);
        foreach ($result as $key => $level1_value) {
            $result[$key]['children'] = getCategory_level_2_by_level_1($level1_value["category_level_1_id"]);
        }
    break;
}

?>