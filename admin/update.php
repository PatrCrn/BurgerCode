<?php
    require 'database.php';

    if(!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    $nom = $description = $prix = $categorie = $image = $nomError = $descriptionError = $prixError = $categorieError = $imageError = "";

    if(!empty($_POST)) {
        $nom = checkInput($_POST['nom']);
        $description = checkInput($_POST['description']);
        $prix = checkInput($_POST['prix']);
        $categorie = checkInput($_POST['categorie']);
        $image = checkInput($_FILES['image']['name']);
        $imagePath = '../images/' . basename($image);
        $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $isSuccess = true;

        if(empty($nom) ) {
            $nomError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        if(empty($description)) {
            $descriptionError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        if(empty($prix)) {
            $prixError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        if(empty($categorie)) {
            $categorieError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        if(empty($image)) {
            $isImageUpdated = false;
        } else {
            $isImageUpdated = true;
            $isUploadSuccess = true;

            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
                $imageError = "Les fichiers autorisés sont : .jpg, .png, .jpeg, .gif";
                $isUploadSuccess = false;
            }
            if(file_exists($imagePath)) {
                $imageError = "Le fichier existe déjà";
                $isUploadSuccess = false;
            }
            if($_FILES['image']['size'] > 500000) {
                $imageError = "Le fichier ne doit pas dépasser les 500KB";
                $isUploadSuccess = false;
            }
            if($isUploadSuccess && $isSuccess) {
                if(!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $imageError = "Il y a eu une erreur lors de l'uplaoad";
                    $isUploadSuccess = false;
                }
            }
        }
        if(($isSuccess && $isUploadSuccess && !$isImageUpdated) || ($isSuccess && !$isImageUpdated)) {
            $db = Database::connect();
            if($isImageUpdated) {
                $statement = $db->prepare("UPDATE items 
                                SET name = ?, description = ?, price = ?, category = ?, image = ?
                                WHERE id = ?");
                $statement->execute(array($nom, $description, $prix, $categorie, $image, $id));
            } else {
                $statement = $db->prepare("UPDATE items 
                                SET name = ?, description = ?, price = ?, category = ?
                                WHERE id = ?");
                $statement->execute(array($nom, $description, $prix, $categorie, $id));
            }
            Database::disconnect();
            header("Location: index.php");
        } else if($isImageUpdated && !$isUploadSuccess) {
            $db = Database::connect();
            $rq = $db->prepare('SELECT image FROM items WHERE id = ?');
            $rq->execute(array($id));
            $item = $rq->fetch();
            $image = $item['image'];
            Database::disconnect();
        }
    } else {
        $db = Database::connect();
        $requete = $db->prepare('SELECT * FROM items WHERE id = ?');
        $requete->execute(array($id));
        $item = $requete->fetch();
        $nom = $item['name'];
        $description = $item['description'];
        $prix = $item['price'];
        $categorie = $item['category'];
        $image = $item['image'];
        Database::disconnect();
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
                <div class="col-md-6">
                    <div class="row">
                        <h1><strong>Modifier un item</strong></h1>
                    </div>
                    <br>
                    <form class="form" role="form" action="update.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" value="<?php echo $nom; ?>">
                            <span class="help-inline"><?php echo $nomError; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Description :</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description; ?>">
                            <span class="help-inline"><?php echo $descriptionError; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="prix">Prix : (en €)</label>
                            <input type="number" step="0.01" class="form-control" id="prix" name="prix" placeholder="Prix" value="<?php echo number_format((float)$prix, 2, '.', ''); ?>">
                            <span class="help-inline"><?php echo $prixError; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="categorie">Catégorie :</label>
                            <select class="form-control" id="categorie" name="categorie">
                            <?php
                                $db = Database::connect();
                                $req = $db->query('SELECT * FROM categories ORDER BY name');
                                while ($cat = $req->fetch()) {
                                    if($cat['id'] == $categorie) {
                                        echo '<option selected="selected" value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                                    }
                                }

                                Database::disconnect();
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prix">Image : </label>
                            <br>
                            <label for="prix" style="font-weight:normal;"><?php echo $image; ?></label>
                        </div>
                        <div class="form-group">
                            <label for="image">Sélectionner une nouvelle image :</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                            <span class="help-inline"><?php echo $imageError; ?></span>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-edit"></i> Modifier</button>
                        <a class="btn btn-primary" href="index.php"><i class="fas fa-arrow-left"></i> Retour</a>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="thumbnail">
                        <img src="../images/<?php echo $image; ?>" class="img-thumbnail" alt="<?php echo $nom; ?>">
                        <div class="top-right"><?php echo number_format((float)$prix, 2, '.', '') ?> €</div>
                        <div class="caption">
                            <h4><?php echo $nom; ?></h4>
                            <p><?php echo $description; ?></p>
                            <a href="#" class="btn btn-order" role="button"><i class="fas fa-shopping-cart"></i> commander</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>