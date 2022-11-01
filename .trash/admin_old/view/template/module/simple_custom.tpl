<?php if (count($custom)) { ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-ui-timepicker-addon.js"></script>
<h2>Simple Data</h2>
<style>
    .container-fluid table.simple-custom-form {
        width: 100%;
    }
    .container-fluid table.simple-custom-form td {
        padding: 4px;
    }
    .container-fluid table.simple-custom-form td:first-child {
        padding-right: 25px;
        text-align: right;
    }
    .container-fluid table.simple-custom-form td+td {
        width: 83.2%;
    }
</style>
<form action="<?php echo $action ?>" id="<?php echo $form_id ?>" method="POST">
<table class="form simple-custom-form">
<?php foreach ($custom as $id => $field) { ?>
  <tr>
    <td><?php echo $field['label']; ?></td>
    <td>
        <?php if (!empty($field['type'])) { ?>
            <?php if ($field['type'] == 'text' || $field['type'] == 'date' || $field['type'] == 'time' || $field['type'] == 'tel' || $field['type'] == 'phone' || $field['type'] == 'email') { ?>
                <input type="text" name="<?php echo $field['id'] ?>" value="<?php echo $field['value'] ?>" class="form-control <?php echo $field['type'] == 'date' ? 'datepicker' : '' ?> <?php echo $field['type'] == 'time' ? 'timepicker' : '' ?>">
            <?php } ?>
            <?php if ($field['type'] == 'textarea') { ?>
                <textarea name="<?php echo $field['id'] ?>"><?php echo $field['value'] ?></textarea>
            <?php } ?>
            <?php if ($field['type'] == 'select' || $field['type'] == 'select_from_api') { ?>
                <?php if (is_array($field['values'])) { ?>
                    <select name="<?php echo $field['id'] ?>">
                        <?php foreach ($field['values'] as $key => $value) { ?>
                            <option value="<?php echo trim($key) ?>" <?php echo trim($key) == $field['value'] ? ' selected="selected"' : ''?>><?php echo $value ?></option>
                        <?php } ?>
                    </select>
                <?php } else { ?>
                        <div class="simple-values" data-name="<?php echo $field['id'] ?>" data-source="<?php echo $field['values'] ?>" data-type="select" data-value="<?php echo $field['value'] ?>"></div>
                <?php } ?>
            <?php } ?>
            <?php if ($field['type'] == 'radio' || $field['type'] == 'radio_from_api') { ?>
                <?php if (is_array($field['values'])) { ?>
                    <?php foreach ($field['values'] as $key => $value) { ?>
                        <label><input type="radio" name="<?php echo $field['id'] ?>" value="<?php echo trim($key) ?>" <?php echo trim($key) == $field['value'] ? ' checked="checked"' : ''?>>&nbsp;<?php echo $value ?></label><br>
                    <?php } ?>
                <?php } else { ?>
                    <div class="simple-values" data-name="<?php echo $field['id'] ?>" data-source="<?php echo $field['values'] ?>" data-type="radio" data-value="<?php echo $field['value'] ?>"></div>
                <?php } ?>
            <?php } ?>
            <?php if ($field['type'] == 'checkbox' || $field['type'] == 'checkbox_from_api') { ?>
                <?php if (is_array($field['values'])) { ?>
                    <?php foreach ($field['values'] as $key => $value) { ?>
                        <input type="hidden" name="<?php echo $field['id'] ?>[]" value="">
                        <label><input type="checkbox" name="<?php echo $field['id'] ?>[]" value="<?php echo trim($key) ?>" <?php echo is_array($field['value']) && in_array(trim($key), $field['value']) ? ' checked="checked"' : ''?>>&nbsp;<?php echo $value ?></label><br>
                    <?php } ?>
                <?php } else { ?>
                    <div class="simple-values" data-name="<?php echo $field['id'] ?>" data-source="<?php echo $field['values'] ?>" data-type="checkbox" data-value="<?php echo implode(',', $field['value']) ?>"></div>
                <?php } ?>
            <?php } ?>
            <?php if ($field['type'] == 'file') { ?>
                <?php $name = basename(utf8_substr($field['value'], 0, utf8_strrpos($field['value'], '.'))); $name = $name ? $name : basename($field['value']) ?>
                <a href="<?php echo $download.$field['value'] ?>"><?php echo $name ?></a>
            <?php } ?>
            <?php if ($field['type'] == 'hidden') { ?>
                <input type="hidden" name="<?php echo $field['id'] ?>" value="<?php echo $field['value'] ?>">
            <?php } ?>
        <?php } else { ?>
            <?php echo $field['value'] ?>
        <?php } ?>
    </td>
  </tr>
<?php } ?>
<?php if (count($custom)) { ?>
  <tr>
    <td></td>
    <td><a class="button btn btn-primary" onclick="submit_<?php echo $form_id ?>();return false;"><?php echo $button_save ?></a></td>
  </tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
(function($) {
    function loadValues($target) {
        var tmp = $target.attr("data-source").split("|");
        var method = tmp[0];
        var filter = tmp[1];
        var type = $target.attr("data-type");
        var value = $target.attr("data-value");
        var name = $target.attr("data-name");

        if (type == "checkbox") {
            value = value.split(",");
        }

        $.getJSON("<?php echo $store_url ?>index.php?route=common/simple_connector&method=" + method + "&filter=" + filter + "&custom=1", function(json) {
            html = '';
            if (json) {
                if (type == "select") {
                    html += "<select name='" + name + "'>";
                    for (var i = 0; i < json.length; i++) {
                        html += "<option value='" + json[i].id + "' " + (json[i].id == value ? "selected='selected'" : "") + ">" + json[i].text + "</option>";
                    }
                    html += "</select>";
                } else if (type == "radio") {
                    for (var i = 0; i < json.length; i++) {
                        html += "<label><input type='radio' name='" + name + "' value='" + json[i].id + "' " + (json[i].id == value ? "checked='checked'" : "") + ">" + json[i].text + "</label><br>";
                    }
                } else if (type == "checkbox") {
                    for (var i = 0; i < json.length; i++) {
                        html += "<input type='hidden' name='" + name + "[]' value=''><label><input type='checkbox' name='" + name + "[]' value='" + json[i].id + "' " + (value.indexOf(json[i].id) >= 0 ? "checked='checked'" : "") + ">" + json[i].text + "</label><br>";
                    }
                }
            }
            $target.html(html);
        });
    }
    window.submit_<?php echo $form_id ?> = function() {
        var data = $('#<?php echo $form_id ?>').find('input,select,textarea').serialize();

        $.ajax({
            url: '<?php echo htmlspecialchars_decode($action) ?>',
            data: data,
            type: 'POST',
            dataType: 'text',
            beforeSend: function() {
                $('#<?php echo $form_id ?> a.button').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
            },
            success: function(data) {
                $('#<?php echo $form_id ?>').parents('.simple-container').html(data);
                $('#<?php echo $form_id ?> span.wait').remove();
                $('#<?php echo $form_id ?>').find('.simple-values').each(function(){
                    loadValues($(this));
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $('#<?php echo $form_id ?> span.wait').remove();
            }
        });
    }
    $(function(){
        if (typeof $('.simple-custom-form .datepicker').datepicker !== 'undefined') {
            $('.simple-custom-form .datepicker').datepicker();
        } else if (typeof $('.simple-custom-form .datepicker').datetimepicker !== 'undefined') {
            $('.simple-custom-form .datepicker').datetimepicker({
                pickDate: true,
                pickTime: false,
            });
        }

        if (typeof $('.simple-custom-form .timepicker').timepicker !== 'undefined') {
            $('.simple-custom-form .timepicker').timepicker();
        } else if (typeof $('.simple-custom-form .timepicker').datetimepicker !== 'undefined') {
            $('.simple-custom-form .timepicker').datetimepicker({
                pickDate: false,
                pickTime: true,
            });
        }

        $('#<?php echo $form_id ?>').find('.simple-values').each(function(){
            loadValues($(this));
        });
    });
})(jQuery);
</script>
<?php } ?>