<?php
require_once '../template/header.php';  // session_start
require_once '../private/pr_register.php.php';  // php part
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
