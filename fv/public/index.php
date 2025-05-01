<?php
/**
 * HOMEPAGE
 * -------------------------------------------------
 * 1. Header
 * 2. Gets current username from loginstatus
 * 3. Echo a welcome
 * 4. Text and image display
 * 5. Decorative image in the middle
 * 6. Footer
 */

require_once '../template/header.php';    // session_start amd navbar 
require_once '../loginstatus.php';        // for getUsername()

// capital first letter and rest lowercase
$username = ucfirst(strtolower(getUsername()));
?>

<!-- Welcome -->
<div class="container text-center my-5">
    <h1 class="homepage-heading">
        Welcome <?php echo escape($username); ?>!
    </h1>
</div>

<!-- Text and image -->
<div class="container home-section">
  <div class="row align-items-center">

    <!-- LEFT image -->
    <div class="col-md-6">
      <img
        src="../images/fruit_and_veggies.jpg"
        alt="Fresh fruit and vegetables"
        class="img-fluid shadow-sm rounded"
      >
    </div>

    <!-- RIGHT text -->
    <div class="col-md-6 mt-4 mt-md-0">
      <h2 class="section-heading">Your One-Stop Shop for Fresh Produce</h2>
      <p class="section-paragraph">
        Welcome to Fruit &amp; Veggies, your one-stop shop for the freshest produce around.
        Whether you’re looking for crisp apples, juicy tomatoes, or hearty greens, we’ve got
        a wide selection to meet your needs.
      </p>
      <p class="section-paragraph">
        Our aim is to make healthy eating simple, sustainable and affordable for everyone.
        Take a look at our online store, learn about our philosophy, or get inspired with the
        latest recipe ideas. Happy browsing on your journey to a healthier lifestyle!
      </p>
    </div>

  </div>
</div>

<!-- Decorative image -->
<div class="container text-center my-5">
  <img
    src="../images/Fresh-Fruits-And-Vegetables.png"
    alt="Decorative produce"
    class="decorative-image"
  >
</div>

<?php require_once '../template/footer.php'; ?>
