<?php
namespace pw\trades;
require_once '.\vendor\autoload.php';
$user = User::validateSession();


if($user->getUsername() === 'admin') {
	header('Location: admin.php');
}

if(isset($_POST['add']) && isset($_POST['xCSRF']) && $_POST['xCSRF'] === $_SESSION['xCSRF']) {
	if($_POST['add'] === 'cancel') {
		unset($_POST['add']);
	}
}

if(isset($_POST['drop']) &&	isset($_POST['xCSRF']) && $_POST['xCSRF'] === $_SESSION['xCSRF']) {
	$db = User::connect();
	$stmt = $db->prepare("delete from book where bid = :bid");
	$stmt->execute([':bid' => $_POST['drop']]);
}

if(isset($_POST['browse']) && isset($_POST['xCSRF']) && $_POST['xCSRF'] === $_SESSION['xCSRF']) {
	$_SESSION['newbook'] = [NULL, $user->getUid(), $_POST["new"][0], $_POST["new"][1], $_POST["new"][2]];
	header('Location: browse.php');
}


$template = new \Twig\Loader\FilesystemLoader('plates');
$env = new \Twig\Environment($template);

$page = $env->load('head.html');
echo $page->render();

$page = $env->load('home.html');
$books = Book::getBooksByUser($user->getUid());
echo $page->render(['user' => $user, 'books' => $books, 'post' => $_POST, 'session' => $_SESSION]);

$page = $env->load('tail.html');
echo $page->render();

