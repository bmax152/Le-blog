<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Connexion au blog</title>
</head>

<body>


    <main id="mainLog">
    <!-- PAGE DE CONNEXION: Envois sur login.php  -->
        <form action="php/login.php" method="post" id="loginDb">

            <label for="login">Login: </label>
            <input type="text" name="login" id="login">
            <br>
            <label for="pass">Password: </label>
            <input type="password" name="pass" id="pass">
            <br>

            <input class="bouton" type="submit" value="Se connecter" id="subLog">

            <div id="erreurLogin">
                <?php
                if (isset($_GET["b"])) {

                    echo $_GET["b"];
                }
                ?>
            </div>
        </form>
        <br>
        <div>
            <a id="retour" class="boutonBlog" href="php/blog.php">Voir le Blog</a>
        </div>
        <br>

    </main>


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="js/monscript.js"></script>

</body>

</html>