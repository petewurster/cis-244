<?php
namespace pw\shop;
use PDO;

// Cart Class expects to process items from the Item Class (namespace pw\shop\Item)
Class Cart {
	public $contents = [];
	public $qtys = [];
	
	// adds item listing to cart, if quantity is negative, remove item from cart
	function addRemItem($item, $qty) {
		//simply add to empty cart
		if (empty($this->contents)) {
			$this->contents[] = $item;
			$this->qtys[] = $qty;
		}else{
			//scan cart for duplicates
			for($i = 0; $i < count($this->contents); $i++) {
				//if product already in cart, update quantity & quit scan
				if($item->getProdID() === $this->contents[$i]->getProdID()) {
					$this->qtys[$i] += $qty;
					//delete cart entry when no items remain of that type
					if($this->qtys[$i] < 1) {
						array_splice($this->qtys, $i, 1);
						array_splice($this->contents, $i, 1);
					}
					return;
				}
			}		
			$this->contents[] = $item;
			$this->qtys[] = $qty;
		}
	}
	
	// empty cart
	public function delCart() {
		$this->contents = [];
		$this->qtys = [];
	}

	// loop to sum prices
	public function totalPrice() {
		$total = 0;
		for($i = 0; $i < count($this->contents); $i++) {
			$total += $this->contents[$i]->getPrice() * $this->qtys[$i];
		}
		return $total;
	}
	
	public function totalItems() {
		return array_sum($this->qtys);
	}

	//save cart into DB
	public function saveCart() {
		$db = new PDO('sqlite:.\cart.db');
		$stmt = $db->prepare('drop table if exists item');
		$stmt->execute();
		$stmt = $db->prepare('create table item (prodID text, product text, price float, qty integer)');
		$stmt->execute();

		if(!empty($this->contents)) {
			
		$qy = 'insert into item (prodID, product, price, qty) values ';
			for($i = 0; $i < count($this->contents); $i++) {
				$qy .= '("'. $this->contents[$i]->getProdID() . '", "';
				$qy .= $this->contents[$i]->getproduct() . '", "';
				$qy .= $this->contents[$i]->getprice() . '", ';
				$qy .= $this->qtys[$i] . '), ';
			}
			$qy = rtrim($qy, ', ');
			$stmt = $db->prepare($qy);
			$stmt->execute();
		}

	}


	//retrieve cart from DB
	static function loadCart() {
		$db = new PDO('sqlite:.\cart.db');
		$stmt = $db->prepare('select * from item');
		$stmt->execute();

		$itemrows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$cart = new Cart();

		foreach($itemrows as $item) {
			$x = new Item($item['prodID'], $item['product'], $item['price']);
			$cart->addRemItem($x, $item['qty']);
		}
		return $cart;

	}


}

