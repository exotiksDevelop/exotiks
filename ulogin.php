<?php

if (is_file('config.php')) {
    require_once('config.php');
}
if ($_POST) {
    session_start();
    $_SESSION['ulogin_token'] = $_POST;
    header('Location: ' . HTTP_SERVER . 'index.php?route=module/ulogin');
}
else {
    header('Location: ' . HTTP_SERVER);
}