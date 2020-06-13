<?php
session_start();
if (isset($_SESSION['login']) && isset($_GET['idComm'])) {
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Suppression Commentaire Perso</title>
    </head>

    <body>
    <?php

    try {

        include 'connection.php';
        $sql = "DELETE FROM test_blog_comm WHERE Id = :id AND Auteur_comm LIKE :auteur";
        $sql2 = "SELECT Image_comm FROM test_blog_comm WHERE Id = :id";
        // Suppression des comm

        $resultat2 = $base->prepare($sql2);
        $resultat2->bindParam(':id', $_GET['idComm']);
        $resultat2->execute();


        $chemin_destination = '../img_comm/';
        $fichier = "";
        $ok = false;

        while ($ligne = $resultat2->fetch()) {

            $ok = true;
            $fichier = $ligne['Image_comm'];
        }

        if ($ok && $fichier != NULL &&  $fichier != "") {

            if (file_exists($chemin_destination . $fichier)) {
                unlink($chemin_destination . $fichier);
            }
        }

        $resultat = $base->prepare($sql);

        $resultat->bindParam(':id', $_GET['idComm']);
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