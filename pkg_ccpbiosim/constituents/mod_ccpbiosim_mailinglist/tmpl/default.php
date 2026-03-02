<?php
defined('_JEXEC') or die;

?>

<section class="section-padding text-center" style="background-color:#1f3a5f; color:white;">
  <div class="container">
    <h2 class="mb-3"><?php echo $params->get('mailing-join-title', 'default'); ?></h2>
    <p class="mb-4">
      <?php echo $params->get('mailing-join-welcome', 'default'); ?>
    </p>
    <?php 
      $id = $params->get('mailing-join-link');
      $url = JRoute::_("index.php?Itemid={$id}");
    ?>
    <a href="<?php echo $url; ?>" class="btn btn-primary btn-lg"><?php echo $params->get('mailing-join-button', 'default'); ?></a>
  </div>
</section>
