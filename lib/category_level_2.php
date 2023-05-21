<?php
function getCategory_level_2($id = 0){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonctions des infos passées en paramètre
    $cond = $id > 0 ? " WHERE category_level_2_id = ".$id : "";

    // requete permettant de récupérer les category_level_2 suivant le(s) filtre(s)
    $sql = "SELECT * 
            FROM category_level_2
            ".$cond." 
            ORDER BY level_2 ASC;";

    // envoi de la requete vers le serveur de DB et stockage du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function getCategory_level_2_for_select($id = 0){
    if(!is_numeric($id)){
        return false;
    }

    // requete permettant de récupérer les category_level_2 suivant le(s) filtre(s)
    $sql = "SELECT category_level_2_id, level_2, level_1 
            FROM category_level_2 
            LEFT JOIN category_level_1 ON category_level_1.category_level_1_id = category_level_2.category_level_1_id 
            ORDER BY level_1 ASC, level_2 ASC;";

    // envoi de la requete vers le serveur de DB et stockage du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function getCategory_level_2_by_level_1($id){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonctions des infos passées en paramètre
    $cond = $id > 0 ? " WHERE category_level_1_id = ".$id : "";

    // requete permettant de récupérer les category_level_2 suivant le(s) filtre(s)
    $sql = "SELECT * 
            FROM category_level_2
            ".$cond." 
            ORDER BY level_2 ASC;";

    // envoi de la requete vers le serveur de DB et stockage du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertCategory_level_2($data){
    $category               = convert2DB($data["category_level_2"]);
    $category_level_1_id    = convert2DB($data["category_level_1_id"]);
    $sql        = "INSERT INTO category_level_2
                        (level_2, category_level_1_id)
                    VALUES
                        ('$category', '$category_level_1_id');
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateCategory_level_2($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $category_level_1_id    = convert2DB($data["category_level_1_id"]);
    $category_level_2       = convert2DB($data["category_level_2"]);
    
    $sql = "UPDATE category_level_2 
                SET 
                    category_level_1_id = '".$category_level_1_id."',
                    level_2 = '".$category_level_2."'
            WHERE category_level_2_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideCategory_level_2($id){
    if(!is_numeric($id)){
        return false;
    }
    // Méthode CASE WHEN sql
    $sql = "UPDATE category_level_2 
                SET is_visible = CASE 
                    WHEN is_visible = '1' THEN '0' 
                    ELSE '1' 
                END
            WHERE category_level_2_id = ".$id.";";
    // exécution de la requête
    return ExecRequete($sql);
}

?>