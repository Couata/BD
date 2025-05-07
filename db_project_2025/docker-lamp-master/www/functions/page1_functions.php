<?php
//---------------------------------------------------------------------------
//                              Fonctions
//---------------------------------------------------------------------------

// Function to get agencies based on search key and value
function getDataAgence($pdo, $research, $key) {
    $value = $_GET[$research];
    $sql = "SELECT * FROM AGENCE WHERE $key LIKE :value";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['value' => "%$value%"]);
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

// Function to get horaires with LIKE
function getDataHoraire($pdo, $research, $key) {
    $value = $_GET[$research];
    $sql = "SELECT * FROM view_horaires_details WHERE $key LIKE :value";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['value' => "%$value%"]);
    $result = $stmt->fetchAll();

    echo "<ul>";
    if (empty($result)) {
        echo "<li>Pas de résultats trouvés</li>";
    } else {
        foreach ($result as $row) {
            echo "<li>SERVICE: {$row['NOM_SERVICE']}, ARRIVÉE: {$row['HEURE_ARRIVEE']}, DÉPART: {$row['HEURE_DEPART']}, ARRÊT: {$row['NOM_ARRET']}, ITINÉRAIRE: {$row['NOM_ITINERAIRE']}, DIRECTION: {$row['DIRECTION']}</li>";
        }
    }
    echo "</ul>";
}

// Function to get horaires by exact hour
function getDataHoraireHeure($pdo, $research, $key) {
    $value = $_GET[$research];
    $exact_value = $value . ":00";
    $sql = "SELECT * FROM view_horaires_details WHERE $key = :exact_value";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['exact_value' => $exact_value]);
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

// Function to get exceptions with LIKE
function getDataException($pdo, $research, $key) {
    $value = $_GET[$research];
    $sql = "SELECT * FROM view_exceptions_details WHERE $key LIKE :value";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['value' => "%$value%"]);
    $result = $stmt->fetchAll();

    echo "<ul>";
    if (empty($result)) {
        echo "<li>Pas de résultats trouvés</li>";
    } else {
        foreach ($result as $row) {
            $text = match ($row['CODE']) {
                1 => "Le service est ajouté",
                2 => "Le service est supprimé",
                default => "Erreur : code inconnu"
            };
            echo "<li>$text pour les {$row['NOM_ITINERAIRE']} pendant les {$row['NOM_SERVICE']} : du {$row['DATE_DEBUT']} au {$row['DATE_FIN']}</li>";
        }
    }
    echo "</ul>";
}

// Function to get exceptions by exact date
function getDataExceptionDate($pdo, $research, $key) {
    $value = $_GET[$research];
    $sql = "SELECT 
                MIN(CODE) AS CODE, 
                NOM_SERVICE, 
                NOM_ITINERAIRE, 
                MIN(DATE_DEBUT) AS DATE_DEBUT, 
                MIN(DATE_FIN) AS DATE_FIN
            FROM view_exceptions_details
            WHERE $key LIKE :value
            GROUP BY NOM_SERVICE, NOM_ITINERAIRE";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['value' => $value]);
    $result = $stmt->fetchAll();

    echo "<ul>";
    if (empty($result)) {
        echo "<li>Pas de résultats trouvés</li>";
    } else {
        foreach ($result as $row) {
            $text = match ($row['CODE']) {
                1 => "Le service est ajouté",
                2 => "Le service est supprimé",
                default => "Erreur : code inconnu"
            };
            echo "<li>$text pour les {$row['NOM_ITINERAIRE']} pendant les {$row['NOM_SERVICE']} : (du {$row['DATE_DEBUT']} au {$row['DATE_FIN']})</li>";
        }
    }
    echo "</ul>";
}
?>
