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
 * @package   mod_cardbox
 * @copyright 2019 RWTH Aachen (see README.md)
 * @author    Anna Heynkes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
define ('QUESTION_SELFCHECK', 1);
define ('QUESTION_AUTOCHECK', 2);
define ('ANSWER_SELFCHECK', 3);
define ('ANSWER_AUTOCHECK', 4);
define('SUGGEST_ANSWER', 5);
define('QUESTION_FLASHCARD', 6);
define('ANSWER_FLASHCARD', 7);
define ('TOPIC_IS_NULL', -1);

class cardbox_practice implements \renderable, \templatable {

    private $topic;
    private $question = array('images' => array(), 'sounds' => array(), 'texts' => array());
    private $answer = array('images' => array(), 'sounds' => array(), 'texts' => array());
    private $case;
    private $case1 = false; // Question_selfcheck.
    private $case2 = false; // Question_autocheck.
    private $case3 = false; // Answer_selfcheck.
    private $case4 = false; // Answer_autocheck.
    private $case5 = false; // Suggest_answer.
    private $case6 = false; // Question_flashcard.
    private $case7 = false; // Answer_flashcard.
    private $inputfields = array();
    private $questioncontext = null;
    private $answercontext = null;
    private $necessaryanswers = 0;
    private $casesensitive = 0;
    private $answercount = 0;
    private $cardsleft;
    private $render_ans = array();
    private $flashcardoptions = array();
    private $deck;
    private $deckimgurl;

    /**
     * Function builds the view of a flashcard during practice.
     *
     * @global type $CFG
     * @param type $context
     * @param obj $cardbox
     */
    public function __construct($case, $context, $cardid, $cardsleft, $disableautocorrect) {
        global $DB;
        switch ($case) {
            case QUESTION_SELFCHECK:
                $casename = 'case'.QUESTION_SELFCHECK;
                $this->$casename = true;
                $this->case = QUESTION_SELFCHECK;
                break;
            case QUESTION_AUTOCHECK:
                $cardstatus = $DB->get_record('cardbox_cards', array('id' => $cardid));
                if ($cardstatus->disableautocorrect) {
                    $casename = 'case'.QUESTION_SELFCHECK;
                    $this->$casename = true;
                    $this->case = QUESTION_SELFCHECK;
                } else {
                    $casename = 'case'.QUESTION_AUTOCHECK;
                    $this->$casename = true;
                    $this->case = QUESTION_AUTOCHECK;
                }
                break;
            case ANSWER_SELFCHECK:
                $casename = 'case'.ANSWER_SELFCHECK;
                $this->$casename = true;
                $this->case = ANSWER_SELFCHECK;
                break;
            case ANSWER_AUTOCHECK:
                $cardstatus = $DB->get_record('cardbox_cards', array('id' => $cardid));
                if ($cardstatus->disableautocorrect) {
                    $casename = 'case'.ANSWER_SELFCHECK;
                    $this->$casename = true;
                    $this->case = ANSWER_SELFCHECK;
                } else {
                    $casename = 'case'.ANSWER_AUTOCHECK;
                    $this->$casename = true;
                    $this->case = ANSWER_AUTOCHECK;
                }
                break;
            case SUGGEST_ANSWER:
                $casename = 'case'.SUGGEST_ANSWER;
                $this->$casename = true;
                $this->case = SUGGEST_ANSWER;
                break;
            case QUESTION_FLASHCARD:
                $casename = 'case'.QUESTION_FLASHCARD;
                $this->$casename = true;
                $this->case = QUESTION_FLASHCARD;
                break;
            case ANSWER_FLASHCARD:
                $casename = 'case'.ANSWER_FLASHCARD;
                $this->$casename = true;
                $this->case = ANSWER_FLASHCARD;
                break;
            default:
                // TODO Error handling.
        }
        $this->cardsleft = $cardsleft;
        $this->cardbox_getcarddeck($cardid);
        $this->cardbox_prepare_cardcontents($context, $cardid, $disableautocorrect);

    }
    public function cardbox_prepare_cardcontents($context, $cardid, $disableautocorrect) {

        global $CFG, $DB;
        require_once($CFG->dirroot . '/mod/cardbox/locallib.php');
        require_once('model/cardbox.class.php');

        $contents = cardbox_cardboxmodel::cardbox_get_card_contents($cardid);

        $topic = cardbox_get_topic($cardid);
        if ($topic == TOPIC_IS_NULL) {
            $this->topic = get_string('nulltopic', 'cardbox');
        } else {
            $this->topic = $DB->get_field('cardbox_topics', 'topicname', array('id' => $topic));
        }

        $this->casesensitive = cardbox_cardboxmodel::cardbox_get_casesensitive($cardid);

        $fs = get_file_storage();
        $solutioncount = 0;
        foreach ($contents as $content) {

            if ($content->area == CARD_CONTEXT_INFORMATION && $content->cardside == CARDBOX_CARDSIDE_QUESTION) {
                // Check for question context.
                $this->questioncontext = format_text($content->content);

            } else if ($content->area == CARD_CONTEXT_INFORMATION && $content->cardside == CARDBOX_CARDSIDE_ANSWER) {
                // Check for answer context.
                $this->answercontext = format_text($content->content);

            } else if ($content->contenttype == CARDBOX_CONTENTTYPE_IMAGE) { // Check for images.

                $downloadurl = cardbox_get_download_url($context, $content->id, $content->content);
                if ($content->cardside == CARDBOX_CARDSIDE_QUESTION) {
                    if ($content->area == CARD_IMAGEDESCRIPTION_INFORMATION) {
                        $this->question['images'][0] += array('imagealt' => $content->content);
                        continue;
                    }
                    $this->question['images'][] = array('imagesrc' => $downloadurl);
                } else {
                    $this->answer['images'][] = array('imagesrc' => $downloadurl);
                }

            } else if ($content->contenttype == CARDBOX_CONTENTTYPE_AUDIO) { // Audio files.

                $downloadurl = cardbox_get_download_url($context, $content->id, $content->content);
                if ($content->cardside == CARDBOX_CARDSIDE_QUESTION) {
                    $this->question['sounds'][] = array('soundsrc' => $downloadurl);
                } else {
                    $this->answer['sounds'][] = array('soundsrc' => $downloadurl);
                }

            } else if ($content->cardside == CARDBOX_CARDSIDE_QUESTION) {

                $content->content = format_text($content->content);

                $this->question['texts'][] = array('text' => $content->content, 'puretext' => $content->content);

            } else {

                $content->content = format_text($content->content, FORMAT_MOODLE, ['para' => false]);
                $this->render_ans[] = array('renderans' => $content->content);
                if ($disableautocorrect) {
                    // We want the bare text for answer comparison, no HTML tags.
                    // Otherwise autocorrection doesn't work.
                    $content->content = strip_tags($content->content);
                    $content->content = trim($content->content);
                }

                if ($content->area === "3") {
                    continue;
                }
                $this->answer['texts'][] = array('text' => $content->content, 'puretext' => $content->content);
                $solutioncount++;
                $this->inputfields[] = array('number' => $solutioncount);
            }
        }
        $this->answercount = $solutioncount;
        $this->necessaryanswers = $DB->get_field('cardbox_cards', 'necessaryanswers', array('id' => $cardid), IGNORE_MISSING);
        if ($this->necessaryanswers != 0) {
            $this->inputfields = ['number' => '1'];
        }
        
        // Generate flashcard options if in flashcard mode
        if ($this->case == QUESTION_FLASHCARD || $this->case == ANSWER_FLASHCARD) {
            $this->generate_flashcard_options($cardid);
        }
    }
    
    /**
     * Generate 6 multiple choice options for flashcard mode (1 correct + 5 wrong)
     * Wrong answers are randomly selected from other cards in the same topic
     * @param int $cardid
     */
    private function generate_flashcard_options($cardid) {
        global $DB;
        
        if (empty($this->answer['texts'])) {
            error_log("=== FLASHCARD DEBUG: No answer texts found ===");
            return;
        }
        
        // Get the correct answer and strip HTML tags
        $correctanswer = $this->answer['texts'][0]['puretext'];
        $correctanswer = strip_tags($correctanswer);
        $correctanswer = trim($correctanswer);
        
        error_log("=== FLASHCARD DEBUG START ===");
        error_log("Card ID: " . $cardid);
        error_log("Correct Answer: " . $correctanswer);
        
        // Add correct answer with context
        $this->flashcardoptions[] = array(
            'answer' => $correctanswer,
            'iscorrect' => 1,
            'context' => $this->answercontext ? $this->answercontext : ''
        );
        
        // Get current card's topic
        $currentcard = $DB->get_record('cardbox_cards', array('id' => $cardid), 'cardbox, topic');
        $topicid = $currentcard->topic;
        
        error_log("Topic ID: " . $topicid);
        
        // Get wrong answers from OTHER CARDS in the SAME topic (regardless of cardbox)
        // cardside = 1 (answer side), area = 0 (main content, not context)
        $sql = "SELECT DISTINCT cc.id as cardid, cct.id as contentid, cct.content
                FROM {cardbox_cards} cc
                JOIN {cardbox_cardcontents} cct ON cc.id = cct.card
                WHERE cc.topic = :topicid
                AND cc.id != :cardid
                AND cc.approved = 1
                AND cct.cardside = 1
                AND cct.area = 0";
        
        $wronganswers = $DB->get_records_sql($sql, array(
            'topicid' => $topicid, 
            'cardid' => $cardid
        ));
        
        error_log("Wrong answers from DB: " . count($wronganswers));
        foreach ($wronganswers as $wa) {
            error_log("  - Card " . $wa->cardid . ": " . substr($wa->content, 0, 50));
        }
        
        // Shuffle the results in PHP instead of SQL RAND()
        if (!empty($wronganswers)) {
            $wronganswers = array_values($wronganswers); // Reset array keys
            shuffle($wronganswers);
            error_log("After shuffle: " . count($wronganswers) . " options");
        }
        
        $wrongcount = 0;
        $addedanswers = array($correctanswer); // Track added answers to avoid duplicates
        
        foreach ($wronganswers as $wrong) {
            if ($wrongcount >= 5) {
                break; // We need exactly 5 wrong answers
            }
            
            $wrongtext = strip_tags($wrong->content);
            $wrongtext = trim($wrongtext);
            
            error_log("  Checking: " . $wrongtext);
            
            // Make sure it's different from correct answer, not empty, and not duplicate
            if (!in_array($wrongtext, $addedanswers) && !empty($wrongtext) && strlen($wrongtext) > 0) {
                // Get context for this wrong answer (area = 1 for context)
                $wrongcontext = $DB->get_field_sql(
                    "SELECT content FROM {cardbox_cardcontents} 
                     WHERE card = :cardid AND cardside = 1 AND area = 1",
                    array('cardid' => $wrong->cardid)
                );
                
                $this->flashcardoptions[] = array(
                    'answer' => $wrongtext,
                    'iscorrect' => 0,
                    'context' => $wrongcontext ? format_text($wrongcontext) : ''
                );
                $addedanswers[] = $wrongtext;
                $wrongcount++;
                error_log("  ✓ Added: " . $wrongtext . " (count: $wrongcount)");
            } else {
                error_log("  ✗ Skipped: duplicate or empty");
            }
        }
        
        error_log("Total wrong answers added: " . $wrongcount);
        
        // If still not enough, add generic placeholders
        while ($wrongcount < 5) {
            $genericwrong = $this->generate_generic_wrong_answer($correctanswer, $wrongcount);
            if ($genericwrong && !in_array($genericwrong, $addedanswers)) {
                $this->flashcardoptions[] = array(
                    'answer' => $genericwrong,
                    'iscorrect' => 0,
                    'context' => ''
                );
                $addedanswers[] = $genericwrong;
                $wrongcount++;
                error_log("  + Added placeholder: " . $genericwrong);
            } else {
                break;
            }
        }
        
        error_log("Final options count: " . count($this->flashcardoptions));
        error_log("=== FLASHCARD DEBUG END ===");
        
        // Shuffle the options so correct answer is not always first
        shuffle($this->flashcardoptions);
    }
    
    /**
     * Generate a generic wrong answer when not enough real ones available
     * @param string $correctanswer
     * @param int $index
     * @return string|null
     */
    private function generate_generic_wrong_answer($correctanswer, $index) {
        // This is a fallback - in practice, there should usually be enough cards
        // Generic options based on common patterns
        $generic = array(
            get_string('option_placeholder_1', 'cardbox'),
            get_string('option_placeholder_2', 'cardbox'),
            get_string('option_placeholder_3', 'cardbox'),
            get_string('option_placeholder_4', 'cardbox'),
            get_string('option_placeholder_5', 'cardbox'),
        );
        
        if (isset($generic[$index])) {
            return $generic[$index];
        }
        
        return null;
    }
    
    public function cardbox_getcarddeck(int $cardid) {
        global $CFG, $DB, $USER;
        if ($DB->record_exists('cardbox_progress', ['userid' => $USER->id, 'card' => $cardid])) {
            $this->deck = $DB->get_field('cardbox_progress', 'cardposition',
                                         ['userid' => $USER->id, 'card' => $cardid], IGNORE_MISSING);
            if ($this->deck == 0) {
                $this->deckimgurl = $CFG->wwwroot . '/mod/cardbox/pix/new.svg';
            } else if ($this->deck == 6) {
                $this->deckdeckimgurlimg = $CFG->wwwroot . '/mod/cardbox/pix/mastered.svg';
            } else {
                $this->deckimgurl = $CFG->wwwroot . '/mod/cardbox/pix/'.$this->deck.'.svg';
            }
        } else {
            $this->deck = null;
            $this->deckimgurl = $CFG->wwwroot . '/mod/cardbox/pix/new.svg';
        }

    }
    public function export_for_template(\renderer_base $output) {

        $data = array();
        $data['topic'] = get_string('choosetopic', 'cardbox').' : '.$this->topic;
        $data['renderanswer'] = $this->render_ans;
        $data['question'] = $this->question;
        $data['answer'] = $this->answer;
        $data['case1'] = $this->case1;
        $data['case2'] = $this->case2;
        $data['case3'] = $this->case3;
        $data['case4'] = $this->case4;
        $data['case5'] = $this->case5;
        $data['case6'] = $this->case6;
        $data['case7'] = $this->case7;
        $data['inputfields'] = $this->inputfields;
        $data['flashcardoptions'] = $this->flashcardoptions;
        $data['contextquestion'] = $this->questioncontext;
        $data['contextanswer'] = $this->answercontext;
        $data['necessaryanswers'] = $this->necessaryanswers;
        $data['casesensitive'] = $this->casesensitive;
        $data['contextquestionavailable'] = $this->questioncontext != null;
        $data['contextansweravailable'] = $this->answercontext != null;
        $data['icon'] = "";
        $data['morethanonesolution'] = ($this->answercount > 1);
        $data['cardsleft'] = $this->cardsleft;
        $data['deck'] = $this->deck;
        $data['deckimgurl'] = $this->deckimgurl;
        return $data;

    }

}
