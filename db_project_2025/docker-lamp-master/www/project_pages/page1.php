<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    ?>
    <head>
        <title>Page1</title>
    </head>
    <body>
        <h1>Page 1</h1>
        <h2> Recherche Agence</h2>
        <div style="display: flex; gap: 20px; align-items: flex-end;">
            <li>
                <form  method="get">
                    <label for="nom">Nom: </label>
                    <input type="text" id="nom_ag" name="nom_agence" placeholder="Entré le nom de L'agence" required>
                    <input type="submit" value="Submit">
                </form>
            </li>
            <li>
                <form  method="get">
                    <label for="nom">Telephone: </label>
                    <input type="text" id="tel_ag" name="tel_agence" placeholder="Entré le numéro de téléphone de L'agence" required>
                    <input type="submit" value="Submit">
                </form>
            </li>
        </div>

        <h2> Recherche Horaire</h2>
        <div style="display: flex; gap: 20px; align: flex-end;">
            <li> 
                <form method="get">
                    <label for="nom">Nom arret: </label>
                    <input type="text" id="nom_ar" name="nom_arret" placeholder="Entré le nom du arret" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Nom trajet: </label>
                    <input type="text" id="nom_traj" name="nom_itineraire" placeholder="Entré le nom du itineraire" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Heure de départ: </label>
                    <input type="time" id="h_dep" name="heure_depart" placeholder="Entré l'heure de départ" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>
            
        </div>

        <h2>Recherche Exception</h2>
        <div style="display: flex; gap: 20px; align: flex-end;">
            <li> 
                <form method="get">
                    <label for="nom">Exception: </label>
                    <input type="text" id="ex" name="exception" required>
                    <input type="submit" value="Submit">
                </form>
            </li>
        </div>
        <!-- Insertion de code PHP -->
        <?php
            include '../db_connection.php';
            function getDataAgence($pdo, $research, $key){
            
                $value = $_GET[$research]; // Récupère la valeur de recherche
                // Construire la requête SQL avec un paramètre préparé
                $sql = "SELECT * FROM AGENCE WHERE $key LIKE :value"; // Utilisation de :value comme paramètre
                $stmt = $pdo->prepare($sql); // Préparer la requête
                // Exécuter la requête avec le paramètre
                $stmt->execute(['value' => "%$value%"]); // Le paramètre de la requête
                $result = $stmt->fetchAll();// Récupérer tous les résultats
                // Afficher les résultats
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

            function getDataHoraire($pdo, $research, $key){
                $value = $_GET[$research];
        
                $sql = "SELECT HEURE_ARRIVEE, HEURE_DEPART, ARRET.NOM AS NOM_ARRET, ITINERAIRE.NOM AS NOM_ITINERAIRE, DIRECTION
                        FROM HORRAIRE
                        JOIN ARRET ON HORRAIRE.ARRET_ID = ARRET.ID
                        JOIN TRAJET ON HORRAIRE.TRAJET_ID = TRAJET.TRAJET_ID
                        JOIN ITINERAIRE ON TRAJET.ITINERAIRE_ID = ITINERAIRE.ID
                        WHERE $key = :value";

                $stmt = $pdo->prepare($sql);
                $stmt->execute(['value' => "%$value%"]);
                $result = $stmt->fetchAll();
        
                echo "<ul>";
                if (empty($result)) {
                    echo "<li>Pas de résultats trouvés</li>";
                } else {
                    foreach ($result as $row) {
                        echo "<li>ARRIVÉE: {$row['HEURE_ARRIVEE']}, DÉPART: {$row['HEURE_DEPART']}, ARRÊT: {$row['NOM_ARRET']}, ITINÉRAIRE: {$row['NOM_ITINERAIRE']}, DIRECTION: {$row['DIRECTION']}</li>";
                    }
                }
                echo "</ul>";
            }
            

            
            
            if (isset($_GET['nom_agence'])) {
                getDataAgence($pdo, 'nom_agence', 'NOM');
            } elseif (isset($_GET['tel_agence'])){
                getDataAgence($pdo, 'tel_agence', 'TELEPHONE');
            } elseif (isset($_GET['nom_arret'])) {
                getDataHoraire($pdo, 'nom_arret', 'ARRET.NOM');
            } elseif (isset($_GET['nom_itineraire'])) {
                getDataHoraire($pdo, 'nom_itineraire', 'ITINERAIRE.NOM');
            } elseif (isset($_GET['heure_depart'])){
                getDataHoraire($pdo, 'heure_depart', 'HEURE_DEPART');
            } else {
                echo "Veuillez entrer un critère de recherche.";
            }
        ?>
    </body>
</html>