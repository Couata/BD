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

        // Insert trajet into the database
        $prep = $db->prepare("INSERT INTO TRAJET (TRAJET_ID, SERVICE_ID, ITINERAIRE_ID, DIRECTION) 
                              VALUES (?, ?, ?, ?)");
        $prep->execute([$trajet_id, $service_id, $itineraire_id, $direction]);

        $db->commit();
        return true;
    } catch (PDOException $e) {
        $db->rollBack();

        if (strpos($e->getMessage(), 'Le ID du trajet doit être unique') !== false) {
            $_SESSION['message'] = "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
        } else {
            $_SESSION['message'] = "<p style='color:red;'>Erreur lors de l'insertion du trajet: " . $e->getMessage() . "</p>";
        }
        return false;
    }
}

// Function to insert horaire for each stop
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

        if (strpos($e->getMessage(), 'arrivée doit être avant le départ') !== false) {
            $_SESSION['message'] = "<p style='color:red;'>Erreur: " . $e->getMessage() . "</p>";
        } else {
            $_SESSION['message'] = "<p style='color:red;'>Erreur lors de l'insertion de l'horaire: " . $e->getMessage() . "</p>";
        }
        return false;
    }
}

// Function to generate unique trajet ID
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

// Function to fetch all itineraries
function getAllItineraires($db) {
    $prep = $db->prepare("SELECT ID, NOM FROM ITINERAIRE ORDER BY NOM");
    $prep->execute();
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch all stops for a specific itinerary
function getArrets($db, $itineraire_id) {
    $prep = $db->prepare("SELECT ARRET_ID AS ID, ARRET_NOM AS NOM FROM view_arrets_par_itineraire WHERE ITINERAIRE_ID = ? ORDER BY SEQUENCE");
    $prep->execute([$itineraire_id]);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

?>
