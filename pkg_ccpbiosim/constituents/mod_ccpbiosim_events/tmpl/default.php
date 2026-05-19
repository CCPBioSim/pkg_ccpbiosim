<?php

\defined('_JEXEC') or die;

use CCPBioSim\Module\Events\Site\Helper\EventsHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.framework');

/** @var \Joomla\CMS\Document\HtmlDocument $doc */
$doc = $app->getDocument();
$doc->addStyleSheet(
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    ['version' => 'auto']
);
?>

<div class="mod-ccpbiosim-events">

<h3 class="text-center mb-3"><?php echo Text::_('MOD_CCPBIOSIM_EVENTS_HEADING'); ?></h3>

<?php if (empty($events)) : ?>

    <div class="alert alert-info d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-calendar-x fs-5" aria-hidden="true"></i>
        <span><?php echo Text::_('MOD_CCPBIOSIM_EVENTS_NO_EVENTS'); ?></span>
    </div>

<?php else : ?>

    <ul class="list-group list-group-flush">

    <?php foreach ($events as $event) :
        $badgeClass     = EventsHelper::getCategoryBadgeClass($event->category);
        $startFormatted = EventsHelper::formatDate($event->startdatetime);
        $endFormatted   = EventsHelper::formatDate($event->enddatetime);
        $hasLink        = !empty($event->shorturl);
    ?>

        <li class="list-group-item px-0 py-3">

            <!-- Category badge + title row -->
            <div class="d-flex flex-wrap align-items-start gap-2 mb-1">

                <span class="badge rounded-pill text-bg-<?php echo htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8'); ?>"
                      style="font-size:.7rem; letter-spacing:.04em; text-transform:uppercase;">
                    <?php echo htmlspecialchars($event->category, ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <h3 class="h6 mb-0 fw-semibold lh-sm">
                    <?php if ($hasLink) : ?>
                        <a href="<?php echo htmlspecialchars($event->shorturl, ENT_QUOTES, 'UTF-8'); ?>"
                           class="text-decoration-none link-dark">
                            <?php echo htmlspecialchars($event->title, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    <?php else : ?>
                        <?php echo htmlspecialchars($event->title, ENT_QUOTES, 'UTF-8'); ?>
                    <?php endif; ?>
                </h3>

            </div>

            <!-- Short description -->
            <?php if (!empty($event->shortdesc)) : ?>
                <p class="mb-2 text-muted small">
                    <?php echo htmlspecialchars($event->shortdesc, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <!-- Date / time + location meta strip -->
            <div class="d-flex flex-wrap gap-3 small text-secondary">

                <?php if ($startFormatted) : ?>
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-calendar-event" aria-hidden="true"></i>
                        <span><?php echo htmlspecialchars($startFormatted, ENT_QUOTES, 'UTF-8'); ?></span>
                    </span>
                <?php endif; ?>

                <?php if ($endFormatted && $endFormatted !== $startFormatted) : ?>
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-calendar-check" aria-hidden="true"></i>
                        <span><?php echo htmlspecialchars($endFormatted, ENT_QUOTES, 'UTF-8'); ?></span>
                    </span>
                <?php endif; ?>

                <?php if ($showLocation && !empty($event->location)) : ?>
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i>
                        <span><?php echo htmlspecialchars($event->location, ENT_QUOTES, 'UTF-8'); ?></span>
                    </span>
                <?php endif; ?>

            </div>

        </li>

    <?php endforeach; ?>

    </ul>

    <!-- "View all events" footer link -->
    <div class="mt-3 text-end">
        <a href="index.php?option=com_ccpbiosim&amp;view=events"
           class="btn btn-primary">
            <i class="bi bi-calendar3" aria-hidden="true"></i>
            <?php echo Text::_('MOD_CCPBIOSIM_EVENTS_VIEW_ALL'); ?>
        </a>
    </div>

<?php endif; ?>

</div>
