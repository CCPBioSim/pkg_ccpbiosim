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

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');
?>

<form
	action="<?php echo Route::_('index.php?option=com_ccpbiosim&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="managementteam-form" class="form-validate form-horizontal">

	
	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'coreteammember')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'coreteammember', Text::_('COM_CCPBIOSIM_TAB_CORETEAMMEMBER', true)); ?>
	<div class="row-fluid">
		<div class="col-md-12 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo Text::_('COM_CCPBIOSIM_FIELDSET_CORETEAMMEMBER'); ?></legend>
				<?php echo $this->form->renderField('title'); ?>
				<?php echo $this->form->renderField('firstname'); ?>
				<?php echo $this->form->renderField('surname'); ?>
				<?php echo $this->form->renderField('role'); ?>
				<?php echo $this->form->renderField('profilephoto'); ?>
				<?php echo $this->form->renderField('groupwebsite'); ?>
				<?php echo $this->form->renderField('social'); ?>
				<?php echo $this->form->renderField('chair'); ?>
				<?php echo $this->form->renderField('cosecprojectlead'); ?>
				<?php echo $this->form->renderField('adminassistant'); ?>
				<?php echo $this->form->renderField('insitution'); ?>
				<?php echo $this->form->renderField('secretary'); ?>
				<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>
	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>

	
	<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>

</form>
