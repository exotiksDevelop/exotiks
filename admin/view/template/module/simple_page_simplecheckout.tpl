<vtab title="<?php echo $l->get('tab_simplecheckout', true) ?>" title-lang-id="tab_simplecheckout" ng-init="settings.checkout = !empty(settings.checkout) ? settings.checkout : [];checkout = !empty(checkout) ? checkout : {}">
    <div style="margin-bottom:10px;">
        <div class="help"><?php echo $l->get('text_settings_help') ?></div>
        <div>
            <?php echo $l->get('text_settings_group') ?>:&nbsp;
            <select ng-model="$root.settingsId" ng-options="group.settingsId as group.settingsId for group in settings.checkout">
            </select>
        </div>
        <div>
            <a ng-click="addSettingsGroup()"><?php echo $l->get('text_add_settings_group') ?></a>
        </div>
    </div>
    <div ng-repeat="checkout in settings.checkout" ng-if="checkout.settingsId == settingsId">
        <div ng-if="checkout.settingsId > 0" style="margin-bottom:5px;">
            <a ng-click="removeSettingsGroup(checkout.settingsId)"><?php echo $l->get('text_remove_settings_group') ?></a>
        </div>
        <htabs>
            <htab title="<?php echo $l->get('tab_simplecheckout_template', true) ?>" title-lang-id="tab_simplecheckout_template">
                <div ng-controller="simpleStepsController" ng-init="checkout.steps = !empty(checkout.steps) ? checkout.steps : [];">
                    <h3><?php echo $l->get('text_steps_count'); ?> {{checkout.steps.length}}</h3>
                    <a ng-click="addStep()" class="button btn btn-primary"><?php echo $l->get('text_add_step'); ?></a>
                    <table class="form" ng-repeat="step in checkout.steps">
                        <tr ng-init="step.label = !empty(step.label) ? step.label : {}" ng-show="checkout.steps.length > 1">
                            <td>
                                <?php echo $l->get('entry_step_label'); ?> {{$index+1}}
                            </td>
                            <td>
                                <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="step.label[l.code]"></div>
                                <div style="font-weight:bold;"><a ng-show="$index > 0" class="button btn btn-primary" style="margin-top: 10px" ng-click="removeStep(step.id)"><?php echo $l->get('text_remove_step'); ?></a></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><?php echo $l->get('entry_available_blocks'); ?></div>
                                <div ng-repeat="column in columns">
                                    <div class="simple-columns" block="{{column.id}}" ui-draggable>{{column.label}}</div>
                                </div>
                                <div ng-repeat="block in blocks">
                                    <div class="simple-block" ng-hide="block.used[checkout.settingsId] || (block.id == 'payment_form' && step.id != getLastStepId())" block="{{block.id}}" ui-draggable>{{block.label}}<span ng-if="block.required"><?php echo $l->get('text_required', true) ?></span></div>
                                </div>
                            </td>
                            <td ng-init="step.template = !empty(step.template) ? step.template : ''">
                                <div visualtemplate="step.template" class="simple-step"></div>
                                <label><input type="checkbox" ng-model="step.manual" ng-init="step.manual = !empty(step.manual) ? step.manual : 0"><?php echo $l->get('text_step_manual_mode'); ?></label><br>
                                <textarea cols="100" rows="1" ng-model="step.template" ng-show="step.manual"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_main', true) ?>" title-lang-id="tab_simplecheckout_main">
                <table class="form">
                    <tr>
                        <td><?php echo $l->get('entry_replace_cart') ?></td>
                        <td>
                            <input type="checkbox" ng-model="settings.replaceCart">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_replace_checkout') ?></td>
                        <td>
                            <input type="checkbox" ng-model="settings.replaceCheckout">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_use_cookies') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.useCookies">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_guest_checkout_disabled') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.guestCheckoutDisabled">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_asap_for_logged') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.asapForLogged">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_asap_for_guests') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.asapForGuests">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_enable_autoreloading_of_payment_form') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.enableAutoReloaingOfPaymentFrom">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_use_geoip'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.useGeoIp">
                        </td>
                    </tr>
                    <tr ng-show="checkout.useGeoIp">
                        <td>
                            <?php echo $l->get('entry_geoip_mode'); ?>
                        </td>
                        <td ng-init="checkout.geoIpMode = isset(checkout.geoIpMode) ? checkout.geoIpMode : 1">
                            <select ng-model="checkout.geoIpMode">
                                <option value="1" ng-selected="checkout.geoIpMode == 1"><?php echo $l->get('text_geoip_mode_own') ?></option>
                                <option value="2" ng-selected="checkout.geoIpMode == 2"><?php echo $l->get('text_geoip_mode_maxmind_extension') ?></option>
                                <option value="3" ng-selected="checkout.geoIpMode == 3"><?php echo $l->get('text_geoip_mode_maxmind_table') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_use_autocomplete'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.useAutocomplete">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_use_googleapi'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.useGoogleApi">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_display_back') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.displayBackButton">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_display_proceed_text') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.displayProceedText">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_display_weight') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.displayWeight">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_scroll_to_error') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.scrollToError">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_scroll_to_payment_form') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.scrollToPaymentForm">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_payment_before_shipping') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.paymentBeforeShipping">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_address_selection_format') ?></td>
                        <td>
                            <input type="text" ng-model="settings.addressFormat" size="50">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_left_column_width') ?></td>
                        <td>
                            <input type="text" ng-model="checkout.leftColumnWidth">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_right_column_width') ?></td>
                        <td>
                            <input type="text" ng-model="checkout.rightColumnWidth">
                        </td>
                    </tr>
                </table>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_cart', true) ?>" title-lang-id="tab_simplecheckout_cart"  ng-init="checkout.cart = !empty(checkout.cart) ? checkout.cart : {}">
                <table class="form">
                    <tr>
                        <td>
                            <?php echo $l->get('entry_display_model'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.cart.displayModel">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_use_total_value'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.cart.useTotal">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_min_amount'); ?>
                        </td>
                        <td ng-init="checkout.cart.minAmount =  !empty(checkout.cart.minAmount) ? checkout.cart.minAmount : {}">
                            <strong><?php echo $l->get('text_groups') ?>:</strong>
                            <div ng-repeat="group in groups"><span style="display:inline-block;min-width:150px;">{{group.name}}</span><input type="text" ng-model="checkout.cart.minAmount[group.customer_group_id]"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_max_amount'); ?>
                        </td>
                        <td ng-init="checkout.cart.maxAmount =  !empty(checkout.cart.maxAmount) ? checkout.cart.maxAmount : {}">
                            <strong><?php echo $l->get('text_groups') ?>:</strong>
                            <div ng-repeat="group in groups"><span style="display:inline-block;min-width:150px;">{{group.name}}</span><input type="text" ng-model="checkout.cart.maxAmount[group.customer_group_id]"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_min_quantity'); ?>
                        </td>
                        <td ng-init="checkout.cart.minQuantity = !empty(checkout.cart.minQuantity) ? checkout.cart.minQuantity : {}">
                            <strong><?php echo $l->get('text_groups') ?>:</strong>
                            <div ng-repeat="group in groups"><span style="display:inline-block;min-width:150px;">{{group.name}}</span><input type="text" ng-model="checkout.cart.minQuantity[group.customer_group_id]"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_max_quantity'); ?>
                        </td>
                        <td ng-init="checkout.cart.maxQuantity = !empty(checkout.cart.maxQuantity) ? checkout.cart.maxQuantity : {}">
                            <strong><?php echo $l->get('text_groups') ?>:</strong>
                            <div ng-repeat="group in groups"><span style="display:inline-block;min-width:150px;">{{group.name}}</span><input type="text" ng-model="checkout.cart.maxQuantity[group.customer_group_id]"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_min_weight'); ?>
                        </td>
                        <td ng-init="checkout.cart.minWeight = !empty(checkout.cart.minWeight) ? checkout.cart.minWeight : {}">
                            <strong><?php echo $l->get('text_groups') ?>:</strong>
                            <div ng-repeat="group in groups"><span style="display:inline-block;min-width:150px;">{{group.name}}</span><input type="text" ng-model="checkout.cart.minWeight[group.customer_group_id]"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_max_weight'); ?>
                        </td>
                        <td ng-init="checkout.cart.maxWeight = !empty(checkout.cart.maxWeight) ? checkout.cart.maxWeight : {}">
                            <strong><?php echo $l->get('text_groups') ?>:</strong>
                            <div ng-repeat="group in groups"><span style="display:inline-block;min-width:150px;">{{group.name}}</span><input type="text" ng-model="checkout.cart.maxWeight[group.customer_group_id]"></div>
                        </td>
                    </tr>
                </table>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_customer', true) ?>" title-lang-id="tab_simplecheckout_customer" ng-init="checkout.customer = !empty(checkout.customer) ? checkout.customer : {}">
                <table class="form" ng-controller="simpleCustomerController">
                    <tr ng-init="setData()">
                        <td>
                            <?php echo $l->get('entry_register'); ?>
                        </td>
                        <td>
                            <label><input type="radio" value="1" ng-model="setData.default.saved" ?><?php echo $l->get('text_yes') ?></label>
                            <label><input type="radio" value="0" ng-model="setData.default.saved" ?><?php echo $l->get('text_no') ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_display_login'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.customer.displayLogin">
                        </td>
                    </tr>
                    <tr ng-init="checkout.loginType = !empty(checkout.loginType) ? checkout.loginType : 'popup'">
                        <td><?php echo $l->get('entry_login_type') ?></td>
                        <td>
                            <label><input type="radio" value="flat" ng-model="checkout.loginType" ?><?php echo $l->get('text_login_type_flat') ?></label>
                            <label><input type="radio" value="popup" ng-model="checkout.loginType" ?><?php echo $l->get('text_login_type_popup') ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_you_will_be_registered'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.customer.displayYouWillRegistered">
                        </td>
                    </tr>
                </table>
                <h3><?php echo $l->get('text_change_value_and_customize') ?></h3>
                <table class="form" ng-controller="simpleSetController">
                    <tr ng-init="checkout.customer.rows = !empty(checkout.customer.rows) ? checkout.customer.rows : {};setData.rows=checkout.customer.rows;setData.filterForObjects=['order', 'customer'];setData.both = true;sortAllRows();">
                        <td>
                            <scenario></scenario>
                        </td>
                        <td>
                            <rows></rows>
                        </td>
                    </tr>
                </table>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_payment_address', true) ?>" title-lang-id="tab_simplecheckout_payment_address" ng-init="checkout.paymentAddress = !empty(checkout.paymentAddress) ? checkout.paymentAddress : {}">
                <table class="form">
                    <tr>
                        <td>
                            <?php echo $l->get('entry_display_address_same'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.paymentAddress.displayAddressSame">
                        </td>
                    </tr>
                    <tr ng-show="checkout.paymentAddress.displayAddressSame">
                        <td>
                            <?php echo $l->get('entry_address_same_init'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.paymentAddress.addressSameInit">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_hide_for_these_methods'); ?>
                        </td>
                        <td ng-init="checkout.paymentAddress.hideForMethods = !empty(checkout.paymentAddress.hideForMethods) ? checkout.paymentAddress.hideForMethods : {}">
                            <div ng-repeat="method in paymentMethods"><label><input type="checkbox" ng-model="checkout.paymentAddress.hideForMethods[method.code]">{{method.title[currentLanguage]}}</label></div>
                        </td>
                    </tr>
                </table>
                <h3><?php echo $l->get('text_change_value_and_customize') ?></h3>
                <table class="form" ng-controller="simpleSetController">
                    <tr ng-init="checkout.paymentAddress.rows = !empty(checkout.paymentAddress.rows) ? checkout.paymentAddress.rows : {};setData.rows=checkout.paymentAddress.rows;setData.filterForObjects=['order', 'address'];setData.both = true;sortAllRows();">
                        <td>
                            <scenario></scenario>
                        </td>
                        <td>
                            <rows></rows>
                        </td>
                    </tr>
                </table>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_shipping_address', true) ?>" title-lang-id="tab_simplecheckout_shipping_address" ng-init="checkout.shippingAddress = !empty(checkout.shippingAddress) ? checkout.shippingAddress : {}">
                <table class="form">
                    <tr>
                        <td>
                            <?php echo $l->get('entry_hide_for_these_methods'); ?>
                        </td>
                        <td ng-init="checkout.shippingAddress.hideForMethods = !empty(checkout.shippingAddress.hideForMethods) ? checkout.shippingAddress.hideForMethods : {}">
                            <div ng-repeat="method in shippingMethods"><label><input type="checkbox" ng-model="checkout.shippingAddress.hideForMethods[method.code]">{{method.title[currentLanguage]}}</label></div>
                        </td>
                    </tr>
                </table>
                <h3><?php echo $l->get('text_change_value_and_customize') ?></h3>
                <table class="form" ng-controller="simpleSetController">
                    <tr ng-init="checkout.shippingAddress.rows = !empty(checkout.shippingAddress.rows) ? checkout.shippingAddress.rows : {};setData.rows=checkout.shippingAddress.rows;setData.filterForObjects=['order', 'address'];setData.both = true;sortAllRows();">
                        <td>
                            <scenario></scenario>
                        </td>
                        <td>
                            <rows></rows>
                        </td>
                    </tr>
                </table>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_shipping', true) ?>" title-lang-id="tab_simplecheckout_shipping" ng-init="checkout.shipping = !empty(checkout.shipping) ? checkout.shipping : {}">
                <?php if ($empty_shipping_methods) { ?>
                <div class="success"><?php echo $empty_shipping_methods; ?></div>
                <?php } ?>
                <table class="form" ng-init="checkout.shipping.rows = !empty(checkout.shipping.rows) ? checkout.shipping.rows : {}">
                    <tr>
                        <td><?php echo $l->get('entry_display_address_empty') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.shipping.displayAddressEmpty">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_display_titles') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.shipping.displayTitles">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_select_first') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.shipping.selectFirst">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_display_type') ?></td>
                        <td ng-init="checkout.shipping.displayType = !empty(checkout.shipping.displayType) ? checkout.shipping.displayType : 1">
                            <label><input type="radio" ng-model="checkout.shipping.displayType" value="1"><?php echo $l->get('entry_display_type_radio') ?></label><br>
                            <label><input type="radio" ng-model="checkout.shipping.displayType" value="2"><?php echo $l->get('entry_display_type_select') ?></label>
                        </td>
                    </tr>
                </table>
                <div ng-repeat="method in checkout.shipping.methods" ng-init="checkout.shipping.methods = !empty(checkout.shipping.methods) ? checkout.shipping.methods : {}">
                    <h3 ng-init="method.title = !empty(method.title) ? method.title : {};method.title[currentLanguage] = !empty(method.title[currentLanguage]) ? method.title[currentLanguage] : shippingModules[method.code].title">{{method.title[currentLanguage]}} ({{method.code}})</h3>
                    <table class="form">
                        <tr>
                            <td><?php echo $l->get('entry_method_wait_full_address') ?></td>
                            <td>
                                <input type="checkbox" ng-model="method.wait">
                            </td>
                        </tr>
                        <tr ng-init="method.title = !empty(method.title) ? method.title : {}">
                            <td><?php echo $l->get('entry_method_title') ?></td>
                            <td>
                                <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="method.title[l.code]"></div>
                                <label><input type="checkbox" ng-model="method.useTitle"><?php echo $l->get('text_use_this_title_always') ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $l->get('entry_method_sort_order') ?></td>
                            <td>
                                <input type="text" ng-model="method.sortOrder">
                            </td>
                        </tr>
                    </table>
                    <div style="padding-left:40px;" ng-init="method.methods = !empty(method.methods) ? method.methods : {};">
                        <h3><?php echo $l->get('text_submethods') ?></h3>
                        <table class="form" ng-controller="simpleShippingController">
                            <tr>
                                <td><?php echo $l->get('entry_submethod_code') ?></td>
                                <td>
                                    <input type="text" ng-model="setData.code">
                                    <div class="help"><?php echo $l->get('text_use_all') ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $l->get('entry_submethod_title') ?></td>
                                <td>
                                    <input type="text" ng-model="setData.title">
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <a ng-click="addShippingSubmethod(method.code)" class="button btn btn-primary"><?php echo $l->get('button_add_submethod'); ?></a>
                                </td>
                            </tr>
                        </table>
                        <div ng-repeat="submethod in method.methods" style="border-left:1px solid #CCCCCC;padding-left:20px;">
                            <h3>{{submethod.title[currentLanguage]}} ({{submethod.code}})</h3>
                            <div ng-controller="simpleShippingController">
                                <a ng-click="removeShippingSubmethod(method.code, submethod.code)" class="button btn btn-primary"><?php echo $l->get('button_remove_setting'); ?></a>
                            </div>
                            <table class="form">
                                <tr ng-init="submethod.hideForStatuses = !empty(submethod.hideForStatuses) ? submethod.hideForStatuses : {'guest': false, 'logged': false}">
                                    <td><?php echo $l->get('entry_method_for_statuses') ?></td>
                                    <td>
                                        <div style="margin-top:5px;">
                                            <div><label><input type="checkbox" ng-model="submethod.hideForStatuses['guest']"><?php echo $l->get('text_display_for_guest'); ?></label></div>
                                            <div><label><input type="checkbox" ng-model="submethod.hideForStatuses['logged']"><?php echo $l->get('text_display_for_logged'); ?></label></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr ng-init="submethod.forGroups = !empty(submethod.forGroups) ? submethod.forGroups : {}">
                                    <td><?php echo $l->get('entry_method_for_groups') ?></td>
                                    <td ng-init="submethod.forAllGroups = !empty(submethod.forAllGroups) ? submethod.forAllGroups : 1">
                                        <label><input type="radio" ng-model="submethod.forAllGroups" value="1"><?php echo $l->get('entry_for_all') ?></label>
                                        <label><input type="radio" ng-model="submethod.forAllGroups" value="0"><?php echo $l->get('entry_for_selected') ?></label>
                                        <div ng-hide="submethod.forAllGroups" style="margin-top:5px;">
                                            <div ng-repeat="group in groups"><label><input type="checkbox" ng-model="submethod.forGroups[group.customer_group_id]">{{group.name}}</label></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr ng-show="checkout.paymentBeforeShipping" ng-init="submethod.forMethods = !empty(submethod.forMethods) ? submethod.forMethods : {}">
                                    <td><?php echo $l->get('entry_method_for_payment_methods') ?></td>
                                    <td ng-init="submethod.forAllMethods = !empty(submethod.forAllMethods) ? submethod.forAllMethods : 1">
                                        <label><input type="radio" ng-model="submethod.forAllMethods" value="1"><?php echo $l->get('entry_for_all') ?></label>
                                        <label><input type="radio" ng-model="submethod.forAllMethods" value="0"><?php echo $l->get('entry_for_selected') ?></label>
                                        <div ng-hide="submethod.forAllMethods" style="margin-top:5px;">
                                            <div ng-repeat="paymentMethod in paymentMethods"><label><input type="checkbox" ng-model="submethod.forMethods[paymentMethod.code]">{{paymentMethod.title[currentLanguage]}} ({{paymentMethod.code}})</label></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr ng-show="submethod.code.indexOf('*') == -1" ng-init="submethod.display = !empty(submethod.display) ? submethod.display : method.display">
                                    <td><?php echo $l->get('entry_display_stub') ?></td>
                                    <td>
                                        <input type="checkbox" ng-model="submethod.display">
                                    </td>
                                </tr>
                                <tr ng-show="submethod.code.indexOf('*') == -1" ng-init="submethod.title = !empty(submethod.title) ? submethod.title : {}">
                                    <td><?php echo $l->get('entry_method_title') ?></td>
                                    <td>
                                        <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="submethod.title[l.code]"></div>
                                        <label><input type="checkbox" ng-model="submethod.useTitle"><?php echo $l->get('text_use_this_title_always') ?></label>
                                    </td>
                                </tr>
                                <tr ng-show="submethod.code.indexOf('*') == -1" ng-init="submethod.description = !empty(submethod.description) ? submethod.description : {}">
                                    <td><?php echo $l->get('entry_method_description') ?></td>
                                    <td>
                                        <div ng-repeat="l in languages"><img style="vertical-align: top" ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<textarea  cols="50" ng-model="submethod.description[l.code]"></textarea></div>
                                        <label><input type="checkbox" ng-model="submethod.useDescription"><?php echo $l->get('text_use_this_description_always') ?></label>
                                    </td>
                                </tr>
                                <tr ng-show="submethod.code.indexOf('*') == -1" ng-controller="simpleSetController">
                                    <td ng-init="setScenarioName(submethod.code,'');checkout.shipping.rows[setData.scenario] = !empty(checkout.shipping.rows[setData.scenario]) ? checkout.shipping.rows[setData.scenario] : [];setData.rows=checkout.shipping.rows;setData.filterForObjects=['order', 'customer', 'address'];setData.onlyCustom = true;setData.both = true;sortAllRows();">
                                      <?php echo $l->get('text_set_of_fields') ?>
                                    </td>
                                    <td>
                                        <rows></rows>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_payment', true) ?>" title-lang-id="tab_simplecheckout_payment" ng-init="checkout.payment = !empty(checkout.payment) ? checkout.payment : {}">
                <?php if ($empty_payment_methods) { ?>
                <div class="warning"><?php echo $empty_payment_methods; ?></div>
                <?php } ?>
                <table class="form" ng-init="checkout.payment.rows = !empty(checkout.payment.rows) ? checkout.payment.rows : {}">
                    <tr>
                        <td><?php echo $l->get('entry_display_address_empty') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.payment.displayAddressEmpty">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_select_first') ?></td>
                        <td>
                            <input type="checkbox" ng-model="checkout.payment.selectFirst">
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $l->get('entry_display_type') ?></td>
                        <td ng-init="checkout.payment.displayType = !empty(checkout.payment.displayType) ? checkout.payment.displayType : 1">
                            <label><input type="radio" ng-model="checkout.payment.displayType" value="1"><?php echo $l->get('entry_display_type_radio') ?></label><br>
                            <label><input type="radio" ng-model="checkout.payment.displayType" value="2"><?php echo $l->get('entry_display_type_select') ?></label>
                        </td>
                    </tr>
                </table>
                <div ng-repeat="method in checkout.payment.methods" ng-init="checkout.payment.methods = !empty(checkout.payment.methods) ? checkout.payment.methods : {}">
                    <h3>{{method.title[currentLanguage]}} ({{method.code}})</h3>
                    <table class="form">
                        <tr>
                            <td><?php echo $l->get('entry_method_wait_full_address') ?></td>
                            <td>
                                <input type="checkbox" ng-model="method.wait">
                            </td>
                        </tr>
                        <tr ng-init="method.hideForStatuses = !empty(method.hideForStatuses) ? method.hideForStatuses : {'guest': false, 'logged': false}">
                            <td><?php echo $l->get('entry_method_for_statuses') ?></td>
                            <td>
                                <div style="margin-top:5px;">
                                    <div><label><input type="checkbox" ng-model="method.hideForStatuses['guest']"><?php echo $l->get('text_display_for_guest'); ?></label></div>
                                    <div><label><input type="checkbox" ng-model="method.hideForStatuses['logged']"><?php echo $l->get('text_display_for_logged'); ?></label></div>
                                </div>
                            </td>
                        </tr>
                        <tr ng-init="method.forGroups = !empty(method.forGroups) ? method.forGroups : {}">
                            <td><?php echo $l->get('entry_method_for_groups') ?></td>
                            <td ng-init="method.forAllGroups = !empty(method.forAllGroups) ? method.forAllGroups : 1">
                                <label><input type="radio" ng-model="method.forAllGroups" value="1"><?php echo $l->get('entry_for_all') ?></label>
                                <label><input type="radio" ng-model="method.forAllGroups" value="0"><?php echo $l->get('entry_for_selected') ?></label>
                                <div ng-hide="method.forAllGroups" style="margin-top:5px;">
                                    <div ng-repeat="group in groups"><label><input type="checkbox" ng-model="method.forGroups[group.customer_group_id]">{{group.name}}</label></div>
                                </div>
                            </td>
                        </tr>
                        <tr ng-hide="checkout.paymentBeforeShipping" ng-init="method.forMethods = !empty(method.forMethods) ? method.forMethods : {}">
                            <td><?php echo $l->get('entry_method_for_shipping_methods') ?></td>
                            <td ng-init="method.forAllMethods = !empty(method.forAllMethods) ? method.forAllMethods : 1">
                                <label><input type="radio" ng-model="method.forAllMethods" value="1"><?php echo $l->get('entry_for_all') ?></label>
                                <label><input type="radio" ng-model="method.forAllMethods" value="0"><?php echo $l->get('entry_for_selected') ?></label>
                                <div ng-hide="method.forAllMethods" style="margin-top:5px;">
                                    <div ng-repeat="shippingMethod in shippingMethods"><label><input type="checkbox" ng-model="method.forMethods[shippingMethod.code]">{{shippingMethod.title[currentLanguage]}} ({{shippingMethod.code}})</label></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $l->get('entry_display_stub') ?></td>
                            <td>
                                <input type="checkbox" ng-model="method.display">
                            </td>
                        </tr>
                        <tr ng-show="method.display">
                            <td><?php echo $l->get('entry_method_sort_order') ?></td>
                            <td>
                                <input type="text" ng-model="method.sortOrder">
                            </td>
                        </tr>
                        <tr ng-init="method.title = !empty(method.title) ? method.title : {}">
                            <td><?php echo $l->get('entry_method_title') ?></td>
                            <td>
                                <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="method.title[l.code]"></div>
                                <label><input type="checkbox" ng-model="method.useTitle"><?php echo $l->get('text_use_this_title_always') ?></label>
                            </td>
                        </tr>
                        <tr ng-init="method.description = !empty(method.description) ? method.description : {}">
                            <td><?php echo $l->get('entry_method_description') ?></td>
                            <td>
                                <div ng-repeat="l in languages"><img style="vertical-align: top" ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<textarea  cols="50" ng-model="method.description[l.code]"></textarea></div>
                                <label><input type="checkbox" ng-model="method.useDescription"><?php echo $l->get('text_use_this_description_always') ?></label>
                            </td>
                        </tr>
                        <tr ng-controller="simpleSetController">
                            <td>
                              <?php echo $l->get('text_set_of_fields') ?>
                            </td>
                            <td ng-init="setScenarioName('', method.code);checkout.payment.rows[setData.scenario] = !empty(checkout.payment.rows[setData.scenario]) ? checkout.payment.rows[setData.scenario] : [];setData.rows=checkout.payment.rows;setData.filterForObjects=['order', 'customer', 'address', 'payment'];setData.onlyCustom = true;setData.both = true;sortAllRows();">
                                <rows></rows>
                            </td>
                        </tr>
                    </table>
                </div>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_comment', true) ?>" title-lang-id="tab_simplecheckout_comment">
                <table class="form" ng-init="checkout.comment = !empty(checkout.comment) ? checkout.comment : {}">
                    <tr>
                        <td>
                            <?php echo $l->get('entry_field_label'); ?>
                        </td>
                        <td ng-init="checkout.comment.label = !empty(checkout.comment.label) ? checkout.comment.label : {}">
                            <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" ng-model="checkout.comment.label[l.code]" size="50"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_field_placeholder'); ?>
                        </td>
                        <td ng-init="checkout.comment.placeholder = !empty(checkout.comment.placeholder) ? checkout.comment.placeholder : {}">
                            <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="checkout.comment.placeholder[l.code]"></div>
                        </td>
                    </tr>
                </table>
            </htab>
            <htab title="<?php echo $l->get('tab_simplecheckout_text', true) ?>" title-lang-id="tab_simplecheckout_text">
                <h3><?php echo $l->get('text_agreement_block') ?></h3>
                <table class="form">
                    <tr>
                        <td>
                            <?php echo $l->get('entry_display_agreement_checkbox'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.displayAgreementCheckbox">
                        </td>
                    </tr>
                    <tr ng-show="checkout.steps.length > 1">
                        <td>
                            <?php echo $l->get('entry_agreement_checkbox_step'); ?>
                        </td>
                        <td>
                            <select ng-model="checkout.agreementCheckboxStep">
                                <option ng-repeat="step in checkout.steps" ng-selected="checkout.agreementCheckboxStep == step.id" value="{{step.id}}" ng-if="step.id != ('step_' + (checkout.steps.length - 1))">{{!empty(step.label) && !empty(step.label[currentLanguage]) ? step.label[currentLanguage] : step.id}}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_display_agreement_checkbox_init'); ?>
                        </td>
                        <td>
                            <input type="checkbox" ng-model="checkout.agreementCheckboxInit">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $l->get('entry_agreement_id'); ?>
                        </td>
                        <td ng-init="checkout.agreementId = !empty(checkout.agreementId) ? checkout.agreementId : 0">
                            <select ng-model="checkout.agreementId" ng-options="info.id as info.title for info in informationPages">
                            </select>
                        </td>
                    </tr>
                </table>
                <h3><?php echo $l->get('text_help_block') ?></h3>
                <table class="form">
                    <tr>
                        <td>
                            <?php echo $l->get('entry_help_id'); ?>
                        </td>
                        <td ng-init="checkout.helpId = !empty(checkout.helpId) ? checkout.helpId : 0">
                            <select ng-model="checkout.helpId" ng-options="info.id as info.title for info in informationPages">
                            </select>
                        </td>
                    </tr>
                </table>
            </htab>
        </htabs>
    </div>
</vtab>