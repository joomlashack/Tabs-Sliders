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

require_once (dirname(__FILE__).'/base_element.php');

class JWElementTemplate extends JWElement
{
    public function fetchElement($name, $value, &$node, $control_name)
    {
        jimport('joomla.filesystem.folder');
        $plgTemplatesPath = JPATH_SITE.'/plugins/content/jw_ts/jw_ts/tmpl';
        $plgTemplatesFolders = JFolder::folders($plgTemplatesPath);
        $db = JFactory::getDBO();
        $query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
        $db->setQuery($query);
        $template = $db->loadResult();
        $templatePath = JPATH_SITE.'/templates/'.$template.'/html/jw_ts';

        if (JFolder::exists($templatePath)) {
            $templateFolders = JFolder::folders($templatePath);
            $folders = @array_merge($templateFolders, $plgTemplatesFolders);
            $folders = @array_unique($folders);
        } else {
            $folders = $plgTemplatesFolders;
        }

        sort($folders);
        $options = array();

        foreach ($folders as $folder) {
            $options[] = JHTML::_('select.option', $folder, $folder);
        }
        $fieldName = $name;
        return JHTML::_('select.genericlist', $options, $fieldName, '', 'value', 'text', $value);
    }
}

class JFormFieldTemplate extends JWElementTemplate
{
    public $type = 'template';
}
