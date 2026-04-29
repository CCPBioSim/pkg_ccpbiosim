<?php

/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\View\Statistics;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Statistics HTML View
 *
 * Passes model data to the tmpl/statistics/default.php layout.
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Full statistics payload from the model.
     *
     * @var array
     */
    protected array $statisticsData = [];

    /**
     * Prepares the view, fetches model data, and renders the layout.
     *
     * @param  string  $tpl  Optional template suffix.
     * @return void
     */
    public function display($tpl = null): void
    {
        /** @var \CCPBioSim\Component\Ccpbiosim\Site\Model\Statistics\StatisticsModel $model */
        $model = $this->getModel();

        $this->statisticsData = $model->getStatisticsData();

        // Set the page title
        $app = Factory::getApplication();
        $app->getDocument()->setTitle(Text::_('COM_CCPBIOSIM_STATISTICS_TITLE'));

        parent::display($tpl);
    }
}
