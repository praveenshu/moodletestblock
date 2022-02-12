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
 * Class containing data for testblock block.
 *
 * @package    block_testblock
 * @author Praveenshukla
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_testblock\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use context_system;
use completion_info;
use moodle_url;
/**
 * Class containing data for testblock block.
 *
 */
class testblock implements renderable, templatable {


    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $USER, $OUTPUT, $CFG, $COURSE;
        require_once($CFG->dirroot.'/course/lib.php');
        $data = new \stdClass();
        $modinfo = get_fast_modinfo($COURSE);
        $completioninfo = new completion_info($COURSE);
        $modfullnames = [];
        foreach ($modinfo->cms as $key => $cm) {
            $mod = [];
            if (!$cm->uservisible) {
                continue;
            }
            $completion = $completioninfo->is_enabled($cm);
            if ($completion != COMPLETION_DISABLED) {
                $completiondata = $completioninfo->get_data($cm, true, $USER->id);
                if ($completiondata->completionstate) {
                    $mod['completionstatus'] = get_string('complete', 'block_testblock');
                }
            }
            $mod['modid'] = $cm->id;
            $mod['mod_url'] = new moodle_url($cm->url);
            $mod['modname'] = $cm->name;
            $mod['modcreated'] = date('d-M-Y', $cm->added);
            $modfullnames['modules'][] = $mod;

        }
        return $modfullnames;
    }
}
