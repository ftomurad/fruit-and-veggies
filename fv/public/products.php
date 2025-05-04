<?php
require_once '../template/header.php';  // session_start
require_once '../private/pr_products.php';  // php part
?>

<!-- HTML part -->
<div class="container py-4">
    <h2 class="mb-4">Product Management</h2>

    <!-- errors -->
    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <p class="mb-0"><?php echo $err; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- FORM part -->
    <form method="post"
          class="border rounded p-4 bg-light mb-4">

        <!-- hidden ID -->
        <input type="hidden" name="id"
               value="<?php echo isset($editProduct['id']) ? $editProduct['id'] : ''; ?>">

        <div class="mb-2">
            <label>Product Name</label>
            <input  name="product_name"
                    class="form-control"
                    value="<?php echo escape(isset($editProduct['product_name']) ? $editProduct['product_name'] : ''); ?>"
                    minlength="2"
                    required>
        </div>

        <!-- price and stock -->
        <div class="row mb-2">
            <div class="col">
                <label>Price&nbsp;€</label>
                <input  name="product_price"
                        type="number"
                        step="0.01"
                        min="0"
                        class="form-control"
                        value="<?php echo escape(isset($editProduct['product_price']) ? $editProduct['product_price'] : ''); ?>"
                        required>
            </div>
            <div class="col">
                <label>Stock&nbsp;(units)</label>
                <input  name="items_in_stock"
                        type="number"
                        min="0"
                        class="form-control"
                        value="<?php echo escape(isset($editProduct['items_in_stock']) ? $editProduct['items_in_stock'] : ''); ?>"
                        required>
            </div>
        </div>

        <!-- type and unit type -->
        <div class="row mb-2">
            <div class="col">
                <label>Type</label>
                <select name="product_type" class="form-select" required>
                    <?php
                    $types = ['FRUIT', 'VEG'];
                    foreach ($types as $t) {
                        $sel = (isset($editProduct['product_type']) && $editProduct['product_type'] === $t) ? 'selected' : '';
                        echo "<option value=\"$t\" $sel>$t</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- unit type (dropdown) -->
            <div class="col">
                <label>Unit&nbsp;Type</label>
                <select name="unit_type" class="form-select" required>
                    <?php
                    $unitTypes = ['KG', 'PACK', 'UNIT'];
                    foreach ($unitTypes as $u) {
                        $sel = (isset($editProduct['unit_type']) && $editProduct['unit_type'] === $u) ? 'selected' : '';
                        echo "<option value=\"$u\" $sel>$u</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- image filename -->
        <div class="mb-2">
            <label>Image&nbsp;filename</label>
            <input  name="image_filename"
                    class="form-control"
                    type="file"
                    accept=".jpg,.png"
                    title="Only JPG or PNG filenames"
                    value="<?php echo escape(isset($editProduct['image_filename']) ? $editProduct['image_filename'] : ''); ?>">
        </div>

        <!-- description -->
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description"
                      rows="3"
                      class="form-control"><?php echo escape(isset($editProduct['description']) ? $editProduct['description'] : ''); ?></textarea>
        </div>

        <!-- add, delete and save buttons -->
        <div class="d-flex justify-content-between">
            <a href="products.php" class="btn orange-btn">Add&nbsp;New</a>
            <?php if ($editProduct): ?>
                <button type="submit"
                        name="action"
                        value="delete"
                        class="btn orange-btn"
                        onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            <?php endif; ?>
            <button type="submit" name="action" value="save" class="btn orange-btn">Save</button>
        </div>
    </form>

    <!-- table -->
    <table class="table table-bordered text-center table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Price&nbsp;€</th>
                <th>Stock</th>
                <th>Unit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr class="<?php echo (isset($_GET['id']) && $_GET['id'] == $p['id']) ? 'table-success' : ''; ?>">
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo escape($p['product_name']); ?></td>
                    <td><?php echo $p['product_type']; ?></td>
                    <td><?php echo number_format($p['product_price'], 2); ?></td>
                    <td><?php echo $p['items_in_stock']; ?></td>
                    <td><?php echo escape($p['unit_type']); ?></td>
                    <td>
                        <a href="products.php?id=<?php echo $p['id']; ?>" class="btn btn-sm orange-btn">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- page numbers -->
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                <a class="page-link page-link-custom" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</div>

<?php require_once '../template/footer.php'; ?>
