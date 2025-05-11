<?php
//get the average stop time
function getAllTrajetAvgStopTime($db){
    try {
        $db->exec("SET TRANSACTION ISOLATION LEVEL REPEATABLE READ");
        $db->beginTransaction();
        $prep= $db->prepare(
            "SELECT NOM,TRAJET_ID,AVG_TIME_STOP
            FROM(SELECT ITINERAIRE_ID,TRAJET_ID,AVG(TIME_TO_SEC(HEURE_DEPART)-TIME_TO_SEC(HEURE_ARRIVEE)) as AVG_TIME_STOP 
            FROM `HORRAIRE` 
            WHERE (HEURE_ARRIVEE != '00:00:00' AND HEURE_DEPART != '00:00:00') 
            GROUP BY ITINERAIRE_ID,TRAJET_ID WITH ROLLUP) as TMP LEFT JOIN ITINERAIRE ON ITINERAIRE.ID=TMP.ITINERAIRE_ID 
            ORDER BY ITINERAIRE.NOM IS NULL,ITINERAIRE.NOM ASC,TRAJET_ID IS NULL, AVG_TIME_STOP ASC;");
        $prep->execute();
        $db->commit();
        return $prep->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['message'] = "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
        return;
    }
    $prep= $db->prepare(
        "SELECT NOM,TRAJET_ID,AVG_TIME_STOP
        FROM(SELECT ITINERAIRE_ID,TRAJET_ID,AVG(TIME_TO_SEC(HEURE_DEPART)-TIME_TO_SEC(HEURE_ARRIVEE)) as AVG_TIME_STOP 
        FROM `HORRAIRE` 
        WHERE (HEURE_ARRIVEE != '00:00:00' AND HEURE_DEPART != '00:00:00') 
        GROUP BY ITINERAIRE_ID,TRAJET_ID WITH ROLLUP) as TMP LEFT JOIN ITINERAIRE ON ITINERAIRE.ID=TMP.ITINERAIRE_ID 
        ORDER BY ITINERAIRE.NOM IS NULL,ITINERAIRE.NOM ASC,TRAJET_ID IS NULL, AVG_TIME_STOP ASC;");
    $prep->execute();
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}
?>
