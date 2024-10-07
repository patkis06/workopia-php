<?php if (isset($errors) && count($errors) > 0) : ?>
  <div class="message bg-red-100 p-3 my-3">
    <?php foreach ($errors as $error) : ?>
      <p><?= $error ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>