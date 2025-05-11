<?php
function send_service($db){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
        // Récupérer et sécuriser les champs
        $nom_service = trim($_POST['nom_service']);
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $jours = $_POST['jours'] ?? [];
        $exceptions = trim($_POST['exception_service']);
    
        // Vérifier que tous les champs nécessaires sont là
        if (empty($nom_service) || empty($date_debut) || empty($date_fin)) {
            die("Tous les champs doivent être remplis.");
        }
    
        // Créer les colonnes booléennes pour chaque jour
        $jours_sem = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $jours_valeurs = [];
        foreach ($jours_sem as $jour) {
            $jours_valeurs[$jour] = in_array($jour, $jours) ? 1 : 0;
        }

        $stmt = $db->query("SELECT MAX(ID) as max_id FROM SERVICE");
        $row = $stmt->fetch();
        $next_id = $row['max_id'] + 1;
    
        // Insérer dans SERVICE
        $sql = "INSERT INTO SERVICE (ID, NOM, LUNDI, MARDI, MERCREDI, JEUDI, VENDREDI, SAMEDI, DIMANCHE, DATE_DEBUT, DATE_FIN)
                VALUES (:id, :nom, :lu, :ma, :me, :je, :ve, :sa, :di, :debut, :fin)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id' => $next_id,
            ':nom' => $nom_service,
            ':lu' => $jours_valeurs['Lundi'],
            ':ma' => $jours_valeurs['Mardi'],
            ':me' => $jours_valeurs['Mercredi'],
            ':je' => $jours_valeurs['Jeudi'],
            ':ve' => $jours_valeurs['Vendredi'],
            ':sa' => $jours_valeurs['Samedi'],
            ':di' => $jours_valeurs['Dimanche'],
            ':debut' => $date_debut,
            ':fin' => $date_fin
        ]);
    
        // Traiter les exceptions si non vide
        if (!empty($exceptions)) {
            $lines = explode("\n", $exceptions);
            foreach ($lines as $line) {
                $line = trim($line);
                if (preg_match('/^(\d{4}-\d{2}-\d{2})\s+(INCLUS|EXCLUS)$/', $line, $matches)) {
                    $date = $matches[1];
                    $code = $matches[2];
                    if($code == "INCLUS"){
                        $nb_code = 1;
                    }
                    elseif($code == "EXCLUS"){
                        $nb_code = 2;
                    }
                    $stmt = $db->prepare("INSERT INTO EXCEPTION (SERVICE_ID, DATE, CODE) VALUES (:id, :date, :code)");
                    $stmt->execute([
                        ':id' => $next_id,
                        ':date' => $date,
                        ':code' => $nb_code
                    ]);
                } else {
                    echo "Ligne d'exception ignorée (format invalide) : $line<br>";
                }
            }
        }
        echo "Service ajouté avec succès.";
    }
    
}
?>