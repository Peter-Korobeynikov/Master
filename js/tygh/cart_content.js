// Recalculate cart content functionality without page reload

// NOTE: `$('.ty-btn--recalculate-cart').click();` used for sending AJAX request
// and dynamically update page content

(function (_, $) {
    var delay = {
        qty: 500,
        inputs: 500
    };

    // Check that we are on the cart page and recalculate button exist
    if (!$('[name=checkout_form]').length && !$('.ty-btn--recalculate-cart').length) { 
        return;
    }

    // Slight delay (before ajax recalculate) 
    // for the case when the quantity of product changes
    $(_.doc).on(
        'click',
        '.ty-cart-content__qty .cm-increase, .ty-cart-content__qty .cm-decrease',
        $.debounce(recalculate.bind(undefined), delay.qty)
    );

    $(_.doc).on(
        'change',
        '#cart_items .cm-cart-contents-updatable-field, .ty-cart-content__qty .cm-amount',
        $.debounce(recalculate.bind(undefined), delay.inputs)
    );

    // Ajax recalculate when product's option changed
    $.ceEvent('on', 'ce.product_option_changed_post', recalculate.bind(undefined));

    function recalculate() {
        $('.ty-btn--recalculate-cart').click();
    }

})(Tygh, Tygh.$);
