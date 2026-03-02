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
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');

// Import CSS
$wa =  $this->document->getWebAssetManager();
$wa->useStyle('com_ccpbiosim.admin')
    ->useScript('com_ccpbiosim.admin');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_ccpbiosim');

$saveOrder = $listOrder == 'a.ordering';

if (!empty($saveOrder))
{
	$saveOrderingUrl = 'index.php?option=com_ccpbiosim&task=events.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

?>

<form action="<?php echo Route::_('index.php?option=com_ccpbiosim&view=events'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-12">
			<div id="j-main-container" class="j-main-container">
			<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

				<div class="clearfix"></div>
				<table class="table table-striped" id="eventList">
					<thead>
					<tr>
						<th class="w-1 text-center">
							<input type="checkbox" autocomplete="off" class="form-check-input" name="checkall-toggle" value=""
								   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
						</th>
						
					<?php if (isset($this->items[0]->ordering)): ?>
					<th scope="col" class="w-1 text-center d-none d-md-table-cell">

					<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>

					</th>
					<?php endif; ?>

						
					<th  scope="col" class="w-1 text-center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
						
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_SHORTURL', 'a.shorturl', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_CATEGORY', 'a.category', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_STARTDATETIME', 'a.startdatetime', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_ENDDATETIME', 'a.enddatetime', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_LOCATION', 'a.location', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_SHORTDESC', 'a.shortdesc', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_YOUTUBE', 'a.youtube', $listDirn, $listOrder); ?>
						</th>
						<th class='left'>
							<?php echo HTMLHelper::_('searchtools.sort',  'COM_CCPBIOSIM_EVENTS_POSTEVENT', 'a.postevent', $listDirn, $listOrder); ?>
						</th>
						
					<th scope="col" class="w-3 d-none d-lg-table-cell" >

						<?php echo HTMLHelper::_('searchtools.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>					</th>
					</tr>
					</thead>
					<tfoot>
					<tr>
						<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
					</tfoot>
					<tbody <?php if (!empty($saveOrder)) :?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" <?php endif; ?>>
					<?php foreach ($this->items as $i => $item) :
						$ordering   = ($listOrder == 'a.ordering');
						$canCreate  = $user->authorise('core.create', 'com_ccpbiosim');
						$canEdit    = $user->authorise('core.edit', 'com_ccpbiosim');
						$canCheckin = $user->authorise('core.manage', 'com_ccpbiosim');
						$canChange  = $user->authorise('core.edit.state', 'com_ccpbiosim');
						?>
						<tr class="row<?php echo $i % 2; ?>" data-draggable-group='1' data-transition>
							<td class="text-center">
								<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
							</td>
							
							<?php if (isset($this->items[0]->ordering)) : ?>

							<td class="text-center d-none d-md-table-cell">

							<?php

							$iconClass = '';

							if (!$canChange)

							{
								$iconClass = ' inactive';

							}
							elseif (!$saveOrder)

							{
								$iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');

							}							?>							<span class="sortable-handler<?php echo $iconClass ?>">
							<span class="icon-ellipsis-v" aria-hidden="true"></span>
							</span>
							<?php if ($canChange && $saveOrder) : ?>
							<input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
								<?php endif; ?>
							</td>
							<?php endif; ?>

							
							<td class="text-center">
								<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'events.', $canChange, 'cb'); ?>
							</td>
							
							<td>
								<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
									<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'events.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit) : ?>
									<a href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=event.edit&id='.(int) $item->id); ?>">
									<?php echo $this->escape($item->title); ?>
									</a>
								<?php else : ?>
												<?php echo $this->escape($item->title); ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->shorturl; ?>
							</td>
							<td>
								<?php echo $item->category; ?>
							</td>
							<td>
								<?php echo $item->startdatetime; ?>
							</td>
							<td>
								<?php echo $item->enddatetime; ?>
							</td>
							<td>
								<?php echo $item->location; ?>
							</td>
							<td>
								<?php echo $item->shortdesc; ?>
							</td>
							<td>
								<?php echo $item->youtube; ?>
							</td>
							<td>
								<?php echo $item->postevent; ?>
							</td>
							
							<td class="d-none d-lg-table-cell">
							<?php echo $item->id; ?>

							</td>


						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<input type="hidden" name="task" value=""/>
				<input type="hidden" name="boxchecked" value="0"/>
				<input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>
