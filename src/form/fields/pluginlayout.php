<?php

/**
 * @package   Tabs & Sliders
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2021-2026 Joomlashack. All rights reserved
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

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Filesystem\Folder;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die();
// phpcs:enable PSR1.Files.SideEffects
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

if (class_exists(ListField::class) == false) {
    FormHelper::loadFieldClass('List');
    class_alias(JFormFieldList::class, ListField::class);
}

class JwtsFormFieldPluginlayout extends ListField
{
    protected $layout = 'joomla.form.field.list-fancy-select';

    /**
     * @inheritDoc
     */
    protected function getInput()
    {
        // for backward compatibility
        $this->value = strtolower($this->value);

        return parent::getInput();
    }

    /**
     * @inheritDoc
     */
    protected function getOptions()
    {
        $path        = dirname(PluginHelper::getLayoutPath('content', 'jw_ts'));
        $coreLayouts = Folder::files($path, '\.php$');

        $options = [];
        foreach ($coreLayouts as $coreLayout) {
            $value = basename($coreLayout, '.php');

            $options[] = HTMLHelper::_('select.option', $value, ucwords(str_replace('-', ' ', $value)));
        }

        uasort($options, function ($a, $b) {
            return $a->text == $b->text
                ? 0
                : ($a->text < $b->text ? -1 : 1);
        });

        return array_merge(parent::getOptions(), $options);
    }
}
