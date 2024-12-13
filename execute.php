<?php

if (isset($_POST['data'])) {
    $receivedData = $_POST['data'];
}
function write_to_file($message, $id){

    $fichier = fopen("log/trace_log.txt", "a");
    if ($fichier) {
        fwrite($fichier, date("d/m/Y H:i:s") . " - " . $message . " - " . $id . "\n");
        fclose($fichier);
    } else {
        error_log("Impossible d'ouvrir le fichier trace_log.txt pour l'écriture.");
    }

}
write_to_file("Formulaire prénom et valeurs avec erreurs", $receivedData);
?>