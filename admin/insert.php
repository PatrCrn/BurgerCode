<?php
    require 'database.php';

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
        $isUploadSuccess = false;

        if(empty($nom)) {
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
            $imageError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        } else {
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
        if($isSuccess && $isUploadSuccess) {
            $db = Database::connect();
            $statement = $db->prepare("INSERT INTO items (name, description, price, category, image) 
                                        VALUES (?, ?, ?, ?, ?)");
            $statement->execute(array($nom, $description, $prix, $categorie, $image));
            Database::disconnect();
            header("Location: index.php");
        }
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
                <h1><strong>Ajouter un item</strong></h1>
            </div>
            <br>
            <div class="row">
                <form class="form" role="form" action="insert.php" method="POST" enctype="multipart/form-data">
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
                        <input type="number" step="0.01" class="form-control" id="prix" name="prix" placeholder="Prix" value="<?php echo $prix; ?>">
                        <span class="help-inline"><?php echo $prixError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="categorie">Catégorie :</label>
                        <select class="form-control" id="categorie" name="categorie">
                        <?php
                            $db = Database::connect();
                            $requete = $db->query('SELECT * FROM categories ORDER BY name');
                            while ($item = $requete->fetch()) {
                                echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
                            }

                            Database::disconnect();
                        ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Sélectionner une image :</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        <span class="help-inline"><?php echo $imageError; ?></span>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-edit"></i> Ajouter</button>
                    <a class="btn btn-primary" href="index.php"><i class="fas fa-arrow-left"></i> Retour</a>
                </form>
            </div>
            
        </div>
    </body>
</html>




