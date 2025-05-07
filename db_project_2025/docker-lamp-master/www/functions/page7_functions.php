<?php
// function to get all Belgian Arrets
function getAllBelgianArrets($db){
    $prep = $db->prepare("SELECT * FROM ARRET WHERE LATITUDE BETWEEN ? AND ? AND LONGITUDE BETWEEN ? AND ?");
    $prep->execute([49.5294835476, 51.4750237087, 2.51357303225, 6.15665815596]);
    return $prep->fetchAll();
}


//fucntion to get Arret of a specific ID
function getArretWithID($db, $selectedID){
    if(!$selectedID)
        return null;

    $prep = $db->prepare("SELECT * FROM ARRET WHERE ID = ?");
    $prep->execute([$selectedID]);
    return $prep->fetch();
}

//function to get all arrets
function getSelectedArret($db){
    if(isset($_GET['id']) && $_GET['id'] != ""){
        $selectedID = $_GET['id'];
        return getArretWithID($db, $selectedID);
    }
    return null;
}

//function which listens to post request and updates the page.
//handles UPDATE requests -> dependencies are handled by triggers
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

            $prep = $db->prepare("UPDATE ARRET SET ID = ? WHERE ID = ?");
            $prep->execute([$newID, $currentID]);

            $currentID = $newID; 
        }

        if(checkSameValues($db,$oldID, $currentID, $nom, $lat, $lon))
            return "Aucune modification n'a été effectuée.";


        $prep = $db->prepare("UPDATE ARRET SET NOM = ?, LATITUDE = ?, LONGITUDE = ? WHERE ID = ?");
        $prep->execute([$nom, $lat, $lon, $currentID]);

        $db->commit();

        return "L'arrêt a été mis à jour avec succès.";
    } catch (PDOException $e) {
        $db->rollBack();
        

        if ($e->getCode() == '45000') {
            return $e->getMessage(); // This will be the error message from the SIGNAL in the trigger
        }

        return "Erreur lors de la mise à jour de l'arrêt: " . $e->getMessage();
    }
}

//check if the newID does not already exist
function checkNewID($db, $id){
    $prep = $db->prepare("SELECT COUNT(*) FROM ARRET WHERE ID = ?");
    $prep->execute([$id]);
    return $prep->fetchColumn() > 0; // Check if the ID already exists by counting if it occurs more then 0 times in the table
}

//check if some values have changed or not
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

?>