<?php
require_once '../template/header.php';  // session_start
require_once '../private/pr_login.php';  // php part
?>

<!-- HTML part -->
<div class="container login-section py-5 my-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">

      <h2 class="text-center mb-4">Login</h2>

      <!-- errors -->
      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <?php foreach ($errors as $err): ?>
            <p class="mb-0"><?php echo $err ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- LOGIN FORM -->
      <form action="login.php" method="post"
            class="p-4 border rounded shadow-sm bg-light">

        <div class="mb-3">
          <label for="Username" class="form-label">Username</label>
          <input type="text" name="Username" id="Username"
                 class="form-control" required>
        </div>

        <div class="mb-4">
          <label for="Password" class="form-label">Password</label>
          <input type="password" name="Password" id="Password"
                 class="form-control" required>
        </div>

        <div class="d-grid">
          <button type="submit" name="Submit" value="Login"
                  class="btn orange-btn">Login</button>
        </div>
      </form>

      <p class="text-center mt-3">
        Not a member? <a href="register.php">Register here</a>
      </p>

    </div>
  </div>
</div>

<?php require_once '../template/footer.php'; ?>
