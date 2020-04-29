<?php
if (isset($_COOKIE['tinkoff_redirect'])) {
    header('Location:' . $_COOKIE['tinkoff_redirect']);
}