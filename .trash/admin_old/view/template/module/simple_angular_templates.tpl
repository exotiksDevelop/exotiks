<script type="text/ng-template" id="htabs">
    <div>
        <div class="htabs">
            <a ng-repeat="tab in tabs" ng-click="select(tab)" ng-show="showTitle(tab)" ng-class="{selected:tab.selected}" style="display:inline;">
                <span class="language-helper" id="{{tab.titleLangId}}">{{tab.title}}</span>
            </a>
        </div>
        <div style="width:100%;height:1px;"></div>
        <div ng-transclude></div>
    </div>
</script>
<script type="text/ng-template" id="vtabs">
<div>
    <div class="vtabs">
        <a ng-repeat="tab in tabs" data-tooltip="{{tab.tooltip}}" ng-click="select(tab)" ng-class="{selected:tab.selected}">
            <img ng-if="tab.removable && tab.removableId" src="view/image/delete.png" ng-click="remove(tab)" alt="remove">
            <span class="language-helper" id="{{tab.titleLangId}}">{{tab.title}}</span>
        </a>
        <span ng-if="extendable">
            <input type="text" ng-model="extendableId" placeholder="{{extendablePlaceholder}}" ng-keydown="$event.keyCode === 13 ? extendableMethod({id:extendableId}) : '';$event.keyCode === 13 ? (extendableId = '') : ''"><img ng-click="extendableMethod({id:extendableId});extendableId=''" title="add" alt="add" src="view/image/add.png">
        </span>
    </div>
    <div ng-transclude></div>
</div>
</script>
<script type="text/ng-template" id="apivalue">
    <table class="field-api">
        <tr>
            <td>
                <?php echo $l->get('entry_field_method'); ?>
                <div>catalog/model/tool/simpleapi{{field.custom ? 'custom' : 'main'}}.php</div>
            </td>
            <td>
                <input type="text" class="api-method" size="50" ng-model="field[forProperty].method" >
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_field_pass'); ?>
            </td>
            <td>
                <select class="api-filter" ng-model="field[forProperty].filter" >
                    <option value="">---</option>
                    <option value="{{f.id}}" ng-repeat="f in settings.fields" ng-if="f.id != field.id" ng-selected="f.id == field[forProperty].filter">{{f.label[currentLanguage]}}</option>
                </select>
            </td>
        </tr>
    </table>
</script>
<script type="text/ng-template" id="scenario">
    <div>
        <div style="border-bottom:1px dotted #CCCCCC;margin-bottom:10px;"><label><input type="radio" ng-change="setScenario()" ng-model="setData.byDefault" ng-click="setData.shippingMethod = '';setData.paymentMethod = ''" value="1"><?php echo $l->get('text_default_set') ?></label></div>
        <div style="margin-bottom:10px;">
            <div><?php echo $l->get('text_shipping_methods') ?></div>
            <div ng-repeat="method in shippingMethods"><label><input type="radio" ng-change="setScenario()" ng-model="setData.shippingMethod" ng-click="setData.byDefault = 0" value="{{method.code}}">{{method.title[currentLanguage]}}</label></div>
        </div>
        <div style="margin-bottom:10px;">
            <div><?php echo $l->get('text_payment_methods') ?></div>
            <div ng-repeat="method in paymentMethods"><label><input type="radio" ng-change="setScenario()" ng-model="setData.paymentMethod" ng-click="setData.byDefault = 0" value="{{method.code}}">{{method.title[currentLanguage]}}</label></div>
        </div>
        <div style="font-weight:bold;margin:10px 0;" ng-if="getScenariosCount()"><?php echo $l->get('text_scenarios') ?> ({{getScenariosCount()}}):</div>
        <div style="border-bottom:1px dotted #CCCCCC;margin-bottom:5px;" ng-repeat="scenarioId in getScenarios()">{{scenarioId}}</div>
        <div style="margin-top:10px;"><a ng-if="getScenariosCount()" ng-click="resetScenarioToDefault()"><?php echo $l->get('text_reset_all_scenarios') ?></a></div>
    </div>
</script>
<script type="text/ng-template" id="rows">
    <div>
        <strong style="display:block;margin-bottom:10px;"><?php echo $l->get('text_help_for_set') ?></strong style="margin-bottom:10px;">
        <div style="overflow:hidden;margin-bottom:10px;">
            <div ui-sortable ng-model="setData.rows[setData.scenario]" style="float:left;width:25%;margin-right:2%;">
                <div ng-repeat="row in setData.rows[setData.scenario] | orderBy:'sortOrder'" row-type="{{row.type}}" row-id="{{row.id}}" class="set-row" ng-init="setData.selectedType = setData.selectedType ? setData.selectedType : row.type;setData.selectedId = setData.selectedId ? setData.selectedId : row.id;">
                    <img src="view/image/delete.png" ng-show="existRow(row.type, row.id)" alt="remove" ng-click="removeRow(row.type, row.id)" style="margin-right:5px;vertical-align:mmiddle;">
                    <div ng-show="existRow(row.type, row.id) && (row.type == 'header' || row.type == 'field')" style="display:inline-block;" ng-class="{selected:selected(row.type, row.id),logged:row.hideForLogged ? true : false,guest:row.hideForGuest ? true : false}" ng-click="select(row.type, row.id)">
                        <span class="required" ng-show="row.masterField">&uarr;</span>
                        <span class="required" ng-show="row.type == 'field' && row.required == 1">*</span>
                        <span class="required-master" ng-show="row.type == 'field' && row.required == 2">*</span>
                        <img ng-show="setData.both && row.type == 'field' && isFieldAutoreload(row.id)" src="view/image/simple_autoreload.png" alt="">
                        <span ng-if="row.type == 'header'" style="font-weight:bold;">{{getHeaderName(row.id)}}</span>
                        <span ng-if="row.type == 'field'">{{getFieldName(row.id)}}</span>
                    </div>
                    <div ng-show="row.type == 'splitter'" style="display:inline-block;" ng-click="select(row.type, row.id)">
                        <span>~~ <?php echo $l->get('text_splitter', true) ?> ~~</span>
                    </div>
                </div>
            </div>
            <div style="float:left;width:73%;">
                <table class="form" ng-repeat="row in setData.rows[setData.scenario]" ng-show="selected(row.type, row.id)">
                    <tr>
                        <td colspan="2">
                            <strong ng-if="row.type == 'header'" style="font-weight:bold;">{{getHeaderName(row.id)}}</strong>
                            <strong ng-if="row.type == 'field'">{{getFieldName(row.id)}}</strong>
                        </td>
                    </tr>
                    <tr ng-show="setData.both" ng-init="row.hideForLogged = !empty(row.hideForLogged) ? row.hideForLogged : false">
                        <td><?php echo $l->get('entry_field_hide_for_logged') ?></td>
                        <td><input type="checkbox" ng-model="row.hideForLogged" ng-change="createScenario()"></td>
                    </tr>
                    <tr ng-show="setData.both" ng-init="row.hideForGuest = !empty(row.hideForGuest) ? row.hideForGuest : false">
                        <td><?php echo $l->get('entry_field_hide_for_guest') ?></td>
                        <td><input type="checkbox" ng-model="row.hideForGuest" ng-change="createScenario()"></td>
                    </tr>
                    <tr ng-init="row.masterField = !empty(row.masterField) ? row.masterField : ''">
                        <td><?php echo $l->get('entry_displaying_depends_on') ?></td>
                        <td>
                            <select ng-model="row.masterField" ng-change="createScenario()">
                                <option value="">---</option>
                                <option value="{{f.id}}" ng-selected="row.masterField == f.id" ng-repeat="f in settings.fields" ng-if="inArray(f.type, listTypes) && f.id != row.id && f.masterField != row.id">{{f.label[currentLanguage]}}</option>
                            </select>
                        </td>
                    </tr>
                    <tr ng-show="row.masterField">
                        <td><?php echo $l->get('entry_display_when') ?> {{getFieldName(row.masterField)}} = </td>
                        <td ng-init="row.displayWhen = !empty(row.displayWhen) ? (isArray(row.displayWhen) ? arrayToObject(row.displayWhen) : row.displayWhen) : {};row.displayWhen = deleteUnused(row.displayWhen, getFieldValues(row.masterField))">
                            <div style="overflow-y:scroll;max-height:200px;">
                                <div ng-repeat="v in getFieldValues(row.masterField)" ng-if="v.id != ''"><label><input type="checkbox" ng-change="createScenario()" value="{{v.id}}" test="{{row.displayWhen[v.id]}}" ng-model="row.displayWhen[v.id]">{{v.text}}</label></div>
                            </div>
                            <div><a ng-click="selectAll(row.type, row.id, 'display', true)"><?php echo $l->get('text_select_all') ?></a></div>
                            <div><a ng-click="selectAll(row.type, row.id, 'display', false)"><?php echo $l->get('text_unselect_all') ?></a></div>
                        </td>
                    </tr>
                    <tr ng-show="row.type == 'field'" ng-init="row.required = !empty(row.required) ? row.required : 0;row.masterField = !empty(row.masterField) ? row.masterField : ''">
                        <td><?php echo $l->get('entry_field_required') ?></td>
                        <td>
                            <div><label><input type="radio" ng-model="row.required" value="0" ng-change="createScenario()"><?php echo $l->get('text_not_required') ?></label></div>
                            <div><label><input type="radio" ng-model="row.required" value="1" ng-change="createScenario()"><?php echo $l->get('text_required_always') ?></label></div>
                            <div>
                                <label><input type="radio" ng-model="row.required" value="2" ng-change="createScenario()"><?php echo $l->get('text_requirement_depends_on') ?></label>
                            </div>
                        </td>
                    </tr>
                    <tr ng-show="row.type == 'field' && row.required == 2 && row.masterField">
                        <td><?php echo $l->get('entry_required_when') ?> {{getFieldName(row.masterField)}} = </td>
                        <td ng-init="row.requireWhen = !empty(row.requireWhen) ? (isArray(row.requireWhen) ? arrayToObject(row.requireWhen) : row.requireWhen) : {};row.requireWhen = deleteUnused(row.requireWhen, getFieldValues(row.masterField))">
                            <div style="overflow-y:scroll;max-height:200px;">
                                <div ng-repeat="v in getFieldValues(row.masterField)" ng-if="v.id != ''"><label><input type="checkbox" ng-change="createScenario()" value="{{v.id}}" ng-model="row.requireWhen[v.id]">{{v.text}}</label></div>
                            </div>
                            <div><a ng-click="selectAll(row.type, row.id, 'require', true)"><?php echo $l->get('text_select_all') ?></a></div>
                            <div><a ng-click="selectAll(row.type, row.id, 'require', false)"><?php echo $l->get('text_unselect_all') ?></a></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <select ng-change="addRow()" ng-model="setData.row" style="margin-bottom:10px;">
            <option value=""><?php echo $l->get('text_add_new_row', true) ?></option>
            <optgroup label="<?php echo $l->get('text_fields', true) ?>">
                <option ng-repeat="field in getAvailableFields()" value="field:{{field.id}}">{{getFieldName(field.id)}}</option>
            </optgroup>
            <optgroup label="<?php echo $l->get('text_headers', true) ?>">
                <option ng-repeat="header in getAvailableHeaders()" value="header:{{header.id}}">{{getHeaderName(header.id)}}</option>
            </optgroup>
            <optgroup label="<?php echo $l->get('text_splitter_group', true) ?>">
                <option value="splitter:splitter"><?php echo $l->get('text_splitter', true) ?></option>
            </optgroup>
        </select>
        <div class="set-row"><span class="required">&nbsp;&uarr;</span> - <?php echo $l->get('text_help_uarr') ?></div>
        <div class="set-row"><span class="required">&nbsp;*</span> - <?php echo $l->get('text_help_required') ?></div>
        <div class="set-row"><span class="required-master">&nbsp;*</span> - <?php echo $l->get('text_help_required_master') ?></div>
        <div class="set-row" ng-show="setData.both"><img src="view/image/simple_autoreload.png" alt=""> - <?php echo $l->get('text_help_autoreload') ?></div>
        <div class="set-row" ng-show="setData.both"><span class="logged">&nbsp;&nbsp;&nbsp;&nbsp;</span> - <?php echo $l->get('text_help_hide_logged') ?></div>
        <div class="set-row" ng-show="setData.both"><span class="guest">&nbsp;&nbsp;&nbsp;&nbsp;</span> - <?php echo $l->get('text_help_hide_guest') ?></div>
    </div>
</script>
<script type="text/ng-template" id="modal">
  <div class="simple-modal-mask" ng-click="close()">
    <div class="simple-modal-wrapper">
      <div class="simple-modal-container">
        <div class="simple-modal-header">
          <h1>{{title}}</h1>
          <a class="btn button btn-default" ng-click="close()"><span>{{closeText}}</span></a>
        </div>
        <div class="simple-modal-body">
            <iframe ng-if="src" ng-src="{{trustSrc(src)}}" frameborder="0" style="width:100%;height:100%"></iframe>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/ng-template" id="alerts">
  <div class="simple-notify">
    <div ng-repeat="alert in alerts" class="simple-notify__alert simple-notify__alert-{{alert.type}}" >
        {{alert.text}}
    </div>
  </div>
</script>