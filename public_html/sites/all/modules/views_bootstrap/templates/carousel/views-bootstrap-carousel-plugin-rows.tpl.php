<?php print $image ?>

<?php if (!empty($title) || !empty($description)): ?>
  <div class="carousel-caption <?php if (!empty($class)): ?><?php print $class ?><?php endif ?>">
    <?php if (!empty($title)): ?>
      <h4><?php print $title ?></h4>
    <?php endif ?>

    <?php if (!empty($description)): ?>
      <p><?php print $description ?></p>
    <?php endif ?>
    <?php if (!empty($buttontext)): ?>
           <?php if (!empty($buttonlink)): ?>
           		<a href="<?php print $buttonlink ?>" class="btn btn-primary btn-sm active" role="button">
           <?php endif ?>
           <?php print $buttontext ?>
           <?php if (!empty($buttonlink)): ?></a><?php endif ?>
    <?php endif ?>
  </div>
<?php endif ?>



  