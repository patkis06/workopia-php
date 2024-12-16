<?php if (isset($_SESSION['success_message'])) : ?>
  <div class="message bg-green-100 p-4 mb-4">
    <?= $_SESSION['success_message'] ?>
  </div>
  <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])) : ?>
  <div class="message bg-red-100 p-4 mb-4">
    <?= $_SESSION['error_message'] ?>
  </div>
  <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>