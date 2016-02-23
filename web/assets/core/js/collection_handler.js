/*global window */
(function (document, $, _, RegExp) {
    'use strict';

    /**
     * @param {Object} element
     * @constructor
     */
    var CollectionWidget = function (element) {
        var $element = $(element);
        this.$element = $element;
        this.counter = this.$element.children('.form-group').length;

        this.createAddItemButton();
        $element.on('click.collection', '> .form-group button.collection-item-remove', this.removeItem.bind(this));
        _.forEach($element.find('> .form-group'), this.createRemoveButton.bind(this));
    };

    CollectionWidget.prototype = {
        constructor: CollectionWidget,

        createAddItemButton: function () {
            if ('undefined' === typeof this.$element.attr('data-collection-allow-add')) {
                return;
            }

            var buttonText = this.$element.attr('data-collection-add-button-text') || '';

            this.$addItemButton = $('<button type="button" class="btn"><i class="fa fa-plus-circle"></i> ' + buttonText + '</button>');

            this.$element.append(this.$addItemButton);
            this.triggerButtonAdded.bind(this);
            this.$addItemButton.on('click.collection', this.addItem.bind(this));
        },

        triggerButtonAdded: function () {
            this.$addItemButton.trigger('bw_collection_button_added');
        },

        removeItem: function (event) {
            event.preventDefault();
            var $element = $(event.currentTarget).closest('.form-group');
            $element.trigger('bw_collection_item_remove');
            $element.remove();
            // Triggered on the container as the element is not in the DOM anymore
            this.$element.trigger($.Event('bw_collection_item_removed', {
                relatedTarget: $element[0]
            }));
        },

        createRemoveButton: function (element) {
            if ('undefined' === typeof this.$element.attr('data-collection-allow-delete')) {
                return;
            }

            var buttonTitle = this.$element.attr('data-collection-remove-button-title') || '';

            var $element = $(element);
            var $button = $('<button type="button" class="btn btn-danger btn-mini collection-item-remove"><i class="fa fa-trash-o"></i></button>');
            var $wrapper = $('<div class="collection-item-actions"></div>');

            if (buttonTitle.length) {
                $button.attr('title', buttonTitle);
            }

            $wrapper.append($button);

            $element.prepend($wrapper);
            $element.trigger('bw_collection_item_remove_button_added');
        },

        addItem: function (event) {
            event.preventDefault();
            var $container = this.$element;
            var prototypeName = $container.attr('data-prototype-name');
            var proto = $container.attr('data-prototype')
                .replace(new RegExp(prototypeName + 'label__', 'g'), this.counter)
                .replace(new RegExp(prototypeName, 'g'), this.counter);
            var $proto = $($.trim(proto));

            this.counter++;
            $container.trigger('bw_collection_item_add', $proto);
            this.$addItemButton.before($proto);
            this.createRemoveButton($proto);
            $proto.trigger('bw_load');
            $proto.trigger('bw_collection_item_added');
        }
    };

    var initialize = function (context) {
        $('[data-widget="collection"]', context).each(function (index, item) {
            var $element = $(item);

            if (!$element.data('collection_widget')) {
                $element.data('collection_widget', new CollectionWidget(item));
            }
        });
    };

    $(document).on('bw_load', function (event) {
        initialize(event.currentTarget);
    });

    $(function () {
        initialize(document);
    });
})(window.document, window.jQuery, window._, window.RegExp);
