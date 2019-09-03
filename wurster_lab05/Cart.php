<?php
namespace wurster\shopping;

// Cart Class expects to process items from the Item Class (namespace wurster\shopping)
Class Cart {
	public $contents = [];
	// adds item listing to cart, if quantity is negative, remove item from cart
	function addRemItem($item) {
		// Items track their own quantity, so the cart prevents duplicate listings with a flag
		$addEntry = true;
		foreach($this->contents as $inCart) {
			if($inCart->getProdId() === $item->getProdId()) {
				$inCart->setQty($inCart->getQty() + $item->getQty());
				$addEntry = false;
			}
			// remove listing as needed
			if($inCart->getQty() <= 0) {
				array_splice($this->contents, array_search($inCart, $this->contents), 1);
			}
		}
		// add new listing
		if($addEntry) {
			array_push($this->contents, $item);
		}
	}
	// reset cart
	public function delCart() {
		$this->contents = [];
	}
	// loop to sum prices
	public function totalPrice() {
		$total = 0;
		foreach($this->contents as $inCart) {
			$total += $inCart->getPrice() * $inCart->getQty();
		}
		return $total;
	}
	// loop to sum items
	public function totalItems() {
		$total = 0;
		foreach($this->contents as $inCart) {
			$total += $inCart->getQty();
		}
		return $total;
	}
}


?>