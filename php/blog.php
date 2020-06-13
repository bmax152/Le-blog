<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Blog</title>
</head>

<body>

    <main id="corpBlog">
        <header id="en_tete">
            <h1 id="titreBlog">Le Blog Communautaire</h1>
            <?php
            if (isset($_SESSION['login'])) {

                echo "<span id=\"bonjour\">Bonjour " . $_SESSION['login'] . "!</span>";
            } else {

                echo "<span id=\"bonjour\">Bonjour visiteur anonyme!</span>";
            }
            ?>
            <div id="divConnexion">

                <?php
                if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {

                    echo "<span><a href=\"administration.php\">Admin</a></span>";
                }

                echo "<span><a href=\"ajout_blog.php\">Ajouter un article</a></span>";

                if (isset($_SESSION['login'])) {

                    echo "<span><a href=\"deco.php\">Deconnexion</a></span>";
                } else {

                    echo "<span><a href=\"../accueil.php\">Connexion</a></span>";
                }
                ?>


            </div>
        </header>

        <?php

        try {

            include 'connection.php';

            // Nbr article pour pagination
            $sql0 = "SELECT COUNT(*) FROM test_blog";

            $resultat0 = $base->prepare($sql0);

            $resultat0->execute();
            $nbrArticle = $resultat0->fetchColumn();
            $nbrPage = (int) ($nbrArticle / 3) + 1;

            if (isset($_GET['page'])) {

                $page = $_GET['page'];
            } else {

                $page = 1;
            }

            $nbrLigneBase = ($page - 1) * 3;

            $sql = "SELECT * FROM test_blog ORDER BY Date_article DESC LIMIT $nbrLigneBase, 3";
            $sql2 = "SELECT * FROM test_blog_comm WHERE Id_article = :id_art ORDER BY Date_comm";
            $sql3 = "SELECT * FROM test_blog_comm WHERE Id_parent = :id_par ORDER BY Date_comm DESC";

            $resultat = $base->prepare($sql);
            $resultat2 = $base->prepare($sql2);
            $resultat3 = $base->prepare($sql3);

            function commentaires($id, $resultat3)
            {

                $resultat3->bindParam(':id_par', $id);
                $resultat3->execute();
                $tab = $resultat3->fetchAll();
                $resultat3->closeCursor();

                foreach ($tab as $key => $value) {

                    $dateFr3 = new DateTime($value['Date_comm']);
                
                    $reponse = str_repeat("↪", ($value['Niveau']-1));
                    echo "<div class=\"sousCommentaire\">";

                    echo "<div><h2 class=\"titreComm\">" . $reponse . " " . $value['Titre_comm'] . "</h2>";

                    echo "<p class=\"auteurDateComm\">Par " . $value['Auteur_comm'] . " le " . $dateFr3->format('d/m/Y à H:i') . "</p>";

                    if ($value['Modifier_comm'] != NULL) {

                        $dateFrModif4 = new DateTime($value['Modifier_comm']);
                        echo "<p class=\"auteurDateComm\">Modifier le " . $dateFrModif4->format('d/m/Y à H:i') . "</p>";
                    }

                    if (isset($_SESSION['login']) && $value['Auteur_comm'] == $_SESSION['login']) {

                        echo "<a class=\"gestionComm gestionCommModif\" href=\"update_comm.php?idComm=" . $key['Id'] . "\" >Modifier</a><a class=\"gestionComm  gestionCommSupp\" href=\"supp_comm.php?idComm=" . $value['Id'] . "\">Supprimer</a>";
                    }

                    echo "<p class=\"texteComm\">" . $value['Commentaire_comm'] . "</p></div>";

                    echo "<img class=\"imgComm\" src=\"../img_comm/" . $value['Image_comm'] . "\" alt=\"" . $value['Image_comm'] . "\"></div>";

                    echo "<a class=\"gestionLogComm\" href=\"ajout_blog_reponse.php?idComm=" . $value['Id'] . "\" >Répondre</a>";

                    commentaires($value['Id'], $resultat3);
                }
            }

            $resultat->execute();

            while ($ligne = $resultat->fetch()) {

                echo "<h2 class=\"titreArticle\">" . $ligne['Titre'] . "</h2>";

                $dateFr = new DateTime($ligne['Date_article']);

                echo "<p class=\"auteurDate\">Par " . $ligne['Auteur'] . " le " . $dateFr->format('d/m/Y à H:i') . "</p>";

                if ($ligne['Modifier'] != NULL) {

                    $dateFrModif = new DateTime($ligne['Modifier']);
                    echo "<p class=\"auteurDate\">Modifier le " . $dateFrModif->format('d/m/Y à H:i') . "</p>";
                }

                echo "<img class=\"imgArticle\" src=\"../img/" . $ligne['Image_article'] . "\" alt=\"" . $ligne['Image_article'] . "\">";

                echo "<p class=\"texteArticle\">" . $ligne['Commentaire'] . "</p>";

                if (isset($_SESSION['login']) && $ligne['Auteur'] == $_SESSION['login']) {

                    echo "<a class=\"gestionLog gestionModif\" href=\"update_article.php?idArticle=" . $ligne['Id'] . "\" >Modifier</a><a class=\"gestionLog  gestionSupp\" href=\"supp_article.php?idArticle=" . $ligne['Id'] . "\">Supprimer</a>";
                }

                // Commentaire de l'article

                echo "<div  id=\"flex\">";
                $resultat2->bindParam(':id_art', $ligne['Id']);
                $resultat2->execute();

                echo "<div  id=\"a\" class=\"divCommentaire " . $ligne['Id'] . "\">";

                $compteur = 0;

                while ($ligne2 = $resultat2->fetch()) {

                    $compteur++;

                    $dateFr2 = new DateTime($ligne2['Date_comm']);

                    echo "<br><div class=\"unCommentaire\">";

                    echo "<div><h2 class=\"titreComm\">" . $ligne2['Titre_comm'] . "</h2>";

                    echo "<p class=\"auteurDateComm\">Par " . $ligne2['Auteur_comm'] . " le " . $dateFr2->format('d/m/Y à H:i') . "</p>";

                    if ($ligne2['Modifier_comm'] != NULL) {

                        $dateFrModif3 = new DateTime($ligne2['Modifier_comm']);
                        echo "<p class=\"auteurDateComm\">Modifier le " . $dateFrModif3->format('d/m/Y à H:i') . "</p>";
                    }

                    if (isset($_SESSION['login']) && $ligne2['Auteur_comm'] == $_SESSION['login']) {

                        echo "<a class=\"gestionComm gestionCommModif\" href=\"update_comm.php?idComm=" . $ligne2['Id'] . "\" >Modifier</a><a class=\"gestionComm  gestionCommSupp\" href=\"supp_comm.php?idComm=" . $ligne2['Id'] . "\">Supprimer</a>";
                    }

                    echo "<p class=\"texteComm\">" . $ligne2['Commentaire_comm'] . "</p></div>";

                    echo "<img class=\"imgComm\" src=\"../img_comm/" . $ligne2['Image_comm'] . "\" alt=\"" . $ligne2['Image_comm'] . "\"></div>";

                    echo "<a class=\"gestionLogComm\" href=\"ajout_blog_reponse.php?idComm=" . $ligne2['Id'] . "\" >Répondre</a>";


                    // Commentaire de commentaire

                    commentaires($ligne2['Id'], $resultat3);
                }
                echo "</div>";

                // Fin Commentaire de l'article
                echo "<div id=\"b\" class=\"ajoutComm two\"><button value = \"0\" class=\"gestionLog linkCommShow\" id=\"" . $ligne['Id'] . "\">(" . $compteur . ")Commentaire</button><a class=\"gestionLog\" href=\"ajout_blog_comm.php?idArticle=" . $ligne['Id'] . "\" >Ajouter Commentaire</a></div></div>";

                echo "<div id=\"divAncre\"><span class=\"ancre\"><a href=\"#en_tete\">↑haut↑</a></span></div>";

                echo "<br><hr>";
            }

            echo "<section id=\"akimbo\">";

            if ($page != 1) {

                echo "<span id=\"x\"><a href=\"blog.php?page=" . ($page - 1) . "\"><--precedent</a></span>";
            } else {

                echo "<span id=\"z1\">Début du blog</span>";
            }

            if ($page != $nbrPage) {

                echo "<span id=\"y\"><a href=\"blog.php?page=" . ($page + 1) . "\">suivant--></a></span>";
            } else {

                echo "<span id=\"z2\">Fin du blog</span>";
            }

            echo "</section>";

            $resultat->closeCursor();
        } catch (Exception $e) {

            die('Erreur : ' . $e->getMessage());
        } finally {

            $base = null; //fermeture de la connexion
        }

        ?>
    </main>


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="../js/monscript.js"></script>
</body>

</html>