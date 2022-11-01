<vtab title="<?php echo $l->get('tab_simpleregister', true) ?>" title-lang-id="tab_simpleregister" ng-init="settings.register = !empty(settings.register) ? settings.register : {}">
    <table class="form">
        <tr>
            <td><?php echo $l->get('entry_replace_register') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.replaceRegister">
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_display_agreement_checkbox'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.register.displayAgreementCheckbox">
            </td>    
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_display_agreement_checkbox_init'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.register.agreementCheckboxInit">
            </td>    
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_agreement_id'); ?>
            </td>
            <td ng-init="settings.register.agreementId = !empty(settings.register.agreementId) ? settings.register.agreementId : 0">
                <select ng-model="settings.register.agreementId" ng-options="info.id as info.title for info in informationPages">
                </select>
            </td>    
        </tr>  
        <tr>
            <td>
                <?php echo $l->get('entry_use_geoip'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.register.useGeoIp">
            </td>    
        </tr>
        <tr ng-show="settings.register.useGeoIp">
            <td>
                <?php echo $l->get('entry_geoip_mode'); ?>
            </td>
            <td ng-init="settings.register.geoIpMode = isset(settings.register.geoIpMode) ? settings.register.geoIpMode : 1">
                <select ng-model="settings.register.geoIpMode">
                    <option value="1" ng-selected="settings.register.geoIpMode == 1"><?php echo $l->get('text_geoip_mode_own') ?></option>
                    <option value="2" ng-selected="settings.register.geoIpMode == 2"><?php echo $l->get('text_geoip_mode_maxmind_extension') ?></option>
                    <option value="3" ng-selected="settings.register.geoIpMode == 3"><?php echo $l->get('text_geoip_mode_maxmind_table') ?></option>
                </select>
            </td>    
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_use_autocomplete'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.register.useAutocomplete">
            </td>    
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_use_googleapi'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.register.useGoogleApi">
            </td>    
        </tr>  
        <tr>
            <td><?php echo $l->get('entry_scroll_to_error') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.register.scrollToError">
            </td>
        </tr>                 
    </table>
    <table class="form" ng-controller="simpleSetController">
        <tr>
            <td ng-init="settings.register.rows = !empty(settings.register.rows) ? settings.register.rows : {};setData.rows=settings.register.rows;setData.filterForObjects=['customer', 'address'];sortAllRows();">
                <rows></rows>
            </td>
        </tr>
    </table>
</vtab>