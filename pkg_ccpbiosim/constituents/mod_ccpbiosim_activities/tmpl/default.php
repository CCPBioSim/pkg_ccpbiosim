<?php
defined('_JEXEC') or die;

?>

<section class="section-padding">
  <div class="container">
    <h2 class="text-center mb-5">What We Do</h2>
    <div class="row g-4">

      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body d-flex flex-column align-items-center text-center">
            <div class="mb-3">
              <span style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;flex-shrink:0;border-radius:12px;background-color:rgba(13,110,253,0.12);">
                <i class="fa-solid fa-code fa-xl text-primary" style="font-size:1.5rem;line-height:1;"></i>
              </span>
            </div>
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock1', 'Software Development'); ?></h5>
            <p class="card-text flex-grow-1">
              <?php echo $params->get('actvties-descblock1', 'Supporting and coordinating open-source biomolecular simulation tools.'); ?>
            </p>
            <?php if (!empty($block1Url)) : ?>
            <div class="mt-3">
              <a href="<?php echo htmlspecialchars($block1Url); ?>" class="btn btn-primary"><?php echo $params->get('actvties-btnblock1', 'Learn More'); ?></a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body d-flex flex-column align-items-center text-center">
            <div class="mb-3">
              <span style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;flex-shrink:0;border-radius:12px;background-color:rgba(25,135,84,0.12);">
                <i class="fa-solid fa-graduation-cap fa-xl text-success" style="font-size:1.5rem;line-height:1;"></i>
              </span>
            </div>
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock2', 'Training & Skills'); ?></h5>
            <p class="card-text flex-grow-1">
              <?php echo $params->get('actvties-descblock2', 'Workshops, summer schools, and online training at all career stages.'); ?>
            </p>
            <?php if (!empty($block2Url)) : ?>
            <div class="mt-3">
              <a href="<?php echo htmlspecialchars($block2Url); ?>" class="btn btn-primary"><?php echo $params->get('actvties-btnblock2', 'Learn More'); ?></a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body d-flex flex-column align-items-center text-center">
            <div class="mb-3">
              <span style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;flex-shrink:0;border-radius:12px;background-color:rgba(124,58,237,0.12);">
                <i class="fa-solid fa-users fa-xl" style="font-size:1.5rem;line-height:1;color:#7c3aed;"></i>
              </span>
            </div>
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock3', 'Community'); ?></h5>
            <p class="card-text flex-grow-1">
              <?php echo $params->get('actvties-descblock3', 'Connecting researchers across academia and industry.'); ?>
            </p>
            <?php if (!empty($block3Url)) : ?>
            <div class="mt-3">
              <a href="<?php echo htmlspecialchars($block3Url); ?>" class="btn btn-primary"><?php echo $params->get('actvties-btnblock3', 'Learn More'); ?></a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body d-flex flex-column align-items-center text-center">
            <div class="mb-3">
              <span style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;flex-shrink:0;border-radius:12px;background-color:rgba(255,193,7,0.15);">
                <i class="fa-solid fa-award fa-xl text-warning" style="font-size:1.5rem;line-height:1;"></i>
              </span>
            </div>
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock4', 'Best Practice'); ?></h5>
            <p class="card-text flex-grow-1">
              <?php echo $params->get('actvties-descblock4', 'Promoting reproducibility and methodological rigor.'); ?>
            </p>
            <?php if (!empty($block4Url)) : ?>
            <div class="mt-3">
              <a href="<?php echo htmlspecialchars($block4Url); ?>" class="btn btn-primary"><?php echo $params->get('actvties-btnblock4', 'Learn More'); ?></a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
