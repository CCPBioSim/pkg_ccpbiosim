<?php
defined('_JEXEC') or die;

?>

<section class="section-padding">
  <div class="container">
    <h2 class="text-center mb-5">What We Do</h2>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body">
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock1', 'Software Development'); ?></h5>
            <p class="card-text">
              <?php echo $params->get('actvties-descblock1', 'Supporting and coordinating open-source biomolecular simulation tools.'); ?>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body">
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock2', 'Training & Skills'); ?></h5>
            <p class="card-text">
              <?php echo $params->get('actvties-descblock2', 'Workshops, summer schools, and online training at all career stages.'); ?>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body">
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock3', 'Community'); ?></h5>
            <p class="card-text">
              <?php echo $params->get('actvties-descblock3', 'Connecting researchers across academia and industry.'); ?>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card h-100 bg-light-alt border-0">
          <div class="card-body">
            <h5 class="card-title"><?php echo $params->get('actvties-ttleblock4', 'Best Practice'); ?></h5>
            <p class="card-text">
              <?php echo $params->get('actvties-descblock4', 'Promoting reproducibility and methodological rigor.'); ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
