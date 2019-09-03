<?php
namespace wurster\shopping;

// intended for use with the Cart Class (namespace wurster\shopping)
Class Item {
	private $qty;
	private $prodId;
	private $product;
	private $price;
	function __construct($qty, $prodId, $product, $price) {
		$this->qty = $qty;
		$this->prodId = $prodId;
		$this->product = $product;
		$this->price = $price;
	}
	// Items track their own quantity
	public function getQty() {
		return $this->qty;
	}
	public function setQty($newQty) {
		$this->qty = $newQty;
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