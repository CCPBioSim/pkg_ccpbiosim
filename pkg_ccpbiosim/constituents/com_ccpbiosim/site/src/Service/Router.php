<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\Service;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Categories\CategoryInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\CMS\Component\ComponentHelper;

/**
 * Class CcpbiosimRouter
 *
 */
class Router extends RouterView
{
	private $noIDs;
	/**
	 * The category factory
	 *
	 * @var    CategoryFactoryInterface
	 */
	private $categoryFactory;

	/**
	 * The category cache
	 *
	 * @var    array
	 */
	private $categoryCache = [];

	public function __construct(SiteApplication $app, AbstractMenu $menu, CategoryFactoryInterface $categoryFactory, DatabaseInterface $db)
	{
		$params = ComponentHelper::getParams('com_ccpbiosim');
		$this->noIDs = (bool) $params->get('sef_ids');
		$this->categoryFactory = $categoryFactory;
		
		# Register views
		$coreteammembers = new RouterViewConfiguration('coreteammembers');
		$this->registerView($coreteammembers);
		$coreteammemberform = new RouterViewConfiguration('coreteammemberform');
		$coreteammemberform->setKey('id');
		$this->registerView($coreteammemberform);
		$events = new RouterViewConfiguration('events');
		$this->registerView($events);
		$ccEvent = new RouterViewConfiguration('event');
		$ccEvent->setKey('id')->setParent($events);
		$this->registerView($ccEvent);
		$eventform = new RouterViewConfiguration('eventform');
		$eventform->setKey('id');
		$this->registerView($eventform);
		$eventcategories = new RouterViewConfiguration('eventcategories');
		$this->registerView($eventcategories);
		$ccEventcategory = new RouterViewConfiguration('eventcategory');
		$ccEventcategory->setKey('id')->setParent($eventcategories);
		$this->registerView($ccEventcategory);
		$eventcategoryform = new RouterViewConfiguration('eventcategoryform');
		$eventcategoryform->setKey('id');
		$this->registerView($eventcategoryform);
                $managementteams = new RouterViewConfiguration('managementteams');
		$this->registerView($managementteams);
		$managementteamform = new RouterViewConfiguration('managementteamform');
		$managementteamform->setKey('id');
		$this->registerView($managementteamform);
		$ourcommunity = new RouterViewConfiguration('ourcommunity');
		$this->registerView($ourcommunity);
                $software = new RouterViewConfiguration('software');
		$this->registerView($software);
		$workshop = new RouterViewConfiguration('workshop');
		$this->registerView($workshop);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}

	/**
	* Method to get the segment(s) for an coreteammember
	*
	* @param   string  $id     ID of the coreteammember to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getCoreteammemberSegment($id, $query)
	{
		return array((int) $id => $id);
	}
	
	/**
	* Method to get the segment(s) for an coreteammemberform
	*
	* @param   string  $id     ID of the coreteammemberform to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getCoreteammemberformSegment($id, $query)
	{
		return $this->getCoreteammemberSegment($id, $query);
	}

	/**
	* Method to get the segment(s) for an coreteammember
	*
	* @param   string  $segment  Segment of the coreteammember to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getCoreteammemberId($segment, $query)
	{
		return (int) $segment;
	}

	/**
	* Method to get the segment(s) for an coreteammemberform
	*
	* @param   string  $segment  Segment of the coreteammemberform to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getCoreteammemberformId($segment, $query)
	{
		return $this->getCoreteammemberId($segment, $query);
	}

	/**
	* Method to get the segment(s) for an managementteam
	*
	* @param   string  $id     ID of the managementteam to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getManagementteamSegment($id, $query)
	{
		return array((int) $id => $id);
	}
	
	/**
	* Method to get the segment(s) for an managementteamform
	*
	* @param   string  $id     ID of the managementteamform to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getManagementteamformSegment($id, $query)
	{
		return $this->getManagementteamSegment($id, $query);
	}

	/**
	* Method to get the segment(s) for an managementteam
	*
	* @param   string  $segment  Segment of the managementteam to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getManagementteamId($segment, $query)
	{
		return (int) $segment;
	}

	/**
	* Method to get the segment(s) for an managementteamform
	*
	* @param   string  $segment  Segment of the managementteamform to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getManagementteamformId($segment, $query)
	{
		return $this->getManagementteamId($segment, $query);
	}

	/**
	* Method to get the segment(s) for an eventcategory
	*
	* @param   string  $id     ID of the eventcategory to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getEventcategorySegment($id, $query)
	{
		return array((int) $id => $id);
	}

	/**
	* Method to get the segment(s) for an eventcategoryform
	*
	* @param   string  $id     ID of the eventcategoryform to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getEventcategoryformSegment($id, $query)
	{
		return $this->getEventcategorySegment($id, $query);
	}

	/**
	* Method to get the segment(s) for an eventcategory
	*
	* @param   string  $segment  Segment of the eventcategory to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getEventcategoryId($segment, $query)
	{
	 	return (int) $segment;
	}

	/**
	* Method to get the segment(s) for an eventcategoryform
	*
	* @param   string  $segment  Segment of the eventcategoryform to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getEventcategoryformId($segment, $query)
	{
		return $this->getEventcategoryId($segment, $query);
	}

	/**
	* Method to get the segment(s) for an event
	*
	* @param   string  $id     ID of the event to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getEventSegment($id, $query)
	{
		return array((int) $id => $id);
	}

	/**
	* Method to get the segment(s) for an eventform
	*
	* @param   string  $id     ID of the eventform to retrieve the segments for
	* @param   array   $query  The request that is built right now
	*
	* @return  array|string  The segments of this item
	*/
	public function getEventformSegment($id, $query)
	{
		return $this->getEventSegment($id, $query);
	}

	/**
	* Method to get the segment(s) for an event
	*
	* @param   string  $segment  Segment of the event to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getEventId($segment, $query)
	{
		return (int) $segment;
	}

	/**
	* Method to get the segment(s) for an eventform
	*
	* @param   string  $segment  Segment of the eventform to retrieve the ID for
	* @param   array   $query    The request that is parsed right now
	*
	* @return  mixed   The id of this item or false
	*/
	public function getEventformId($segment, $query)
	{
		return $this->getEventId($segment, $query);
	}

	/**
	 * Method to get categories from cache
	 *
	 * @param   array  $options   The options for retrieving categories
	 *
	 * @return  CategoryInterface  The object containing categories
	 */
	private function getCategories(array $options = []): CategoryInterface
	{
		$key = serialize($options);

		if (!isset($this->categoryCache[$key]))
		{
			$this->categoryCache[$key] = $this->categoryFactory->createCategory($options);
		}

		return $this->categoryCache[$key];
	}
}
