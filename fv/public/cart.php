<?php
/**
 * CART
 * ----------------------------------------------
 * 1. Header and login check
 * 2. Show items from session cart
 * 3. Update quantity and remove item
 * 4. Update stock on actions
 */

require_once '../template/header.php';
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

<!-- HTML part -->
<div class="container py-5">
  <h2 class="text-center mb-4">Your Shopping Cart</h2>

  <?php if (count($cart) === 0): ?>
    <div class="alert alert-info text-center">
      Your cart is empty. <a href="shop.php">Browse the shop</a> to add items.
    </div>
  <?php else: ?>
    <!-- table -->
    <table class="table table-bordered text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart as $i => $item): ?>
          <tr>
            <td>
              <img src="../images/<?php echo escape($item['image']); ?>"
                   alt="<?php echo escape($item['name']); ?>"
                   width="80" class="mb-2">
              <br>
              <?php echo escape($item['name']); ?>
            </td>
            <td>
              <?php echo number_format($item['price'], 2); ?> €
            </td>
            <td>
              <form method="post" class="d-flex justify-content-center align-items-center gap-2">
                <input type="hidden" name="index" value="<?php echo $i; ?>">
                <input type="number" name="quantity"
                       value="<?php echo $item['quantity']; ?>"
                       min="1" max="99"
                       class="form-control text-center qty-input">
                <button type="submit" name="update_qty" class="btn btn-sm add-button">Update</button>
              </form>
            </td>
            <td>
              <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
            </td>
            <td>
              <form method="post">
                <input type="hidden" name="index" value="<?php echo $i; ?>">
                <button type="submit" name="remove_item" class="btn add-button">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <!-- total and checkout -->
    <div class="text-end">
      <h5>Total: <strong><?php echo number_format($total, 2); ?> €</strong></h5>
      <a href="checkout.php" class="btn add-button mt-2">Proceed to Checkout</a>
    </div>
  <?php endif; ?>
</div>

<?php require_once '../template/footer.php'; ?>
