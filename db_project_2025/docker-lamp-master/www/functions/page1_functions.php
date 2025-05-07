<?php           
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

            function getDataExceptionDate($pdo, $research, $key){
                $value = $_GET[$research];  
                //var_dump($value);  // Vérification de la valeur reçue (doit afficher une date, exemple: 2025-04-24)
            
                // Préparer la requête SQL avec un paramètre nommé :value
                $sql = "SELECT 
                            MIN(CODE) AS CODE, 
                            SERVICE.NOM AS NOM_SERVICE, 
                            ITINERAIRE.NOM AS NOM_ITINERAIRE, 
                            MIN(DATE_DEBUT) AS DATE_DEBUT, 
                            MIN(DATE_FIN) AS DATE_FIN
                        FROM EXCEPTION
                        JOIN SERVICE ON EXCEPTION.SERVICE_ID = SERVICE.ID
                        JOIN TRAJET ON EXCEPTION.SERVICE_ID = TRAJET.SERVICE_ID
                        JOIN ITINERAIRE ON TRAJET.ITINERAIRE_ID = ITINERAIRE.ID
                        WHERE $key LIKE :value
                        GROUP BY ITINERAIRE.NOM, SERVICE.NOM";
            
                // Préparer la requête SQL
                $stmt = $pdo->prepare($sql);
                // Exécuter la requête en passant la valeur correctement (assurez-vous que la clé est 'value' sans les deux-points)
                $stmt->execute(['value' => $value]);
                // Récupérer les résultats
                $result = $stmt->fetchAll();

                //var_dump($result);
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
                        // Affichage
                        echo "<li>$text pour les {$row['NOM_ITINERAIRE']} pendant les {$row['NOM_SERVICE']} : (du {$row['DATE_DEBUT']} au {$row['DATE_FIN']})</li>";
                        

                    }
                }
                echo "</ul>";
            }
?>