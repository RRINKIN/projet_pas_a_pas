<?php
function getProduct($id = 0, $alpha = ""){
    if(!is_numeric($id)){
        return false;
    }
    // création de la condition WHERE en fonctions des infos passées en paramètre
    $cond = !empty($alpha) ? " ad_title LIKE '".$alpha."%' " : " 1 ";
    $cond .= $id > 0 ? " AND ad_id = ".$id : "";

    // requete permettant de récupérer les products suivant le(s) filtre(s)
    $sql = "SELECT A.*, D.firstname, D.lastname, M.manufacturer, C1.level_1, C2.level_2 
            FROM ad AS A
            LEFT JOIN designer AS D ON D.designer_id = A.designer_id
            LEFT JOIN manufacturer AS M ON M.manufacturer_id = A.manufacturer_id
            LEFT JOIN category_level_2 AS C2 ON C2.category_level_2_id = A.category_level_2_id
            LEFT JOIN category_level_1 AS C1 ON C2.category_level_1_id = C1.category_level_1_id
            WHERE ".$cond."
            ORDER BY ad_title ASC";
    // envoi de la requete vers le serveur de DB et stockaqge du résultat obtenu dans la variable result (array qui contiendra toutes les données récupérées)
    // renvoi de l'info
    return requeteResultat($sql);
}

function insertProduct($data){
    $category_level_2_id         = convert2DB($data["category_level_2_id"]);
    $admin_id                    = convert2DB($data["admin_id"]);
    $shape_id                    = convert2DB($data["shape_id"]);
    $designer_id                 = convert2DB($data["designer_id"]);
    $manufacturer_id             = convert2DB($data["manufacturer_id"]);
    $ad_title                    = convert2DB($data["ad_title"]);
    $ad_description              = convert2DB($data["ad_description"]);
    $ad_description_detail       = convert2DB($data["ad_description_detail"]);
    $price_htva                  = convert2DB($data["price_htva"]);
    $amount_tva                  = $price_htva*0.21;
    $price                       = $price_htva+$amount_tva;
    $price_delivery              = convert2DB($data["price_delivery"]);
    $date_add                    = date('Y-m-d h:i:s');

    $sql = "INSERT INTO ad
                        (category_level_2_id, 
                        admin_id, 
                        shape_id, 
                        designer_id, 
                        manufacturer_id, 
                        ad_title, 
                        ad_description, 
                        ad_description_detail, 
                        price, 
                        price_htva, 
                        amount_tva,
                        price_delivery, 
                        date_add) 
                    VALUES
                        (
                        '$category_level_2_id',  
                        '$admin_id',             
                        '$shape_id',             
                        '$designer_id',          
                        '$manufacturer_id',      
                        '$ad_title',             
                        '$ad_description',       
                        '$ad_description_detail',
                        '$price',                
                        '$price_htva',           
                        '$amount_tva',           
                        '$price_delivery',       
                        '$date_add'             
                        );
                    ";
    // exécution de la requête
    return ExecRequete($sql);
}

function updateProduct($id, $data){
    if(!is_numeric($id)){
        return false;
    }

    $category_level_2_id         = convert2DB($data["category_level_2_id"]);
    $admin_id                    = convert2DB($data["admin_id"]);
    $shape_id                    = convert2DB($data["shape_id"]);
    $designer_id                 = convert2DB($data["designer_id"]);
    $manufacturer_id             = convert2DB($data["manufacturer_id"]);
    $ad_title                    = convert2DB($data["ad_title"]);
    $ad_description              = convert2DB($data["ad_description"]);
    $ad_description_detail       = convert2DB($data["ad_description_detail"]);
    $price_htva                  = convert2DB($data["price_htva"]);
    $amount_tva                  = $price_htva*0.21;
    $price                       = $price_htva+$amount_tva;
    $price_delivery              = convert2DB($data["price_delivery"]);
    $date_add                    = date('Y-m-d h:i:s'); // je prend garde la date de mise à jour

    $sql = "UPDATE ad 
            SET
                category_level_2_id     = '".$category_level_2_id."', 
                admin_id                = '".$admin_id."', 
                shape_id                = '".$shape_id."', 
                designer_id             = '".$designer_id."', 
                manufacturer_id         = '".$manufacturer_id."', 
                ad_title                = '".$ad_title."', 
                ad_description          = '".$ad_description."', 
                ad_description_detail   = '".$ad_description_detail."', 
                price                   = '".$price."', 
                price_htva              = '".$price_htva."', 
                amount_tva              = '".$amount_tva."',
                price_delivery          = '".$price_delivery."', 
                date_add                = '".$date_add."'            
            WHERE
                ad_id = ".$id.";
            ";

    // exécution de la requête
    // retourne TRUE/FALSE en fonction de la réponse
    return ExecRequete($sql);
}

function showHideProduct($id){
    if(!is_numeric($id)){
        return false;
    }
    // Méthode CASE WHEN sql
    $sql = "UPDATE ad 
            SET is_visible = CASE 
                WHEN is_visible = '1' THEN '0' 
                ELSE '1' 
                END
            WHERE ad_id = ".$id.";";
    // exécution de la requête
    return ExecRequete($sql);
    /*
    // Méthode classique en php
    // récupération de l'état avant mise à jour
    $sql = "SELECT is_visible FROM product WHERE product_id = ".$id.";";
    $result = requeteResultat($sql);
    if(is_array($result)){
        $etat_is_visble = $result[0]["is_visible"];

        $nouvel_etat = $etat_is_visble == "1" ? "0" : "1";
        // mise à jour vers le nouvel état
        $sql = "UPDATE product SET is_visible = '".$nouvel_etat."' WHERE product_id = ".$id.";";
        // exécution de la requête
        return ExecRequete($sql);

    }else{
        return false;
    }
    */
}

?>