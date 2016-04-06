<?php
/**
 * @version		3.0.0
 * @package		Tabs & Sliders (plugin)
 * @author    	JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2015 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JWTSHelper {

	// Path overrides for MVC templating
	function getTemplatePath($pluginName,$folder){
		$app = JFactory::getApplication();
		$p = new JObject;
		$pluginGroup = 'content';

		if(file_exists(JPATH_SITE.'/templates/'.$app->getTemplate().'/html/'.$pluginName.'/'.$folder)){
			$p->folder = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/'.$pluginName.'/'.$folder;
			$p->http = JURI::root(true).'/templates/'.$app->getTemplate().'/html/'.$pluginName.'/'.$folder;
		} else {
			$p->folder = JPATH_SITE.'/plugins/'.$pluginGroup.'/'.$pluginName.'/'.$pluginName.'/tmpl/'.$folder;
			$p->http = JURI::root(true).'/plugins/'.$pluginGroup.'/'.$pluginName.'/'.$pluginName.'/tmpl/'.$folder;
		}
		return $p;
	}

} // end class
