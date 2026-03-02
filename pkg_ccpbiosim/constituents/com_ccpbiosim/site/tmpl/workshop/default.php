<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

// Import CSS & JS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_ccpbiosim.site')
   ->useScript('com_ccpbiosim.site');
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif; ?>
<p>We provide access to training courses that have been previously run as instructor led workshops. These courses are made available for everyone to learn about topics from the simple to the more advanced. We have categorised these below, please report any problems using our <a href="/contact">contact</a> form.</p>
<?php
$json = "https://ccpbiosim.github.io/workshop.json";
$data = json_decode(file_get_contents($json), true);
$data_sorted = array();
foreach ($data["containers"] as $course => $coursedata) {
  $data_sorted[$coursedata["category"]][$course] = $coursedata;
}
$mappings = array("setup" => "Courses for Simulation Setup",
                  "simulation" => "Courses for Running Simulations",
                  "analysis" => "Courses for Analysing Simulations",
                  "coding" => "Courses for Programming",
                  "advanced" => "Courses on Advanced Topics");
?>
<div class="container my-5">
  <div class="accordion" id="courseAccordion">
    <?php foreach ($data_sorted as $category => $categorydata) : ?>
      <?php if ($category != "infrastructure") : ?>
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $category; ?>" aria-expanded="false"><?php echo $mappings[$category]; ?></button></h2>
          <div id="<?php echo $category; ?>" class="accordion-collapse collapse" data-bs-parent="#courseAccordion">
            <div class="accordion-body">
              <div class="row g-4">
                <?php foreach ($categorydata as $course => $coursedata) : ?>
                  <div class="col-12 col-md-6">
                    <div class="workshopcard workshopcard-horizontal h-100" 
                      data-bs-toggle="modal" data-bs-target="#courseModal" 
                      data-title="<?php echo $coursedata["name"]; ?>"
                      data-summary="<?php echo $coursedata["shortdesc"]; ?>"
                      data-description="<?php echo $coursedata["longdesc"]; ?>"
                      data-category="<?php echo $category; ?>"
                      data-image="https://via.placeholder.com/400x300?text=<?php echo $course; ?>"
                      data-link="https://ccpbiosim.ac.uk/notebooks/hub/spawn?profile=<?php echo $course; ?>"
                      docker-pull="docker pull ghcr.io/ccpbiosim/<?php echo $course; ?>:latest"
                      docker-run="docker run -p 8888:8888 ghcr.io/ccpbiosim/<?php echo $course; ?>:latest"
                      badge-repo="https://img.shields.io/badge/github-source%20code-blue?style=for-the-badge&logo=github&link=https%3A%2F%2Fgithub.com%2Fccpbiosim%2F<?php echo $course; ?>"
                      badge-build="https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fccpbiosim.github.io%2Fworkshop.json&query=%24.containers.<?php echo $course; ?>.latest&style=for-the-badge&logo=github&label=Latest%20Build"
                      badge-repo-link="https://github.com/CCPBioSim/<?php echo $course; ?>"
                      badge-build-link="https://github.com/CCPBioSim/<?php echo $course; ?>/pkgs/container/<?php echo $course; ?>">
                    <img src="https://via.placeholder.com/120" alt="<?php echo $course; ?>">
                    <div class="workshopcard-body">
                      <h5 class="workshopcard-title"><?php echo $coursedata["name"]; ?></h5>
                      <p class="workshopcard-summary"><?php echo $coursedata["shortdesc"]; ?></p>
                    </div>
                    </div>
                  </div>
                <? endforeach ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif ?>
    <? endforeach ?>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="courseModalLabel">Course Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col-2">
              <img src="" id="courseModalImage" class="img-fluid mb-3" alt="Course Image">
            </div>
            <div class="col">
              <a id="courseModalBadgeBuildLink" target="_blank" href="#"><img class="workshopbadge-build" id="courseModalBadgeBuild" alt="Dynamic JSON Badge" src=""></a>
              <a id="courseModalBadgeRepoLink" target="_blank" href="#"><img class="workshopbadge-repo" id="courseModalBadgeRepo" alt="Static Badge" src=""></a>
              <p id="courseModalSummary" class="fw-bold modalworkshop-summarytext"></p>
            </div>
          </div>
        </div>
        <p id="courseModalDescription"></p>
        <p><strong>Want to run this on your own machine?</strong></p>
        <p>You will need to have a working install of <a target="_blank" href="https://docs.docker.com/engine/install/">docker</a> first before running:</p>
        <code id="courseModalDockerPull"></code>
        <p style="padding-top: 10px;">Followed by:</p>
        <code id="courseModalDockerRun"></code>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="#" id="courseModalLink" target="_blank" class="btn btn-primary">Open Course</a>
      </div>
    </div>
  </div>
</div>
<script src="/media/vendor/bootstrap/js/modal.min.js?5.3.8" type="module"></script>
<script>
  const courseModal = document.getElementById('courseModal');
  courseModal.addEventListener('show.bs.modal', event => {
    const card = event.relatedTarget;
    const title = card.getAttribute('data-title');
    const summary = card.getAttribute('data-summary');
    const description = card.getAttribute('data-description');
    const category = card.getAttribute('data-category');
    const image = card.getAttribute('data-image');
    const link = card.getAttribute('data-link');
    const dpull = card.getAttribute('docker-pull');
    const drun = card.getAttribute('docker-run');
    const brepo = card.getAttribute('badge-repo');
    const bbuild = card.getAttribute('badge-build');
    const brepolink = card.getAttribute('badge-repo-link');
    const bbuildlink = card.getAttribute('badge-build-link');

    document.getElementById('courseModalLabel').textContent = title;
    document.getElementById('courseModalSummary').textContent = summary;
    document.getElementById('courseModalDescription').textContent = description;
    document.getElementById('courseModalImage').src = image;
    document.getElementById('courseModalImage').alt = title;
    document.getElementById('courseModalLink').href = link;
    document.getElementById('courseModalDockerPull').textContent = dpull;
    document.getElementById('courseModalDockerRun').textContent = drun;
    document.getElementById('courseModalBadgeRepo').src = brepo;
    document.getElementById('courseModalBadgeBuild').src = bbuild;
    document.getElementById('courseModalBadgeRepoLink').href = brepolink;
    document.getElementById('courseModalBadgeBuildLink').href = bbuildlink;
  });
</script>
