<?php
include '../db_connection.php';
include '../functions/page3_functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page3</title>
    <link rel="stylesheet" type="text/css" href="../css/pages_style.css">
</head>
<body>
    <h1>Page 3: Affichage SERVICE se basant sur les vues</h1>
    <a href="../index.html">
        <div class="subbox">Revenir à l'acceuil</div>
    </a>

    <form method="POST">
        <label for="date_debut"> Date de début :</label>
        <input type="date" name="date_debut" required><br>

        <label for="date_fin"> Date de fin :</label>
        <input type="date" name="date_fin" required><br>

        <input type="submit" value="Trouver les services">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date_debut'], $_POST['date_fin'])) {
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];

        if (validate_date_range($date_debut, $date_fin)) {
            $results = get_services_by_date_range($pdo, $date_debut, $date_fin);
            foreach ($results as $row) {
                echo "<p><strong>{$row['date_service']} :</strong> {$row['services_actifs']}</p>";
            }
        }
    }
    ?>
</body>
</html>
