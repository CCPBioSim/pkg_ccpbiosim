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
use \Ccpbiosim\Component\Ccpbiosim\Site\Helper\CcpbiosimHelper;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_ccpbiosim', JPATH_SITE);

$user    = Factory::getApplication()->getIdentity();
$canEdit = CcpbiosimHelper::canUserEdit($this->item, $user);


?>

<div class="event-edit front-end-edit">

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
    <?php endif;?>
	<?php if (!$canEdit) : ?>
		<h3>
		<?php throw new \Exception(Text::_('COM_CCPBIOSIM_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_CCPBIOSIM_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_CCPBIOSIM_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-event"
			  action="<?php echo Route::_('index.php?option=com_ccpbiosim&task=eventform.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'event')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'event', Text::_('COM_CCPBIOSIM_TAB_EVENT', true)); ?>

        <?php echo $this->form->renderField('title'); ?>
        <?php echo $this->form->renderField('shorturl'); ?>
        <?php echo $this->form->renderField('category'); ?>
        <?php echo $this->form->renderField('location'); ?>
        <?php echo $this->form->renderField('startdatetime'); ?>
        <?php echo $this->form->renderField('enddatetime'); ?>
        <?php echo $this->form->renderField('shortdesc'); ?>
        <?php echo $this->form->renderField('eventdetails'); ?>
        <?php echo $this->form->renderField('youtube'); ?>
        <?php echo $this->form->renderField('postevent'); ?>

	<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<span class="fas fa-check" aria-hidden="true"></span>
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn btn-danger"
					   href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=eventform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
					   <span class="fas fa-times" aria-hidden="true"></span>
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_ccpbiosim"/>
			<input type="hidden" name="task"
				   value="eventform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
