<header class="u-full-width">
    <div class="container row">
        <h2 class="six columns" id="logo">
            <a href="./" title=""><img src="images/content/logo.png" alt="" /></a>
        </h2>
        <div class="six columns">
            <form action="index.php?p=<?php echo $url_page; ?>" method="get">
                <input type="text" name="alpha" value="" placeholder="Que recherchez-vous ?" />
                <input type="submit" name="submit" value="OK" />
            </form>
        </div>
    </div>
</header>
<nav class="container" id="nav">
    <ul class="row">
        <?php
        foreach ($menu as $key => $value) {
            $url_menu = "index.php?p=template_category&category_level_1_id=".$value["category_level_1_id"];
            $name_menu = $value["level_1"];
            echo '<li class="three columns"><a href="'.$url_menu.'" title="">'.$name_menu.'</a></li>';
        }
        ?>
    </ul>
</nav>
<div id='search' class='u-full-width'>
    <div id="trail" class="container row">
        <ul>
            <li>Vous êtes ici :</li>
            <?php echo "<li>".$page_view."</li>";?>
        </ul>
    </div>
</div>