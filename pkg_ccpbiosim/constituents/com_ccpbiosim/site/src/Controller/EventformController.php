<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
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
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Event class.
 */
class EventformController extends FormController
{
	/**
	 * Determine the best return URL after a successful save, cancel, or delete.
	 *
	 * Priority order:
	 *   1. A return URL stored in the session (set by edit() from the HTTP referer).
	 *   2. The first menu item that points to the events list view.
	 *   3. A bare internal URL for the events list (last-resort fallback).
	 *
	 * @return  string  A routable internal URL.
	 */
	protected function getReturnUrl(): string
	{
		// 1. Use the referring URL we stored when the edit session started.
		$stored = $this->app->getUserState('com_ccpbiosim.edit.event.return', null);

		if ($stored)
		{
			$decoded = base64_decode($stored);

			if ($decoded && Uri::isInternal($decoded))
			{
				return $decoded;
			}
		}

		// 2. Walk the menu looking for a ccpbiosim events or event view item.
		$menu  = Factory::getApplication()->getMenu();
		$items = $menu->getItems('component', 'com_ccpbiosim');

		foreach ($items as $menuItem)
		{
			if (strpos($menuItem->link, 'view=events') !== false)
			{
				return 'index.php?Itemid=' . $menuItem->id;
			}
		}

		// Any ccpbiosim menu item is better than the home page.
		if (!empty($items))
		{
			return 'index.php?Itemid=' . $items[0]->id;
		}

		// 3. Bare fallback — at least stays within the component.
		return 'index.php?option=com_ccpbiosim&view=events';
	}

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $this->app->getUserState('com_ccpbiosim.edit.event.id');
		$editId     = $this->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$this->app->setUserState('com_ccpbiosim.edit.event.id', $editId);

		// Store where we came from so save() and cancel() can return there.
		// Prefer an explicit ?return= parameter, otherwise use the HTTP referer.
		$return = $this->input->getBase64('return', null);

		if (!$return)
		{
			$referer = $this->app->input->server->getString('HTTP_REFERER', '');

			if ($referer && Uri::isInternal($referer))
			{
				$return = base64_encode($referer);
			}
		}

		if ($return)
		{
			$this->app->setUserState('com_ccpbiosim.edit.event.return', $return);
		}

		// Get the model.
		$model = $this->getModel('Eventform', 'Site');

		// Check out the item.
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous item (only if it differs from the one we're opening).
		if ($previousId && $previousId !== $editId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(Route::_('index.php?option=com_ccpbiosim&view=eventform&layout=edit', false));
	}

	/**
	 * Method to save data.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		$this->checkToken();

		// Initialise variables.
		$model = $this->getModel('Eventform', 'Site');

		// Get the user data.
		$data = $this->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			throw new \Exception($model->getError(), 500);
		}

		// Send an object which can be modified through the plugin event.
		$objData = (object) $data;
		$this->app->triggerEvent(
			'onContentNormaliseRequestData',
			array($this->option . '.' . $this->context, $objData, $form)
		);

		$data = (array) $objData;

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof \Exception)
				{
					$this->app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$this->app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$jform = $this->input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$this->app->setUserState('com_ccpbiosim.edit.event.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $this->app->getUserState('com_ccpbiosim.edit.event.id');
			$this->setRedirect(Route::_('index.php?option=com_ccpbiosim&view=eventform&layout=edit&id=' . $id, false));

			$this->redirect();
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for save errors.
		if ($return === false)
		{
			// Save the data in the session.
			$this->app->setUserState('com_ccpbiosim.edit.event.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $this->app->getUserState('com_ccpbiosim.edit.event.id');
			$this->setMessage(Text::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_ccpbiosim&view=eventform&layout=edit&id=' . $id, false));
			$this->redirect();
		}

		// Check in the item now it has been saved.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the edit id and form data from the session.
		$this->app->setUserState('com_ccpbiosim.edit.event.id',   null);
		$this->app->setUserState('com_ccpbiosim.edit.event.data', null);

		// Notify the user.
		if (!empty($return))
		{
			$this->setMessage(Text::_('COM_CCPBIOSIM_ITEM_SAVED_SUCCESSFULLY'));
		}

		// Redirect back to wherever the edit was initiated from.
		$url = $this->getReturnUrl();
		$this->app->setUserState('com_ccpbiosim.edit.event.return', null);
		$this->setRedirect(Route::_($url, false));

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $data);
	}

	/**
	 * Method to abort current operation.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function cancel($key = NULL)
	{
		// Get the current edit id.
		$editId = (int) $this->app->getUserState('com_ccpbiosim.edit.event.id');

		// Get the model.
		$model = $this->getModel('Eventform', 'Site');

		// Check in the item.
		if ($editId)
		{
			$model->checkin($editId);
		}

		// Clear session state.
		$this->app->setUserState('com_ccpbiosim.edit.event.id',   null);
		$this->app->setUserState('com_ccpbiosim.edit.event.data', null);

		// Redirect back to wherever the edit was initiated from.
		$url = $this->getReturnUrl();
		$this->app->setUserState('com_ccpbiosim.edit.event.return', null);
		$this->setRedirect(Route::_($url, false));
	}

	/**
	 * Method to remove data.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function remove()
	{
		$model = $this->getModel('Eventform', 'Site');
		$pk    = $this->input->getInt('id');

		try
		{
			// Check in before delete.
			$model->checkin($pk);

			// Clear id from the session.
			$this->app->setUserState('com_ccpbiosim.edit.event.id',     null);
			$this->app->setUserState('com_ccpbiosim.edit.event.data',   null);
			$this->app->setUserState('com_ccpbiosim.edit.event.return', null);

			$deleted = $model->delete($pk);

			if ($deleted)
			{
				$this->setMessage(Text::_('COM_CCPBIOSIM_ITEM_DELETED_SUCCESSFULLY'));
			}
			else
			{
				$this->setMessage(Text::_('COM_CCPBIOSIM_ITEM_DELETED_UNSUCCESSFULLY'), 'warning');
			}

			// After a delete, always go to the events list (the specific item no longer exists).
			$menu  = Factory::getApplication()->getMenu();
			$items = $menu->getItems('component', 'com_ccpbiosim');
			$url   = 'index.php?option=com_ccpbiosim&view=events';

			foreach ($items as $menuItem)
			{
				if (strpos($menuItem->link, 'view=events') !== false)
				{
					$url = 'index.php?Itemid=' . $menuItem->id;
					break;
				}
			}

			$this->setRedirect(Route::_($url, false));
		}
		catch (\Exception $e)
		{
			$errorType = ($e->getCode() == '404') ? 'error' : 'warning';
			$this->setMessage($e->getMessage(), $errorType);
			$this->setRedirect(Route::_('index.php?option=com_ccpbiosim&view=events', false));
		}
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   BaseDatabaseModel  $model      The data model object.
	 * @param   array              $validData  The validated data.
	 *
	 * @return  void
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = array())
	{
	}
}
