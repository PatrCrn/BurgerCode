<?php
    require 'database.php';

    if(!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    if(!empty($_POST)) {
        $id = checkInput($_POST['id']);
        $db = Database::connect();
        $requete = $db->prepare('DELETE FROM items WHERE id = ?');
        $requete->execute(array($id));
        Database::disconnect();
        header("Location: index.php");
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
            <h1><strong>Supprimer un item</strong></h1>
            <br>

            <p class="alert alert-warning">Êtes vous sûr de vouloir supprimer ?</p>

            <form class="form" role="form" action="delete.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" class="btn btn-warning">Oui</button>
                <a class="btn btn-light" href="index.php">Non</a>
            </form>
        </div>
    </body>
</html>