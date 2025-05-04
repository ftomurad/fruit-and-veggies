<?php
/**
 * SHOP
 * -------------------------------------------------------
 * 1. Header and login check
 * 2. Cart session
 * 4. Handle Add to cart button
 * 5. Filter by type
 * 6. Page setup
 * 7. Show table
 */

require_once '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../common.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

// cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ADD button
if (isset($_POST['add'])) {
    $pid = isset($_POST['pid']) ? $_POST['pid'] : 0;
    $qty = isset($_POST['qty']) ? $_POST['qty'] : 1;

    $stmt = $connection->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $pid]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if ($qty > $product['items_in_stock']) {
            $qty = $product['items_in_stock'];
        }

        $found = false;

        for ($i = 0; $i < count($_SESSION['cart']); $i++) {
            if ($_SESSION['cart'][$i]['id'] == $pid) {
                $_SESSION['cart'][$i]['quantity'] += $qty;

                if ($_SESSION['cart'][$i]['quantity'] > $product['items_in_stock']) {
                    $_SESSION['cart'][$i]['quantity'] = $product['items_in_stock'];
                }

                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id'       => $product['id'],
                'name'     => $product['product_name'],
                'price'    => $product['product_price'],
                'unit'     => $product['unit_type'],
                'image'    => $product['image_filename'] !== '' ? $product['image_filename'] : 'default.jpg',
                'quantity' => $qty
            ];
        }

        $redirectType = isset($_GET['type']) ? $_GET['type'] : 'FRUIT';
        $redirectPage = isset($_GET['page']) ? $_GET['page'] : 1;
        header('Location: shop.php?type=' . $redirectType . '&page=' . $redirectPage);
        exit;
    }
}

// Filter button FRUIT / VEG
$validTypes = ['FRUIT', 'VEG'];
$type = isset($_GET['type']) && in_array($_GET['type'], $validTypes)
        ? $_GET['type']
        : 'FRUIT';

// page setup
$perPage = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$offset = ($page - 1) * $perPage;

$stmt = $connection->prepare(
    'SELECT * FROM products 
     WHERE product_type = :type 
     ORDER BY id ASC 
     LIMIT :offset, :limit'
);
$stmt->bindValue(':type', $type, PDO::PARAM_STR);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

// get total count
$countStmt = $connection->prepare('SELECT COUNT(*) FROM products WHERE product_type = :type');
$countStmt->execute(['type' => $type]);
$total = $countStmt->fetchColumn();
$pages = ceil($total / $perPage);
?>

<!-- HTML part -->
<div class="container shop-section py-5">

  <!-- filters -->
  <div class="filter-buttons text-center mb-4">
    <a href="shop.php?type=FRUIT" class="btn <?php echo ($type === 'FRUIT') ? 'active-filter' : 'filter-btn'; ?>">Fruit</a>
    <a href="shop.php?type=VEG" class="btn <?php echo ($type === 'VEG') ? 'active-filter' : 'filter-btn'; ?>">Veggies</a>
  </div>

  <!-- product cards -->
  <div class="row product-grid">
    <?php for ($i = 0; $i < count($products); $i++): ?>
      <?php
        $p = $products[$i];
        $img = $p['image_filename'] !== '' ? $p['image_filename'] : 'default.jpg';
      ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card product-card h-100 shadow-sm">

          <!-- image -->
          <div class="product-image-wrapper">
            <img src="../images/<?php echo escape($img); ?>"
                 alt="<?php echo escape($p['product_name']); ?>"
                 class="product-image">
          </div>

          <!-- info -->
          <div class="card-body d-flex flex-column text-center">

            <!-- name -->
            <h6 class="fw-bold mb-2"><?php echo escape($p['product_name']); ?></h6>

            <!-- price line -->
            <p class="mb-1">
              Price per <?php echo strtoupper(escape($p['unit_type'])); ?>
              <?php echo number_format($p['product_price'], 2); ?> €
            </p>

            <!-- ADD form -->
            <form method="post" class="mt-auto">
              <input type="hidden" name="pid" value="<?php echo $p['id']; ?>">
              <div class="quantity-row mb-2">
                <input type="number"
                       name="qty"
                       min="1"
                       max="<?php echo $p['items_in_stock']; ?>"
                       value="1"
                       class="form-control text-center qty-input">
                <span class="text-muted small available-text">
                  of <?php echo $p['items_in_stock']; ?>
                </span>
              </div>
              <!-- ADD btn -->
              <button type="submit" name="add" class="btn add-button w-100">Add to Cart</button>
            </form>

          </div>
        </div>
      </div>
    <?php endfor; ?>
  </div>

  <!-- Pagination -->
  <?php if ($pages > 1): ?>
    <ul class="pagination justify-content-center mt-4">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
          <a class="page-link page-link-custom"
             href="shop.php?page=<?php echo $i; ?>&type=<?php echo $type; ?>">
             <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>
    </ul>
  <?php endif; ?>

</div>

<?php require_once '../template/footer.php'; ?>
