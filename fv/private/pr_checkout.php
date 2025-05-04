<?php
/**
 * CHECKOUT
 * -------------------------------------------------------
 * 1. Header and login check
 * 2. Load cart session and total
 * 3. Fetch user data from DB
 */

require_once '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../common.php';

if (!isUserLoggedIn()) {
  header('Location: login.php');
  exit;
}

// load cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// total cost
$total = 0;
for ($i = 0; $i < count($cart); $i++) {
  $total += $cart[$i]['price'] * $cart[$i]['quantity'];
}

// fetch user data from DB
$username = getUsername();
$stmt = $connection->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// no checkout if user not found or cart is empty
if (!$user || count($cart) === 0) {
  echo '<div class="container py-5">';
  echo '<div class="alert alert-warning text-center">';
  echo 'Checkout not available. Please ensure you are logged in and your cart is not empty.';
  echo '</div></div>';
  require_once '../template/footer.php';
  exit;
}
?>