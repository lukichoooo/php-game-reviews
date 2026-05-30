<?php
$AUTH_COOKIE_EXPARATION =  3600;
session_start();
session_destroy();
setcookie('remember_token', '', time() - $AUTH_COOKIE_EXPARATION, '/');
header('Location: index.php');
exit;
