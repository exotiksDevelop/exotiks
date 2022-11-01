<?php
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
$_['version']           = '1.3.4';
$_['type']              = 'module';
$_['email']             = 'support@lutylab.ru';
$_['site']              = 'https://lutylab.ru';
$_['docs']              = 'https://docs.lutylab.ru/ll_ozon_exchange';
$_['api_service']       = 'https://rocket.ozon.ru';
$_['api_docs']          = 'https://docs.ozon.ru/api/rocket';
$_['api_auth_url']      = 'https://xapi.ozon.ru/principal-auth-api/connect/token';
$_['api_auth_url_test'] = 'https://api-stg.ozonru.me/principal-auth-api/connect/token';
$_['api_url']           = 'https://xapi.ozon.ru/principal-integration-api/v1';
$_['api_test_url']      = 'https://api-stg.ozonru.me/principal-integration-api/v1';
$_['variants']          = [
	'pickpoint' => [
		'code'  => 'pickpoint',
		'name'  => 'Самовывоз',
	],
	'postamat'  => [
		'code'  => 'postamat',
		'name'  => 'Постамат',
	],
	'courier'   => [
		'code'  => 'courier',
		'name'  => 'Курьер',
	],
];
$_['variants_map']      = ['pickpoint', 'postamat'];
$_['statuses']          = [
	'-1' => [
		'type'        => 'local',
		'code'        => '-1',
		'title'       => 'Требует редактирования',
		'description' => 'Заказ оформлен с заглушкой. Необходимо выбрать тариф, отредактировав заказ стандартными средствами движка.',
		'color'       => 'danger',
	],
	'0' => [
		'type'        => 'local',
		'code'        => '0',
		'title'       => 'Не отправлен',
		'description' => 'Необходимо выделить заказ и нажать <b>Создать отправление</b>.',
		'color'       => '',
	],
	'5' => [
		'type'        => 'shipping',
		'code'        => '5',
		'title'       => 'Отправление зарегистрировано',
		'description' => 'Манифест успешно загружен от принципала. Информация о заказе передана службе доставки.',
		'color'       => 'info',
	],
	'10' => [
		'type'        => 'shipping',
		'code'        => '10',
		'title'       => 'Передано в службу доставки',
		'description' => 'Отправление принято и оприходовано на складе.',
		'color'       => 'info',
	],
	'1010' => [
		'type'        => 'shipping',
		'code'        => '1010',
		'title'       => 'Отправление аннулировано',
		'description' => 'Отправление отменено.',
		'color'       => 'danger',
	],
	'20' => [
		'type'        => 'shipping',
		'code'        => '20',
		'title'       => 'Отправлено в ваш город',
		'description' => 'Отправка перевозки со склада.',
		'color'       => 'info',
	],
	'40' => [
		'type'        => 'shipping',
		'code'        => '40',
		'title'       => 'Прибыло в ваш город',
		'description' => 'Перевозка принята на складе субагента.',
		'color'       => 'info',
	],
	'45' => [
		'type'        => 'shipping',
		'code'        => '45',
		'title'       => 'Готово к выдаче',
		'description' => 'Отправление принято и оприходовано на складе субагента.',
		'color'       => 'info',
	],
	'50' => [
		'type'        => 'shipping',
		'code'        => '50',
		'title'       => 'Отправление выдано',
		'description' => 'Отправление выдано в пункте выдачи заказов.',
		'color'       => 'success',
	],
	'60' => [
		'type'        => 'shipping',
		'code'        => '60',
		'title'       => 'Отправление выдано частично',
		'description' => 'Отправление частично выдано в пункте выдачи заказов или при доставке курьером.',
		'color'       => 'success',
	],
	'65' => [
		'type'        => 'shipping',
		'code'        => '65',
		'title'       => 'Частичный возврат после выдачи',
		'description' => 'Клиентский возврат. Возврат части экземпляров после выдачи в пункте выдачи заказов.',
		'color'       => 'info',
	],
	'70' => [
		'type'        => 'shipping',
		'code'        => '70',
		'title'       => 'Отказ клиента',
		'description' => 'Отказ клиента от заказа или отказ клиента от заказа при доставке курьером.',
		'color'       => 'danger',
	],
	'80' => [
		'type'        => 'shipping',
		'code'        => '80',
		'title'       => 'Отправление не востребовано',
		'description' => 'Автовозврат, если отправление невостребовано. Автоматическая пометка отправления на возврат при истечении срока хранения в пункте выдачи согласно условиям хранения по договору.',
		'color'       => 'danger',
	],
	'90' => [
		'type'        => 'shipping',
		'code'        => '90',
		'title'       => 'Передано курьеру',
		'description' => 'Отправление передано курьеру.',
		'color'       => 'info',
	],
	'91' => [
		'type'        => 'shipping',
		'code'        => '91',
		'title'       => 'Выехал к клиенту',
		'description' => 'Отправление доставляется курьером.',
		'color'       => 'info',
	],
	'92' => [
		'type'        => 'shipping',
		'code'        => '92',
		'title'       => 'Передаётся клиенту',
		'description' => 'Отправление передаётся клиенту.',
		'color'       => 'info',
	],
	'93' => [
		'type'        => 'shipping',
		'code'        => '93',
		'title'       => 'Выполненo',
		'description' => 'Заказ выдан (при доставке курьером).',
		'color'       => 'success',
	],
	'99' => [
		'type'        => 'error',
		'code'        => '99',
		'title'       => 'Проблема',
		'description' => 'Статус заказа не определён, обратитесь к своему менеджеру.',
		'color'       => 'danger',
	],
	'100' => [
		'type'        => 'return',
		'code'        => '100',
		'title'       => 'Возврат отправлен на склад',
		'description' => 'Перевозка с возвратным заказом отправлена на склад.',
		'color'       => 'info',
	],
	'110' => [
		'type'        => 'return',
		'code'        => '110',
		'title'       => 'Возврат прибыл на склад',
		'description' => 'Перевозка с возвратным заказом прибыла на склад.',
		'color'       => 'info',
	],
	'115' => [
		'type'        => 'return',
		'code'        => '115',
		'title'       => 'Возврат готов к передаче отправителю',
		'description' => 'Возврат готов к передаче принципалу. Прибывший возврат обработан на складе и помещён в перевозку.',
		'color'       => 'info',
	],
	'120' => [
		'type'        => 'return',
		'code'        => '120',
		'title'       => 'Возврат передан отправителю',
		'description' => 'Возврат передан принципалу.',
		'color'       => 'success',
	],
	'997' => [
		'type'        => 'local',
		'code'        => '997',
		'title'       => 'Отгрузка создана в ЛК',
		'description' => 'Отгрузка зарегистрирована в базе данных доставки, теперь можно обновить статусы заказа.',
		'color'       => 'warning',
	],
	'998' => [
		'type'        => 'local',
		'code'        => '998',
		'title'       => 'Заказ создан в ЛК',
		'description' => 'Заказ зарегистрирован в базе данных доставки, теперь его можно отправить на отгрузку.',
		'color'       => 'warning',
	],
	'999' => [
		'type'        => 'local',
		'code'        => '999',
		'title'       => 'Ожидается подтверждение',
		'description' => 'Заказ зарегистрирован в базе данных доставки, но для подтверждения необходимо обновить статусы.',
		'color'       => 'warning',
	],
];
