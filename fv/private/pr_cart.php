<?php
/**
 * CART
 * ----------------------------------------------
 * 1. Header and login check
 * 2. Show items from session cart
 * 3. Update quantity and remove item
 * 4. Update stock on actions
 */

require_once '../src/dbconnect.php';
require_once '../common.php';

// login check
if (!isUserLoggedIn()) {
  header('Location: login.php');
  exit;
}

// make sure cart exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$errors = [];

// handle POST action
if (isset($_POST['update_qty']) || isset($_POST['remove_item'])) {
  $index = isset($_POST['index']) ? $_POST['index'] : -1;

  if (isset($cart[$index])) {
    $item = &$cart[$index];

    // fetch live stock
    $stmt = $connection->prepare("SELECT items_in_stock FROM products WHERE id = :id");
    $stmt->execute(['id' => $item['id']]);
    $stock = (int)$stmt->fetchColumn();

    // update qty
    if (isset($_POST['update_qty'])) {
      $newQty = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
      if ($newQty < 1) $newQty = 1;
      if ($newQty > $stock) $newQty = $stock;
      $item['quantity'] = $newQty;
    }

    // remove item at $index
if (isset($_POST['remove_item'])) {
    //alternative: array_splice($cart, $index, 1); -- $cart: array, $index: position in array, 1:  how many elements to remove
    unset($cart[$index]);
    $cart = array_values($cart);
  }

  $_SESSION['cart'] = $cart;
  header('Location: cart.php');
  exit;
}
}
// calc total
$total = 0;
foreach ($cart as $c) {
  $total += $c['price'] * $c['quantity'];
}
?>
