<?php
namespace progroman;

class Util {
    public static function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public static function isSSL() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
            || stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true || $_SERVER['SERVER_PORT'] == 443
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on');
    }

    public static function printp($msg = '', $head = null, $dump = false) {
        echo '<br/><pre>';
        if ($head) {
            echo '<b>' . $head . '</b><br/>';
        }

        $backtraces = debug_backtrace(0);
        $backtrace = $backtraces[0];
        if ($backtrace['file'] == __FILE__) {
            $backtrace = $backtraces[1];
        }

        echo '<small>', $backtrace['file'], ' (', $backtrace['line'], ')</small><br/>';

        if ($dump) {
            var_dump($msg);
        } else {
            print_r($msg);
        }

        echo '</pre>';
    }

    public static function printd($msg = '', $head = null, $dump = false) {
        printp($msg, $head, $dump);
        die;
    }

    public static function echonl($msg = '') {
        echo $msg, '<br>';
    }

    public static function backtrace() {
        echo '<br>---------------------------<br>';

        $backtraces = debug_backtrace(0);
        unset($backtraces[0]);

        foreach ($backtraces as $row) {

            if (isset($row['file'])) {
                echo '<b>File:</b> ', $row['file'], ' (', $row['line'], '): ';
            }

            if (isset($row['class'])) {
                echo '<b>Class: </b>', $row['class'] . $row['type'];
            }

            if (isset($row['function'])) {
                echo $row['function'], '()';
            }

            echo '<br>';
        }

        echo '<br>---------------------------<br>';
    }
}