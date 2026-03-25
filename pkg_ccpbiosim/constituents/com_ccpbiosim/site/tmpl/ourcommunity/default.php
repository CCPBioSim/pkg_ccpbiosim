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
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    </div>
<?php endif; ?>
<!-- Bootstrap Icons (remove if already loaded by your template) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<!-- ══ HERO ══════════════════════════════════════════════════════════ -->
<div class="community-hero py-5 mb-5 text-center rounded-3">
  <p class="lead mx-auto" style="max-width:580px;">
    A vibrant UK network of scientists and developers advancing biomolecular
    simulation through training, collaboration, software support, and community
    events - with an emphasis on inclusivity and real-world impact.
  </p>
</div>
<div class="row align-items-start g-4 mb-5">
  <div class="col-md-6">
    <p class="community-section-label">Who we are</p>
    <h2 class="h3 fw-bold mb-3">Collaborative Computational Project for Biomolecular Simulation</h2>
    <p class="text-body-secondary">
      CCPBioSim unites researchers and developers working on biomolecular simulation
      methods and tools, alongside experimentalists and computational scientists who
      want to integrate simulation with experimental workflows, welcoming people at
      every career stage.
    </p>
  </div>
  <div class="col-md-6">
    <div class="community-callout p-4 rounded-3 h-100">
      <i class="bi bi-quote community-callout-icon"></i>
      <p class="mb-0">
        Inclusive and creative, encouraging participation from anyone interested in
        biomolecular simulation - from early-career researchers to seasoned experts.
      </p>
    </div>
  </div>
</div>

<hr class="community-rule my-5">
<div class="mb-5">
  <p class="community-section-label">Community purpose</p>
  <h2 class="h3 fw-bold mb-4">Who the Community Brings Together</h2>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card h-100 border community-card">
        <div class="card-body p-4">
          <div class="community-icon-wrap rounded-circle mb-3">🔬</div>
          <h5 class="card-title fw-bold">Researchers &amp; Developers</h5>
          <p class="card-text text-body-secondary small">
            Working on biomolecular simulation methods, algorithms, and software tools.
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100 border community-card">
        <div class="card-body p-4">
          <div class="community-icon-wrap rounded-circle mb-3">⚗️</div>
          <h5 class="card-title fw-bold">Experimentalists</h5>
          <p class="card-text text-body-secondary small">
            Computational scientists integrating simulation with experimental workflows.
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100 border community-card">
        <div class="card-body p-4">
          <div class="community-icon-wrap rounded-circle mb-3">🌱</div>
          <h5 class="card-title fw-bold">All Career Stages</h5>
          <p class="card-text text-body-secondary small">
            From early-career researchers to seasoned experts - everyone learns,
            shares, and collaborates.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<hr class="community-rule my-5">
<div class="mb-5">
  <p class="community-section-label">What we do</p>
  <h2 class="h3 fw-bold mb-3">How CCPBioSim Supports Its Community</h2>
  <ul class="list-group list-group-flush community-activity-list mt-3">
    <li class="list-group-item d-flex align-items-start gap-3 px-0 py-3">
      <span class="community-num fw-bold flex-shrink-0">01</span>
      <span><strong>Training workshops &amp; tutorials</strong> - teaching simulation
        methods and best practices to researchers at all levels.</span>
    </li>
    <li class="list-group-item d-flex align-items-start gap-3 px-0 py-3">
      <span class="community-num fw-bold flex-shrink-0">02</span>
      <span><strong>Annual &amp; specialist conferences</strong> - where members share
        research, techniques, and emerging science.</span>
    </li>
    <li class="list-group-item d-flex align-items-start gap-3 px-0 py-3">
      <span class="community-num fw-bold flex-shrink-0">03</span>
      <span><strong>Software development support</strong> - for tools that help
        researchers run and analyse simulations.</span>
    </li>
    <li class="list-group-item d-flex align-items-start gap-3 px-0 py-3">
      <span class="community-num fw-bold flex-shrink-0">04</span>
      <span><strong>Monthly seminar series &amp; events</strong> - connecting academia,
        industry, and experimentalists throughout the year.</span>
    </li>
  </ul>
</div>
<hr class="community-rule my-5">
<div class="mb-5">
  <p class="community-section-label">Community values</p>
  <h2 class="h3 fw-bold mb-4">What We Stand For</h2>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="community-value p-3 rounded-2 h-100">
        <h5 class="fw-bold mb-2">Accessibility</h5>
        <p class="text-body-secondary small mb-0">
          Lowering barriers so more people can use biomolecular simulation,
          regardless of background or resources.
        </p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="community-value p-3 rounded-2 h-100">
        <h5 class="fw-bold mb-2">Interdisciplinary Engagement</h5>
        <p class="text-body-secondary small mb-0">
          Bridging chemistry, physics, biology, and computing to foster richer,
          more impactful science.
        </p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="community-value p-3 rounded-2 h-100">
        <h5 class="fw-bold mb-2">Open Knowledge</h5>
        <p class="text-body-secondary small mb-0">
          Making methods, data, and software more <strong>FAIR</strong> -
          Findable, Accessible, Interoperable, and Reusable.
        </p>
      </div>
    </div>
  </div>
</div>
<div class="community-dark p-4 p-md-5 rounded-3">
  <div class="row g-4 align-items-center">
    <div class="col-md-7">
      <p class="community-section-label community-section-label--light">Wider initiatives</p>
      <h2 class="h3 fw-bold text-white mb-3">Links with the Broader Ecosystem</h2>
      <p class="mb-0" style="color:rgba(255,255,255,.65);">
        CCPBioSim contributes to national data infrastructure efforts. Integrating
        provenance and reproducibility for simulation data and actively collaborates
        with computational biology communities and consortia including through DRIIMB.
      </p>
    </div>
    <div class="col-md-5">
      <p class="text-uppercase mb-2" style="color:rgba(255,255,255,.4);font-size:.7rem;letter-spacing:.12em;">
        Key themes &amp; partners
      </p>
      <div>
        <span class="community-tag community-tag--featured">DRIIMB</span>
        <span class="community-tag">HECBioSim</span>
        <span class="community-tag">CCP5</span>
        <span class="community-tag">CCP4</span>
        <span class="community-tag">National Infrastructure</span>
        <span class="community-tag">CCPEM</span>
        <span class="community-tag">CCPN</span>
        <span class="community-tag">PSDI</span>
      </div>
    </div>
  </div>
</div>
