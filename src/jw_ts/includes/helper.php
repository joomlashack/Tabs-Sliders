<?php
/**
 * @package   Tabs and Sliders
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @author    JoomlaWorks - http://www.joomlaworks.net
 * @copyright 2006-2015 JoomlaWorks Ltd. All rights reserved.
 * @copyright 2016-2019 Joomlashack.com. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of Tabs and Sliders.
 *
 * Tabs and Sliders is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tabs and Sliders is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tabs and Sliders.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JWTSHelper
{
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

            $properties   = array(
                'folder' => JPATH_SITE . $pluginFolder,
                'http'   => JUri::root(true) . $pluginFolder
            );
        }

        $paths = new JObject($properties);

        return $paths;
    }
}
