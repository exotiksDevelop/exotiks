<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
$_['log'] = [
	0 => [
		'code'  => 'error',
		'title' => 'Запрос окончился ошибкой',
	],
	1 => [
		'code'  => 'success',
		'title' => 'Успешный запрос',
	],
	2 => [
		'code'  => 'info',
		'title' => 'Запрос вернул пустой результат',
	],
];
$_['sms'] = [
	'smscab_ru' => [
		'code'    => 'smscab_ru',
		'title'   => 'smscab.ru',
		'url'     => 'http://my.smscab.ru/sys/send.php',
		'options' => [
			'login'  => 'login',
			'psw'    => 'password',
			'phones' => 'to',
			'mes'    => 'message',
			'sender' => 'sender',
			'fmt'    => 3, // получаем json ответ
			'cost'   => 3, // получаем в ответе стоимость и новый баланс
			'pp'     => 570346,
		],
	],
	'sms_sending_ru' => [
		'code'    => 'sms_sending_ru',
		'title'   => 'sms-sending.ru',
		'url'     => 'https://lcab.sms-sending.ru/lcabApi/sendSms.php',
		'options' => [
			'login'    => 'login',
			'password' => 'password',
			'to'       => 'to',
			'txt'      => 'message',
			'source'   => 'sender',
		],
	],
	'smsaero_ru' => [
		'code'    => 'smsaero_ru',
		'title'   => 'smsaero.ru',
		'url'     => 'https://gate.smsaero.ru/send/',
		'options' => [
			'user'     => 'login',
			'password' => 'password',
			'to'       => 'to',
			'text'     => 'message',
			'from'     => 'sender',
		],
	],
	'sms_ru' => [
		'code'    => 'sms_ru',
		'title'   => 'sms.ru',
		'url'     => 'https://sms.ru/sms/send',
		'options' => [
			'login'      => 'login',
			'password'   => 'password',
			'to'         => 'to',
			'msg'        => 'message',
			'from'       => 'sender',
			'partner_id' => 322497,
			'json'       => 1, // получаем json ответ
		],
	],
	'bytehand_com' => [
		'code'    => 'bytehand_com',
		'title'   => 'bytehand.com',
		'url'     => 'https://api.bytehand.com/v1/send',
		'options' => [
			'id'   => 'login',
			'key'  => 'password',
			'to'   => 'to',
			'text' => 'message',
			'from' => 'sender',
		],
	],
	'smsc_ru' => [
		'code'    => 'smsc_ru',
		'title'   => 'smsc.ru',
		'url'     => 'https://smsc.ru/sys/send.php',
		'options' => [
			'login'  => 'login',
			'psw'    => 'password',
			'phones' => 'to',
			'mes'    => 'message',
			'sender' => 'sender',
			'cost'   => 2, // 1 - test
			'fmt'    => 3, // получаем json ответ
			'pp'     => 601466,
		],
	],
];
