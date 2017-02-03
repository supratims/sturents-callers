<?php
require_once __DIR__.'/common.php';
session_unset();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . URL_OAUTH;
header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
