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

class JWElementHeader extends JWElement {
	public function fetchElement($name, $value, &$node, $control_name){
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('
			.jwHeaderClr { clear:both; height:0; line-height:0; border:none; float:none; background:none; padding:0; margin:0; }
			.jwHeaderContainer { clear:both; font-weight:bold; font-size:12px; color:#369; margin:12px 0 4px; padding:0; background:#d5e7fa; border-bottom:2px solid #96b0cb; float:left; width:100%; }
			.jwHeaderContent { padding:6px 8px; }
			/* Temp override for param label width */
			.form-horizontal .control-label {width:175px !important;padding-right:5px;text-align:left;}
		');
		return '<div class="jwHeaderContainer"><div class="jwHeaderContent">'.JText::_($value).'</div><div class="jwHeaderClr"></div></div>';
	}

	public function fetchTooltip($label, $description, &$node, $control_name, $name){
		return NULL;
	}

}

class JFormFieldHeader extends JWElementHeader {
	var $type = 'header';
}
