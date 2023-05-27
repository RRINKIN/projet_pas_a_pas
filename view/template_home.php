<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
    <head>
        <title></title>
        <meta name="description" content="" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="language" content="fr" />
        <meta name="revisit-after" content="7 days" />
        <meta name="robots" content="index, follow" />
    </head>
    <body>
    <div class="main_content">
        <?php
            // header
            include_once("view/template_header.php");
        ?>
        <section class="container">
            <div class="row">
                <?php
                $limit = 6;
                for ($i=0; $i < $limit; $i++) { 
                    $ad_title           = $produits[$i]["ad_title"];
                    $ad_firstName       = $produits[$i]["firstname"];
                    $ad_lastName        = $produits[$i]["lastname"];
                    $ad_description     = $produits[$i]["ad_description"];
                    $ad_description_100 = substr($ad_description, 0, 100);
                    $ad_price           = $produits[$i]["price"];
                    $ad_manufacturer    = $produits[$i]["manufacturer"];
                    $ad_date            = $produits[$i]["ad_description_detail"];
                    $designer_id              = $produits[$i]["designer_id"];
                    // récupération de la date
                    $ad_year            = substr($ad_date, 22, 4);
                    echo 
                    '<article class="pres_product four columns border">
                    <div class="thumb">
                        <a href="././template_detail.html" title="">
                            <span class="rollover"><i>+</i></span>
                            <img src="upload/thumb/thumb_2163-1.jpg" alt="" />
                        </a>
                    </div>
                    <header>
                        <h4><a href="././template_detail.html" title="">'.$ad_title.' by '.$ad_firstName.' '.$ad_lastName.', '.$ad_year.'</a></h4>
                        <div class="subheader">
                            <span class="fa fa-bars"></span> <a href="" title=""></a>
                            <span class="separator">|</span>
                            <span class="fa fa-pencil"></span> <a href="index.php?p=admin_designer&id='.$designer_id.'" title="">'.$ad_firstName.' '.$ad_lastName.'</a>
                            <span class="separator">|</span>
                            <span class="fa fa-building-o"></span> <a href="" title=""><small style="opacity:.5;">-'.$ad_manufacturer.'-</small></a>
                        </div>
                    </header>
                    <div class="une_txt">
                        <p>
                            '.$ad_description_100.'
                            <a href="index.php?p=detail" title="">[...]</a>
                            <b>'.$ad_price.'€</b>
                        </p>
                    </div>
                </article>';
                }
                ?>

            <br /><br />
            <ul class='pagination'>
                <?php
                for ($i=1; $i < 10; $i++) { 
                    echo '<li><a href="" class="active">'.$i.'</a></li>';
                }
                ?>
                <li><a href='' title='résultats suivants'>></a></li>
                <li><a href='' title='derniers résultats'>>>|</a></li>
            </ul>

        </section>
        <?php
            // footer
            include_once("view/template_footer.php");
        ?>
    </div>

    <link rel="stylesheet" type="text/css" href="css/public/style.min.css" media="screen" defer="true" />
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

    </body>
</html>