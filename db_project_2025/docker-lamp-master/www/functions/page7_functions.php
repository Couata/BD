<?php

function isInBelgium($lat, $lon) {
    return $lon >= 2.51357303225 && $lon <= 6.15665815596 &&
           $lat >= 49.5294835476 && $lat <= 51.4750237087;
}


function getAllArrets($db){
    return $db->query("SELECT * FROM ARRET")->fetchAll();
}

function getArretWithID($db, $selectedID){
    if(!$selectedID)
        return null;

    return $db-> query("SELECT * FROM ARRET WHERE ID = $selectedID")->fetch();
}

function getSelectedArret($db){

    if(isset($_GET['id'])&& $_GET['id'] != ""){
        $selectedID = $_GET['id'];
        return getArretWithID($db, $selectedID);
    }
    return null;
}

function updatePOSTmethod($db){
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        return "";
    }

    if (!isset($_POST['new_id'], $_POST['nom'], $_POST['lat'], $_POST['lon']) || 
        $_POST['new_id'] === "" || $_POST['nom'] === "" || $_POST['lat'] === "" || $_POST['lon'] === "") {
        return "Sélectionnez une valeur pour tous les champs.";
    }

    $newID = $_POST['new_id'];
    $nom = $_POST['nom'];
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];

    $sameValuesMessage = checkSameValues($db, $newID, $nom, $lat, $lon);
    if ($sameValuesMessage) {
        return $sameValuesMessage;
    }

    $stmt = update($db, $nom, $lat, $lon, $newID);

    if ($stmt->rowCount() > 0) {
        return "L'arrêt a été mis à jour avec succès.";
    }

    return "Aucune modification n'a été effectuée.";
}


function update($db, $nom, $lat, $lon, $id){

    $stmt = $db->prepare("UPDATE ARRET SET NOM = ?, LATITUDE = ?, LONGITUDE = ? WHERE ID = ?");
    $stmt->execute([$nom, $lat, $lon, $id]);

    return $stmt;
}

function checkSameValues($db, $id, $nom, $lat, $lon) {
    $selected = getArretWithID($db, $id);

    if (!$selected) {
        return false;
    }

    if ($selected['ID'] == $id) {
        return "L'ID reste le même.";
    }

    if ($selected['NOM'] == $nom) {
        return "Le nom de l'arrêt reste le même.";
    }

    if ($selected['LATITUDE'] == $lat && $selected['LONGITUDE'] == $lon) {
        return "Les coordonnées restent les mêmes.";
    }

    return false; 
}