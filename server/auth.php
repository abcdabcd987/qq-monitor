<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Secret"');
    header('HTTP/1.0 401 Unauthorized');
    echo("oops");
    exit;
} elseif ($_SERVER['PHP_AUTH_USER'] != 'username' || $_SERVER['PHP_AUTH_PW'] != 'password') {
    echo("wrong answer");
    exit;
}
