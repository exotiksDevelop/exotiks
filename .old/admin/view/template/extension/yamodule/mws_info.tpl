<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">

    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
		<?php if(count($mws_order_error)==0){ ?>
        <ul class="nav nav-tabs">
				 <li class="active"><a href="#tab-return" data-toggle="tab"><?php echo $tab_return; ?></a></li>
				 <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
        </ul>
        <div class="tab-content">
         <div class="tab-pane active" id="tab-return">
			<?php if (isset($return_success)) { ?><p class='alert alert-success'><?php echo $text_return_success; ?></p><?php } ?>
			<?php if (isset($return_error)) foreach ($return_error as $text_err){?><p class='alert alert-danger'><?php echo $text_err; ?></p><?php } ?>
			<form class="form-horizontal" method='post' action='<?php echo $form_return_url; ?>'>
            <table class="table table-bordered">
					<?php if ($invoiceId>0) {?>
				  <tr>
                <td><?php echo $lbl_mws_inv; ?></td>
                <td><?php echo $invoiceId; ?></td>
              </tr>
				  <tr>
                <td><?php echo $text_order_id; ?></td>
                <td><?php echo $order_id; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_payment_method; ?></td>
                <td><?php echo $payment_method; ?></td>
              </tr>
				  <tr>
                <td><?php echo $text_total; ?></td>
                <td><?php echo $order_total; ?></td>
              </tr>
				  <tr>
                <td><?php echo $text_return_total; ?></td>
                <td><?php echo $return_total; ?></td>
              </tr>
                <? if (count($products) < 1 && $ya_kassa_send_check) { ?>

                <? } else { ?>
					  <tr>
						 <td><?php echo $text_amount; ?></td>
                            <td>
                                <? if ($ya_kassa_send_check) { ?>
                                    <input type="text" disabled name="return_sum_front" class='control-form return_sum return_disabled' value="<?php echo ($return_sum); ?>" id="return_sum" />
                                    <input type="hidden" name="return_sum" class='control-form return_sum return_hidden' value="<?php echo ($return_sum); ?>" id="return_sum" /> руб.
                                <?  } else { ?>
                                    <input type="text" name="return_sum" class='control-form return_sum' value="<?php echo ($return_sum); ?>" id="return_sum" /> руб.
                                <? } ?>
                            </td>
					  </tr>
					  <tr>
						 <td><?php echo $text_cause; ?></td>
						 <td><textarea class='control-form' name='return_cause'></textarea></td>
					  </tr>
                <? if ($ya_kassa_send_check) { ?>
                    <tr>
                        <td></td>
                        <td>
                            <label><input checked type="radio" name="fullreturn" value="1" style="margin-left: 10px;"/> Полный возврат</label>
                            <label><input <? if (!$ya_kassa_send_check || !count($products)) { ?> disabled <? } ?>type="radio" name="fullreturn" value="0" style="margin-left: 10px;"/> Частичный возврат</label>
                        </td>
                    </tr>
                            <tr class="product-list" style="display: none;">
                                <td colspan="2">
                                <label>Товары, которые будут удалены из чека</label>
                                    <script>
                                        $(document).ready(function(){
                                            updPrice();

                                            $('input[name="fullreturn"]').on('change', function () {
                                                var value = $('input[name="fullreturn"]:checked').val();
                                                if (value == 1) {
                                                    $('.product-list').hide();
                                                } else {
                                                    $('.product-list').show();
                                                }

                                                updPrice();
                                            });
                                            $('input[name="fullreturn"]').trigger('change');

                                            $('.removeProduct').click(function(e) {
                                                $(this).parent().parent().remove();
                                                updPrice();
                                            });

                                            $('.qty_change').click(function(e) {
                                                e.preventDefault();
                                                var value = parseInt($(this).parent().find('input.nshow').first().val());
                                                var min = parseInt($(this).attr('min'));
                                                var max = parseInt($(this).attr('max'));

                                                if (value < min) {
                                                    return false;
                                                }
                                                if (value > max) {
                                                    return false;
                                                }

                                                if ($(this).hasClass('up')) {
                                                    value++;
                                                }

                                                if ($(this).hasClass('down')) {
                                                    value--;
                                                }

                                                $(this).parent().find('input').val(value);
                                                updPrice();
                                            });

                                            function updPrice() {
                                                var sum = 0;
                                                $('input.summa').each(function() {
                                                    var qty = parseFloat($(this).parent().parent().find('input.nshow').val());
                                                    sum += parseFloat($(this).val()) * qty;
                                                });

                                                $('.return_sum').val(sum.toFixed(2));
                                            }
                                        });
                                    </script>
                                <input type="hidden" class="email" value="<? echo $id_order; ?>" name="id_order"/>
                                    <input type="hidden" class="email" value="<? echo $email; ?>" name="email"/>
                                    <table style="width:100%;" class="list">
                                        <tbody>
                                            <? foreach ($products as $product) { ?>
                                                <tr >
                                                    <td><? echo $product['name']; ?></td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button class="qty_change down" max="<? echo $product['quantity']; ?>" min="0"> - </button>
                                                            <input class="show" disabled style="text-align: center;width:60px;" type="text" value="<? echo $product['quantity']; ?>" />
                                                            <input class="nshow" type="hidden" value="<? echo $product['quantity']; ?>" name="items[<? echo $product['product_id']; ?>_<? echo $product['order_product_id']; ?>][quantity]" />
                                                            <button class="qty_change up" max="<? echo $product['quantity']; ?>" min="0"> + </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class="removeProduct" style="text-decoration: underline; cursor: pointer;">Оставить в чеке</a>
                                                        <input type="hidden" class="summa" value="<? echo $product['price_total']; ?>" name="items[<? echo $product['product_id']; ?>_<? echo $product['order_product_id']; ?>][price][amount]"/>
                                                        <input type="hidden" value="<? echo $product['product_id']; ?>" name="items[<? echo $product['product_id']; ?>_<? echo $product['order_product_id']; ?>][order_product_id]"/>
                                                        <input type="hidden" value="<? echo $product['name']; ?>" name="items[<? echo $product['product_id']; ?>_<? echo $product['order_product_id']; ?>][text]"/>
                                                        <input type="hidden" value="643" name="items[<? echo $product['product_id']; ?>_<? echo $product['order_product_id']; ?>][price][currency]"/>
                                                        <input type="hidden" value="<? echo $product['tax_value']; ?>" name="items[<? echo $product['product_id']; ?>_<? echo $product['order_product_id']; ?>][tax]"/>
                                                    </td>
                                                </tr>
                                            <? } ?>
                                            <? if ($delivery) {?>
                                                <tr>
                                                    <td><? echo $dname; ?></td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button class="qty_change down" max="1" min="0"> - </button>
                                                            <input class="show" disabled style="text-align: center;width:60px;" type="text" value="1" />
                                                            <input class="nshow" type="hidden" value="1" name="items[shipping][quantity]" />
                                                            <button class="qty_change up" max="1" min="0"> + </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class="removeProduct" style="text-decoration: underline; cursor: pointer;">Оставить в чеке</a>
                                                        <input type="hidden" value="0" name="items[shipping][order_product_id]"/>
                                                        <input type="hidden" class="summa" value="<? echo $delivery; ?>" name="items[shipping][price][amount]"/>
                                                        <input type="hidden" value="643" name="items[shipping][price][currency]"/>
                                                        <input type="hidden" value="0" name="items[shipping][tax]"/>
                                                        <input type="hidden" value="<? echo $dname; ?>" name="items[shipping][text]"/>
                                                    </td>
                                                </tr>
                                            <? } ?>
                                        </tbody>
                                </table>
                                </td>
                            </tr>
                        <? } ?>
					  <tr>
						 <td colspan='2'><button type='submit' class='btn btn-success'><?php echo $btn_return; ?></button></td>
					  </tr>
                    <? } ?>
					<?php } else { ?>
							<tr>
								<td colspan='3'><div class='alert alert-danger'><?php echo $text_invoice_empty; ?> </div></td>
							</tr>
						<?php } ?>
					</table>
				</form>
          </div>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
            <br />
            <fieldset>
              <legend><?php echo $text_history; ?></legend>
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-order-status"><?php //echo $entry_order_status; ?></label>
                  <div class="col-sm-10">
						<table class='table'>
							 <tr>
								 <th><?php echo $tbl_head_date; ?></th>
								 <th><?php echo $tbl_head_amount; ?></th>
								 <th><?php echo $tbl_head_cause; ?></th>
							 </tr>
                      <?php if($return_items!==false){
								foreach ($return_items as $key => $item) { ?>
							 <tr>
								 <td><?php echo $item['date']; ?></td>
								 <td><?php echo $item['amount']; ?></td>
								 <td><?php echo $item['cause']; ?></td>
							 </tr>
                      <?php }}else{ ?>
							 <tr>
								 <td colspan='3'><div class='alert alert-danger'><?php echo $text_history_empty; ?> </div></td>
							 </tr>
							 <?php } ?>
						</table>
                  </div>
                </div>
              </form>
            </fieldset>
          </div>
        </div>
		<?php }else{?>
				<?php foreach ($mws_order_error as $alert_text) { ?><div class='alert alert-danger'><?php echo $alert_text; ?> </div> <?php } ?>
		<?php } ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
<style>
    table.list td {
        padding:5px;
    }
</style>