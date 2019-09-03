<?php
namespace pw\trades;
require_once '.\vendor\autoload.php';
$user = User::validateSession();


$args = $_SESSION['newbook'] ?? [];

if(isset($_POST['select']) && isset($_POST['xCSRF']) && $_POST['xCSRF'] === $_SESSION['xCSRF']) {
	if(!empty($args)) {
		$newbook = new Book($args[0], $args[1], $args[2], $args[3], $args[4], $_POST['select']);
		$newbook->saveBook();
	}
	header('Location: home.php');
}


$template = new \Twig\Loader\FilesystemLoader('plates');
$env = new \Twig\Environment($template);

$page = $env->load('head.html');
echo $page->render();

$page = $env->load('browse.html');
//negative user value flags retrieval function to get everybody else's books instead
$books = Book::getBooksByUser($user->getUid() * -1);
echo $page->render(['user' => $user, 'books' => $books, 'session' => $_SESSION]);

$page = $env->load('tail.html');
echo $page->render();
