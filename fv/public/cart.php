<?php
require_once '../template/header.php';  // session_start
require_once '../private/pr_cart.php';  // php part
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
