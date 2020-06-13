<?php
session_start();


if (isset($_SESSION["login"])) {

    if (isset($_GET['idComm'])) {
        try {
            include "connection.php";

            $sql = 'SELECT * FROM test_blog_comm WHERE Id = :id AND Auteur_comm LIKE :auteur';
            $resultat = $base->prepare($sql);

            $resultat->bindParam(':id', $_GET['idComm']);
            $resultat->bindParam(':auteur', $_SESSION["login"]);
            $resultat->execute();

            $ok = true;
            while ($ligne = $resultat->fetch()) {

                $valueTitre = $ligne['Titre_comm'];
                $valueComm = $ligne['Commentaire_comm'];
                $valueImg = $ligne['Image_comm'];
                $ok = false;
            }

            if($ok){

                header("Location:blog.php");
            }

            
        } catch (Exception $e) {

            die('Erreur : ' . $e->getMessage());
        } finally {

            $base = null; //fermeture de la connexion
        }
    }else{

        header("Location:blog.php");
    }

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Upadte Article</title>
    </head>

    <body>
        <main id="mainAjout">
            <h1>Formulaire de modification de comentaire du Blog</h1>

            <form action="update_comm_bdd.php?idComm=<?php echo $_GET['idComm']; ?>" id="formUpdateBlog" method="post" enctype="multipart/form-data">
                <div class="formAjout">
                    <label for="titre">Titre: </label>
                    <input <?php 
                        if(isset($valueTitre)){  
                            echo "value=\"". $valueTitre . "\"";
                        } 
                        ?> type="text" name="titre" id="titre" size="42">
                </div>
                <div class="formAjout">
                    <label for="comm">Commentaire <br> (en 280 caractères max): </label>
                    <textarea name="comm" id="comm" cols="40" rows="10"><?php 
                        if(isset($valueComm)){  
                            echo $valueComm;
                        } 
                        ?></textarea>
                </div>
                <div class="formAjout">
                    <label for="uploadImg">Choisissez une photo avec une taille inférieure à 2 Mo.</label>
                    <input type="file" name="uploadImg" id="uploadImg">
                </div>

                <div class="formAjout">
                    <p>Mon Image actuelle: <?php echo $valueImg; ?></p>
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