<?php
function getAdminUser($id = 0, $alpha = ""){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonction des infos passées en paramètre
    $cond = !empty($alpha) ? " login LIKE '".$alpha."%' " : " 1 ";
    $cond .= $id > 0 ? " AND admin_id = ".$id : "";

    // requete permettant de récupérer les manufacturers suivant le(s) filtre(s)
    $sql = "SELECT * 
            FROM admin 
            WHERE ".$cond." 
            ORDER BY login ASC;";
    // envoi de la requete vers le serveur de DB et stockaqge du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertAdminUser($data){
    $login          = convert2DB($data["login"]);
    $password       = convert2DB($data["password"]);
    $pseudo         = convert2DB($data["pseudo"]);
    $level_access   = convert2DB($data["level_access"]);
    $street         = convert2DB($data["street"]);
    $num            = convert2DB($data["num"]);
    $zip            = convert2DB($data["zip"]);
    $city           = convert2DB($data["city"]);

    $sql = "INSERT INTO admin
                        (login,
                        password,
                        pseudo,
                        level_access,
                        street,
                        num,
                        zip,
                        city) 
                    VALUES
                        ('$login',
                        '$password',
                        '$pseudo',
                        '$level_access',
                        '$street',
                        '$num',
                        '$zip',
                        '$city');
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateAdminUser($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $login          = convert2DB($data["login"]);
    $password       = convert2DB($data["password"]);
    $pseudo         = convert2DB($data["pseudo"]);
    $level_access   = convert2DB($data["level_access"]);
    $street         = convert2DB($data["street"]);
    $num            = convert2DB($data["num"]);
    $zip            = convert2DB($data["zip"]);
    $city           = convert2DB($data["city"]);

    $sql = "UPDATE admin 
            SET 
                login           = '".$login."',
                password        = '".$password."',
                level_access    = '".$level_access."',
                street          = '".$street."',
                num             = '".$num."',
                zip             = '".$zip."',
                city            = '".$city."',
            WHERE admin_id = ".$id.";
            ";
    // exécution de la requête
    return ExecRequete($sql);
}

function showHideAdminUser($id){
    if(!is_numeric($id)){
        return false;
    }
    // Méthode CASE WHEN sql
    $sql = "UPDATE admin
            SET is_visible = CASE 
                WHEN is_visible = '1' THEN '0' 
                ELSE '1' 
                END
            WHERE admin_id = ".$id.";";
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