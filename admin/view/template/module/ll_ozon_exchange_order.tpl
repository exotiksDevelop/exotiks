<?php 
/**
 * @author    p0v1n0m <support@lutylab.ru>
 * @license   Commercial
 * @link      https://lutylab.ru
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a onclick="$('#form').attr('action', '<?php echo $send; ?>'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_create; ?>" class="btn btn-success disabled" id="button-send"><i class="fa fa-plus"></i></a>
        <a onclick="$('#form').attr('action', '<?php echo $label; ?>').attr('target', '_blank'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_label; ?>" class="btn btn-primary disabled" id="button-label"><i class="fa fa-sticky-note-o"></i></a>
        <a onclick="$('#form').attr('action', '<?php echo $print; ?>').attr('target', '_blank'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_print; ?>" class="btn btn-primary disabled" id="button-print"><i class="fa fa-print"></i></a>
        <a onclick="$('#form').attr('action', '<?php echo $dropoff; ?>'); $('#form').submit();" class="btn btn-success disabled" id="button-dropoff"><?php echo $button_dropoff; ?></a>
        <button type="button" class="btn btn-success disabled" id="button-pickup" data-toggle="modal" data-target="#form_request"><?php echo $button_pickup; ?></button>
        <a onclick="$('#form').attr('action', '<?php echo $update; ?>'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-info disabled" id="button-update"><i class="fa fa-refresh"></i></a>
        <a href="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping; ?>" class="btn btn-default"><i class="fa fa-truck"></i></a>
        <a href="<?php echo $exchange; ?>" data-toggle="tooltip" title="<?php echo $button_exchange; ?>" class="btn btn-default"><i class="fa fa-exchange"></i></a>
        <a data-toggle="tooltip" title="<?php echo $button_order; ?>" class="btn btn-default active" disabled><i class="fa fa-shopping-cart"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title_order; ?></h1>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="well well-sm">
          <div class="row">
            <div class="col-sm-3">
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_id; ?></div>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" class="form-control" />
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_logistic; ?></div>
                <input type="text" name="filter_logisticOrderNumber" value="<?php echo $filter_logisticOrderNumber; ?>" class="form-control" />
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_shipment; ?></div>
                <input type="text" name="filter_shipment_id" value="<?php echo $filter_shipment_id; ?>" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_to; ?></div>
                <input type="text" name="filter_to_name" value="<?php echo $filter_to_name; ?>" class="form-control" />
                <input type="hidden" name="filter_to" value="<?php echo $filter_to; ?>" class="form-control" />
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-addon"><?php echo $entry_pvz; ?></div>
                <input type="text" name="filter_pvz" value="<?php echo $filter_pvz; ?>" class="form-control" />
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-addon"><?php echo $entry_customer; ?></div>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_tariff; ?></div>
                <select name="filter_tariff" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($variants as $variant) { ?>
                  <option value="<?php echo $variant['code']; ?>" <?php if ($variant['code'] == $filter_tariff) { ?>selected="selected"<?php } ?>><?php echo $variant['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_order_status; ?></div>
                <select name="filter_order_status" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>selected="selected"<?php } ?>><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_status; ?></div>
                <select name="filter_delivery_status" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($statuses as $status_id => $status) { ?>
                  <option value="<?php echo $status_id; ?>" <?php if ($status_id == $filter_delivery_status && is_numeric($filter_delivery_status)) { ?>selected="selected"<?php } ?>><?php echo $status['title']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="input-group">
                <div class="input-group-addon"><?php echo $column_total; ?></div>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" class="form-control" />
              </div>
              <br>
              <div class="input-group date">
                <div class="input-group-addon"><?php echo $column_date; ?></div>
                <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" class="form-control" />
                <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-6">
                  <a href="<?php echo $orderr; ?>" class="btn btn-warning btn-block"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
                </div>
                <div class="col-sm-6">
                  <button type="button" id="button-filter" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if ($orders) { ?>
        <form method="post" action="" target="" enctype="multipart/form-data" id="form">
          <div class="modal fade" id="form_request" tabindex="-1" role="dialog" aria-labelledby="form_request_label">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="form_request_label">Забор</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <div class="input-group date">
                      <div class="input-group-addon">Дата забора</div>
                      <input type="text" name="pickup_date" value="<?php echo $pickup_date; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                      <span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
                    </div>
                    <div class="input-group time">
                      <div class="input-group-addon">с</div>
                      <input type="text" name="pickup_from" value="<?php echo $pickup_from; ?>" class="form-control" />
                      <span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
                    </div>
                    <div class="input-group time">
                      <div class="input-group-addon">до</div>
                      <input type="text" name="pickup_to" value="<?php echo $pickup_to; ?>" class="form-control" />
                      <span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
                    </div>
                    <div class="input-group">
                      <div class="input-group-addon">Склад забора</div>
                      <select name="pickup_sklad" class="form-control">
                        <?php if ($pickups && !empty($pickups)) { ?>
                        <?php foreach ($pickups as $p) { ?>
                        <option value="<?php echo $p['id']; ?>" <?php if ($p['id'] == $pickup_sklad) { ?>selected="selected"<?php } ?>><?php echo $p['address']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="input-group">
                      <div class="input-group-addon">Имя ответственного</div>
                      <input type="text" name="pickup_name" value="<?php echo $pickup_name; ?>" class="form-control" />
                    </div>
                    <div class="input-group">
                      <div class="input-group-addon">Телефон ответственного</div>
                      <input type="text" name="pickup_phone" value="<?php echo $pickup_phone; ?>" class="form-control" />
                    </div>
                    <a onclick="$('#form').attr('action', '<?php echo $pickup; ?>'); $('#form').submit();" class="btn btn-success disabled btn-lg btn-block" id="button-request"><?php echo $button_request; ?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <table class="table table-bordered table-hover table-responsive">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" name="onselected" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php echo $column_id; ?></td>
                  <td class="text-center"><?php echo $column_customer; ?></td>
                  <td class="text-center"><?php echo $column_order_status; ?></td>
                  <td class="text-center"><?php echo $column_logistic; ?></td>
                  <td class="text-center"><?php echo $column_shipment; ?></td>
                  <td class="text-center"><?php echo $column_status; ?></td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order) { ?>
                <tr class="<?php echo $order['color']; ?>">
                  <td class="text-center" style="line-height: 30px;">
                    <i class="fa fa-<?php echo $order['icon']; ?> fa-2x" title="<?php echo $order['tariff']; ?>"></i><br>
                    <?php if (in_array($order['order_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                    <?php } ?>
                    <br><a href="<?php echo $order['order_view']; ?>" target="_blank"><?php echo $order['order_id']; ?></a>
                  </td>
                  <td class="text-left">
                    <i class="fa fa-map-marker"></i> <?php echo $order['to']; ?><br>
                    <strong class="text-success" style="font-size: 14px;"><i class="fa fa-rub"></i> <?php echo $order['total']; ?></strong><br>
                    <i class="fa fa-clock-o"></i> <?php echo $order['date_added']; ?>
                  </td>
                  <td class="text-center">
                    <?php if ($order['check']) { ?>
                      <span id="ll_checkclient_<?php echo $order['order_id']; ?>" class="ll_checkclient_popover label label-<?php echo $order['check_color']; ?>" data-content="<?php echo $order['check']; ?>" style="cursor: pointer;"><?php echo $order['customer']; ?></span>
                    <?php } else { ?>
                      <span class="label label-default"><?php echo $order['customer']; ?></span>
                    <?php } ?>
                  </td>
                  <td class="text-center"><?php echo $order['order_status']; ?></td>
                  <td class="text-center"><?php echo $order['logisticOrderNumber']; ?></td>
                  <td class="text-center"><?php echo $order['shipment_id']; ?></td>
                  <td class="text-center">
                    <label class="control-label"><span data-toggle="tooltip" title="<?php echo $order['description']; ?>"><?php echo $order['status']; ?></span></label>
                    <br>
                    <small class="form-text text-muted"><?php echo $order['date']; ?></small>
                  </td>
                  <td class="text-center">
                    <?php if ($order['status_id'] == 1010) { ?>
                    <div class="btn-group">
                      <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-default"><i class="fa fa-eye"></i></a>
                    </div>
                    <?php } elseif ($order['status_id'] >= 0) { ?>
                    <div class="btn-group">
                      <?php if ($order['status_id']) { ?>
                        <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-default"><i class="fa fa-eye"></i></a>
                        <a onclick="$('input[value=<?php echo $order['order_id']; ?>]').prop('checked', true); $('#form').attr('action', '<?php echo $label; ?>').attr('target', '_blank'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_label; ?>" class="btn btn-primary"><i class="fa fa-sticky-note-o"></i></a>
                        <?php if ($order['shipment_id'] != '') { ?><!-- создана отгрузка -->
                          <a onclick="$('input[value=<?php echo $order['order_id']; ?>]').prop('checked', true); $('#form').attr('action', '<?php echo $print; ?>').attr('target', '_blank'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_print; ?>" class="btn btn-primary"><i class="fa fa-print"></i></a>
                          <a href="<?php echo $order['update']; ?>" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></a>
                        <?php } elseif ($order['orderId'] != '') { ?><!-- создано отправление -->
                          <a onclick="$('input[value=<?php echo $order['order_id']; ?>]').prop('checked', true); $('#form').attr('action', '<?php echo $change; ?>'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_change; ?>" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                          <a style="cursor: pointer;" onclick="confirm('<?php echo $text_canceled_confirm; ?>') ? location = '<?php echo $order['canceled']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_canceled; ?>" class="btn btn-warning"><i class="fa fa-times"></i></a>
                        <?php } ?>
                      <?php } else { ?>
                        <a onclick="$('input[value=<?php echo $order['order_id']; ?>]').prop('checked', true); $('#form').attr('action', '<?php echo $send; ?>'); $('#form').submit();" data-toggle="tooltip" title="<?php echo $button_create; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                        <a style="cursor: pointer;" onclick="confirm('<?php echo $text_remove_confirm; ?>') ? location = '<?php echo $order['remove']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
        <?php } else { ?>
        <div class="row">
          <div class="col-sm-12 text-center"><?php echo $text_no_results; ?></div>
        </div>
        <?php } ?>
      </div>
      <div class="panel-footer">
        <img src="../image/catalog/<?php echo $m; ?>/ll.png" class="pull-right">
        <span class="label label-default"><?php echo $m; ?></span>
        <span class="label label-default"><?php echo $version; ?></span>
      </div>
    </div>
  </div>
</div>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script>
$('.date').datetimepicker({
  pickTime: false,
  format: 'YYYY-MM-DD'
});

$('input[name*=\'selected\'], input[name*=\'onselected\']').on('change', function() {
  $('#button-send, #button-dropoff, #button-pickup, #button-request, #button-update, #button-label, #button-print').addClass('disabled');

  var selected = $('input[name*=\'selected\']:checked');

  if (selected.length) {
    $('#button-send, #button-dropoff, #button-pickup, #button-request, #button-update, #button-label, #button-print').removeClass('disabled');
  }
});

$('input[name=\'filter_order_id\'], input[name=\'filter_to\'], input[name=\'filter_pvz\'], input[name=\'filter_total\'], input[name=\'filter_logisticOrderNumber\'], input[name=\'filter_shipment_id\'], input[name=\'filter_date_added\']').on('keydown', function(e) {
  if (e.keyCode == 13) {
    $('#button-filter').trigger('click');
  }
});

$('#button-filter').on('click', function() {
  url = '<?php echo $orderr; ?>';

  var filter_order_id = $('input[name=\'filter_order_id\']').val();

  if (filter_order_id) {
    url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
  }

  var filter_to = $('input[name=\'filter_to\']').val();

  if (filter_to) {
    url += '&filter_to=' + encodeURIComponent(filter_to);
  }

  var filter_pvz = $('input[name=\'filter_pvz\']').val();

  if (filter_pvz) {
    url += '&filter_pvz=' + encodeURIComponent(filter_pvz);
  }

  var filter_total = $('input[name=\'filter_total\']').val();

  if (filter_total) {
    url += '&filter_total=' + encodeURIComponent(filter_total);
  }

  var filter_tariff = $('select[name=\'filter_tariff\']').val();

  if (filter_tariff != '*') {
    url += '&filter_tariff=' + encodeURIComponent(filter_tariff);
  }

  var filter_order_status = $('select[name=\'filter_order_status\']').val();

  if (filter_order_status != '*') {
    url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
  }

  var filter_logisticOrderNumber = $('input[name=\'filter_logisticOrderNumber\']').val();

  if (filter_logisticOrderNumber) {
    url += '&filter_logisticOrderNumber=' + encodeURIComponent(filter_logisticOrderNumber);
  }

  var filter_shipment_id = $('input[name=\'filter_shipment_id\']').val();

  if (filter_shipment_id) {
    url += '&filter_shipment_id=' + encodeURIComponent(filter_shipment_id);
  }

  var filter_delivery_status = $('select[name=\'filter_delivery_status\']').val();

  if (filter_delivery_status != '*') {
    url += '&filter_delivery_status=' + encodeURIComponent(filter_delivery_status);
  }

  var filter_date_added = $('input[name=\'filter_date_added\']').val();

  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }

  var filter_customer = $('input[name=\'filter_customer\']').val();

  if (filter_customer) {
    url += '&filter_customer=' + encodeURIComponent(filter_customer);
  }

  location = url;
});

$('input[name=\'filter_customer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: '<?php echo $customer_autocomplete; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_customer\']').val(item['label']);
  }
});

$('input[name=\'filter_to_name\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: '<?php echo $to_autocomplete; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['full'],
            value: item['id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_to\']').val(item['value']);
    $('input[name=\'filter_to_name\']').val(item['label']);
  }
});

$('.ll_checkclient_popover').popover({
  animation: 'false',
  placement: 'left auto',
  html: 'true',
  trigger: 'hover click'
});

function checkPhone(id, phone, customer_id) {
  $.ajax({
    url: '<?php echo $check_phone_url; ?>&phone=' + encodeURIComponent(phone) + '&customer_id=' + encodeURIComponent(customer_id),
    dataType: 'json',
    success: function(json) {
      if (json['success']) {
        $('#ll_checkclient_' + id).removeAttr('data-content');
        $('#ll_checkclient_' + id).data('bs.popover').options.content = json['success'];
        $('#ll_checkclient_' + id).popover('show');
      }

      if (json['error']) {
        $('#ll_checkclient_' + id).removeAttr('data-content');
        $('#ll_checkclient_' + id).data('bs.popover').options.content = json['error'];
        $('#ll_checkclient_' + id).popover('show');
      }

      if (json['color']) {
        $('#ll_checkclient_' + id).removeClass('label-default label-success label-warning label-danger');
        $('#ll_checkclient_' + id).addClass('label-' + json['color']);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$('.date').datetimepicker({
  pickTime: false,
});

$('.time').datetimepicker({
  pickDate: false,
  locale: 'ru',
  format: 'HH:mm',
});
</script>
<?php echo $footer; ?> 
