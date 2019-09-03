<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="icon" type="image/ico" href="./images/hat2.png">
	<script src="./$()-.js"></script>
	<script src="./museum.js"></script>
	<title>wurster php databasic+</title>
</head>

<body>
<?php

define('DB_CONNECT', 'sqlite:wurster_museums.db');
define('SOURCE_FILE','./sources/museums-countries.txt');
define('CITY_SOURCE_FILE', './sources/cities.txt');
