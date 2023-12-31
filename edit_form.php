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
 * External class for declaring webservices.
 *
 * @package    block_accessibility
 * @copyright  2020 onward Brickfield Education Labs Ltd, https://www.brickfield.ie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_accessibility_edit_form extends block_edit_form {

    /**
     * Defines the autosave configuration.
     */
    const CNF_AUTOSAVE = 'config_autosave';
    /**
     * Defines the foreground configuration.
     */
    const CNF_FG = 'config_fg';
    /**
     * Defines the background configuration.
     */
    const CNF_BG = 'config_bg';
    /**
     * Regex for colour codes.
     */
    const RE_COLOUR = '/^#[a-f0-9]{6}$/i';

    /**
     * Sets the definitions for the display.
     *
     * @param object $mform
     * @return void
     */
    protected function specific_definition($mform) {

        // Load default colours.
        global $CFG;
        require_once($CFG->dirroot . '/blocks/accessibility/defaults.php');

        // Colour schemes.
        for ($i = 2; $i < 5; $i++) {  // This is how many declarations we defined in defaults.php.
            // Get previously saved configuration.
            $form = $this->block->config; // Or cast it to (array) and get properties like with [].
            $fg = str_replace('config_', '', self::CNF_FG);
            $bg = str_replace('config_', '', self::CNF_BG);
            $fgcolour = isset($form->{$fg . $i}) ? $form->{$fg . $i} : $defaults['fg' . $i];
            $bgcolour = isset($form->{$bg . $i}) ? $form->{$bg . $i} : $defaults['bg' . $i];

            // Display scheme example and identifier number of a scheme.
            $mform->addElement('html', '
			<div class="fitem" style="padding:10px 0 8px">
				<div class="fitemtitle"></div>
				<div class="felement">
					<span style="padding:2px 8px; color:' . $fgcolour . '; border:1px solid ' . $fgcolour . '; background:' .
                    $bgcolour . ' !important">A</span>
					Colour scheme #' . $i . '
				</div>
			</div>');

            // Foreground colour.
            $id = self::CNF_FG . $i;
            $mform->addElement('text', $id, get_string(self::CNF_FG, 'block_accessibility'));
            $mform->setDefault($id, $defaults['fg' . $i]);
            $mform->setType($id, PARAM_TEXT);
            $mform->addHelpButton($id, self::CNF_FG, 'block_accessibility');
            $mform->addRule($id, get_string('color_input_error', 'block_accessibility'), 'regex', self::RE_COLOUR, 'server', false,
                    false);

            // Background colour.
            $id = self::CNF_BG . $i;
            $mform->addElement('text', $id, get_string(self::CNF_BG, 'block_accessibility'));
            $mform->setDefault($id, $defaults['bg' . $i]);
            $mform->setType($id, PARAM_TEXT);
            $mform->addHelpButton($id, self::CNF_BG, 'block_accessibility');
            $mform->addRule($id, get_string('color_input_error', 'block_accessibility'), 'regex', self::RE_COLOUR, 'server', false,
                    false);

        }

    }
}
