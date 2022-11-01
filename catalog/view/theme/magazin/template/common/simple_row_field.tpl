<div class="form-group <?php if ($required) { ?>required<?php } ?> row-<?php echo $id ?>">
    <label class="control-label <?php echo $page == 'checkout' ? 'col-sm-4' : 'col-sm-2' ?>" for="<?php echo $id ?>"><?php echo $label ?></label>
    <div class="<?php echo $page == 'checkout' ? 'col-sm-8' : 'col-sm-10' ?>">
      <?php if ($type == 'select' || $type == 'select2') { ?>
        <select class="form-control" name="<?php echo $name ?>" id="<?php echo $id ?>" <?php echo $bootstrap ? 'data-theme="bootstrap"' : '' ?> <?php echo $type == 'select2' ? 'data-type="select2"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
          <?php foreach ($values as $info) { ?>
            <option value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'selected="selected"' : '' ?>><?php echo $info['text'] ?></option>
          <?php } ?>
        </select>
      <?php } elseif ($type == 'radio') { ?>
        <div>
        <?php foreach ($values as $info) { ?>
          <div class="radio">
            <label><input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $info['id'] ?>" <?php echo $value == $info['id'] ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $info['text'] ?></label>
          </div>
        <?php } ?>
        </div>
      <?php } elseif ($type == 'checkbox') { ?>
        <div>
        <?php foreach ($values as $info) { ?>
          <div class="checkbox">
            <input type="hidden" name="<?php echo $name ?>[<?php echo $info['id'] ?>]" value="0">
            <label><input type="checkbox" name="<?php echo $name ?>[<?php echo $info['id'] ?>]" id="<?php echo $id ?>" value="1" <?php echo !empty($value[$info['id']]) ? 'checked="checked"' : '' ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $info['text'] ?></label>
          </div>
        <?php } ?>
        </div>
      <?php } elseif ($type == 'textarea') { ?>
        <textarea class="form-control" name="<?php echo $name ?>" id="<?php echo $id ?>" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>><?php echo $value ?></textarea>
      <?php } elseif ($type == 'captcha') { ?>
        <?php if ($site_key) { ?>
          <script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>
          <input type="hidden" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>">
          <script type="text/javascript">function recaptchaCallback(value){$('#<?php echo $id ?>').val(value).trigger('change')}</script>
          <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" data-callback="recaptchaCallback"></div>
        <?php } else { ?>
          <input type="text" class="form-control" name="<?php echo $name ?>" id="<?php echo $id ?>" value="" placeholder="<?php echo $placeholder ?>" <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
          <div class="simple-captcha-container"><img src="index.php?<?php echo $additional_path ?>route=common/simple_connector/captcha&t=<?php echo time() ?>" alt="" id="captcha" /></div>
        <?php } ?>
      <?php } elseif ($type == 'file') { ?>
        <input type="button" value="<?php echo $button_upload; ?>" data-file="<?php echo $id ?>" class="button form-control">
        <div id="text_<?php echo $id ?>" style="margin-top:3px;max-width:200px;"><?php echo $filename ?></div>
        <input type="hidden" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>">
      <?php } elseif ($type == 'date') { ?>
        <div class="input-group date">
          <input class="form-control" type="text" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>" <?php echo $attrs ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
          <span class="input-group-btn">
            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span>
        </div>
      <?php } elseif ($type == 'time') { ?>
      <div class="input-group time">
        <input class="form-control" type="text" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>" <?php echo $attrs ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
        <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-clock-o"></i></button>
        </span>
      </div>
      <?php } else { ?>
        <input  class="form-control" type="<?php echo $type ?>" name="<?php echo $name ?>" id="<?php echo $id ?>" value="<?php echo $value ?>" placeholder="<?php echo $placeholder ?>" <?php echo $attrs ?> <?php echo $reload ? 'data-onchange="reloadAll"' : 'data-reload-payment-form="true"'?>>
      <?php } ?>
      <?php if (!empty($rules)) { ?>
        <div class="simplecheckout-rule-group" data-for="<?php echo $id ?>">
          <?php foreach ($rules as $rule) { ?>
            <div <?php echo $rule['display'] && !$rule['passed'] ? '' : 'style="display:none;"' ?> data-for="<?php echo $id ?>" data-rule="<?php echo $rule['id'] ?>" class="simplecheckout-error-text simplecheckout-rule" <?php echo $rule['attrs'] ?>><?php echo $rule['text'] ?></div>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if ($description) { ?>
        <div class="simplecheckout-tooltip" data-for="<?php echo $id ?>"><?php echo $description ?></div>
      <?php } ?>
    </div>
 </div>