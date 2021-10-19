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

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die();

/**
 * @var plgContentJw_ts $this
 * @var string          $text
 * @var string          $template
 * @var string          $layout
 * @var boolean         $print
 */
?>
<div class="jwts_toggleControlContainer">
    <a href="#" class="jwts_toggleControl" title="<?php echo Text::_('PLG_CONTENT_JW_TS_CLICK_TO_OPEN'); ?>">
        <span class="jwts_togglePlus">+</span>
        <span class="jwts_toggleMinus">-</span>
        <span class="jwts_toggleControlTitle">{SLIDER_TITLE}</span>
        <span class="jwts_toggleControlNotice"><?php echo JText::_('PLG_CONTENT_JW_TS_CLICK_TO_COLLAPSE'); ?></span>
        <span class="jwts_clr"></span>
    </a>
</div>
<div class="jwts_toggleContent">
    <div class="jwts_content">
        {SLIDER_CONTENT}
    </div>
</div>
