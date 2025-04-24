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
                    <input type="text" id="nom_ar" name="nom_arret" placeholder="Nom de l'arret" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Nom Itinéraire: </label>
                    <input type="text" id="nom_traj" name="nom_itineraire" placeholder="Nom de l'itineraire" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Heure d'arrivé: </label>
                    <input type="time" id="h_ariv" name="heure_arrive" placeholder="Heure d'arrivé" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Heure de départ: </label>
                    <input type="time" id="h_dep" name="heure_depart" placeholder="Heure de départ" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>
            
        </div>

        <h2>Recherche Exception</h2>
        <div style="display: flex; gap: 20px; align: flex-end;">
            <li> 
                <form method="get">
                    <label for="nom">Nom de l'itiniéraire: </label>
                    <input type="text" id="it_ser" name="itineraire_exception" placeholder="Nom de l'itinéraire" required>
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Nom du service: </label>
                    <input type="text" id="ex_ser" name="service_exception" placeholder="Nom du service" required>
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Date de l'exception: </label>
                    <input type="date" id="da_ser" name="date_exception" placeholder="Date d'exception" required>
                    <input type="submit" value="Submit">
                </form>
            </li>
        </div>
        
        <!-- Insertion de code PHP -->
        <?php
            include '../db_connection.php';

            
            //---------------------------------------------------------------------------
            //                              Fonctions
            //---------------------------------------------------------------------------

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
        
                $sql = "SELECT HEURE_ARRIVEE, HEURE_DEPART, ARRET.NOM AS NOM_ARRET, ITINERAIRE.NOM AS NOM_ITINERAIRE, DIRECTION, SERVICE.NOM AS NOM_SERVICE
                        FROM HORRAIRE
                        JOIN ARRET ON HORRAIRE.ARRET_ID = ARRET.ID
                        JOIN TRAJET ON HORRAIRE.TRAJET_ID = TRAJET.TRAJET_ID
                        JOIN ITINERAIRE ON TRAJET.ITINERAIRE_ID = ITINERAIRE.ID
                        JOIN AGENCE ON ITINERAIRE.AGENCE_ID = AGENCE.ID
                        JOIN SERVICE ON TRAJET.SERVICE_ID = SERVICE.ID
                        WHERE $key LIKE :value";

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

            function getDataHoraireHeure($pdo, $research, $key){
                $value = $_GET[$research];
                $exact_value = $value.":00";
        
                $sql = "SELECT HEURE_ARRIVEE, HEURE_DEPART, ARRET.NOM AS NOM_ARRET, ITINERAIRE.NOM AS NOM_ITINERAIRE, DIRECTION, AGENCE.NOM AS NOM_AGENCE, SERVICE.NOM AS NOM_SERVICE
                        FROM HORRAIRE
                        JOIN ARRET ON HORRAIRE.ARRET_ID = ARRET.ID
                        JOIN TRAJET ON HORRAIRE.TRAJET_ID = TRAJET.TRAJET_ID
                        JOIN ITINERAIRE ON TRAJET.ITINERAIRE_ID = ITINERAIRE.ID
                        JOIN AGENCE ON ITINERAIRE.AGENCE_ID = AGENCE.ID
                        JOIN SERVICE ON TRAJET.SERVICE_ID = SERVICE.ID
                        WHERE $key = :exact_value";

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

            function getDataException($pdo, $research, $key){
                $value = $_GET[$research];

                $sql = "SELECT DISTINCT CODE, SERVICE.NOM AS NOM_SERVICE, ITINERAIRE.NOM AS NOM_ITINERAIRE, DATE_DEBUT, DATE_FIN
                        FROM EXCEPTION
                        JOIN SERVICE ON EXCEPTION.SERVICE_ID = SERVICE.ID
                        JOIN TRAJET ON SERVICE.ID = TRAJET.SERVICE_ID
                        JOIN ITINERAIRE ON TRAJET.ITINERAIRE_ID = ITINERAIRE.ID
                        WHERE $key LIKE :value";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['value' => "%$value%"]);
                $result = $stmt->fetchAll();


                echo "<ul>";
                if (empty($result)) {
                    echo "<li>Pas de résultats trouvés</li>";
                } else {
                    foreach ($result as $row) {

                        // Message selon le code
                        if ($row['CODE'] == 1) {
                            $text = "Le service est ajouté";
                        } elseif ($row['CODE'] == 2) {
                            $text = "Le service est supprimé";
                        } else {
                            $text = "Erreur : code inconnu";
                        }
                        

                        // Construction de la liste des jours
                        /*
                        $jours_actifs = [];
                        if ($row['LUNDI']) $jours_actifs[] = "lundi";
                        if ($row['MARDI']) $jours_actifs[] = "mardi";
                        if ($row['MERCREDI']) $jours_actifs[] = "mercredi";
                        if ($row['JEUDI']) $jours_actifs[] = "jeudi";
                        if ($row['VENDREDI']) $jours_actifs[] = "vendredi";
                        if ($row['SAMEDI']) $jours_actifs[] = "samedi";
                        if ($row['DIMANCHE']) $jours_actifs[] = "dimanche";
                        
                        // Transformation du tableau en phrase
                        $jours_str = implode(", ", $jours_actifs);
                        */
                        // Affichage
                        echo "<li>$text pour les {$row['NOM_ITINERAIRE']} pendant les {$row['NOM_SERVICE']} : du {$row['DATE_DEBUT']} au {$row['DATE_FIN']}</li>";
                        

                    }
                }
                echo "</ul>";
            }

            function getDataExceptionDate($pdo, $research){
                $value = $_GET[$research];  
                var_dump($value);  // Vérification de la valeur reçue (doit afficher une date, exemple: 2025-04-24)
            
                // Préparer la requête SQL avec un paramètre nommé :value
                $sql = "SELECT CODE, SERVICE.NOM AS NOM_SERVICE, ITINERAIRE.NOM AS NOM_ITINERAIRE, DATE_DEBUT, DATE_FIN
                        FROM EXCEPTION
                        JOIN SERVICE ON EXCEPTION.SERVICE_ID = SERVICE.ID
                        JOIN TRAJET ON EXCEPTION.SERVICE_ID = TRAJET.SERVICE_ID
                        JOIN ITINERAIRE ON TRAJET.ITINERAIRE_ID = ITINERAIRE.ID
                        WHERE DATE_DEBUT <= :value AND DATE_FIN >= :value";
            
                // Préparer la requête SQL
                $stmt = $pdo->prepare($sql);
                // Exécuter la requête en passant la valeur correctement (assurez-vous que la clé est 'value' sans les deux-points)
                $stmt->execute(['value' => $value]);
                // Récupérer les résultats
                $result = $stmt->fetchAll();

                var_dump($result);
                echo "<ul>";
                if (empty($result)) {
                    echo "<li>Pas de résultats trouvés</li>";
                } else {
                    foreach ($result as $row) {

                        // Message selon le code
                        if ($row['CODE'] == 1) {
                            $text = "Le service est ajouté";
                        } elseif ($row['CODE'] == 2) {
                            $text = "Le service est supprimé";
                        } else {
                            $text = "Erreur : code inconnu";
                        }
                        

                        // Construction de la liste des jours
                        
                        $jours_actifs = [];
                        if ($row['LUNDI']) $jours_actifs[] = "lundi";
                        if ($row['MARDI']) $jours_actifs[] = "mardi";
                        if ($row['MERCREDI']) $jours_actifs[] = "mercredi";
                        if ($row['JEUDI']) $jours_actifs[] = "jeudi";
                        if ($row['VENDREDI']) $jours_actifs[] = "vendredi";
                        if ($row['SAMEDI']) $jours_actifs[] = "samedi";
                        if ($row['DIMANCHE']) $jours_actifs[] = "dimanche";
                        
                        // Transformation du tableau en phrase
                        $jours_str = implode(", ", $jours_actifs);
                        
                        // Affichage
                        echo "<li>$text pour les {$row['NOM_ITINERAIRE']} pendant les {$row['NOM_SERVICE']} : ($jours_str) (du {$row['DATE_DEBUT']} au {$row['DATE_FIN']})</li>";
                        

                    }
                }
                echo "</ul>";
            }

            //---------------------------------------------------------------------------
            //                              CODE
            //---------------------------------------------------------------------------

            
            if (isset($_GET['nom_agence'])) {
                getDataAgence($pdo, 'nom_agence', 'NOM');
            } elseif (isset($_GET['tel_agence'])){
                getDataAgence($pdo, 'tel_agence', 'TELEPHONE');
            } elseif (isset($_GET['nom_arret'])) {
                getDataHoraire($pdo, 'nom_arret', 'ARRET.NOM');
            } elseif (isset($_GET['nom_itineraire'])) {
                getDataHoraire($pdo, 'nom_itineraire', 'ITINERAIRE.NOM');
            } elseif (isset($_GET['heure_arrive'])){
                getDataHoraireHeure($pdo, 'heure_arrive', 'HEURE_ARRIVEE');
            } elseif (isset($_GET['heure_depart'])){
                getDataHoraireHeure($pdo, 'heure_depart', 'HEURE_DEPART');
            } elseif(isset($_GET['itineraire_exception'])){
                getDataException($pdo, 'itineraire_exception', 'ITINERAIRE.NOM');
            } elseif (isset($_GET['service_exception'])){
                getDataException($pdo, 'service_exception', 'SERVICE.NOM');
            } elseif (isset($_GET['date_exception'])){
                getDataExceptionDate($pdo, 'date_exception');
            }
            else {
                echo "Veuillez entrer un critère de recherche.";
            }
        ?>
    </body>
</html>