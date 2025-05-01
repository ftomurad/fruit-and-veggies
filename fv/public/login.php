<?php
/**
 * USER LOGIN
 * -------------------------------------------------
 * 1.  Redirect if already loggedin 
 * 2.  Handle form
 *     - sanitze input with escape()
 *     - check both username and passwordf fields
 *     - prepared statement to fetch user using PDO
 *     - password match check
 *     - set $_SESSION keys and redirect
 * 3.  Show the form and any error messages
 */

require_once '../loginstatus.php';   // session_start()
require_once '../src/dbconnect.php'; // connection to DB
require_once '../common.php';        // escape()

// access control 
if (isUserLoggedIn()) {
    header('Location: index.php');
    exit;
}

// errors array 
$errors = [];

// FORM - runs only when form is posted
if (isset($_POST['Submit'])) {

  // set default values
  $formUsername = '';
  $formPassword = '';

  // check if values exist, then sanitize
  if (isset($_POST['Username'])) {
      $formUsername = escape($_POST['Username']);
  }

  if (isset($_POST['Password'])) {
      $formPassword = escape($_POST['Password']);
  }

// check if both fields are filled
if ($formUsername === '') {
    $errors[] = 'Please enter your username.';
}
if ($formPassword === '') {
    $errors[] = 'Please enter your password.';
}

// check if username is alphanumeric (only if not empty)
if ($formUsername !== '') {
    if (!ctype_alnum($formUsername)) {
        $errors[] = 'Username may contain only letters and numbers.';
    }
}

    // talk to DB only if no erros
    if (!$errors) {
        try {   //prepared statement
            $sql  = 'SELECT * FROM users WHERE username = :username LIMIT 1';
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':username', $formUsername);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // compare passwords
            if ($user && $formPassword === $user['password']) {

                // store session keys
                $_SESSION['Username'] = $user['username'];
                $_SESSION['Role']     = $user['role'];
                $_SESSION['Active']   = true;

                header('Location: index.php');
                exit;
            }

            $errors[] = 'Incorrect username or password.';
        }
        catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

/* HTML part ------------------- */
require_once '../template/header.php';
?>

<!-- LOGIN -->
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
