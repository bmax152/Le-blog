<?php
session_start();

if (isset($_SESSION["login"])) {

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Ajouter au blog</title>
    </head>

    <body>
        <main id="mainAjout">
            <h1>Formulaire d'ajout de contenu au Blog</h1>

            <form action="ajout_bdd.php" id="formAjoutBlog" method="post" enctype="multipart/form-data">
                <div class="formAjout">
                    <label for="titre">Titre: </label>
                    <input type="text" name="titre" id="titre" size="42">
                </div>
                <div class="formAjout">
                    <label for="comm">Commentaire <br> (en 280 caractères max): </label>
                    <textarea name="comm" id="comm" cols="40" rows="10"></textarea>
                </div>
                <div class="formAjout">
                    <label for="uploadImg">Choisissez une photo avec une taille inférieure à 2 Mo.</label>
                    <input type="file" name="uploadImg" id="uploadImg">
                </div>

                <button class="bouton" type="submit">Envoyer</button>

            </form>

            <div id="lienRetour">
                <a class="formAjout boutonBlog" id="retour" href="blog.php">Page d'affichage du blog</a>
            </div>

            <div id="incomplet">
                <?php
                if (isset($_GET["double"])) {

                    echo $_GET["double"];
                }
                ?>
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