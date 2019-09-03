<?php

require_once 'head.php';

//populate the database from a flat file source
$db = new PDO(DB_CONNECT);

$qy = 'create table country(cid integer primary key, c_name text unique)';
$stmt = $db->prepare($qy);
$stmt->execute();

$qy = 'create table museum(mid integer primary key, m_name text, c_code int, foreign key (c_code) references country(cid))';
$stmt = $db->prepare($qy);
$stmt->execute();

//extract data from museum-country file to build tables
$lines = explode('+', file_get_contents(SOURCE_FILE));
//builds one grand insert statement for museum table
$fillmuseum = 'insert into museum (m_name, c_code) values ';
foreach($lines as $k => $v) {
	$lines[$k] = trim($v);
	$mcentry = explode('  --  ', $lines[$k]);
	//populate country table
	$fillcountry = 'insert into country (c_name) values ("' . $mcentry[1] . '")';
	$stmt = $db->prepare($fillcountry);
	$stmt->execute();
	//assign country code to museum table based on country table info
	$sel = 'select cid from country where country.c_name="' . $mcentry[1] . '"';
	$stmt = $db->prepare($sel);
	$stmt->execute();
	$cid = $stmt->fetch();
	$fillmuseum .= '("' . $mcentry[0] . '", ' . $cid['cid'] . '),';
}
//populate museum table
$fillmuseum = rtrim($fillmuseum, ',');
$stmt = $db->prepare($fillmuseum);
$stmt->execute();
