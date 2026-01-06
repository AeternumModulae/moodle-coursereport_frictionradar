define([
    'core/modal_factory',
    'core/modal_events'
], function(ModalFactory, ModalEvents) {

    return {
        init: function() {
            document.querySelectorAll('.friction-info').forEach(function(button) {
                button.addEventListener('click', function() {

                    const title = button.textContent;
                    const score = button.dataset.score;
                    const explanation = button.dataset.explanation;
		    const what = button.dataset.what;
		    const action = button.dataset.action;

                    ModalFactory.create({
                        title: title,
                        body:
                            '<p><strong>Score:</strong> ' + score + '</p>' +
                            '<p>' + explanation.replace(/\n/g, '<br>') + '</p>' +
			    '<p><b>' + what.replace(/\n/g, '<br>') + '</b></p>' +
			    '<p>' + action.replace(/\n/g, '<br>') + '</p>'
                    }).then(function(modal) {
                        modal.show();
                    });

                });
            });
        }
    };
});
