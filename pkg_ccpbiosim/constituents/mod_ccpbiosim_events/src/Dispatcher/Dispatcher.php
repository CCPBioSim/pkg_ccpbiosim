<?php

namespace CCPBioSim\Module\Events\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use CCPBioSim\Module\Events\Site\Helper\EventsHelper;

/**
 * Dispatcher for mod_ccpbiosim_events.
 *
 * Gathers data from the helper and passes variables to the template.
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data array that is extracted inside the tmpl file.
     *
     * @return  array<string, mixed>
     */
    protected function getLayoutData(): array
    {
        // Retrieve parent data (includes $app, $module, $params).
        $data = parent::getLayoutData();

        $params = $data['params'];

        $count    = (int) $params->get('count', 5);
        $showPast = (bool) $params->get('show_past', 0);

        /** @var EventsHelper $helper */
        $helper = $this->getHelperFactory()->getHelper('EventsHelper');

        $data['events']       = $helper->getEvents($count, $showPast);
        $data['showLocation'] = (bool) $params->get('show_location', 1);

        return $data;
    }
}
