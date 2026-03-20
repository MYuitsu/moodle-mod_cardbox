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
 * Bulk user upload forms
 *
 * @package    mod_cardbox
 * @copyright  2021 Amrita Deb, RWTH Aachen University <Deb@itc.rwth-aachen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_cardbox\output;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot . '/user/editlib.php');

class massimport_form extends \moodleform {
    public function definition () {
        $mform = $this->_form;
        $cbxdata = $this->_customdata;

        $mform->addElement('hidden', 'id'); // Course module id.
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $cbxdata['cmid']);

        $mform->addElement('hidden', 'action');
        $mform->setType('action', PARAM_ALPHANUM);
        $mform->setDefault('action', 'massimport');

        // AI Card Generator section.
        $mform->addElement('html',
            '<div class="cardbox-aicardgen-section">'
            . '<div class="cardbox-aicardgen-label">✨ ' . get_string('ai_cardgen_label', 'cardbox') . '</div>'
            . '<p style="color:#555; font-size:0.9em; margin-bottom:8px">' . get_string('ai_cardgen_massdesc', 'cardbox') . '</p>'
            // JLPT quick buttons
            . '<div class="cardbox-aicardgen-jlpt-row">'
            . '<span style="font-size:0.85em; color:#555; margin-right:8px; line-height:2">' . get_string('ai_cardgen_jlpt_label', 'cardbox') . '</span>'
            . '<button type="button" class="cardbox-jlpt-btn" data-level="N5">N5</button>'
            . '<button type="button" class="cardbox-jlpt-btn" data-level="N4">N4</button>'
            . '<button type="button" class="cardbox-jlpt-btn" data-level="N3">N3</button>'
            . '<button type="button" class="cardbox-jlpt-btn" data-level="N2">N2</button>'
            . '<button type="button" class="cardbox-jlpt-btn" data-level="N1">N1</button>'
            . '</div>'
            // Manual input row
            . '<div class="cardbox-aicardgen-row" style="margin-top:10px">'
            . '<input type="text" id="cardbox-aicardgen-input" class="cardbox-aicardgen-input"'
            .   ' placeholder="' . get_string('ai_cardgen_placeholder', 'cardbox') . '" />'
            . '<button type="button" id="cardbox-aicardgen-btn" class="btn btn-primary btn-sm">'
            .   '✨ ' . get_string('ai_cardgen_btn', 'cardbox')
            . '</button>'
            . '</div>'
            . '<div id="cardbox-aicardgen-status" class="cardbox-aicardgen-status"></div>'
            . '<ul id="cardbox-aicardgen-list" style="margin-top:10px; padding-left:18px; font-size:0.88em; color:#333;"></ul>'
            . '</div>'
            . '<hr style="margin: 16px 0">'
        );

        $singleurl = new \moodle_url('example_singleans.csv');
        $singlelink = \html_writer::link($singleurl, 'example_singleans.csv');
        $mform->addElement('static', 'examplesinglecsv', get_string('examplesinglecsv', 'cardbox'), $singlelink);

        $multiurl = new \moodle_url('example_multians.csv');
        $multilink = \html_writer::link($multiurl, 'example_multians.csv');
        $mform->addElement('static', 'examplemulticsv', get_string('examplemulticsv', 'cardbox'), $multilink);

        $mform->addElement('filepicker', 'cardimportfile', get_string('file'));
        $mform->addRule('cardimportfile', null, 'required');

        $choices = \csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'tool_uploaduser'), $choices);
        if (array_key_exists('cfg', $choices)) {
            $mform->setDefault('delimiter_name', 'cfg');
        } else if (get_string('listsep', 'langconfig') == ';') {
            $mform->setDefault('delimiter_name', 'semicolon');
        } else {
            $mform->setDefault('delimiter_name', 'comma');
        }

        $choices = \core_text::get_encodings();
        $mform->addElement('select', 'encoding', get_string('encoding', 'tool_uploaduser'), $choices);
        $mform->setDefault('encoding', 'UTF-8');
        $this->add_action_buttons(false, get_string('massimport', 'cardbox'));
    }
}
