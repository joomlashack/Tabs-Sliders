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

use Alledia\Installer\AbstractScript;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\InstallerAdapter;

defined('_JEXEC') or die();

require_once 'library/Installer/include.php';

class Plgcontentjw_tsInstallerScript extends AbstractScript
{
    /**
     * @inheritDoc
     */
    protected function customPostFlight(string $type, InstallerAdapter $parent)
    {
        if ($type != 'uninstall') {
            $oldLanguageFiles = Folder::files(JPATH_ADMINISTRATOR . '/language', '\.plg_content_jw_ts\.', true, true);
            foreach ($oldLanguageFiles as $oldLanguageFile) {
                File::delete($oldLanguageFile);
            }
        }
    }
}
