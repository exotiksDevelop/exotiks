<div>
  <vtabs>
    <vtab title="<?php echo $l->get('tab_joomla', true) ?>" title-lang-id="tab_joomla">
      <table class="form">
        <tr>
          <td>
            <?php echo $l->get('text_additional_path') ?>
          </td>
          <td>
            <input type="text" size="70" ng-model="settings.additionalPath">
            <span class="help">/components/com_aceshop/opencart/</span>
            <span class="help">/components/com_mijoshop/opencart/</span>
          </td>
        </tr>
        <tr>
          <td>
            <?php echo $l->get('text_additional_params') ?>
          </td>
          <td>
            <input type="text" size="70" ng-model="settings.additionalParams">
            <span class="help">option=com_aceshop&tmpl=component&format=raw&</span>
            <span class="help">option=com_mijoshop&tmpl=component&format=raw&</span>
          </td>
        </tr>
      </table>
    </vtab>
    <vtab title="<?php echo $l->get('tab_theme', true) ?>" title-lang-id="tab_theme">
      <table class="form">
        <tr>
            <td><?php echo $l->get('entry_styles_path') ?></td>
            <td>
                <?php echo $styles_path ?>
            </td>
        </tr>
        <tr>
          <td colspan="2">
            <h4><?php echo $l->get('text_template_2') ?> <?php echo $header_path ?></h4>
            <textarea cols="120" rows="20"><?php echo $header_template ?></textarea><br><br>
            <a class="button btn btn-primary" target="_blank" href="<?php echo $header_save_link ?>"><?php echo $l->get('button_download') ?></a>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <h4><?php echo $l->get('text_template_3') ?> <?php echo $footer_path ?></h4>
            <textarea cols="120" rows="20"><?php echo $footer_template ?></textarea><br><br>
            <a class="button btn btn-primary" target="_blank" href="<?php echo $footer_save_link ?>"><?php echo $l->get('button_download') ?></a>
          </td>
        </tr>
      </table>
    </vtab>
    <vtab title="<?php echo $l->get('tab_javascript', true) ?>" title-lang-id="tab_javascript">
      <table class="form">
        <tr>
            <td><?php echo $l->get('entry_colorbox') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.colorbox">
            </td>
        </tr>
        <tr>
            <td><?php echo $l->get('entry_minify') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.minify">
            </td>
        </tr>
        <tr>
            <td><?php echo $l->get('entry_disable_jquery_ui') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.disableJQueryUI">
            </td>
        </tr>
        <tr>
          <td><?php echo $l->get('entry_javascript_callback') ?></td>
          <td>
            <textarea rows="10" cols="100" ng-model="settings.javascriptCallback"></textarea>
          </td>
        </tr>
      </table>
    </vtab>
    <vtab title="<?php echo $l->get('tab_google', true) ?>" title-lang-id="tab_google">
      <table class="form">
        <tr>
            <td><?php echo $l->get('entry_googleapi_enabled') ?></td>
            <td>
                <input type="checkbox" ng-model="settings.googleApiEnabled">
            </td>
        </tr>
        <tr>
          <td><?php echo $l->get('entry_googleapi_key') ?></td>
          <td>
            <input ng-model="settings.googleApiKey" size="50">
          </td>
        </tr>
      </table>
    </vtab>
  </vtabs>
</div>