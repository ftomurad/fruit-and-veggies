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

<!-- HTML part ----------------------------------------- -->
<div class="container py-5 my-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">

      <h2 class="text-center mb-4">Register as a new customer</h2>

            <!-- errors -->
      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <?php foreach ($errors as $err): ?>
            <p class="mb-0"><?php echo $err ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- form -->
      <form method="post" action="register.php"
            class="p-4 border rounded shadow-sm bg-light">

        <!-- name -->
        <div class="row mb-3">
          <div class="col">
            <label>First&nbsp;Name</label>
            <input  name="name" type="text" class="form-control" required>
          </div>
          <div class="col">
            <label>Last&nbsp;Name</label>
            <input  name="surname" type="text" class="form-control" required>
          </div>
        </div>

        <!-- email -->
        <div class="mb-3">
          <label>E-mail</label>
          <input name="email" type="email" class="form-control" required>
        </div>

        <!-- username -->
        <div class="mb-3">
          <label>Username</label>
          <input name="username" type="text" class="form-control" required>
        </div>

        <!-- passwords -->
        <div class="row mb-3">
          <div class="col">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="col">
            <label>Confirm&nbsp;Password</label>
            <input name="password2" type="password" class="form-control" required>
          </div>
        </div>

        <!-- phone -->
        <div class="mb-3">
          <label>Phone</label>
          <input name="phone" type="text" class="form-control" required>
        </div>

        <!-- address lines -->
        <div class="mb-3">
          <label>Address&nbsp;Line&nbsp;1</label>
          <input name="address1" type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Address&nbsp;Line&nbsp;2&nbsp;(optional)</label>
          <input name="address2" type="text" class="form-control">
        </div>

        <!-- eircode and role -->
        <div class="row mb-4">
          <div class="col">
            <label>Eircode</label>
            <input name="eircode" type="text" class="form-control" required>
          </div>
          <div class="col">
            <label>Role</label>
            <input class="form-control" value="CUSTOMER" readonly>
          </div>
        </div>

        <div class="text-center">
          <button type="submit" name="Submit" class="btn orange-btn">
              Register&nbsp;Now
          </button>
        </div>
      </form>

      <p class="text-center mt-3">
        Already registered? <a href="login.php">Login here</a>
      </p>
    </div>
  </div>
</div>

<?php require_once '../template/footer.php'; ?>
