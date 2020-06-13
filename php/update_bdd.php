<?php
session_start();
if (isset($_SESSION['login']) && $_POST['titre'] != "" && $_POST['comm'] != "" && isset($_GET['idArticle'])) {
?>


    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Update BDD</title>
    </head>

    <body>
        <main id="mainAjoutBdd">
        <?php

        try {

            include "connection.php";

            $tempImg = "";
            if ($_FILES['uploadImg']['name'] != "") {

                $tempImg = ", Image_article = :img";
            }

            $sql = "UPDATE test_blog SET Titre = :titre, Commentaire = :comm" . $tempImg . " , Auteur = :auteur, Modifier = :date_modi WHERE Id = :id AND Auteur LIKE :auteur";
            $sql2 = "SELECT Image_article FROM test_blog WHERE Image_article LIKE :doublon";

            // Préparation de la requête avec les marqueurs

            $resultat = $base->prepare($sql);
            $resultat2 = $base->prepare($sql2);

            //Test de l'image
            if ($_FILES['uploadImg']['error'] && $_FILES['uploadImg']['error'] != 4) {

                switch ($_FILES['uploadImg']['error']) {

                    case 1: // UPLOAD_ERR_INI_SIZE
                        echo "La taille du fichier est plus grande que la limite autorisée par le serveur (paramètre upload_max_filesize du fichier php.ini).";
                        break;

                    case 2: // UPLOAD_ERR_FORM_SIZE
                        echo "La taille du fichier est plus grande que la limite autorisée par le formulaire (paramètre post_max_size du fichier php.ini).";
                        break;

                    case 3: // UPLOAD_ERR_PARTIAL
                        echo "L'envoi du fichier a été interrompu pendant le transfert.";
                        break;
                }
            } else {

                //si il n'ya pas d'erreur alors $_FILES['nom_du_fichier']['error'] vaut 0 ou qu'il n'y a pas d'image
                if ($_FILES['uploadImg']['error'] == 0) {

                    echo "Aucune erreur dans l'upload du fichier.<br />";
                }

                if ((isset($_FILES['uploadImg']['name']) && ($_FILES['uploadImg']['error'] == UPLOAD_ERR_OK)) || $_FILES['uploadImg']['error']  == 4) {

                    $titreHtml = htmlspecialchars($_POST['titre']);
                    $commHtml = htmlspecialchars($_POST['comm']);
                    $date_ajout = date("Y-m-d H:i:s");
                    $resultat->bindParam(':titre', $titreHtml);
                    $resultat->bindParam(':comm', $commHtml);
                    $resultat->bindParam(':auteur', $_SESSION['login']);
                    $resultat->bindParam(':date_modi', $date_ajout);
                    $resultat->bindParam(':id', $_GET['idArticle']);

                    if (isset($_FILES['uploadImg']['name']) && $_FILES['uploadImg']['error']  != 4) {

                        // Effacer ancienne image si nouvelle 
                        $sql3 = "SELECT Image_article FROM test_blog WHERE Id = :id";

                        $resultat3 = $base->prepare($sql3);
                        $resultat3->bindParam(':id', $_GET['idArticle']);
                        $resultat3->execute();

                        $chemin_destination = '../img/';
                        $fichier = "";
                        $ok2 = false;

                        while ($ligne = $resultat3->fetch()) {

                            $ok2 = true;
                            $fichier = $ligne['Image_article'];            
                        }
                        
                        if ($ok2 && $fichier != NULL &&  $fichier != "") {
                        
                            if( file_exists ( $chemin_destination.$fichier)) {
                                unlink( $chemin_destination.$fichier ) ;
                            }
                        }

                        // Recherche de doublons
                        $resultat2->bindParam(':doublon', $_FILES['uploadImg']['name']);
                        $resultat2->execute();

                        $okDoublon = false;

                        while ($ligneDoublon = $resultat2->fetch()) {

                            $okDoublon = true;
                        }

                        $chemin_destination = '../img/';

                        if ($okDoublon) {
                            $date_img = date("H-i-s");
                            $newName = $_GET['idArticle'] . $date_img . $_FILES['uploadImg']['name'];
                            move_uploaded_file($_FILES['uploadImg']['tmp_name'], $chemin_destination . $newName);
                            echo "Le fichier " . $newName . " a été copié dans le répertoire img<br />";
                            $resultat->bindParam(':img', $newName);
                        } else {

                            move_uploaded_file($_FILES['uploadImg']['tmp_name'], $chemin_destination . $_FILES['uploadImg']['name']);
                            echo "Le fichier " . $_FILES['uploadImg']['name'] . " a été copié dans le répertoire img<br />";
                            $resultat->bindParam(':img', $_FILES['uploadImg']['name']);
                        }

                        
                    }
                    $resultat->execute();
                    echo "Modifications réussis<br />";
                } else {
                    echo "Le fichier n'a pas pu être copié dans le répertoire fichiers.<br />";
                }
            }

            echo "<br /><a class=\"boutonBlog\" href=\"ajout_blog.php\">Page d'ajout</a>";
            echo "<a id=\"retour\" class=\"boutonBlog\" href=\"blog.php\">Voir le Blog</a>";
        } catch (Exception $e) {

            die('Erreur : ' . $e->getMessage());
        } finally {

            $base = null; //fermeture de la connexion
        }
    } else {

        header("Location:../accueil.php?b=" . urlencode("*Veuillez vous connecter"));
    }

        ?>
        </main>

    </body>

    </html>