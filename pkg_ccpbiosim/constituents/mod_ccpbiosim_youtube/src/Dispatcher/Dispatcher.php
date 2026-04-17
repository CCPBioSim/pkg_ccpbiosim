<?php
namespace Ccpbiosim\Module\Youtube\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Ccpbiosim\Module\Youtube\Site\Helper\YoutubeHelper;

class Dispatcher implements DispatcherInterface
{

    protected $module;
    protected $app;

    public function __construct(\stdClass $module, CMSApplicationInterface $app, Input $input)
    {
        $this->module = $module;
        $this->app = $app;
    }

    public function dispatch()
    {
        $language = $this->app->getLanguage();
        $language->load('mod_ccpbiosim_youtube', JPATH_BASE . '/modules/mod_ccpbiosim_youtube');
        $params = new Registry($this->module->params);

        $videos = YoutubeHelper::getVideos($params, $this->app);
        $moduleId = 'mod-yt-carousel-' . $this->module->id;

        require ModuleHelper::getLayoutPath('mod_ccpbiosim_youtube');
    }
}

?>
