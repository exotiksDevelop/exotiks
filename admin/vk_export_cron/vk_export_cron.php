<?php
/**
 * Основной скрипт для запуска через cron автоэкспорта товаров вконтакте(модуль vkExport)
 * для OpenCart версии 2.x.x
 */

if (!isset($logfile) || !isset($route)) {
	die('Направление экспорта не установлено.');
}

if (file_exists(DIR_LOGS . $logfile) && !unlink(DIR_LOGS . $logfile)) {
    die('Ошибка прав доступа к файлу лога ' . DIR_LOGS . $logfile);
}

if (!ini_get('date.timezone')) {
    date_default_timezone_set('Europe/Moscow');
}

// лог
function logging($text, $logfile) {
    $date = date('d.m.y H:i:s');
    $msg = "Date: $date\n";
    $msg .= $text . " \n------------------------------------------------------\n";
    file_put_contents(DIR_LOGS . $logfile, $msg, FILE_APPEND);
}
logging('Старт скрипта', $logfile);
chmod(DIR_LOGS . $logfile, 0777);

// DB connect
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    logging("Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error, $logfile);
}
$sql = 'SELECT * FROM ' . DB_PREFIX . 'setting 
    WHERE `code` = \'vk_export\' AND (`key` = \'vk_export_cron_user\' OR `key` = \'vk_export_cron_pass\')';
$res = $mysqli->query($sql);
if ($mysqli->error) {
    trigger_error('Error: ' . $mysqli->error  . '<br />Error No: ' . $mysqli->errno . '<br />' . $sql);
    logging('Ошибка MySQL ' . $mysqli->error, $logfile);
    exit();
}
$user = array();
while ($row = $res->fetch_assoc()) {
    $user[$row['key']] = $row['value'];
}

if (!$user) {
    logging('не найдены данные пользователя для запуска автоэкспорта', $logfile);
    exit();
}

$server = HTTP_SERVER;

$ch = curl_init($server . 'index.php?route=common/login');
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// для базовой авторизации
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//curl_setopt($ch, CURLOPT_USERPWD, "username:password");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POST, true); // set POST method  
curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=' . $user['vk_export_cron_user'] . '&password=' . $user['vk_export_cron_pass']); // add POST fields
$res = curl_exec($ch);
$curl_error = curl_error($ch);
curl_close($ch);

if (preg_match('/Location: .+?index\.php\?route=common\/dashboard&token=(.+?)\n/i', $res, $matches)) {
    logging('Успешная авторизация в админке', $logfile);
}
else {
    
    $server = HTTPS_SERVER;

    $ch = curl_init($server . 'index.php?route=common/login');
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // для базовой авторизации
    //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($ch, CURLOPT_USERPWD, "username:password");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, true); // set POST method  
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=' . $user['vk_export_cron_user'] . '&password=' . $user['vk_export_cron_pass']); // add POST fields
    $res = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if (preg_match('/Location: .+?index\.php\?route=common\/dashboard&token=(.+?)\n/i', $res, $matches)) {
        logging('Успешная авторизация в админке', $logfile);
    }
    else {
        logging('неудачная авторизация в админке (пользователь ' . $user['vk_export_cron_user'] . ')', $logfile);
        logging($curl_error . PHP_EOL . $res, '.vk_export_cron_start_' . time());
        exit();
    }
}

$cookie_string = '';
preg_match_all('/Set-Cookie:(.+?)\n/i', $res, $cookies);
for ($i = 0; $i < count($cookies[0]); $i++) {
    $cookie_string .= trim($cookies[1][$i]) . '; ';
}

$ch = curl_init($server . 'index.php?route=extension/vk_export/' . $route . '&token=' . trim($matches[1]));
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$res = curl_exec($ch);
echo $res;

?>
