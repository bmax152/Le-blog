<?php
session_start();
if (isset($_SESSION['login']) && $_POST['titre'] != "" && $_POST['comm'] != ""  && (isset($_GET['idArticle']) || isset($_GET['idParent']))) {
?>


    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Ajouter à la BDD le commentaire</title>
    </head>

    <body>
        <main id="mainAjoutBdd">
        <?php

        try {

            include "connection.php";

            $temp1Img = "";
            $temp2Img = "";
            $temp1Id = "";
            $temp2Id = "";
            $idFinal = "";

            if ($_FILES['uploadImg']['name'] != "") {

                $temp1Img = ", Image_comm";
                $temp2Img = ", :img";
            }

            if(isset($_GET['idArticle'])){

                $temp1Id = "Id_article,";
                $idFinal = $_GET['idArticle'];
            }else{

                $temp1Id = "Id_parent,";

                $idFinal = $_GET['idParent'];
            }


            $sql = "INSERT INTO test_blog_comm ($temp1Id Titre_comm, Commentaire_comm $temp1Img , Auteur_comm, Date_comm) VALUES (:id, :titre, :comm $temp2Img , :auteur, :date_art)";
            $sql2 = "SELECT Image_comm FROM test_blog_comm WHERE Image_comm LIKE :doublon";
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

                if ($_FILES['uploadImg']['error'] == 0) {

                    echo "Aucune erreur dans l'upload du fichier.<br />";
                }

                if ((isset($_FILES['uploadImg']['name']) && ($_FILES['uploadImg']['error'] == UPLOAD_ERR_OK) || $_FILES['uploadImg']['error']  == 4)) {

                    $titreHtml = htmlspecialchars($_POST['titre']);
                    $commHtml = htmlspecialchars($_POST['comm']);
                    $date_ajout = date("Y-m-d H:i:s");
                    $resultat->bindParam(':titre', $titreHtml);
                    $resultat->bindParam(':id', $idFinal);
                    $resultat->bindParam(':comm', $commHtml);
                    $resultat->bindParam(':auteur', $_SESSION['login']);
                    $resultat->bindParam(':date_art', $date_ajout);

                    if (isset($_FILES['uploadImg']['name']) && $_FILES['uploadImg']['error']  != 4) {

                        $resultat2->bindParam(':doublon', $_FILES['uploadImg']['name']);
                        $resultat2->execute();

                        $okDoublon = false;

                        while ($ligneDoublon = $resultat2->fetch()) {

                            $okDoublon = true;
                        }

                        $chemin_destination = '../img_comm/';

                        if ($okDoublon) {

                            $date_img = date("H-i-s");
                            $newName = $idFinal . $date_img . $_FILES['uploadImg']['name'];
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
                    echo "Ajout du commentaire réussi<br />";
                } else {
                    echo "Le fichier n'a pas pu être copié dans le répertoire fichiers.<br />";
                }
            }

            echo "<a id=\"retour\" class=\"boutonBlog\" href=\"blog.php\">Retour au Blog</a>";
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