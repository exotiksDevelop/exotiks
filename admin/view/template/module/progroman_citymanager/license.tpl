<div class="prmn-cmngr-settings__block">
  <?php if (!$valid_license) { ?>
    <p><a class="btn btn-primary btn-lg" id="get-secret-key" data-loading-text="<?= $text_loading ?>..."><?= $button_secret_key ?></a></p>
    <p><?= $error_license; ?></p>
  <?php } else { ?>
    <p class="alert alert-success">
      <?= $text_license_success ?>
    </p>
    <a class="btn btn-danger btn-lg" id="clear-secret-key" data-loading-text="<?= $text_loading ?>..."><?= $button_reset ?></a>
  <?php } ?>
</div>
<script type="text/javascript">
    $(function() {
        $('#get-secret-key').click(function () {
            var btn = $(this).button('loading');
            $.get('<?= $url_get_secret ?>', function(json) {
                alert(json.message);
                if (json.success) {
                    $('#field-secret-key').val(json.key);
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
                btn.button('reset');
            }, 'json')
        });

        $('#clear-secret-key').click(function () {
            var btn = $(this).button('loading');
            $.get('<?= $url_clear_secret ?>', function(json) {
                alert(json.message);
                if (json.success) {
                    $('#field-secret-key').val('');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
                btn.button('reset');
            }, 'json')
        });
    })
</script>