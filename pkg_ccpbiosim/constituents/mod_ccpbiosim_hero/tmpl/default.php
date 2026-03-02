<?php
defined('_JEXEC') or die;

?>

  <style>
    :root {
      --primary: #1f3a5f;
      --accent: #2bb0e6;
    }

    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    /* HERO VIDEO */
    .hero {
      position: relative;
      min-height: 90vh;
      color: white;
      overflow: hidden;
    }

    .hero video {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
    }

    .hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(
        rgba(15, 30, 60, 0.75),
        rgba(15, 30, 60, 0.85)
      );
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .btn-primary {
      background-color: var(--accent);
      border: none;
    }

    .btn-primary:hover {
      background-color: #1998c8;
    }

    .section-padding {
      padding: 4rem 0;
    }

    .bg-light-alt {
      background-color: #f5f7fa;
    }

    footer {
      background-color: #0f172a;
      color: #cbd5f5;
    }

    footer a {
      color: #93c5fd;
      text-decoration: none;
    }
  </style>

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
        </div>
      </div>
    </div>
  </div>
</header>
