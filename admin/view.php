<?php
    if(!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    function checkInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Burger Code</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/styles.css">
        <script src="../js/script.js"></script>
    </head>

    <body>
        <h1 class="logoResto">
            <i class="fas fa-utensils"></i> Burger Code <i class="fas fa-utensils"></i>
        </h1>
        <div class="container admin">
            <div class="row">
                <?php
                    require 'database.php';
                    $db = Database::connect();
                    $requete = $db->prepare('SELECT it.name, it.description, it.price, it.image, cat.name AS catNom 
                                            FROM items it
                                            LEFT JOIN categories cat ON cat.id = it.category 
                                            WHERE it.id = ?');
                    $requete->execute(array($id));
                    $item = $requete->fetch();
                    Database::disconnect();

                    echo '<div class="col-md-6">
                                <h1><strong>Voir un item</strong></h1>
                                <br>
                                <h6><strong>Nom:</strong> ' . $item['name'] . '</h6>
                                <h6><strong>Description:</strong> ' . $item['description'] . '</h6>
                                <h6><strong>Prix:</strong> ' . number_format((float)$item['price'], 2, '.', '') . ' €</h6>
                                <h6><strong>Catégorie:</strong> ' . $item['catNom'] . '</h6>
                                <h6 style="margin-bottom: 30px;"><strong>Image:</strong> ' . $item['image'] . '</h6>
                                <a class="btn btn-primary" href="index.php"><i class="fas fa-arrow-left"></i> Retour</a>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="thumbnail">
                                    <img src="../images/' . $item['image'] . '" class="img-thumbnail" alt="' . $item['name'] . '">
                                    <div class="top-right">' . number_format((float)$item['price'], 2, '.', '') . ' €</div>
                                    <div class="caption">
                                        <h4>' . $item['name'] . '</h4>
                                        <p>' . $item['description'] . '</p>
                                        <a href="#" class="btn btn-order" role="button"><i class="fas fa-shopping-cart"></i> commander</a>
                                    </div>
                                </div>
                            </div>';
                ?>
            </div>
        </div>
    </body>
</html>