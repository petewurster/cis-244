<?php

$db = new PDO('sqlite:../wurster_classes.db');
$stmt = $db->prepare("select distinct credits from class order by credits asc");
$stmt->execute();

$credits = $stmt->fetchAll(PDO::FETCH_COLUMN);

header('Content-Type: application/json');

echo json_encode($credits);

?>