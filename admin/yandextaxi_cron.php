<?php
// Configuration
if (is_file('config.php')) {
    require_once('config.php');
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('yandextaxi_cron');
