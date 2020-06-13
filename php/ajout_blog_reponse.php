<?php
session_start();

if (isset($_SESSION["login"]) && isset($_GET['idComm'])) {

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Réponse à un commentaire</title>
    </head>

    <body>
        <main id="mainAjout">
            <h1>Formulaire de réponse au commentaire de l'article</h1>

            <form action="ajout_com_bdd.php?idParent=<?php echo $_GET['idComm']?>" id="formAjoutBlogComm" method="post" enctype="multipart/form-data">
                <div class="formAjout">
                    <label for="titre">Titre: </label>
                    <input type="text" name="titre" id="titre" size="42">
                </div>
                <div class="formAjout">
                    <label for="comm">Commentaire <br> (en 280 caractères max): </label>
                    <textarea name="comm" id="comm" cols="40" rows="10"></textarea>
                </div>
                <div class="formAjout">
                    <label for="uploadImg">Choisissez une photo avec une taille inférieure à 2 Mo. (optionnel)</label>
                    <input type="file" name="uploadImg" id="uploadImg">
                </div>

                <button class="bouton" type="submit">Envoyer</button>

            </form>

            <div id="incomplet">
                <?php
                if (isset($_GET["double"])) {

                    echo $_GET["double"];
                }
                ?>
            </div>
            <hr>
            <section id="corpBlog">
                <?php

                try {

                    include 'connection.php';
                    $sql = "SELECT * FROM test_blog_comm WHERE Id = :id";

                    
                    $resultat3 = $base->prepare($sql);
                    $resultat3->bindParam(':id', $_GET['idComm']);
                    $resultat3->execute();

                    while ($ligne3 = $resultat3->fetch()) {

                        if ($ligne3['Titre_comm'] != "") {
                            $dateFr3 = new DateTime($ligne3['Date_comm']);

                            echo "<div class=\"sousCommentaire\">";
    
                            echo "<div><h2 class=\"titreComm\">" . $ligne3['Titre_comm'] . "</h2>";
    
                            echo "<p class=\"auteurDateComm\">Par " . $ligne3['Auteur_comm'] . " le " . $dateFr3->format('d/m/Y à H:i') . "</p>";
    
                            if ($ligne3['Modifier_comm'] != NULL) {
    
                                $dateFrModif4 = new DateTime($ligne3['Modifier_comm']);
                                echo "<p class=\"auteurDateComm\">Modifier le " . $dateFrModif4->format('d/m/Y à H:i') . "</p>";
                            }
       
                            echo "<p class=\"texteComm\">" . $ligne3['Commentaire_comm'] . "</p></div>";
    
                            echo "<img class=\"imgComm\" src=\"../img_comm/" . $ligne3['Image_comm'] . "\" alt=\"" . $ligne3['Image_comm'] . "\"></div>";
        
                        }
                    }

                    $resultat3->closeCursor();
                } catch (Exception $e) {

                    die('Erreur : ' . $e->getMessage());
                } finally {

                    $base = null; //fermeture de la connexion
                }

                ?>

            </section>
            <div id="lienRetour">
                <a class="formAjout boutonBlog" id="retour" href="blog.php">Retour au blog</a>
            </div>
        <?php
        
    } else {

        header("Location:../accueil.php?b=" . urlencode("*Veuillez vous connecter"));
    }

        ?>
        </main>


        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="../js/monscript.js"></script>
    </body>

    </html>