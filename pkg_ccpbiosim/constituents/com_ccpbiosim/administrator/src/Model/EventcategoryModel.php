<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Administrator\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\Table\Table;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Plugin\PluginHelper;
use \Joomla\CMS\MVC\Model\AdminModel;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\CMS\Filter\OutputFilter;
use \Joomla\CMS\Event\Model;
use Joomla\CMS\Event\AbstractEvent;


/**
 * Eventcategory model.
 */
class EventcategoryModel extends AdminModel
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_CCPBIOSIM';

	/**
	 * @var    string  Alias to manage history control
	 */
	public $typeAlias = 'com_ccpbiosim.eventcategory';

	/**
	 * @var    null  Item data
	 */
	protected $item = null;
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  Table    A database object
	 */
	public function getTable($type = 'Eventcategory', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A \JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = Factory::getApplication();

		// Get the form.
		$form = $this->loadForm(
								'com_ccpbiosim.eventcategory', 
								'eventcategory',
								array(
									'control' => 'jform',
									'load_data' => $loadData 
								)
							);

		

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_ccpbiosim.edit.eventcategory.data', array());

		if (empty($data))
		{
			if ($this->item === null)
			{
				$this->item = $this->getItem();
			}

			$data = $this->item;
			
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		
			if ($item = parent::getItem($pk))
			{
				if (isset($item->params))
				{
					$item->params = json_encode($item->params);
				}
				
				// Do any procesing on fields here if needed
			}

			return $item;
		
	}

	/**
	 * Method to duplicate an Eventcategory
	 *
	 * @param   array  &$pks  An array of primary key IDs.
	 *
	 * @return  boolean  True if successful.
	 *
	 * @throws  Exception
	 */
	public function duplicate(&$pks)
	{
		$app = Factory::getApplication();
		$user = $app->getIdentity();
        $dispatcher = $this->getDispatcher();

		// Access checks.
		if (!$user->authorise('core.create', 'com_ccpbiosim'))
		{
			throw new \Exception(Text::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		$context    = $this->option . '.' . $this->name;

		// Include the plugins for the save events.
		PluginHelper::importPlugin($this->events_map['save']);

		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			
				if ($table->load($pk, true))
				{
					// Reset the id to create a new record.
					$table->id = 0;

					if (!$table->check())
					{
						throw new \Exception($table->getError());
					}
					

					// Create the before save event.
					$beforeSaveEvent = AbstractEvent::create(
						$this->event_before_save,
						[
							'context' => $context,
							'subject' => $table,
							'isNew'   => true,
							'data'    => $table,
						]
					);

					// Trigger the before save event.
					$dispatchResult = Factory::getApplication()->getDispatcher()->dispatch($this->event_before_save, $beforeSaveEvent);

					// Check if dispatch result is an array and handle accordingly
					$result = isset($dispatchResult['result']) ? $dispatchResult['result'] : [];

					// Proceed with your logic
					if (in_array(false, $result, true) || !$table->store()) {
						throw new \Exception($table->getError());
					}

					// Trigger the after save event.
					Factory::getApplication()->getDispatcher()->dispatch(
						$this->event_after_save,
						AbstractEvent::create(
							$this->event_after_save,
							[
								'context'    => $context,
								'subject'    => $table,
								'isNew'      => true,
								'data'       => $table,
							]
						)
					);			
				}
				else
				{
					throw new \Exception($table->getError());
				}
			
		}

		// Clean cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table  $table  Table Object
	 *
	 * @return  void
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = $this->getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__ccpbiosim_event_categories');
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}
}
