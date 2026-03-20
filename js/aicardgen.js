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
 * AI Card Generator for the mass import page.
 *
 * @package   mod_cardbox
 */

function initAiCardGen(cmid) {
    require(['jquery'], function($) {

        var btn    = document.getElementById('cardbox-aicardgen-btn');
        var input  = document.getElementById('cardbox-aicardgen-input');
        var status = document.getElementById('cardbox-aicardgen-status');
        var list   = document.getElementById('cardbox-aicardgen-list');

        if (!btn) { return; }

        // JLPT level buttons — each click generates 1 card from that level
        document.querySelectorAll('.cardbox-jlpt-btn').forEach(function(jlptBtn) {
            jlptBtn.addEventListener('click', function() {
                var level = jlptBtn.getAttribute('data-level');
                generateCard(cmid, null, level, $, status, list);
            });
        });

        // Manual topic button
        btn.addEventListener('click', function() {
            var topic = input.value.trim();
            if (!topic) {
                status.textContent = M.util.get_string('ai_cardgen_empty', 'cardbox');
                status.className = 'cardbox-aicardgen-status cardbox-aicardgen-error';
                return;
            }
            generateCard(cmid, topic, null, $, status, list);
            input.value = '';
        });

        // Allow Enter key
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); btn.click(); }
        });

        function generateCard(cmid, topic, jlptLevel, $, status, list) {
            var postData = { id: cmid, sesskey: M.cfg.sesskey };
            if (jlptLevel) {
                postData.action = 'aicardgenjlpt';
                postData.level  = jlptLevel;
            } else {
                postData.action = 'aicardgensave';
                postData.topic  = topic;
            }
            status.textContent = M.util.get_string('ai_cardgen_loading', 'cardbox');
            status.className = 'cardbox-aicardgen-status cardbox-aicardgen-loading';
            // Disable all generate buttons while loading
            document.querySelectorAll('.cardbox-jlpt-btn, #cardbox-aicardgen-btn').forEach(function(b) { b.disabled = true; });

            $.ajax({
                url: M.cfg.wwwroot + '/mod/cardbox/action.php',
                type: 'POST',
                data: postData,
                success: function(response) {
                    document.querySelectorAll('.cardbox-jlpt-btn, #cardbox-aicardgen-btn').forEach(function(b) { b.disabled = false; });
                    var result;
                    try {
                        result = typeof response === 'string' ? JSON.parse(response) : response;
                    } catch(e) {
                        result = {status: 'error', reason: M.util.get_string('ai_cardgen_error', 'cardbox')};
                    }
                    if (result.status === 'success') {
                        var li = document.createElement('li');
                        li.style.marginBottom = '4px';
                        var badge = result.jlpt_level
                            ? '<span class="cardbox-jlpt-badge cardbox-jlpt-' + result.jlpt_level.toLowerCase() + '">' + result.jlpt_level + '</span> '
                            : '';
                        li.innerHTML = '<span style="color:#137333">✔</span> '
                            + badge
                            + '<strong>' + escapeHtml(result.question) + '</strong>'
                            + ' → ' + escapeHtml(result.answer)
                            + ' <span style="color:#888; font-size:0.85em">(ID: ' + result.cardid + ')</span>';
                        list.appendChild(li);
                        status.textContent = M.util.get_string('ai_cardgen_saved', 'cardbox');
                        status.className = 'cardbox-aicardgen-status cardbox-aicardgen-ok';
                    } else {
                        status.textContent = result.reason || M.util.get_string('ai_cardgen_error', 'cardbox');
                        status.className = 'cardbox-aicardgen-status cardbox-aicardgen-error';
                    }
                },
                error: function() {
                    document.querySelectorAll('.cardbox-jlpt-btn, #cardbox-aicardgen-btn').forEach(function(b) { b.disabled = false; });
                    status.textContent = M.util.get_string('ai_cardgen_error', 'cardbox');
                    status.className = 'cardbox-aicardgen-status cardbox-aicardgen-error';
                }
            });
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
    });
}
