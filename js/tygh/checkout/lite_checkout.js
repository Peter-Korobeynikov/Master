(function (_, $) {

    var $checkout,
        $country,
        $city,
        $zipCode,
        $autocompleteCity,
        $shippingMethods,
        $userProfiles,
        $shipToAnother;

    var SHIPPING_PREFIX = 's_',
        BILLING_PREFIX = 'b_',
        NAME_SEPARATOR = ' ',
        EMPTY_STATE_CODE = '\u00a0'; // "\xc2\xa0"

    function assign(obj, keyPath, value) {
        var lastKeyIndex = keyPath.length-1;
        for (var i = 0; i < lastKeyIndex; ++ i) {
            var key = keyPath[i];
            if (!(key in obj))
                obj[key] = {};
            obj = obj[key];
        }
        obj[keyPath[lastKeyIndex]] = value;
    }

    var methods = {
        init: function ($jelm) {
            $checkout = $jelm;
            $country = methods.getElement('country');
            $city = methods.getElement('city');
            $zipCode = methods.getElement('zipcode');
            $autocompleteCity = methods.getElement('city-autocomplete');
            $shippingMethods = methods.getElement('shipping-methods');
            $userProfiles = methods.getElement('user-profiles');

            if ($autocompleteCity.length) {
                methods.initAutocompleteCity();
            } else {
                methods.initPlainCityInput();
            }

            methods.bindAbandonedCartFieldsSaving();
            methods.initMultipleProfileRoutines();
            methods.toggleAddress();

            methods.setupCheckoutForm(methods.getElement('checkout-form'));
        },

        bindAbandonedCartFieldsSaving: function () {
            var addressFields = ['s_country', 's_state', 's_city', 's_zipcode', 's_address', 's_address_2'];
            var updateCustomerInfo = $.debounce(function() {
                var $input = $(this);
                var fieldName = $input.data('caLiteCheckoutField').replace(/^user_data\./, '');
                /**
                 * Shipping address affects available shipping methods, thus afffecting available payment methods.
                 * So, when updating the shipping address, the checkout page must be redrawn completely.
                 */
                var isAddressField = addressFields.indexOf(fieldName) !== -1;
                methods.updateCustomerInfo($.noop, false, true, true, isAddressField);
            }, 1000);

            $('[data-ca-lite-checkout-auto-save="true"]', $checkout).on('input _input', updateCustomerInfo);
            $('[data-ca-lite-checkout-auto-save-on-change="true"]', $checkout).on('change', updateCustomerInfo);
        },

        initMultipleProfileRoutines: function () {
            if ($userProfiles.length === 0) {
                return;
            }

            $('.js-lite-checkout-edit-profile-popup-opener').on('change click', methods.openEditProfilePopup);
            $('.js-lite-checkout-profile-selector', $userProfiles).on('change', methods.switchProfile);
        },

        lockShippingMethodSelector: function () {
            $shippingMethods.toggleClass($shippingMethods.data('caLiteCheckoutOverlayClass'), true);
        },

        unlockShippingMethodSelector: function () {
            $shippingMethods.toggleClass($shippingMethods.data('caLiteCheckoutOverlayClass'), false);
        },

        setupCheckoutForm: function ($checkoutForm) {
            $.ceEvent('on', 'ce.formpost_' + $checkoutForm.prop('name'), function (form) {
                var $checkoutForm = $(form);

                if ($checkoutForm.data('caLiteCheckoutReadyForCheckout') === true) {
                    $.toggleStatusBox('show', {
                        statusContent: '<span class="ty-ajax-loading-box-with__text-wrapper">' + _.tr('placing_order') + '</span>',
                        statusClass: 'ty-ajax-loading-box_text_block'
                    });
                    return true;
                }

                return methods.check(function (result) {
                    if (!result) {
                        return false;
                    }

                    methods.updateCustomerInfo(function (data) {
                        setTimeout(function () {
                            $checkoutForm.data('caLiteCheckoutReadyForCheckout', true);
                            $checkoutForm.submit();
                        }, 100);
                    }, false, true);

                    return false;
                });
            });
        },

        check: function (callback) {
            callback = callback || false;

            if (callback === false) {
                return true;
            }

            var result = $checkout.ceFormValidator('check'); // check
            $checkout.ceFormValidator('check', false); // notice

            return callback(result);
        },

        serializeCheckout: function (userDataExtend) {
            userDataExtend = typeof userDataExtend === 'undefined' ? {} : userDataExtend;

            var serializedCheckout = {
                result_ids: 'litecheckout_form,checkout_info*,checkout_order_info*',
                dispatch: 'checkout.update_steps',
                full_render: 'Y',
                user_data: {}
            };

            var $serializedFields = $('[data-ca-lite-checkout-field]', $checkout);
            $serializedFields
                .filter(function (key, field) {
                    var $field       = $(field),
                        is_checkable = $field.is(':checkbox') || $field.is(':radio');

                    return !$field.prop('disabled') && (!is_checkable || $field.is(':checked'));
                })
                .each(function(index, elm) {
                    var $elm = $(elm),
                        value;
                    if ($(elm).data('caLiteCheckoutElement')) {
                        value = methods.getElement($(elm).data('caLiteCheckoutElement')).val();
                    } else {
                        value = $elm.val();
                    }
                    var keyPath = $elm.data('caLiteCheckoutField').split('.');

                    assign(serializedCheckout, keyPath, value);
                });

            var userData = serializedCheckout.user_data;

            var fullnameFormat, fullnameParts;
            if (userData.fullname) {
                fullnameFormat = $('[data-ca-lite-checkout-field="user_data.fullname"]').data('caFullnameFormat');
                fullnameParts = methods.splitFullname(userData.fullname, fullnameFormat);
                userData.firstname = fullnameParts.firstname;
                userData.lastname = fullnameParts.lastname;
            }
            if (userData.s_fullname) {
                fullnameFormat = $('[data-ca-lite-checkout-field="user_data.s_fullname"]').data('caFullnameFormat');
                fullnameParts = methods.splitFullname(userData.s_fullname, fullnameFormat);
                userData.s_firstname = fullnameParts.firstname;
                userData.s_lastname = fullnameParts.lastname;
            }
            if (userData.b_fullname) {
                fullnameFormat = $('[data-ca-lite-checkout-field="user_data.b_fullname"]').data('caFullnameFormat');
                fullnameParts = methods.splitFullname(userData.b_fullname, fullnameFormat);
                userData.b_firstname = fullnameParts.firstname;
                userData.b_lastname = fullnameParts.lastname;
            }

            if (userData.customer_notes) {
                serializedCheckout.customer_notes = userData.customer_notes;
            }

            serializedCheckout.user_data = userData;

            if (userDataExtend) {
                serializedCheckout.user_data = $.extend(serializedCheckout.user_data, userDataExtend);
            }

            return serializedCheckout;
        },

        splitFullname: function (fullname, fullnameFormat) {
            fullnameFormat = fullnameFormat || 'firstname_first';

            var nameParts = fullname.split(NAME_SEPARATOR).map(function(part) {
                    return part.trim();
                }).filter(function(part) {
                    return part !== '';
                }),
                firstname = '',
                lastname = '';

            if (fullnameFormat === 'firstname_first') {
                if (nameParts.length > 1) {
                    lastname = nameParts.pop();
                }
                firstname = nameParts.join(NAME_SEPARATOR);
            } else {
                lastname = nameParts.shift();
                firstname = nameParts.join(NAME_SEPARATOR);
            }

            return {
                firstname: firstname,
                lastname: lastname
            };
        },

        updateShipping: function (userDataExtend) {
            $.ceAjax('request', fn_url(''), {
                method: 'post',
                caching: false,
                data: methods.serializeCheckout(userDataExtend)
            });
        },

        autocompleteCity: function (query, countryCode, callback) {
            var url = $autocompleteCity.data('caLiteCheckoutAutocompleteUrl'),
                method = $autocompleteCity.data('caLiteCheckoutAutocompleteRequestMethod'),
                cityParam = $autocompleteCity.data('caLiteCheckoutAutocompleteCityParam'),
                countryParam = $autocompleteCity.data('caLiteCheckoutAutocompleteCountryParam'),
                itemsPerPageParam = $autocompleteCity.data('caLiteCheckoutAutocompleteItemsPerPageParam'),
                itemsPerPage = $autocompleteCity.data('caLiteCheckoutAutocompleteItemsPerPage'),
                hidden = $autocompleteCity.data('caLiteCheckoutAutocompleteHidden') !== false,
                caching = $autocompleteCity.data('caLiteCheckoutAutocompleteCaching') !== false,
                ajaxCallback = callback || $.noop;

            var requestData = {};
            requestData[cityParam] = query;
            requestData[countryParam] = countryCode;
            requestData[itemsPerPageParam] = itemsPerPage;

            $.ceAjax('request', url, {
                method: method,
                hidden: hidden,
                caching: caching,
                data: requestData,
                callback: function (data) {
                    ajaxCallback(data);
                }
            })
        },

        setLocation: function(city, stateCode, state, zipcode) {
            // update plain fields
            $city.val(city);
            $zipCode.val(zipcode ? zipcode : '');
            methods.getElement('state').val(stateCode ? stateCode : state);

            // update autocomplete field
            $autocompleteCity.val(city + (state ? ' (' + state + ')' : ''));

            methods.updateShipping({s_zipcode: zipcode});
        },

        initAutocompleteCity: function () {
            $autocompleteCity.on('focus', function (e) {
                if ($autocompleteCity.val() !== '') {
                    return;
                }
                methods.lockShippingMethodSelector();
            }).on('input', function (e) {
                methods.lockShippingMethodSelector();
            });

            $autocompleteCity.autocomplete({
                appendTo: "#litecheckout_autocomplete_dropdown",

                source: function (request, response) {
                    var countryCode = $country.val();

                    methods.autocompleteCity(request.term, countryCode, function(data) {
                        for (var i = 0; i < data.autocomplete.length; i++) {
                            data.autocomplete[i].label = data.autocomplete[i].value
                                + (data.autocomplete[i].state
                                    ? ' (' + data.autocomplete[i].state + ')'
                                    : ''
                                );
                        }

                        $autocompleteCity.data('caLiteCheckoutAutocompleteList', data.autocomplete);

                        response(data.autocomplete);
                    });
                },

                select: function (event, ui) {
                    event.preventDefault();
                    methods.setLocation(ui.item.value, ui.item.state_code, ui.item.state, ui.item.zipcode);
                }
            });

            $country.on('change', function (e, extra) {
                if (extra && !extra.is_triggered_by_user) {
                    return;
                }

                methods.getElement('state').val('');
                $city.val('');
                $autocompleteCity.val('');

                $autocompleteCity.focus();
            });

            $shippingMethods.on('click', function (e) {
                if (!$(this).hasClass($(this).data('caLiteCheckoutOverlayClass'))) {
                    return;
                }

                var cityState = $autocompleteCity.val(),
                    lastSelectedCityState = $autocompleteCity.data('caLiteCheckoutLastValue'),
                    autocompleteList = $autocompleteCity.data('caLiteCheckoutAutocompleteList');

                if (cityState) {
                    methods.unlockShippingMethodSelector();

                    if (cityState !== lastSelectedCityState) {
                        if (autocompleteList.length) {
                            methods.setLocation(
                                autocompleteList[0].value,
                                autocompleteList[0].state_code,
                                autocompleteList[0].state,
                                autocompleteList[0].zipcode
                            );
                        } else {
                            methods.setLocation(cityState, EMPTY_STATE_CODE);
                        }
                    }
                }
            });
        },

        initPlainCityInput: function () {
            var $states = methods.getElement('state', true);

            $city.on('focus', function (e) {
                if ($(this).val() !== '') {
                    return;
                }
                methods.lockShippingMethodSelector();
            }).on('input', function (e) {
                methods.lockShippingMethodSelector();
            });

            $states.on('focus', function (e) {
                if ($(this).val() !== '') {
                    return;
                }
                methods.lockShippingMethodSelector();
            }).on('input change', function(e) {
                $states.val(methods.getElement('state').val());
                methods.lockShippingMethodSelector();
            });

            $country.on('change', function (e, extra) {
                if (extra && !extra.is_triggered_by_user) {
                    $states.val(methods.getElement('state').val());
                    return;
                }

                $city.val('');
                $city.focus();
            });

            $shippingMethods.on('click', function (e) {
                if (!$(this).hasClass($(this).data('caLiteCheckoutOverlayClass'))) {
                    return;
                }

                var $state = methods.getElement('state'),
                    city = $city.val(),
                    state = $state.val(),
                    lastSelectedCity = $city.data('caLiteCheckoutLastValue'),
                    lastSelectedState = $state.data('caLiteCheckoutLastValue');

                var isCityChanged = city !== lastSelectedCity,
                    isStateChanged = state !== lastSelectedState;

                var stateCode = '';
                if ($state.data('caLiteCheckoutIsStateCodeContainer')) {
                    stateCode = state;
                    state = $state.find('option:selected').text();
                }

                methods.unlockShippingMethodSelector();

                if (isCityChanged || isStateChanged) {
                    methods.setLocation(city, stateCode, state);
                }
            });
        },

        toggleAddress: function (isEnabled) {
            if (typeof isEnabled === 'undefined') {
                var $selectedShippingMethods = $('[data-ca-lite-checkout-element="shipping-method"]:checked');
                isEnabled = false;
                $selectedShippingMethods.each(function(i, elm) {
                    isEnabled = isEnabled || $(elm).data('caLiteCheckoutIsAddressRequired');
                });

                // when no one shipping method available
                if (!$selectedShippingMethods.length) {
                    isEnabled = true;
                }
            }

            var $addressRow = methods.getElement('address-group');
            $addressRow.toggleClass('hidden', !isEnabled);
            $addressRow.find('.cm-required,.cm-required-removed')
                .toggleClass('cm-required-removed', !isEnabled)
                .toggleClass('cm-required', isEnabled);
        },

        openEditProfilePopup: function (e) {
            e.preventDefault();

            var $target = $(e.target),
                profileId = $target.data('caProfileId');

            $('.js-edit-profile-' + profileId).click();
        },

        switchProfile: function (e) {
            var profileId = parseInt($(e.target).val());
            if (!profileId) {
                return;
            }

            $.ceAjax('request', fn_url('checkout.customer_info'), {
                method: 'POST',
                hidden: false,
                caching: false,
                result_ids: 'litecheckout_final_section,litecheckout_step_payment,shipping_rates_list,checkout*',
                data: { profile_id: profileId, full_render: 'Y' },
            })
        },

        /**
         *
         * @param {function} callback
         * @param {boolean} recalculateCart
         * @param {boolean} isHidden
         * @param {boolean} clearNotification
         * @param {boolean} redraw
         * @returns {boolean}
         */
        updateCustomerInfo: function (callback, recalculateCart, isHidden, clearNotification, redraw) {
            callback = callback || $.noop;
            if (typeof recalculateCart === 'undefined') {
                recalculateCart = true;
            }
            isHidden = isHidden || false;
            clearNotification = clearNotification || false;
            redraw = redraw || false;

            var data = methods.serializeCheckout();

            data.dispatch = 'checkout.customer_info';
            data.result_ids = '';
            data.full_render = null;
            data.recalculate_cart = recalculateCart ? 'Y': 'N';

            $.ceAjax('request', fn_url(''), {
                method: 'post',
                caching: false,
                hidden: isHidden,
                data: data,
                full_render: redraw,
                result_ids: redraw ?
                    'litecheckout_final_section,litecheckout_step_payment,shipping_rates_list,checkout*'
                    : '',
                callback: function (response) {
                    for (var i in response.notifications) {
                        if (response.notifications[i].type === 'E') {
                            return;
                        }
                    }

                    if (response.has_errors) {
                        return;
                    }

                    methods.toggleAddress();

                    callback(data, response);
                },
                pre_processing: function (response) {
                    if (response.notifications && clearNotification) {
                        delete response.notifications;
                    }
                }
            });
        },

        getElement: function (role, getAll) {
            var selector = '[data-ca-lite-checkout-element="' + role + '"]';
            if (getAll !== true) {
                selector += ':not(:disabled)';
            }

            return $(selector, $checkout);
        }
    };

    $.extend({
        ceLiteCheckout: function (method) {
            if (methods[method]) {
                return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
            } else {
                $.error('ty.ceLiteCheckout: method ' + method + ' does not exist');
            }
        }
    });

    $.ceEvent('on', 'ce.commoninit', function (context) {
        var $liteCheckoutForm = $('[data-ca-lite-checkout-element="form"]', context);
        if ($liteCheckoutForm.length) {
            $.ceLiteCheckout('init', $liteCheckoutForm);
        }
    });

    $(_.doc).on('click', '.cm-open-pickups', function (e) {
        $( $(this).data('caTarget') )
            .toggleClass('hidden-phone', false)
            .ceDialog('open', { title: $(this).data('caTitle') });

        var $dialogBody = $.ceDialog('get_last'),
            $dialogBodyContainer = $dialogBody.parents('.ui-dialog'),
            $objectContainer = $('.object-container', $dialogBody);

        _resize();

        $.ceEvent('on', 'ce.window.resize', function () {
            _resize();
        });

        $.ceEvent('on', 'ce.shipping.select-store', function () {
            try {
                $dialogBody.ceDialog('close'); // force closing dialog
            } catch (e) { /* dummy */ }
        });

        function _resize () {
            $dialogBodyContainer.css({ width: 'auto', left: 0, right: 0, top: 0, bottom: 0, position: 'fixed' });
            $objectContainer.css({ padding: 0 });
        }
    });

})(Tygh, Tygh.$);
