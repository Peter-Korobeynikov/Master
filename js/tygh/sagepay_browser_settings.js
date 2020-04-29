(function(_, $){

    var browserUserAgent = function () {
        return (navigator.userAgent || null);
    };

    var browserLanguage = function () {
        return (navigator.language || navigator.userLanguage || navigator.browserLanguage || navigator.systemLanguage || null);
    };

    var browserColorDepth = function () {
        if (screen.colorDepth || window.screen.colorDepth) {
            return new String(screen.colorDepth || window.screen.colorDepth);
        }
        return null;
    };

    var browserScreenHeight = function () {
        if (window.screen.height) {
            return new String(window.screen.height);
        }
        return null;
    };

    var browserScreenWidth = function () {
        if (window.screen.width) {
            return new String(window.screen.width);
        }
        return null;
    };

    var browserTZ = function () {
        return new String(new Date().getTimezoneOffset());
    };

    var browserJavaEnabled = function () {
        return (navigator.javaEnabled() || null);
    };

    var browserJavascriptEnabled = function () {
        return (true);
    };

    $('#browser_settings').find('input[name="browser_settings[user_agent]"]').val(browserUserAgent);
    $('#browser_settings').find('input[name="browser_settings[language]"]').val(browserLanguage);
    $('#browser_settings').find('input[name="browser_settings[color_depth]"]').val(browserColorDepth);
    $('#browser_settings').find('input[name="browser_settings[screen_height]"]').val(browserScreenHeight);
    $('#browser_settings').find('input[name="browser_settings[screen_width]"]').val(browserScreenWidth);
    $('#browser_settings').find('input[name="browser_settings[timezone]"]').val(browserTZ);
    $('#browser_settings').find('input[name="browser_settings[java_enabled]"]').val(browserJavaEnabled);
    $('#browser_settings').find('input[name="browser_settings[js_enabled]"]').val(browserJavascriptEnabled);

})(Tygh, Tygh.$);