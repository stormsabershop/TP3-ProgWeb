<?php
//TODO !!! le plus important regarder tout ce qu'il y a a faire pour le tp (y compris les petites subtilités) et voir que reste-t-il a faire et faire des TODOS de ce qu'il y a a faire
//TODO regarder la video pour voir si les messages de validations sont bons
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
                $_SESSION['random_number'] = rand($minimum, $maximum);
                echo $_SESSION['random_number'];
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
// TODO faire une verification pour savoir si la valeur donné est entre les deux valeurs (un peut dans le mem style que le request_playing au dessus)
if (isset($_POST['transmit_value'])){
    session_start();
    echo $_SESSION['random_number'];

    $valeur = $_POST['valeur'];
    $_SESSION['valeur'] = $valeur;



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

        let valeur = parseInt('<?php if(isset($_SESSION)){ if (isset($_SESSION['valeur'])){ echo $_SESSION['valeur']; }}?>');

        if (!isNaN(valeur) && '<?php if(isset($_SESSION)){ echo $_SESSION['coups']; }?>' !== "0"){
            if (valeur > parseInt('<?php if(isset($_SESSION)){ echo $_SESSION['random_number']; }?>')){
                ajoute_message("haut!");
            } else if (valeur < parseInt('<?php if(isset($_SESSION)){ echo $_SESSION['random_number']; }?>')) {
                ajoute_message("bas!");
            } else {
                //TODO ici a la fin du message entrer les valeurs essayés (par le même fait faire en sorte que elles se stock quand on joue)
                //TODO en dessous du message suivant, ajouter deux liens: 1.lien pour recommencer avec la même intervalle  2.lien pour recommencer en modifiant l'intervalle
                $('#message').text("Super tu as trouvé. c'est la valeur "+ valeur +". Pas pire, mais tu as quand même essayé ces valeurs [valeurs essayés]");
            }
        } else{
            $('#message').text("Une valeur a été choisie par le jeu, à toi de la trouver :o)").css("color" , "green");
            ajoute_reste_message();
        }



    }

    function ajoute_message(indice){
        if ('<?php if(isset($_SESSION)){ echo $_SESSION['coups']; }?>' === "1"){
            $('#message').text("Après 1 coup, tu es trop " + indice).css("color" , "green");
        } else {
            $('#message').text("Après " + '<?php if(isset($_SESSION)){ echo $_SESSION['coups']; }?>' + " coups, tu es trop " + indice).css("color" , "green");
        }
        ajoute_reste_message();

    }

    function ajoute_reste_message() {
        $('<label>').attr("id", "entreValue").text("Entrez une valeur entre <?php if(isset($_SESSION)){ echo $_SESSION['minimum']; }?> et <?php if(isset($_SESSION)){ echo $_SESSION['maximum']; }?>").appendTo('#formulaire');
        $('<input>').attr("id", "valeurEntre").attr("name", "valeur").attr("type", "number").appendTo("#formulaire");
        retour_ligne("#formulaire");
        $('<input>').attr("id", "sendValue").attr("type", "submit").attr("value", "Tranmettre ma valeur!").attr("name", "transmit_value").appendTo("#formulaire");
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
