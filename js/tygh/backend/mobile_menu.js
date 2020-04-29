(function (_, $) {
    $.ceEvent('one', 'ce.commoninit', function () {

        // Toggle mobile navbar
        $(document).on('click', '.mobile-menu-toggler', toggleMobileMenu);
        $(document).on('click', '.mobile-menu-closer', toggleMobileMenu);

        // Toggle submenu in the top of main menu
        $(document).on('click', '.menu-heading__title-block', toggleMobileMenuSubmenu);

        // Toggle mobile overlay from dropdown element
        $(document).on('click', '.overlayed-mobile-menu-closer', toggleMobileOverlay);
        $(document).on('click', 'li.dropdown > .dropdown-toggle', createSubmenuFromDropdown);

        // Stores/Vendors selector
        $(document).on('click', 'li.dropdown > .mobile-menu--js-companies-popup', initStoresVendorsSelectorDialog);
        $(document).on('click', '.store-vendor-selector--show-more-btn', updateStoresVendorsSelector);

        // Toggle mobile search
        initMobileSearch();

        // Handle searching
        $(document).on('keyup', '.store-vendor-selector--search', searchInStoresVendorsSelector());

        // FIXME: Decompose and clean code
        $('.navbar-right li').hover(function () {
            var pagePosition = $(".admin-content").offset();
            var adminContentWidth = 1240;

            if ($(this).hasClass('dropdown-submenu')) {
                var dropdownMenu = $(this).find('.dropdown-menu');
                var elmPosition = dropdownMenu.offset().left + dropdownMenu.width();

                if ((elmPosition - pagePosition.left) > adminContentWidth) {
                    dropdownMenu.addClass('dropdown-menu-to-right');
                }
            }
        }, function () {
            $(this).find('.dropdown-menu').removeClass('dropdown-menu-to-right');
        });
    });

    /**
     * Add new entries to selector, when 'Show more' clicked
     */
    function updateStoresVendorsSelector (e) {
        var dataFromTarget = cutDataFromTarget(e.target);

        sendStoresVendorsSelectorAjax(
            dataFromTarget.data,
            dataFromTarget.link,
            dataFromTarget.elements,
            dataFromTarget.start,
            callback
        );

        /**
         * Add new entries to selector, when 'Show more' clicked
         */
        function callback (response) {
            var $listContainer = $('.store-vendor-selector--list-container:not(.hidden)'),
                $listWrapper = $listContainer.find('.store-vendor-selector--list-wrapper-container'),
                $showMoreButton = $(e.target);

            if (response.completed === true) {
                $showMoreButton.remove();
            } else {
                // Increment start offset
                $showMoreButton.data('caSelectorStart', dataFromTarget.start + dataFromTarget.elements);
            }

            appendStoresVendorsListToWrapper(
                response.objects,
                $listContainer.find('.store-vendor-selector--list-wrapper'),
                'append'
            );
        }
    }

    /**
     * Init selector.
     */
    function initStoresVendorsSelectorDialog (e) {
        var dataFromTarget = cutDataFromTarget(e.target);

        sendStoresVendorsSelectorAjax(
            dataFromTarget.data,
            dataFromTarget.link,
            dataFromTarget.elements,
            dataFromTarget.start,
            callback
        );

        /**
         * Shows rendered dialog.
         */
        function callback (response) {
            showStoresVendorsSelectorDialog(
                renderStoresVendorsSelectorDialog(
                    response.objects,
                    response.completed,
                    dataFromTarget.data,
                    dataFromTarget.link,
                    dataFromTarget.elements,
                    dataFromTarget.start
                )
            );
        }
    }

    /**
     * Get useful data from event target and wrap into Object.
     * @param {HTMLElement} target event target
     * @returns {Object}
     */
    function cutDataFromTarget (target) {
        var data = $(target).data();
        return {
            data : data,
            link : fn_url(data.caSelectorHref),
            elements : data.caSelectorElements || 20,
            start : data.caSelectorStart || 0
        };
    }

    /**
     * Wrapper for ceAjax with custom data and handlers.
     */
    function sendStoresVendorsSelectorAjax(data, link, elements, start, callback, postData) {
        // Will be send in POST-request body
        var postData = $.extend({
                show_all: 'Y',
                action: 'href',
                start: start,
                limit: elements,
                is_ajax: 1
            }, (postData ? postData : {})),

            // Params for $.ceAjax
            ajaxData = {
                method: 'POST', 
                data: postData,
                callback: callback
            };

        $.ceAjax('request', link, ajaxData);
    }

    /**
     * Wrapper for ceDialog with custom target, returns dialog and dialog's content.
     */
    function showStoresVendorsSelectorDialog (target) {
        $(target).ceDialog(
            'open',
            { title: _.tr('manage') }
        );

        var $dialogContent = $($.ceDialog('get_last')),
            $dialogContainer = $dialogContent.parent();

        // Sets black background z-index
        $dialogContainer.prev().css({ zIndex: 100590 });

        // Sets target dialog z-index
        $dialogContainer.css({ zIndex: 100600 });

        return [ $dialogContent, $dialogContainer ];
    }

    /**
     * Render JSON-response in HTML and return this HTML.
     */
    function renderStoresVendorsSelectorDialog (objects, completed, data, link, elements, start) {
        var $container = cloneAndAppend('.hidden.store-vendor-selector--dummy-dialog', 'html');
        $container.removeClass('hidden');

        // Append list container to container
        var $listContainer = cloneAndAppend(
            '.hidden.store-vendor-selector--list-container',
            $container
        );
        $listContainer.removeClass('hidden');

        var $listWrapper = $listContainer.find('.store-vendor-selector--list-wrapper-container'),
            $searchInput = $listContainer.find('.store-vendor-selector--search');

        // Append list elements to list container
        appendStoresVendorsListToWrapper(
            objects,
            $listContainer.find('.store-vendor-selector--list-wrapper')
        );

        // Append 'Show more' button
        var $showMoreButton = restoreShowMoreButton($listWrapper, $searchInput, data);

        // Increment start offset
        $showMoreButton.data('caSelectorStart', start + elements);

        // Sets initial value of search input
        $searchInput.data('oldValue', $searchInput.val());

        if (completed === false || $.isUndefined(completed)) {
            $showMoreButton.removeClass('hidden');
        }

        return $container;
    }

    /**
     * Generates decoratored handler.
     * @returns {function}
     */
    function searchInStoresVendorsSelector () {
        return $.debounce(decoratedSearchInStoresVendorsSelector, 400);
    }

    /**
     * Handle type in search input, and handle all search logic
     * @param {Event} e
     */
    function decoratedSearchInStoresVendorsSelector (e) {

        var $listContainer = $('.store-vendor-selector--list-container:not(.hidden)'),
            $listWrapper = $listContainer.find('.store-vendor-selector--list-wrapper-container'),
            $searchInput = $(e.target),
            postData = { pattern: $searchInput.val() },
            dataFromTarget = cutDataFromTarget($searchInput);

        // Disable processing, if previous value equals current value
        if ($searchInput.val() === $searchInput.data('oldValue')) {
            return;
        }

        sendStoresVendorsSelectorAjax(dataFromTarget.data, dataFromTarget.link, dataFromTarget.elements, 0, callback, postData)

        function callback (response) {
            appendStoresVendorsListToWrapper(
                response.objects,
                $listContainer.find('.store-vendor-selector--list-wrapper'),
                'override'
            );
            $searchInput.data('oldValue', $searchInput.val());

            $listContainer.find('.store-vendor-selector--show-more-btn').remove();

            if (response.completed === true) {
                $listContainer.find('.store-vendor-selector--show-more-btn').remove();
            } else {
                dataFromTarget.data.caSelectorStart = (0 + dataFromTarget.data.caSelectorElements);

                var $showMoreButton = restoreShowMoreButton($listWrapper, $searchInput, dataFromTarget.data);
                $showMoreButton.removeClass('hidden');
            }
        }
    }

    /**
     * Render Show more button, and apply some datasets. Returns this button.
     * @returns {HTMLElement} $showMoreButton
     */
    function restoreShowMoreButton($listContainer, $searchInput, data) {
        var $showMoreButton = cloneAndAppend(
            '.hidden.store-vendor-selector--show-more-btn',
            $listContainer
        );

        // Clone datasets from target link to show-more btn and search input
        $.each(data, function (key, value) {
            $showMoreButton.data(key, value);
            $searchInput.data(key, value);
        });

        return $showMoreButton;
    }

    /**
     * Render list entries and put into their wrapper.
     */
    function appendStoresVendorsListToWrapper (objects, $listWrapper, mode) {
        if (mode === 'override') {
            $listWrapper.empty();
        }

        $.each(objects, function (index, obj) {
            var $elm = cloneAndAppend(
                '.hidden.store-vendor-selector--list-element',
                $listWrapper
            );

            $elm.removeClass('hidden');
            $elm.find('a').text(obj.name);

            if (obj.storefront_status == 'Y') {
                var $icon = cloneAndAppend(
                    '.hidden.store-vendor-selector--list-element-storefront-status',
                    $elm.find('a')
                );

                $icon.removeClass('hidden');
            }

            var link = fn_url('') + (obj.append ? '?' + obj.append : '?switch_company_id=0');
            $elm.find('a').prop('href', link);
        });
    }

    /**
     * Utility function, clone element and append to another element.
     * @param {HTMLElement|string} what
     * @param {HTMLElement|string} where
     */
    function cloneAndAppend (what, where) {
        return ( $(what).clone().appendTo(where) );
    }

    /**
     * Toggle mobile navbar
     * @param {Event} e event
     */
    function toggleMobileMenu (e) {
        $('.navbar-admin-top').toggleClass('open');
        $('body').toggleClass('noscrolling');
    }

    /**
     * Toggle mobile overlay
     * @param {Event} e event
     */
    function toggleMobileOverlay (e) {
        $('.overlayed-mobile-menu').toggleClass('open');
    }

    /**
     * Toggle mobile navbar submenu
     * @param {Event} e event
     */
    function toggleMobileMenuSubmenu (e) {
        $('.menu-heading__title-block').toggleClass('openned');

        var targetChild = $('.menu-heading__dropdowned-menu');
        var targetContainer = $('.menu-heading__dropdowned');
        var magicBottomOffset = 5; // need for bottom shadow visibility

        if (targetContainer.height()) {
            targetContainer.height(0);
        } else {
            targetContainer.height(targetChild.height() + magicBottomOffset);
        }
    }

    /**
     * Creating overlay submenu from dropdown element
     * @param {Event} e event
     */
    function createSubmenuFromDropdown (e) {

        // Stop function, if not mobile resolution
        if (!($.matchScreenSize(['xs', 'xs-large', 'sm']))) {
            return;
        }

        var self = e.target,
            parent = self.parentElement,
            children = parent.childNodes,
            title = self.text,
            dropdown = undefined;

        // Stop function, if dropdown processing disabled manually
        if (($(self).data('disableDropdownProcessing')) || ($(parent).data('disableDropdownProcessing'))) {
            return;
        }

        // Find target dropdown (will be converted into overlay menu)
        for (var childIndex = 0; childIndex < children.length; childIndex++) {
            var child = children[childIndex];

            if (child.classList) {
                if (child.classList.contains('dropdown-menu')) {
                    dropdown = child;
                }
            }
        }

        // Stop function, if target dropdown not found
        if ($.isUndefined(dropdown)) {
            return;
        }

        // Converting
        e.preventDefault();
        convertDropdownToOverlayMenu(dropdown, title);
    }

    /**
     * Convert passed dropdown and title into overlay menu.
     * @param {HTMLElement} dropdown target dropdown
     * @param {string} title overlay title
     */
    function convertDropdownToOverlayMenu (dropdown, title) {
        var $secondMenu = $('.overlayed-mobile-menu-container'),
            $secondMenuTitle = $('.overlayed-mobile-menu__content');

        // Clean menu
        $secondMenu.empty();

        // Apply title for menu
        $secondMenuTitle.find('.overlayed-mobile-menu-title').text(title);

        // Apply dropdown content
        $(dropdown).clone().appendTo($secondMenu);

        // Open menu
        toggleMobileOverlay();
    }

    /**
     * Initialization mobile search
     */
    function initMobileSearch() {
        var $searchGroup = $('.cm-search-mobile-group');
        var $searchBlock = $('#' + $searchGroup.data('caSearchMobileBlock'));
        var $searchInput = $('#' + $searchGroup.data('caSearchMobileInput'));

        $('#' + $searchGroup.data('caSearchMobileBtn')).on('click', function (e) {
            e.preventDefault();
            $searchBlock.removeClass('hidden');
            $searchInput.prop("disabled", false);
            $searchInput.focus();
        });
        
        $('#' + $searchGroup.data('caSearchMobileBack')).on('click', function (e) {
            event.preventDefault();
            $searchInput.prop("disabled", true);
            $searchBlock.addClass('hidden');
        });

    }

})(Tygh, Tygh.$);