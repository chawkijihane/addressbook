/*global window*/
(function (window, document, $) {
    'use strict';

    var confirmHandler = function (event) {
        var $this = $(this);
        var launch = window.confirm($this.attr('data-confirmation-message'));

        if (!launch) {
            event.preventDefault();
        }
    };

    $(document).on('click', '[data-action="confirm-submission"]', confirmHandler);
})(window, window.document, window.jQuery);
