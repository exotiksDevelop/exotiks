<?php
// Heading
$_['heading_title']      = 'Доставка Плюс';

// Text 
$_['text_extension']     = 'Дополнения';
$_['text_shipping']      = 'Доставка';
$_['text_success']       = 'Настройки модуля обновлены!';
$_['text_browse']        = 'Обзор';
$_['text_clear']         = 'Удалить';
$_['text_image_manager'] = 'Менеджер изображений';
$_['text_bib_zone']      = 'региона';
$_['text_bib_quantity']  = 'наличия товаров на складе';
$_['text_bib_weight']    = 'веса заказа';
$_['text_bib_total']     = 'стоимости заказа';
$_['text_for_free']      = 'бесплатно';
$_['text_not_logged_in'] = 'неавторизованные';
$_['text_form']          = 'Редактирование модуля Доставка Плюс';
$_['text_settings']      = 'Настройки';
$_['text_group']         = '-- Не выбрана --';
$_['text_restore']       = 'Восстановление из бэкапа';
$_['text_backup']        = 'Новый бэкап';

$_['button_apply']       = 'Применить';
$_['button_restore']     = 'Восстановить';
$_['button_add_discount'] = 'Добавить скидку';
$_['button_add_product_group'] = 'Добавить привязку доставок к группе';

//Tab
$_['tab_module']         = 'Модуль';
$_['tab_backup']         = 'Бэкап';
$_['tab_discount']       = 'Скидки/Наценки';
$_['tab_product_groups'] = 'Группы товаров';

// Column
$_['column_method']						= 'Вид доставки';
$_['column_geo_zone']					= 'Географическая зона';
$_['column_markup']						= 'Наценка';
$_['column_min_cost']				    = 'Мин. сумма за товары в корзине';
$_['column_max_cost']				    = 'Макс. сумма за товары в корзине';
$_['column_customer_group']				= 'Группа покупателей';
$_['column_discount_value']				= 'Значение';
$_['column_group']                      = 'Группа товаров';
$_['column_group_logic']                = 'Отображать эти доставки, если в корзине лежат товары из выбранной группы';

// Entry
$_['entry_name']         = 'Название:';
$_['entry_title']        = 'Название способа доставки:';
$_['entry_min_total']    = 'Минимальная сумма заказа:';
$_['entry_max_total']    = 'Максимальная сумма заказа:';
$_['entry_min_weight']   = 'Минимальный вес заказа:';
$_['entry_max_weight']   = 'Максимальный вес заказа:';
$_['entry_price']        = 'Цена:';
$_['entry_price_text']   = 'Текст для нулевой цены:';
$_['entry_geo_zone']     = 'Географическая зона:';
$_['entry_status']       = 'Статус:';
$_['entry_sort_order']   = 'Порядок сортировки:';
$_['entry_store']        = 'Сайт:';
$_['entry_rate']         = 'Цена зависящая от веса:';
$_['entry_city_rate']    = 'Исключить города:';
$_['entry_city_rate2']   = 'Отображать этот способ доставки только для городов:';
$_['entry_show_error_text'] = 'Показывать заглушку:';
$_['entry_image']        = 'Изображение:';
$_['entry_info']         = 'Описание:';
$_['entry_cost']         = 'Надбавка:';
$_['entry_weight_class'] = 'Единица измерения веса:';
$_['entry_value_for_total'] = 'В качестве суммы заказа брать все включая:';
$_['entry_bibb']            = 'Отображать заглушку зависящую от:';
$_['entry_customer_group']  = 'Группы покупателей:';
$_['entry_tax_class']       = 'Налоговый класс:';
$_['entry_group']           = 'Группа товаров:';
$_['entry_group_logic']     = 'Отображать этот способ доставки, если в корзине лежат товары из выбранной группы:';
$_['entry_notes']           = 'Для вставки JavaScript:';
$_['entry_title_tab']       = 'Альтернативное название для вкладки:';
$_['entry_show_description'] = 'Показать описание только тогда, когда этот вид доставки был выбран:';
$_['entry_currency']         = 'Валюта:';

$_['text_all']              = 'Все';
$_['text_any']              = 'Хотя бы один';
$_['text_no_one']           = 'Ни одного';
$_['text_spec_number']      = 'Больше чем';
$_['text_spec_number2']     = 'Меньше чем';
$_['text_fixed']				    = 'Число';
$_['text_percent_source_product']	= '% от стоимости товаров';
$_['text_percent_shipping']			= '% от стоимости доставки';
$_['text_factor']				    = 'Множитель';

// Help
$_['help_name']       = 'Это заголовок для блока выбора способов доставки.';
$_['help_min_total']  = 'Минимальная стоимость заказа, чтобы этот метод доставки стал доступен.';
$_['help_max_total']  = 'Максимальная стоимость заказа, чтобы этот метод доставки стал недоступен.';
$_['help_min_weight'] = 'Минимальный вес заказа, чтобы этот метод доставки стал доступен.';
$_['help_max_weight'] = 'Максимальный вес заказа, чтобы этот метод доставки стал недоступен.';
$_['help_rate']       = 'Например: 3000:250,5000:350 Вес:Цена,Вес:Цена и т.д.';
$_['help_city_rate']  = 'Список городов через точку с запятой, для которых данный способ доставки не будет отображаться, например: Москва;Санкт-Петербург';
$_['help_city_rate2'] = 'Список городов через точку с запятой, например: Москва;Санкт-Петербург';
$_['help_cost']       = 'Дополнительная плата за обработку/отправку заказа. Эта сумма добавляется к стоимости доставки.';
$_['help_total']		= 'Начиная с этой суммы правило действительно.';
$_['help_discount_value'] = 'Для множителя размер скидки/наценки расчитывается по формуле: (Сумма_товаров + Сумма_доставки) * Множитель';

// Error
$_['error_permission']        = 'У Вас нет прав для управления модулем Доставка Плюс!';
$_['error_title']             = 'Необходимо ввести название способа доставки!';
$_['error_store']             = 'Необходимо выбрать магазин!';
$_['error_warning']           = 'Внимательно проверьте форму на ошибки!';
$_['error_decimal']           = 'Это поле может содержать целые числа или десятичные числа написанные через точку!';
$_['error_procent']           = 'Это поле может содержать значения от 1 до 100!';
$_['error_tariff_list']	      = 'Выберите вид отправления!';
$_['error_empty']		      = 'Заполните эти значения!';
?>