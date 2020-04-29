export function PredefinedVariantsDecorator(decorated, $element, options) {
    decorated.call(this, $element, options);

    this.variants = options.get('predefinedVariants');
}

PredefinedVariantsDecorator.prototype.query = function (decorated, params, callback) {
    var self = this;

    if (params.term || params.page != null) {
        decorated.call(this, params, callback);
        return;
    }

    this._removeOldVariants();

    function wrapper (obj) {
        var data = obj.results;
        var options = [];

        self.variants.forEach(function (variant) {
            variant = self._normolizeVariant(variant);
            let $option = self.option(variant);
            $option.attr('data-select2-predefined-variant', true);

            options.push($option);
            self._insertVariant(data, variant);
        });

        self.addOptions(options);

        obj.results = data;
        callback(obj);
    }

    decorated.call(this, params, wrapper);
}

PredefinedVariantsDecorator.prototype._insertVariant = function (_, data, variant) {
    data.unshift(variant);
};

PredefinedVariantsDecorator.prototype._normolizeVariant = function (_, variant) {
    return Object.assign(variant, {
        data: variant.data || {},
        loaded: true,
        isPredefined: true
    });
};

PredefinedVariantsDecorator.prototype._removeOldVariants = function (_) {
    let $options = this.$element.find('option[data-select2-predefined-variant]');

    $options.each(function () {
        if (this.selected) {
            return;
        }

        $(this).remove();
    });
};