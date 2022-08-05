<?php
/**
 * @package   Tabs & Sliders
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2006-2015 JoomlaWorks Ltd. All rights reserved.
 * @copyright 2016-2021 Joomlashack.com. All rights reserved
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of Tabs & Sliders.
 *
 * Tabs & Sliders is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tabs & Sliders is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tabs & Sliders.  If not, see <https://www.gnu.org/licenses/>.
 */

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die();

class plgContentJw_ts extends CMSPlugin
{
    /**
     * @var string
     */
    protected $plg_name = 'jw_ts';

    /**
     * @var string
     */
    protected $commentStart = "\n\n<!-- Tabs and Sliders Plugin start -->\n";

    /**
     * @var string
     */
    protected $commentEnd = "\n<!-- Tabs and Sliders Plugin end -->\n\n";

    /**
     * @inheritdoc
     */
    protected $autoloadLanguage = true;

    /**
     * @var CMSApplication
     */
    protected $app = null;

    /**
     * @var Document
     */
    protected $document = null;

    /**
     * @var bool
     */
    protected $supportLoaded = false;

    public function __construct(&$subject, $config = [])
    {
        parent::__construct($subject, $config);

        $this->document = Factory::getDocument();
    }

    /**
     * @param ?string $context
     * @param ?object $row
     *
     * @return void
     * @throws Exception
     */
    public function onContentPrepare(?string $context, ?object $row)
    {
        if (
            empty($row->text) === true
            || $this->isEnabled($row->text) !== true
        ) {
            return;
        }

        if ($this->document->getType() != 'html') {
            // Variable cleanups for K2
            // @TODO: Really?! Is this needed?

            $this->commentStart = '';
            $this->commentEnd   = '';

        } else {
            $this->loadSupport();
            $this->createTabs($row->text);
            $this->createSliders($row->text);
        }
    }

    /**
     * Load support js/css
     *
     * @return void
     */
    protected function loadSupport()
    {
        if ($this->supportLoaded == false) {
            HTMLHelper::_('jquery.framework');
            HTMLHelper::_('script', 'plg_content_jw_ts/behaviour.min.js', ['relative' => true]);

            $sliderAutoScroll = $this->params->get('sliderAutoScroll', 0);
            $template         = strtolower($this->params->get('template', 'default'));

            $this->document->addScriptDeclaration(
                sprintf('let jsts_sliderAutoScroll = %s;', $sliderAutoScroll ? 'true' : 'false')
            );

            $tabContentHeight = $this->params->get('tabContentHeight');
            if ($tabContentHeight) {
                $this->document->addStyleDeclaration(
                    sprintf(
                        '.jwts_tabberlive .jwts_tabbertab {height: %spx!important;overflow:auto!important;}',
                        $tabContentHeight
                    )
                );
            }

            HTMLHelper::_(
                'stylesheet',
                "plg_content_jw_ts/template/{$template}/template.min.css",
                ['relative' => true]
            );

            $color = $this->params->get('color', '#2184cd');
            $this->document->addStyleDeclaration(
                sprintf(
                    'ul.jwts_tabbernav li a {background-color: %1$s; border-color: %1$s;}'
                    . 'ul.jwts_tabbernav li a:hover { background-color: %1$s;}'
                    . '.jwts_tabberlive .jwts_tabbertab { border-color: %1$s;',
                    $color
                )
            );

            $this->supportLoaded = true;
        }
    }

    /**
     * @param string $text
     *
     * @return void
     */
    protected function createTabs(string &$text)
    {
        if (preg_match_all('/{tab=(.+?)}|{\/tabs}/', $text, $matches, PREG_SET_ORDER)) {
            $tabSetId   = 0;
            $tabId      = 0;
            $tabsClosed = 0;

            foreach ($matches as $match) {
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

                $text = preg_replace(
                    '/' . preg_quote($source, '/') . '/',
                    $replace,
                    $text,
                    1
                );
            }

            if ($tabsClosed < $tabSetId) {
                // Close the last tab and tabset
                $text .= '</div></div>' . $this->commentEnd;
            }
        }
    }

    /**
     * @param string $text
     *
     * @return void
     */
    protected function createSliders(string &$text)
    {
        if (strpos($text, '{slide') !== false) {
            $template = strtolower($this->params->get('template', 'default'));
            $layout   = PluginHelper::getLayoutPath('content', 'jw_ts', $template);
            $print    = (bool)$this->app->input->getCmd('print');

            ob_start();
            include $layout;
            $templateDisplay = $this->commentStart . ob_get_contents() . $this->commentEnd;
            ob_end_clean();

            $regex = "#(?:<p>)?\{slide[r]?=([^}]+)\}(?:</p>)?(.*?)(?:<p>)?\{/slide[r]?\}(?:</p>)?#s";
            if ($print) {
                $text = preg_replace($regex, '<h3>$1</h3>$2', $text);

            } else {
                $text = preg_replace(
                    $regex,
                    str_replace(
                        ['{SLIDER_TITLE}', '{SLIDER_CONTENT}'],
                        ['$1', '$2'],
                        $templateDisplay
                    ),
                    $text
                );
            }
        }

    }

    /**
     * @param ?string $text
     *
     * @return bool
     */
    protected function isEnabled(?string $text): bool
    {
        return $text && preg_match("#{tab=.+?}|{slide=.+?}|{slider=.+?}#s", $text);
    }
}
