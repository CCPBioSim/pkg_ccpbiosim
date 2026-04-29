<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ccpbiosim
 * @author     James <james.gebbie@gmail.com>
 * @copyright  2026 James
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;

/**
 * Statistics class.
 *
 * @since  1.0.0
 */
class StatisticsController extends FormController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return  object	The model
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Statistics', $prefix = 'Site', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
}
