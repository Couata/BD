<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page6_functions.php';

    $db = $pdo;
    $message = "";

    $itineraires = getALlItineraires(($db));


    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['show_add_trajet'])) {
        $itineraire_id = $_POST['itineraire_id_add'];
        $direction = $_POST['direction'];
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_itineraire'])){
        if (deleteItineraire($db, $_POST['itineraire_id'])){
            $message = "<p style='color:green;'>Itinéraire supprimé.</p>";
        }
        else{
            $message = "<p style='color:red;'>Erreur lors de la suppression.</p>";
        }
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save_trajet'])){

    }


    ?>
    <head>
        <title>Page6</title>
        <link rel="stylesheet" type="text/css" href="../css/page6_style.css">
    </head>
    <body>
        <h1>BD Projet - Question 6</h1>

        <?= $message?>

    <!-- FORMULAIRE 1 -->
    <h2>Supprimer un itinéraire</h2>
    <form method="post">
        <label>Choisir l'itinéraire à supprimer :</label>
        <select name="itineraire_id" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($itineraires as $i): ?>
                <option value="<?= $i['ID'] ?>"><?= htmlspecialchars($i['NOM']) ?> : ID <?= $i['ID'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="delete_itineraire">Supprimer</button>
    </form>

    <!-- FORMULAIRE 2 -->
    <h2>Ajouter un trajet</h2>
    <form method="post">
        <label>Itinéraire :</label>
        <select name="itineraire_id_add" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($itineraires as $i): ?>
                <option value="<?= $i['ID'] ?>"><?= htmlspecialchars($i['NOM']) ?> : ID <?= $i['ID'] ?></option>
            <?php endforeach; ?>
        </select>
        <label>Direction :</label>
        <select name="direction" required>
            <option value="">-- Choisir --</option>
            <option value="0">Aller</option>
            <option value="1">Retour</option>
        </select>

        <button type="submit" name="show_add_trajet">Afficher les arrêts</button>
    </form>
    </body>
</html>