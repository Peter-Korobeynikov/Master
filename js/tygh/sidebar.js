/*
 * Sidebar
 *
 */
(function ($) {
    var sidebars = [];

    var methods = {
        init: function () {
            var $self = $(this);

            $self.find('.sidebar-toggle').on('click', function () {
                methods._toggle($self);
            });

            methods._resize($self);
            sidebars.push($self);
        },

        resize: function () {
            return methods._resize(this);
        },

        toggle: function () {
            $(this).toggleClass('sidebar-open');
        },

        open: function () {
            if (!methods._is_open(this)) {
                $(this).addClass('sidebar-open');
            }
        },

        close: function () {
            if (methods._is_open(this)) {
                $(this).removeClass('sidebar-open');
            }
        },

        is_open: function () {
            return methods._is_open(this);
        },

        _toggle: function (elem) {
            $(elem).toggleClass('sidebar-open');
        },

        _resize: function (elem) {
            $(elem).css({
                "top": $('#actions_panel').height() + 'px'
            });
        },

        _is_open: function (elem) {
            return $(elem).hasClass('sidebar-open');
        }
    };

    $.fn.ceSidebar = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('ty.sidebar: method ' + method + ' does not exist');
        }
    };

    $(window).on('resize', function (e) {
        for (var i in sidebars) {
            methods._resize(sidebars[i]);
        }
    });
})(Tygh.$);
