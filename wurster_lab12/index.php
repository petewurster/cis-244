<?php

require_once '.\vendor\autoload.php';

use pw\shop\Item;
use pw\shop\Cart;

$template = new \Twig\Loader\FilesystemLoader('plates');
$env = new \Twig\Environment($template);

$db = new PDO('sqlite:.\cart.db');

$option = $_GET['select'] ?? 'showlist';

switch($option){

	case 'update':
		$cart = Cart::loadCart();
		foreach($_GET as $item => $amt) {
			$stmt = $db->prepare('select * from stock where prodID = :prodID limit 1');
			$stmt->execute([':prodID' => substr($item, 3)]);
			$details = $stmt->fetch(PDO::FETCH_ASSOC);
			if($details) {
				if((int)$amt){
					$prod = new Item($details['prodID'], $details['product'], $details['price']);
					$cart->addRemItem($prod, $amt);
				}
			}
		}
		$cart->saveCart();
		unset($_GET);
		//continue ahead to showcart

	case 'showcart':
		$cart = Cart::loadCart();
		$page = $env->load('viewcart.html');
		echo $page->render(['cart' => $cart]);
		break;

	case 'dump':
		$cart = Cart::loadCart();
		$cart->delCart();
		$cart->saveCart();
		//continue ahead to showlist

	case 'showlist':
		$stmt = $db->prepare('select * from stock');
		$stmt->execute();
		$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$page = $env->load('index.html');
		echo $page->render(['list' => $products]);
		break;

	default:
		$stmt = $db->prepare('select * from stock where prodID = :prodID limit 1');
		$stmt->execute([':prodID' => $_GET['select']]);
		$details = $stmt->fetch(PDO::FETCH_ASSOC);
		if($details) {
			$item = new Item($details['prodID'], $details['product'], $details['price']);
			$page = $env->load('itemdetail.html');
			echo $page->render(['item' => $item]);
		}else{
			header('Location: index.php');
		}

}


?>
