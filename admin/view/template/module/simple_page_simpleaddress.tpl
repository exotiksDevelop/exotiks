<vtab title="<?php echo $l->get('tab_simpleaddress', true) ?>" title-lang-id="tab_simpleaddress" ng-init="settings.address = !empty(settings.address) ? settings.address : {}">
    <table class="form">
        <tr>
            <td><?php echo $l->get('entry_replace_address') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.replaceAddress">
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_use_geoip'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.address.useGeoIp">
            </td>    
        </tr>
        <tr ng-show="settings.address.useGeoIp">
            <td>
                <?php echo $l->get('entry_geoip_mode'); ?>
            </td>
            <td ng-init="settings.address.geoIpMode = isset(settings.address.geoIpMode) ? settings.address.geoIpMode : 1">
                <select ng-model="settings.address.geoIpMode">
                    <option value="1" ng-selected="settings.address.geoIpMode == 1"><?php echo $l->get('text_geoip_mode_own') ?></option>
                    <option value="2" ng-selected="settings.address.geoIpMode == 2"><?php echo $l->get('text_geoip_mode_maxmind_extension') ?></option>
                    <option value="3" ng-selected="settings.address.geoIpMode == 3"><?php echo $l->get('text_geoip_mode_maxmind_table') ?></option>
                </select>
            </td>    
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_use_autocomplete'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.address.useAutocomplete">
            </td>    
        </tr>
        <tr>
            <td>
                <?php echo $l->get('entry_use_googleapi'); ?>
            </td>
            <td>
                <input type="checkbox" ng-model="settings.address.useGoogleApi">
            </td>    
        </tr> 
        <tr>
            <td><?php echo $l->get('entry_scroll_to_error') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.address.scrollToError">
            </td>
        </tr>   
    </table>
    <table class="form" ng-controller="simpleSetController">
        <tr>
            <td ng-init="settings.address.rows = !empty(settings.address.rows) ? settings.address.rows : {};setData.rows=settings.address.rows;setData.filterForObjects=['address'];sortAllRows();">
                <rows></rows>
            </td>
        </tr>
    </table>
</vtab>