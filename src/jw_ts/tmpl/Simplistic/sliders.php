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

// Please note that this is the override for each slider only! Any change you do in the html below will reflect on all slider panes across your entire site articles.

?>

<div class="jwts_toggleControlContainer">
    <a href="#" class="jwts_toggleControl" title="<?php echo JText::_('JW_PLG_TS_CLICK_TO_OPEN'); ?>">
        <span class="jwts_togglePlus">+</span>
        <span class="jwts_toggleMinus">-</span>
        <span class="jwts_toggleControlTitle">{SLIDER_TITLE}</span>
        <span class="jwts_toggleControlNotice"><?php echo JText::_('JW_PLG_TS_CLICK_TO_COLLAPSE'); ?></span>
        <span class="jwts_clr"></span>
    </a>
</div>
<div class="jwts_toggleContent">
    <div class="jwts_content">
        {SLIDER_CONTENT}
    </div>
</div>
