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
 * Define the accessibility block's class
 *
 * @package    block_accessibility
 * @author      Mark Johnson <mark.johnson@tauntons.ac.uk>
 * @copyright   2010 Tauntons College, UK
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/blocks/accessibility/lib.php');

/**
 * accessibility Block's class
 */
class block_accessibility extends block_base {

    /**
     * URL of the JavaScript file.
     */
    const JS_URL = '/blocks/accessibility/module.js';
    /**
     * URL of the CSS declaration file.
     */
    const CSS_URL = '/blocks/accessibility/userstyles.php';
    /**
     * URL of the fontsize file.
     */
    const FONTSIZE_URL = '/blocks/accessibility/changesize.php';
    /**
     * URL of the colour change file.
     */
    const COLOUR_URL = '/blocks/accessibility/changecolour.php';
    /**
     * URL of the database file.
     */
    const DB_URL = '/blocks/accessibility/database.php';

    function has_config() {
        return true;
    }

    /**
     * Set the title and include the stylesheet
     *
     * We need to include the stylesheet here rather than in {@see get_content()} since get_content
     * is sometimes called after $OUTPUT->heading(), e.g. such as /user/index.php where the middle
     * region is hard-coded.
     * However, /admin/plugins.php calls init() for each plugin after $OUTPUT->heading(), so the
     * sheet is not included at all on that page.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_accessibility');
    }

    /**
     * Called after init(). Here we have instance id so we can use config for specific instance
     * The function will include CSS declarations into Moodle Page
     * CSS declarations will be generated according to user settings and instance configuration
     *
     */
    public function specialization() {
        $instanceid = $this->instance->id;

        if (!$this->page->requires->is_head_done()) {

            // Link default/saved settings to a page.
            // Each block instance has it's own configuration form, so we need instance id.
            $cssurl = new moodle_url(self::CSS_URL, ["instance_id" => $instanceid]);
            $this->page->requires->css($cssurl);
        }
    }

    /**
     * instance_allow_multiple explicitly tells there cannot be multiple
     * block instance on the same page
     *
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Set where the block should be allowed to be added
     *
     * @return array
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Generate the contents for the block
     *
     * @return object Block contents and footer
     */
    public function get_content() {
        global $USER;
        global $FULLME;
        global $DB;
        global $PAGE;

        // Until Issue #63 is fixed, we don't want to display block for unauthenticated users.
        if (!isloggedin()) {
            return null;
        }

        if ($this->content !== null) {
            return $this->content;
        }

        // Get the current page url (redirection after action when no Javascript).
        $params = array('redirect' => $FULLME);

        // Set block services paths: changesize.php, changecolour.php and database.php.
        $sizeurl = new moodle_url(self::FONTSIZE_URL, $params);
        $coloururl = new moodle_url(self::COLOUR_URL, $params);

        $params['op'] = 'save';
        $params['size'] = true;
        $params['scheme'] = true;
        $dburl = new moodle_url(self::DB_URL, $params);

        // Initialization of increase_font, decrease_font and save button.
        $incattrs = array(
                'title' => get_string('inctext', 'block_accessibility'),
                'id' => "block_accessibility_inc",
                'href' => $sizeurl->out(false, array('op' => 'inc'))
        );
        $decattrs = array(
                'title' => get_string('dectext', 'block_accessibility'),
                'id' => "block_accessibility_dec",
                'href' => $sizeurl->out(false, array('op' => 'dec'))
        );
        $saveattrs = array(
                'title' => get_string('save', 'block_accessibility'),
                'id' => "block_accessibility_save",
                'href' => $dburl->out(false)
        );

        // Initialization of reset button.
        $resetattrs = array(
                'id' => 'block_accessibility_reset',
                'title' => get_string('resettext', 'block_accessibility'),
                'href' => $sizeurl->out(false, array('op' => 'reset'))
        );

        // If any of increase/decrease buttons reached maximum size, disable it.
        if (isset($USER->fontsize)) {
            if ($USER->fontsize == MIN_FONTSIZE) {
                $decattrs['class'] = 'disabled';
                unset($decattrs['href']);
            }
            if ($USER->fontsize == MAX_FONTSIZE) {
                $incattrs['class'] = 'disabled';
                unset($incattrs['href']);
            }
        } else {
            // Or disable reset button.
            $resetattrs['class'] = 'disabled';
        }

        // Initialization of scheme profiles buttons.
        $c1attrs = array(
                'title' => get_string('col1text', 'block_accessibility'),
                'id' => 'block_accessibility_colour1',
                'href' => $coloururl->out(false, array('scheme' => 1))
        );
        $c2attrs = array(
                'title' => get_string('col2text', 'block_accessibility'),
                'id' => 'block_accessibility_colour2',
                'href' => $coloururl->out(false, array('scheme' => 2)),

        );
        $c3attrs = array(
                'title' => get_string('col3text', 'block_accessibility'),
                'id' => 'block_accessibility_colour3',
                'href' => $coloururl->out(false, array('scheme' => 3))
        );
        $c4attrs = array(
                'title' => get_string('col4text', 'block_accessibility'),
                'id' => 'block_accessibility_colour4',
                'href' => $coloururl->out(false, array('scheme' => 4))
        );

        if (!isset($USER->colourscheme)) {
            $c1attrs['class'] = 'disabled';
        }

        // The display:inline-block CSS declaration is not applied to block's buttons because IE7 doesn't support, float is
        // used instead for IE7 only.
        $clearfix = '';
        if (preg_match('/(?i)msie [1-7]/', $_SERVER['HTTP_USER_AGENT'])) {
            $clearfix = html_writer::tag('div', '', array('style' => 'clear:both')); // Required for IE7.
        }

        // Render block HTML.
        $content = '';

        $strchar = get_string('char', 'block_accessibility');
        $savechar = "SAVE";
        $resetchar = "RESET";
        $divattrs = array('id' => 'accessibility_controls', 'class' => 'content');
        $listattrs = array('id' => 'block_accessibility_textresize', 'class' => 'button_row');

        $content .= html_writer::start_tag('div', $divattrs);
        $content .= html_writer::start_tag('div', $listattrs);
        $content .= html_writer::tag('span', 'Text size');
        $content .= html_writer::start_tag('ul');

        $content .= html_writer::start_tag('li', array('class' => 'access-button btn btn-outline-primary'));
        $content .= html_writer::tag('a', $strchar . '-', $decattrs);
        $content .= html_writer::end_tag('li');

        $content .= html_writer::start_tag('li', array('class' => 'access-button btn btn-outline-primary'));
        $content .= html_writer::tag('a', $strchar, $resetattrs);
        $content .= html_writer::end_tag('li');

        $content .= html_writer::start_tag('li', array('class' => 'access-button btn btn-outline-primary'));
        $content .= html_writer::tag('a', $strchar . '+', $incattrs);
        $content .= html_writer::end_tag('li');



        $content .= html_writer::end_tag('ul');

        $content .= $clearfix;

        // Colour change buttons.
        $content .= html_writer::tag('br','');
        $content .= html_writer::tag('span', 'Colour scheme');
        $content .= html_writer::start_tag('ul', array('id' => 'block_accessibility_changecolour'));

        $content .= html_writer::start_tag('li', array('class' => ' access-button btn'));
        $content .= html_writer::tag('a', $strchar, $c2attrs);
        $content .= html_writer::end_tag('li');

        $content .= html_writer::start_tag('li', array('class' => 'access-button btn'));
        $content .= html_writer::tag('a', $strchar, $c3attrs);
        $content .= html_writer::end_tag('li');

        $content .= html_writer::start_tag('li', array('class' => 'access-button btn'));
        $content .= html_writer::tag('a', $strchar, $c4attrs);
        $content .= html_writer::end_tag('li');

        $content .= html_writer::start_tag('li', array('class' => 'access-button float-right btn btn-outline-primary'));
        $content .= html_writer::tag('a', $savechar, $saveattrs);
        $content .= html_writer::end_tag('li');

        $content .= html_writer::start_tag('li', array('class' => 'access-button float-right btn btn-outline-primary'));
        $content .= html_writer::tag('a', $resetchar, $c1attrs);
        $content .= html_writer::end_tag('li');

        $content .= html_writer::end_tag('ul');

        $content .= $clearfix;

        // Display "settings saved" or etc.
        if (isset($USER->accessabilitymsg)) {
            $message = $USER->accessabilitymsg;
            unset($USER->accessabilitymsg);
        } else {
            $message = '';
        }
        $messageattrs = array('id' => 'block_accessibility_message', 'class' => 'clearfix');
        $content .= html_writer::tag('div', $message, $messageattrs);

        // Data to pass to module.js.
        $jsdata['instance_id'] = $this->instance->id;

        // Bionic Reading

        $this->page->requires->js('/blocks/accessibility/bionic-mode.js');
        $this->page->requires->js_init_call('initAPIkey', array('apikeyconfig'=>get_config('accessibility')->apikey));

        if ($PAGE->bodyid == "page-mod-book-view"){

            $settingscript = '                    function hideSettings() {
                if(document.getElementById("BRsettings") .style.display=="none") {document.getElementById("BRsettings") .style.display=""}
                else{document.getElementById("BRsettings") .style.display="none"}
            }';

            $content .= html_writer::start_tag('div', array('id'=>'allBR'));
            $content .= html_writer::tag('hr', '', array('id' => 'null'));

            $content .= html_writer::start_tag('div', array('style' => 'width:100%; text-align:right;'));
            $content .= html_writer::start_tag('button', array('style' => 'padding:15px; border:none; background-color:transparent;', 'title' => 'BR settings', 'type' => 'button', 'onclick' =>'hideSettings()'));
            $content .= html_writer::start_tag('i', array('class' => 'fa fa-gear'));
            $content .= html_writer::end_tag('i');
            $content .= html_writer::end_tag('button');
            $content .= html_writer::end_tag('div');

            $content .= html_writer::tag('p', 'Bionic Reading', array('style'=>'width:fit-content; position:relative; top:-45px;'));

            $content .= html_writer::start_tag('div', array('style' => 'width:100%;'));
            $content .= html_writer::start_tag('div', array('style' => 'margin:0 auto; display:none; position:relative; top:-20px;', 'id' => 'BRsettings', 'class'=>'mce'));
            $content .= html_writer::tag('script', $settingscript);

            $content .= html_writer::start_tag('label', array('class'=>'fixationLabel', 'data-toggle' =>'tooltip', 'data-placement'=>'top', 'title'=>'Fixation point'));
            $content .= html_writer::tag('strong', 'Bold points:');
            $content .= html_writer::end_tag('label');

            $content .= html_writer::tag('br', ' ');

            $content .= html_writer::start_tag('div', array('style' => 'width:100%;', 'margin'=>'0 auto;'));
            $content .= html_writer::start_tag('input', array('id'=>'rangeFixation', 'type' =>'range', 'min'=>'0', 'max'=>'10', 'value'=>'3', 'style'=>'display:block; margin:0 auto; vertical-align:middle;'));
            $content .= html_writer::end_tag('div');

            $content .= html_writer::tag('br', ' ');

            $content .= html_writer::start_tag('label', array('class'=>'saccadeLabel', 'data-toggle' =>'tooltip', 'data-placement'=>'top', 'title'=>'Saccade'));
            $content .= html_writer::tag('strong', 'Space between:');
            $content .= html_writer::end_tag('label');

            $content .= html_writer::tag('br', ' ');

            $content .= html_writer::start_tag('div', array('style' => 'width:100%;', 'margin'=>'0 auto;'));
            $content .= html_writer::tag('input', ' ', array('id'=>'rangeSaccade', 'type' =>'range', 'min'=>'0', 'max'=>'50', 'value'=>'15', 'style'=>'display:block; margin:0 auto; vertical-align:middle;'));
            $content .= html_writer::tag('br', ' ');
            $content .= html_writer::end_tag('div');

            $content .= html_writer::end_tag('div');

            $content .= html_writer::start_tag('div', array('style' => 'width:100%; position:relative; top:-20px;', 'class'=>'col-md-12 text-center'));
            $content .= html_writer::tag('button', 'Toggle Bionic Mode', array('id'=>'bionic-mode', 'type'=>'button', 'class'=>'btn btn-outline-primary', 'title'=>'Toggle Bionic Mode', 'style'=>'border-radius:3px;'));
            $content .= html_writer::end_tag('div');
            $content .= html_writer::tag('div',' ', array('style'=>'clear:both;'));
        }

        $content .= html_writer::end_tag('div');

        // Loader icon.
        $spanattrs = array('id' => 'loader-icon');
        $content .= html_writer::start_tag('span', $spanattrs);
        $content .= html_writer::end_tag('span');

        $content .= $clearfix;

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $content;

        // Keep in mind that dynamic AJAX mode cannot work properly with IE <= 8 (for now), so javascript will not even be loaded.
        if (!preg_match('/(?i)msie [1-8]/', $_SERVER['HTTP_USER_AGENT'])) {
            // Language strings to pass to module.js.
            $this->page->requires->string_for_js('saved', 'block_accessibility');
            $this->page->requires->string_for_js('jsnosave', 'block_accessibility');
            $this->page->requires->string_for_js('reset', 'block_accessibility');
            $this->page->requires->string_for_js('jsnosizereset', 'block_accessibility');
            $this->page->requires->string_for_js('jsnocolourreset', 'block_accessibility');
            $this->page->requires->string_for_js('jsnosize', 'block_accessibility');
            $this->page->requires->string_for_js('jsnocolour', 'block_accessibility');
            $this->page->requires->string_for_js('jsnosizereset', 'block_accessibility');
            $this->page->requires->string_for_js('jsnotloggedin', 'block_accessibility');
            $this->page->requires->string_for_js('launchtoolbar', 'block_accessibility');

            $jsmodule = array(
                    'name' => 'block_accessibility',
                    'fullpath' => self::JS_URL,
                    'requires' => array('base', 'node', 'stylesheet')
            );

            // Include js script and pass the arguments.
            $this->page->requires->js_init_call('M.block_accessibility.init', $jsdata, false, $jsmodule);
        }

        return $this->content;
    }

}
