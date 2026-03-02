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

$canEdit = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_ccpbiosim');

if (!$canEdit && Factory::getApplication()->getIdentity()->authorise('core.edit.own', 'com_ccpbiosim'))
{
	$canEdit = Factory::getApplication()->getIdentity()->id == $this->item->created_by;
}
?>

<div class="item_fields">
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
    <?php endif;?>
	<table class="table">
		

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_CATEGORY'); ?></th>
			<td><?php echo $this->item->category; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_STARTDATETIME'); ?></th>
			<td><?php echo $this->item->startdatetime; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_ENDDATETIME'); ?></th>
			<td><?php echo $this->item->enddatetime; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_EVENTDETAILS'); ?></th>
			<td><?php echo nl2br($this->item->eventdetails); ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_LOCATION'); ?></th>
			<td><?php echo $this->item->location; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_SHORTDESC'); ?></th>
			<td><?php echo $this->item->shortdesc; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_YOUTUBE'); ?></th>
			<td><?php echo $this->item->youtube; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_POSTEVENT'); ?></th>
			<td><?php echo nl2br($this->item->postevent); ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_SHORTURL'); ?></th>
			<td><?php echo $this->item->shorturl; ?></td>
		</tr>

	</table>

</div>

<?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_ccpbiosim.' . $this->item->id) || $this->item->checked_out == Factory::getApplication()->getIdentity()->id; ?>
	<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=event.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_CCPBIOSIM_EDIT_ITEM"); ?></a>
	<?php elseif($canCheckin && $this->item->checked_out > 0) : ?>
	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=event.checkin&id=' . $this->item->id .'&'. Session::getFormToken() .'=1'); ?>"><?php echo Text::_("JLIB_HTML_CHECKIN"); ?></a>

<?php endif; ?>

<?php if (Factory::getApplication()->getIdentity()->authorise('core.delete','com_ccpbiosim.event.'.$this->item->id)) : ?>

	<a class="btn btn-danger" rel="noopener noreferrer" href="#deleteModal" role="button" data-bs-toggle="modal">
		<?php echo Text::_("COM_CCPBIOSIM_DELETE_ITEM"); ?>
	</a>

	<?php echo HTMLHelper::_(
                                    'bootstrap.renderModal',
                                    'deleteModal',
                                    array(
                                        'title'  => Text::_('COM_CCPBIOSIM_DELETE_ITEM'),
                                        'height' => '50%',
                                        'width'  => '20%',
                                        
                                        'modalWidth'  => '50',
                                        'bodyHeight'  => '100',
                                        'footer' => '<button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button><a href="' . Route::_('index.php?option=com_ccpbiosim&task=event.remove&id=' . $this->item->id, false, 2) .'" class="btn btn-danger">' . Text::_('COM_CCPBIOSIM_DELETE_ITEM') .'</a>'
                                    ),
                                    Text::sprintf('COM_CCPBIOSIM_DELETE_CONFIRM', $this->item->id)
                                ); ?>

<?php endif; ?>
