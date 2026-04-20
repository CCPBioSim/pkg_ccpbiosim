<?php
defined('_JEXEC') or die;
?>

<header class="hero d-flex align-items-center">
  <!-- Replace src with real simulation movie -->
  <video
    autoplay
    muted
    loop
    playsinline
    poster="<?php echo $params->get('hero-image', 'default.jpg'); ?>"
  >
    <source src="<?php echo $params->get('hero-video', 'default.mp4'); ?>" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <div class="container hero-content">
    <div class="row">
      <div class="col-lg-7">
        <h1 class="display-4 fw-bold mb-3"><?php echo $params->get('hero-title', 'title'); ?></h1>
        <p class="lead mb-4"><?php echo $params->get('hero-welcome', 'welcome'); ?></p>
        <div class="d-flex gap-3">
          <?php 
            $id = $params->get('hero-software-link');
            $url = JRoute::_("index.php?Itemid={$id}");
          ?>
          <a href="<?php echo $url; ?>" class="btn btn-primary btn-lg"><?php echo $params->get('hero-software-button', 'default'); ?></a>
          <?php 
            $id = $params->get('hero-events-link');
            $url = JRoute::_("index.php?Itemid={$id}");
          ?>
          <a href="<?php echo $url; ?>" class="btn btn-outline-light btn-lg"><?php echo $params->get('hero-events-button', 'default'); ?></a>
          <?php 
            $id = $params->get('hero-training-link');
            $url = JRoute::_("index.php?Itemid={$id}");
          ?>
          <a href="<?php echo $url; ?>" class="btn btn-outline-light btn-lg"><?php echo $params->get('hero-training-button', 'default'); ?></a>
        </div>
      </div>
    </div>
  </div>
</header>
