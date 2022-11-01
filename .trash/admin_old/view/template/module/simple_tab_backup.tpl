<div>
  <vtabs>
    <vtab title="<?php echo $l->get('tab_save', true) ?>" title-lang-id="tab_save">
      <h3><?php echo $l->get('text_backup') ?></h3>
      <table class="form">
        <tr>
          <td>
            <a class="button btn btn-primary" target="_blank" ng-href="<?php echo $action_backup ?>&{{$root.settings.additionalParams}}"><?php echo $l->get('button_download') ?></a>
          </td>
        </tr>
      </table>
    </vtab>
    <vtab title="<?php echo $l->get('tab_restore', true) ?>" title-lang-id="tab_restore">
      <h3><?php echo $l->get('text_restore') ?></h3>
      <table class="form">
        <tr>
          <td><input type="file" name="import" />&nbsp;<a onclick="jQuery('#form').submit();" class="button btn btn-primary"><?php echo $l->get('button_restore') ?></a></td>
        </tr>
      </table>
    </vtab>
  </vtabs>
</div>