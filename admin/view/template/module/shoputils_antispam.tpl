<?php
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.2.0.x-2.1.x-2.2.x.RUS.TXT
 * Так же лицензионное соглашение можно найти по адресу:
 * https://opencart.market/LICENSE.2.0.x-2.1.x-2.2.x.RUS.TXT
 * 
 * =================================================================
 * OPENCART/ocStore 2.0.x-2.1.x-2.2.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart/ocStore 2.0.x-2.1.x-2.2.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart/ocStore, кроме Opencart/ocStore 2.0.x-2.1.x-2.2.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart/ocStore.
 * =================================================================
*/
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
      </div>
      <h1><img src="<?php echo $icon; ?>" alt="<?php echo $icon; ?>" /> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <ul class="nav nav-tabs" id="tabs_general">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-power-off"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-log" data-toggle="tab"><i class="fa fa-eye"></i> <?php echo $tab_log; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                      <label class="btn btn-success<?php echo $m_shoputils_antispam_contact_status ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_contact_status" value="1"<?php echo $m_shoputils_antispam_contact_status ? ' checked="checked"' : ''; ?> /> <?php echo $text_enabled; ?></label>
                      <label class="btn btn-danger<?php echo !$m_shoputils_antispam_contact_status ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_contact_status" value="0"<?php echo !$m_shoputils_antispam_contact_status ? ' checked="checked"' : ''; ?> /> <?php echo $text_disabled; ?></label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-registr_status"><?php echo $entry_registr_status; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                      <label class="btn btn-success<?php echo $m_shoputils_antispam_registr_status ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_registr_status" value="1"<?php echo $m_shoputils_antispam_registr_status ? ' checked="checked"' : ''; ?> /> <?php echo $text_enabled; ?></label>
                      <label class="btn btn-danger<?php echo !$m_shoputils_antispam_registr_status ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_registr_status" value="0"<?php echo !$m_shoputils_antispam_registr_status ? ' checked="checked"' : ''; ?> /> <?php echo $text_disabled; ?></label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-affiliate_status"><?php echo $entry_affiliate_status; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                      <label class="btn btn-success<?php echo $m_shoputils_antispam_affiliate_status ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_affiliate_status" value="1"<?php echo $m_shoputils_antispam_affiliate_status ? ' checked="checked"' : ''; ?> /> <?php echo $text_enabled; ?></label>
                      <label class="btn btn-danger<?php echo !$m_shoputils_antispam_affiliate_status ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_affiliate_status" value="0"<?php echo !$m_shoputils_antispam_affiliate_status ? ' checked="checked"' : ''; ?> /> <?php echo $text_disabled; ?></label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-word"><?php echo $entry_word; ?></label>
                <div class="col-sm-10">
                  <textarea name="m_shoputils_antispam_word" id="input-word" placeholder="<?php echo $entry_word; ?>" class="form-control"><?php echo $m_shoputils_antispam_word; ?></textarea>
                  <span class="help-block"><?php echo $help_word; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                <div class="col-sm-10">
                  <textarea name="m_shoputils_antispam_ip" id="input-ip" placeholder="<?php echo $entry_ip; ?>" class="form-control"><?php echo $m_shoputils_antispam_ip; ?></textarea>
                  <span class="help-block"><?php echo $help_ip; ?></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-not_found"><?php echo $entry_not_found; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                      <label class="btn btn-success<?php echo $m_shoputils_antispam_not_found ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_not_found" value="1"<?php echo $m_shoputils_antispam_not_found ? ' checked="checked"' : ''; ?> /> <?php echo $text_enabled; ?></label>
                      <label class="btn btn-danger<?php echo !$m_shoputils_antispam_not_found ? ' active' : ''; ?>"><input type="radio" name="m_shoputils_antispam_not_found" value="0"<?php echo !$m_shoputils_antispam_not_found ? ' checked="checked"' : ''; ?> /> <?php echo $text_disabled; ?></label>
                  </div>
                  <span class="help-block"><?php echo $help_not_found; ?></span>
                </div>
              </div>

            </div><!-- </div id="tab-general"> -->
            <div class="tab-pane" id="tab-log">

              <fieldset>
                <legend><?php echo $text_contact; ?></legend>

                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-log"><?php echo $entry_log; ?></label>
                  <div class="col-sm-8">
                    <input type="hidden" name="m_shoputils_antispam_contact_log_filename" value="<?php echo $log_filename; ?>" />
                    <input type="hidden" name="m_shoputils_antispam_version" value="<?php echo $version; ?>" />
                    <select name="m_shoputils_antispam_contact_log" id="input-log" class="form-control">
                      <?php foreach ($logs as $key => $value) { ?>
                      <?php if ($key == $m_shoputils_antispam_contact_log) { ?>
                      <option value="<?php echo $key; ?>"
                          selected="selected"><?php echo $value; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                    <span class="help-block"><?php echo $help_log; ?></span>
                  </div>
                  <div class="col-sm-2">
                    <a class="btn btn-success" id="button-download" href="<?php echo $download; ?>"><i class="fa fa-download"></i> <?php echo $button_download; ?></a>
                    <a class="btn btn-danger" id="button-clear" data-loading-text="<?php echo $text_loading; ?>"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_log_file; ?></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 300px; overflow: auto;">
                      <pre id="pre-log" style="font-size:11px; min-height: 280px;"><?php foreach ($log_lines as $log_line) {echo $log_line;} ?></pre>
                    </div>
                    <span class="help-block"><?php echo $help_log_file; ?></span>
                  </div>
                </div>

                <legend><?php echo $text_registr; ?></legend>

                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-registr_log"><?php echo $entry_log; ?></label>
                  <div class="col-sm-8">
                    <input type="hidden" name="m_shoputils_antispam_registr_log_filename" value="<?php echo $log_registr_filename; ?>" />
                    <select name="m_shoputils_antispam_registr_log" id="input-log" class="form-control">
                      <?php foreach ($logs as $key => $value) { ?>
                      <?php if ($key == $m_shoputils_antispam_registr_log) { ?>
                      <option value="<?php echo $key; ?>"
                          selected="selected"><?php echo $value; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                    <span class="help-block"><?php echo $help_registr_log; ?></span>
                  </div>
                  <div class="col-sm-2">
                    <a class="btn btn-success" id="button-registr_download" href="<?php echo $registr_download; ?>"><i class="fa fa-download"></i> <?php echo $button_download; ?></a>
                    <a class="btn btn-danger" id="button-registr_clear" data-loading-text="<?php echo $text_loading; ?>"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_log_file; ?></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 300px; overflow: auto;">
                      <pre id="pre-registr_log" style="font-size:11px; min-height: 280px;"><?php foreach ($log_registr_lines as $log_line) {echo $log_line;} ?></pre>
                    </div>
                    <span class="help-block"><?php echo $help_log_file; ?></span>
                  </div>
                </div>

                <legend><?php echo $text_affiliate; ?></legend>

                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-affiliate_log"><?php echo $entry_log; ?></label>
                  <div class="col-sm-8">
                    <input type="hidden" name="m_shoputils_antispam_affiliate_log_filename" value="<?php echo $log_affiliate_filename; ?>" />
                    <select name="m_shoputils_antispam_affiliate_log" id="input-log" class="form-control">
                      <?php foreach ($logs as $key => $value) { ?>
                      <?php if ($key == $m_shoputils_antispam_affiliate_log) { ?>
                      <option value="<?php echo $key; ?>"
                          selected="selected"><?php echo $value; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                    <span class="help-block"><?php echo $help_affiliate_log; ?></span>
                  </div>
                  <div class="col-sm-2">
                    <a class="btn btn-success" id="button-affiliate_download" href="<?php echo $affiliate_download; ?>"><i class="fa fa-download"></i> <?php echo $button_download; ?></a>
                    <a class="btn btn-danger" id="button-affiliate_clear" data-loading-text="<?php echo $text_loading; ?>"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_log_file; ?></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 300px; overflow: auto;">
                      <pre id="pre-affiliate_log" style="font-size:11px; min-height: 280px;"><?php foreach ($log_affiliate_lines as $log_line) {echo $log_line;} ?></pre>
                    </div>
                    <span class="help-block"><?php echo $help_log_file; ?></span>
                  </div>
                </div>

              </fieldset>

            </div><!-- </div id="tab-log"> -->
          </div><!-- </div class="tab-content"> -->
        </form>
        <div style="padding: 15px 15px; border:1px solid #ccc; margin-top: 15px; box-shadow:0 0px 5px rgba(0,0,0,0.1);"><?php echo $text_copyright; ?></div>
      </div><!-- </div class="panel-body"> -->
    </div><!-- </div class="panel panel-default"> -->
  </div><!-- </div class="container-fluid"> -->
</div><!-- </div id="content"> -->

<script type="text/javascript"><!--
  $('#button-clear').on('click', function() {
    if (confirm('<?php echo $text_confirm; ?>')){
      $.ajax({
        url: '<?php echo $clear_log; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-clear').button('loading');
        },
        complete: function() {
          $('#button-clear').button('reset');
        },
        success: function(json) {
          $('.alert-success, .alert-danger').remove();
                
          if (json['error']) {
            $('#content > .container-fluid').before('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            $('.alert-danger').fadeIn('slow');
          }
          
          if (json['success']) {
                    $('#content > .container-fluid').before('<div class="alert alert-success" style="display: none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            
            $('#pre-log').empty();
            $('.alert-success').fadeIn('slow');
          }

          $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });

  $('#button-registr_clear').on('click', function() {
    if (confirm('<?php echo $text_confirm; ?>')){
      $.ajax({
        url: '<?php echo $registr_clear_log; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-registr_clear').button('loading');
        },
        complete: function() {
          $('#button-registr_clear').button('reset');
        },
        success: function(json) {
          $('.alert-success, .alert-danger').remove();
                
          if (json['error']) {
            $('#content > .container-fluid').before('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            $('.alert-danger').fadeIn('slow');
          }
          
          if (json['success']) {
                    $('#content > .container-fluid').before('<div class="alert alert-success" style="display: none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            
            $('#pre-registr_log').empty();
            $('.alert-success').fadeIn('slow');
          }

          $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });

  $('#button-affiliate_clear').on('click', function() {
    if (confirm('<?php echo $text_confirm; ?>')){
      $.ajax({
        url: '<?php echo $affiliate_clear_log; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-affiliate_clear').button('loading');
        },
        complete: function() {
          $('#button-affiliate_clear').button('reset');
        },
        success: function(json) {
          $('.alert-success, .alert-danger').remove();
                
          if (json['error']) {
            $('#content > .container-fluid').before('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            $('.alert-danger').fadeIn('slow');
          }
          
          if (json['success']) {
                    $('#content > .container-fluid').before('<div class="alert alert-success" style="display: none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            
            $('#pre-affiliate_log').empty();
            $('.alert-success').fadeIn('slow');
          }

          $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });

  $(function() { 
      $('a[href^=\'#tab\']').on('shown.bs.tab', function () {
          localStorage.setItem('antispam_lastTab', $(this).attr('href'));
      });

      var lastTab = localStorage.getItem('antispam_lastTab');
 
      if (lastTab) {
          $('a[href=\'' + lastTab + '\']').tab('show');
      } else {
          $('#tabs_general a:first').tab('show');
      }
  });
//--></script>
<?php echo $footer; ?>