<?php
/**
 * PRODUCT MANAGEMENT  (ADMIN and USER)
 * -------------------------------------------------
 * 1. Header and admin/user check
 * 2. If POST — sanitize, validate, INSERT/UPDATE/DELETE
 * 3. If GET — get data for edit
 * 4. Get list and sort pages
 * 5. Show form and table below
 */

require_once '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../common.php';

$role = getUserRole();
if (!in_array($role, ['admin', 'user'], true)) {
    header('Location: index.php');
    exit;
}

$errors      = [];
$editProduct = null;

// Pages
$perPage = 10; // rows per page
// figure out what page we are on
if (isset($_GET['page'])) {
    $page = (int) $_GET['page'];         // converts to int
} else {
    $page = 1;                           // default page
}
$offset = ($page - 1) * $perPage;

// Handle POST
if (isset($_POST['action'])) {

    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id     = isset($_POST['id'])     ? $_POST['id']     : '';

    // sanitise input
    $product = [
        'product_name'   => escape(isset($_POST['product_name'])   ? $_POST['product_name']   : ''),
        'product_price'  => escape(isset($_POST['product_price'])  ? $_POST['product_price']  : ''),
        'items_in_stock' => escape(isset($_POST['items_in_stock']) ? $_POST['items_in_stock'] : ''),
        'product_type'   => escape(isset($_POST['product_type'])   ? $_POST['product_type']   : 'FRUIT'),
        'unit_type'      => escape(isset($_POST['unit_type'])      ? $_POST['unit_type']      : ''),
        'image_filename' => escape(isset($_POST['image_filename']) ? $_POST['image_filename'] : ''),
        'description'    => escape(isset($_POST['description'])    ? $_POST['description']    : '')
    ];

    // Required field check
    $required = ['product_name','product_price','items_in_stock','product_type'];
    foreach ($required as $field) {
        if ($product[$field] === '') {
            $errors[] = 'All required fields must be filled.';
            break; // stop after first missing field
        }
    }

    // Check for duplicate username, but only when saving
    if ($action === 'save' && !$errors) {
        $sql    = 'SELECT id FROM products WHERE product_name = :product_name';
        $params = ['product_name' => $product['product_name']];

        if ($id !== '') {   // ignore current row
            $sql .= ' AND id <> :id';
            $params['id'] = $id;
        }

        $stmt = $connection->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'A product with that name already exists.';
        }
    }

    // INSERT / UPDATE / DELETE
    if (!$errors) {
        try {
            // SAVE (Insert or Update)
            if ($action === 'save') {
                if ($id === '') {
                    // INSERT
                    $cols = array_keys($product);
                    $sql  = 'INSERT INTO products (' . implode(', ', $cols) . ')
                             VALUES (:' . implode(', :', $cols) . ')';
                } else {
                    // UPDATE
                    $product['id'] = $id;
                    $pairs = [];
                    foreach ($product as $k => $v) {
                        if ($k !== 'id') {
                            $pairs[] = "$k = :$k";
                        }
                    }
                    $sql = 'UPDATE products SET ' . implode(', ', $pairs) . ' WHERE id = :id';
                }

                $stmt = $connection->prepare($sql);
                $stmt->execute($product);
                header('Location: products.php');
                exit;
            }

            if ($action === 'delete' && $id !== '') {
                $stmt = $connection->prepare('DELETE FROM products WHERE id = :id');
                $stmt->execute(['id' => $id]);
                header('Location: products.php');
                exit;
            }

        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// When EDIT is pressed
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $stmt = $connection->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $_GET['id']]);
    //fetch single row from DB and return as an associative array
    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}

// list and pages
$stmt = $connection->prepare("SELECT * FROM products ORDER BY id ASC LIMIT $offset, $perPage");
$stmt->execute();
$products = $stmt->fetchAll();

$total = $connection->query('SELECT COUNT(*) FROM products')->fetchColumn();
$pages = (int)ceil($total / $perPage);
?>