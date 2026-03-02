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
$canCreate  = $user->authorise('core.create', 'com_ccpbiosim') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'eventform.xml');
$canEdit    = $user->authorise('core.edit', 'com_ccpbiosim') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'eventform.xml');
$canCheckin = $user->authorise('core.manage', 'com_ccpbiosim');
$canChange  = $user->authorise('core.edit.state', 'com_ccpbiosim');
$canDelete  = $user->authorise('core.delete', 'com_ccpbiosim');

// Import CSS & JS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_ccpbiosim.site')
   ->useScript('com_ccpbiosim.site');
$categories = array_unique(array_column($this->items, 'category'));
asort($categories);
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif;?>
<p>Below are upcoming events relevant to our community.</p>
<?php if ($canCreate) : ?>
  <a href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=eventform.edit&id=0', false, 0); ?>"
     class="btn btn-success btn-small"><i
     class="icon-plus"></i>
     <?php echo Text::_('COM_CCPBIOSIM_ADD_ITEM'); ?></a>
<?php endif; ?>
<div class="container my-5">
  <div class="accordion" id="eventAccordion">
    <?php foreach ($categories as $i => $cat) : ?>
      <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion<?php echo $i ?>" aria-expanded="false"><?php echo $cat; ?></button></h2>
        <div id="accordion<?php echo $i ?>" class="accordion-collapse collapse" data-bs-parent="#eventAccordion">
          <div class="accordion-body">
            <?php foreach ($this->items as $j => $item) : ?>
              <?php if ($item->category == $cat && Factory::getDate($item->enddatetime) > Factory::getDate()): ?>
                <div class="row events-row border rounded p-3 mb-3 bg-light" onclick="window.location.href='<?php echo Route::_('index.php?option=com_ccpbiosim&view=event&id='.(int) $item->id); ?>'">
                  <div class="col-md-1 events-date text-center bg-success text-white">
                    <div class="month"><?php echo Factory::getDate($item->startdatetime)->format("M"); ?></div>
                    <div class="day">
                      <?php if (Factory::getDate($item->startdatetime)->__get("day") == Factory::getDate($item->enddatetime)->__get("day")): ?>
                        <?php echo Factory::getDate($item->startdatetime)->__get("day"); ?>
                      <?php else: ?>
                        <?php echo Factory::getDate($item->startdatetime)->__get("day"); ?>-<?php echo Factory::getDate($item->enddatetime)->__get("day"); ?>
                      <?php endif; ?>
                    </div>
                    <div class="year"><?php echo Factory::getDate($item->startdatetime)->__get("year"); ?></div>
                  </div>
                  <div class="col-md-8">
                    <h6 class="mb-1"><?php echo $this->escape($item->title); ?></h6>
                    <small class="text-muted"><?php echo $this->escape($item->location); ?></small>
                    <p class="mb-1"><?php echo $this->escape($item->shortdesc); ?></p>
                  </div>
                  <div class="col-md-3 text-md-end events-actions mt-3 mt-md-0">
                    <?php if ($canChange): ?>
                      <?php $class = ($canChange) ? 'active' : 'disabled'; ?>
                      <a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? Route::_('index.php?option=com_ccpbiosim&task=event.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
                      <?php if ($item->state == 1): ?>
                        <i class="icon-publish"></i>
                      <?php else: ?>
                        <i class="icon-unpublish"></i>
                      <?php endif; ?>
                    <?php endif; ?>
                    </a>
                    <?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_ccpbiosim.' . $item->id) || $item->checked_out == Factory::getApplication()->getIdentity()->id; ?>
                    <?php if($canCheckin && $item->checked_out > 0) : ?>
                      <a href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=event.checkin&id=' . $item->id .'&'. Session::getFormToken() .'=1'); ?>">
                      <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'event.', false); ?></a>
		    <?php endif; ?>
		    <?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_ccpbiosim.' . $item->id) || $item->checked_out == Factory::getApplication()->getIdentity()->id; ?>
		    <?php if($canEdit && $item->checked_out == 0): ?>
	              <a href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=event.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini bg-primary text-white" type="button"><i class="icon-edit" ></i></a>
                    <?php endif; ?>
		    <?php if ($canDelete): ?>
		      <a href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=eventform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini bg-danger text-white delete-button" type="button"><i class="icon-trash" ></i></a>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php
  if($canDelete) {
    $wa->addInlineScript("
      jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
      });
      function deleteItem() {
        if (!confirm(\"" . Text::_('COM_CCPBIOSIM_DELETE_MESSAGE') . "\")) {
          return false;
        }
      }
    ", [], [], ["jquery"]);
  }
?>
