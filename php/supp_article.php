<?php
session_start();
if (isset($_SESSION['login']) && isset($_GET['idArticle'])) {
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Suppression Article Perso</title>
    </head>

    <body>
    <?php

    try {

        include 'connection.php';
        $sql = "DELETE FROM test_blog WHERE Id = :id AND Auteur LIKE :auteur";
        $sql2 = "SELECT Image_article FROM test_blog WHERE Id = :id";
        // Suppression des comm
        $sql3 = "DELETE FROM test_blog_comm WHERE Id_article = :id_art";

        $resultat2 = $base->prepare($sql2);
        $resultat2->bindParam(':id', $_GET['idArticle']);
        $resultat2->execute();


        $chemin_destination = '../img/';
        $fichier = "";
        $ok = false;

        while ($ligne = $resultat2->fetch()) {

            $ok = true;
            $fichier = $ligne['Image_article'];
        }

        if ($ok && $fichier != "") {

            if (file_exists($chemin_destination . $fichier)) {
                unlink($chemin_destination . $fichier);
            }
        }

        $resultat = $base->prepare($sql);

        $resultat3 = $base->prepare($sql3);
        $resultat3->bindParam(':id_art', $_GET['idArticle']);
        $resultat3->execute();

        $resultat->bindParam(':id', $_GET['idArticle']);
        $resultat->bindParam(':auteur', $_SESSION['login']);
        $resultat->execute();


        header("Location:blog.php");
    } catch (Exception $e) {

        die('Erreur : ' . $e->getMessage());
    } finally {

        $base = null; //fermeture de la connexion
    }
} else {

    header("Location:../accueil.php?b=" . urlencode("*Veuillez vous connecter"));
}

    ?>
    </body>

    </html>