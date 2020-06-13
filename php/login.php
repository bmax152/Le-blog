<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>BDD Admin</title>
</head>
<!-- PAGE D'AJOUT DANS LA BDD -->
<body>

    <?php

    try {
        include 'connection.php';
        
        // Recherche des logins dans la bdd login_password
        $sql = 'SELECT * FROM login_password WHERE Login LIKE :login AND Password LIKE :pass';
        $resultat = $base->prepare($sql);

        $resultat->execute(array('login' => $_POST['login'], 'pass' => $_POST['pass']));

        $ok = false;
        $admin = 0;
        while ($ligne = $resultat->fetch()) {

            $ok = true;
            $admin = $ligne['Admin'];
        }

        if ($ok) {
           
            $_SESSION["login"] = $_POST['login'];
            $_SESSION["admin"] = $admin;
            header("Location:blog.php");
        } else {

            header("Location:../accueil.php?b=" . urlencode("*Login ou Mdp Incorrecte")); 
        }
    } catch (Exception $e) {

        die('Erreur : ' . $e->getMessage());
    } finally {

        $base = null; //fermeture de la connexion
    }

    ?>

</body>

</html>