<?php
/**
 * @version		3.0.0
 * @package		Tabs & Sliders (plugin)
 * @author    	JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2015 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/base.php');

class JWElementTemplate extends JWElement {
	public function fetchElement($name, $value, &$node, $control_name){
		jimport('joomla.filesystem.folder');
		$plgTemplatesPath = JPATH_SITE.'/plugins/content/jw_ts/jw_ts/tmpl';
		$plgTemplatesFolders = JFolder::folders($plgTemplatesPath);
		$db = JFactory::getDBO();
		$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
		$db->setQuery($query);
		$template = $db->loadResult();
		$templatePath = JPATH_SITE.'/templates/'.$template.'/html/jw_ts';
		if (JFolder::exists($templatePath)){
			$templateFolders = JFolder::folders($templatePath);
			$folders = @array_merge($templateFolders, $plgTemplatesFolders);
			$folders = @array_unique($folders);
		} else {
			$folders = $plgTemplatesFolders;
		}
		sort($folders);
		$options = array();
		foreach ($folders as $folder){
			$options[] = JHTML::_('select.option', $folder, $folder);
		}
		$fieldName = $name;
		return JHTML::_('select.genericlist', $options, $fieldName, '', 'value', 'text', $value);
	}
}

class JFormFieldTemplate extends JWElementTemplate {
	var $type = 'template';
}
