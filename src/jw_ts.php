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
use Joomla\Registry\Registry;

defined('_JEXEC') or die('Restricted access');

class plgContentJw_ts extends JPlugin
{
    public $plg_name             = "jw_ts";
    public $plg_copyrights_start = "\n\n<!-- \"Tabs and Sliders\" Plugin starts here -->\n";
    public $plg_copyrights_end   = "\n<!-- \"Tabs and Sliders\" Plugin ends here -->\n\n";

    protected $autoloadLanguage = true;

    /**
     * @param string   $context
     * @param object   $row
     * @param Registry $params
     * @param int      $page
     *
     * @return void
     * @throws Exception
     */
    public function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        $app      = JFactory::getApplication();
        $document = JFactory::getDocument();

        if (!preg_match("#{tab=.+?}|{slide=.+?}|{slider=.+?}#s", $row->text)) {
            return;
        }

        if (JPluginHelper::isEnabled('content', $this->plg_name) == false) {
            return;
        }

        require_once __DIR__ . '/' . $this->plg_name . '/includes/helper.php';

        $template         = $this->params->get('template', 'Default');
        $tabContentHeight = $this->params->get('tabContentHeight', 0);
        $sliderAutoScroll = $this->params->get('sliderAutoScroll', 0);

        /* ----------------------------------- Render the output ----------------------------------- */
        // Variable cleanups for K2
        if ($document->getType() != 'html') {
            $this->plg_copyrights_start = '';
            $this->plg_copyrights_end   = '';
        }

        $helper              = new JWTSHelper;
        $pluginTemplatePaths = $helper->getTemplatePath($this->plg_name, $template);
        $documentType        = $document->getType();

        // html-only setups
        if ($documentType == 'html') {
            $pluginTemplateBaseUrl = $pluginTemplatePaths->get('http');

            JHtml::_('jquery.framework');
            JHtml::_('script', sprintf('/plugins/content/%1$s/%1$s/includes/js/behaviour.min.js', $this->plg_name));
            $document->addScriptDeclaration('var jsts_sliderAutoScroll = ' . $sliderAutoScroll . ';');

            JHtml::_('stylesheet', $pluginTemplateBaseUrl . '/css/template.css');

            if ($tabContentHeight) {
                $document->addStyleDeclaration(
                    sprintf(
                        '.jwts_tabberlive .jwts_tabbertab {height: %spx!important;overflow:auto!important;}',
                        $tabContentHeight
                    )
                );
            }
        }

        // --- Tabs ---
        if ($documentType == 'html') {
            $b    = 1;
            $tabs = array();

            if (preg_match_all('/{tab=.+?}{tab=.+?}|{tab=.+?}|{\/tabs}/', $row->text, $matches)) {
                foreach ($matches[0] as $match) {
                    if ($b == 1 && $match != "{/tabs}") {
                        $tabs[] = 1;
                        $b      = 2;

                    } elseif ($match == "{/tabs}") {
                        $tabs[] = 3;
                        $b      = 1;

                    } elseif (preg_match("/{tab=.+?}{tab=.+?}/", $match)) {
                        $tabs[] = 2;
                        $tabs[] = 1;
                        $b      = 2;

                    } else {
                        $tabs[] = 2;
                    }
                }
            }

            reset($tabs);
            $tabscount = 0;

            if (preg_match_all('/{tab=.+?}|{\/tabs}/', $row->text, $matches)) {
                $tabid = 1;

                foreach ($matches[0] as $match) {
                    if ($tabs[$tabscount] == 1) {
                        $match     = str_replace('{tab=', '', $match);
                        $match     = str_replace('}', '', $match);
                        $row->text = str_replace(
                            '{tab=' . $match . '}',
                            sprintf(
                                '%s<div class="jwts_tabber" id="jwts_tab%s"><div class="jwts_tabbertab" title="%3$s">'
                                . '<h2 class="jwts_heading"><a href="#" title="%3$s">%3$ss</a></h2>',
                                $this->plg_copyrights_start,
                                $tabid,
                                $match
                            ),
                            $row->text
                        );
                        $tabid++;

                    } elseif ($tabs[$tabscount] == 2) {
                        $match     = str_replace('{tab=', '', $match);
                        $match     = str_replace('}', '', $match);
                        $row->text = str_replace(
                            '{tab=' . $match . '}',
                            sprintf(
                                '</div>'
                                . '<div class="jwts_tabbertab" title="%1$s">'
                                . '<h2 class="jwts_heading"><a href="#" title="%1$s">%1$ss</a></h2>',
                                $match
                            ),
                            $row->text
                        );

                    } elseif ($tabs[$tabscount] == 3) {
                        $row->text = str_replace(
                            '{/tabs}',
                            sprintf(
                                '</div></div><div class="jwts_clr"></div>%s',
                                $this->plg_copyrights_end
                            ),
                            $row->text
                        );
                    }

                    $tabscount++;
                }
            }

        } else {
            if (preg_match_all('/{tab=.+?}/', $row->text, $matches)) {
                foreach ($matches[0] as $match) {
                    $match     = str_replace('{tab=', '', $match);
                    $match     = str_replace('}', '', $match);
                    $row->text = str_replace('{tab=' . $match . '}', '<h3>' . $match . '</h3>', $row->text);
                    $row->text = str_replace('{/tabs}', '', $row->text);
                }
            }
        }

        // --- Sliders ---
        $pluginBaseFolder = $pluginTemplatePaths->get('folder');

        ob_start();

        include $pluginBaseFolder . '/sliders.php';

        $getSlidersTemplate = $this->plg_copyrights_start . ob_get_contents() . $this->plg_copyrights_end;

        ob_end_clean();

        if (substr_count($row->text, '{slide') > 0) {
            $regex = '#(?:<p>)?\{slide[r]?=([^}]+)\}(?:</p>)?(.*?)(?:<p>)?\{/slide[r]?\}(?:</p>)?#s';

            if ($documentType == 'html' && !$app->input->getCmd('print')) {
                $row->text = preg_replace(
                    $regex,
                    str_replace(
                        array('{SLIDER_TITLE}', '{SLIDER_CONTENT}'),
                        array('$1', '$2'),
                        $getSlidersTemplate
                    ),
                    $row->text
                );

            } else {
                $row->text = preg_replace($regex, '<h3>$1</h3>$2', $row->text);
            }
        }
    }
}
