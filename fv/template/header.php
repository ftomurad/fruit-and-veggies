<?php
/**
 * HEADER
 * -------------------------------------------------
 * * Starts session
 * * Creates navbar
 *   – menu icon for small screens
 *   – Font-Awesome cart icon
 *   – active-link highlighting
 */

require_once '../loginstatus.php';  // for session_start() and getting user roles, username, etc..

$userRole   = getUserRole();     // guest / user / admin
$isLoggedIn = isUserLoggedIn();  // true / false
$username   = getUsername();

// figure out current file for <li class="active-link">
// $_SERVER['PHP_SELF'] - returns relative path
// basename() - removes '\' and leaves only filename
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fruit and Veggies</title>

  <!-- Bootstrap CSS and JS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
    <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/stylesheet.css">
</head>
<body>

<!-- WRAPPER -->
<div class="page-wrapper container-lg">

  <!-- LOGO -->
  <div class="logo-area text-center my-3">
    <img src="../images/logo.png"
         alt="Fruit and Veggies logo"
         class="logo-image">
  </div>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-sm custom-navbar shadow-sm">
    <div class="container">

      <!-- MENU icon -->
      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse" data-bs-target="#mainNav"
              aria-controls="mainNav" aria-expanded="false"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">

        <!-- LEFT links -->
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo ($current === 'index.php') ? 'active-link' : ''; ?>"
               href="index.php">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($current === 'shop.php') ? 'active-link' : ''; ?>"
               href="shop.php">Shop</a>
          </li>

          <?php if ($userRole === 'user' || $userRole === 'admin') { ?>
            <li class="nav-item">
              <a class="nav-link <?php echo ($current === 'products.php') ? 'active-link' : ''; ?>"
                 href="products.php">Products</a>
            </li>
          <?php } ?>

          <?php if ($userRole === 'admin') { ?>
            <li class="nav-item">
              <a class="nav-link <?php echo ($current === 'users.php') ? 'active-link' : ''; ?>"
                 href="users.php">Users</a>
            </li>
          <?php } ?>

          <li class="nav-item">
            <a class="nav-link <?php echo ($current === 'about.php') ? 'active-link' : ''; ?>"
               href="about.php">About</a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo ($current === 'contact.php') ? 'active-link' : ''; ?>"
               href="contact.php">Contact</a>
          </li>
        </ul>

        <!-- RIGHT links -->
        <ul class="navbar-nav ms-auto">

          <!-- Cart FA icon -->
          <li class="nav-item">
            <a class="nav-link <?php echo ($current === 'cart.php') ? 'active-link' : ''; ?>"
               href="cart.php">
              <i class="fas fa-shopping-cart"></i>
            </a>
          </li>

          <!-- Login or Logout -->
          <li class="nav-item">
            <?php if ($isLoggedIn) { ?>
              <a class="nav-link" href="logout.php">Logout</a>
            <?php } else { ?>
              <a class="nav-link <?php echo ($current === 'login.php') ? 'active-link' : ''; ?>"
                 href="login.php">Login</a>
            <?php } ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- BANNER -->
  <div class="banner-image">
    <img src="../images/banner.jpg"
         alt="Fresh fruit and vegetables banner"
         class="banner-img">
  </div>

  <!-- CONTENT START -->
  <div class="content">

</body>
</html>
