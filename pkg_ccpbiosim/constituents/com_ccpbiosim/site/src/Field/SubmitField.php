<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\Field;

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Form\FormField;

/**
 * Class SubmitField
 */
class SubmitField extends FormField
{
	protected $type = 'submit';

	protected $value;

	protected $for;

	/**
	 * Get a form field markup for the input
	 *
	 * @return string
	 */
	public function getInput()
	{
		$this->value = $this->getAttribute('value');

		return '<button id="' . $this->id . '"'
		. ' name="submit_' . $this->for . '"'
		. ' value="' . $this->value . '"'
		. ' title="' . Text::_('JSEARCH_FILTER_SUBMIT') . '"'
		. ' class="btn" style="margin-top: -10px;">'
		. Text::_('JSEARCH_FILTER_SUBMIT')
		. ' </button>';
	}
}
