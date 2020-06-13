$(document).ready(function () {
    $('.divCommentaire').hide();

    $("#loginDb").submit(function (event) {

        var login = $("#login").val();
        var pass = $("#pass").val();

        if (login == "" || pass == "") {


            $("#erreurLogin").html("*Veuillez remplir tous les champs requis");
            return false;
        } else {

            $("#erreurLogin").html("");
            return true;
        }
    });

    $("#formAjoutBlog").submit(function (event) {

        var titre = $("#titre").val();
        var comm = $("#comm").val();
        var imgArt = $("#uploadImg").val();
        var filename = $("#uploadImg").val().replace(/C:\\fakepath\\/i, '');

        if (titre == "" || comm == "") {

            $("#incomplet").html("*Veuillez remplir correctement le formulaire d'ajout");
            return false;
        } else if (titre.length > 40) {

            $("#incomplet").html("*Le titre est trop long (40 carac. max)");
            return false;
        } else if (comm.length > 280) {

            $("#incomplet").html("*Votre commentaire est trop long (280 carac. max)");
            return false;
        } else if (filename.length > 50) {

            $("#incomplet").html("*Le nom de l'image est trop long (50 carac. max)");
            return false;
        } else {

            $("#incomplet").html("");
            return true;
        }

    });

    $("#formAjoutBlogComm").submit(function (event) {

        var titre = $("#titre").val();
        var comm = $("#comm").val();
        var imgArt = $("#uploadImg").val();
        var filename = $("#uploadImg").val().replace(/C:\\fakepath\\/i, '');

        if (titre == "" || comm == "") {

            $("#incomplet").html("*Veuillez remplir correctement le formulaire d'ajout");
            return false;
        } else if (titre.length > 40) {

            $("#incomplet").html("*Le titre est trop long (40 carac. max)");
            return false;
        } else if (comm.length > 280) {

            $("#incomplet").html("*Votre commentaire est trop long (280 carac. max)");
            return false;
        } else if (filename.length > 50) {

            $("#incomplet").html("*Le nom de l'image est trop long (50 carac. max)");
            return false;
        } else {

            $("#incomplet").html("");
            return true;
        }

    });


    $(".gestionModif").click(function (e) {

        let conf = confirm('Voulez-vous modifier l\'article?');
        if (conf) {

            return true;
        } else {
            
            return false;
        }

    });

    $(".gestionSupp").click(function (e) {

        let conf = confirm('Voulez-vous supprimer l\'article?');
        if (conf) {

            return true;
        } else {

            return false;
        }

    });

    $(".gestionCommModif").click(function (e) {

        let conf = confirm('Voulez-vous modifier le commentaire?');
        if (conf) {

            return true;
        } else {
            
            return false;
        }

    });

    $(".gestionCommSupp").click(function (e) {

        let conf = confirm('Voulez-vous supprimer le commentaire?');
        if (conf) {

            return true;
        } else {

            return false;
        }

    });


    $(".linkCommShow").click(function (e) { 

        e.preventDefault();
        if($(this).val() == 0){

            $('.'+$(this).attr('id')).show();
            $(this).attr('value', '1');
        }else{

            $('.'+$(this).attr('id')).hide();
            $(this).attr('value', '0');
        }
    });

});

