<?php
verifConnexion();
include_once("lib/category_level_2.php");

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

        $result = getCategory_level_2(0);

        // si le paramètre id n'est pas null et qu'il est numérique alors cela veut dire qu'il est demandé d'afficher le détail d'un category_level_1 en particulier
        if(!is_null($get_id) && is_numeric($get_id)){
            // utilisation de la fonction getcategory_level_1 pour récupérer un category_level_1 en particulier
            $result_detail               = getCategory_level_2($get_id);
            // interrogation de la variable (array) result_detail pour en extraire les données récupérées et les attribuer à des variables qui seront utilisée dans la partie affichage (switch(view) => pagination)
            $detail_category_level_2     = $result_detail[0]["category_level_2"];
        }
        break;

    // dans ce cas-ci, on désire ajouter un category_level_2
    // deux cas de figure :
    // 1) présentation du formulaire (en utilisant les fonctions de form)
    // 2) insertion des données dans la DB et affichage d'un message de succès ou d'erreur à l'utilisateur
    case "add":
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "category_level_2" => ["string", null],
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
        // ajout des différents champs du formulaire
        $input[] = addLayout("<h4>Ajouter une sous-catégorie</h4>");
        $input[] = addLayout("<div class='row'>");
        $input[] = addInput('Category_level_1', ["type" => "text", "value" => $post_category_level_2, "name" => "category_level_2", "class" => "u-full-width"], true, "twelve columns");
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
            //
            // si le paramètre id n'est pas null et qu'il est numérique alors cela veut dire qu'il est demandé d'afficher le détail d'un category_level_1 en particulier
            if(!is_null($get_id) && is_numeric($get_id)){
                // utilisation de la fonction getcategory_level_1 pour récupérer l'info du category_level_1 précédemment sélectionné
                $result_detail = getCategory_level_2($get_id);
                // interrogation de la variable (array) result_detail pour en extraire les données récupérées et les attribuer à des variables qui seront utilisée dans la partie affichage (switch(view) => pagination)
                $detail_category_level_2       = $result_detail[0]["category_level_2"];
            }
        }
        break;

    case "update":
        // récupération avec filtre de netoyage FILTER_SANITIZE_NUMBER_INT
        $get_category_level_2_id = isset($_GET["category_level_2_id"]) ? filter_input(INPUT_GET, 'category_level_2_id', FILTER_SANITIZE_NUMBER_INT) : null;
        // récupération des données correspondant uniquement dans le cas du premier affichage du formulaire
        if(empty($_POST)){
            // récupération des infos dans la DB en utilisant l'id récupéré en GET
            $result             = getCategory_level_2($get_category_level_2_id);
            $category_level_2   = $result[0]["level_2"];
        }else{
            $category_level_2   = null;
        }
        // récupération / initialisation des données qui transitent via le formulaire via la fonction init_tagname
        $array_name = [
            "category_level_2" => ["string", $category_level_2]
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
        $input[] = addLayout("<h4>Modifier une catégorie level 2</h4>");
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
            // si le paramètre id n'est pas null et qu'il est numérique alors cela veut dire qu'il est demandé d'afficher le détail d'un category_level_1 en particulier
            if(!is_null($get_id) && is_numeric($get_id)){
                // utilisation de la fonction getcategory_level_2 pour récupérer l'info du category_level_2 précédemment sélectionné
                $result_detail = getCategory_level_2($get_id);
                // interrogation de la variable (array) result_detail pour en extraire les données récupérées et les attribuer à des variables qui seront utilisée dans la partie affichage (switch(view) => pagination)
                $detail_category_level_2       = $result_detail[0]["category_level_2"];
                // paramètre permettant de "dire" si il faut oui ou non afficher un détail dans la partie affichage
                $show_description = true;
            }
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
        $result = getCategory_level_2(0);
        //
        // si le paramètre id n'est pas null et qu'il est numérique alors cela veut dire qu'il est demandé d'afficher le détail d'un category_level_1 en particulier
        if(!is_null($get_id) && is_numeric($get_id)){
            // utilisation de la fonction getcategory_level_1 pour récupérer l'info du category_level_2 précédemment sélectionné
            $result_detail    = getCategory_level_2($get_id);
            // interrogation de la variable (array) result_detail pour en extraire les données récupérées et les attribuer à des variables qui seront utilisée dans la partie affichage (switch(view) => pagination)
            $detail_detail    = $result_detail[0]["category_level_2"];
            // paramètre permettant de "dire" si il faut oui ou non afficher un détail dans la partie affichage
            $show_description = true;
        }

        break;
}

?>