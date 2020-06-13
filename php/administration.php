<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Administration des articles</title>
    </head>

    <body id="bodyTab">

        <?php

        try {

            include 'connection.php';
            $sql = "SELECT * FROM test_blog";


            $resultat = $base->prepare($sql);
            $resultat->execute();

            $table = "<table id=\"tableau\"><tr><th>Titre</th><th>Commentaire</th><th>Image</th><th>Date</th><th>Auteur</th><th>Supprimer</th></tr>";

            $i = 0;

            while ($ligne = $resultat->fetch()) {

                if ($i % 2 == 0) {

                    $table .= "<tr class=\"couleur\"><td>" . $ligne['Titre'] . "</td><td>" . $ligne['Commentaire'] . "</td><td>" . $ligne['Image_article'] . "</td><td>" . $ligne['Date_article'] . "</td><td>" . $ligne['Auteur'] . "</td><td><input type=\"radio\" id=\"" . $ligne['Id'] . "\" name=\"choix\" value=\"" . $ligne['Id'] . "\"></td></tr>";
                } else {

                    $table .= "<tr><td>" . $ligne['Titre'] . "</td><td>" . $ligne['Commentaire'] . "</td><td>" . $ligne['Image_article'] . "</td><td>" . $ligne['Date_article'] . "</td><td>" . $ligne['Auteur'] . "</td><td><input type=\"radio\" id=\"" . $ligne['Id'] . "\" name=\"choix\" value=\"" . $ligne['Id'] . "\"></td></tr>";
                }
                $i++;
            }
            $table .= "</table>";

            echo "<h1>Bonjour " . $_SESSION['login'] . ". <br>Voici la liste des articles:</h1><br><br>";
            echo "<form method=\"POST\" action=\"supp_bdd_admin.php\" id=\"formSupp\">";
            echo $table;
            echo "<div><button class=\"boutonBlog\" type=\"submit\">Supprimer Article</button></div></form>";

            $resultat->closeCursor();
        } catch (Exception $e) {

            die('Erreur : ' . $e->getMessage());
        } finally {

            $base = null; //fermeture de la connexion
        }

        ?>

        <a id="retour" class="boutonBlog" href="blog.php">Voir le Blog</a>

        <div id="infoSupp">
            <br>
            <br>
            <?php
            if (isset($_GET["c"])) {

                echo "<p>" . $_GET["c"] . "</p>";
            }
            ?>
        </div>


    </body>

    </html>

<?php
} else {

    header("Location:../accueil.php?b=" . urlencode("*Veuillez vous connecter"));
}
?>