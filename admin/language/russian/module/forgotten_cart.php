<?php
// Heading
$_['heading_title']                     = '<b style="color: #f0563d;">ZR code</b> | Брошенная корзина';

// Text
$_['text_module']                       = 'Дополнения';
$_['text_success']                      = 'Модуль "ZR code | Брошенная корзина" успешно обновлен!';
$_['text_success_message']              = 'Письмо для пользователя успешно отправлено!';
$_['text_success_remove_customer']      = 'Пользователь успешно удалён!';
$_['text_edit']                         = 'Редактировать';
$_['text_remove']                       = 'Удалить';
$_['text_and']                          = 'И';
$_['text_or']                           = 'ИЛИ';
$_['text_customer']                     = 'Клиенты с брошенной корзиной';
$_['text_percent']                      = 'Процент';
$_['text_sum']                          = 'Сумма';
$_['text_customer_edit']                = 'Просмотр клиента';
$_['text_send_message']                 = 'Отправить письмо';
$_['text_send_message_repeated']        = 'Отправить повторное письмо';
$_['text_subject']                      = '[%customer_name%] у Вас остался товар в корзине';
$_['text_subject_manager']              = 'У клиента остался товар в корзине';
$_['text_message']                      = "<div style='min-width: 320px;max-width: 640px;margin: 0 auto;'>\n<img src='[%logo%]' style='max-width: 100%;'>\n<br>\n<p>Уважаемый [%customer_name%]<br>Вы оставили товар в корзине, на нашем сайте.</p><br>\n<p>Предлагаем Вам вернуться на сайт и совершить покупку.<br>Ваш товар до сих пор находится в корзине.</p>[%template_coupon%]\n[%template_products%]\n[%template_shipping%]\n<br>\n<p style='text-align: center;'><a href='[%cart_link%]' style='text-decoration: none;background: #0EA012;color: #fff;font-size: 20px;padding: 7px 12px;font-weight: bold;line-height: 30px;-webkit-border-radius: 8px;-moz-border-radius: 8px;border-radius: 8px;text-transform: uppercase;'>Перейти в корзину</a></p>[%related_block%]\n<br>\n<br>\n<p>С уважением,<br>администрация сайта <b>[%site_name%]</b></p></div>";
$_['text_message_manager']              = "<div style='min-width: 320px;max-width: 640px;margin: 0 auto;'>\n<img src='[%logo%]' style='max-width: 100%;'>\n<br>\n<p>У клиента остался товар в корзине.</p><br>\n<p>Имя: [%customer_name%]</p><p>E-mail: [%customer_email%]</p>\n<p>Телефон: [%customer_phone%]</p>\n[%template_coupon%]\n[%template_shipping%]\n[%template_products%]\n<br>\n</div>";
$_['text_coupon_name']                  = '[Брошенная корзина] Скидка для клиента %s';
$_['text_template_products']            = "<div style='border: 1px solid #ddd;margin-bottom: 15px;min-height:100px;'>\n<div style='float: left;text-align: center;'><a href='[%link%]'><img src='[%image%]'></a></div><div style='margin-left: 105px;'>\n<h4><a href='[%link%]'>[%name%]</a></h4><p>[%options%]</p><p>[%price%]</p><p>Модель: [%model%]</p><p>Кол-во: [%quantity%]</p><p style='font-size: 16px'>Итого: [%sum%]</p></div></div>";
$_['text_template_shipping']            = '<p>Доставка: <b>бесплатно</b></p>';
$_['text_template_coupon']              = "<br>\n<p>Спешим сообщить, что купить данные товары Вы можете со скидкой <b>[%discount%]</b></p><p>Для получения скидки вернитесь на сайт и используйте код купона: <b style='font-size: 18px;'>[%coupon%]</b></p><br>";
$_['text_template_coupon_manager']      = "<p>Скидка: [%discount%]</p><p>Купон: [%coupon%]</p><br>";
$_['text_related_block']                = "<h3>Вам будет интересно</h3>\n[%template_products%]";
$_['text_template_products_related']    = "<div style='border: 1px solid #ddd;margin-bottom: 15px;min-height:100px;'>\n<div style='float: left;text-align: center;'><a href='[%link%]'><img src='[%image%]'></a></div><div style='margin-left: 105px;'>\n<h4><a href='[%link%]'>[%name%]</a></h4><p>Модель: [%model%]</p><p>[%price%]</p></div></div>";
$_['text_hour']                         = 'ч.';
$_['text_discount_setting']             = 'Настройка скидки';
$_['text_discount_message']             = 'Только для основного письма';
$_['text_discount_repeated_message']    = 'Только для повторного письма';
$_['text_discount_messages']            = 'Для основного и повторного письма';
$_['text_percent']                      = 'Процент';
$_['text_amount']                       = 'Фиксированная сумма';
$_['text_from']                         = 'от';
$_['text_message_title']                = 'Основное письмо';
$_['text_message_repeated_title']       = 'Повторное письмо';
$_['text_help_sitename']                = 'Название магазина';
$_['text_help_sitephone']               = 'Телефон магазина';
$_['text_help_customername']            = 'Имя клиента';
$_['text_help_customer_email']          = 'E-mail клиента';
$_['text_help_customer_phone']          = 'Телефон клиента';
$_['text_help_logo']                    = 'Ссылка на логотип';
$_['text_help_cart_link']               = 'Ссылка на корзину';
$_['text_help_product_name']            = 'Название товара';
$_['text_help_product_model']           = 'Модель товара';
$_['text_help_product_options']         = 'Опции товара';
$_['text_help_product_image']           = 'Ссылка на изображение товара';
$_['text_help_product_quantity']        = 'Количество товара в корзине';
$_['text_help_product_price']           = 'Цена за единицу товара';
$_['text_help_product_sum']             = 'Общая цена (цена * количество)';
$_['text_help_product_link']            = 'Ссылка на товар';
$_['text_help_coupon_code']             = 'Код купона на скидку';
$_['text_help_coupon_discount']         = 'Скидка купона';
$_['text_help_products_count']          = 'Количество товаров';
$_['text_help_el']                      = 'Вспомогательные элементы:';
$_['text_cron_link']                    = 'Ссылка на cron';
$_['text_related_template_setting']     = 'Настройка шаблона';
$_['text_total']                        = 'Итого';
$_['text_first_visit']                  = 'Зашел на сайт';
$_['text_last_visit']                   = 'Вышел с сайта';
$_['text_time_spent']                   = 'Провёл на сайте';
$_['text_activation']                   = 'Активация модуля';
$_['text_how_get_license']              = 'Как получить лицензионный ключ?';
$_['text_license']                      = 'Для активации модуля отправьте на email <a href="mailto:support@zr-code.com">support@zr-code.com</a> письмо с темой <b>"Активация - брошенная корзина"</b> и укажите в нём адрес сайта на котором была произведена покупка, номер счета Вашей оплаты и доменное имя сайта на котором собираетесь использовать модуль. Полученный в ответ лицензионный ключ вставьте в поле ниже и нажмите на кнопку "Сохранить".<br><br><br>С уважением,<br>команда <a href="https://zr-code.com" target="_blank"><b style="color: #f0563d;">ZR code</b></a>';

// Column
$_['column_customer']                   = 'Клиент';
$_['column_cart']                       = 'Корзина';
$_['column_date_time']                  = 'Дата и время';
$_['column_last_page']                  = 'Последняя страница';
$_['column_coupon']                     = 'Скидка';
$_['column_action']                     = 'Действие';

// Tabs
$_['tab_general']                       = 'Основное';
$_['tab_messages']                      = 'Настройка писем';
$_['tab_related']                       = 'Сопутствующие товары';
$_['tab_customers']                     = 'Клиенты';

// Entry
$_['entry_status']                      = 'Статус';
$_['entry_repeated_message']            = 'Повторное письмо';
$_['entry_auto_send_message']           = 'Автоматическая отправка';
$_['entry_manager_notifi']              = 'Оповещение менеджера';
$_['entry_manager_notifi_email']        = 'E-mail менеджера';
$_['entry_manager_notifi_time']         = 'Время отправки оповещения менеджеру';
$_['entry_customer_notifi']             = 'Оповещение покупателя';
$_['entry_subject']                     = 'Тема сообщения';
$_['entry_message']                     = 'Сообщение';
$_['entry_general_message_time']        = 'Время отправки основного письма';
$_['entry_repeated_message_time']       = 'Время отправки повторного письма';
$_['entry_type']                        = 'Тип';
$_['entry_discount']                    = 'Скидка';
$_['entry_shipping']                    = 'Бесплатная доставка';
$_['entry_sum']                         = 'Сумма';
$_['entry_template_products']           = 'Шаблон товаров';
$_['entry_template_shipping']           = 'Шаблон доставки';
$_['entry_template_coupon']             = 'Шаблон скидки';
$_['entry_related_status']              = 'Статус';
$_['entry_related_attribute']           = 'Атрибуты';
$_['entry_related_attribute_condition'] = 'Условие атрибут';
$_['entry_related_option']              = 'Опции';
$_['entry_related_option_condition']    = 'Условие опций';
$_['entry_related_price_step']          = 'Шаг цены';
$_['entry_related_limit']               = 'Лимит товаров';
$_['entry_related_block']               = 'Блок сопутствующих товаров';
$_['entry_license_key']                 = 'Лицензионный ключ';

// Help
$_['help_sum']                          = 'Минимальная сумма корзины, начиная с которой скидка действительна.';
$_['help_javascript']                   = 'Автоматическая отправка осуществляется если на сайте есть пользователи.';
$_['help_cron']                         = 'Автоматическая отправка не зависит от трафика сайта (подробности по настройке в инструкции к модулю)';
$_['help_manager_email']                = 'Если не задан e-mail адрес, то оповещения будут приходить на e-mail магазина';
$_['help_price_step']                   = 'Если значение 0, то шаг цены не будет учитыватся';
$_['help_condition']                    = 'Условие &quot;ИЛИ&quot; выводит подборку товаров, где совпадает хотя бы 1 значение. &quot;И&quot; - если совпадают все значения.';

// Button
$_['button_discount_add']               = 'Добавить скидку';
$_['button_discount_remove']            = 'Удалить скидку';

// Error
$_['error_permission']                  = 'У вас нет прав для управления данным модулем!';
$_['error_warning']                     = 'Внимательно проверьте форму на ошибки!';
$_['error_license']                     = 'Неверный лицензионный ключ!';
$_['error_manager_notifi_email']        = 'Некорректный e-mail адрес';
$_['error_manager_notifi_time']         = 'Время отправки оповещения менеджеру должно быть не менее 1 часа';
$_['error_general_message_time']        = 'Время отправки основного письма должно быть не менее 1 часа';
$_['error_repeated_message_time']       = 'Время отправки повторного письма должно быть больше времени отправки основного письма';
$_['error_subject']                     = 'Тема сообщения должна содержать от 3 символов';
$_['error_message']                     = 'В сообщении должны присутствовать вспомогательные элементы <b>[%template_coupon%]</b> и <b>[%template_shipping%]</b>';
$_['error_coupon']                      = 'В шаблоне скидки должен присутствовать вспомогательный элемент <b>[%coupon%]</b>';
$_['error_shipping']                    = 'Шаблон доставки должен содержать от 3 символов';
$_['error_related']                     = 'Для работы сопутствующих товаров укажите атрибут или опцию!';
$_['error_related_block']               = 'В блоке должен присутствовать вспомогательный элемент <b>[%template_products%]</b>';
$_['error_template_products_related']   = 'В шаблоне товаров должны присутствовать вспомогательные элементы <b>[%name%]</b> и <b>[%link%]</b>';

//
$_['forgotten_cart_date_format']        = 'd.m.Y в H:i:s';