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
 * Declares the default settings of the page.
 *
 * @package   block_accessibility
 * @copyright Copyright &copy; 2009 Taunton's College
 * @author    Mark Johnson
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
function has_config() {
    return true;
}
$defaults = array(
    // The fg1 and bg1 would be reset/default colour - do not define it.
        'bg2' => get_config('accessibility')->bg2,
        'fg2' => get_config('accessibility')->fg2,
        'bg3' => get_config('accessibility')->bg3,
        'fg3' => get_config('accessibility')->fg3,
        'bg4' => get_config('accessibility')->bg4,
        'fg4' => get_config('accessibility')->fg4,
);
