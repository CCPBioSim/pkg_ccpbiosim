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

// Helper: extract a YouTube video ID from various URL formats
function ccpbiosim_get_youtube_id($url)
{
	if (empty($url)) {
		return false;
	}
	// Handles youtu.be/ID, youtube.com/watch?v=ID, youtube.com/embed/ID, youtube.com/shorts/ID
	$pattern = '/(?:youtu\.be\/|youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/))([A-Za-z0-9_-]{11})/';
	if (preg_match($pattern, $url, $matches)) {
		return $matches[1];
	}
	return false;
}

$youtubeId = ccpbiosim_get_youtube_id($this->item->youtube);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<div class="ccpbiosim-event">

	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="d-flex align-items-start justify-content-between gap-3 mb-1">
		<h1><?php echo $this->escape($this->item->title); ?></h1>
		<div class="d-flex gap-2 flex-wrap flex-shrink-0">

			<?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_ccpbiosim.' . $this->item->id) || $this->item->checked_out == Factory::getApplication()->getIdentity()->id; ?>

			<?php if ($canEdit && $this->item->checked_out == 0) : ?>
				<a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=event.edit&id=' . $this->item->id); ?>">
					<?php echo Text::_('COM_CCPBIOSIM_EDIT_ITEM'); ?>
				</a>
			<?php elseif ($canCheckin && $this->item->checked_out > 0) : ?>
				<a class="btn btn-outline" href="<?php echo Route::_('index.php?option=com_ccpbiosim&task=event.checkin&id=' . $this->item->id . '&' . Session::getFormToken() . '=1'); ?>">
					<?php echo Text::_('JLIB_HTML_CHECKIN'); ?>
				</a>
			<?php endif; ?>

			<?php if (Factory::getApplication()->getIdentity()->authorise('core.delete', 'com_ccpbiosim.event.' . $this->item->id)) : ?>
				<a class="btn btn-danger" rel="noopener noreferrer" href="#deleteModal" role="button" data-bs-toggle="modal">
					<?php echo Text::_('COM_CCPBIOSIM_DELETE_ITEM'); ?>
				</a>

				<?php echo HTMLHelper::_(
					'bootstrap.renderModal',
					'deleteModal',
					[
						'title'       => Text::_('COM_CCPBIOSIM_DELETE_ITEM'),
						'height'      => '50%',
						'width'       => '20%',
						'modalWidth'  => '50',
						'bodyHeight'  => '100',
						'footer'      => '<button class="btn btn-primary" data-bs-dismiss="modal">' . Text::_('JCANCEL') . '</button>'
						               . '<a href="' . Route::_('index.php?option=com_ccpbiosim&task=event.remove&id=' . $this->item->id, false, 2) . '" class="btn btn-danger">'
						               . Text::_('COM_CCPBIOSIM_DELETE_ITEM') . '</a>',
					],
					Text::sprintf('COM_CCPBIOSIM_DELETE_CONFIRM', $this->item->id)
				); ?>
			<?php endif; ?>

		</div>
	</div>
	<?php endif; ?>

	<div class="event-meta mb-3">
		<i class="bi bi-calendar-range me-1" aria-hidden="true"></i>
		<span>
			<?php echo $this->escape($this->item->startdatetime); ?>
			<span class="mx-1">&ndash;</span>
			<?php echo $this->escape($this->item->enddatetime); ?>
		</span>
	</div>

	<?php if (!empty($this->item->location)) : ?>
	<div class="event-meta mb-3">
		<i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
		<span><?php echo $this->escape($this->item->location); ?></span>
	</div>
	<?php endif; ?>

	<?php if (!empty($this->item->category)) : ?>
	<div class="mb-4">
		<span class="badge bg-secondary"><?php echo $this->escape($this->item->category); ?></span>
	</div>
	<?php endif; ?>

	<hr class="mb-4">

	<?php if (!empty($this->item->eventdetails)) : ?>
	<div class="event-section mb-4">
		<h3 class="event-section__heading"><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_EVENTDETAILS'); ?></h3>
		<div class="event-section__body">
			<?php echo nl2br($this->item->eventdetails); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if (!empty(trim($this->item->postevent))) : ?>
	<div class="event-callout mb-4" role="note">
		<div class="event-callout__icon" aria-hidden="true">
			<i class="bi bi-megaphone"></i>
		</div>
		<div class="event-callout__content">
			<strong class="event-callout__label"><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_POSTEVENT'); ?></strong>
			<div class="event-callout__text mt-1">
				<?php echo nl2br($this->escape($this->item->postevent)); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($youtubeId) : ?>
	<div class="event-section mb-4">
		<h3 class="event-section__heading"><?php echo Text::_('COM_CCPBIOSIM_FORM_LBL_EVENT_YOUTUBE'); ?></h3>
		<div class="ratio ratio-16x9 event-youtube">
			<iframe
				src="https://www.youtube-nocookie.com/embed/<?php echo htmlspecialchars($youtubeId, ENT_QUOTES, 'UTF-8'); ?>"
				title="<?php echo $this->escape($this->item->title); ?> – video"
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen
				loading="lazy"
			></iframe>
		</div>
	</div>
	<?php endif; ?>

</div>

<style>
/* ── CCPBioSim Event Page ──────────────────────────────────────────────── */

/* Meta row (date / location) */
.event-meta {
	display: flex;
	align-items: center;
	color: #555;
	font-size: .95rem;
}

/* Post-event callout */
.event-callout {
	display: flex;
	gap: 1rem;
	background: #e8f4f2;
	border-left: 4px solid var(--cassiopeia-color-primary);
	border-radius: 0 .375rem .375rem 0;
	padding: 1rem 1.25rem;
}

.event-callout__icon {
	flex-shrink: 0;
	color: var(--cassiopeia-color-primary);
	padding-top: .15rem;
}

.event-callout__label {
	font-size: .9rem;
	text-transform: uppercase;
	letter-spacing: .05em;
	color: var(--cassiopeia-color-primary);
}

/* YouTube embed */
.event-youtube {
	border-radius: .375rem;
	overflow: hidden;
}
</style>
