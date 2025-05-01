<?php
/**
 * About Us
 * --------------------------
 *   1. Header
 *   2. Show image and text
 *   3. Footer
 */

require_once '../template/header.php';
?>

<!-- Contact -->
<div class="container about-section my-5">
  <div class="row align-items-center">

    <!-- LEFT image -->
    <div class="col-md-6 text-center mb-4 mb-md-0">
      <img 
        src="../images/basket_of_veggies.jpg" 
        alt="About Us" 
        class="img-fluid shadow-sm rounded contact-image"
      >
    </div>

    <!-- RIGHT text -->
    <div class="col-md-6">
      <h2>About Us</h2>
      <p>
        At Fruit &amp; Veggies, we believe that fresh produce is more than just food—
        it’s the cornerstone of a healthy life. Our story began with a small family farm 
        dedicated to quality and sustainability, and it has grown into a community-wide effort 
        to deliver the very best.
      </p>
      <p>
        We carefully source our fruits and vegetables from trusted farmers who share our 
        commitment to ethical, eco-friendly practices. From seed to store, each step of 
        our process focuses on flavor, nutrition, and respect for the environment.
      </p>
    </div>

  </div>
</div>

<?php
require_once '../template/footer.php';
?>
