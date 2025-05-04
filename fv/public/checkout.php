<?php
require_once '../template/header.php';  // session_start
require_once '../private/pr_checkout.php';  // php part
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
