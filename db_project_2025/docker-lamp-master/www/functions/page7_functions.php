<?php

function isInBelgium($lat, $lon) {
    return $lon >= 2.51357303225 && $lon <= 6.15665815596 &&
           $lat >= 49.5294835476 && $lat <= 51.4750237087;
}

function getAllBelgianArrets($db){
    $query = "SELECT * FROM ARRET WHERE LATITUDE BETWEEN 49.5294835476 AND 51.4750237087 AND LONGITUDE BETWEEN 2.51357303225 AND 6.15665815596";
    return $db->query($query)->fetchAll();
}

function getArretWithID($db, $selectedID){
    if(!$selectedID)
        return null;

    return $db->query("SELECT * FROM ARRET WHERE ID = $selectedID")->fetch();
}

function getSelectedArret($db){
    if(isset($_GET['id']) && $_GET['id'] != ""){
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
    $currentID = $_GET['id'];

    if (!isInBelgium($lat, $lon)) {
        return "Les coordonnées doivent être en Belgique.";
    }

    $selected = getArretWithID($db, $currentID);

    if (!$selected) {
        return "L'arrêt sélectionné n'existe pas.";
    }

    try {
        $db->beginTransaction();
        $oldID = $currentID;
        if ($newID != $currentID) {
            if (checkNewID($db, $newID)) {
                return "L'ID $newID est déjà utilisé.";
            }
            #update ID in the tables first
            $prep = $db->prepare("UPDATE ARRET SET ID = ? WHERE ID = ?");
            $prep->execute([$newID, $currentID]);

            $prep = $db->prepare("UPDATE ARRET_DESSERVI SET ARRET_ID = ? WHERE ARRET_ID = ?");
            $prep->execute([$newID, $currentID]);

            $currentID = $newID; 
        }

        if(checkSameValues($db,$oldID, $currentID, $nom, $lat, $lon))
            return "Aucune modification n'a été effectuée.";

        #update other values / overwrites them
        $prep = $db->prepare("UPDATE ARRET SET NOM = ?, LATITUDE = ?, LONGITUDE = ? WHERE ID = ?");
        $prep->execute([$nom, $lat, $lon, $currentID]);

  
        $db->commit();



        return "L'arrêt a été mis à jour avec succès.";
    } catch (PDOException $e) {
        $db->rollBack();
        return "Erreur lors de la mise à jour de l'arrêt: " . $e->getMessage();
    }
}


function checkNewID($db, $id){
 
    $prep = $db->prepare("SELECT COUNT(*) FROM ARRET WHERE ID = ?");
    $prep->execute([$id]);
    return $prep->fetchColumn() > 0; #checks if the ID exist in the table since ID Int > 0
    
}

function checkSameValues($db, $oldID ,$id, $nom, $lat, $lon) {
    $selected = getArretWithID($db, $id);

    if (!$selected) {
        return false;
    }

    if ($selected['NOM'] == $nom && $selected['LATITUDE'] == $lat && $selected['LONGITUDE'] == $lon && $oldID == $id) {
        return true;
    }

    return false;
}
