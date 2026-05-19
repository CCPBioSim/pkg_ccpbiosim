<?php
namespace Ccpbiosim\Module\Activities\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Router\Route;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Ccpbiosim\Module\Activities\Site\Helper\ActivitiesHelper;

class Dispatcher implements DispatcherInterface
{

    protected $module;
    
    protected $app;

    public function __construct(\stdClass $module, CMSApplicationInterface $app, Input $input)
    {
        $this->module = $module;
        $this->app    = $app;

        $wa = $app->getDocument()->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('com_ccpbiosim');
        $wa->useStyle('com_ccpbiosim.site');
    }

    public function dispatch()
    {
        $language = $this->app->getLanguage();
        $language->load('mod_ccpbiosim_activities', JPATH_BASE . '/modules/mod_ccpbiosim_activities');

        $params = new Registry($this->module->params);

        // Resolve each menu item ID to a routed URL.
        // Route::_() returns an empty string when $id is 0/empty, so the
        // template uses empty-check to decide whether to render a button.
        $block1Url = $this->resolveMenuUrl((int) $params->get('actvties-menublock1', 0));
        $block2Url = $this->resolveMenuUrl((int) $params->get('actvties-menublock2', 0));
        $block3Url = $this->resolveMenuUrl((int) $params->get('actvties-menublock3', 0));
        $block4Url = $this->resolveMenuUrl((int) $params->get('actvties-menublock4', 0));

        require ModuleHelper::getLayoutPath('mod_ccpbiosim_activities');
    }

    /**
     * Convert a Joomla menu item ID into a routed, SEF-friendly URL.
     *
     * @param  int     $menuId  The menu item ID (0 means not set).
     * @return string           The routed URL, or an empty string if not set.
     */
    protected function resolveMenuUrl(int $menuId): string
    {
        if ($menuId <= 0) {
            return '';
        }

        return Route::_('index.php?Itemid=' . $menuId);
    }
}
