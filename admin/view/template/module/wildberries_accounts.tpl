<div class="container-fluid" style="padding: 0;">
    <ul id="wb_auth_nav_pills" class="nav nav-pills nav-stacked col-md-2">
        <?php foreach ($wb_settings as $index => $setting) : ?>
        <li class="auth_tabs <?= $index == 0 ? 'active': ''; ?>">
            <a href="#tab<?= $index;?>" data-toggle="tab">
                <?= !empty($setting['wb_store_name']) ? $setting['wb_store_name'] : '<i class="fa fa-exclamation-circle"></i>';?>
            </a>
        </li>
        <?php endforeach; ?>
        <li id="wb_auth_new_tab">
            <a href="#" onclick="addNewTab(event)">Новая вкладка</a>
        </li>
    </ul>
    <div id="wb_auth_tab_content" class="tab-content">
        <?php foreach ($wb_settings as $index => $setting) : ?>
        <div class="tab-pane <?= $index == 0 ? 'active': ''; ?> col-md-10" id="tab<?= $index; ?>">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="wb_store_name_<?= $index; ?>"><span data-toggle="tooltip" title="<?= $entry_store_name; ?>"><?= $entry_store_name; ?></span></label>
                <div class="col-sm-5">
                    <input type="text" oninput="changeTitle(event, <?= $index;?>)" name="wb_store_name_<?= $index; ?>" value="<?= !empty($setting['wb_store_name']) ? $setting['wb_store_name'] : ''; ?>" placeholder="<?= $entry_store_name; ?>" id="wb_store_name_<?= $index; ?>" class="form-control"/>
                </div>
                <div class="col-sm-5">
                    
                </div>	
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="wb_supplier_uuid_<?= $index; ?>"><span data-toggle="tooltip" title="<?= $entry_supplier_uuid; ?>"><?= $entry_supplier_uuid; ?></span></label>
                <div class="col-sm-5">
                    <input type="text" name="wb_supplier_uuid_<?= $index; ?>" value="<?= !empty($setting['wb_supplier_uuid']) ? $setting['wb_supplier_uuid'] : ''; ?>" placeholder="<?= $entry_supplier_uuid; ?>" id="entry-supplier-uuid_<?= $index; ?>" class="form-control"/>
                </div>
                <div class="col-sm-5">
                    Для того чтобы найти SuplierID необходимо в Google Chrome нажать F12 </br>и перейти на вкладку Network <a href="http://prntscr.com/z8kvcz" target="_blank">SuplierID здесь</a></br>
                </div>	
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="wb_token_phone"><span data-toggle="tooltip" title="<?= $entry_wb_token_phone; ?>"><?= $entry_wb_token_phone; ?></span></label>
                <div class="col-sm-5">
                    <input type="text" name="wb_token_phone_<?= $index; ?>" value="<?= !empty($setting['wb_token_phone']) ? $setting['wb_token_phone'] : '' ;?>" placeholder="<?= $entry_wb_token_phone; ?>" id="wb_token_phone" class="form-control"/>
                </div>
                <div class="col-sm-5">Токен необходимо получить на странице <a target="_blank" href="https://seller.wildberries.ru/supplier-settings/access-to-new-api">Доступ к API</a></div>
            </div> 
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?= $work_status; ?></label>
                <div class="col-sm-5">
                    <select name="wb_work_status" id="input-status_<?= $index; ?>" class="form-control">
                    <?php if ($setting['wb_work_status']) : ?>
                        <option value="1" selected="selected"><?= $text_production; ?></option>
                        <option value="0"><?= $text_test; ?></option>
                    <?php else : ?>
                        <option value="1"><?= $text_production; ?></option>
                        <option value="0" selected="selected"><?= $text_test; ?></option>
                    <?php endif; ?>
                    </select>
                </div>
                <div class="col-sm-5">Выберите режим работы API Stock, price, status</div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="entry-w-token"><span data-toggle="tooltip" title="<?= $entry_w_token; ?>"><?= $entry_w_token; ?></span></label>
                <div class="col-sm-5">
                    <input type="text" name="wb_w_token_<?= $index; ?>" value="<?= $setting['wb_w_token']; ?>" placeholder="<?= $entry_w_token; ?>" id="entry-w-token" class="form-control"/>
                    <?php if($error_w_token) : ?>
                    <div class="text-danger"><?= $error_w_token; ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-sm-5">Добавьте токен для рабочего режима работы API Stock, price, status</div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="entry-t-token"><span data-toggle="tooltip" title="<?= $entry_t_token; ?>"><?= $entry_t_token; ?></span></label>
                <div class="col-sm-5">
                    <input type="text" name="wb_t_token_<?= $index; ?>" value="<?= $setting['wb_t_token']; ?>" placeholder="<?= $entry_t_token; ?>" id="entry-t-token" class="form-control"/>
                    <?php if($error_t_token) : ?>
                    <div class="text-danger"><?= $error_t_token; ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-sm-5">Добавьте токен для тестового режима работы API Stock, price, status</div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="entry-store"><span data-toggle="tooltip" title="<?= $entry_store; ?>"><?= $entry_store; ?></span></label>
                <div class="col-sm-5">
                    <input type="text" name="wb_store_<?= $index; ?>" value="<?= !empty($setting['wb_store']) ? $setting['wb_store'] : '' ; ?>" placeholder="<?= $entry_store; ?>" id="entry-store" class="form-control"/>
                    <?php if($error_store) { ?>
                        <div class="text-danger"><?= $error_store; ?></div>
                    <?php } ?>
                </div>
                <div class="col-sm-5">Добавьте <a href="https://suppliers-portal.wildberries.ru/marketplace-pass/warehouses" target="_blank" class="btn">ID склада</a></div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?= $entry_create_products; ?></label>
                <div class="col-sm-5 checkboxed">
                    <input type="checkbox" name="wb_product_create_<?= $index; ?>" <?= !empty($setting['wb_product_create']) && $setting['wb_product_create'] == 'on' ? 'checked' : '' ; ?> class="form-control"/>
                </div>
                <div class="col-sm-5 checkboxed">Товары которых нет в Opencart будут создаваться при синхронизации с Wildberries</div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?= $entry_create_group; ?></label>
                <div class="col-sm-5 checkboxed">
                    <input type="checkbox" name="wb_product_group_<?= $index; ?>" <?= !empty($setting['wb_product_group']) && $setting['wb_product_group'] == 'on' ? 'checked' : '' ; ?> class="form-control"/>
                </div>
                <div class="col-sm-5 checkboxed">Номенклатуры одной карточки будут опциями в товаре opencart</div>
            </div>
            <legend></legend>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?= $entry_status; ?></label>
                <div class="col-sm-5">
                    <select name="wb_status_<?= $index; ?>" id="input-status" class="form-control">
                    <?php if ($setting['wb_status']) : ?>
                        <option value="1" selected="selected"><?= $text_enabled; ?></option>
                        <option value="0"><?= $text_disabled; ?></option>
                    <?php  else : ?>
                        <option value="1"><?= $text_enabled; ?></option>
                        <option value="0" selected="selected"><?= $text_disabled; ?></option>
                    <?php endif; ?>
                    </select>
                </div>
                <div class="col-sm-5">Инструкция</div>
            </div>
            <input type="hidden" name="wb_refresh_token_<?= $index; ?>" value="<?= !empty($setting['wb_refresh_token']) ? $setting['wb_refresh_token'] : '' ;?>" />
            <input type="hidden" name="wb_token_created_<?= $index; ?>" value="<?= !empty($setting['wb_token_created']) ? $setting['wb_token_created'] : '' ;?>" />
            <input type="hidden" name="phone_token_value_<?= $index;?>" value="<?= !empty($setting['phone_token_value']) ? $setting['phone_token_value'] : '' ;?>">
            <input type="hidden" name="wb_uuid_<?= $index;?>" value="<?= !empty($setting['wb_uuid']) ? $setting['wb_uuid'] : '' ;?>" />
        </div>
        <?php endforeach; ?>
    </div>
</div>
<style>
.checkboxed {
    padding-top: 8px;
}
</style>
<script>
function addNewTab(event) {
    event.preventDefault();
    var index = $('#wb_auth_nav_pills .auth_tabs').length;

    var aListItem = $('<a />', {
        href: '#tab' + index,
        'data-toggle': 'tab',
        html: '<i class="fa fa-exclamation-circle"></i>'
    });
    var listItem = $('<li />', {
        'class': 'auth_tabs'
    }).append(aListItem);

    $('#wb_auth_new_tab').before(listItem);

    /** wb_store_name start **/
    var storeNameInput = $('<input />', {
        type: 'text',
        name: 'wb_store_name_' + index,
        value: '',
        'oninput': 'changeTitle(event, ' + index + ')',
        'class': 'form-control',
        placeholder: '<?= $entry_store_name;?>'
    });
    var storeName = combineFormGroup({
        id: index,
        required: false,
        label: {
            title: '<?= $entry_store_name; ?>'
        },
        inputElement: storeNameInput,
        notice: ''
    });
    /** wb_store_name end **/

    /** supplier block start **/
    var supplierInput = $('<input />', {
        type: 'text',
        name: 'wb_supplier_uuid_' + index,
        value: '',
        'class': 'form-control',
        placeholder: '<?= $entry_supplier_uuid;?>'
    });
    var supplierBlock = combineFormGroup({
        id: index,
        required: false,
        label: {
            title: '<?= $entry_supplier_uuid; ?>'
        },
        inputElement: supplierInput,
        notice: 'Для того чтобы найти SuplierID необходимо в Google Chrome нажать F12 </br>и перейти на вкладку Network <a href="http://prntscr.com/z8kvcz" target="_blank">SuplierID здесь</a></br>'
    });
    /** supplier block end **/

    /** work select start **/
    var workStatusSelect = $('<select />', {
        name: 'wb_work_status_' + index,
        'class': 'form-control'
    });
    [
        $('<option />', {
            value: 0,
            selected: 'selected',
            text: '<?= $text_test;?>'
        }), $('<option />', {
            value: 1,
            text: '<?= $text_production;?>'
        })
    ].forEach(function(el) {
        workStatusSelect.append(el);
    });
    var workStatus = combineFormGroup({
        id: index,
        required: false,
        label: {
            title: '<?= $work_status; ?>'
        },
        inputElement: workStatusSelect,
        notice: 'Выберите режим работы API Stock, price, status'
    });
    /** work select end **/
    /** 'wb_token_phone' start **/
    var wbTokenPhoneInput = $('<input />', {
        type: 'text',
        name: 'wb_token_phone_' + index,
        value: '',
        'class': 'form-control',
        placeholder: '<?= $entry_wb_token_phone;?>'
    });
    var wbTokenPhoneInputBlock = combineFormGroup({
        id: index,
        required: true,
        label: {
            title: '<?= $entry_wb_token_phone; ?>'
        },
        inputElement: wbTokenPhoneInput,
        notice: 'Токен необходимо получить на странице <a target="_blank" href="https://seller.wildberries.ru/supplier-settings/access-to-new-api">Доступ к API</a>'
    });
    /** 'wb_token_phone' end **/
    /** work w token start **/
    var wTokenInput = $('<input />', {
        type: 'text',
        'class': 'form-control',
        value: '',
        name: 'wb_w_token_' + index
    });
    var wToken = combineFormGroup({
        id: index,
        required: true,
        label: {
            title: '<?= $entry_w_token; ?>'
        },
        inputElement: wTokenInput,
        notice: 'Добавьте токен для рабочего режима работы API Stock, price, status'
    });
    /** work w token end **/
    /** work t token start **/
    var tTokenInput = $('<input />', {
        type: 'text',
        'class': 'form-control',
        value: '',
        name: 'wb_t_token_' + index
    });
    var tToken = combineFormGroup({
        id: index,
        required: true,
        label: {
            title: '<?= $entry_t_token; ?>'
        },
        inputElement: tTokenInput,
        notice: 'Добавьте токен для тестового режима работы API Stock, price, status'
    });
    /** work t token end **/
    /** work store start **/
    var wStoreInput = $('<input />', {
        type: 'text',
        'class': 'form-control',
        value: '',
        name: 'wb_store_' + index
    });
    var wStore = combineFormGroup({
        id: index,
        required: true,
        label: {
            title: '<?= $entry_store; ?>'
        },
        inputElement: wStoreInput,
        notice: 'Добавьте <a href="https://suppliers-portal.wildberries.ru/marketplace-pass/warehouses" target="_blank" class="btn">ID склада</a>'
    });
    /** work store end **/
    /** product create start**/
    var wProductCreateInput = $('<input />', {
        type: 'checkbox',
        'class': 'form-control checkboxed',
        value: '',
        name: 'wb_product_create_' + index
    });
    var wProductCreate = combineFormGroup({
        id: index,
        required: false,
        label: {
            title: '<?= $entry_create_products; ?>'
        },
        inputElement: wProductCreateInput,
        notice: 'Товары которых нет в Opencart будут создаваться при синхронизации с Wildberries'
    });
    /** product create end **/
    /** product group create start**/
    var wProductGroupInput = $('<input />', {
        type: 'checkbox',
        'class': 'form-control checkboxed',
        value: '',
        name: 'wb_product_group_' + index
    });
    var wProductGroup = combineFormGroup({
        id: index,
        required: false,
        label: {
            title: '<?= $entry_create_group; ?>'
        },
        inputElement: wProductGroupInput,
        notice: 'Номенклатуры одной карточки будут опциями в товаре opencart',
    });
    /** product group create end **/
    /** work legend content API start **/
    var legendContentApi = $('<legend />', {
        text: '<?= $content_api_head;?>'
    });
    /** work legend content API end **/
    /** work entry phone start **/
    var entryPhoneLabel = $('<label />', {
        text: '<?= $entry_phone;?>',
        'class': 'col-sm-2 control-label'
    });
    var entryPhoneInput = $('<input />', {
        maxlength: 11,
        name: 'wb_phone_' + index,
        id: 'entry-phone_' + index,
        'oninput': 'changePhone(event, ' + index + ')',
        value: '',
        'class': 'form-control phone_mask'
    });
    var entryPhoneInputAlert = $('<div />', {
        'class': 'alert alert-danger',
        html: '<i class="fa fa-exclamation-circle"></i><?= $phone_token_status; ?>'
    })
    var entryPhoneInputWrapper = $('<div />', {
        'class': 'col-sm-2'
    });
    [entryPhoneInput, entryPhoneInputAlert].forEach(function(elk){ entryPhoneInputWrapper.append(elk); });

    var aAuthToken = $('<a />', {
        'onclick': 'setToken(event, ' + index + ')',
        'data-toggle': 'tooltip',
        title: '<?= $button_auth_phone; ?>',
        'class': 'btn btn-primary'
    }).append('<i class="fa fa-save"></i> <?= $button_auth_phone; ?>');
    var entryPhoneAuthWrapper = $('<div />', {
        'class': 'col-sm-3'
    }).append(aAuthToken);

    var entryPhoneNotice = $('<div />', {
        'class': 'col-sm-5',
        html: "1. Введите номер телефона указанному при регистрации личного кабинета WB</br>2. Нажмите кнопку \"Авторизация\"</br>3. Введите полученный код в появившемся окне и нажмите \"Отправить код из СМС\"</br>4. Код необходимо ввести в течении 2 минут</br>5. При успешной авторизации под номером телефона будет написано \"Токен успешно получен\""
    });

    var entryPhoneFormGroup = $('<div />', {
        'class': 'form-group'
    });

    [
        entryPhoneLabel,
        entryPhoneInputWrapper,
        entryPhoneAuthWrapper,
        entryPhoneNotice
    ].forEach(function(eld) { entryPhoneFormGroup.append(eld); });
    /** work entry phone end **/
    /** empty legend start **/
    var emptyLegend = $('<legend />');
    /** empty legend end **/
    /** entry status start **/
    var entryStatusSelect = $('<select />', {
        name: 'input-status_' + index,
        'class': 'form-control'
    });
    [
        $('<option />', {
            value: 0,
            selected: 'selected',
            text: '<?= $text_disabled;?>'
        }),
        $('<option />', {
            value: 1,
            text: '<?= $text_enabled;?>'
        })
    ].forEach(function(ela){ entryStatusSelect.append(ela); });
    var entryStatus = combineFormGroup({
        id: index,
        required: false,
        label: {
            title: '<?= $entry_status; ?>'
        },
        inputElement: entryStatusSelect,
        notice: 'Инструкция'
    });
    /** entry status end **/
    var tabPane = $('<div />', {
        'class': 'tab-pane col-md-10',
        id: 'tab' + index,
    });


    [
        storeName,
        supplierBlock,
        wbTokenPhoneInputBlock,
        workStatus,
        wToken,
        tToken,
        wStore,
        wProductCreate,
        wProductGroup,
        //legendContentApi,
        //entryPhoneFormGroup,
        emptyLegend,
        entryStatus
    ].forEach(function(eli) { tabPane.append(eli); });

    /** input groups start **/
    var inputGroups = ['phone_token_value', 'wb_token_created', 'wb_refresh_token', 'wb_token_created'].map(function(elp){
        return $('<input />', {
            type: 'hidden',
            name: elp + '_' + index,
            value: '',
        });
    });
    /** input groups end **/
    /** wb_uuid input start **/
    var uuid = (new Date().getTime() / 1000) >> 0;
    var wbUuid = $('<input />', {
        type: 'hidden',
        name: 'wb_uuid_' + index,
        value: uuid
    });
    /** wb_uuid input end **/
    tabPane.append(wbUuid);
    inputGroups.forEach(function(eli) { tabPane.append(eli); });

    $('#wb_auth_tab_content').append(tabPane);
}
function combineFormGroup(data = {
    id: 0,
    required: false,
    label: {
        title: ''
    },
    inputElement: {
        id: '0',
    },
    notice: ''
}) {
    var label = $('<label />', {
        'class': 'col-sm-2 control-label',
        html: data.label.title
    });
    var inputWrap = $('<div />', {
        'class': 'col-sm-5'
    }).append($(data.inputElement));
    var noticeWrap = $('<div />', {
        'class': 'col-sm-5',
        html: data.notice
    });
    var formGroupClass = data.required ? 'form-group required' : 'form-group';
    var formGroup = $('<div />', {
        'class': formGroupClass
    });
    [label, inputWrap, noticeWrap].forEach(function(element){
        formGroup.append(element);
    });
    return formGroup;
}
$(document).ready(function() {
    var trig_form = false;
    $('[form="form-wb"]').on('submit, click', function(event) {
        if (trig_form) {
            trig_form = false;
            return;
        }
        event.preventDefault();
        var settingsData = [];
        $('#wb_auth_tab_content .tab-pane').each(function(i, el) {
            var fields = $(el).find('input, select');
            var phone = $(el).find('[name=wb_token_phone_' + i + ']');
            if (!phone.length || (phone.length && !phone.val())) {
            } else {
                var serFields = fields.serializeArray();
                var obj = {};
                serFields.forEach(function(el){
                    var key = el.name.replace('_' + i, '');
                    obj[key] = el.value;
                });
                settingsData.push(obj);
            }
        });
        var settingsInput = $('<input />', {
            type: 'hidden',
            name: 'wb_settings',
            value: JSON.stringify(settingsData),
        });
        $('#form-wb').append(settingsInput);
        trig_form = true;
        $(this).trigger(event.type);
    });
});
function changePhone (event, index) {
    var targVal = $(event.target).val();
    var newVal = targVal.replace(/[^0-9]/g, "");
    $(event.target).val(newVal);
}
function changeTitle (event, index) {
    var newTitle = $(event.target).val();
    var tabTitle = newTitle.length ? newTitle : '<i class="fa fa-exclamation-circle"></i>' ;
    $('#wb_auth_nav_pills .auth_tabs').eq(index).find('a').html(tabTitle)
}
</script>
