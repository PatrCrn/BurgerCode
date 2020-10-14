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
                <h1><strong>Liste des items </strong><a href="insert.php" class="btn btn-success btn-lg"><i class="fas fa-plus"></i> Ajouter</a></h1>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php 
                        require 'database.php';
                        $db = Database::connect();
                        $requete = $db->query('SELECT it.id, it.name, it.description, it.price, cat.name AS nomCat 
                                                FROM items it
                                                LEFT JOIN categories cat ON cat.id = it.category
                                                ORDER BY it.id DESC');
                        while ($item = $requete->fetch()) {
                            echo '<tr>
                                    <td>' . $item['name'] . '</td>
                                    <td>' . $item['description'] . '</td>
                                    <td>' . number_format((float)$item['price'], 2, '.', '') . '€</td>
                                    <td>' . $item['nomCat'] . '</td>
                                    <td style="width:337px;">
                                        <a type="button" class="btn btn-light" href="view.php?id=' . $item['id'] . '"><i class="fas fa-eye"></i> Voir</a>
                                         <a class="btn btn-primary" href="update.php?id=' . $item['id'] . '"><i class="fas fa-edit"></i> Modifier</a>
                                         <a class="btn btn-danger" href="delete.php?id=' . $item['id'] . '"><i class="far fa-trash-alt"></i> Supprimer</a>
                                    </td>
                                </tr>';
                        }

                        Database::disconnect();
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>