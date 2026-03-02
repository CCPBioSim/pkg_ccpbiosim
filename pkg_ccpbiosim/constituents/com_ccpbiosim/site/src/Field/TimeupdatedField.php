<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\Field;

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Form\FormField;
use \Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Supports an HTML select list of categories
 */
class TimeupdatedField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'timeupdated';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		$old_time_updated = $this->value;
		$hidden           = (boolean) $this->element['hidden'];

		if ($hidden == null || !$hidden)
		{
			if (!strtotime($old_time_updated))
			{
				$html[] = '-';
			}
			else
			{
				$pretty_date = HTMLHelper::_('date', $old_time_updated, Text::_('DATE_FORMAT_LC2'), true);
				$html[]      = "<div>" . $pretty_date . "</div>";
			}
		}

		$time_updated = Factory::getDate()->toSql();
		$html[]       = '<input type="hidden" name="' . $this->name . '" value="' . $time_updated . '" />';

		return implode($html);
	}
}
