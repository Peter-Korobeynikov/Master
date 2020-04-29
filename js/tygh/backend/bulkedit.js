// Bulk edit
(function (_, $) {

    var _doc = $(document);

    $.ceEvent('on', 'ce.commoninit', _bulkEditInit);

    $.ceEvent('on', 'ce.tap.toggle', function (selected) {
        (selected ? $('.bulkedit-toggler') : $('.bulkedit-disabler')).trigger('click');
    });

    $.ceEvent('on', 'ce.select_template_selection', function (object, list_elm, $container) {
        if (!$container.hasClass('cm-bulk-edit-object-categories-add') || !object.data) {
            return;
        }

        $(list_elm).toggleClass('no-bold', true);
    });

    $.ceEvent('on', 'ce.select2.init', function ($elm) {
        if (!$elm.hasClass('cm-bulk-edit-object-categories-add')) {
            return;
        }

        $elm.data('select2').dropdown.$dropdownParent.addClass('fixed-select2-dropdown');

        $elm.data('select2').on('selection:update', function () {
            var categories_products = $elm.data('caCategoriesProducts') || {};
            var product_ids = $elm.data('caSelectedProductIds') || [];

            $elm.data('select2').$container.find('.select2__category-status-checkbox').each(function () {
                var $checkbox = $(this),
                    category_id = $checkbox.data('caCategoryId'),
                    checked = typeof categories_products[category_id] === 'undefined'
                        || product_ids.length === categories_products[category_id].length;

                if (checked) {
                    $checkbox
                        .prop('defaultChecked', true)
                        .prop('checked', true);
                } else {
                    $checkbox
                        .prop('defaultChecked', false)
                        .prop('checked', false)
                        .prop('indeterminate', true)
                        .prop('readOnly', true);
                }
            });
        });
    });

    /**
     * Init function, binds events
     */
    function _bulkEditInit (context) {
        if ($(context).is(document)) {
            _doc.on('click', '.bulkedit-toggler', toggleBulkEditPanel);
            _doc.on('click', '.bulkedit-disabler', toggleBulkEditPanel);
        }
    }

    /**
     * Toggling bulk edit panel
     * @param {Event} event 
     */
    function toggleBulkEditPanel (event) {
        var $self    = $(this),
            $enable  = $( $self.data('caBulkeditEnable') ),
            $disable = $( $self.data('caBulkeditDisable') );

        $enable.removeClass('hidden');
        $disable.addClass('hidden');

        $('[name="check_all"]').prop('checked', false);
    }

})(Tygh, Tygh.$);



// Bulk edit => Categories
(function (_, $) {
    var _doc = $(document);

    $.ceEvent('on', 'ce.commoninit', _cat);

    function _cat (context) {
        if (context.is(document)) {
            _doc.on('click', '.bulk-edit__btn-content--category', function () {

                if ($( $(this).data('toggle') ).hasClass('open')) {
                    _updateCategoriesDropdown();
                } else {
                    $('.bulk-edit--overlay').remove();
                    $( $(this).data('toggle') ).toggleClass('open', false);
                }

            });

            $('[data-ca-bulkedit-mod-cat-cancel]', _doc)
                .on('click', _resetter);

            $('[data-ca-bulkedit-mod-cat-update]', _doc)
                .on('click', _applyNewCategories);
        }
    }

    /**
     * Update categories lists in dropdown (from backend)
     */
    function _updateCategoriesDropdown () {

        var $applyBtn           = $('[data-ca-bulkedit-mod-cat-update]', _doc),
            $form               = $( $applyBtn.data('caBulkeditModTargetForm') ),
            $selectedNodes      = $form.find( $applyBtn.data('caBulkeditModTargetFormActiveObjects') ),
            $selecbox           = $('#bulk_edit_categories_list_content').find('.cm-bulk-edit-object-categories-add'),
            categories_products = {},
            category_ids        = [],
            product_ids         = [],
            categories          = [];

        $selecbox.val(null).empty().trigger('change');

        $selectedNodes.each(function (i, node) {
            var product_category_ids = $(node).data('caCategoryIds');
            var product_id = $(node).data('caId');

            for (var j in product_category_ids) {
                var category_id = product_category_ids[j];

                categories_products[category_id] = categories_products[category_id] || [];

                if (categories_products[category_id].indexOf(product_id) === -1) {
                    categories_products[category_id].push(product_id);
                }


                if (category_ids.indexOf(category_id) === -1) {
                    category_ids.push(category_id);

                    categories.push({
                        category: '',
                        category_id: category_id
                    })
                }
            }

            product_ids.push(product_id);
        });

        $selecbox.data('caCategoriesProducts', categories_products);
        $selecbox.data('caSelectedProductIds', product_ids);

        $.ceEvent('trigger', 'ce.select2_categories.add_categories', [categories, $selecbox]);
    }

    /**
     * Resets fields in dropdown
     * @param {Event} event 
     */
    function _resetter (event) {
        _updateCategoriesDropdown();
        event.preventDefault();
    }

    function _applyNewCategories (event) {
        event.preventDefault();

        var categoriesMap = {
                A: [],
                D: []
            },
            productsIds   = [],
            checkboxes    = $('.cm-tristate', '.bulk-edit--reset-dropdown-menu'),
            products      = $('.cm-longtap-target.selected');

        // calculate categories statuses map
        $.each(checkboxes, function (i, elm) {
            var jelm = $(elm);

            if (elm.indeterminate) {
                return;
            }

            if (elm.checked) {
                categoriesMap.A.push(jelm.data('caCategoryId'));
            } else {
                categoriesMap.D.push(jelm.data('caCategoryId'));
            }
        });

        if (categoriesMap.D.length == checkboxes.length) {
            alert(_.tr('unable_to_delete_all_categories'));
            return;
        }

        // calculate current selected products
        $.each(products, function (i, elm) {
            productsIds.push($(elm).data('caId'));
        });

        $.ceAjax('request', fn_url(''), {
            caching: false,
            method: 'POST',
            full_render: 'Y',
            result_ids: '',
            data: ({
                dispatch: 'products.m_update_categories',
                redirect_url: _.current_url,
                categories_map: categoriesMap,
                products_ids: productsIds
            }),
            callback: function () {
                $.each(products, function(i, elm) {
                    var new_product_category_ids = [];
                    var $product = $(elm);
                    var product_category_ids = $product.data('caCategoryIds');
                    product_category_ids = product_category_ids.concat(categoriesMap.A);

                    for (var j in product_category_ids) {
                        product_category_ids[j] = parseInt(product_category_ids[j]);

                        if (categoriesMap.D.indexOf(product_category_ids[j]) === -1) {
                            new_product_category_ids.push(product_category_ids[j])
                        }
                    }
                    $product.data('caCategoryIds', new_product_category_ids);
                });
                _updateCategoriesDropdown();
            }
        });
    }
})(Tygh, Tygh.$);



// Bulk edit => Price
(function (_, $) {

    var _doc = $(document);

    $.ceEvent('on', 'ce.commoninit', _mod);

    /**
     * Init function, binds events
     */
    function _mod (context) {
        $('[data-ca-bulkedit-mod-changer]', _doc)
            .on('change', _changer)
            .on('input', _changer);

        $('[data-ca-bulkedit-mod-update]', _doc)
            .on('click', _sender);

        $('[data-ca-bulkedit-mod-cancel]', _doc)
            .on('click', _resetter);

        $(
            '[data-ca-bulkedit-mod-price-filter-p],[data-ca-bulkedit-mod-price-filter-lp],[data-ca-bulkedit-mod-price-filter-is]',
            _doc
        ).on('change', function () {
            $self = $(this);
            $('[data-ca-bulkedit-mod-changer]').trigger('change');
        })
    }

    /**
     * Calculate all new values and send to backend
     * @param {Event} event 
     */
    function _sender (event) {
        event.preventDefault();
        var $self          = $(this),
            $form          = $( $self.data('caBulkeditModTargetForm') ),
            $valuesNodes   = $( $self.data('caBulkeditModValues') ),
            $selectedNodes = $form.find( $self.data('caBulkeditModTargetFormActiveObjects') ),
            dispatch       = $self.data('caBulkeditModDispatch'),
            currentValues  = [];

        // Calculating new values and store to 'currentValues'
        $selectedNodes.each(function (i, node) {
            var id = $(node).data('caId'),
                values = {};

            $valuesNodes.each(function (i, _node) {
                var $self = $(_node),
                    eqFieldSel, filter;

                if (!$self.data('caName') || !$self.val().length) {
                    return true;
                }

                eqFieldSel = $.sprintf($self.data('caBulkeditEqualField'), [ id ], '?');
                filter = _getFilter($( $self.data('caBulkeditModFilter') ));

                values[$self.data('caName')] = filter(
                    +($(eqFieldSel).val()), 
                    +($self.val())
                );
            });

            currentValues.push({ id: id, values: values });
        });

        $.ceAjax('request', fn_url(''), {
            caching: false,
            method: 'POST',
            full_render: 'Y',
            result_ids: 'content_manage_products',
            data: ({
                dispatch: dispatch,
                redirect_url: _.current_url,
                new_values: currentValues
            })
        });
    }

    /**
     * Resets fields in dropdown
     * @param {Event} event 
     */
    function _resetter (event) {
        event.preventDefault();

        $( $(this).data('caBulkeditModResetChanger') )
            .each(function (index, elm) {
                var $self = $(elm),
                    $affected = $( $self.data('caBulkeditModAffectOn') );
                
                $( $affected.data('caBulkeditModAffectedWriteInto'), $affected )
                    .text( '' )
                    .toggleClass('active', false);

                $( $affected.data('caBulkeditModAffectedOldValue'), $affected )
                    .text( $affected.data('caBulkeditModDefaultValue') )
                    .toggleClass('active', false);

                $self.val(undefined);
            });
    }

    /**
     * Handle changing fields in dropdown
     * @param {Event} event 
     */
    function _changer (event) {
        var $self         = $(this),
            $affectedNode = $( $self.data('caBulkeditModAffectOn') ),
            filter        = _getFilter($( $self.data('caBulkeditModFilter') )),
            oldValue      = $affectedNode.data('caBulkeditModDefaultValue'),
            curValue      = filter(+oldValue, +($self.val()));

            if (+curValue === +oldValue) {
                _toggle( '', false );
            } else {
                _toggle( curValue.toString(), true );
            }
            
            function _toggle (val, flag) {
                $( $affectedNode.data('caBulkeditModAffectedWriteInto'), $affectedNode )
                    .text( val )
                    .toggleClass('active', flag);

                $( $affectedNode.data('caBulkeditModAffectedOldValue'), $affectedNode )
                    .toggleClass('active', flag);
            }
    }

    /**
     * Return filter-function
     * @param {jQuery} $containsFilterName form element, that contains name of filter
     */
    function _getFilter ($containsFilterName) {
        filterName = $containsFilterName.val();
        return _filters()[filterName];
    }

    /**
     * Returns filters
     */
    function _filters () {
        return ({
            percent: function (oldValue, modValue) {
                return (oldValue * (modValue / 100)).toFixed(2);
            },
            number: function (oldValue, modValue) {
                return (oldValue + modValue);
            }
        });
    }

})(Tygh, Tygh.$);



// Bulk edit => Custom tristate checkbox
(function (_, $) {
    $(document).on('click', '.cm-readonly', function (e) {
        e.preventDefault();
    });

    $.ceEvent('on', 'ce.commoninit', function (context) {
        $('[data-set-indeterminate="true"]', $(context)).prop('indeterminate', true);
    });

    $(document).on('mouseup', '.cm-tristate', function (e) {
        e.preventDefault();
        var scope = this;

        setTimeout(function () {
            _onclick.call(scope);
        }, 1);
    });

    function _onclick () {
        if ($(this).data('caTristateJustClick')) {
            return;
        }

        var elm = $(this).get(0);

        if (elm.readOnly) elm.checked = elm.readOnly = false;
        else if (!elm.checked) elm.readOnly = elm.indeterminate = true;
    }
})(Tygh, Tygh.$);



// Bulk edit => Custom dropdown
(function (_, $) {
    $(document).on('click', '.bulk-edit-toggle', function () {
        $('.bulk-edit--overlay').remove();
        $('body').append($('<div class="bulk-edit--overlay"></div>'));

        $( $(this).data('toggle') ).toggleClass('open', true);

        var scope = this;

        $('.bulk-edit--overlay').one('click', function () {
            $('.bulk-edit--overlay').remove();

            $( $(scope).data('toggle') ).toggleClass('open', false);
        });
    });

    $(document).on('click', '.cm-toggle', function (e) {
        var self = $(this);

        if (self.data('state') == 'show') {
            self.data('state', 'hide');
            self.html( self.data('hideText') );
            $(self.data('toggle')).toggleClass('hidden', false);
        } else {
            self.data('state', 'show');
            self.html( self.data('showText') );
            $(self.data('toggle')).toggleClass('hidden', true);
        }

        e.preventDefault();
    });
})(Tygh, Tygh.$);