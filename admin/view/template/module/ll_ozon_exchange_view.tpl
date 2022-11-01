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
        <?php if ($order['shipment_id'] != '') { ?><!-- создана отгрузка -->
          <a href="<?php echo $order['update']; ?>" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></a>
          <a href="<?php echo $order['print']; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_print; ?>" class="btn btn-primary"><i class="fa fa-print"></i></a>
        <?php } elseif ($order['orderId'] != '') { ?><!-- создано отправление -->
        <?php } ?>
        <a href="<?php echo $order['label']; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_label; ?>" class="btn btn-primary"><i class="fa fa-sticky-note-o"></i></a>
        <a href="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping; ?>" class="btn btn-default"><i class="fa fa-truck"></i></a>
        <a href="<?php echo $exchange; ?>" data-toggle="tooltip" title="<?php echo $button_exchange; ?>" class="btn btn-default"><i class="fa fa-exchange"></i></a>
        <a href="<?php echo $orders; ?>" data-toggle="tooltip" title="<?php echo $button_order; ?>" class="btn btn-default"><i class="fa fa-shopping-cart"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title_view; ?></h1>
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
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Заказ</h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td style="width: 30%;">Номер заказа</td>
                <td><a href="<?php echo $order['order_link']; ?>" target="_blank"><?php echo $order['order_id']; ?></a></td>
              </tr>
              <tr>
                <td>Номер заказа Ozon</td>
                <td><?php echo $order['orderId']; ?></td>
              </tr>
              <tr>
                <td>Номер отправления</td>
                <td><?php echo $order['logistic']; ?></td>
              </tr>
              <tr>
                <td>Номер отгрузки</td>
                <td><?php echo $order['shipment_id']; ?></td>
              </tr>
              <tr>
                <td>Тариф</td>
                <td><?php echo $order['tariff']; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Покупатель</h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td style="width: 30%;">ФИО</td>
                <td>
                  <?php if ($order['customer_link']) { ?>
                    <a href="<?php echo $order['customer_link']; ?>" target="_blank"><?php echo $order['customer']; ?></a>
                  <?php } else { ?>
                    <?php echo $order['customer']; ?>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td>Страна</td>
                <td><?php echo $order['country']; ?></td>
              </tr>
              <tr>
                <td>Регион</td>
                <td><?php echo $order['zone']; ?></td>
              </tr>
              <tr>
                <td>Город</td>
                <td><?php echo $order['city']; ?></td>
              </tr>
              <?php if ($order['pvz']) { ?>
              <tr>
                <td>ПВЗ</td>
                <td><?php echo $order['pvz']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-calendar"></i> Статусы заказа</h3>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Дата</td>
                <td class="text-left">Статус</td>
                <td class="text-left">Комментарий</td>
                <td class="text-left">Покупатель уведомлен</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($histories as $history) { ?>
              <tr>
                <td class="text-left"><?php echo $history['date_added']; ?></td>
                <td class="text-left"><?php echo $history['status']; ?></td>
                <td class="text-left"><?php echo $history['comment']; ?></td>
                <td class="text-left"><?php echo $history['notify']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-truck"></i> Статусы отправления</h3>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Дата</td>
                <td class="text-left">Статус</td>
                <td class="text-left">Описание</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($statuses as $status) { ?>
              <tr class="<?php echo $status['color']; ?>">
                <td class="text-left"><?php echo $status['date']; ?></td>
                <td class="text-left"><?php echo $status['status']; ?></td>
                <td class="text-left"><?php echo $status['description']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
