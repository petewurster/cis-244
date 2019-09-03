<?php

require_once 'head.php';
require_once 'Item.php';
require_once 'Cart.php';

use wurster\shopping\Item;
use wurster\shopping\Cart;

session_start();
$cart = $_SESSION['myCart'] ?? null;

// checks for & performs cart reset
if(isset($_GET['restock']) && $_GET['restock'] == true) {
	unset($cart);
}

// check for cart and process update
if(isset($cart)) {
	if(isset($_GET['empty']) && $_GET['empty'] == true) {
		$cart->delCart();
	}
	// loop over cart contents and update quantities
	foreach($cart->contents as $k => $v) {
		// loop over GET fields to check for updated values
		foreach($_GET as $item => $amt) {
			if($item === 'item' . $v->getProdId()) {
				if((int)$amt){
					$new = new Item($amt, $v->getProdId(), $v->getProduct(), $v->getPrice());
					$cart->addRemItem($new);
				}
			}
		}
	}
}else{
	// stock cart with various products
	$cart = new Cart();
	// add some items
	$item = new Item(1, '33kka456d4', 'cool item', 5.99);
	$cart->addRemItem($item);
	$item = new Item(3, '556666567a', 'another item', 2.77);
	$cart->addRemItem($item);
	$item = new Item(2, 'aaaaaaaa34', 'last item', 1.25);
	$cart->addRemItem($item);
	$item = new Item(7, 'smallitem3', 'small items', 1);
	$cart->addRemItem($item);
	// demonstrates removing an item by using negative value
	$item = new Item(-1, '556666567a', 'another item', 2.77);
	$cart->addRemItem($item);
}
	// place cart object into session
	$_SESSION['myCart'] = $cart;

?>

<form action="index.php" method="GET">
<!-- loops to populate cart fields -->
	<?php foreach($cart->contents as $inCart): ;?>
		<label><?php
			$data = 'item' . $inCart->getProdId();
			$value = ($inCart->getQty() . ', ' . $inCart->getProdId() . ', ' . $inCart->getProduct() . ', ' . $inCart->getPrice() ); ?>
			<input type="number" name='<?php echo $data; ?>'><?php echo $inCart->getQty() . "\t" . $inCart->getProduct() ;?> @ <?php echo number_format($inCart->getPrice(), 2); ?> each
		</label><br>
	<?php endforeach; ?>
		<p><?php echo $cart->totalItems(); ?> Items in Cart</p>
	<!-- display total if there are items in the cart -->
	<?php if($cart->totalItems() > 0):; ?>
		<p>Total cost: <?php echo number_format($cart->totalPrice(), 2); ?></p>
	<?php endif; ?>
	<label><input type="checkbox" name="empty" value="true">check to empty cart</label><br>
	<label><input type="checkbox" name="restock" value="true">check to restock for demo</label><br>
	<input type="submit" name="submit" value="UPDATE" style="width:100px;">

</form>

</body>
</html>


