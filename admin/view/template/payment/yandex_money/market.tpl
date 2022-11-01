<?php foreach ($market_status as $m) { echo $m; } ?>
<style>
    .yandex-money-market-sub-header {
        font-size: 120%;
        font-style: italic;
    }
    .yandex-money-market-sub-form-group {

    }
    .yandex-money-market-font-weight-normal {
        font-weight: normal;
        text-align: left !important;
    }

    .yandex-money-market-with-padding-top {
        padding-top: 9px;
    }

    .yandex-money-market-short-width {
        display: inline-block;
        width: 20%;
    }

    .yandex-money-market-width-100-percent {
        width: 100%;
    }

    .yandex-money-market-first-letter-uppercase:first-letter {
        text-transform: uppercase;
    }

    .yandex-money-market-category-tree {
        display: block;
    }

    .yandex-money-market-edit-on-button {
        position: relative;
        cursor: pointer;
        display: none;
    }

    .yandex-money-market-currency-disabled {
        color: silver;
    }

    .yandex-money-market-js-editable:hover .yandex-money-market-edit-on-button {
        display: inline;
    }

    .yandex-money-market-js-editable-edit {
        display: none;
    }

    .yandex-money-market-currency-edit form {
        display: inline-block;
    }

    .yandex-money-market-url {
        display: inline-block;
        width: 80%;
    }

    .yandex-money-market-copy-url {
        margin-left: 5px;
        cursor: pointer;
    }

    .yandex-money-market-delivery-more {
        padding-left: 20px;
        display: none;
    }

    .yandex-money-market-available-options-list::after {
        content: ',';
    }

    .yandex-money-market-available-options-list.last::after {
        content: '';
    }

    .yandex-money-market-available-status,
    .yandex-money-market-available-with-ready,
    .yandex-money-market-available-with-to-order {
        font-style: italic;
    }

    .yandex-money-market-additional-condition-template {
        display: none;
    }

    .yandex-money-market-hidden-element {
        display: none;
    }
</style>
<div class="panel panel-default">
    <div class="panel-body">
        <div>
            <p><?php echo $language->get('kassa_header_description'); ?></p>
            <p><?php echo $language->get('kassa_version_string'); ?> <?php echo $module_version; ?></p>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $active; ?></label>
            <div class="col-sm-8">
                <label class="radio-inline">
                    <input type="radio" <?php echo ($yandex_money_market_active ? ' checked="checked"' : ''); ?> name="yandex_money_market_active" value="1"/> <?php echo $active_on; ?></label>
                <label class="radio-inline">
                    <input type="radio" <?php echo (!$yandex_money_market_active ? ' checked="checked"' : ''); ?> name="yandex_money_market_active" value="0"/> <?php echo $active_off; ?></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="yandex_money_market_shopname"><?php echo $language->get('market_short_name'); ?></label>
            <div class="col-sm-8">
                <input type="text" name="yandex_money_market_shopname" value="<?php echo $yandex_money_market_shopname; ?>" id="yandex_money_market_shopname" class="form-control" maxlength="20"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="yandex_money_market_full_shopname"><?php echo $language->get('market_full_name'); ?></label>
            <div class="col-sm-8">
                <input type="text" name="yandex_money_market_full_shopname" value="<?php echo $yandex_money_market_full_shopname; ?>"  id="yandex_money_market_full_shopname" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_currencies'); ?></label>
            <div class="col-sm-8">
                <?php echo $market_currency_list; ?>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-4"><?php echo $language->get('market_categories'); ?></label>
            <div class="col-sm-8">
                <div>
                    <label class="radio-inline">
                        <input type="radio" <?php echo ($yandex_money_market_category_all ? ' checked="checked"' : ''); ?>
                               class="yandex_money_market_category_tree_switcher"
                               name="yandex_money_market_category_all" value="on"/> <?php echo $language->get('market_categories_all'); ?>
                    </label>
                </div>
                <div>
                    <label class="radio-inline">
                        <input type="radio" <?php echo (!$yandex_money_market_category_all ? ' checked="checked"' : ''); ?>
                               class="yandex_money_market_category_tree_switcher"
                               name="yandex_money_market_category_all" value=""/> <?php echo $language->get('market_categories_selected'); ?>
                    </label>
                </div>
                <div class="panel panel-default yandex-money-market-category-tree <?php echo ($yandex_money_market_category_all ? 'yandex-money-market-hidden-element' : ''); ?>">
                    <div class="tree-panel-heading tree-panel-heading-controls clearfix">
                        <div class="tree-actions pull-right">
                            <a onclick="return false;" class="btn btn-default catTreeHideCatAll">
                                <i class="fa fa-minus-square-o"></i> <?php echo $market_sv_all; ?>
                            </a>
                            <a onclick="return false;" class="btn btn-default catTreeShowCatAll">
                                <i class="fa fa-plus-square-o "></i> <?php echo $market_rv_all; ?>
                            </a>
                            <a onclick="return false;" class="btn btn-default catTreeCheckCatAll">
                                <i class="fa fa-check-square-o"></i> <?php echo $market_ch_all; ?>
                            </a>
                            <a onclick="return false;" class="btn btn-default catTreeUncheckCatAll">
                                <i class="fa fa-square-o "></i> <?php echo $market_unch_all; ?>
                            </a>
                        </div>
                    </div>
                    <ul id="categoryTree" class="tree">
                        <?php echo $market_cat_tree; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_delivery_label'); ?></label>
            <div class="col-sm-8">
                <?php echo $market->htmlDeliveryList(); ?>
                <a onclick="return false;"
                   class="yandex-money-market-delivery-more"><?php echo $language->get('market_delivery_more'); ?></a>
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-4 control-label yandex-money-market-sub-header"><?php echo $language->get('market_header_offer_settings'); ?></label>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_format'); ?></label>
            <div class="col-sm-8">
                <div>
                    <label class="radio-inline">
                        <input type="radio" <?php echo (!$yandex_money_market_simple ? ' checked="checked"' : ''); ?>
                               name="yandex_money_market_simple" value="0"/> <?php echo $language->get('market_format_vendor_model'); ?>
                    </label>
                </div>
                <div>
                    <label class="radio-inline">
                        <input type="radio" <?php echo ($yandex_money_market_simple ? ' checked="checked"' : ''); ?>
                               name="yandex_money_market_simple" value="1"/> <?php echo $language->get('market_format_simple'); ?>
                    </label>
                    <div class="form-group yandex_money_market_offer_name_template">
                        <label class="col-sm-3 control-label yandex-money-market-font-weight-normal"><?php echo $language->get('market_offer_name_template'); ?></label>
                        <div class="col-sm-9">
                            <input type="text" name="yandex_money_market_name_template"
                                   value="<?php echo $yandex_money_market_name_template; ?>" class="form-control"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_available_label'); ?></label>
            <div class="col-sm-8">
                <?php echo $market->htmlAvailableList(); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_vat_label'); ?></label>
            <div class="col-sm-8">
                <label class="form-check-label yandex-money-market-font-weight-normal">
                    <input type="checkbox" name="yandex_money_market_vat_enabled"
                           value="on" <?php echo $yandex_money_market_vat_enabled ? 'checked="checked"' : ''; ?>>
                    <?php echo $language->get('market_vat_enable_label'); ?>
                </label>
                <?php foreach($market->getTaxClasses() as $taxClass): ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label yandex-money-market-font-weight-normal"
                           for="kassa-tax-rate-<?php echo $taxClass['tax_class_id']; ?>"><?php echo $taxClass['title']; ?></label>
                    <span class="col-sm-8">
                            <select name="yandex_money_market_vat[<?php echo $taxClass['tax_class_id']; ?>]" class="form-control">
                                <?php foreach ($market->getVatList() as $vatId => $vatName): ?>
                                    <option value="<?php echo $vatId; ?>" <?php echo $market->getVatRateId($taxClass['tax_class_id']) == $vatId ? ' selected' : ''; ?>>
                                        <?php echo $vatName; ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_option_label'); ?></label>
            <div class="col-sm-8">
                <?php echo $market->htmlOptionList(); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_offer_options_label'); ?></label>
            <div class="col-sm-8">
                <div>
                    <label class="radio-inline">
                        <input type="checkbox" name="yandex_money_market_features"
                               <?php echo ($yandex_money_market_features == 'on' ? ' checked="checked"' : ''); ?>
                               value="on"/> <?php echo $language->get('market_offer_options_export_attributes'); ?>
                    </label>
                </div>
                <div>
                    <label class="radio-inline">
                        <input type="checkbox" name="yandex_money_market_dimensions"
                               <?php echo ($yandex_money_market_dimensions == 'on' ? ' checked="checked"' : ''); ?>
                               value="on"/> <?php echo $language->get('market_offer_options_export_dimensions'); ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group yandex_money_market_additional_condition_container">
            <label class="col-sm-4 control-label"><?php echo $language->get('market_additional_condition_label'); ?></label>
            <?php echo $market->htmlAdditionalConditionList(); ?>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label yandex-money-market-sub-header"><?php echo $language->get('market_header_param_settings'); ?></label>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="yandex_money_market_dynamic"><?php echo $market_lnk_yml; ?></label>
            <div class="col-sm-8">
                <input type="text" name="yandex_money_market_dynamic" value="<?php echo $yandex_money_market_lnk_yml; ?>"
                       id="yandex_money_market_dynamic" disabled="disabled"
                       class="form-control yandex-money-market-url"/>
                <span class="yandex-money-market-copy-url fa fa-clone"
                      title="<?php echo $language->get('market_copy_url_to_clipboard'); ?>"></span>
            </div>
        </div>
    </div>
</div>

<script>
    function marketAllCategoriesChangeHandler() {
        if ($(this).val() === 'on') {
            $(this).closest('form').find('.yandex-money-market-category-tree').slideUp();
        } else {
            $(this).closest('form').find('.yandex-money-market-category-tree').slideDown();
        }
    }

    function catTreeHideCatAll() {
        $(this).closest('.yandex-money-market-category-tree').find('ul.yandex_money_market_category_tree_branch').each(function () {
            $(this).slideUp();
        });
    }

    function catTreeShowCatAll() {
        $(this).closest('.yandex-money-market-category-tree').find('ul.yandex_money_market_category_tree_branch').each(function () {
            $(this).slideDown();
        });
    }

    function catTreeCheckCatAll() {
        $(this).closest('.yandex-money-market-category-tree').find('input[type=checkbox]').each(function () {
            $(this).prop("checked", true);
        });
    }

    function catTreeUncheckCatAll() {
        $(this).closest('.yandex-money-market-category-tree').find('input[type=checkbox]').each(function () {
            $(this).prop("checked", false);
        });
    }

    function marketCurrencyShowHideRate(currencyIdValue, rateElement) {
        if (currencyIdValue && currencyIdValue !== '1') {
            rateElement.show();
        } else {
            rateElement.hide();
        }
    }

    function marketCurrencySelectChangeHandler() {
        marketCurrencyShowHideRate($(this).val(), $(this).next('.edit_rate'))
    }

    function marketJsEditableEditOnHandler(event, parent) {
        event.stopPropagation();
        event.preventDefault();
        parent.find('.yandex-money-market-edit-on-button').hide();
        parent.find('.yandex_money_market_js_editable_view').hide();
        parent.find('.yandex-money-market-js-editable-edit').show();
        $(this).hide();
    }

    function marketJsEditableEditFinishHandler(parent) {
        parent.find('.yandex-money-market-js-editable-edit').hide();
        parent.find('.yandex-money-market-edit-on-button').css('display', '');
        parent.find('.yandex_money_market_js_editable_view').show();
    }

    function marketCurrencyEditOnHandler(event) {
        let parent = $(this).closest('.yandex-money-market-js-editable');
        marketJsEditableEditOnHandler(event, parent);
        marketCurrencyShowHideRate(parent.find('select').val(), parent.find('.edit_rate'));
    }

    function marketCurrencyEditFinishHandler() {
        let parent = $(this).closest('.yandex-money-market-js-editable');
        marketJsEditableEditFinishHandler(parent);
        setTimeout(function () {
            marketCurrencyUpdateViewValues(parent)
        }, 0);
    }

    function marketCurrencyUpdateViewValues(parent) {
        let plus = parent.find('.yandex_money_market_currency_edit_plus').val();
        let rateOption = parent.find('.yandex_money_market_currency_rate option:selected');
        let rateValue = rateOption.val();
        parent.find('.yandex_money_market_currency_view_plus_value').text(plus);
        parent.find('.yandex_money_market_currency_input_plus').val(plus);
        parent.find('.yandex_money_market_currency_view_rate').text(rateOption.text());
        parent.find('.yandex_money_market_currency_input_rate').val(rateValue);
        if (rateValue === '1') {
            parent.find('.yandex_money_market_currency_view_plus').hide();
        } else {
            parent.find('.yandex_money_market_currency_view_plus').show();
        }
    }

    function marketCategoryClickHandler() {
        $(this).closest('li').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    }

    function marketCopyUrlToClipboard() {
        let el = $('input[name="yandex_money_market_dynamic"]');
        el.prop('disabled', false);
        el.select();
        document.execCommand("copy");
        el.prop('disabled', true);
        alert("<?php echo $language->get('market_url_copied_to_clipboard'); ?>");
    }

    function marketCommonEditOnHandler(event) {
        let parent = $(this).closest('.yandex-money-market-js-editable');
        marketJsEditableEditOnHandler(event, parent);
    }

    function marketDeliveryEditFinishHandler() {
        let parent = $(this).closest('.yandex_money_market_delivery');
        marketJsEditableEditFinishHandler(parent);
        setTimeout(function () {
            marketDeliveryUpdateViewValues(parent)
        }, 0);
    }

    function marketDeliveryUpdateViewValues(parent) {
        let edit = parent.find('.yandex-money-market-js-editable-edit');
        let cost = edit.find('.delivery_cost').val();
        let daysFrom = edit.find('.delivery_days_from').val();
        let daysTo = edit.find('.delivery_days_to').val();
        let orderBeforeOption = edit.find('.delivery_order_before option:selected');
        let orderBeforeValue = +orderBeforeOption.val() ? orderBeforeOption.val() : "";
        let orderBeforeText = +orderBeforeOption.val()
            ? orderBeforeOption.text()
            : "<?php echo $language->get('market_delivery_default_value'); ?>";
        let days = !daysTo || daysFrom === daysTo ? +daysFrom : daysFrom + '-' + daysTo;

        let view = parent.find('.yandex_money_market_js_editable_view');
        view.find('.delivery_cost').text(+cost);
        view.find('.delivery_days').text(days);
        view.find('.delivery_order_before').text(orderBeforeText);
        parent.find('.delivery_cost').val(cost);
        parent.find('.delivery_days_from').val(daysFrom);
        parent.find('.delivery_days_to').val(daysTo);
        parent.find('.delivery_order_before').val(orderBeforeValue);
    }

    function hideEmptyDeliveries() {
        let count = 0;
        $('.yandex_money_market_delivery').each(function (index) {
            if (!index) {
                return;
            }
            let parent = $(this);
            if ((parent.find('.delivery_cost').val() === '')
                || (parent.find('.delivery_days_from').val() === '')) {
                parent.hide();
                count++;
            }
        });
        if (count) {
            $('.yandex-money-market-delivery-more').show();
        }
    }

    function marketDeliveryShowMoreOptions() {
        $('.yandex_money_market_delivery').show();
        $('.yandex-money-market-delivery-more').hide();
    }

    function marketOfferFormatClickHandler() {
        if ($('input[name="yandex_money_market_simple"]:checked').val() === "1") {
            $('.yandex_money_market_offer_name_template').show();
        } else {
            $('.yandex_money_market_offer_name_template').hide();
        }
    }

    function marketAvailableEditFinishHandler() {
        let parent = $(this).closest('.yandex_money_market_available');
        marketJsEditableEditFinishHandler(parent);
        setTimeout(function () {
            marketAvailableUpdateViewValues(parent)
        }, 0);
    }

    function marketAvailableUpdateViewValues(parent) {
        let edit = parent.find('.yandex-money-market-js-editable-edit');
        let view = parent.find('.yandex_money_market_js_editable_view');

        let delivery = edit.find('.available_delivery').is(':checked');
        let pickup = edit.find('.available_pickup').is(':checked');
        let store = edit.find('.available_store').is(':checked');

        let available = edit.find('select option:selected').val();
        if (available === 'none') {
            view.find('.available_dont_upload').show();
            view.find('.available_will_upload').hide();
        } else {
            view.find('.available_dont_upload').hide();
            view.find('.available_will_upload').show();
            if (available === 'true') {
                view.find('.yandex-money-market-available-with-ready').show();
                view.find('.yandex-money-market-available-with-to-order').hide();
            } else {
                view.find('.yandex-money-market-available-with-ready').hide();
                view.find('.yandex-money-market-available-with-to-order').show();
            }
            if (delivery || pickup || store) {
                view.find('.available_list').show();
                if (delivery) {
                    let el = view.find('.available_delivery');
                    el.show();
                    if (pickup || store) {
                        el.removeClass('last');
                    } else {
                        el.addClass('last');
                    }
                } else {
                    view.find('.available_delivery').hide();
                }
                if (pickup) {
                    let el = view.find('.available_pickup');
                    el.show();
                    if (store) {
                        el.removeClass('last');
                    } else {
                        el.addClass('last');
                    }
                } else {
                    view.find('.available_pickup').hide();
                }
                if (store) {
                    view.find('.available_store').show();
                } else {
                    view.find('.available_store').hide();
                }
            } else {
                view.find('.available_list').hide();
            }
        }

        parent.find('.yandex_money_market_available_input_available').val(available);
        parent.find('.yandex_money_market_available_input_delivery').val(delivery ? 'on' : '');
        parent.find('.yandex_money_market_available_input_pickup').val(pickup ? 'on' : '');
        parent.find('.yandex_money_market_available_input_store').val(store ? 'on' : '');
    }

    function marketAddNewAdditionalCondition() {
        let index = $(this).data('index');
        let nextIndex = index + 1;
        $(this).data('index', nextIndex);
        let list = $('.yandex_money_market_additional_condition_list');
        let template = list.find('.yandex-money-market-additional-condition-template');
        let newForm = template.clone();
        newForm.removeClass('yandex-money-market-additional-condition-template');
        template.before(newForm);
        newForm.find('.yandex_money_market_additional_condition_edit_on_button').click();
        newForm.find('input[type=hidden], input[type=checkbox]').each(function () {
            $(this).attr('name', $(this).data('name').replace(/\[\]/, '[' + index + ']'));
        });
    }

    function marketAdditionalConditionEditFinishHandler() {
        let parent = $(this).closest('.yandex_money_market_additional_condition');
        marketJsEditableEditFinishHandler(parent);
        setTimeout(function () {
            marketAdditionalConditionUpdateViewValues(parent)
        }, 0);
    }

    function marketAdditionalConditionDeleteHandler() {
        $(this).closest('.yandex_money_market_additional_condition').detach();
    }

    function marketAdditionalConditionUpdateViewValues(parent) {
        let edit = parent.find('.yandex-money-market-js-editable-edit');
        let name = edit.find('.additional_condition_name').val();
        let tag = edit.find('.additional_condition_tag').val();
        let typeValue = edit.find('input[name="additional_condition_type_value"]:checked').val();
        let staticValue = edit.find('.additional_condition_static_value').val();
        let dataValueOption = edit.find('.additional_condition_data_value option:selected');
        let dataValue = dataValueOption.val();
        let dataValueText = dataValueOption.text();
        let valueText = typeValue === 'static' ? staticValue : dataValueText;
        let view = parent.find('.yandex_money_market_js_editable_view');
        let forAllCat = edit.find('input[name="additional_condition_for_all_cat"]:checked').val();
        let join = edit.find('input[name="additional_condition_join"]:checked').val();

        edit.find('.additional_condition_categories').each(function () {
            let el = $(this);
            parent.find('.additional_condition_categories[value=' + el.val() + ']').prop("checked", el.prop("checked"));
        });

        view.find('.additional_condition_name').text(name);
        view.find('.additional_condition_tag').text(tag);
        view.find('.additional_condition_value').text(valueText);
        let forAllCatText = forAllCat === 'on'
            ? "<?php echo $language->get('market_additional_condition_for_all_category_label'); ?>"
            : "<?php echo $language->get('market_additional_condition_for_selected_category_label'); ?>";
        view.find('.additional_condition_category_list').text(forAllCatText);

        parent.find('.additional_condition_name').val(name);
        parent.find('.additional_condition_tag').val(tag);
        parent.find('.additional_condition_type_value').val(typeValue);
        parent.find('.additional_condition_static_value').val(staticValue);
        parent.find('.additional_condition_data_value').val(dataValue);
        parent.find('.additional_condition_for_all_cat').val(forAllCat);
        parent.find('.additional_condition_join').val(join);
    }


    document.addEventListener('DOMContentLoaded', function () {
        $('#tab-market').on('change', '.yandex_money_market_category_tree_switcher', marketAllCategoriesChangeHandler)
            .on('click', '.yandex-money-market-category-tree input[type="checkbox"]', marketCategoryClickHandler)
            .on('click', '.catTreeHideCatAll', catTreeHideCatAll)
            .on('click', '.catTreeShowCatAll', catTreeShowCatAll)
            .on('click', '.catTreeCheckCatAll', catTreeCheckCatAll)
            .on('click', '.catTreeUncheckCatAll', catTreeUncheckCatAll);

        $('.yandex_money_market_currency_rate').on('change', marketCurrencySelectChangeHandler);
        $('.yandex_money_market_currency_edit_on_button').on('click', marketCurrencyEditOnHandler);
        $('.yandex-money-market-currency-edit .edit_finish').on('click', marketCurrencyEditFinishHandler);
        $('.yandex-money-market-copy-url').on('click', marketCopyUrlToClipboard);
        $('.yandex_money_market_delivery_edit_on_button').on('click', marketCommonEditOnHandler);
        $('.yandex_money_market_delivery_edit .edit_finish').on('click', marketDeliveryEditFinishHandler);
        $('.yandex-money-market-delivery-more').on('click', marketDeliveryShowMoreOptions);
        marketOfferFormatClickHandler();
        $('input[name="yandex_money_market_simple"]').on('change', marketOfferFormatClickHandler);
        $('.yandex_money_market_available_edit_on_button').on('click', marketCommonEditOnHandler);
        $('.yandex_money_market_available_edit .edit_finish').on('click', marketAvailableEditFinishHandler);
        $('.yandex_money_market_available').each(marketAvailableEditFinishHandler);
        $('.yandex_money_market_additional_condition_more').on('click', marketAddNewAdditionalCondition);
        $('.yandex_money_market_additional_condition_edit_on_button').on('click', marketCommonEditOnHandler);
        $('.yandex_money_market_additional_condition_container')
            .on('click', '.yandex_money_market_additional_condition_edit_on_button', marketCommonEditOnHandler)
            .on('click', '.edit_finish', marketAdditionalConditionEditFinishHandler)
            .on('click', '.edit_delete', marketAdditionalConditionDeleteHandler);
    });
</script>