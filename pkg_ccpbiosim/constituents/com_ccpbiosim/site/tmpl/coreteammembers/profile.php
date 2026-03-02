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
$canCreate  = $user->authorise('core.create', 'com_ccpbiosim') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'coreteammemberform.xml');
$canEdit    = $user->authorise('core.edit', 'com_ccpbiosim') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'coreteammemberform.xml');
$canCheckin = $user->authorise('core.manage', 'com_ccpbiosim');
$canChange  = $user->authorise('core.edit.state', 'com_ccpbiosim');
$canDelete  = $user->authorise('core.delete', 'com_ccpbiosim');

// Import CSS & JS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_ccpbiosim.site')
   ->useScript('com_ccpbiosim.site');
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif;?>
<p>Our core team is made up of our chair, administrative support from the chairs institution and CoSeC support staff from UKRI - STFC.</br></p>

<div class="container container-md">
  <div class="row g-4">
    <?php foreach ($this->items as $i => $item) : ?>
      <?php $canEdit = $user->authorise('core.edit', 'com_ccpbiosim'); ?>
      <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_ccpbiosim')): ?>
        <?php $canEdit = Factory::getApplication()->getIdentity()->id == $item->created_by; ?>
      <?php endif; ?>
      <div class="col-12 col-md-6 fade-up">
        <div class="core-team-card core-team-horizontal">
          <div class="core-team-image-wrap">
            <img src="<?php echo Uri::root() . $item->profilephoto; ?>" alt="<?php echo $item->title; ?> <?php echo $item->firstname; ?> <?php echo $item->surname; ?>">
          </div>
          <div class="core-team-content">
            <div class="core-team-name"><?php echo $item->title; ?> <?php echo $item->firstname; ?> <?php echo $item->surname; ?></div>
            <div class="core-team-institution"><?php echo $item->insitution; ?></div>
            <p class="core-team-bio"><?php echo $item->role; ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
