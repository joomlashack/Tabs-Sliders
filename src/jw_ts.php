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

use Joomla\Registry\Registry;

defined('_JEXEC') or die();

class plgContentJw_ts extends JPlugin
{
    /**
     * @var string
     */
    protected $plg_name = 'jw_ts';

    /**
     * @var string
     */
    protected $commentStart = "\n\n<!-- 'Tabs and Sliders' Plugin starts here -->\n";

    /**
     * @var string
     */
    protected $commentEnd = "\n<!-- 'Tabs and Sliders' Plugin ends here -->\n\n";

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

        $JWTSHelper          = new JWTSHelper;
        $documentType        = $document->getType();
        $pluginTemplatePaths = $JWTSHelper->getTemplatePath($this->plg_name, $template);

        /********* Render the output *********/
        // Variable cleanups for K2
        if ($documentType != 'html') {
            $this->commentStart = '';
            $this->commentEnd   = '';
        }

        if ($documentType == 'html') {
            JHtml::_('jquery.framework');
            JHtml::_(
                'script',
                sprintf('plugins/content/%s/%s/includes/js/behaviour.min.js', $this->plg_name, $this->plg_name)
            );

            $document->addScriptDeclaration('var jsts_sliderAutoScroll = ' . $sliderAutoScroll . ';');

            $pluginTemplateBaseUrl = $pluginTemplatePaths->get('http');
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

        /********* Tabs *********/
        if ($documentType == 'html') {
            if (preg_match_all('/{tab=(.+?)}|{\/tabs}/', $row->text, $matches, PREG_SET_ORDER)) {
                $tabSetId   = 0;
                $tabId      = 0;
                $tabsClosed = 0;

                foreach ($matches as $key => $match) {
                    $source = array_shift($match);
                    $title  = array_shift($match);

                    $replace = $tabId ? '</div>' : '';

                    if ($source == '{/tabs}') {
                        if ($tabSetId) {
                            $replace .= '</div>' . $this->commentEnd;
                            $tabId   = 0;
                            $tabsClosed++;
                        }

                    } else {
                        if ($tabId == 0) {
                            $tabSetId++;

                            $replace .= sprintf(
                                $this->commentStart . '<div class="jwts_tabber" id="jwts_tab%s">',
                                $tabSetId
                            );
                        }

                        $replace .= sprintf(
                            '<div class="jwts_tabbertab" title="%1$s">'
                            . '<h2 class="jwts_heading"><a href="#" title="%1$s">%1$s</a></h2>',
                            $title
                        );

                        $tabId++;
                    }

                    $row->text = preg_replace(
                        '/' . preg_quote($source, '/') . '/',
                        $replace,
                        $row->text,
                        1
                    );
                }

                if ($tabsClosed < $tabSetId) {
                    // Close the last tab and tabset
                    $row->text .= '</div></div>' . $this->commentEnd;
                }
            }
        }

        /********* Sliders *********/
        $pluginTemplateBasePath = $pluginTemplatePaths->get('folder');

        ob_start();
        include($pluginTemplateBasePath . '/sliders.php');
        $getSlidersTemplate = $this->commentStart . ob_get_contents() . $this->commentEnd;
        ob_end_clean();

        if (substr_count($row->text, '{slide') > 0) {
            $regex = "#(?:<p>)?\{slide[r]?=([^}]+)\}(?:</p>)?(.*?)(?:<p>)?\{/slide[r]?\}(?:</p>)?#s";
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
