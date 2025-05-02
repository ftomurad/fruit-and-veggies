<?php
/**
 * REGISTER NEW CUSTOMER
 * -------------------------------------------------
 * 1. Header
 * 2. IF Submitted -- sanitise, do validation, insert row
 * 3. Echo registration form and error list
 * 4. Footer
 */

require_once '../template/header.php';   // header starts session & nav
require_once '../src/dbconnect.php';    // $connection (PDO)
require_once '../common.php';           // escape()

// variables
$errors  = [];
$isSaved = false;

// PHP logic part ----------------------------------------------
if (isset($_POST['Submit'])) {

    // sanitise data
    $name = '';
    if (isset($_POST['name'])) {
        $name = escape($_POST['name']);
    }
    
    $surname = '';
    if (isset($_POST['surname'])) {
        $surname = escape($_POST['surname']);
    }
    
    $email = '';
    if (isset($_POST['email'])) {
        $email = escape($_POST['email']);
    }
    
    $username = '';
    if (isset($_POST['username'])) {
        $username = escape($_POST['username']);
    }
    
    $password = '';
    if (isset($_POST['password'])) {
        $password = escape($_POST['password']);
    }
    
    $password2 = '';
    if (isset($_POST['password2'])) {
        $password2 = escape($_POST['password2']);
    }
    
    $phone = '';
    if (isset($_POST['phone'])) {
        $phone = escape($_POST['phone']);
    }
    
    $addr1 = '';
    if (isset($_POST['address1'])) {
        $addr1 = escape($_POST['address1']);
    }
    
    $addr2 = '';
    if (isset($_POST['address2'])) {
        $addr2 = escape($_POST['address2']);
    }
    
    $eircode = '';
    if (isset($_POST['eircode'])) {
        $eircode = escape($_POST['eircode']);
    }
    


    // validate that fields are not empty
    if ($name === '') {
      $errors[] = 'Please enter your first name.';
    }
    if ($surname === '') {
      $errors[] = 'Please enter your last name.';
    }
    if ($email === '') {
      $errors[] = 'Please enter your email.';
    }
    if ($username === '') {
      $errors[] = 'Please enter a username.';
    }
    if ($password === '') {
      $errors[] = 'Please enter a password.';
    }
    if ($password2 === '') {
      $errors[] = 'Please confirm your password.';
    }
    if ($phone === '') {
      $errors[] = 'Please enter your phone number.';
    }
    if ($addr1 === '') {
      $errors[] = 'Please enter your address.';
    }
    if ($eircode === '') {
      $errors[] = 'Please enter your eircode.';
    }
  
    // passwords 1and2 must match
    if ($password !== $password2) {
        $errors[] = 'Passwords dont match.';
    }

    
    // check if duplicate username exist
    if (!$errors) {
  // 1. create associative array
      $check = ['username' => $username];

  // 2. build SQL using key
      $sql = sprintf(
      "SELECT id FROM users WHERE %s = :%s LIMIT 1",
      key($check), key($check) // both 'username'
      );

  // 3. prepare and execute
      $stmt = $connection->prepare($sql);
      $stmt->execute($check);

  // 4. check result
      if ($stmt->fetch()) {
      $errors[] = 'Username already exists – pick another.';
      }
}

    // insert
    if (!$errors) {
      try {
          // 1. create associative array
          $new_user = [
              'username'       => $username,
              'password'       => $password,
              'role'           => 'CUSTOMER',
              'name'           => $name,
              'surname'        => $surname,
              'email'          => $email,
              'phone'          => $phone,
              'address_line1'  => $addr1,
              'address_line2'  => $addr2,
              'eircode'        => $eircode
          ];
  
          // 2. build SQL statement
          $sql = sprintf(
              "INSERT INTO users (%s) VALUES (%s)",
              implode(", ", array_keys($new_user)),
              ":" . implode(", :", array_keys($new_user))
          );
  
          // 3. prepare and execute with array
          $stmt = $connection->prepare($sql);
          $stmt->execute($new_user);
  
          // redirect
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
