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