<?php

namespace Ccpbiosim\Module\Mailinglist\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class HeroHelper
{
    public static function getLoggedonUsername(string $default)
    {
        $user = Factory::getApplication()->getIdentity();
        if ($user->id !== 0)  // found a logged-on user
        {
            return $user->username;
        }
        else
        {
            return $default;
        }
    }
}

?>
