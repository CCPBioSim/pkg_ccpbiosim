<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Ccpbiosim\Component\Ccpbiosim\Administrator\Extension\CcpbiosimComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;


/**
 * The Ccpbiosim service provider.
 */
return new class implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 */
	public function register(Container $container)
	{

		$container->registerServiceProvider(new CategoryFactory('\\Ccpbiosim\\Component\\Ccpbiosim'));
		$container->registerServiceProvider(new MVCFactory('\\Ccpbiosim\\Component\\Ccpbiosim'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Ccpbiosim\\Component\\Ccpbiosim'));
		$container->registerServiceProvider(new RouterFactory('\\Ccpbiosim\\Component\\Ccpbiosim'));

		$container->set(
			ComponentInterface::class,
			function (Container $container)
			{
				$component = new CcpbiosimComponent($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);
	}
};
