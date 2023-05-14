<?php
function getManufacturer($id = 0, $alpha = ""){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonction des infos passées en paramètre
    $cond = !empty($alpha) ? " manufacturer LIKE '".$alpha."%' " : " 1 ";
    $cond .= $id > 0 ? " AND manufacturer_id = ".$id : "";

    // requete permettant de récupérer les manufacturers suivant le(s) filtre(s)
    $sql = "SELECT manufacturer_id as id, manufacturer, description, is_visible 
                FROM manufacturer 
                WHERE ".$cond." 
                ORDER BY manufacturer ASC;";
    // envoi de la requete vers le serveur de DB et stockaqge du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertManufacturer($data){
    $manufacturer   = convert2DB($data["manufacturer"]);
    $description    = convert2DB($data["description"]);

    $sql = "INSERT INTO manufacturer
                        (manufacturer, description) 
                    VALUES
                        ('$manufacturer', '$description');
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateManufacturer($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $manufacturer   = convert2DB($data["manufacturer"]);
    $description    = convert2DB($data["description"]);

    $sql = "UPDATE manufacturer 
                SET 
                    manufacturer = '".$manufacturer."',
                    description = '".$description."'
            WHERE manufacturer_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideManufacturer($id){
    if(!is_numeric($id)){
        return false;
    }
    // Méthode CASE WHEN sql
    $sql = "UPDATE manufacturer 
                SET is_visible = CASE 
                    WHEN is_visible = '1' THEN '0' 
                    ELSE '1' 
                    END
            WHERE manufacturer_id = ".$id.";";
    // exécution de la requête
    return ExecRequete($sql);
    /*
    // Méthode classique en php
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM manufacturer WHERE manufacturer_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE manufacturer SET is_visible = '".$nouvel_etat."' WHERE manufacturer_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
    */
}

?>