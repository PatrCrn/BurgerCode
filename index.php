<?php
    require 'admin/database.php';
    $db = Database::connect();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Burger Code</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/styles.css">
        <script src="js/script.js"></script>
    </head>
    <body>
        <div class="container site">
            <h1 class="logoResto">
                <i class="fas fa-utensils"></i> Burger Code <i class="fas fa-utensils"></i>
            </h1>
            <nav>
                <ul class="nav nav-pills">
                    <?php

                        $requete = $db->query('SELECT * FROM categories');
                        while($row = $requete->fetch()) {
                            if ($row['id'] == 1) {
                                echo '<li role="presentation" class="active"><a href="#' . $row['name'] . '" data-toggle="tab">' . $row['name'] . '</a></li>';
                            } else {
                                echo '<li role="presentation"><a href="#' . $row['name'] . '" data-toggle="tab">' . $row['name'] . '</a></li>';
                            }
                        }
                    ?>
                </ul>
            </nav>
            <div class="tab-content">
                <?php
                    $req = $db->query('SELECT * FROM categories');
                    while ($cat = $req->fetch()) {
                        if($cat['id'] == 1) {
                            echo '<div class="tab-pane active" id="' . $cat['name'] . '">
                                <div class="row">';
                        } else {
                            echo '<div class="tab-pane" id="' . $cat['name'] . '">
                                <div class="row">';
                        }
                        $requete = $db->prepare('SELECT name, description, price, image
                                                FROM items
                                                WHERE category = ?');
                        $requete->execute(array($cat['id']));

                        while ($menu = $requete->fetch()) {
                            echo    '<div class="col-md-6 col-lg-4">
                                        <div class="thumbnail">
                                            <img src="images/' . $menu['image'] . '" class="img-thumbnail" alt="' . $menu['name'] . '">
                                            <div class="top-right">' . number_format((float)$menu['price'], 2, '.', '') . '€</div>
                                            <div class="caption">
                                                <h4>' . $menu['name'] . '</h4>
                                                <p>' . $menu['description'] . '</p>
                                                <a href="#" class="btn btn-order" role="button"><i class="fas fa-shopping-cart"></i> commander</a>
                                            </div>
                                        </div>
                                    </div>';
                        }
                        echo '</div>
                            </div>';
                    }
                    Database::disconnect();
                ?>
            </div>
        </div>
    </body>
</html>