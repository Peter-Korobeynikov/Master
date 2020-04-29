<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8" />

    <style>
        body {
            margin: 0 !important;
            overflow: hidden !important;
        }
        .fbapp-message {
            margin: 0 20px 20px 20px;
            text-align: left;
        }
        .fbapp-message p {
            font-family: sans-serif;
            background: #ffa9a9;
            padding: 16px 20px;
            border-radius: 3px;
            font-size: 13px;
            color: #ffffff;
        }
        .fbapp-message a {
            text-decoration: none;
            color: #28aced;
        }
    </style>
</head>
<body>
<div id="fb-root"></div>
<script type="text/javascript" data-no-defer>
    var app_id = '{$app_id}',
        page_id = '{$page_id}';

    window.fbAsyncInit = function () {
        FB.init({
            appId: app_id
        });
        FB.Canvas.setAutoGrow();
    };

    (function() {
        var e = document.createElement('script');
        e.async = true;
        e.src = '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
    }());

    var TYGH_FACEBOOK = {
        'external': '{$facebook_script_url}',
        'app_id': app_id,
        'page_id': page_id,
        'app_data': '',
        'url': 'https://www.facebook.com/pages/~/' + page_id + '?sk=app_' + app_id,
    };
</script>

{include file="backend:views/block_manager/widget_code.tpl"}
</body>
</html>