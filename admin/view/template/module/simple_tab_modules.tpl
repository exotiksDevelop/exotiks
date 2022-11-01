<table id="module" class="list">
  <thead>
    <tr>
      <td class="left"><?php echo $l->get('entry_module_page'); ?></td>
      <td class="left"><?php echo $l->get('entry_layout'); ?></td>
      <td class="left"><?php echo $l->get('entry_position'); ?></td>
      <td class="left"><?php echo $l->get('entry_status'); ?></td>
      <td class="right"><?php echo $l->get('entry_sort_order'); ?></td>
      <td></td>
    </tr>
  </thead>
  <tbody ng-init="settings.modules = !empty(settings.modules) ? settings.modules : []">
    <tr ng-repeat="module in settings.modules">
      <td class="left">
        <div>
          <select ng-model="module.page" ng-init="module.page = !empty(module.page) ? module.page : 'checkout' ">
            <option value="checkout" ng-selected="module.page == 'checkout'"><?php echo $l->get('text_module_simplecheckout') ?></option>
            <option value="register" ng-selected="module.page == 'register'"><?php echo $l->get('text_module_simpleregister') ?></option>
          </select>
        </div>
        <div ng-show="module.page == 'checkout'" style="margin-top:5px;">
          <div><?php echo $l->get('entry_select_settings_group') ?></div>
          <select ng-model="module.settingsId" ng-init="module.settingsId = 0" ng-options="group.settingsId as group.settingsId for group in settings.checkout">
          </select>
        </div>
      </td>
      <td class="left">
        <div ng-init="module.layout_id = isset(module.layout_id) ? module.layout_id : 0">
          <select ng-model="module.layout_id" ng-options="layout.layout_id as layout.name for layout in layouts">
          </select>
        </div>
        <div style="margin-top:5px;">
          <label><input type="checkbox" ng-model="module.scripts"><?php echo $l->get('entry_scripts_only') ?></label>
        </div>
      </td>
      <td class="left">
        <select ng-model="module.position" ng-init="module.position = !empty(module.position) ? module.position : 'column_right'">
          <option value="content_top" ng-selected="module.position == 'content_top'"><?php echo $l->get('text_content_top'); ?></option>
          <option value="content_bottom" ng-selected="module.position == 'content_bottom'"><?php echo $l->get('text_content_bottom'); ?></option>
          <option value="column_left" ng-selected="module.position == 'column_left'"><?php echo $l->get('text_column_left'); ?></option>
          <option value="column_right" ng-selected="module.position == 'column_right'"><?php echo $l->get('text_column_right'); ?></option>
        </select>
      </td>
      <td class="left">
        <select ng-model="module.status" ng-init="module.status = isset(module.status) ? module.status : 0">
          <option value="1" ng-selected="module.status == 1"><?php echo $l->get('text_enabled'); ?></option>
          <option value="0" ng-selected="module.status == 0"><?php echo $l->get('text_disabled'); ?></option>
        </select>
      </td>
      <td class="right"><input type="text" ng-init="module.sort_order = isset(module.sort_order) ? module.sort_order : 0" ng-model="module.sort_order" size="3"></td>
      <td class="left"><a ng-click="settings.modules.splice($index, 1)" class="button btn btn-primary"><?php echo $l->get('button_remove'); ?></a></td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5"></td>
      <td class="left"><a ng-click="settings.modules.push({})" class="button btn btn-primary"><?php echo $l->get('button_add_module'); ?></a></td>
    </tr>
  </tfoot>
</table>