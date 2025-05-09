
<?php
// Function to delete an itinerary
function deleteItineraire($db, $itineraire_id) {
    try {
        $db->beginTransaction();
        $prep = $db->prepare("DELETE FROM ITINERAIRE WHERE ID = ?");
        $prep->execute([$itineraire_id]);
        $db->commit();
        return true;
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['message'] = "<p style='color:red;'>Erreur lors de la suppression de l'itinéraire: " . $e->getMessage() . "</p>";
        return false;
    }
}

// Function to insert a new trajet
function insertTrajet($db, $trajet_id, $service_id, $itineraire_id, $direction) {
    if (empty($trajet_id) || empty($service_id) || empty($itineraire_id) || $direction === "") {
        return false;
    }

    try {
        $db->beginTransaction();
        $prep = $db->prepare("INSERT INTO TRAJET (TRAJET_ID, SERVICE_ID, ITINERAIRE_ID, DIRECTION) 
                              VALUES (?, ?, ?, ?)");
        $prep->execute([$trajet_id, $service_id, $itineraire_id, $direction]);
        $db->commit();
        return true;
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['message'] = "<p style='color:red;'>Erreur lors de l'insertion du trajet: " . $e->getMessage() . "</p>";
        return false;
    }
}

// Function to insert horaire for a stop
function insertHoraire($db, $trajet_id, $itineraire_id, $arret_id, $arrivee, $depart) {
    try {
        $db->beginTransaction();
        $prep = $db->prepare("INSERT INTO HORRAIRE (TRAJET_ID, ITINERAIRE_ID, ARRET_ID, HEURE_ARRIVEE, HEURE_DEPART)
                              VALUES (?, ?, ?, ?, ?)");
        $prep->execute([$trajet_id, $itineraire_id, $arret_id, $arrivee, $depart]);
        $db->commit();
        return true;
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['message'] = "<p style='color:red;'>Erreur lors de l'insertion de l'horaire: " . $e->getMessage() . "</p>";
        return false;
    }
}

// Function to generate a unique trajet ID
function generateUniqueTrajetId($db) {
    while (true) {
        $id = uniqid('trajet_');
        $prep = $db->prepare("SELECT 1 FROM TRAJET WHERE TRAJET_ID = ? LIMIT 1");
        $prep->execute([$id]);
        if (!$prep->fetch()) {
            return $id;
        }
    }
}

// Get all itineraries
function getAllItineraires($db) {
    $prep = $db->prepare("SELECT ID, NOM FROM ITINERAIRE ORDER BY NOM");
    $prep->execute();
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

// Get all stops for an itinerary
function getArrets($db, $itineraire_id) {
    $prep = $db->prepare("
        SELECT ART.ID, ART.NOM
        FROM ARRET ART
        JOIN ARRET_DESSERVI AD ON ART.ID = AD.ARRET_ID
        WHERE AD.ITINERAIRE_ID = ?
        ORDER BY AD.SEQUENCE
    ");
    $prep->execute([$itineraire_id]);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}