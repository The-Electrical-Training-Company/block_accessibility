<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Define metadata for Accessibility block
 *
 * This file is the cornerstone of the block - when the page loads, it
 * checks if the user has a custom settings for the font size and colour
 * scheme (either in the session or the database) and creates a stylesheet
 * to override the standard styles with this setting.
 *
 * @package   block_accessibility
 * @copyright 2021 Brickfield Education Labs <https://www.brickfield.ie/>
 * @author    Jay Churchward <jay@brickfieldlabs.ie>
 * @author    Mark Johnson
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$settings->add(new admin_setting_heading('accessibilityheader',
                                         get_string('settingsheader', 'block_accessibility'),
                                         get_string('settingsheaderdesc', 'block_accessibility')));

$settings->add(new admin_setting_configtext('accessibility/apikey',
                                         get_string('apilabel', 'block_accessibility'),
                                         get_string('apidesc', 'block_accessibility'),
                                         'api key'));

$settings->add(new admin_setting_configcolourpicker('accessibility/bg2',
                                         get_string('bg2label', 'block_accessibility'),
                                         get_string('bgdesc', 'block_accessibility'),
                                         '#FAFAC8'));

$settings->add(new admin_setting_configcolourpicker('accessibility/fg2',
                                         get_string('fg2label', 'block_accessibility'),
                                         get_string('fgdesc', 'block_accessibility'),
                                         '#000000'));

$settings->add(new admin_setting_configcolourpicker('accessibility/bg3',
                                         get_string('bg3label', 'block_accessibility'),
                                         get_string('bgdesc', 'block_accessibility'),
                                         '#EDD1B0'));

$settings->add(new admin_setting_configcolourpicker('accessibility/fg3',
                                         get_string('fg3label', 'block_accessibility'),
                                         get_string('fgdesc', 'block_accessibility'),
                                         '#000000'));

$settings->add(new admin_setting_configcolourpicker('accessibility/bg4',
                                         get_string('bg4label', 'block_accessibility'),
                                         get_string('bgdesc', 'block_accessibility'),
                                         '#B987DC'));

$settings->add(new admin_setting_configcolourpicker('accessibility/fg4',
                                         get_string('fg4label', 'block_accessibility'),
                                         get_string('fgdesc', 'block_accessibility'),
                                         '#000000'));