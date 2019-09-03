<?php
require_once 'head.php';
require_once 'Card.php';
use wurster\BusinessCard\demo\Card;

//incorporate POST data into html tags while filtering
$userData = [];
foreach($_POST as $k => $v) {
	$userData[$k] = htmlentities($v);
}

//data formatting
$userData['fname'] .= '&nbsp;';
$userData['city'] .= ',&nbsp;';
$userData['state'] .= '&nbsp;';

//set how many cards
$limit = 'J';

//demo products
for($num = 'A'; $num < $limit; $num++) {
	//letters used instead of numbers to create valid HTML classes
	$card = new Card($userData, $num);
	$card->display();
}

?>