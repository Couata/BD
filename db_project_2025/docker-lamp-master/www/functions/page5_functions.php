<?php
// get Arrets with a min number of stops
function getArretsWith($db,$gare,$min_stop){
    $prep= $db->prepare(
        "SELECT ARRET.NOM as NOM_ARRET, SERVICE.NOM as NOM_SERVICE, COUNT(TRAJET_ID) as NB_STOP
        FROM ARRET LEFT JOIN ARRET_DESSERVI ON ARRET_DESSERVI.ARRET_ID = ARRET.ID LEFT JOIN TRAJET ON ARRET_DESSERVI.ITINERAIRE_ID = TRAJET.ITINERAIRE_ID LEFT JOIN SERVICE ON SERVICE.ID=TRAJET.SERVICE_ID
        WHERE ARRET.NOM LIKE ?
        GROUP BY NOM_ARRET,SERVICE_ID
        HAVING NB_STOP>=?
        ORDER BY NB_STOP DESC;");
    $prep->execute(['%' . $gare . '%',$min_stop]);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
} 
?>