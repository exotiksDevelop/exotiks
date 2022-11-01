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
        <a onclick="$('#form').submit()" class="btn btn-success"><i class="fa fa-play"></i> <?php echo $button_export; ?></a>
        <a href="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping; ?>" class="btn btn-default"><i class="fa fa-truck"></i></a>
        <a href="<?php echo $exchange; ?>" data-toggle="tooltip" title="<?php echo $button_exchange; ?>" class="btn btn-default"><i class="fa fa-exchange"></i></a>
        <a href="<?php echo $order; ?>" data-toggle="tooltip" title="<?php echo $button_order; ?>" class="btn btn-default"><i class="fa fa-shopping-cart"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title_send; ?></h1>
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
        <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="row">
            <div class="col-sm-1">
              <ul class="nav nav-pills nav-stacked" id="orders">
                <?php $row = 0; ?>
                <?php foreach ($orders as $order) { ?>
                <li><a href="#tab-order-<?php echo $row; ?>" data-toggle="tab"><?php echo $order['order_id']; ?></a></li>
                <?php $row++; ?>
                <?php } ?>
              </ul>
            </div>
            <div class="col-sm-11">
              <div class="tab-content">
                <?php $row = 0; ?>
                <?php foreach ($orders as $order) { ?>
                  <div class="tab-pane" id="tab-order-<?php echo $row; ?>">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab-general-<?php echo $row; ?>" data-toggle="tab">Общее</a></li>
                      <li><a href="#tab-buyer-<?php echo $row; ?>" data-toggle="tab">Покупатель</a></li>
                      <li><a href="#tab-recipient-<?php echo $row; ?>" data-toggle="tab">Получатель</a></li>
                      <li><a href="#tab-payment-<?php echo $row; ?>" data-toggle="tab">Оплата</a></li>
                      <li><a href="#tab-shipping-<?php echo $row; ?>" data-toggle="tab">Доставка</a></li>
                      <li><a href="#tab-package-<?php echo $row; ?>" data-toggle="tab">Упаковка</a></li>
                      <li><a href="#tab-product-<?php echo $row; ?>" data-toggle="tab">Товары</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab-general-<?php echo $row; ?>">
                        <input type="hidden" name="order[<?php echo $row; ?>][order_id]" value="<?php echo $order['order_id']; ?>" />
                        <?php if (isset($order['orderId'])) { ?>
                          <input type="hidden" name="order[<?php echo $row; ?>][orderId]" value="<?php echo $order['orderId']; ?>" />
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Заказ</label>
                          <div class="col-sm-10">
                            <a href="<?php echo $order['link_edit']; ?>" target="_blank" class="btn"><?php echo $order['order_id']; ?></a>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Номер заказа</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][orderNumber]" value="<?php echo $order['orderNumber']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Способ отгрузки</label>
                          <div class="col-sm-10">
                            <select name="order[<?php echo $row; ?>][firstMileTransfer][type]" class="form-control">
                              <option value="DropOff" <?php if ('DropOff' == $order['firstMileTransfer_type']) { ?>selected="selected"<?php } ?>>Доставка на склад Озон</option>
                              <option value="PickUp" <?php if ('PickUp' == $order['firstMileTransfer_type']) { ?>selected="selected"<?php } ?>>Забор</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Склад</label>
                          <div class="col-sm-10">
                            <select name="order[<?php echo $row; ?>][firstMileTransfer][fromPlaceId]" class="form-control">
                              <?php if ($order['places'] && !empty($order['places'])) { ?>
                              <?php foreach ($order['places'] as $place) { ?>
                              <option value="<?php echo $place['id']; ?>" <?php if ($place['id'] == $order['firstMileTransfer_fromPlaceId']) { ?>selected="selected"<?php } ?>><?php echo $place['address']; ?></option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Частичная выдача</label>
                          <div class="col-sm-10">
                            <select name="order[<?php echo $row; ?>][allowPartialDelivery]" class="form-control">
                              <option value="0" <?php if (0 == $order['allowPartialDelivery']) { ?>selected="selected"<?php } ?>>Нет</option>
                              <option value="1" <?php if (1 == $order['allowPartialDelivery']) { ?>selected="selected"<?php } ?>>Да</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Запрет вскрытия до оплаты</label>
                          <div class="col-sm-10">
                            <select name="order[<?php echo $row; ?>][allowUncovering]" class="form-control">
                              <option value="0" <?php if (0 == $order['allowUncovering']) { ?>selected="selected"<?php } ?>>Да</option>
                              <option value="1" <?php if (1 == $order['allowUncovering']) { ?>selected="selected"<?php } ?>>Нет</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Комментарий</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][comment]" value="<?php echo $order['comment']; ?>" class="form-control" />
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab-buyer-<?php echo $row; ?>">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Тип клиента</label>
                          <div class="col-sm-10">
                            <select name="order[<?php echo $row; ?>][buyer][type]" class="form-control">
                              <option value="NaturalPerson" <?php if ('NaturalPerson' == $order['buyer_type']) { ?>selected="selected"<?php } ?>>Физическое лицо</option>
                              <option value="LegalPerson" <?php if ('LegalPerson' == $order['buyer_type']) { ?>selected="selected"<?php } ?>>Юридическое лицо</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Наименование юр. лица</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][buyer][legalName]" value="<?php echo $order['buyer_legalName']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">ФИО</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][buyer][name]" value="<?php echo $order['buyer_name']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Телефон</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][buyer][phone]" value="<?php echo $order['buyer_phone']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][buyer][email]" value="<?php echo $order['buyer_email']; ?>" class="form-control" />
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab-recipient-<?php echo $row; ?>">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Тип клиента</label>
                          <div class="col-sm-10">
                            <select name="order[<?php echo $row; ?>][recipient][type]" class="form-control">
                              <option value="NaturalPerson" <?php if ('NaturalPerson' == $order['recipient_type']) { ?>selected="selected"<?php } ?>>Физическое лицо</option>
                              <option value="LegalPerson" <?php if ('LegalPerson' == $order['recipient_type']) { ?>selected="selected"<?php } ?>>Юридическое лицо</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Наименование юр. лица</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][recipient][legalName]" value="<?php echo $order['recipient_legalName']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">ФИО</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][recipient][name]" value="<?php echo $order['recipient_name']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Телефон</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][recipient][phone]" value="<?php echo $order['recipient_phone']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][recipient][email]" value="<?php echo $order['recipient_email']; ?>" class="form-control" />
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab-payment-<?php echo $row; ?>">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Тип оплаты</label>
                          <div class="col-sm-10">
                            <select name="order[<?php echo $row; ?>][payment][type]" class="form-control">
                              <option value="FullPrepayment" <?php if ('FullPrepayment' == $order['payment_type']) { ?>selected="selected"<?php } ?>>Предоплата</option>
                              <option value="Postpay" <?php if ('Postpay' == $order['payment_type']) { ?>selected="selected"<?php } ?>>Наложенный</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Сумма предоплаты</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                              <input type="text" name="order[<?php echo $row; ?>][payment][prepaymentAmount]" value="<?php echo $order['payment_prepaymentAmount']; ?>" class="form-control" />
                              <div class="input-group-addon">руб</div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Сумма наложенного</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                              <input type="text" name="order[<?php echo $row; ?>][payment][recipientPaymentAmount]" value="<?php echo $order['payment_recipientPaymentAmount']; ?>" class="form-control" />
                              <div class="input-group-addon">руб</div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Стоимость доставки</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                              <input type="text" name="order[<?php echo $row; ?>][payment][deliveryPrice]" value="<?php echo $order['payment_deliveryPrice']; ?>" class="form-control" />
                              <div class="input-group-addon">руб</div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Ставка НДС доставки</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-sm">
                              <input type="text" name="order[<?php echo $row; ?>][payment][deliveryVat][rate]" value="<?php echo $order['payment_deliveryVat_rate']; ?>" class="form-control" />
                              <div class="input-group-addon">%</div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Сумма НДС доставки</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                              <input type="text" name="order[<?php echo $row; ?>][payment][deliveryVat][sum]" value="<?php echo $order['payment_deliveryVat_sum']; ?>" class="form-control" />
                              <div class="input-group-addon">руб</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab-shipping-<?php echo $row; ?>">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Идентификатор способа доставки</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][deliveryInformation][deliveryVariantId]" value="<?php echo $order['deliveryInformation_deliveryVariantId']; ?>" class="form-control" readonly />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Тип доставки</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][deliveryInformation][deliveryType]" value="<?php echo $order['deliveryInformation_deliveryType']; ?>" class="form-control" readonly />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Адрес</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][deliveryInformation][address]" value="<?php echo $order['deliveryInformation_address']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Номер квартиры</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][deliveryInformation][additionalAddress]" value="<?php echo $order['deliveryInformation_additionalAddress']; ?>" class="form-control" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Желаемый интервал доставки</label>
                          <div class="col-sm-5">
                            <div class="input-group">
                              <div class="input-group-addon">с</div>
                              <input type="text" name="order[<?php echo $row; ?>][deliveryInformation][desiredDeliveryTimeInterval][from]" value="<?php echo $order['deliveryInformation_desiredDeliveryTimeInterval_from']; ?>" class="form-control" />
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="input-group">
                              <div class="input-group-addon">до</div>
                              <input type="text" name="order[<?php echo $row; ?>][deliveryInformation][desiredDeliveryTimeInterval][to]" value="<?php echo $order['deliveryInformation_desiredDeliveryTimeInterval_to']; ?>" class="form-control" />
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab-package-<?php echo $row; ?>">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Номер грузоместа</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][packages][packageNumber]" value="<?php echo $order['packages_packageNumber']; ?>" class="form-control" readonly />
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Габариты</label>
                          <div class="col-sm-10">
                            <div class="row">
                              <div class="col-sm-4">
                                <div class="input-group">
                                  <div class="input-group-addon">Длина</div>
                                  <input type="text" name="order[<?php echo $row; ?>][packages][dimensions][length]" value="<?php echo $order['packages_dimensions_length']; ?>" class="form-control" />
                                  <div class="input-group-addon">мм</div>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="input-group">
                                  <div class="input-group-addon">Высота</div>
                                  <input type="text" name="order[<?php echo $row; ?>][packages][dimensions][height]" value="<?php echo $order['packages_dimensions_height']; ?>" class="form-control" />
                                  <div class="input-group-addon">мм</div>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="input-group">
                                  <div class="input-group-addon">Ширина</div>
                                  <input type="text" name="order[<?php echo $row; ?>][packages][dimensions][width]" value="<?php echo $order['packages_dimensions_width']; ?>" class="form-control" />
                                  <div class="input-group-addon">мм</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label">Вес</label>
                          <div class="col-sm-10">
                            <div class="input-group">
                              <input type="text" name="order[<?php echo $row; ?>][packages][dimensions][weight]" value="<?php echo $order['packages_dimensions_weight']; ?>" class="form-control" />
                              <div class="input-group-addon">г</div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Штрихкод грузоместа</label>
                          <div class="col-sm-10">
                            <input type="text" name="order[<?php echo $row; ?>][packages][barCode]" value="<?php echo $order['packages_barCode']; ?>" class="form-control" />
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab-product-<?php echo $row; ?>">
                        <table class="table table-bordered table-hover table-condensed">
                          <thead>
                            <tr>
                              <td colspan="3">
                                <button type="button" onclick="mergeProducts(<?php echo $row; ?>);" class="btn btn-success btn-block btn-sm"><i class="fa fa-compress"></i> Объединить товары</button>
                              </td>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $product_row = 0; ?>
                            <?php foreach ($order['products'] as $product) { ?>
                            <tr>
                              <td rowspan="11" class="col-xs-2"><a href="<?php echo $product['edit']; ?>" target="_blank"><?php echo $product['name']; ?></a></td>
                            </tr>
                            <tr>
                              <td class="col-xs-2 required">Наименование</td>
                              <td>
                                <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][name]" value="<?php echo $product['name']; ?>" class="form-control input-sm" />
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2 required">Артикул</td>
                              <td>
                                <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][articleNumber]" value="<?php echo $product['articleNumber']; ?>" class="form-control input-sm" />
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2 required">Стоимость</td>
                              <td>
                                <div class="input-group input-group-sm">
                                  <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][sellingPrice]" value="<?php echo $product['sellingPrice']; ?>" class="form-control input-sm" />
                                  <div class="input-group-addon">руб</div>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2 required">Оценочная стоимость</td>
                              <td>
                                <div class="input-group input-group-sm">
                                  <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][estimatedPrice]" value="<?php echo $product['estimatedPrice']; ?>" class="form-control input-sm" />
                                  <div class="input-group-addon">руб</div>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2 required">Количество</td>
                              <td>
                                <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][quantity]" value="<?php echo $product['quantity']; ?>" class="form-control input-sm" />
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2">Ставка НДС</td>
                              <td>
                                <div class="input-group input-group-sm">
                                  <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][vat][rate]" value="<?php echo $product['vat_rate']; ?>" class="form-control input-sm" />
                                  <div class="input-group-addon">%</div>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2">Сумма НДС</td>
                              <td>
                                <div class="input-group input-group-sm">
                                  <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][vat][sum]" value="<?php echo $product['vat_sum']; ?>" class="form-control input-sm" />
                                  <div class="input-group-addon">руб</div>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2">Опасный груз</td>
                              <td>
                                <select name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][attributes][isDangerous]" class="form-control input-sm">
                                  <option value="0" <?php if (0 == $product['isDangerous']) { ?>selected="selected"<?php } ?>>Нет</option>
                                  <option value="1" <?php if (1 == $product['isDangerous']) { ?>selected="selected"<?php } ?>>Да</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2">ИНН поставщика</td>
                              <td>
                                <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][supplierTin]" value="<?php echo $product['supplierTin']; ?>" class="form-control input-sm" />
                              </td>
                            </tr>
                            <tr>
                              <td class="col-xs-2 required">Номер грузоместа</td>
                              <td>
                                <input type="text" name="order[<?php echo $row; ?>][orderLines][<?php echo $product_row; ?>][resideInPackages]" value="<?php echo $order['packages_packageNumber']; ?>" class="form-control input-sm" readonly />
                              </td>
                            </tr>
                            <?php $product_row++; ?>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <?php $row++; ?>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="panel-footer">
        <img src="../image/catalog/<?php echo $m; ?>/ll.png" class="pull-right">
        <span class="label label-default"><?php echo $m; ?></span>
        <span class="label label-default"><?php echo $version; ?></span>
      </div>
    </div>
  </div>
</div>
<script>
$('#orders a:first').tab('show');

let html = [];

<?php $row = 0; ?>
<?php foreach ($orders as $order) { ?>
  html[<?php echo $row; ?>]  = '<tr>';
  html[<?php echo $row; ?>] += '  <td rowspan="11" class="col-xs-2"><a href="<?php echo $order['merge']['edit']; ?>" target="_blank"><?php echo $order['merge']['name']; ?></a></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2 required">Наименование</td>';
  html[<?php echo $row; ?>] += '  <td><input type="text" name="order[<?php echo $row; ?>][orderLines][0][name]" value="<?php echo $order['merge']['name']; ?>" class="form-control input-sm" /></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2 required">Артикул</td>';
  html[<?php echo $row; ?>] += '  <td><input type="text" name="order[<?php echo $row; ?>][orderLines][0][articleNumber]" value="<?php echo $order['merge']['articleNumber']; ?>" class="form-control input-sm" /></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2 required">Стоимость</td>';
  html[<?php echo $row; ?>] += '  <td><div class="input-group input-group-sm"><input type="text" name="order[<?php echo $row; ?>][orderLines][0][sellingPrice]" value="<?php echo $order['merge']['sellingPrice']; ?>" class="form-control input-sm" /><div class="input-group-addon">руб</div></div></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2 required">Оценочная стоимость</td>';
  html[<?php echo $row; ?>] += '  <td><div class="input-group input-group-sm"><input type="text" name="order[<?php echo $row; ?>][orderLines][0][estimatedPrice]" value="<?php echo $order['merge']['estimatedPrice']; ?>" class="form-control input-sm" /><div class="input-group-addon">руб</div></div></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2 required">Количество</td>';
  html[<?php echo $row; ?>] += '  <td><input type="text" name="order[<?php echo $row; ?>][orderLines][0][quantity]" value="<?php echo $order['merge']['quantity']; ?>" class="form-control input-sm" /></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2">Ставка НДС</td>';
  html[<?php echo $row; ?>] += '  <td><div class="input-group input-group-sm"><input type="text" name="order[<?php echo $row; ?>][orderLines][0][vat][rate]" value="<?php echo $order['merge']['vat_rate']; ?>" class="form-control input-sm" /><div class="input-group-addon">%</div></div></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2">Сумма НДС</td>';
  html[<?php echo $row; ?>] += '  <td><div class="input-group input-group-sm"><input type="text" name="order[<?php echo $row; ?>][orderLines][0][vat][sum]" value="<?php echo $order['merge']['vat_sum']; ?>" class="form-control input-sm" /><div class="input-group-addon">руб</div></div></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2">Опасный груз</td>';
  html[<?php echo $row; ?>] += '  <td>';
  html[<?php echo $row; ?>] += '   <select name="order[<?php echo $row; ?>][orderLines][0][attributes][isDangerous]" class="form-control input-sm">';
  html[<?php echo $row; ?>] += '     <option value="0" <?php if (0 === $order['merge']['isDangerous']) { ?>selected="selected"<?php } ?>>Нет</option>';
  html[<?php echo $row; ?>] += '     <option value="1" <?php if (1 === $order['merge']['isDangerous']) { ?>selected="selected"<?php } ?>>Да</option>';
  html[<?php echo $row; ?>] += '   </select>';
  html[<?php echo $row; ?>] += '  </td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2">ИНН поставщика</td>';
  html[<?php echo $row; ?>] += '  <td><input type="text" name="order[<?php echo $row; ?>][orderLines][0][supplierTin]" value="<?php echo $order['merge']['supplierTin']; ?>" class="form-control input-sm" /></td>';
  html[<?php echo $row; ?>] += '</tr>';
  html[<?php echo $row; ?>] += '<tr>';
  html[<?php echo $row; ?>] += '  <td class="col-xs-2 required">Номер грузоместа</td>';
  html[<?php echo $row; ?>] += '  <td><input type="text" name="order[<?php echo $row; ?>][orderLines][0][resideInPackages]" value="1" class="form-control input-sm" readonly /></td>';
  html[<?php echo $row; ?>] += '</tr>';
<?php $row++; ?>
<?php } ?>

function mergeProducts(row) {
  $('#tab-product-' + row + ' table tbody').html(html[row]);
}
</script>
<?php echo $footer; ?> 
