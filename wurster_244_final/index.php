<?php
namespace pw\trades;
require_once '.\vendor\autoload.php';
session_start();


if(User::loginTest()) {
	$user = User::id_login($_SESSION['uid']);
	header('Location: home.php');
}

User::chkCreds();


$template = new \Twig\Loader\FilesystemLoader('plates');
$env = new \Twig\Environment($template);

$page = $env->load('head.html');
echo $page->render();

$page = $env->load('index.html');
echo $page->render(['post' => $_POST]);
