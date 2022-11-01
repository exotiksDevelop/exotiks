<?php

// Heading
$_['heading_title'] = '<img width="24" height="24" src="view/image/neoseo.png" style="float: left;"><span style="margin:0;line-height: 24px;">NeoSeo Обмен с Bitrix24</span>';
$_['heading_title_raw'] = 'NeoSeo Обмен с Bitrix24';

//Tabs
$_['tab_general'] = 'Основные настройки';
$_['tab_logs'] = 'Логи';
$_['tab_support'] = 'Поддержка';
$_['tab_license'] = 'Лицензия';
$_['tab_lead'] = 'Лиды';
$_['tab_deal'] = 'Сделки';
$_['tab_contact'] = 'Контакты';

// Text
$_['text_module_version'] = '';
$_['text_success'] = 'Настройки модуля обновлены!';
$_['text_module'] = 'Модули';
$_['text_success_clear'] = 'Лог файл успешно очищен!';
$_['text_clear_log'] = 'Очистить лог';
$_['text_clear'] = 'Очистить';
$_['text_match_table_contact_type'] = 'Таблица соответствий: Группа покупателя на сайте - Тип контакта в Bitrix24';
$_['text_match_table_deal_stage'] = 'Таблица соответствий: Статус заказа на сайте - Стадия сделки в Bitrix24';
$_['text_match_table_deal_type'] = 'Таблица соответствий: Категория на сайте - Тип сделки в Bitrix24';

//Columns
$_['column_customer_group'] = 'Группа покупателя';
$_['column_type_contact'] = 'Тип контакта';
$_['column_order_status'] = 'Статус заказа';
$_['column_deal_stage'] = 'Стадия сделки';
$_['column_category'] = 'Категория';
$_['column_deal_type'] = 'Тип сделки';

//Buttons
$_['button_save'] = 'Сохранить';
$_['button_save_and_close'] = 'Сохранить и Закрыть';
$_['button_close'] = 'Закрыть';
$_['button_recheck'] = 'Проверить еще раз';
$_['button_clear_log'] = 'Очистить лог';
$_['button_download_log'] = 'Скачать файл логов';

// Entry
$_['entry_debug'] = 'Отладочный режим:<br /><span class="help">В логи модуля будет писаться различная информация для разработчика модуля.</span>';
$_['entry_status'] = 'Статус';
$_['entry_portal_name'] = 'Название портала';
$_['entry_portal_name_desc'] = 'Название портала можно получить через ссылку в создании вебхука. Указывается лишь название портала, без bitrix24. Например, название портала test.bitrix24. В поле необходимо указать <b>test</b>';
$_['entry_id_user'] = 'Идентификатор пользователя';
$_['entry_id_user_desc'] = 'Числовой идентификатор пользователя, создавшего вебхук. Под правами этого пользователя будет работать этот вебхук. ИД можно получить через ссылку в создании вебхука';
$_['entry_secret_code'] = 'Секретный код';
$_['entry_secret_code_desc'] = 'Секретный код, можно получить через ссылку в создании вебхука';
$_['entry_domain'] = 'Домен для синхронизации';

$_['entry_lead_user_id'] = 'Пользователь, который ответственный за лиды';

$_['entry_add_lead_register'] = 'Создавать лид при регистрации';
$_['entry_add_lead_register_desc'] = 'Лид будет создан только при использовании стандартных методов регистрации';
$_['entry_add_lead_neoseo_catch_contacts'] = 'Создавать лид при создании заявки в модуле "NeoSeo Захват контактов"';
$_['entry_add_lead_neoseo_catch_contacts_desc'] = 'Лид будет создан только при наличии установленного модуля "NeoSeo Захват контактов"';
$_['entry_add_lead_neoseo_notify_when_available'] = 'Создавать лид при создании заявки в модуле "NeoSeo Подписка на поступление товара"';
$_['entry_add_lead_neoseo_notify_when_available_desc'] = 'Лид будет создан только при наличии установленного модуля "NeoSeo Подписка на поступление товара"';

$_['entry_source_lead_register'] = 'Источник при  регистрации';
$_['entry_source_lead_neoseo_account_register'] = 'Источник при регистрации в модуле "NeoSeo Личный кабинет покупателя"';
$_['entry_source_lead_neoseo_checkout_register'] = 'Источник при регистрации в модуле "NeoSeo Оформление заказа"';
$_['entry_source_lead_neoseo_catch_contacts'] = 'Источник при создании заявки в модуле "NeoSeo Захват контактов"';
$_['entry_source_lead_neoseo_notify_when_available'] = 'Источник при создании заявки в модуле "NeoSeo Подписка на поступление товара"';

$_['entry_add_deal_order'] = 'Создавать сделку при создании заказа';
$_['entry_add_deal_order_desc'] = 'Сделка будет создана при использовании стандартных методов создания заказа';
$_['entry_deal_user_id'] = 'Пользователь, который ответственный за сделки';
$_['entry_deal_stage_default'] = 'Стадия сделки по умолчанию';
$_['entry_deal_stage_default_desc'] = 'Применяется, если статус заказа не выбран в таблице соответствий.';
$_['entry_deal_type_default'] = 'Тип сделки по умолчанию';
$_['entry_deal_type_default_desc'] = 'Применяется, если категория не выбрана в таблице соответствий.';
$_['entry_deal_extra_property'] = 'Выгружать расширенные данные по сделке';
$_['entry_deal_extra_property_desc'] = 'Если в заказе Вам нужно выгружать дополнительные даные по сделке, укажите <b>Название;Таблица БД;Поле</b>, где <b>Название</b> - название идентификатора поля в Битрикс24, в который будет помещены дополнительные даные, <b>Таблица БД</b> - таблица из которой будет взято дополнительные даные, <b>Поле</b> - поле таблицы, из которого будет взято значение и помещено в дополнительное поле. Обязательное наличие в таблице одного из полей (order_id | customer_id). Разбор указанных полей должен быть настроен в Битрикс 24. Каждая запись с новой строки. <br> <b>Пример:</b> <br> UF_CRM_5EB107860C481;oc_order;shipping_address_1';
$_['entry_unload_options'] = 'Выгружать опции в комментарии к сделке';
$_['entry_unload_order_status'] = 'Создавать сделку, если заказу присвоен статус';

$_['entry_add_contact'] = 'Создавать контакт перед созданием лида';
$_['entry_contact_user_id'] = 'Пользователь, который ответственный за контакты';
$_['entry_source_contact'] = 'Источник при создании контакта';
$_['entry_type_contact_default'] = 'Тип контакта по умолчанию';
$_['entry_type_contact_default_desc'] = 'Применяется, если группа покупателя не выбрана в таблице соответствий.';

// Error
$_['error_permission'] = 'У Вас нет прав для управления этим модулем!';
$_['error_empty_params'] = 'Для отображения списка выбора пользователей и источников (вкладка Контакты, Лиды и Сделки) необходимо заполнить основные параметры модуля и сохранить настройки!';
$_['error_download_logs'] = 'Файл логов пустой или отсутствует!';
$_['error_ioncube_missing'] = '';
$_['error_license_missing'] = '';
$_['mail_support'] = '';
$_['module_licence'] = '';
$_['text_module_version']='7';
$_['error_license_missing']='<h3 style = "color: red"> Missing file with key! </h3>

<p> To obtain a file with a key, contact NeoSeo by email <a href="mailto:license@neoseo.com.ua"> license@neoseo.com.ua </a>, with the following: </p>

<ul>
	<li> the name of the site where you purchased the module, for example, https://neoseo.com.ua </li>
	<li> the name of the module that you purchased, for example: NeoSeo Sharing with 1C: Enterprise </li>
	<li> your username (nickname) on this site, for example, NeoSeo</li>
	<li> order number on this site, e.g. 355446</li>
	<li> the main domain of the site for which the key file will be activated, for example, https://neoseo.ua</li>
</ul>

<p>Put the resulting key file at the root of the site, that is, next to the robots.txt file and click the "Check again" button.</p>';
$_['error_ioncube_missing']='<h3 style="color: red">No IonCube Loader! </h3>

<p>To use our module, you need to install the IonCube Loader.</p>

<p>For installation please contact your hosting TS</p>

<p>If you can not install IonCube Loader yourself, you can also ask for help from our specialists at <a href="mailto:info@neoseo.com.ua"> info@neoseo.com.ua </a> </p>';
$_['module_licence']='<h2>NeoSeo Software License Terms</h2>
<p>Thank you for purchasing our web studio software.</p>
<p>Below are the legal terms that apply to anyone who visits our site and uses our software products or services. These Terms and Conditions are intended to protect your interests and interests of LLC NEOSEO and its affiliated entities and individuals (hereinafter referred to as "we", "NeoSeo") acting in the agreements on its behalf.</p>
<p><strong>1. Introduction</strong></p>
<p>These Terms of Use of NeoSeo (the "Terms of Use"), along with additional terms that apply to a number of specific services or software products developed and presented on the NeoSeo website (s), contain terms and conditions that apply to each and every one of them. the visitor or user ("User", "You" or "Buyer") of the NeoSeo website, applications, add-ons and components offered by us along with the provision of services and the website, unless otherwise noted (all services and software, software Modules offered through the NeoSeo website or auxiliary servers Isa, web services, etc. Applications on behalf NeoSeo collectively referred to as - "NeoSeo Service" or "Services").</p>
<p>NeoSeo Terms are a binding contract between NeoSeo and you - so please carefully read them.</p>
<p>You may visit and/or use the NeoSeo Services only if you fully agree to the NeoSeo Terms: By using and/or signing up to any of the NeoSeo Services, you express and agree to these Terms of Use and other NeoSeo terms, for example, provide programming services in the context of typical and non-typical tasks that are outlined here: <a href = "https://neoseo.com.ua/vse-chto-nujno-znat-klienty "target ="_blank" class ="external"> https://neoseo.com.ua/vse-chto-nujno-znat-klienty </a>, (hereinafter the NeoSeo Terms).</p>
<p>If you are unable to read or agree to the NeoSeo Terms, you must immediately leave the NeoSeo Website and not use the NeoSeo Services.</p>
<p>By using our Software products, Services, and Services, you acknowledge that you have read our Privacy Policy at <a href = "https://neoseo.com.ua/policy-konfidencialnosti "target ="_blank " class ="external"> https://neoseo.com.ua/politika-konfidencialnosti </a> (" Privacy Policy ")</p>
<p>This document is a license agreement between you and NeoSeo.</p>
<p>By agreeing to this agreement or using the software, you agree to all these terms.</p>
<p>This agreement applies to the NeoSeo software, any fonts, icons, images or sound files provided as part of the software, as well as to all NeoSeo software updates, add-ons or services, if not applicable to them. miscellaneous. This also applies to NeoSeo apps and add-ons for the SEO-Store, which extend its functionality.</p>
<p>Prior to your use of some of the application features, additional NeoSeo and third party terms may apply. For the correct operation of some applications, additional agreements are required with separate terms and conditions of privacy, for example, with services that provide SMS-notification services.</p>
<p>Software is not sold, but licensed.</p>
<p>NeoSeo retains all rights (for example, the rights provided by intellectual property laws) that are not explicitly granted under this agreement. For example, this license does not entitle you to:</p>
<li> <span> </span> <span> </span> separately use or virtualize software components; </li>
<li> publish or duplicate (with the exception of a permitted backup) software, provide software for rental, lease or temporary use; </li>
<li> transfer the software (except as provided in this agreement); </li>
<li> Try to circumvent the technical limitations of the software; </li>
<li> study technology, decompile or disassemble the software, and make appropriate attempts, other than those to the extent and in cases where (a) it provides for the right; (b) authorized by the terms of the license to use the components of the open source code that may be part of this software; (c) necessary to make changes to any libraries licensed under the small GNU General Public License, which are part of the software and related; </li>
<p> You have the right to use this software only if you have the appropriate license and the software was properly activated using the genuine product key or in another permissible manner.
</p>
<p> The cost of the SEO-Shop license does not include installation services, settings, and more of its stylization, as well as other paid/free add-ons. These services are optional, the cost depends on the number of hours required for the implementation of the hours, here: <a href = "https://neoseo. com.ua/vse-chto-nujno-znat-klienty "target =" _ blank "class =" external "> https://neoseo.com.ua/vse-chto-nujno-znat-klienty </a>
</p>
<p> The complete version of the document can be found here:
</p>
<p> <a href="https://neoseo.com.ua/usloviya-licenzionnogo-soglasheniya" target="_blank" class="external"> https://neoseo.com.ua/usloviya-licenzionnogo-soglasheniya </a>
</p>';
$_['mail_support']='<h2>Terms of free and paid information and technical support in <a class="external" href="https://neoseo.com.ua/" target="_blank"> NeoSeo</a>.</h2>

<p>Since we are confident that any quality work must be paid, all consultations requiring preliminary preparation of the answer, pay, including and case studies: &quot; look, and why your module is not working here? &quot;</p>

<p>If the answer to your question is already ready, you will receive it for free. But if you need to spend time answering the question, studying files, finding a bug and analyzing it, then we&#39;ll ask you to make a payment before you can answer.</p>

<p>We are <strong>helping to install</strong> and <strong> fix bugs when installing </strong>our modules in our order.</p>

<p>For any questions, please contact www.opencartmasters.com.</p>

<p>See the full version of the license agreement here:<strong> </strong><a class="external" href="https://neoseo.com.ua/usloviya-licenzionnogo-soglasheniya" target="_blank"> https://neoseo.com .ua/usloviya-licenzionnogo-soglasheniya</a></p>

<p><strong>Special offer: write review - get an add-on as a gift :)</strong></p>

<p>Dear Customers of web studio NeoSeo,</p>

<p>Tell us, what could be better for the development of the company than public reviews? This is a great way to hear your Client and make your products and service even better.</p>

<p>Please, leave a review about cooperation with our web studio or about our software solutions (add-ons) on our Facebook, Google, pages, Google, Yandex and OpenCartForum.com. pages.</p>

<p>Write as it is, it is important for us to hear an honest and objective assessment, and as a sign of gratitude for the time spent writing reviews, we have prepared a nice bonus. Detailed conditions are here: <a href="https://neoseo.com.ua/akciya-modul-v-podarok " target="_blank">https://neoseo.com.ua/akciya-modul-v-podarok </a></p>

<p>Once again, thank you very much for being with us!</p>

<p>The NeoSeo Team</p>';
