<?php	
// Heading	
$_['heading_title_setting'] = 'Yandex Go Delivery';
$_['heading_title_create_order'] = 'Send order to Yandex Go Delivery';
$_['heading_title_order_not_found'] = 'Order not found';
$_['heading_title_order'] = 'Orders';
$_['heading_title'] = 'Yandex Go Delivery';
$_['heading_warehouses_index'] = 'Depots';
$_['heading_warehouses_edit'] = 'Edit depot';
$_['heading_order_view'] = 'Order';
$_['heading_order_index'] = 'Orders';
// Text	
$_['text_extension'] = 'Extensions';
$_['text_success'] = 'Settings changed!';
$_['text_edit'] = 'Edit';
$_['text_minutes'] = 'minutes';
$_['text_source'] = 'Point 1 (Depot)';
$_['text_destination'] = 'Point %d,';
$_['text_order_details'] = 'Order details';
$_['text_order_not_found'] = 'Order not found';
$_['text_create_order'] = 'Create a claim';
$_['text_price'] = 'Delivery cost';
$_['text_price_updated'] = 'The order price was updated. The new price is %s rub.\r\nCreate an order?';
$_['text_updating'] = 'updating...';
$_['text_currency'] = 'rub.';
$_['text_order_created'] = 'Delivery claim was created successfully';
$_['text_cancel_confirm'] = 'Available for order No. %d: %s. Confirm cancelation?';
$_['text_cancel_multiple_confirm'] = 'Available for order No. %d: %s. If you cancel delivery of this order, the following orders are canceled: %s. Confirm cancelation?';
$_['text_remove_destination'] = 'Are you sure that you want to delete the point?';
$_['text_order_id'] = 'order No. %d';
$_['text_placeholder_flat'] = 'Apartment number';
$_['text_placeholder_porch'] = 'Entrance';
$_['text_placeholder_floor'] = 'Floor';
$_['text_country'] = 'Country';
$_['text_city'] = 'City';
$_['text_street'] = 'Street';
$_['text_house'] = 'House';
$_['text_intercom'] = 'Intercom';
$_['text_order_comment'] = 'Comments on the order';
$_['text_fake_route_point'] = 'Fake point';
$_['text_confirm_reship'] = 'Order No. %d was already sent to Yandex Go Delivery.\nSend it again?';
$_['text_order_already_in_form'] = 'Order No. %d was already added to the form';
$_['text_tariff_default'] = 'Default';
$_['text_tariff'] = 'Service class';
	
// Entry	
$_['entry_yandex_taxi_api_token'] = 'Yandex Go Delivery API Token';
$_['entry_yandex_geo_coder_api_token'] = 'Yandex.Geoservices API Token';
$_['entry_warehouse_address'] = 'Depot address';
$_['entry_warehouse_coordinate'] = 'Depot address coordinates';
$_['entry_warehouse_email'] = 'Email address';
$_['entry_warehouse_contact_name'] = 'Depot contacts: Name';
$_['entry_warehouse_contact_phone'] = 'Phone number';
$_['entry_warehouse_default'] = 'Default';
$_['entry_warehouse_start_time'] = 'Depot business hours start';
$_['entry_warehouse_worktime'] = 'Depot business hours start';
$_['entry_warehouse_end_time'] = 'Depot business hours end';
$_['entry_assembly_delay_minutes'] = 'Order assembly time';
$_['entry_tax_class'] = 'Tax class';
$_['entry_geo_zone'] = 'Geographical area';
$_['entry_status'] = 'Status';
$_['entry_sort_order'] = 'Sorting order';
$_['entry_address'] = 'Address';
$_['entry_name'] = 'Name';
$_['entry_phone'] = 'Phone number';
$_['entry_email'] = 'Email';
$_['entry_sms_on'] = 'Enable confirmation via SMS at point';
$_['entry_need_due'] = 'Pick up the order at a specific time';
$_['entry_due'] = 'Vehicle ETA';
$_['entry_comment'] = 'Comments';
$_['entry_address_detail'] = 'Apartment number, entrance, floor';
$_['entry_destination_order_id'] = 'Order number to add to the form';
$_['entry_tariff'] = 'Choose a service class';
$_['entry_continue_anyway'] = 'Continue anyway?';
$_['entry_use_warehouse'] = 'Use depot';
$_['entry_change_status'] = 'Automatically change status to Shipped when the order is delivered';
$_['entry_order_status'] = 'Order status';
$_['entry_date'] = 'Date';
$_['entry_sum'] = 'Total';
$_['entry_yandex_go_status'] = 'Delivery status';
	
// Button	
$_['button_calculate'] = 'Calculate cost';
$_['button_confirm'] = 'Create claim';
$_['button_add_destination_for_order'] = 'Add order';
$_['button_add_fake_destination'] = 'Add point without order';
$_['button_add_warehouse'] = 'Add depot';
$_['button_index_warehouse'] = 'Depot list';
$_['button_index_orders'] = 'Order list';
$_['button_send_to_yandex_go'] = 'Send the order to Yandex Go Delivery';
$_['button_cancel_yandex_go'] = 'Cancel Yandex Go Delivery';
$_['button_send_orders_to_yandex_go'] = 'Send to Yandex Go';
	
// Help	
$_['help_yandex_taxi_api_token'] = 'API token from Yandex Go Delivery account profile';
$_['help_yandex_geo_coder_api_token'] = 'API token from Yandex.Geoservices API account profile';
$_['help_warehouse_address'] = 'Address of the depot where the driver can pick up the item';
$_['help_warehouse_coordinate'] = 'Latitude,Longitude\'; filled out automatically';
$_['help_warehouse_email'] = 'Address to get service invoices';
$_['help_warehouse_contact_name'] = 'Name of the contact at the depot';
$_['help_warehouse_contact_phone'] = 'Phone number of the contact at the depot';
$_['help_warehouse_start_time'] = 'Time starting from which the driver can pick up items every day, time zone %s';
$_['help_warehouse_end_time'] = 'Time before which the driver can pick up items every day, time zone %s';
$_['help_assembly_delay_minutes'] = 'Time between when the user placed the order and the vehicle arrived at the depot';
$_['help_time_zone'] = 'Time zone:';
	
// Error	
$_['error_permission'] = 'Your don\'t have the rights to manage this module!';
$_['error_settings_validation'] = 'Please fill out the fields:';
$_['error_calculation_price'] = 'An error occurred during cost calculation. Make sure that all the data is entered correctly';
$_['error_order_form_validation'] = 'Make sure that all the fields are filled out correctly';
$_['error_geo_coder_not_set'] = 'Couldn\'t parse address';
$_['error_required_field'] = 'This field is required for calculating the delivery';
$_['error_invalid_telephone'] = 'Incorrect phone number';
$_['error_inaccurate_address_need_house_number'] = 'Inaccurate address, enter the house number';
$_['error_incomplete_address_need_house_number'] = 'Incomplete address, enter the house number';
$_['error_inaccurate_telephone_need_details'] = 'Inaccurate address, please refine it';
$_['error_address_not_determined'] = 'Address not found';
$_['error_during_cancellation'] = 'An error occurred during delivery cancelation. Please try again';
$_['error_during_status_synchronization'] = 'An error occurred while updating the status';
$_['error_cannot_cancel_order'] = 'You can no longer cancel this order';
$_['error_no_one_destination'] = 'The claim must contain at least one delivery point';
$_['error_warehouse_not_found'] = 'Depot not found';
$_['warning_wrong_dimension_alert'] = 'The plugin works with the length units: m, cm, mm, in\'; and with the weight units: kg, g, lb, oz. If the item uses other units of measurement, they are interpreted as m and kg.';
$_['warning_wrong_default_dimension'] = 'An unknown default measurement unit is used in the system. Use meters (m)';
$_['warning_wrong_default_weight'] = 'An unknown default weight unit is used in the system. Use kilograms (kg)';
	
$_['message_warehouse_was_deleted'] = 'Depot was deleted';
$_['message_delete_warehouse_confirm'] = 'Are you sure you want to delete depot No. %s?';
$_['message_auto_status_change_history_comment'] = 'The Yandex Go Delivery plugin automatically changed the status';
	
// Support	
	
$_['text_support_questions'] = 'Any questions?';
$_['text_support_bot'] = 'Ask the Telegram bot';
$_['text_support_email'] = 'Write an email';
$_['text_support_write_us'] = 'write us';
	
// No tariffs	
$_['delivery_not_connected'] = 'Yandex Go Delivery is not connected.';
$_['connect'] = 'Connect';
	
// Locale	
$_['datetime_format'] = 'd.m.Y H:i:s (\U\T\CP)';
	
$_['connect_yandex_go_delivery_button'] = 'Connect Yandex Go Delivery';
$_['yandex_go_delivery_token_not_works'] = 'Can\'t apply your Yandex Go Delivery API Token.';
$_['yandex_go_delivery_token_not_works_description'] = 'You might not have enough funds on your account or Yandex Go Delivery is not connected.';
	
$_['connect_yandex_go_delivery_text'] = 'Fill out the form, get an email with a link to your account profile, enter your company information, and order delivery today';
$_['how_get_geocode_token'] = 'How to get a token';
$_['how_get_geocode_token_link_title'] = 'Go to Yandex.Geoservices';
$_['warehouse_is_not_valid'] = 'Check that the depot data is valid';
	
$_['order_already_sent_to_yandex_go'] = 'Order No. $%d was already sent to Yandex Go Delivery.\nSend it again?';
$_['orders_already_sent_to_yandex_go'] = 'Orders No. %d were already sent to Yandex Go Delivery.\nSend them again?';
	
$_['yandex_go_claim_id'] = 'Order ID in Yandex Go Delivery';
$_['tariff'] = 'Service class';
$_['yandex_go_claim_status'] = 'Order status in Yandex Go Delivery';
$_['yandex_go_route_point_status'] = 'Point visiting status in Yandex Go Delivery';
$_['driver_name'] = 'Driver\'s name';
$_['car'] = 'Vehicle';
$_['driver_phone_number'] = 'Driver\'s phone number';
$_['order_details'] = 'Order details';
$_['delivery_details'] = 'Delivery details';
$_['not_needed'] = 'Not needed';

$_['status_label_new'] = 'New';
$_['status_label_estimating'] = 'Evaluating';
$_['status_label_estimating_failed'] = 'Can\'t evaluate';
$_['status_label_ready_for_approval'] = 'Awaiting confirmation';
$_['status_label_accepted'] = 'Confirmed';
$_['status_label_performer_lookup'] = 'Accepted for processing';
$_['status_label_performer_draft'] = 'Searching for driver';
$_['status_label_performer_found'] = 'Driver found';
$_['status_label_performer_not_found'] = 'No drivers found';
$_['status_label_pickup_arrived'] = 'Driver arrived at point A';
$_['status_label_ready_for_pickup_confirmation'] = 'Awaiting SMS confirmation on acceptance by driver';
$_['status_label_pickuped'] = 'Accepted by driver';
$_['status_label_pay_waiting'] = 'Order awaiting payment';
$_['status_label_delivery_arrived'] = 'Driver arrived at point B';
$_['status_label_ready_for_delivery_confirmation'] = 'Awaiting SMS confirmation of delivery';
$_['status_label_delivered'] = 'Delivered';
$_['status_label_delivered_finish'] = 'Delivered';
$_['status_label_returning'] = 'Parcel is being returned to depot';
$_['status_label_return_arrived'] = 'Driver arrived at the return point.';
$_['status_label_ready_for_return_confirmation'] = 'Awaiting SMS confirmation of return';
$_['status_label_returned'] = 'Returned';
$_['status_label_returned_finish'] = 'Returned';
$_['status_label_cancelled'] = 'Canceled';
$_['status_label_cancelled_with_payment'] = 'Paid cancelation used';
$_['status_label_cancelled_by_taxi'] = 'Canceled by service';
$_['status_label_cancelled_with_items_on_hands'] = 'Canceled (cargo remained with the driver)';
$_['status_label_failed'] = 'Error';

$_['route_point_status_label_pending'] = 'Awaiting performer';
$_['route_point_status_label_arrived'] = 'Performer arrived';
$_['route_point_status_label_visited'] = 'Done';
$_['route_point_status_label_skipped'] = 'Skipped';

$_['cancel_status_free'] = 'Free';
$_['cancel_status_paid'] = 'Paid';

$_['rubles'] = '₽';
$_['price'] = 'Delivery cost';
$_['bad_geotoken_message'] = 'Your Yandex.Geoservices API token doesn\'t work. Make sure the token is correct in the settings. You will be forwarded to the settings page.';

$_['price_multi_order'] = '%s (total shipping cost with multipoints: %s)';

$_['order_already_sent'] = 'Order №%s was already sent to Yandex Go. Send again?';
$_['order_already_sent_many'] = 'Orders №%s were already sent to Yandex Go. Send again?';

$_['entry_shipping_yandextaxi_cart_shipping_method_title'] = 'Select the name Yandex Go Delivery in the cart';
$_['entry_shipping_yandextaxi_free_shipping_enabled'] = 'Use the "Free delivery from" setting';
$_['entry_shipping_yandextaxi_free_shipping_value'] = 'Free delivery from (rubles):';
$_['entry_shipping_yandextaxi_free_shipping_value_description'] = 'Minimum order amount for free Yandex Go Delivery for the user (rubles)';
$_['entry_shipping_yandextaxi_fixed_shipping_enabled'] = 'Whether the fixed cost for Yandex Go Delivery is enabled';
$_['entry_shipping_yandextaxi_fixed_shipping_value'] = 'Fixed cost for Yandex Go Delivery (rubles)';
$_['entry_shipping_yandextaxi_extra_charge_shipping_value'] = 'Delivery surcharge, %';
$_['entry_shipping_yandextaxi_extra_charge_shipping_value_description'] = 'Surcharge on delivery cost in Yandex Go, percent';
$_['entry_shipping_yandextaxi_discount_shipping_enabled'] = 'Whether Yandex Go Delivery discount is enabled';
$_['entry_shipping_yandextaxi_discount_shipping_value'] = 'Discount off delivery, %';
$_['entry_shipping_yandextaxi_discount_shipping_value_description'] = 'Discount off delivery cost in Yandex Go, percent';
$_['entry_shipping_yandextaxi_discount_shipping_from'] = 'Order amount subject to discount (rubles)';
$_['yandex_go_delivery'] = 'Yandex Delivery';
$_['yandex_go_delivery_express'] = 'Express Yandex Delivery';
$_['entry_shipping_yandextaxi_cart_enabled'] = 'Enabled in the basket';
$_['shipping_yandextaxi_cart_enabled_description'] = "The price for express delivery as soon as possible will be calculated in the cart.If a long time passes between calculating the price and sending the order for delivery, the price may change significantly.";
