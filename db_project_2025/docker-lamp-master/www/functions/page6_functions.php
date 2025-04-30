<?php

function getAllItineraires($db){
    return $db->query("SELECT ID,NOM FROM ITINERAIRE ORDER BY NOM") -> fetchALL(PDO::FETCH_ASSOC);
}

function getArrets($db, $itineraire_id, $direction) {
    $prep = $db->prepare("SELECT ART.ID, ART.NOM FROM ARRET ART
                          JOIN ARRET_DESSERVI AD ON ART.ID = AD.ARRET_ID
                          WHERE AD.ITINERAIRE_ID = ? AND AD.DIRECTION = ?
                          ORDER BY AD.ORDER");

    $prep->execute([$itineraire_id, $direction]);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}


function deleteItineraire($db, $id) {
    $prep = $db->prepare("DELETE FROM ITINERAIRE WHERE ID = ?");
    return $prep->execute([$id]);
}


function insertTrajet($db, $trajet_id, $service_id, $itineraire_id, $direction){
    $prep = $db->query("INSERT INTO TRAJET (TRAJET_ID, SERVICE_ID, ITINERAIRE_ID, DIRECTION) 
                            VALUES (?,?,?,?)");
    return $prep->exectute([$trajet_id, $service_id, $itineraire_id, $direction]);
}

function insertHoraire($db, $trajet_id, $itineraire_id, $arret_id, $arrivee, $depart) {
    $prep = $db->prepare("INSERT INTO HORRAIRE (TRAJET_ID, ITINERAIRE_ID, ARRET_ID, HEURE_ARRIVEE, HEURE_DEPART)
                          VALUES (?, ?, ?, ?, ?)");
    return $prep->execute([$trajet_id, $itineraire_id, $arret_id, $arrivee, $depart]);
}