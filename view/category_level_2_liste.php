<div class="row">
    <div class="six columns">
        <form action="index.php?p=<?php echo $url_page; ?>" method="get" id="search">
            <div>
                <input type="hidden" name="p" value="<?php echo $url_page; ?>" />
                <a href="index.php?p=<?php echo $url_page; ?>&action=add" class="button"><i class="fas fa-user-plus"></i> Ajouter</a>
            </div>
        </form>
    </div>
</div>



<div class="row">
    <div class="four columns">
        <?php
        // Récupération d'un array dans un array et ainsi avoir les sous-catégories
        // à l'intérieur des catégories
        $result = getCategory_level_1(0);
        foreach ($result as $key => $level1_value) {
            $result[$key]['children'] = getCategory_level_2_by_level_1($level1_value["category_level_1_id"]);
        }
        // Double each pour que affichage des sous-catégorie au sein des catégories
        if(is_array($result)){
            foreach ($result as $key => $value) {
                echo "<h4>".$value["level_1"]."</h4>";
                foreach($value["children"] as $r){
                    $id_2               = $r["category_level_2_id"];
                    $category_level_2   = $r["level_2"];
                    $is_visible         = $r["is_visible"];
    
                    // nb : peut également se faire dans la requête sql pour une raison d'optimisation avec un CASE THEN
                    if($is_visible == "1"){
                        $txt_category_level_2   = $category_level_2;
                        $txt_visible            = "<i class=\"fas fa-eye-slash\"></i>";
                        $txt_title              = "Masquer cette entrée";
                    }else{
                        $txt_category_level_2   = "<s style='color:#b1b1b1;'>" .$category_level_2."</s>";
                        $txt_visible            = "<i class=\"fas fa-eye\"></i>";
                        $txt_title              = "Réactiver cette entrée";
                    }
    
                    echo "
                        <p>
                            <a href='index.php?p=".$url_page."&category_level_2_id=".$id_2."&action=update&id=".$get_id."' title='éditer cette entrée' class='bt-action'><i class=\"far fa-edit\"></i></a> 
                            <a href='index.php?p=".$url_page."&category_level_2_id=".$id_2."&action=showHide&id=".$get_id."' title='".$txt_title."' class='bt-action'>".$txt_visible."</a> 
                            ".$txt_category_level_2."
                        </p>";
                }
            }
        }
        ?>
    </div>
    <div class="eight columns">
        <?php
        echo isset($msg) && !empty($msg) ? "<div class='missingfield $msg_class'>".$msg."</div>" : "";
        ?>
    </div>
</div>