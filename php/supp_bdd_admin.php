<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression</title>
</head>

<body>
<?php

try {

    include 'connection.php';
    $sql = "DELETE FROM test_blog WHERE Id = :id";

    if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1 && isset($_POST['choix'])) {

        $resultat = $base->prepare($sql);

        $resultat->bindParam(':id', $_POST['choix']);
        $resultat->execute();

        header("Location:administration.php?c=" . urlencode("Suppression Réussi!"));
    }else {

        header("Location:administration.php?c=" . urlencode("Selectionner un article à supprimer"));
    }
} catch (Exception $e) {

    die('Erreur : ' . $e->getMessage());
} finally {

    $base = null; //fermeture de la connexion
}





?>
</body>
</html>