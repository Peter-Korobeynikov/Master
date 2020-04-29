import $ from "jquery";

export function BaseMultipleDecorator(decorated, $element, options) {
    decorated.call(this, $element, options);
}

BaseMultipleDecorator.prototype.bind = function (decorated, container, $container) {
    this.$selection.on('click', function (e) {
        if (!$(e.target).hasClass('select2-search__field') && !$(e.target).hasClass('select2-selection__rendered')) {
            // disable rendering dropdown if click was not on the search field
            e.stopImmediatePropagation();
        }
    });

    decorated.call(this, container, $container);
};

BaseMultipleDecorator.prototype.searchRemoveChoice = function () {
    // prevent selected option from deletion (when pressing backspace and search box is empty)
    return false;
};