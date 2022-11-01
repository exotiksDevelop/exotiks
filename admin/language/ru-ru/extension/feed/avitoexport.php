<?php
// Heading
$_['heading_title']   			     = 'Экспорт в XML для Avito';

// Text
$_['text_module']        			 = 'Каналы продвижения';
$_['text_update_success']   	     = 'Настройки модуля Экспорт в XML для Avito обновлены!';

$_['text_avitoexport_head']    		 = 'Редактирование Экспорт в XML для Avito';
$_['text_avitoexport_enable']        = 'Статус';
$_['text_avitoexport_allowEmail']    = 'Разрешить сообщения:';
$_['text_avitoexport_name']  		 = 'Контактное лицо:';
$_['text_avitoexport_phone']  		 = 'Контактный телефон:';
$_['text_avitoexport_region'] 		 = 'Регион';
$_['text_avitoexport_subway'] 		 = 'Ближайшая станция метро:';
$_['text_avitoexport_district'] 	 = 'Район:';
$_['text_avitoexport_city']          = 'Город:';
$_['text_avitoexport_categ'] 		 = 'Категории';
$_['text_avitoexport_date_mode']     = 'Режим публикации:';
$_['text_avitoexport_stock']         = 'Выбрать только товары в наличии';
$_['text_avitoexport_package']       = 'Вариант платного размещения';
$_['text_avitoexport_stat']   		 = 'Дополнительные услуги';
$_['text_avitoexport_ignore'] 	 	 = 'Черный список:';
$_['text_avitoexport_delete'] 	 	 = 'Снять с публикации уже опубликованные объявления:';
$_['text_avitoexport_feed'] 		 = 'Адрес:';
$_['text_avitoexport_adtype'] 		 = 'Тип объявления:';

$_['val_avitoexport_undefined']   	 = '-- Не выбрано --';

$_['section_avitoexport_contact'] 	 = 'Контактная информация';
$_['section_avitoexport_location'] 	 = 'Местоположение';
$_['section_avitoexport_categories'] = 'Соответствие категорий';
$_['section_avitoexport_settings'] 	 = 'Настройки публикации';

$_['step_avitoexport_one'] 			 = '-- Категория в магазине --';
$_['step_avitoexport_two'] 			 = '-- Категория на Avito --';
$_['step_avitoexport_three'] 		 = '-- Подкатегория на Avito --';

$_['hint_avitoexport_date_mode'] 	 = '"В один день" - все объявления будут опубликованы в один день.</br>"Каждый день" - каждый день будет публиковаться 1/30 всех объявлений(в расчете на 30 дней месяца).</br>"Раз в неделю" - каждые 7 дней будет публиковаться 1/4 всех объявлений.';
$_['hint_avitoexport_categ'] 		 = 'Установите соответствие между категориями';
$_['hint_avitoexport_pack_package']	 = 'Размещение объявления осуществляется только при наличии подходящего пакета размещения.';
$_['hint_avitoexport_pack_pSingle']	 = 'При наличии подходящего пакета оплата размещения объявления произойдет с него; если нет подходящего пакета, но достаточно денег на кошельке Avito, то произойдет разовое размещение.';
$_['hint_avitoexport_pack_single'] 	 = 'Только разовое размещение, произойдет при наличии достаточной суммы на кошельке Avito; если есть подходящий пакет размещения, он будет проигнорирован.';
$_['hint_avitoexport_ignore'] 	     = 'Перечислите через запятую id товаров,которые стоит игнорировать.<br>Пример:89,78,44';
$_['hint_avitoexport_delete'] 	 	 = 'Перечислите через запятую id товаров,объявления о продаже которых уже опубликованы, чтобы снять их с публикации.<br>Чтобы снять <u>ВСЕ</u> объявления, введите <strong>STOP_ALL</strong>.<br>Объявления будут удалены после следующего цикла автозагрузки.';

// Error
$_['error_permission']    			= 'У Вас нет прав для управления этим модулем!';
$_['error_name']          			= 'Поле обязательно для заполнения';
$_['error_phone']         			= 'Введите номер телефона';
$_['error_region']        			= 'Укажите регион';
$_['error_location']          		= 'Выберите значение';

$_['warning_category']   			= 'Предупреждение: Категории не выбраны';
?>