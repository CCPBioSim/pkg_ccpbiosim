<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Statistics controller - read-only view, no form handling needed.
 */
class StatisticsController extends BaseController
{
    /**
     * The default view.
     *
     * @var string
     */
    protected $default_view = 'statistics';
}
?>
