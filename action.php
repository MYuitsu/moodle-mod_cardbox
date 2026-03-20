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
 * In this file, incoming AJAX request from  practice.js are handled.
 *
 * @package   mod_cardbox
 * @copyright 2019 RWTH Aachen (see README.md)
 * @author    Anna Heynkes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/cardbox/locallib.php');

$cmid = required_param('id', PARAM_INT);

list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'cardbox');
$cardbox = $DB->get_record('cardbox', array('id' => $cm->instance), '*', MUST_EXIST);

require_login($course, true, $cm);
require_sesskey();

$context = context_module::instance($cmid);

$action = required_param('action', PARAM_ALPHA); // ...'$action' determines what is to be done; see below.


/* * ********************** move card to the next box and return next card *********************** */

if ($action === 'updateandnext') {

    require_once($CFG->dirroot . '/mod/cardbox/locallib.php');
    require_once($CFG->dirroot . '/mod/cardbox/classes/output/practice.php');

    $cardid = required_param('cardid', PARAM_INT);
    $iscorrect = required_param('iscorrect', PARAM_INT);
    $next = required_param('next', PARAM_INT);
    $isrepetition = required_param('isrepetition', PARAM_INT);
    $case = optional_param('case', 1, PARAM_INT);
    $cardsleft = required_param('cardsleft', PARAM_INT);
    $correction = required_param('mode', PARAM_INT);

    $dataobject = $DB->get_record('cardbox_progress', array('userid' => $USER->id, 'card' => $cardid), $fields = '*', MUST_EXIST);
    if (empty($dataobject)) {
        echo json_encode(['status' => 'error', 'reason' => 'nocardboxentryfound']);
    }
    $lastposition = $dataobject->cardposition;

    $cardisdue = cardbox_is_card_due($dataobject);

    // 1. Update the card entry in the DB if
    // a) this is the first time the card was answered in this session and
    // b) the card is (over)due and/or was answered incorrectly.
    if ($isrepetition == 0 && ($cardisdue == true || $iscorrect == 0) ) {

        $success = cardbox_update_card_progress($dataobject, $iscorrect);

        if (empty($success)) {
            echo json_encode(['status' => 'error', 'reason' => 'failedtoupdate']);
        }

    }

    // 2. Get next card and pass it to javascript for rendering.
    if ($next != 0) {
        $renderer = $PAGE->get_renderer('mod_cardbox');
        // For flashcard mode, always show question (case 6), not answer
        if ($correction == 2) {
            $nextcase = 6; // Question flashcard
        } else {
            $nextcase = $case; // Keep the same case for other modes
        }
        $practice = new cardbox_practice($nextcase, $context, $next, $cardsleft, !$correction);
        $newdata = $practice->export_for_template($renderer);

        echo json_encode(['status' => 'success', 'lastposition' => $lastposition, 'newdata' => $newdata]);

    } else {
        echo json_encode(['status' => 'finished', 'lastposition' => $lastposition]);
    }

}

/* * ********************** Save performance at the end of a practice session *********************** */

if ($action === 'saveperformance') {

    global $DB, $USER;

    $countright = required_param('countright', PARAM_INT);
    $countwrong = required_param('countwrong', PARAM_INT);
    $starttime = required_param('starttime', PARAM_TEXT);
    $percentcorrect = 100 * $countright / ($countright + $countwrong);

    $data = new stdClass();
    $data->userid = $USER->id;
    $data->cardboxid = $cardbox->id;
    $data->timeofpractice = time();
    $data->numberofcards = $countright + $countwrong;
    $data->duration = $data->timeofpractice - $starttime;
    $data->percentcorrect = round($percentcorrect, 0, PHP_ROUND_HALF_UP);
    $success = $DB->insert_record('cardbox_statistics', $data);

    $event = \mod_cardbox\event\practice_session_ended::create(['context' => $context,  'objectid' => $cm->instance]);
    $event->trigger();

    if (empty($success)) {
        echo json_encode(['status' => 'error', 'reason' => 'failedtosaveperformance']);
    } else {
        echo json_encode(['status' => 'success']);
    }

}

/* ****************************************** Suggest answer for a card **************************************************** */


if ($action === 'savesuggestedanswer') {

    require_once($CFG->dirroot . '/mod/cardbox/classes/output/practice.php');

    $cardid = required_param('cardid', PARAM_INT);
    $case = optional_param('case', 1, PARAM_INT);
    $userinput = required_param('userinput', PARAM_TEXT);

    if (!(empty($userinput) || $userinput === "")) {
        cardbox_save_new_cardcontent($cardid, CARDBOX_CARDSIDE_ANSWER, CARDBOX_CONTENTTYPE_TEXT,
                                     $userinput, CARD_ANSWERSUGGESTION_INFORMATION);
    }

}

/* ****************************************** AI Hint (Text) **************************************************** */

if ($action === 'aihint') {

    require_capability('mod/cardbox:useai', $context);

    $questiontext = required_param('questiontext', PARAM_TEXT);
    $cardid = optional_param('cardid', 0, PARAM_INT);

    // Build personalized context from learner's error history.
    $historycontext = '';
    $hintlevel = 'new';
    if ($cardid > 0) {
        $progress = $DB->get_record('cardbox_progress', ['userid' => $USER->id, 'card' => $cardid]);
        if ($progress) {
            $repetitions = (int) $progress->repetitions;
            $cardposition = (int) $progress->cardposition;
            if ($cardposition == 1 && $repetitions >= 5) {
                $historycontext = get_string('ai_hint_history_struggling', 'cardbox', $repetitions);
                $hintlevel = 'struggling';
            } else if ($cardposition == 1 && $repetitions >= 2) {
                $historycontext = get_string('ai_hint_history_retry', 'cardbox', $repetitions);
                $hintlevel = 'retry';
            } else if ($cardposition >= 3) {
                $historycontext = get_string('ai_hint_history_known', 'cardbox');
                $hintlevel = 'known';
            }
        }
    }

    // Build the prompt.
    $promptdata = new stdClass();
    $promptdata->question = $questiontext;
    $promptdata->history = $historycontext;
    $prompt = get_string(
        $historycontext ? 'ai_hint_prompt_personalized' : 'ai_hint_prompt',
        'cardbox',
        $promptdata
    );

    // Call the AI subsystem.
    try {
        $aiaction = new \core_ai\aiactions\generate_text(
            contextid: $context->id,
            userid: $USER->id,
            prompttext: $prompt,
        );
        $manager = \core\di::get(\core_ai\manager::class);
        $response = $manager->process_action($aiaction);

        if ($response->get_success()) {
            $responsedata = $response->get_response_data();
            $generatedcontent = $responsedata['generatedcontent'] ?? '';
            echo json_encode(['status' => 'success', 'content' => format_text($generatedcontent, FORMAT_MARKDOWN), 'hint_level' => $hintlevel]);
        } else {
            echo json_encode(['status' => 'error', 'reason' => get_string('ai_error_noprovider', 'cardbox')]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'reason' => get_string('ai_error', 'cardbox')]);
    }
}

/* ****************************************** AI Hint (Image) **************************************************** */

if ($action === 'aihintimage') {

    require_capability('mod/cardbox:useai', $context);

    $questiontext = required_param('questiontext', PARAM_TEXT);

    // Build the prompt.
    $promptdata = new stdClass();
    $promptdata->question = $questiontext;
    $prompt = get_string('ai_hint_image_prompt', 'cardbox', $promptdata);

    // Read API key and model from Moodle's OpenAI provider config.
    // Bypasses Moodle AI subsystem to support newer models (gpt-image-1, gpt-image-1-mini, etc.)
    $apikey = get_config('aiprovider_openai', 'apikey');
    $model  = get_config('mod_cardbox', 'ai_image_model');
    if (empty($model)) {
        $model = 'gpt-image-1-mini'; // Default model.
    }

    if (empty($apikey)) {
        echo json_encode(['status' => 'error', 'reason' => get_string('ai_error_noprovider', 'cardbox')]);
    } else {
        try {
            $requestbody = json_encode([
                'model'   => $model,
                'prompt'  => $prompt,
                'n'       => 1,
                'size'    => '1024x1024',
                'output_format' => 'png',
            ]);

            $ch = curl_init('https://api.openai.com/v1/images/generations');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $requestbody,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $apikey,
                    'Content-Type: application/json',
                ],
                CURLOPT_TIMEOUT        => 60,
            ]);
            $rawresponse = curl_exec($ch);
            $httcode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($rawresponse === false || $httcode !== 200) {
                $apierror = $rawresponse ? (json_decode($rawresponse, true)['error']['message'] ?? $rawresponse) : 'curl error';
                echo json_encode(['status' => 'error', 'reason' => get_string('ai_error', 'cardbox'), 'debug' => $apierror]);
            } else {
                $decoded = json_decode($rawresponse, true);
                // gpt-image-1 / gpt-image-1-mini returns b64_json.
                $b64 = $decoded['data'][0]['b64_json'] ?? '';
                $url = $decoded['data'][0]['url']      ?? '';
                if (!empty($b64)) {
                    // Return as data URI so the browser can display it directly.
                    $imageurl = 'data:image/png;base64,' . $b64;
                } else {
                    $imageurl = $url;
                }
                if (empty($imageurl)) {
                    echo json_encode(['status' => 'error', 'reason' => get_string('ai_error', 'cardbox')]);
                } else {
                    echo json_encode(['status' => 'success', 'imageurl' => $imageurl]);
                }
            }
        } catch (\Throwable $e) {
            echo json_encode(['status' => 'error',
                'reason' => get_string('ai_error', 'cardbox'),
                'debug'  => $e->getMessage()]);
        }
    }
}

/* ****************************************** AI Card Generator — JLPT Level **************************************************** */

if ($action === 'aicardgenjlpt') {

    require_capability('mod/cardbox:submitcard', $context);
    require_once($CFG->dirroot . '/mod/cardbox/locallib.php');

    $level = required_param('level', PARAM_ALPHA); // N1 N2 N3 N4 N5
    $allowed = ['N1', 'N2', 'N3', 'N4', 'N5'];
    if (!in_array(strtoupper($level), $allowed)) {
        echo json_encode(['status' => 'error', 'reason' => 'Invalid level']);
        exit;
    }
    $level = strtoupper($level);
    $cardboxid = $cardbox->id;
    $prompt = get_string('ai_cardgen_jlpt_prompt', 'cardbox', $level);

    try {
        $aiaction = new \core_ai\aiactions\generate_text(
            contextid: $context->id,
            userid: $USER->id,
            prompttext: $prompt,
        );
        $manager = \core\di::get(\core_ai\manager::class);
        $response = $manager->process_action($aiaction);

        if ($response->get_success()) {
            $responsedata = $response->get_response_data();
            $generatedcontent = $responsedata['generatedcontent'] ?? '';
            $jsonstart = strpos($generatedcontent, '{');
            $jsonend   = strrpos($generatedcontent, '}');
            if ($jsonstart !== false && $jsonend !== false) {
                $jsonstr  = substr($generatedcontent, $jsonstart, $jsonend - $jsonstart + 1);
                $carddata = json_decode($jsonstr, true);
                if ($carddata && isset($carddata['question']) && isset($carddata['answer'])) {
                    $questiontext = clean_param(strip_tags($carddata['question']), PARAM_TEXT);
                    $answertext   = clean_param(strip_tags($carddata['answer']), PARAM_TEXT);
                    $ismanager = has_capability('mod/cardbox:approvecard', $context);
                    $cardid = cardbox_save_new_card($cardboxid, $context, $ismanager);
                    cardbox_save_new_cardcontent($cardid, CARDBOX_CARDSIDE_QUESTION, CARDBOX_CONTENTTYPE_TEXT, $questiontext, CARD_MAIN_INFORMATION);
                    cardbox_save_new_cardcontent($cardid, CARDBOX_CARDSIDE_ANSWER,   CARDBOX_CONTENTTYPE_TEXT, $answertext,   CARD_MAIN_INFORMATION);
                    echo json_encode([
                        'status'     => 'success',
                        'cardid'     => $cardid,
                        'question'   => $questiontext,
                        'answer'     => $answertext,
                        'jlpt_level' => $level,
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'reason' => get_string('ai_cardgen_error', 'cardbox')]);
                }
            } else {
                echo json_encode(['status' => 'error', 'reason' => get_string('ai_cardgen_error', 'cardbox')]);
            }
        } else {
            echo json_encode(['status' => 'error', 'reason' => get_string('ai_error_noprovider', 'cardbox')]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'reason' => get_string('ai_error', 'cardbox')]);
    }
}

/* ****************************************** AI Card Generator (Mass Import page) ********************************** */

if ($action === 'aicardgensave') {

    require_capability('mod/cardbox:submitcard', $context);
    require_once($CFG->dirroot . '/mod/cardbox/locallib.php');

    $topic = required_param('topic', PARAM_TEXT);
    $cardboxid = $cardbox->id;
    $prompt = get_string('ai_cardgen_prompt', 'cardbox', $topic);

    try {
        $aiaction = new \core_ai\aiactions\generate_text(
            contextid: $context->id,
            userid: $USER->id,
            prompttext: $prompt,
        );
        $manager = \core\di::get(\core_ai\manager::class);
        $response = $manager->process_action($aiaction);

        if ($response->get_success()) {
            $responsedata = $response->get_response_data();
            $generatedcontent = $responsedata['generatedcontent'] ?? '';
            // Extract JSON from AI response (AI may wrap in markdown fences)
            $jsonstart = strpos($generatedcontent, '{');
            $jsonend   = strrpos($generatedcontent, '}');
            if ($jsonstart !== false && $jsonend !== false) {
                $jsonstr  = substr($generatedcontent, $jsonstart, $jsonend - $jsonstart + 1);
                $carddata = json_decode($jsonstr, true);
                if ($carddata && isset($carddata['question']) && isset($carddata['answer'])) {
                    $questiontext = clean_param(strip_tags($carddata['question']), PARAM_TEXT);
                    $answertext   = clean_param(strip_tags($carddata['answer']), PARAM_TEXT);
                    // Save card to DB (approved because teacher is creating it)
                    $ismanager = has_capability('mod/cardbox:approvecard', $context);
                    $cardid = cardbox_save_new_card($cardboxid, $context, $ismanager);
                    cardbox_save_new_cardcontent($cardid, CARDBOX_CARDSIDE_QUESTION, CARDBOX_CONTENTTYPE_TEXT, $questiontext, CARD_MAIN_INFORMATION);
                    cardbox_save_new_cardcontent($cardid, CARDBOX_CARDSIDE_ANSWER,   CARDBOX_CONTENTTYPE_TEXT, $answertext,   CARD_MAIN_INFORMATION);
                    echo json_encode([
                        'status'   => 'success',
                        'cardid'   => $cardid,
                        'question' => $questiontext,
                        'answer'   => $answertext,
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'reason' => get_string('ai_cardgen_error', 'cardbox')]);
                }
            } else {
                echo json_encode(['status' => 'error', 'reason' => get_string('ai_cardgen_error', 'cardbox')]);
            }
        } else {
            echo json_encode(['status' => 'error', 'reason' => get_string('ai_error_noprovider', 'cardbox')]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'reason' => get_string('ai_error', 'cardbox')]);
    }
}

/* ****************************************** AI Explain Wrong Answer **************************************************** */

/* ****************************************** AI Course Suggest **************************************************** */

if ($action === 'aicoursesuggest') {

    require_capability('mod/cardbox:useai', $context);

    $countright = required_param('countright', PARAM_INT);
    $countwrong = required_param('countwrong', PARAM_INT);
    $wrongquestions = optional_param('wrongquestions', '', PARAM_TEXT);
    $topicname = optional_param('topicname', '', PARAM_TEXT);

    $total = $countright + $countwrong;
    $percent = $total > 0 ? round(100 * $countright / $total) : 0;

    // Build the prompt.
    $promptdata = new stdClass();
    $promptdata->total = $total;
    $promptdata->countright = $countright;
    $promptdata->countwrong = $countwrong;
    $promptdata->percent = $percent;
    $promptdata->wrongquestions = $wrongquestions;
    $promptdata->topicname = $topicname;
    $prompt = get_string('ai_course_suggest_prompt', 'cardbox', $promptdata);

    // Call the AI subsystem.
    try {
        $aiaction = new \core_ai\aiactions\generate_text(
            contextid: $context->id,
            userid: $USER->id,
            prompttext: $prompt,
        );
        $manager = \core\di::get(\core_ai\manager::class);
        $response = $manager->process_action($aiaction);

        if ($response->get_success()) {
            $responsedata = $response->get_response_data();
            $generatedcontent = $responsedata['generatedcontent'] ?? '';
            echo json_encode(['status' => 'success', 'content' => format_text($generatedcontent, FORMAT_MARKDOWN)]);
        } else {
            echo json_encode(['status' => 'error', 'reason' => get_string('ai_error_noprovider', 'cardbox')]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'reason' => get_string('ai_error', 'cardbox')]);
    }
}

/* ****************************************** AI Explain **************************************************** */

if ($action === 'aiexplain') {

    require_capability('mod/cardbox:useai', $context);

    $questiontext = required_param('questiontext', PARAM_TEXT);
    $correctanswer = required_param('correctanswer', PARAM_TEXT);
    $studentanswer = required_param('studentanswer', PARAM_TEXT);

    // Build the prompt.
    $promptdata = new stdClass();
    $promptdata->question = $questiontext;
    $promptdata->correctanswer = $correctanswer;
    $promptdata->studentanswer = $studentanswer;
    $prompt = get_string('ai_explain_prompt', 'cardbox', $promptdata);

    // Call the AI subsystem.
    try {
        $aiaction = new \core_ai\aiactions\generate_text(
            contextid: $context->id,
            userid: $USER->id,
            prompttext: $prompt,
        );
        $manager = \core\di::get(\core_ai\manager::class);
        $response = $manager->process_action($aiaction);

        if ($response->get_success()) {
            $responsedata = $response->get_response_data();
            $generatedcontent = $responsedata['generatedcontent'] ?? '';
            echo json_encode(['status' => 'success', 'content' => format_text($generatedcontent, FORMAT_MARKDOWN)]);
        } else {
            echo json_encode(['status' => 'error', 'reason' => get_string('ai_error_noprovider', 'cardbox')]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'reason' => get_string('ai_error', 'cardbox')]);
    }
}
