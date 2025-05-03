<?php
session_start();  

include '../db_connection.php';
include '../functions/page7_functions.php';

$message = "";
$selected = null;
$db = $pdo;


$message = updatePOSTmethod($db);

$arrets = getAllBelgianArrets($db);
$selected = getSelectedArret($db);

if ($message) {
    $_SESSION['message'] = $message;
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page 7</title>
    <link rel="stylesheet" type="text/css" href="../css/page7_style.css">
</head>
<body>
    <h1>BD Projet - Question 7</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div id="message"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="get">
        <label>Sélectionnez un arrêt :</label>
        <select name="id" onchange="this.form.submit()">
            <option value="">CHOISIR ARRET</option>
            <?php foreach ($arrets as $row): ?>
                <option value="<?= $row['ID'] ?>" <?= ($selected && $selected['ID'] == $row['ID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['NOM']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selected): ?>
        <h2>Modifier l'arrêt : <?= htmlspecialchars($selected['NOM']) ?></h2>
        <form method="post">
            <label>ID:</label>
            <input type="number" name="new_id" value="<?= $selected['ID'] ?>" required><br>

            <label>Nom:</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($selected['NOM']) ?>" required><br>

            <label>Latitude:</label>
            <input type="text" name="lat" value="<?= $selected['LATITUDE'] ?>" required><br>

            <label>Longitude:</label>
            <input type="text" name="lon" value="<?= $selected['LONGITUDE'] ?>" required><br>

            <input type="submit" value="Mettre à jour">
        </form>
    <?php endif; ?>

    <script src="../js/pages_script.js"></script>
</body>
</html>
