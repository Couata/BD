<?php

function getAllService($pdo){
    $stmt = $pdo->prepare("SELECT NOM FROM SERVICE ORDER BY NOM");
    $stmt ->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ------------------------------------
//              AGENCE
// ------------------------------------

function getAgenceSearch($pdo){
    $conditions = [];
    $params = [];

    $agence = isset($_GET['nom_agence']) ? trim($_GET['nom_agence']) : '';
    $tel = isset($_GET['tel_agence']) ? trim($_GET['tel_agence']) : '';
    
    if ($agence !== ''){
        $conditions[] = "NOM LIKE :agence";
        $params['agence'] = "%$agence%";
    }
    if($tel !== ''){
        $conditions[] = "TELEPHONE LIKE :tel";
        $params['tel'] = "%$tel%";
    }
    if (empty($conditions)) {
        echo "Il est nécessaire de remplir au moins une case.";
    } else{
        $crit_recherche = implode(" AND ", $conditions);
        $sql = "SELECT * FROM AGENCE WHERE $crit_recherche";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll();

        echo "<ul>";
        if (empty($result)) {
            echo "<li>Pas de résultats trouvés</li>";
        } else {
            foreach ($result as $row) {
                echo "<li>NOM: {$row['NOM']}, URL: {$row['URL']}, TELEPHONE: {$row['TELEPHONE']}, SIEGE: {$row['SIEGE']}</li>";
            }
        }
        echo "</ul>";
    }
}
// ------------------------------------
//              Horaire
// ------------------------------------

function getHoraireSearch($pdo){
    $conditions = [];
    $params = [];

    $arret = isset($_GET['nom_arret']) ? trim($_GET['nom_arret']) : '';
    $itineraire = isset($_GET['nom_itineraire']) ? trim($_GET['nom_itineraire']) : '';
    $heure_arrive = isset($_GET['heure_arrive']) ? trim($_GET['heure_arrive']) : '';
    $heure_depart = isset($_GET['heure_depart']) ? trim($_GET['heure_depart']) : '';

    if ($arret !== '') {
        $conditions[] = "NOM_ARRET LIKE :arret";
        $params['arret'] = "%$arret%";
    }

    if ($itineraire !== '') {
        $conditions[] = "NOM_ITINERAIRE LIKE :itineraire";
        $params['itineraire'] = "%$itineraire%";
    }

    if ($heure_arrive !== '') {
        $heure_arrive = $heure_arrive . ":00";
        $conditions[] = "HEURE_ARRIVEE = :heure_arrive";
        $params['heure_arrive'] = $heure_arrive;
    }

    if ($heure_depart !== '') {
        $heure_depart = $heure_depart . ":00";
        $conditions[] = "HEURE_DEPART = :heure_depart";
        $params['heure_depart'] = $heure_depart;
    }
    if (empty($conditions)) {
        echo "Il est nécessaire de remplir au moins une case.";
    } else{
        $crit_recherche = implode(" AND ", $conditions);
        $sql = "SELECT * FROM view_horaires_details WHERE $crit_recherche";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll();

        echo "<ul>";
        if (empty($result)) {
            echo "<li>Pas de résultats trouvés</li>";
        } else {
            foreach ($result as $row) {
                echo "<li>SERVICE: {$row['NOM_SERVICE']}, AGENCE: {$row['NOM_AGENCE']}, ARRIVÉE: {$row['HEURE_ARRIVEE']}, DÉPART: {$row['HEURE_DEPART']}, ARRÊT: {$row['NOM_ARRET']}, ITINÉRAIRE: {$row['NOM_ITINERAIRE']}, DIRECTION: {$row['DIRECTION']}</li>";
            }
        }
        echo "</ul>";
    }
}

// ------------------------------------
//              Exception
// ------------------------------------

function getExceptionSearch($pdo){
    $conditions = [];
    $params = [];

    $itineraire = isset($_GET['itineraire_exception']) ? $_GET['itineraire_exception'] : '';
    $service = isset($_GET['service_exception']) ? $_GET['service_exception'] : '';
    $date_exception = isset($_GET['date_exception']) ? $_GET['date_exception'] : '';
    
    if($itineraire !== ''){
        $conditions[] = "NOM_ITINERAIRE LIKE :itineraire";
        $params['itineraire'] = "%$itineraire%";
    }
    if($service !== ''){
        $conditions[] = "NOM_SERVICE LIKE :service";
        $params['service'] = "%$service%";
    }
    if($date_exception !== ''){
        $conditions[] = "DATE_EXCEPTION_TEST = :date_exception";
        $params['date_exception'] = $date_exception;
    }

    if (empty($conditions)) {
        echo "Il est nécessaire de remplir au moins une case.";
        return;
    } else {
        $crit_recherche = implode(" AND ", $conditions);
        $sql = "SELECT 
                    MIN(CODE) AS CODE, 
                    NOM_SERVICE, 
                    NOM_ITINERAIRE, 
                    DATE_EXCEPTION_TEST
                FROM view_exceptions_details
                WHERE $crit_recherche
                GROUP BY NOM_SERVICE, NOM_ITINERAIRE, DATE_EXCEPTION_TEST";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll();
    
        echo "<ul>";
        if (empty($result)) {
            echo "<li>Pas de résultats trouvés</li>";
        } else {
            foreach ($result as $row) {
                switch ($row['CODE']) {
                    case 1:
                        $text = "Le service est ajouté";
                        break;
                    case 2:
                        $text = "Le service est supprimé";
                        break;
                    default:
                        $text = "Erreur : code inconnu";
                };
                echo "<li>$text pour les {$row['NOM_ITINERAIRE']} pendant les {$row['NOM_SERVICE']}, le {$row['DATE_EXCEPTION_TEST']}</li>";
            }
        }
        echo "</ul>";
    }
}
?>