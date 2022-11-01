<div class="for-general-form">
  <div class="form-group">
    <label class="control-label">
      <input name="setting[enable_switch_messages]" value="1" type="checkbox"
        <?= !empty($settings['enable_switch_messages']) ? ' checked="checked"' : ''; ?>">
        <?= $entry_sub_enabled; ?>
    </label>
  </div>
</div>
<table id="messages" class="table table-striped table-bordered">
  <thead>
  <tr>
    <th>
      <div class="row">
        <div class="col-sm-3 col-xs-12">
            <?= $entry_key; ?>
        </div>
        <div class="col-sm-3 col-xs-12">
            <?= $entry_zone; ?>
        </div>
        <div class="col-sm-4 col-xs-12">
            <?= $entry_value; ?>
        </div>
      </div>
    </th>
  </tr>
  </thead>
  <tbody>
  <?php $message_row = 0; ?>
  <?php foreach ($messages as $message) { ?>
    <tr id="message-row<?= $message_row; ?>">
      <td>
        <div class="row">
          <div class="col-sm-3 col-xs-12">
            <input type="text" class="form-control" name="key" value="<?= $message['key']; ?>"/>
          </div>
          <div class="col-sm-3 col-xs-12">
            <input type="text" name="" value="<?= $message['fias_name']; ?>" class="row-fias-name form-control"/>
            <input type="hidden" name="fias_id" value="<?= $message['fias_id']; ?>" class="row-fias-id"/>
            <input type="hidden" name="id" value="<?= $message['id']; ?>"/>
          </div>
          <div class="col-sm-4 col-xs-12">
            <textarea class="form-control" name="value"><?= $message['value']; ?></textarea>
          </div>
          <div class="col-sm-2 col-xs-12">
            <a class="btn btn-primary save-message" data-toggle="tooltip" title="<?= $button_save; ?>" onclick="saveMessage(<?= $message_row; ?>)">
              <i class="fa fa-save"></i>
            </a>
            <a class="btn btn-danger remove-message" data-toggle="tooltip" title="<?= $button_remove; ?>"
               onclick="removeMessage(<?= $message_row; ?>)">
              <i class="fa fa-remove"></i>
            </a>
          </div>
        </div>
      </td>
    </tr>
      <?php $message_row++; ?>
  <?php } ?>
  </tbody>
  <tfoot>
  <tr>
    <th>
      <a class="btn btn-success" onclick="addMessage();"> <?= $button_add; ?></a>
    </th>
  </tr>
  </tfoot>
</table>
<div class="row">
  <div class="col-sm-6 text-left"><?= $pagination; ?></div>
  <div class="col-sm-6 text-right"><?= $results; ?></div>
</div>

<script type="text/javascript">
    var message_row = <?= $message_row; ?>;

    function addMessage() {
        var html = '<tr id="message-row' + message_row + '"><td><div class="row"><div class="col-sm-3 col-xs-12">';
        html += '<input type="text" name="key" class="form-control"/>';
        html += '</div><div class="col-sm-3 col-xs-12">';
        html += '<input type="text" name="" class="row-fias-name form-control"/>';
        html += '<input type="hidden" name="fias_id" class="row-fias-id"/>';
        html += '<input type="hidden" name="id" value=""/>';
        html += '</div><div class="col-sm-4 col-xs-12">';
        html += '<textarea class="form-control" name="value"></textarea>';
        html += '</div><div class="col-sm-2 col-xs-12">';
        html += '<a class="btn btn-primary save-message" data-toggle="tooltip" title="<?= $button_save; ?>" onclick="saveMessage(' + message_row + ')"><i class="fa fa-save"></i></a>';
        html += ' <a class="btn btn-danger remove-message" data-toggle="tooltip" title="<?= $button_remove; ?>" onclick="removeMessage(' + message_row + ')"><i class="fa fa-remove"></i></a>';
        html += '</div></div></td></tr>';

        $('#messages').find('tbody').append(html);
        $('#message-row' + message_row).find('[data-toggle="tooltip"]').tooltip({container: 'body', html: true});

        message_row++;
    }

    function saveMessage(row) {
        var container = $('#message-row' + row);
        var btn = container.find('.save-message').attr('disabled', 'disabled');
        container.find('.text-danger').remove();

        $.ajax({
            url: '<?= $action_savemessage ?>',
            type: 'post',
            dataType: 'json',
            data: container.find(':input').serialize(),
            success: function(json) {
                if (json.errors) {
                    if (json.errors.key) {
                        container.find('input[name="key"]').after('<p class="text-danger">' + json.errors.key + '</p>');
                    }

                    if (json.errors.fias) {
                        container.find('.row-fias-name').after('<p class="text-danger">' + json.errors.fias + '</p>');
                    }
                }

                btn.removeAttr('disabled');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function removeMessage(row) {
        var container = $('#message-row' + row);
        container.find('.remove-message').attr('disabled', 'disabled');

        $.ajax({
            url: '<?= $action_removemessage ?>',
            type: 'post',
            dataType: 'json',
            data: container.find(':input').serialize(),
            success: function(json) {
                container.remove();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    $('#tab-messages').find('.pagination a').click(function() {
        $('#tab-messages').load($(this).attr('href'));
        return false;
    });
</script>