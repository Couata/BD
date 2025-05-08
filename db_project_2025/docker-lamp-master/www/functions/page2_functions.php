<?php

function display_post_data($post) {
    echo "<ul>";
    if(isset($post['nom_service'])){
        echo "{$post['nom_service']} <br>";
    }

    if(isset($post['exception_service'])){
        $lines = explode("\n", $post['exception_service']);
        foreach ($lines as $line) {
            $x = trim($line);
            if ($x) echo '<li>'. $x .'</li>';
        }
    }

    if (isset($post['jours'])) {
        foreach ($post['jours'] as $jour) {
            echo "Jour coché : $jour<br>";
        }
    } else {
        echo "Aucun jour n'a été coché.";
    }
    echo '</ul>';
}

function get_day_flags($jours) {
    return [
        'lundi' => in_array('Lundi', $jours) ? 1 : 0,
        'mardi' => in_array('Mardi', $jours) ? 1 : 0,
        'mercredi' => in_array('Mercredi', $jours) ? 1 : 0,
        'jeudi' => in_array('Jeudi', $jours) ? 1 : 0,
        'vendredi' => in_array('Vendredi', $jours) ? 1 : 0,
        'samedi' => in_array('Samedi', $jours) ? 1 : 0,
        'dimanche' => in_array('Dimanche', $jours) ? 1 : 0,
    ];
}

function validate_dates($date_debut, $date_fin) {
    $valid = true;
    $today = date("Y-m-d");

    if($date_debut > $date_fin){
        echo "La date de fin ne doit pas être avant la date de début <br>";
        $valid = false;
    }
    if($date_debut < $today){
        echo "La date de début du service ne peut pas être dans le passé ($today) <br>";

    }
    return $valid;
}

function parse_exceptions($text) {
    $lines = explode("\n", $text);
    $parsed_exceptions = [];
    $valid = true;

    foreach ($lines as $line) {
        $parts = explode(" ", trim($line));
        if (count($parts) == 2) {
            $date = $parts[0];
            $type = strtoupper($parts[1]);
            if ($type == "INCLUS") {
                $parsed_exceptions[] = ['date' => $date, 'exception' => 1];
            } elseif ($type == "EXCLUS") {
                $parsed_exceptions[] = ['date' => $date, 'exception' => 2];
            } else {
                $valid = false;
            }
        } else {
            $valid = false;
        }
    }

    return [$parsed_exceptions, $valid];
}

function get_last_service_id($pdo) {
    $sql = "SELECT MAX(ID) AS ID FROM SERVICE";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['ID'] ?? null;
}
