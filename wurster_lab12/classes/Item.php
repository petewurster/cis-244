<?php
namespace pw\shop;

// intended for use with the Cart Class (namespace pw\shop\Cart)
Class Item {
	private $prodId;
	private $product;
	private $price;
	function __construct($prodId, $product, $price) {
		$this->prodId = $prodId;
		$this->product = $product;
		$this->price = $price;
	}
	public function getProdID() {
		return $this->prodId;
	}
	public function getProduct() {
		return $this->product;
	}
	public function getPrice() {
		return $this->price;
	}


}


?>