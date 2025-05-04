<?php
/**
 * USER MANAGEMENT (ADMIN ONLY)
 * -------------------------------------------------
 * 1. Header and admin check
 * 2. If POST — sanitize, validate, INSERT/UPDATE/DELETE
 * 3. If GET — get data for edit
 * 4. Get list and sort pages
 * 5. Show form and table below
 */

require_once '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../common.php';

if (getUserRole() !== 'admin') {
  header('Location: index.php');
  exit;
}

$errors = [];
$editUser = null;

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

    // sanitize input
    $user = [
        'username'       => escape($_POST['username'] ?? ''), //SAME AS 'username' => isset($_POST['username']) ? escape($_POST['username']) : ''
        'password'       => escape($_POST['password'] ?? ''),
        'role'           => escape($_POST['role'] ?? 'CUSTOMER'),
        'name'           => escape($_POST['name'] ?? ''),
        'surname'        => escape($_POST['surname'] ?? ''),
        'email'          => escape($_POST['email'] ?? ''),
        'phone'          => escape($_POST['phone'] ?? ''),
        'address_line1'  => escape($_POST['address_line1'] ?? ''),
        'address_line2'  => escape($_POST['address_line2'] ?? ''),
        'eircode'        => escape($_POST['eircode'] ?? '')
    ];

    // required fields check
    $required = ['username','password','name','surname','email','phone','address_line1','eircode'];
      foreach ($required as $field) {
          if ($user[$field] === '') {
          $errors[] = 'All fields are required';
          break; // stop after first missing field
          }
      }

    // check for duplicate username, but only when saving
    if ($action === 'save' && !$errors) {
      $sql = "SELECT id FROM users WHERE username = :username";
      $params = ['username' => $user['username']];

      if ($id !== '') { //skip current user with this ID, ignore current row
          $sql .= " AND id <> :id";
          $params['id'] = $id;
      }
      $stmt = $connection->prepare($sql);
      $stmt->execute($params);
      if ($stmt->fetch()) {
          $errors[] = 'Username already exists.';
      }
    }

    // INSERT / UPDATE / DELETE
    if (!$errors) {
      try {
          // SAVE (Insert or Update)
          if ($action === 'save') {
              if ($id === '') {
                  // INSERT
                  $columns = array_keys($user);
                  $sql = "INSERT INTO users (" . implode(", ", $columns) . ") 
                          VALUES (:" . implode(", :", $columns) . ")";
              } else 
              {
                  // UPDATE
                  $user['id'] = $id;
                  $pairs = [];
  
                  foreach ($user as $key => $value) {
                      if ($key !== 'id') {
                          $pairs[] = "$key = :$key";
                      }
                  }
  
                  $sql = "UPDATE users SET " . implode(", ", $pairs) . " WHERE id = :id";
              }
  
              $stmt = $connection->prepare($sql);
              $stmt->execute($user);
              header("Location: users.php");
              exit;
          }
          // DELETE
          if ($action === 'delete' && $id !== '') {
              $stmt = $connection->prepare("DELETE FROM users WHERE id = :id");
              $stmt->execute(['id' => $id]);
              header("Location: users.php");
              exit;
          }
  
      } catch (PDOException $e) {
          $errors[] = "Database error: " . $e->getMessage();
      }
  }  
}

// When EDIT is pressed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $connection->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    //fetch single row from DB and return as an associative array in this format: 'id' => 1, 'username' => 'john', etc...
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC); 
}

// list and pages
$stmt = $connection->prepare("SELECT * FROM users ORDER BY id ASC LIMIT $offset, $perPage");
$stmt->execute();
$users = $stmt->fetchAll();

$total = $connection->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pages = ceil($total / $perPage);
?>