<?php

$db = new PDO('sqlite:../wurster_classes.db');
$stmt = $db->prepare("select department_id, name from dept order by department_id asc");
$stmt->execute();

$depts = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($depts);

?>