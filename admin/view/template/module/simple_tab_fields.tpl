<div ng-controller="simpleRowController" ng-init="settings.fields = !empty(settings.fields) ? settings.fields : [];rows=settings.fields;loadAllValues()">
    <vtabs extendable="true" removable-method="deleteRow(id)" extendable-placeholder="<?php echo $l->get('entry_new_field_id', true) ?>" extendable-method="createRow(id)">
        <vtab title="{{!empty(field.label[currentLanguage]) ? field.label[currentLanguage] : field.id}}" removable="{{field.custom ? 'true' : ''}}" tooltip="{{ field.id }}" removable-id="{{field.id}}" ng-repeat="field in rows">
            <htabs class="simple-field-settings">
                <htab title="<?php echo $l->get('tab_field_main', true) ?>" title-lang-id="tab_field_main">
                    <table class="form">
                        <tr>
                            <td>
                                ID
                            </td>
                            <td>
                                <h3>{{field.id}}</h3>
                            </td>
                        </tr>
                        <tr ng-init="field.label= !empty(field.label) ? field.label : {}">
                            <td>
                                <?php echo $l->get('entry_field_label'); ?>
                            </td>
                            <td>
                                <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="field.label[l.code]"></div>
                            </td>
                        </tr>
                        <!--<tr ng-init="field.custom= !empty(field.custom) ? field.custom : true">
                            <td><?php echo $l->get('entry_custom') ?></td>
                            <td><input type="checkbox" ng-model="field.custom"></td>
                        </tr>-->
                        <tr ng-if="field.custom" ng-init="field.objects = !empty(field.objects) ? field.objects : {}">
                            <td>
                                <?php echo $l->get('entry_field_object'); ?>
                            </td>
                            <td>
                                <!-- <div ng-if="!field.custom">
                                    <div ng-repeat="o in opencartObjects"><label><input ng-disabled="!field.custom" type="checkbox" ng-model="field.objects[o.id]" value="1">{{o.label}}</label></div>
                                </div> -->
                                <div ng-if="field.custom">
                                    <div ng-repeat="o in opencartObjects"><label><input type="radio" ng-model="field.object" value="{{o.id}}">{{o.label}}</label></div>
                                    <!--<div><label><input type="radio" ng-model="field.object" value="payment"><?php echo $l->get('text_field_of_payment_form') ?></label></div>-->
                                </div>
                            </td>
                        </tr>
                        <tr ng-init="field.type = !empty(field.type) ? field.type : 'text';field.type = field.type == 'phone' ? 'tel' : field.type">
                            <td>
                                <?php echo $l->get('entry_field_type'); ?>
                            </td>
                            <td>
                                <select ng-model="field.type" ng-change="changeDefault(field.id)" ng-options="type for type in types">
                                </select>
                            </td>
                        </tr>
                        <tr ng-init="field.description= !empty(field.description) ? field.description : {}">
                            <td>
                                <?php echo $l->get('entry_field_description'); ?>
                            </td>
                            <td>
                                <div ng-repeat="l in languages"><img style="vertical-align: top" ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<textarea  cols="50" ng-model="field.description[l.code]"></textarea></div>
                            </td>
                        </tr>
                        <tr ng-show="field.type != 'file'" ng-init="row.autoreload = !empty(row.autoreload) ? row.autoreload : false">
                            <td><?php echo $l->get('entry_autoreload') ?></td>
                            <td><input type="checkbox" ng-model="field.autoreload"></td>
                        </tr>
                        <tr ng-if="inArray(field.type, typesWithPlaceholder)" ng-init="field.placeholder= !empty(field.placeholder) ? field.placeholder : {}">
                            <td>
                                <?php echo $l->get('entry_field_placeholder'); ?>
                            </td>
                            <td>
                                <div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="field.placeholder[l.code]"></div>
                            </td>
                        </tr>
                        <tr ng-if="inArray(field.type, typesWithMask)" ng-init="field.mask= !empty(field.mask) ? field.mask : {}">
                            <td ng-init="field.mask.source= !empty(field.mask.source) ? field.mask.source : 'saved'">
                                <div><?php echo $l->get('entry_field_mask'); ?></div>
                                <div><label><input type="radio" ng-model="field.mask.source" value="saved"><?php echo $l->get('text_source_saved'); ?></label></div>
                                <div><label><input type="radio" ng-model="field.mask.source" value="model"><?php echo $l->get('text_source_model'); ?></label></div>
                            </td>
                            <td>
                                <div ng-if="field.mask.source == 'saved'">
                                    <input type="text" ng-model="field.mask.saved">
                                </div>
                                <div ng-if="field.mask.source == 'model'">
                                    <apivalue for-property="mask"></apivalue>
                                </div>
                            </td>
                        </tr>
                        <tr ng-if="inArray(field.type, typesWithValues)" ng-init="field.values = !empty(field.values) ? field.values : {}">
                            <td ng-init="field.values.source= !empty(field.values.source) ? field.values.source : 'saved'">
                                <div><?php echo $l->get('entry_field_values'); ?></div>
                                <div><label><input type="radio" ng-model="field.values.source" value="saved" ng-click="parseValues(field.id, currentLanguage, field.values.saved[currentLanguage])"><?php echo $l->get('text_source_saved'); ?></label></div>
                                <div><label><input type="radio" ng-model="field.values.source" value="model"><?php echo $l->get('text_source_model'); ?></label></div>
                            </td>
                            <td>
                                <div ng-if="field.values.source == 'saved'" ng-init="field.values.saved = !empty(field.values.saved) ? field.values.saved : {}">
                                    <div ng-repeat="l in languages"><img style="vertical-align: top" ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<textarea ng-model="field.values.saved[l.code]" ng-keyup="parseValues(field.id, l.code, field.values.saved[l.code])" cols="100" placeholder="Example: value1=text1;value2=text2;value3=text3"></textarea></div>
                                </div>
                                <div ng-if="field.values.source == 'model'">
                                    <table class="field-api">
                                        <tr>
                                            <td>
                                                <?php echo $l->get('entry_field_method'); ?>
                                                <div>catalog/model/tool/simpleapi{{field.custom ? 'custom' : 'main'}}.php</div>
                                            </td>
                                            <td>
                                                <input type="text" class="api-method" size="50" ng-model="field.values.method" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php echo $l->get('entry_field_filter'); ?>
                                            </td>
                                            <td>
                                                <select class="api-filter" ng-model="field.values.filter" >
                                                    <option value="">---</option>
                                                    <option value="{{f.id}}" ng-repeat="f in settings.fields" ng-selected="field.values.filter == f.id" ng-if="f.id != field.id">{{f.label[currentLanguage]}}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <a ng-click="loadValues(field.id)"><?php echo $l->get('text_load_values'); ?></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="field.type != 'file'" ng-init="field.default= !empty(field.default) ? field.default : {}">
                            <td ng-init="field.default.source = !empty(field.default.source) ? field.default.source : 'saved'">
                                <div><?php echo $l->get('entry_field_default'); ?></div>
                                <div><label><input type="radio" ng-model="field.default.source" value="saved" ng-click="parseValues(field.id, currentLanguage, field.values.saved[currentLanguage])"><?php echo $l->get('text_source_saved'); ?></label></div>
                                <div><label><input type="radio" ng-model="field.default.source" value="model"><?php echo $l->get('text_source_model'); ?></label></div>
                            </td>
                            <td ng-init="field.valuesList = !empty(field.valuesList) ? field.valuesList : {};field.default.saved = !empty(field.default.saved) ? field.default.saved : (field.type != 'checkbox' ? '' : {})">
                                <div ng-if="field.default.source == 'saved'">
                                    <input ng-if="inArray(field.type, textTypes)" type="text" size="50" ng-change="reloadDependedValues(field.id)" ng-model="field.default.saved">
                                    <select ng-if="field.type == 'select'" ng-model="field.default.saved" ng-change="reloadDependedValues(field.id)">
                                        <option ng-repeat="v in field.valuesList[currentLanguage]" ng-selected="field.default.saved == v.id" value="{{v.id}}">{{v.text}}</option>
                                    </select>
                                    <div ng-if="field.type == 'radio'" ng-repeat="v in field.valuesList[currentLanguage]"><label><input type="radio" value="{{v.id}}" ng-model="field.default.saved" ng-change="reloadDependedValues(field.id)">{{v.text}}</label></div>
                                    <div ng-if="field.type == 'checkbox'" ng-repeat="v in field.valuesList[currentLanguage]"><label><input type="checkbox" value="1" ng-model="field.default.saved[v.id]" ng-change="reloadDependedValues(field.id)">{{v.text}}</label></div>
                                </div>
                                <div ng-if="field.default.source == 'model'">
                                    <apivalue for-property="default"></apivalue>
                                </div>
                            </td>
                        </tr>
                        <tr ng-if="field.custom && field.object != 'payment' && field.type != 'file'" ng-init="field.saveToComment = !empty(field.saveToComment) ? field.saveToComment : false">
                            <td><?php echo $l->get('entry_save_to_comment') ?></td>
                            <td><input type="checkbox" ng-model="field.saveToComment"></td>
                        </tr>
                        <tr ng-if="field.custom" ng-init="field.sync = !empty(field.sync) ? field.sync : false">
                            <td><?php echo $l->get('entry_sync_field') ?><br><?php echo $l->get('entry_sync_desc') ?></td>
                            <td ng-init="customFields[field.id] = !empty(customFields[field.id]) ? customFields[field.id] : {}">
                                <input type="checkbox" ng-model="field.sync">
                                <select ng-if="field.sync" ng-model="customFields[field.id].id">
                                    <option ng-selected="empty(customFields[field.id]) || findOpencartCustomField(customFields[field.id].id) == -1" value=""><?php echo $l->get('text_create_new_opencart_field'); ?></option>
                                    <option ng-repeat="v in opencartFields" ng-selected="customFields[field.id].id == v.custom_field_id" value="{{v.custom_field_id}}">{{v.name}}</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </htab>
                <htab title="<?php echo $l->get('tab_field_attrs', true) ?>" title-lang-id="tab_field_attrs" title-show="{{field.type == 'date' || field.type == 'time'}}">
                    <table class="form">
                        <tbody  ng-show="field.type == 'date'">
                            <tr>
                                <td ng-init="field.dateStartType = !empty(field.dateStartType) ? field.dateStartType : 'calculated'">
                                    <div><?php echo $l->get('entry_field_date_start'); ?></div>
                                    <div><label><input type="radio" ng-model="field.dateStartType" value="fixed"><?php echo $l->get('text_fixed'); ?></label></div>
                                    <div><label><input type="radio" ng-model="field.dateStartType" value="calculated"><?php echo $l->get('text_calculated'); ?></label></div>
                                </td>
                                <td>
                                    <div ng-show="field.dateStartType == 'fixed'">
                                        <input type="date" ng-model="field.dateStartDay">
                                    </div>
                                    <div ng-show="field.dateStartType == 'calculated'">
                                        <input type="text" ng-model="field.dateStartAfter">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td ng-init="field.dateEndType = !empty(field.dateEndType) ? field.dateEndType : 'calculated'">
                                    <div><?php echo $l->get('entry_field_date_end'); ?></div>
                                    <div><label><input type="radio" ng-model="field.dateEndType" value="fixed"><?php echo $l->get('text_fixed'); ?></label></div>
                                    <div><label><input type="radio" ng-model="field.dateEndType" value="calculated"><?php echo $l->get('text_calculated'); ?></label></div>
                                </td>
                                <td>
                                    <div ng-show="field.dateEndType == 'fixed'">
                                        <input type="date" ng-model="field.dateEndDay">
                                    </div>
                                    <div ng-show="field.dateEndType == 'calculated'">
                                        <input type="text" ng-model="field.dateEndAfter">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div><?php echo $l->get('entry_field_date_weekdays_only'); ?></div>
                                </td>
                                <td>
                                    <input type="checkbox" ng-model="field.dateWeekdaysOnly" value="1">
                                </td>
                            </tr>
                            <tr>
                                <td ng-init="field.dateSelected = !empty(field.dateSelected) ? field.dateSelected : {}">
                                    <div><?php echo $l->get('entry_field_date_selected_only'); ?></div>
                                </td>
                                <td>
                                    <div><label><input type="checkbox" ng-model="field.dateSelected[0]" value="1"><?php echo $l->get('text_sunday') ?></label></div>
                                    <div><label><input type="checkbox" ng-model="field.dateSelected[1]" value="1"><?php echo $l->get('text_monday') ?></label></div>
                                    <div><label><input type="checkbox" ng-model="field.dateSelected[2]" value="1"><?php echo $l->get('text_tuesday') ?></label></div>
                                    <div><label><input type="checkbox" ng-model="field.dateSelected[3]" value="1"><?php echo $l->get('text_wednesday') ?></label></div>
                                    <div><label><input type="checkbox" ng-model="field.dateSelected[4]" value="1"><?php echo $l->get('text_thursday') ?></label></div>
                                    <div><label><input type="checkbox" ng-model="field.dateSelected[5]" value="1"><?php echo $l->get('text_friday') ?></label></div>
                                    <div><label><input type="checkbox" ng-model="field.dateSelected[6]" value="1"><?php echo $l->get('text_saturday') ?></label></div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody ng-show="field.type == 'time'">
                            <tr>
                                <td>
                                    <div><?php echo $l->get('entry_field_time_hour_only'); ?></div>
                                </td>
                                <td>
                                    <input type="checkbox" ng-model="field.timeHoursOnly" value="1">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div><?php echo $l->get('entry_field_time_min'); ?></div>
                                </td>
                                <td>
                                    <input type="time" ng-model="field.timeMin">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div><?php echo $l->get('entry_field_time_max'); ?></div>
                                </td>
                                <td>
                                    <input type="time" ng-model="field.timeMax">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </htab>
                <htab title="<?php echo $l->get('tab_field_validation', true) ?>" title-lang-id="tab_field_validation">
                    <table class="form" ng-init="field.rules= !empty(field.rules) ? field.rules : {}">
                        <tr ng-if="inArray(field.type, rulesForTypes['notEmpty'])" ng-init="field.rules.notEmpty = !empty(field.rules.notEmpty) ? field.rules.notEmpty : {}">
                            <td>
                                <label><input type="checkbox" ng-model="field.rules.notEmpty.enabled"><?php echo $l->get('text_rule_notempty') ?></label>
                            </td>
                            <td>
                                <table class="form" ng-if="field.rules.notEmpty.enabled">
                                    <tr ng-init="field.rules.notEmpty.errorText= !empty(field.rules.notEmpty.errorText) ? field.rules.notEmpty.errorText : {}">
                                        <td><?php echo $l->get('text_rule_error') ?></td>
                                        <td><div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="field.rules.notEmpty.errorText[l.code]"></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr ng-if="field.type != 'file' && inArray(field.type, rulesForTypes['equal'])" ng-init="field.rules.equal = !empty(field.rules.equal) ? field.rules.equal : {}">
                            <td>
                                <label><input type="checkbox" ng-model="field.rules.equal.enabled"><?php echo $l->get('text_rule_equal') ?></label>
                            </td>
                            <td>
                                <table class="form" ng-if="field.rules.equal.enabled">
                                    <tr>
                                        <td><?php echo $l->get('entry_rule_equal_field') ?></td>
                                        <td>
                                            <select ng-model="field.rules.equal.fieldId" >
                                                <option value="">---</option>
                                                <option value="{{f.id}}" ng-repeat="f in settings.fields" ng-selected="field.rules.equal.fieldId == f.id" ng-if="f.id != field.id">{{f.label[currentLanguage]}}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr ng-init="field.rules.equal.errorText= !empty(field.rules.equal.errorText) ? field.rules.equal.errorText : {}">
                                        <td><?php echo $l->get('text_rule_error') ?></td>
                                        <td><div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="field.rules.equal.errorText[l.code]"></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr ng-if="inArray(field.type, rulesForTypes['byLength'])" ng-init="field.rules.byLength = !empty(field.rules.byLength) ? field.rules.byLength : {}">
                            <td>
                                <label><input type="checkbox" ng-model="field.rules.byLength.enabled"><?php echo $l->get('text_rule_length') ?></label>
                            </td>
                            <td>
                                <table class="form" ng-if="field.rules.byLength.enabled">
                                    <tr>
                                        <td><?php echo $l->get('text_rule_min') ?></td>
                                        <td><input type="text" ng-model="field.rules.byLength.min"></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $l->get('text_rule_max') ?></td>
                                        <td><input type="text" ng-model="field.rules.byLength.max"></td>
                                    </tr>
                                    <tr ng-init="field.rules.byLength.errorText= !empty(field.rules.byLength.errorText) ? field.rules.byLength.errorText : {}">
                                        <td><?php echo $l->get('text_rule_error') ?></td>
                                        <td><div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="field.rules.byLength.errorText[l.code]"></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr ng-if="inArray(field.type, rulesForTypes['regexp'])" ng-init="field.rules.regexp=  !empty(field.rules.regexp) ? field.rules.regexp : {}">
                            <td>
                                <label><input type="checkbox" ng-model="field.rules.regexp.enabled"><?php echo $l->get('text_rule_regexp') ?></label>
                            </td>
                            <td>
                                <table class="form" ng-if="field.rules.regexp.enabled">
                                    <tr>
                                        <td><?php echo $l->get('text_rule_regexp') ?></td>
                                        <td><input type="text" ng-model="field.rules.regexp.value"></td>
                                    </tr>
                                    <tr ng-init="field.rules.regexp.errorText= !empty(field.rules.regexp.errorText) ? field.rules.regexp.errorText : {}">
                                        <td><?php echo $l->get('text_rule_error') ?></td>
                                        <td><div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="field.rules.regexp.errorText[l.code]"></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr ng-if="inArray(field.type, rulesForTypes['api'])" ng-init="field.rules.api = !empty(field.rules.api) ? field.rules.api : {}">
                            <td>
                                <label><input type="checkbox" ng-model="field.rules.api.enabled"><?php echo $l->get('text_rule_api') ?></label>
                            </td>
                            <td>
                                <table class="form" ng-if="field.rules.api.enabled">
                                    <tr>
                                        <td><?php echo $l->get('entry_field_method') ?><div>catalog/model/tool/simpleapi{{field.custom ? 'custom' : 'main'}}.php</div></td>
                                        <td><input type="text" ng-model="field.rules.api.method"></td>
                                    </tr>
                                    <td>
                                        <?php echo $l->get('entry_field_pass'); ?>
                                    </td>
                                    <td>
                                        <select class="api-filter" ng-model="field.rules.api.filter" >
                                            <option value="">---</option>
                                            <option value="{{f.id}}" ng-repeat="f in settings.fields" ng-if="f.id != field.id" ng-selected="field.rules.api.filter == f.id">{{f.label[currentLanguage]}}</option>
                                        </select>
                                    </td>
                                    <tr ng-init="field.rules.api.errorText= !empty(field.rules.api.errorText) ? field.rules.api.errorText : {}">
                                        <td><?php echo $l->get('text_rule_error') ?></td>
                                        <td><div ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;<input type="text" size="50" ng-model="field.rules.api.errorText[l.code]"></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </htab>
            </htabs>
        </vtab>
    </vtabs>
</div>