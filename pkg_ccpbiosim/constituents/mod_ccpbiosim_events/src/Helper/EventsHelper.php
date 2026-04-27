<?php

namespace CCPBioSim\Module\Events\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Helper class for mod_ccpbiosim_events
 *
 * Retrieves upcoming events from the #__ccpbiosim_events table.
 */
class EventsHelper
{
    /**
     * Retrieve upcoming events.
     *
     * @param   int   $count     Maximum number of events to return.
     * @param   bool  $showPast  Whether to include past events (false = upcoming only).
     *
     * @return  array  Array of event objects.
     */
    public function getEvents(int $count = 5, bool $showPast = false): array
    {
        $db    = Factory::getDbo();
        $now   = Factory::getDate()->toSql();
        $query = $db->getQuery(true);

        $query->select(
            $db->quoteName([
                'e.id',
                'e.title',
                'e.startdatetime',
                'e.enddatetime',
                'e.shortdesc',
                'e.location',
                'e.shorturl',
            ])
        )
        ->select($db->quoteName('c.eventcategory', 'category'))
        ->from($db->quoteName('#__ccpbiosim_events', 'e'))
        ->leftJoin(
            $db->quoteName('#__ccpbiosim_event_categories', 'c')
            . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('e.category')
            . ' AND ' . $db->quoteName('c.state') . ' = 1'
        )
        ->where($db->quoteName('e.state') . ' = 1');

        if (!$showPast) {
            $query->where(
                '(' .
                $db->quoteName('e.enddatetime') . ' >= ' . $db->quote($now) .
                ' OR (' .
                $db->quoteName('e.enddatetime') . ' IS NULL AND ' .
                $db->quoteName('e.startdatetime') . ' >= ' . $db->quote($now) .
                '))'
            );
        }

        $query->order($db->quoteName('e.startdatetime') . ' ASC')
              ->setLimit($count);

        $db->setQuery($query);

        return $db->loadObjectList() ?: [];
    }

    /**
     * Map a category string to a Bootstrap contextual colour class.
     *
     * Extend this list as new categories are introduced.
     *
     * @param   string  $category  The raw category value from the database.
     *
     * @return  string  A Bootstrap badge colour class (e.g. 'primary', 'success').
     */
    public static function getCategoryBadgeClass(string $category): string
    {
        $map = [
            'conferences'       => 'primary',
            'training workshops' => 'success',
            'webinars'          => 'danger',
        ];

        $key = strtolower(trim($category));

        return $map[$key] ?? 'primary';
    }

    /**
     * Format a datetime string for human-friendly display.
     *
     * @param   string|null  $datetime  A MySQL DATETIME string or null.
     * @param   string       $format    PHP date() format string.
     *
     * @return  string  Formatted date string, or an empty string if null.
     */
    public static function formatDate(?string $datetime, string $format = 'j M Y, H:i'): string
    {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return '';
        }

        try {
            $date = Factory::getDate($datetime);
            return $date->format($format, true);
        } catch (\Exception $e) {
            return '';
        }
    }
}
