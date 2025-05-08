<?php
    include '../db_connection.php';
?>
<!DOCTYPE html>
<html>
    
    <head>
        <title>Page3</title>
        <link rel="stylesheet" type="text/css" href="../css/pages_style.css">
    </head>
    <body>
        <h1>Page 3: Affichage SERRVICE se basant sur les vues</h1>
        <a href="../index.html">
            <div class="subbox">Revenir à l'acceuil</div>
        </a>
        <!-- Insertion de code PHP -->
        <form method="POST">
            <label for="date_debut"> Date de début :</label> <!-- date début -->
                <input type="date" name="date_debut" placeholder="Date de début" required><br>

            <label for="date_debut"> Date de fin :</label> <!-- date fin-->
                <input type="date" name="date_fin" placeholder="Date de fin" required><br>
            <input type="submit" value="Trouver les services">
        </form>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST')
            if (isset($_POST['date_debut']) && isset($_POST['date_fin'])) {
                $date_debut = $_POST['date_debut'];
                $date_fin = $_POST['date_fin'];
                if($date_fin < $date_debut){
                    echo "<div id='message'> La date de fin doit être après la date de début</div>";
                }
                else{
                    $query = "SELECT * FROM services_par_date_final 
                            WHERE date_service BETWEEN :date_debut AND :date_fin";
                
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([
                        ':date_debut' => $date_debut,
                        ':date_fin' => $date_fin
                    ]);
                
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                    foreach ($results as $row) {
                        echo "<p><strong>{$row['date_service']} :</strong> {$row['services_actifs']}</p>";
                    }
                }
            }
            
        ?>
        
    </body>
</html>