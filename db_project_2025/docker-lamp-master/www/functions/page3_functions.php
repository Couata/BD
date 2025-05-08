<?php

function validate_date_range($start, $end) {
    if ($end < $start) {
        echo "<div id='message'> La date de fin doit être après la date de début</div>";
        return false;
    }
    return true;
}

function get_services_by_date_range($pdo, $start, $end) {
    $query = "SELECT * FROM services_par_date_final WHERE date_service BETWEEN :date_debut AND :date_fin";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':date_debut' => $start,
        ':date_fin' => $end
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
