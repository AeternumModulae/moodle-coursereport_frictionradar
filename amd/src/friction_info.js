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
        } catch (e) {
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

    return {
        init: function() {

            document.querySelectorAll('.friction-info').forEach(button => {

                button.addEventListener('click', function() {

                    const title = esc(button.textContent);

                    const body = `
                        <p>
                            <strong>${esc(button.dataset.uiScore)}:</strong>
                            ${esc(button.dataset.score)}
                        </p>

                        <p>${nl2br(button.dataset.explanation)}</p>

                        ${renderCalculationBlock(
                            button.dataset.uiFormula,
                            button.dataset.uiParam,
                            button.dataset.uiValue,
                            button.dataset.uiNotes,
                            button.dataset.formula || '',
                            parseInputs(button.dataset.inputs || '[]'),
                            button.dataset.notes || ''
                        )}

                        <p class="mt-3"><strong>${nl2br(button.dataset.what)}</strong></p>
                        <p>${nl2br(button.dataset.action)}</p>
                    `;

                    ModalFactory.create({
                        type: ModalFactory.types.DEFAULT,
                        title: title,
                        body: body,
                        large: true
                    }).then(function(modal) {

                        modal.show();

                        const root = modal.getRoot()[0];

                        root.querySelectorAll('.friction-toggle').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const details = btn.nextElementSibling;
                                const arrow = btn.querySelector('.friction-arrow');
                                const open = details.style.display !== 'none';

                                details.style.display = open ? 'none' : 'block';
                                arrow.textContent = open ? '▶' : '▼';
                            });
                        });
                    });
                });
            });
        }
    };
});
