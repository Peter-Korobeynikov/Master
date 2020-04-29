(function(_, $) {

    var last_scroll = null;
    var page_info = null;
    var magic_offset = 119;
    var magic_diff = 78;

    $.ceEvent('on', 'ce.notificationshow', function(n) {
        FB.Canvas.getPageInfo(function(info) {
            if (n.hasClass('cm-notification-content-extended')) {
                var vh = info.clientHeight + info.scrollTop * 2 - info.offsetTop - magic_offset * 2;
                if (vh < n.height()) {
                    vh = n.height() + magic_diff;
                }
                n.css('top', vh / 2 - (n.height() / 2));
            } else {
                var t = info.scrollTop - info.offsetTop;
                if (t < 0) {
                    t = 0;
                }
                n.css('top', t);
            }
        });
    });

    $.ceEvent('on', 'ce.loadershow', function(l) {

        l.hide();
        FB.Canvas.getPageInfo(function(info) {
            l.css('top', info.clientHeight / 2 + (magic_diff + info.scrollTop - info.offsetTop));
            l.show();
        });
    });

    $.ceEvent('on', 'ce.commoninit', function(context) {

        if (context.prop('id') == _.container) {
            setTimeout(function() {
                FB.Canvas.setSize({
                    height: context.height()
                });
            }, 100);
        }
    });

    var _refresh_page_info = true;

    $.ceEvent('on', 'ce.scrolltoelm', function(elm) {
        FB.Canvas.getPageInfo(function(info) {
            var elm_offset = elm.offset().top;
            last_scroll = info.offsetTop + elm_offset - magic_offset;
            FB.Canvas.scrollTo(0, last_scroll);

            if ($.ceDialog('inside_dialog', {jelm: elm})) {
                // Prevent refreshing page info to keep the scroll on the element after reloading the dialog
                _refresh_page_info = false;
            }
        });

    });

    function _poll(callback) {
        FB.Canvas.getPageInfo(function(info) {
            page_info = info;
            if (typeof(callback) != 'undefined') {
                callback();
            }
        });
    };

    // Refresh page info(scrollTop) before dialog opening
    $.widget('ui.dialog', $.ui.dialog, {
        open: function() {

            if (!_refresh_page_info) {
                setTimeout(function() {
                    _refresh_page_info = true;
                })
                return this._super();
            }

            var self = this;
            _poll(function () {
                _refresh_page_info = false;
                self.open();
            });
        }
    });

    _poll();

    var _parentHeight = $.fn.height;
    var _parentScrollTop = $.fn.scrollTop;

    $.fn.height = function() {
        if (arguments.length === 0 && $.isWindow(this[0])) {
            return page_info.clientHeight - 200;
        }

        return _parentHeight.apply(this, arguments);
    };

    $.fn.scrollTop = function() {
        if ($.isWindow(this[0])) {
            return page_info.scrollTop - page_info.offsetTop;
        }

        return _parentScrollTop.apply(this, arguments);
    }

}(Tygh, Tygh.$));