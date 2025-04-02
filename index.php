<?php
require "config.php";

// Initialisation des paramètres
$where = [];
$params = [];

if (!empty($_GET['date_vol'])) {
    $where[] = "v.date_vol = :date_vol";
    $params[':date_vol'] = $_GET['date_vol'];
}

if (!empty($_GET['aeroport'])) {
    $where[] = "(v.aeroport_depart = :aeroport OR v.aeroport_arrivee = :aeroport)";
    $params[':aeroport'] = $_GET['aeroport'];
}

if (!empty($_GET['type_avion'])) {
    $where[] = "v.type_avion_id = :type_avion";
    $params[':type_avion'] = $_GET['type_avion'];
}

$sql = "SELECT v.flight_id, v.date_vol, v.heure_depart, v.heure_arrivee, 
               t.modele AS type_avion, c.nom AS compagnie, 
               d.nom AS aeroport_depart, a.nom AS aeroport_arrivee
        FROM Vol v
        JOIN TypeAvion t ON v.type_avion_id = t.type_avion_id
        JOIN Compagnie c ON v.compagnie_id = c.compagnie_id
        JOIN Aeroport d ON v.aeroport_depart = d.code_oaci
        JOIN Aeroport a ON v.aeroport_arrivee = a.code_oaci";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vols = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des vols</title>
</head>
<body>
    <h1>Liste des vols</h1>

    <form method="GET">
        <label>Date :</label>
        <input type="date" name="date_vol" value="<?= $_GET['date_vol'] ?? '' ?>">

        <label>Aéroport :</label>
        <select name="aeroport">
            <option value="">Tous</option>
            <?php
            $stmt = $pdo->query("SELECT code_oaci, nom FROM Aeroport");
            while ($aeroport = $stmt->fetch()) {
                $selected = ($_GET['aeroport'] ?? '') == $aeroport['code_oaci'] ? 'selected' : '';
                echo "<option value='{$aeroport['code_oaci']}' $selected>{$aeroport['nom']}</option>";
            }
            ?>
        </select>

        <label>Type Avion :</label>
        <select name="type_avion">
            <option value="">Tous</option>
            <?php
            $stmt = $pdo->query("SELECT type_avion_id, modele FROM TypeAvion");
            while ($avion = $stmt->fetch()) {
                $selected = ($_GET['type_avion'] ?? '') == $avion['type_avion_id'] ? 'selected' : '';
                echo "<option value='{$avion['type_avion_id']}' $selected>{$avion['modele']}</option>";
            }
            ?>
        </select>

        <input type="submit" value="Filtrer">
    </form>

    <table border="1">
        <tr>
            <th>ID Vol</th>
            <th>Date</th>
            <th>Heure Départ</th>
            <th>Heure Arrivée</th>
            <th>Type Avion</th>
            <th>Compagnie</th>
            <th>Aéroport Départ</th>
            <th>Aéroport Arrivée</th>
        </tr>
        <?php foreach ($vols as $vol) : ?>
        <tr>
            <td><?= htmlspecialchars($vol['flight_id']) ?></td>
            <td><?= htmlspecialchars($vol['date_vol']) ?></td>
            <td><?= htmlspecialchars($vol['heure_depart']) ?></td>
            <td><?= htmlspecialchars($vol['heure_arrivee']) ?></td>
            <td><?= htmlspecialchars($vol['type_avion']) ?></td>
            <td><?= htmlspecialchars($vol['compagnie']) ?></td>
            <td><?= htmlspecialchars($vol['aeroport_depart']) ?></td>
            <td><?= htmlspecialchars($vol['aeroport_arrivee']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
