<?php
session_start();

if (isset($_SESSION["login"]) && isset($_GET['idArticle'])) {

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Ajouter un commentaire</title>
    </head>

    <body>
        <main id="mainAjout">
            <h1>Formulaire d'ajout de commentaire à l'article</h1>

            <form action="ajout_com_bdd.php?idArticle=<?php echo $_GET['idArticle']?>" id="formAjoutBlogComm" method="post" enctype="multipart/form-data">
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
                    $sql = "SELECT * FROM test_blog WHERE Id = :id";

                    
                    $resultat = $base->prepare($sql);
                    $resultat->bindParam(':id', $_GET['idArticle']);
                    $resultat->execute();

                    while ($ligne = $resultat->fetch()) {

                        echo "<div><h2 class=\"titreArticleComm\"> Article: </h2></div>";
                        echo "<h2 class=\"titreArticle\">" . $ligne['Titre'] . "</h2>";

                        $dateFr = new DateTime($ligne['Date_article']);

                        echo "<p class=\"auteurDate\">Par " . $ligne['Auteur'] . " le " . $dateFr->format('d/m/Y à H:i') . "</p>";

                        if ($ligne['Modifier'] != NULL) {

                            $dateFrModif = new DateTime($ligne['Modifier']);
                            echo "<p class=\"auteurDate\">Modifier le " . $dateFrModif->format('d/m/Y à H:i') . "</p>";
                        }

                        echo "<img class=\"imgArticle\" src=\"../img/" . $ligne['Image_article'] . "\" alt=\"" . $ligne['Image_article'] . "\">";

                        echo "<p class=\"texteArticle\">" . $ligne['Commentaire'] . "</p>";
                    }

                    $resultat->closeCursor();
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