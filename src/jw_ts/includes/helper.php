<?php
/**
 * @package   TabsSliders
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2006-2015 JoomlaWorks Ltd. All rights reserved.
 * @copyright 2016-2021 Joomlashack.com. All rights reserved
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of TabsSliders.
 *
 * TabsSliders is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * TabsSliders is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TabsSliders.  If not, see <https://www.gnu.org/licenses/>.
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die();

class JWTSHelper
{
    /**
     * @param string $pluginName
     * @param string $pluginTemplate
     *
     * @return Registry
     * @throws Exception
     */
    public function getTemplatePath($pluginName, $pluginTemplate)
    {
        $app         = JFactory::getApplication();
        $pluginGroup = 'content';

        $templateFolder = sprintf(
            '/templates/%s/html/%s/%s',
            $app->getTemplate(),
            $pluginName,
            $pluginTemplate
        );
        if (is_dir(JPATH_SITE . $templateFolder)) {
            $properties = array(
                'folder' => JPATH_SITE . $templateFolder,
                'http'   => JUri::root() . $templateFolder
            );

        } else {
            $pluginFolder = sprintf(
                '/plugins/%s/%s/%s/tmpl/%s',
                $pluginGroup,
                $pluginName,
                $pluginName,
                $pluginTemplate
            );

            $properties = array(
                'folder' => JPATH_SITE . $pluginFolder,
                'http'   => JUri::root() . $pluginFolder
            );
        }

        $paths = new Registry($properties);

        return $paths;
    }
}
