// This file is part of Moodle - https://moodle.org/
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
 * Friction Radar info modal controller.
 *
 * @module     coursereport_frictionradar/friction_info
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([
    'core/modal_factory'
], function(ModalFactory) {

    const esc = (s) => (s ?? '').toString()
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const nl2br = (s) => esc(s).replace(/\n/g, '<br>');

    const parseInputs = (s) => {
        try {
            const v = JSON.parse(s || '[]');
            return Array.isArray(v) ? v : [];
        } catch {
            return [];
        }
    };

    const renderCalculationBlock = (
        uiLabel,
        uiParam,
        uiValue,
        uiNotes,
        formula,
        inputs,
        notes
    ) => {

        if (!formula && !inputs.length && !notes) {
            return '';
        }

        const rows = inputs.map(i => `
            <tr>
                <td><code>${esc(i.key ?? '')}</code></td>
                <td class="text-end"><strong>${esc(i.value ?? '')}</strong></td>
            </tr>
        `).join('');

        return `
            <div class="mt-3">
                <button type="button"
                        class="btn btn-link p-2 friction-toggle"
                        style="text-decoration:none;">
                    <span class="friction-arrow me-1">▶</span>
                    <span class="fw-semibold">${esc(uiLabel)}</span>
                </button>

                <div class="friction-details mt-2" style="display:none;">
                    ${formula ? `<pre class="p-2 bg-light border rounded small" style="white-space: pre-wrap;"><code>${esc(formula)}</code></pre>` : ''}

                    ${inputs.length ? `
                        <div class="table-responsive">
                            <table class="table table-sm mb-2">
                                <thead>
                                    <tr>
                                        <th>${esc(uiParam)}</th>
                                        <th class="text-end">${esc(uiValue)}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rows}
                                </tbody>
                            </table>
                        </div>
                    ` : ''}

                    ${notes ? `
                        <div class="small text-muted">
                            ${nl2br(notes)}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    };

    const bindCalculationToggles = (root) => {
        root.querySelectorAll('.friction-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const details = button.nextElementSibling;
                const arrow = button.querySelector('.friction-arrow');
                const open = details.style.display !== 'none';

                details.style.display = open ? 'none' : 'block';
                arrow.textContent = open ? '▶' : '▼';
            });
        });
    };

    const buildModalBody = (trigger) => `
        <p>
            <strong>${esc(trigger.dataset.uiScore)}:</strong>
            ${esc(trigger.dataset.score)}
        </p>

        <p>${nl2br(trigger.dataset.explanation)}</p>

        ${renderCalculationBlock(
            trigger.dataset.uiFormula,
            trigger.dataset.uiParam,
            trigger.dataset.uiValue,
            trigger.dataset.uiNotes,
            trigger.dataset.formula || '',
            parseInputs(trigger.dataset.inputs || '[]'),
            trigger.dataset.notes || ''
        )}

        <p class="mt-3"><strong>${nl2br(trigger.dataset.what)}</strong></p>
        <p>${nl2br(trigger.dataset.action)}</p>
    `;

    const openDetails = (trigger) => {
        const title = esc(trigger.dataset.label || trigger.textContent);

        ModalFactory.create({
            type: ModalFactory.types.DEFAULT,
            title: title,
            body: buildModalBody(trigger),
            large: true
        }).then(function(modal) {
            modal.show();
            bindCalculationToggles(modal.getRoot()[0]);
        });
    };

    const isActivationKey = (event) => event.key === 'Enter' || event.key === ' ';

    return {
        init: function() {
            document.querySelectorAll('.friction-detail-trigger').forEach(trigger => {
                trigger.addEventListener('click', function(event) {
                    event.preventDefault();
                    openDetails(trigger);
                });

                trigger.addEventListener('keydown', function(event) {
                    if (!isActivationKey(event)) {
                        return;
                    }

                    event.preventDefault();
                    openDetails(trigger);
                });
            });
        }
    };
});
