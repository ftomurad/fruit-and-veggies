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

require_once '../loginstatus.php';     // session_start()
require_once '../src/dbconnect.php';  // $connection
require_once '../common.php';         // escape()

// redirect if logged
if (isUserLoggedIn()) {
    header('Location: index.php');
    exit;
}

$errors = [];

// process form
if (isset($_POST['Submit'])) {
    // 1. Sanitize inputs
    $formUsername = escape($_POST['Username'] ?? '');
    $formPassword = escape($_POST['Password'] ?? '');

    // 2. Validate
    if ($formUsername === '') {
        $errors[] = 'Please enter your username.';
    }
    if ($formPassword === '') {
        $errors[] = 'Please enter your password.';
    }
    if ($formUsername !== '' && !ctype_alnum($formUsername)) {
        $errors[] = 'Username may contain only letters and numbers.';
    }

    // 3. DB check
    if (!$errors) {
        try {
            $check = ['username' => $formUsername];
            $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $connection->prepare($sql);
            $stmt->execute($check);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 4. Password match
            if ($user && $formPassword === $user['password']) {
                // SESSION
                $_SESSION['Username'] = $user['username'];
                $_SESSION['Role']     = $user['role'];
                $_SESSION['Active']   = true;

                header('Location: index.php');
                exit;
            }

            $errors[] = 'Incorrect username or password.';
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>