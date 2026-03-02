<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Administrator\View\Managementteams;
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use \Ccpbiosim\Component\Ccpbiosim\Administrator\Helper\CcpbiosimHelper;
use \Joomla\CMS\Toolbar\Toolbar;
use \Joomla\CMS\Toolbar\ToolbarHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\Component\Content\Administrator\Extension\ContentComponent;
use \Joomla\CMS\Form\Form;
use \Joomla\CMS\HTML\Helpers\Sidebar;
/**
 * View class for a list of Managementteams.
 *
 */
class HtmlView extends BaseHtmlView
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors));
		}

		$this->addToolbar();

		$this->sidebar = Sidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = CcpbiosimHelper::getActions();

		ToolbarHelper::title(Text::_('COM_CCPBIOSIM_TITLE_MANAGEMENTTEAMS'), "generic");

		$toolbar = Toolbar::getInstance('toolbar');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/src/View/Managementteams';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				$toolbar->addNew('managementteam.add');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fas fa-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			if (isset($this->items[0]->state))
			{
				$childBar->publish('managementteams.publish')->listCheck(true);
				$childBar->unpublish('managementteams.unpublish')->listCheck(true);
				$childBar->archive('managementteams.archive')->listCheck(true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				$toolbar->delete('managementteams.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
			}

			$childBar->standardButton('duplicate')
				->text('JTOOLBAR_DUPLICATE')
				->icon('fas fa-copy')
				->task('managementteams.duplicate')
				->listCheck(true);

			if (isset($this->items[0]->checked_out))
			{
				$childBar->checkin('managementteams.checkin')->listCheck(true);
			}

			if (isset($this->items[0]->state))
			{
				$childBar->trash('managementteams.trash')->listCheck(true);
			}
		}

		

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{

			if ($this->state->get('filter.state') == ContentComponent::CONDITION_TRASHED && $canDo->get('core.delete'))
			{
				$toolbar->delete('managementteams.delete')
					->text('JTOOLBAR_EMPTY_TRASH')
					->message('JGLOBAL_CONFIRM_DELETE')
					->listCheck(true);
			}
		}

		if ($canDo->get('core.admin'))
		{
			$toolbar->preferences('com_ccpbiosim');
		}

		// Set sidebar action
		Sidebar::setAction('index.php?option=com_ccpbiosim&view=managementteams');
	}
	
	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => Text::_('JGRID_HEADING_ID'),
			'a.`state`' => Text::_('JSTATUS'),
			'a.`ordering`' => Text::_('JGRID_HEADING_ORDERING'),
			'a.`title`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_TITLE'),
			'a.`firstname`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_FIRSTNAME'),
			'a.`surname`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_SURNAME'),
			'a.`role`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_ROLE'),
			'a.`profilephoto`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_PROFILEPHOTO'),
			'a.`groupwebsite`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_GROUPWEBSITE'),
			'a.`social`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_SOCIAL'),
			'a.`chair`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_CHAIR'),
			'a.`cosecprojectlead`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_COSECPROJECTLEAD'),
			'a.`adminassistant`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_ADMINASSISTANT'),
			'a.`insitution`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_INSITUTION'),
			'a.`secretary`' => Text::_('COM_CCPBIOSIM_MANAGEMENTTEAMS_SECRETARY'),
		);
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}
}
