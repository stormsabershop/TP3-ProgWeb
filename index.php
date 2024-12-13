<?php
session_start();

define("ETAT_1", "page de bienvenue");
define("ETAT_2", "page de jeu");
define("ETAT_3", "page de fin");

function write_to_file($message){

    $fichier = fopen("log/trace_log.txt", "a");
    if ($fichier) {
        // Ecrit la trace dans le fichier avec la date, le message et l'ID de session
        fwrite($fichier, date("d/m/Y H:i:s") . " - " . $message . " - " . session_id() . "\n");
        fclose($fichier);  // Ferme le fichier après l'écriture
    } else {
        // Gère les erreurs si le fichier ne peut pas être ouvert
        error_log("Impossible d'ouvrir le fichier trace_log.txt pour l'écriture.");
    }

}


// Vérifier si la variable 'data' existe dans la requête POST



$log = "";

if (empty($_SESSION)) {
    $_SESSION["etat"] = ETAT_1;
    write_to_file("Nouvelle session ");
}

function generateRandomNumber($min, $max) {
    $val_random = rand($min, $max);
    write_to_file("Choix d'une nouvelle valeur à deviner ($val_random)");
    return $val_random;
}

if (isset($_POST['request_playing'])) {
    $name = trim($_POST['name']);
    $minimum = intval($_POST['minimum']);
    $maximum = intval($_POST['maximum']);
    $log = "Formulaire prénom et valeurs ";
    $_SESSION['error'] = null;
    if ($name === "") {
        $_SESSION['error'] .= "Le prénom est obligatoire. <br>";
    } if ($minimum < 0) {
        $_SESSION['error'] .= "La valeur minimum doit être plus grande ou égale à 0. <br>";
    } if ($minimum >= $maximum && $minimum!=null) {
        $_SESSION['error'] .= "La valeur maximum doit être supérieure à la valeur minimum. <br>";
    }
    if ($minimum == null){
        $_SESSION['error'] .= "La valeur minimum est obligatoire <br>";
    }
    if ($maximum == null) {
        $_SESSION['error'] .= "La valeur maximum est obligatoire<br>";
    }

    if ($name !== "" && $minimum >= 0 && $minimum < $maximum) {
        write_to_file($log);
        $_SESSION["etat"] = ETAT_2;
        $_SESSION['name'] = $name;
        $_SESSION['minimum'] = $minimum;
        $_SESSION['maximum'] = $maximum;
        $_SESSION['coups'] = 0;
        $_SESSION['random_number'] = generateRandomNumber($minimum, $maximum);

        $_SESSION['values_tried'] = [];
        $_SESSION['error'] = null;
    } else {
        $log .= "avec erreurs ";
        write_to_file($log);
    }
}

if (isset($_POST['transmit_value'])) {
    $valeur = intval($_POST['valeur']);
    write_to_file("Formulaire plus haut et plus bas");

    if ($valeur < $_SESSION['minimum'] || $valeur > $_SESSION['maximum']) {
        $_SESSION['error'] = "La valeur doit être entre {$_SESSION['minimum']} et {$_SESSION['maximum']}.";
    } else {
        $_SESSION['coups']++;
        $_SESSION['values_tried'][] = $valeur;
        $_SESSION['error'] = null;

        if ($valeur > $_SESSION['random_number']) {
            $feedback = "haut!";
        } elseif ($valeur < $_SESSION['random_number']) {
            $feedback = "bas!";
        } else {
            write_to_file("Valeur trouvée par le joueur");
            $_SESSION["etat"] = ETAT_3;
            $feedback = "Super tu as trouvé. c'est la valeur " . $valeur . ". Pas pire, mais tu as quand même essayé ces valeurs [" . implode(", ", $_SESSION['values_tried']) . "]";
        }
    }
}

if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_GET['restart_same_interval']) && $_GET['restart_same_interval'] === 'true') {
    write_to_file("Même intervalle, nouvelle valeur");
    $_SESSION["etat"] = ETAT_2;
    $_SESSION['coups'] = 0;
    $_SESSION['random_number'] = generateRandomNumber($_SESSION['minimum'], $_SESSION['maximum']);
    $_SESSION['values_tried'] = [];
    $_SESSION['error'] = null;
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devine la valeur (jeu)</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="JS/script.js"></script>
    <script src="jquery/jquery-3.7.1.js"></script>
</head>
<body>

<?php if ($_SESSION['etat'] == ETAT_1){ ?>
    <?php if (isset($_SESSION['error']) && $_SESSION['error']) { ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php } ?>
    <h1 id="titre">Devine la valeur :o)</h1>
    <p id="message">Salut, voici un petit jeu où tu auras à deviner une valeur. Si tu veux jouer, inscris dans le formulaire suivant les informations demandées et transmets-les.</p>
    <form id="formulaire" action="index.php" method="POST">
        <input type="hidden" name="data" id="dataField" value="<?php echo session_id();?>">
        <label for="name" id="msgName">Écris ton prénom ici: </label><input type="text" id="name" name="name">
        <br>
        <label for="minimum" id="msgValMin">Par la suite, donne-nous la valeur minimum: </label><input type="number" id="minimum" name="minimum">
        <label for="maximum" id="msgValMax"> et la valeur maximum: </label><input type="number" id="maximum" name="maximum">
        <label id="msgInfo"> que nous devrons respecter et entre lesquelles tu veux jouer.</label>
        <p id="msgInfo">À l'intérieur de cet intervalle, nous allons choisir, au hasard, une valeur que tu auras à deviner !!!</p>
        <input class="button-64" id="submit_btn" type="submit" value="Je veux jouer!" name="request_playing">
    </form>

<?php }elseif ($_SESSION['etat'] == ETAT_2){ ?>
    <input type="hidden" name="data" id="dataField" value="<?php echo session_id();?>">
    <h1 id="titre">Devine la valeur <?php echo $_SESSION['name']; ?> </h1>
    <?php if ($_SESSION['error']){ ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>

    <?php }elseif ($_SESSION['coups'] == 0){ ?>
        <p id="message">Une valeur a été choisie par le jeu, à toi de la trouver :o)</p>
    <?php }else{ ?>
        <p id="message">Après <?php echo $_SESSION['coups']; ?> coups, tu es trop <?php echo $feedback; ?></p>
    <?php }?>
    <form id="formulaire" action="index.php" method="POST">
        <label id="entreValue" for="valeurEntre">Entrez une valeur entre <?php echo $_SESSION['minimum']; ?> et <?php echo $_SESSION['maximum']; ?> :</label>
        <input id="valeurEntre" name="valeur" type="number">
        <br>
        <input id="sendValue" type="submit" value="Tranmettre ma valeur!" name="transmit_value">
    </form>

<?php }elseif ($_SESSION['etat'] == ETAT_3){ ?>
    <input type="hidden" name="data" id="dataField" value="<?php echo session_id();?>">
    <h1 id="titre">Devine la valeur <?php echo $_SESSION['name']; ?> </h1>
    <p id="message"><?php echo $feedback; ?></p>
    <a href="index.php?restart_same_interval=true">Recommencer avec la même intervalle</a>
    <br>
    <a href="index.php?reset=true">Recommencer en modifiant l'intervalle</a>
<?php } ?>

</body>
</html>




