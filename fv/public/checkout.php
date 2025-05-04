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

<!-- HTML part -->
<div class="container py-5">

  <h2 class="text-center mb-4">Summary</h2>

  <!-- table -->
  <table class="table table-bordered text-center align-middle mb-4">
    <thead class="table-dark">
      <tr>
        <th>Product</th>
        <th>Unit Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart as $item): ?>
        <tr>
          <td><?php echo escape($item['name']); ?></td>
          <td><?php echo number_format($item['price'], 2); ?> €</td>
          <td><?php echo $item['quantity']; ?></td>
          <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> €</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- total -->
  <div class="text-end mb-5">
    <h5>Total to Pay: <strong><?php echo number_format($total, 2); ?> €</strong></h5>
  </div>

  <!-- address -->
  <div class="card p-4 shadow-sm mb-5 bg-light">
    <h4>Shipping Details</h4>
    <p>
      <?php echo escape($user['name'] . ' ' . $user['surname']); ?><br>
      <?php echo escape($user['address_line1']); ?><br>
      <?php if ($user['address_line2']) echo escape($user['address_line2']) . '<br>'; ?>
      <?php echo escape($user['eircode']); ?><br>
      <?php echo escape($user['email']); ?><br>
      <?php echo escape($user['phone']); ?>
    </p>
  </div>



</div>

<?php require_once '../template/footer.php'; ?>
