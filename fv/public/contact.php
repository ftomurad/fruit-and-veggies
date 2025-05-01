<?php
/**
 * Contact
 * -------------------------------
 *   1. Header
 *   2. Show image and text
 *   3. Footer
 */

require_once '../template/header.php';
?>

<!-- Contact -->
<div class="container contact-section my-5">
  <div class="row align-items-center">

    <!-- LEFT image -->
    <div class="col-md-6 text-center mb-4 mb-md-0">
      <img 
        src="../images/african_farmer.jpg" 
        alt="African farmer" 
        class="img-fluid shadow-sm rounded contact-image"
      >
    </div>

    <!-- RIGHT text -->
    <div class="col-md-6">
      <h2 class="section-heading">Contact Us</h2>
      <p class="section-paragraph">Have questions about our products or need assistance with your order?</p>
      <p class="section-paragraph">We’re here to help!</p>
      <p class="section-paragraph">
        You can reach us by phone at <strong>(555) 123-4567</strong>,<br>
        or email at <strong>support@fruitandveggies.ie</strong>
      </p>
      <p class="section-paragraph">
        We welcome any feedback and suggestions, so don’t hesitate to get in touch.
        Your satisfaction is our priority, and we look forward to helping you enjoy
        the freshest produce possible.
      </p>
    </div>

  </div>
</div>

<?php
require_once '../template/footer.php';
?>
