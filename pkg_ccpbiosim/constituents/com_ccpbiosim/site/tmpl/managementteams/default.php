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
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\User\UserFactoryInterface;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');

$user       = Factory::getApplication()->getIdentity();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_ccpbiosim') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'managementteamform.xml');
$canEdit    = $user->authorise('core.edit', 'com_ccpbiosim') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'managementteamform.xml');
$canCheckin = $user->authorise('core.manage', 'com_ccpbiosim');
$canChange  = $user->authorise('core.edit.state', 'com_ccpbiosim');
$canDelete  = $user->authorise('core.delete', 'com_ccpbiosim');

// Import CSS & JS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_ccpbiosim.site')
   ->useScript('com_ccpbiosim.site');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif;?>
<p>Our management team is made up of leading academics from UK universities and national facilities.</p>

<div class="container my-5">
  <div class="row g-4">
    <?php foreach ($this->items as $i => $item) : ?>
      <?php $canEdit = $user->authorise('core.edit', 'com_ccpbiosim'); ?>
      <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_ccpbiosim')): ?>
        <?php $canEdit = Factory::getApplication()->getIdentity()->id == $item->created_by; ?>
      <?php endif; ?>
        <div class="col-md-6 col-lg-4 fade-up">
          <div class="card h-100 text-center shadow-sm management-team-card-hover">
            <img src="<?php echo Uri::root() . $item->profilephoto; ?>" alt="Profile Photo"
                 class="rounded-circle mx-auto mt-4"
                 style="width:160px;height:160px;object-fit:cover;">
            <div class="card-body">
              <h5 class="mb-1"><?php echo $item->title; ?> <?php echo $item->firstname; ?> <?php echo $item->surname; ?></h5>
              <p class="text-muted mb-3"><?php echo $item->role; ?></p>
              <p><?php echo $item->insitution; ?></p>
              <div class="management-team-social mt-3">
                <a href="#"><i class="bi bi-linkedin"></i></a>
                <a href="#"><i class="bi bi-envelope"></i></a>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
              </div>
            </div>
          </div>
        </div>
    <?php endforeach; ?>
  </div>
</div>
