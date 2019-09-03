<?php
namespace pw\trades;
require_once '.\vendor\autoload.php';
$user = User::validateSession();


$tables = [];

if(isset($_POST['process']) && isset($_POST['xCSRF']) && $_POST['xCSRF'] === $_SESSION['xCSRF']) {
	$tables = Book::buildMatchTables();
}

if(isset($_POST['demo']) &&	isset($_POST['xCSRF']) && $_POST['xCSRF'] === $_SESSION['xCSRF']) {
	Book::makeXbooks();
}


$template = new \Twig\Loader\FilesystemLoader('plates');
$env = new \Twig\Environment($template);

$page = $env->load('head.html');
echo $page->render();

$page = $env->load('admin.html');
echo $page->render(['user' => $user, 'post' => $_POST, 'tables' => $tables, 'session' => $_SESSION]);

$page = $env->load('tail.html');
echo $page->render();
