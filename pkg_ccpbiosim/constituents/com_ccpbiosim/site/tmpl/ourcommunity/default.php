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

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<p>CCPBioSim stands for the Collaborative Computational Project for Biomolecular Simulation. CCPBioSim is a vibrant UK network of scientists and developers focused on advancing biomolecular simulation through training, collaboration, software support, and community events, with an emphasis on inclusivity and real-world impact.</p>

<div class="container my-5">
  <div class="row g-4">
    <!-- Community Purpose -->
    <div class="community-card mb-5 shadow-sm fade-up">
      <div class="community-card-body">
        <h2 class="community-card-title mb-4"><i class="bi bi-heart-fill me-2"></i>Community Purpose</h2>
        <p>The community brings together:</p>
        <ul>
          <li>Researchers and developers working on biomolecular simulation methods and tools.</li>
          <li>Experimentalists and computational scientists who want to integrate simulation with experimental workflows.</li>
          <li>People at all career stages, from early-career researchers to seasoned experts, to learn, share, and collaborate.</li>
        </ul>
        <p>It’s intended to be inclusive and creative, encouraging participation from anyone interested in biomolecular simulation.</p>
      </div>
    </div>
    <!-- What the Community Does -->
    <div class="community-card mb-5 shadow-sm fade-up">
      <div class="community-card-body">
        <h2 class="community-card-title mb-4"><i class="bi bi-gear-fill me-2"></i>What the Community Does</h2>
        <p>CCPBioSim supports its community through:</p>
        <ul>
          <li>Training workshops and tutorials to teach simulation methods and best practices.</li>
          <li>Annual and specialist conferences, where members share research, techniques, and emerging science.</li>
          <li>Software development support for tools that help researchers run and analyse simulations.</li>
          <li>Monthly seminar series and other events that connect academia, industry and experimentalists.</li>
        </ul>
      </div>
    </div>
    <!-- Community Values -->
    <div class="community-card mb-5 shadow-sm fade-up">
      <div class="community-card-body">
        <h2 class="community-card-title mb-4"><i class="bi bi-stars me-2"></i>Community Values</h2>
        <p>The CCPBioSim community emphasises:</p>
        <ul>
          <li><strong>Accessibility</strong> — lowering barriers so more people can use biomolecular simulation.</li>
          <li><strong>Sharing knowledge and tools</strong> — making methods, data and software more FAIR (Findable, Accessible, Interoperable, Reusable).</li>
          <li><strong>Interdisciplinary engagement</strong> — bridging chemistry, physics, biology and computing.</li>
        </ul>
      </div>
    </div>
    <!-- Links with Wider Initiatives -->
    <div class="community-card mb-5 shadow-sm fade-up">
      <div class="community-card-body">
        <h2 class="community-card-title mb-4"><i class="bi bi-link-45deg me-2"></i>Links with Wider Initiatives</h2>
        <p>CCPBioSim contributes to national data infrastructure efforts, like integrating provenance and reproducibility for simulation data.</p>
        <p>It collaborates with other computational biology communities and projects (e.g., through consortia like DRIIMB).</p>
      </div>
    </div>
  </div>
</div>

