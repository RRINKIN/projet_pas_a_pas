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
            RIGHT JOIN category_level_1 ON category_level_1.category_level_1_id = category_level_2.category_level_1_id 
            ".$cond." 
            ORDER BY level_1 ASC;";

    // envoi de la requete vers le serveur de DB et stockage du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertCategory_level_1($data){
    $category   = convert2DB($data["category_level_2"]);
    $sql        = "INSERT INTO category_level_2
                        (level_2) 
                    VALUES
                        ('$category');
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateCategory_level_1($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $category_level_1       = convert2DB($data["category_level_2"]);
    $sql = "UPDATE level_2 
                SET 
                    level_2 = '".$category_level_2."',
            WHERE category_level_2_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideCategory_level_1($id){
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