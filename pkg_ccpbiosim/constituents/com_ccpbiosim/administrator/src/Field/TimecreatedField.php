<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Administrator\Field;

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Form\FormField;
use \Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Supports an HTML select list of categories
 *
 */
class TimecreatedField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'timecreated';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		$time_created = $this->value;

		// If time is empty or invalid, use current time in UTC for saving
		if (empty($time_created) || $time_created === '0000-00-00 00:00:00' || !strtotime($time_created)) {
			$now = Factory::getDate(); // UTC
			$time_created = $now->toSql(true);
		}

		// Store raw UTC date in hidden input
		$html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($time_created, ENT_QUOTES, 'UTF-8') . '" />';


		$hidden = (boolean) $this->element['hidden'];

		if ($hidden == null || !$hidden)
		{
			$pretty_date = HTMLHelper::_('date', $time_created, Text::_('DATE_FORMAT_LC2'), true);
			$html[]      = "<div>" . $pretty_date . "</div>";
		}

		return implode($html);
	}
}
