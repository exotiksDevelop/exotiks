<?php
$_ = array_merge(
  (isset($_)?$_:array()),
  array (
  'text_success' => 'Заказ обновлен',
  'error_permission' => 'Внимание! Доступ запрещен к API!',
  'error_customer' => 'Необходимы данные о клиенте!',
  'error_payment_address' => 'Необходим адрес плательщика!',
  'error_payment_method' => 'Необходим способ оплаты!',
  'error_shipping_address' => 'Необходим адрес доставки!',
  'error_shipping_method' => 'Необходим способ доставки!',
  'error_stock' => 'Товары отмеченные *** не доступны в нужном количестве или нет на складе!',
  'error_minimum' => 'Минимальное кол-во для заказа %s is %s!',
  'error_not_found' => 'Внимание! Заказ не найден',
));

