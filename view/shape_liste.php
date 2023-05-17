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
    <div class="twelve columns">
        <?php
        if(is_array($result)){
            foreach($result as $r){
                $id         = $r["id"];
                $shape      = $r["shape_title"];
                $is_visible = $r["is_visible"];

                // nb : peut également se faire dans la requête sql pour une raison d'optimisation avec un CASE THEN
                if($is_visible == "1"){
                    $txt_shape = $shape;
                    $txt_visible = "<i class=\"fas fa-eye-slash\"></i>";
                    $txt_title = "Masquer cette entrée";
                }else{
                    $txt_shape = "<s style='color:#b1b1b1;'>" .$shape."</s>";
                    $txt_visible = "<i class=\"fas fa-eye\"></i>";
                    $txt_title = "Réactiver cette entrée";
                }

                echo "<p>
                        <a href='index.php?p=".$url_page."&shape_id=".$id."&action=update&id=".$get_id."' title='éditer cette entrée' class='bt-action'><i class=\"far fa-edit\"></i></a> 
                        <a href='index.php?p=".$url_page."&shape_id=".$id."&action=showHide&id=".$get_id."' title='".$txt_title."' class='bt-action'>".$txt_visible."</a> 
                        ".$txt_shape." 
                    </p>";
            }
        }
        ?>
    </div>
</div>