<?php

$dept = $_GET['department'] ?? '';
$cred = $_GET['credits'] ?? '';

$db = new PDO('sqlite:../wurster_classes.db');

//build out query conditionally
$execArr = [];
$qy = "select class_id, class.name as name, dept.name as department, credits
	from class, dept
	where dept.department_id = class.department";
if($dept !== '') {
	$qy .= " and dept.department_id = :dept";
	$execArr[':dept'] = $dept; 
}
if($cred !== '') {
	$qy .= " and credits = :cred";
	$execArr[':cred'] = $cred; 
}
$qy .= " order by class_id asc";
$stmt = $db->prepare($qy);
$stmt->execute($execArr);

$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($classes);

?>