<?php
session_start();
include '../db_connection.php';
include '../functions/page6_functions.php';

$db = $pdo;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_itineraire'])) {
    if (deleteItineraire($db, $_POST['itineraire_id'])) {
        $_SESSION['message'] = "<p style='color:green;'>Itinéraire supprimé.</p>";
    } else {
        $_SESSION['message'] = "<p style='color:red;'>Erreur lors de la suppression.</p>";
    }
}

$itineraires = getAllItineraires($db);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save_trajet'])) {
    if (empty($_POST['itineraire_id_add']) || empty($_POST['direction'])) {
        $_SESSION['message'] = "<p style='color:red;'>Veuillez remplir tous les champs.</p>";
    } else {
        $itineraire_id = $_POST['itineraire_id_add'];
        $direction = $_POST['direction'];
        $trajet_id = generateUniqueTrajetId($db);
        $service_id = 1;
        $stops = getArrets($db, $itineraire_id);

        if (!insertTrajet($db, $trajet_id, $service_id, $itineraire_id, $direction)) {
            $_SESSION['message'] = "<p style='color:red;'>Erreur lors de l'insertion du trajet.</p>";
        } else {
            $success_all = true;
            foreach ($stops as $index => $stop) {
                $arret_id = $stop['ID'];
                $arrivee_time = $_POST['arrivee'][$arret_id] ?? null;
                $depart_time = $_POST['depart'][$arret_id] ?? null;

                if ($index === 0 && !empty($depart_time)) {
                    $success = insertHoraire($db, $trajet_id, $itineraire_id, $arret_id, null, $depart_time);
                } elseif ($index === count($stops) - 1 && !empty($arrivee_time)) {
                    $success = insertHoraire($db, $trajet_id, $itineraire_id, $arret_id, $arrivee_time, null);
                } elseif (!empty($arrivee_time) && !empty($depart_time)) {
                    $success = insertHoraire($db, $trajet_id, $itineraire_id, $arret_id, $arrivee_time, $depart_time);
                } else {
                    $success = false;
                }

                if (!$success) {
                    $success_all = false;
                }
            }

            $_SESSION['message'] = $success_all
                ? "<p style='color:green;'>Trajet et horaires insérés avec succès !</p>"
                : "<p style='color:orange;'>Trajet inséré, mais certaines horaires n'ont pas été enregistrées correctement.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page6</title>
    <link rel="stylesheet" type="text/css" href="../css/pages_style.css">
</head>
<body>
    <h1>BD Projet - Question 6</h1>
    <a href="../index.html"><div class="subbox">Revenir à l'accueil</div></a>

    <?php if (isset($_SESSION['message'])): ?>
        <div id="message"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

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

    <h2>Ajouter un trajet</h2>
    <form method="post">
        <label>Itinéraire :</label>
        <select name="itineraire_id_add" onchange="this.form.submit()" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($itineraires as $i): ?>
                <option value="<?= $i['ID'] ?>" <?= isset($_POST['itineraire_id_add']) && $_POST['itineraire_id_add'] == $i['ID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($i['NOM']) ?> : ID <?= $i['ID'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Direction :</label>
        <select name="direction" onchange="this.form.submit()" required>
            <option value="">-- Choisir --</option>
            <option value="0" <?= isset($_POST['direction']) && $_POST['direction'] === "0" ? 'selected' : '' ?>>Aller</option>
            <option value="1" <?= isset($_POST['direction']) && $_POST['direction'] === "1" ? 'selected' : '' ?>>Retour</option>
        </select>
        <noscript><button type="submit" name="show_add_trajet">Afficher les arrêts</button></noscript>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" &&
        isset($_POST['itineraire_id_add'], $_POST['direction']) &&
        $_POST['itineraire_id_add'] !== "" && $_POST['direction'] !== "") {
        $itineraire_id = $_POST['itineraire_id_add'];
        $direction = $_POST['direction'];
        $stops = getArrets($db, $itineraire_id);
        if ($stops): ?>
            <h3>Ajouter un trajet pour l'itinéraire <?= htmlspecialchars($itineraire_id) ?> : direction <?= $direction === "0" ? "Aller" : "Retour" ?></h3>
            <form method="post">
                <input type="hidden" name="itineraire_id_add" value="<?= $itineraire_id ?>">
                <input type="hidden" name="direction" value="<?= $direction ?>">
                <?php foreach ($stops as $index => $stop): ?>
                    <p>
                        <strong><?= htmlspecialchars($stop['NOM']) ?></strong><br>
                        <?php if ($index === 0): ?>
                            Départ: <input type="text" name="depart[<?= $stop['ID'] ?>]" placeholder="HH:MM:SS">
                        <?php elseif ($index === count($stops) - 1): ?>
                            Arrivée: <input type="text" name="arrivee[<?= $stop['ID'] ?>]" placeholder="HH:MM:SS">
                        <?php else: ?>
                            Arrivée: <input type="text" name="arrivee[<?= $stop['ID'] ?>]" placeholder="HH:MM:SS">
                            Départ: <input type="text" name="depart[<?= $stop['ID'] ?>]" placeholder="HH:MM:SS">
                        <?php endif; ?>
                    </p>
                <?php endforeach; ?>
                <button type="submit" name="save_trajet">Sauvegarder le trajet</button>
            </form>
        <?php endif;
    }
    ?>
    <script src="../js/pages_script.js"></script>
</body>
</html>
