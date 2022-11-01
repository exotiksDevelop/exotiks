<div ng-controller="simpleAddressFormatsController" ng-init="settings.addressFormats = !empty(settings.addressFormats) ? settings.addressFormats : {};addressFormats = settings.addressFormats;settings.addressFormatsShipping = !empty(settings.addressFormatsShipping) ? settings.addressFormatsShipping : {};addressFormatsShipping = settings.addressFormatsShipping">
  <table class="form">
    <tbody>
      <tr>
        <td>
          <h3 style="margin:0"><?php echo $l->get('text_groups') ?></h3>
        </td>
        <td ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;{{l.name}}</td>
      </tr>
      <tr ng-repeat="group in groups">
        <td ng-init="addressFormats[group.customer_group_id] = !empty(addressFormats[group.customer_group_id]) ? addressFormats[group.customer_group_id] : {}">
          <div style="margin-bottom:5px;font-weight:bold;">{{group.name}}</div>
          <?php echo $entry_address_format ?>
          <div class="help" ng-repeat="field in settings.fields" ng-if="field.custom">{{field.label[currentLanguage]}} = <span>&#123;</span>{{field.id}}<span>&#125;</span></div>
        </td>
        <td ng-repeat="l in languages">
          <textarea style="width:100%" rows="15" placeholder="<?php echo $l->get('text_help_address_formats', true) ?>" ng-model="addressFormats[group.customer_group_id][l.code]"></textarea>
        </td>
      </tr>
    </tbody>
    <tbody>
      <tr>
        <td>
          <h3 style="margin:0"><?php echo $l->get('text_shipping_methods') ?></h3>
        </td>
        <td ng-repeat="l in languages"><img ng-src="view/image/flags/{{l.image}}" ng-if="l.image"/><span ng-if="empty(l.image)">{{l.name}}</span>&nbsp;{{l.name}}</td>
      </tr>
      <tr ng-repeat="method in shippingMethods">
        <td ng-init="addressFormatsShipping[method.code] = !empty(addressFormatsShipping[method.code]) ? addressFormatsShipping[method.code] : {}">
          <div style="margin-bottom:5px;font-weight:bold;">{{method.title[currentLanguage]}} ({{method.code}})</div>
          <?php echo $entry_address_format ?>
          <div class="help" ng-repeat="field in settings.fields" ng-if="field.custom">{{field.label[currentLanguage]}} = <span>&#123;</span>{{field.id}}<span>&#125;</span></div>
        </td>
        <td ng-repeat="l in languages">
          <textarea style="width:100%" rows="15" placeholder="<?php echo $l->get('text_help_address_formats', true) ?>" ng-model="addressFormatsShipping[method.code][l.code]"></textarea>
        </td>
      </tr>
    </tbody>
  </table>
</div>