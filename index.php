<?php
if (isset($_POST['request_playing'])){
    if($_POST['name'] != "" && $_POST['minimum'] != null && $_POST['maximum'] != null) {
        if ($_POST['minimum'] >= 0) {
            if ($_POST['minimum'] < $_POST['maximum']) {
                session_start();

                $name = $_POST['name'];
                $minimum = $_POST['minimum'];
                $maximum = $_POST['maximum'];

                $_SESSION['name'] = $name;
                $_SESSION['minimum'] = $minimum;
                $_SESSION['maximum'] = $maximum;
                $_SESSION['coups'] = 0;
            } else{
                echo "your maximum value must be more than minimum value";
            }
        } else{
            echo "your minimum value must be 0 or higher";
        }
    } else{
        echo "Please fill all the required fields.";
    }
}

if (isset($_POST['transmit_value'])){




    $_SESSION['coups']++;

}



$session_started = (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['name']));
echo "<script>let session_started = " . json_encode($session_started) . ";</script>";



include "html/index.html";



?>
<script>
    function start_intro(){
        $('#titre').text("Devine la valeur :o)");

        $('#message').text("Salut, voici un petit jeu où tu auras à deviner une valeur. Si tu veux jouer, inscris dans le formulaire suivant les informations demandés et transmets-les.");
        $('<label>').attr("id", "msgName").text("Écris ton prénom ici: ").appendTo("#formulaire");
        $('<input>').attr("type", 'text').attr("id", "name").attr("name", "name").appendTo("#formulaire");

        retour_ligne("#formulaire");

        $('<label>').attr("id", "msgValMin").text("Par la suite, donne-nous la valeur minimum: ").appendTo("#formulaire");
        $('<input>').attr("id", "minimum").attr("name", "minimum").attr("type", 'number').appendTo("#formulaire");

        $('<label>').attr("id", "msgValMax").text(" et la valeur maximum: ").appendTo("#formulaire");
        $('<input>').attr("id", "maximum").attr("name", "maximum").attr("type", 'number').appendTo("#formulaire");
        $('<label>').attr("id", "msgInfo").text(" que nous devrons respecter et entre lesquelles tu veux jouer.").appendTo('#formulaire');
        $('<p>').attr("id", "msgInfo").text("À l'intérieur de cet intervalle, nous allons choisir, au hasard, une valeur que tu auras à deviner !!!").appendTo('#formulaire');
        $('<input>').attr("id", "submit_btn").attr("type", "submit").attr("value", "Je veux jouer!").attr("name", "request_playing").appendTo("#formulaire");
    }

    function start_game(){
        $('#titre').text("Devine la valeur <?php if(isset($_SESSION)){ echo $_SESSION['name']; }?>");
        $('#message').text()
    }



    $(document).ready(function () {
        $('body').css("font-family", "'Segoe UI', Tahoma, sans-serif");
        $('<h1>').attr("id", "titre").appendTo('body');
        $('<p>').attr("id", "message").appendTo('body');
        $('<form>').attr("id", "formulaire").attr("action", "index.php").attr("method", "POST").appendTo("body");
        if (session_started === true){
            start_game();
        } else {
            start_intro();
        }




    });



    function retour_ligne(parent){
        $('<br>').appendTo(parent);
    }
</script>
