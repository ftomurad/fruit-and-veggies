<?php
/**
 * REGISTER NEW CUSTOMER
 * -------------------------------------------------
 * 1. Header
 * 2. IF POSTed do sanitise, validate and insert in DB
 * 3. Display registration form and error list
 * 4. Footer
 */

require_once '../template/header.php';
require_once '../src/dbconnect.php'; 
require_once '../common.php';  

// variables
$errors  = [];
$isSaved = false;

// PHP logic part ----------------------------------------------
if (isset($_POST['Submit'])) {

  // 1. create and sanitize associative array
  $new_user = [
      'username'       => escape($_POST['username'] ?? ''),
      'password'       => escape($_POST['password'] ?? ''),
      'role'           => 'CUSTOMER',
      'name'           => escape($_POST['name'] ?? ''),
      'surname'        => escape($_POST['surname'] ?? ''),
      'email'          => escape($_POST['email'] ?? ''),
      'phone'          => escape($_POST['phone'] ?? ''),
      'address_line1'  => escape($_POST['address1'] ?? ''),
      'address_line2'  => escape($_POST['address2'] ?? ''),
      'eircode'        => escape($_POST['eircode'] ?? '')
  ];

  $password2 = escape($_POST['password2'] ?? '');

  // 2. validate required fields
  foreach (['name','surname','email','username','password','phone','address_line1','eircode'] as $field) {
      if ($new_user[$field] === '') {
          $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
      }
  }

  // 3. confirm passwords match
  if ($new_user['password'] !== $password2) {
      $errors[] = 'Passwords don’t match.';
  }

  // 4. check if username already exists
  if (!$errors) {
      $check = ['username' => $new_user['username']];
      $sql = sprintf(
          "SELECT id FROM users WHERE %s = :%s LIMIT 1",
          key($check), key($check)
      );
      $stmt = $connection->prepare($sql);
      $stmt->execute($check);

      if ($stmt->fetch()) {
          $errors[] = 'Username already exists – pick another.';
      }
  }

  // 5. insert into DB
  if (!$errors) {
      try {
          $sql = sprintf(
              "INSERT INTO users (%s) VALUES (%s)",
              implode(", ", array_keys($new_user)),
              ":" . implode(", :", array_keys($new_user))
          );

          $stmt = $connection->prepare($sql);
          $stmt->execute($new_user);

          header("Location: login.php");
          exit;

      } catch (PDOException $e) {
          $errors[] = "Database error: " . $e->getMessage();
      }
  }
} 
?>