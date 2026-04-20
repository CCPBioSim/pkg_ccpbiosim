<?php
namespace Ccpbiosim\Module\Activities\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
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
        $this->app = $app;

        $wa  = $app->getDocument()->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('com_ccpbiosim');
        $wa->useStyle('com_ccpbiosim.site');
    }

    public function dispatch()
    {
        $language = $this->app->getLanguage();
        $language->load('mod_ccpbiosim_activities', JPATH_BASE . '/modules/mod_ccpbiosim_activities');
        $params = new Registry($this->module->params);

        require ModuleHelper::getLayoutPath('mod_ccpbiosim_activities');
    }
}

?>
