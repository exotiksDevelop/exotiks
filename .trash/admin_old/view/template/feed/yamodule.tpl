<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
		<div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
		<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
		</ul>
		</div>
	</div>
	<div class="container-fluid">
				<?php if($err_token) { ?>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-circle"></i>
					<?php echo $err_token; ?>
					<button type="button" class="close" data-dismiss="alert">×</button>
				</div>
				<?php } ?>
				<div class='row'>
					<div class='col-sm-12'>
						<p><?php echo $text_license; ?></p>
						<p>Версия модуля <span id='ya_version'><?php echo $ya_version; ?></span></p>
					</div>
				</div>
				<ul class="nav nav-tabs">
					<li class=""><a href="#tab-kassa" data-toggle="tab"><?php echo $kassa; ?></a></li>
					<li><a href="#tab-mws" data-toggle="tab"><?php echo $mws_starter; ?></a></li>
					<li><a href="#tab-fast-pay" data-toggle="tab"><?php echo $fast_pay_title; ?></a></li>
					<li class=""><a href="#tab-p2p" data-toggle="tab"><?php echo $p2p; ?></a></li>
					<li><a href="#tab-metrika" data-toggle="tab"><?php echo $metrika; ?></a></li>
					<li class=""><a href="#tab-market" data-toggle="tab"><?php echo $market; ?></a></li>
					<li><a href="#tab-pokupki" data-toggle="tab"><?php echo $pokupki; ?></a></li>
				</ul>
				<div class="tab-content bootstrap">
					<div class="tab-pane" id="tab-kassa">
						<?php foreach ($kassa_status as $k) echo $k;?>
							<div class="">
								<!-- new view -->
								<?php if($mod_status) { ?>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="kassa_form form-horizontal">
									<input type="hidden" value="kassa" name="type_data"/>
								<div class='row'>
									<div class='col-sm-12'>
										<p><?php echo $kassa_text_connect; ?></p>
										<div class='form-horizontal'>
											<div class="form-group">
												<label for="ya_kassamode" class="col-sm-3 control-label"></label>
												<div class="col-sm-9">
													<label class='checkbox'>
														<input type="checkbox" name="ya_kassa_active" class="cls_ya_kassamode" value="1" <?php echo ($ya_kassa_active ? ' checked="checked"' : ''); ?>>
														<?php echo $kassa_text_enable; ?>
													</label>
												</div>
												<label for="ya_kassa_test" class="col-sm-3 control-label"></label>
												<div class="col-sm-9">
													<label class='radio-inline'>
														<input type="radio" name="ya_kassa_test" class="cls_ya_workmode" value="1"
															<?php echo (($ya_kassa_test) ? ' checked="checked"':''); ?>> <?php echo $kassa_text_testmode; ?>
													</label>
													<label class='radio-inline'>
														<input type="radio" name="ya_kassa_test" class="cls_ya_workmode" value="0"
															<?php echo ((!$ya_kassa_test)?' checked="checked"':''); ?>> <?php echo $kassa_text_realmode; ?>
													</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">checkUrl/avisoUrl</label>
												<div class='col-sm-8'>
													<input class='form-control disabled' value='<?php echo $ya_kassa_check; ?>' disabled>
												</div>
												<div class='col-sm-8 col-sm-offset-3'>
													<p class="help-block"><?php echo $kassa_text_help_cburl; ?></p>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">successUrl/failUrl</label>
												<div class='col-sm-8'>
													<input class='form-control disabled' value='<?php echo $kassa_text_dynamic; ?>' disabled>
												</div>
												<div class='col-sm-8 col-sm-offset-3'>
													<p class="help-block"><?php echo $kassa_text_help_url; ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- row -->
								<div class='row'>
									<div class='col-sm-12'><h4 class="form-heading"><?php echo $kassa_text_lk_head; ?></h4></div>
									<div class='col-sm-12'>
										<p><?php echo $kassa_text_get_setting; ?></p>
											<div class="form-group">
												<label for="ya_kassa_sid" class="col-sm-3 control-label">Shop ID</label>
												<div class="col-sm-9">
													<input name="ya_kassa_sid" value="<?php echo trim($ya_kassa_sid); ?>" id="ya_kassa_sid" class="form-control">
													<p class="help-block"><?php echo $kassa_text_sid; ?></p>
												</div>
												<label for="ya_kassa_scid" class="col-sm-3 control-label">scid</label>
												<div class="col-sm-9">
													<input name="ya_kassa_scid" value="<?php echo trim($ya_kassa_scid); ?>" id="ya_kassa_scid" class="form-control">
													<p class="help-block"><?php echo $kassa_text_scid; ?></p>
												</div>
												<label for="ya_kassa_pw" class="col-sm-3 control-label">ShopPassword</label>
												<div class="col-sm-9">
													<input name="ya_kassa_pw" value="<?php echo trim($ya_kassa_pw); ?>" id="ya_kassa_pw" class="form-control">
													<p class="help-block"><?php echo $kassa_text_pw; ?></p>
												</div>
											</div>
									</div>
								</div>
								<!-- row -->
								<div class='row'>
									<div class='col-sm-12'><h4 class="form-heading"><?php echo $kassa_text_paymode_head; ?></h4></div>
									<div class='col-md-12'>
										<div class='form-horizontal'>
											<div class="form-group">
												<label for="ya_paymode" class="col-sm-3 control-label"><?php echo $kassa_text_paymode_label; ?></label>
												<div class="col-sm-9">
													<input type="radio"  <?php echo ($ya_kassa_paymode ? ' checked="checked"' : ''); ?> name="ya_kassa_paymode" value="1"> <?php echo $kassa_text_paymode_kassa; ?>
												</div>
												<div class="col-sm-9 col-sm-offset-3">
													<input type="radio" <?php echo (!$ya_kassa_paymode ? ' checked="checked"' : ''); ?> name="ya_kassa_paymode" value="0"> <?php echo $kassa_text_paymode_shop; ?>
													<p class="help-block"><?php echo $kassa_text_paymode_help; ?> </p>
												</div>
												<div class="col-sm-9 col-sm-offset-3 kassa-wo-select">
													<p><?php echo $kassa_text_paylogo_help; ?></p>
													<div class="checkbox">
														<label for="ya_kassa_yandexlogo">
															<input type="checkbox" <?php echo ($ya_kassa_paylogo ? ' checked="checked"' : ''); ?> name="ya_kassa_paylogo" id="ya_kassa_paylogo" class="" value="1"/>
															<?php echo $kassa_paylogo_text; ?>
														</label>
													</div>
												</div>
												<div class="col-sm-9 col-sm-offset-3  kassa-w-select">
														<p><?php echo $kassa_text_pay_help; ?></p>
														<div class="checkbox">
															<label for="ya_kassa_ym"><input type="checkbox" <?php echo ($ya_kassa_ym ? ' checked="checked"' : ''); ?> name="ya_kassa_ym" id="ya_kassa_ym" class="" value="1"/> <?php echo $kassa_ym; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_cards"><input type="checkbox" <?php echo ($ya_kassa_cards ? ' checked="checked"' : ''); ?> name="ya_kassa_cards" id="ya_kassa_cards" class="" value="1"/> <?php echo $kassa_cards; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_cash"><input type="checkbox" <?php echo ($ya_kassa_cash ? ' checked="checked"' : ''); ?> name="ya_kassa_cash" id="ya_kassa_cash" class="" value="1"/> <?php echo $kassa_cash; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_mobile"><input type="checkbox" <?php echo ($ya_kassa_mobile ? ' checked="checked"' : ''); ?> name="ya_kassa_mobile" id="ya_kassa_mobile" class="" value="1"/> <?php echo $kassa_mobile; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_wm"><input type="checkbox" <?php echo ($ya_kassa_wm ? ' checked="checked"' : ''); ?> name="ya_kassa_wm" id="ya_kassa_wm" class="" value="1"/> <?php echo $kassa_wm; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_sber"><input type="checkbox" <?php echo ($ya_kassa_sber ? ' checked="checked"' : ''); ?> name="ya_kassa_sber" id="ya_kassa_sber" class="" value="1"/> <?php echo $kassa_sber; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_alfa"><input type="checkbox" <?php echo ($ya_kassa_alfa ? ' checked="checked"' : ''); ?> name="ya_kassa_alfa" id="ya_kassa_alfa" class="" value="1"/> <?php echo $kassa_alfa; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_ma"><input type="checkbox" <?php echo ($ya_kassa_ma ? ' checked="checked"' : ''); ?> name="ya_kassa_ma" id="ya_kassa_ma" class="" value="1"/> <?php echo $kassa_ma; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_pb"><input type="checkbox" <?php echo ($ya_kassa_pb ? ' checked="checked"' : ''); ?> name="ya_kassa_pb" id="ya_kassa_pb" class="" value="1"/> <?php echo $kassa_pb; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_qw"><input type="checkbox" <?php echo ($ya_kassa_qw ? ' checked="checked"' : ''); ?> name="ya_kassa_qw" id="ya_kassa_qw" class="" value="1"/> <?php echo $kassa_qw; ?></label>
														</div>
														<div class="checkbox">
															<label for="ya_kassa_cr"><input type="checkbox" <?php echo ($ya_kassa_cr ? ' checked="checked"' : ''); ?> name="ya_kassa_cr" id="ya_kassa_cr" class="" value="1"/> <?php echo $kassa_cr; ?></label>
														</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- row -->
								<div class='row'>
									<div class='col-sm-12'>
										<h4 class="form-heading"><?php echo $kassa_text_adv_head; ?></h4>
									</div>
									<div class='col-sm-12'>
										<p><a href='' target='_blank'> </a></p>
										<div class='form-horizontal'>
											<div class="form-group">
												<label for="ya_debugmode" class="col-sm-3 control-label"><?php echo $kassa_text_debug; ?></label>
												<div class="col-sm-9">
													<label class='radio-inline'>
														<input type="radio" <?php echo ($ya_kassa_log ? ' checked="checked"' : ''); ?> name="ya_kassa_log" value="1"> <?php echo $kassa_text_debug_en; ?>
													</label>
													<label class='radio-inline'>
														<input type="radio" <?php echo (!$ya_kassa_log ? ' checked="checked"' : ''); ?> name="ya_kassa_log" value="0"> <?php echo $kassa_text_debug_dis; ?>
													</label>
													<p class="help-block"><?php echo $kassa_text_debug_help; ?></p>
												</div>
												<!-- -->
												<label class="control-label col-sm-3"><?php echo $kassa_text_sort_order; ?></label>
												<div class='col-sm-8'>
													<input type="text" class="form-control" value="<?php echo $yamodule_total_sort_order; ?>" name="yamodule_total_sort_order" />
												</div>
												<div class='col-sm-8 col-sm-offset-3'>
													<p class="help-block"><?php //echo $kassa_text_help_url; ?></p>
												</div>
												<label class="control-label col-sm-3"><?php echo $kassa_text_cart_reset; ?></label>
												<div class='col-sm-8'>
													<div class="checkbox">
														<label for="ya_kassa_cart_reset">
															<input type="checkbox" <?php echo ($ya_kassa_cart_reset ? ' checked="checked"' : ''); ?> name="ya_kassa_cart_reset" id="ya_kassa_cart_reset" class="" value="1"/>
															<?php echo $kassa_cart_reset_text; ?>
														</label>
													</div>
												</div>
												<label class="control-label col-sm-3"><?php echo $kassa_text_create_order; ?></label>
												<div class='col-sm-8'>
													<div class="checkbox">
														<label for="ya_kassa_create_order">
															<input type="checkbox" <?php echo ($ya_kassa_create_order ? ' checked="checked"' : ''); ?> name="ya_kassa_create_order" id="ya_kassa_create_order" class="" value="1"/>
															<?php echo $kassa_create_order_text; ?>
														</label>
													</div>
													<p></p>
												</div>
												<!-- -->
												<label class="control-label col-sm-3"><?php echo $kassa_text_status; ?></label>
												<div class='col-sm-8'>
													<select name="ya_kassa_os" id="ya_kassa_os" class="form-control">
														<?php foreach ($order_statuses as $order_status) { ?>
															<?php if ($order_status['order_status_id'] == $ya_kassa_os) { ?>
																<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
									<!-- -->
									<div class='row'>
										<div class='col-sm-12'>
											<div class="form-group">
												<label class="col-sm-3 control-label"><?php echo $kassa_text_inv; ?></label>
												<div class="col-sm-9">
													<label class='radio-inline'>
														<input type="radio" <?php echo ($ya_kassa_inv ? ' checked="checked"' : ''); ?> name="ya_kassa_inv" value="1"> <?php echo $kassa_text_debug_en; ?>
													</label>
													<label class='radio-inline'>
														<input type="radio" <?php echo (!$ya_kassa_inv ? ' checked="checked"' : ''); ?> name="ya_kassa_inv" value="0"> <?php echo $kassa_text_debug_dis; ?>
													</label>
													<p class="help-block"></p>
												</div>
												<div class='col-sm-8 col-sm-offset-3'>
													<p class="help-block"><?php echo $kassa_text_inv_pattern; ?></p>
												</div>
												<div class="invoice-setting form-horizontal">
													<label class="col-sm-3 control-label"><?php echo $kassa_text_invhelp; ?></label>
													<div class="col-sm-9">
														<label for='ya_kassa_inv_subject' class="col-sm-3 control-label"><?php echo $kassa_text_inv_subj; ?></label>
														<div class="col-sm-9">
															<input name="ya_kassa_inv_subject" class="form-control" value="<?php echo $ya_kassa_inv_subject; ?>" id="ya_kassa_inv_subject">
															<p class="help-block"><?php echo $kassa_text_inv_subjhelp; ?></p>
														</div>
													</div>
													<label class="col-sm-3 control-label"></label>
													<div class="col-sm-9">
														<label for="ya_kassa_inv_message" class="col-sm-3 control-label"><?php echo $kassa_text_inv_text; ?></label>
														<div class="col-sm-9">
															<textarea name="ya_kassa_inv_message" id="ya_kassa_inv_message" class="form-control"><?php echo (($ya_kassa_inv_message)?$ya_kassa_inv_message:''); ?></textarea>
															<p class="help-block"><?php echo $kassa_text_inv_texthelp; ?></p>
														</div>
													</div>
													<label class="col-sm-3 control-label"></label>
													<div class="col-sm-9">
														<div class="col-sm-3"></div>
														<div class="col-sm-9">
															<div class='checkbox-inline'>
																<input class="" type="checkbox" <?php echo ($ya_kassa_inv_logo ? ' checked="checked"' : ''); ?> name="ya_kassa_inv_logo" value="1">
																<?php echo $kassa_text_inv_logo; ?>
															</div>
														</div>
													</div>
												</div>
												<!-- -->
											</div>
										</div>
									</div>
									<!-- -->
									<!-- -->
									<div class='row'>
										<div class='col-sm-12'>
											<div class="form-group">
												<label class="col-sm-3 control-label">Отправлять в Яндекс.Кассу данные для чеков (54-ФЗ)</label>
												<div class="col-sm-9">
													<label class='radio-inline'>
														<input type="radio" <?php echo ($ya_kassa_send_check ? ' checked="checked"' : ''); ?> name="ya_kassa_send_check" value="1"> <?php echo $kassa_text_debug_en; ?>
													</label>
													<label class='radio-inline'>
														<input type="radio" <?php echo (!$ya_kassa_send_check ? ' checked="checked"' : ''); ?> name="ya_kassa_send_check" value="0"> <?php echo $kassa_text_debug_dis; ?>
													</label>
												</div>
												<!-- -->
												<div class="col-sm-8 col-sm-offset-3 taxesArea">
													<div class="form-group">
														<label class="col-sm-4 control-label">Ставка по умолчанию</label>
														<div class="col-sm-8">
															<select name="ya_kassa_tax_default" id="ya_kassa_tax_default" class="form-control">
																<option <?= $ya_kassa_tax_default == 1 ? 'selected' : '';?> value="1">Без НДС</option>
																<option <?= $ya_kassa_tax_default == 2 ? 'selected' : '';?> value="2">0%</option>
																<option <?= $ya_kassa_tax_default == 3 ? 'selected' : '';?> value="3">10%</option>
																<option <?= $ya_kassa_tax_default == 4 ? 'selected' : '';?> value="4">18%</option>
																<option <?= $ya_kassa_tax_default == 5 ? 'selected' : '';?> value="5">Рассчётная ставка 10/110</option>
																<option <?= $ya_kassa_tax_default == 6 ? 'selected' : '';?> value="6">Рассчётная ставка 18/118</option>
															</select>
															<p class="help-block">Ставка по умолчанию будет в чеке, если в карточке товара не указана другая ставка</p>
														</div>
													</div>
												</div>
												<div class="col-sm-8 col-sm-offset-3 taxesArea">
                                                    Ставка в вашем магазине
												</div>
												<div class="col-sm-8 col-sm-offset-3 taxesArea">
													<p class="help-block">Слева — ставка НДС в вашем магазине, справа — в Яндекс.Кассе. Пожалуйста, сопоставьте их.</p>
												</div>
												<div class="col-sm-8 col-sm-offset-3 taxesArea">
													<?php echo $kassa_taxes; ?>
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-sm-12'>
											<div class="form-group">
												<label class="col-sm-3 control-label">Показывать ссылку на сайт Кассы</label>
												<div class="col-sm-9">
													<label class='radio-inline'>
														<input type="radio" <?php echo ($ya_kassa_show_in_footer ? ' checked="checked"' : ''); ?> name="ya_kassa_show_in_footer" value="1"> <?php echo $kassa_text_debug_en; ?>
													</label>
													<label class='radio-inline'>
														<input type="radio" <?php echo (!$ya_kassa_show_in_footer ? ' checked="checked"' : ''); ?> name="ya_kassa_show_in_footer" value="0"> <?php echo $kassa_text_debug_dis; ?>
													</label>
													<p class="help-block">Ссылка будет отображаться в подвале вашего сайта.</p>
												</div>
											</div>
										</div>
									</div>
									<!-- -->
								</form>
								<?php }  else { ?>
								<div class="alert alert-danger">
									<i class="fa fa-exclamation-circle"></i>
									<?php echo $mod_off; ?>
									<button type="button" class="close" data-dismiss="alert">×</button>
								</div>
								<?php }?>
							</div>
							<?php if($mod_status) { ?>
							<div class="clearfix">
								<button type="button" onclick="$('.kassa_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $kassa_sv; ?>
								</button>
							</div>
							<?php }?>
						</div>
					<div class="tab-pane" id="tab-mws">
						<div class="">
							<div class="">
								<h4><?php echo $lbl_mws_main; ?></h4>
								<?php
							if (count($mws_global_error)==0){
								if (!$cert_loaded){ ?>

								<?php if (!$mws_ip_same) : ?>
								<div class="alert alert-danger">
									<i class="fa fa-exclamation-circle"></i> IP-адрес Вашего сервера изменился с <?php echo $mws_ip_old; ?> на <?php echo $mws_ip_new; ?>.
									<button type="button" class="close" data-dismiss="alert">×</button>
								</div>
								<?php endif; ?>

								<p><?php echo $txt_mws_main; ?><p>
								<form id='mws_form' class="mws_form form-horizontal" method='post' action='<?php echo $action; ?>'>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="mws_download"><?php echo $lbl_mws_crt; ?></label>
										<div class="col-sm-3">
											<label id="mws_crt_load" class="btn btn-default"/><?php echo $btn_mws_crt_load; ?></label>
										</div>
										<div class="col-sm-6" id='mws_cert_status'>
										</div>
									</div>
									<div class="form-group without-cert">
										<label class="col-sm-3 control-label" for="mws_rule"><?php echo $lbl_mws_connect; ?></label>
										<div class="col-sm-9">
											<ol>
												<li><?php echo $txt_mws_connect; ?>
												<li><?php echo $txt_mws_doc; ?>
												<li><?php echo $txt_mws_cer; ?>
											</ol>
										</div>
									</div>

									<div class="form-group without-cert">
										<label class="col-sm-3 control-label" ><?php echo $lbl_mws_doc; ?></label>
										<div class="col-sm-9">
											<?php echo $tab_mws_before; ?>
											<table style="width: 600px;" class="table table-bordered">
												<tr>
													<td>CN</td>
													<td><?php echo $mws_cn; ?></td>
												</tr>
												<tr>
													<td><?php echo $tab_row_sign; ?></td>
													<td><textarea cols="55" disabled rows="13"><?php if (isset($mws_sign)) echo $mws_sign; ?></textarea></td>
												</tr>
												<tr>
													<td><?php echo $tab_row_cause; ?></td>
													<td><?php echo $tab_row_primary; ?></td>
												</tr>
											</table>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" >IP-адрес сервера.</label>
										<div class="col-sm-9">
											<div style="padding: 7px 0;"><?php echo $mws_ip_new; ?></div>
											<?php if (!$mws_ip_same) : ?>
												<p class="text-warning">IP-адрес Вашего сервера изменился с <?php echo $mws_ip_old; ?> на <?php echo $mws_ip_new; ?>.</p>
											<?php endif; ?>
										</div>
									</div>
									<?php } else { echo $success_mws_alert; }
										}else{
											foreach ($mws_global_error as $alert_text) { ?><div class='alert alert-danger'><?php echo $alert_text; ?></div><?php } ?>
									<?php } ?>
							</div>
							</form>
						</div>
					</div>
					<!-- Start FastPay Tab -->
					<div class="tab-pane" id="tab-fast-pay">

						<?php if($fast_pay_status):?>
							<?php foreach($fast_pay_status as $status):?>
								<?= $status;?>
							<?php endforeach;?>
						<?php endif;?>

						<div class="">
							<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="fast_pay_form form-horizontal">
								<input type="hidden" value="fast_pay" name="type_data"/>
								<div class='row'>
									<div class="col-sm-8">
										<p><?php echo $fast_pay_text?></p>
									</div>
									<div class='col-sm-12'>
										<div class='form-horizontal'>
											<div class="form-group">
												<label for="ya_fast_pay_active" class="col-sm-3 control-label"></label>
												<div class="col-sm-9">
													<label class='checkbox'>
														<input type="checkbox" name="ya_fast_pay_active" class="cls_ya_fastpay" value="1" <?php echo ($ya_fast_pay_active ? ' checked="checked"' : ''); ?>>
														<?php echo $fast_pay_enable_label; ?>
													</label>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3"><?= $fast_pay_id_label?></label>
												<div class='col-sm-8'>
													<input class='form-control' name="ya_fast_pay_id" value='<?php echo $ya_fast_pay_id; ?>'>
												</div>
												<div class='col-sm-8 col-sm-offset-3'>
													<p class="help-block"><?php echo ''; ?></p>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3"><?= $fast_pay_purpose_label?></label>
												<div class='col-sm-8'>
													<input class='form-control' name="ya_fast_pay_description" value='<?php echo $ya_fast_pay_description; ?>'>
												</div>
												<div class='col-sm-8 col-sm-offset-3'>
													<p class="help-block"><?php echo $fast_pay_desc_text; ?></p>
												</div>
											</div>

											<label class="control-label col-sm-3"><?php echo $fast_pay_os_label; ?></label>
											<div class='col-sm-8'>
												<select name="ya_fast_pay_os" id="ya_fast_pay_os" class="form-control">
													<?php foreach ($order_statuses as $order_status) { ?>
														<?php if ($order_status['order_status_id'] == $ya_fast_pay_os) { ?>
															<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
											</div>
											<div class='col-sm-8 col-sm-offset-3'>
												<p class="help-block"><?php echo $fast_pay_os_text?></p>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>

						<div class="clearfix">
							<button type="button" onclick="$('.fast_pay_form').submit(); return false;" value="1" class="btn btn-default">
								<i class="process-icon-save"></i> <?php echo $kassa_sv; ?>
							</button>
						</div>

					</div>
					<!-- End FastPay Tab -->
					<div class="tab-pane" id="tab-p2p">
						<?php foreach ($p2p_status as $p) { echo $p; } ?>
						<div class="">
							<div class="">
								<?php if($mod_status) { ?>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="p2p_form form-horizontal">
									<input type="hidden" value="p2p" name="type_data"/>
									<div class='row'>
										<div class='col-sm-12'>
											<p><?php echo $p2p_text_connect; ?></p>
											<div class='form-horizontal'>
												<div class="form-group">
													<div class="col-sm-9 col-sm-offset-3">
														<label class='checkbox'>
															<input type="checkbox" name="ya_p2p_active" class="cls_ya_kassamode" value="1" <?php echo ($ya_p2p_active ? ' checked="checked"' : ''); ?>>
															<?php echo $p2p_text_enable; ?>
														</label>
													</div>
												</div>
											</div>
											<div class='form-horizontal'>
												<div class="form-group">
													<label class="control-label col-sm-3">RedirectURL</label>
													<div class='col-sm-8'>
														<input class='form-control disabled' value='<?php echo $ya_p2p_linkapp; ?>' disabled>
													</div>
													<div class='col-sm-8 col-sm-offset-3'>
														<?php echo $p2p_text_url_help; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- row -->
									<div class='row'>
										<div class='col-sm-12'>
											<h4 class="form-heading"><?php echo $p2p_text_setting_head; ?></h4>
										</div>
										<div class='col-sm-12'>
												<div class="form-group">
													<label for="ya_p2p_number" class="col-sm-3 control-label"><?php echo $p2p_text_account; ?></label>
													<div class="col-sm-9">
														<input name="ya_p2p_number" value="<?php echo trim($ya_p2p_number); ?>" id="ya_p2p_number" class="form-control">
													</div>
												</div>
												<div class="form-group">
													<label for="ya_p2p_idapp" class="col-sm-3 control-label"><?php echo $p2p_text_appId; ?></label>
													<div class="col-sm-9">
														<input name="ya_p2p_idapp" value="<?php echo trim($ya_p2p_idapp); ?>" id="ya_p2p_idapp" class="form-control">
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-12"></div>
													<label for="ya_p2p_pw" class="col-sm-3 control-label"><?php echo $p2p_text_appWord; ?></label>
													<div class="col-sm-9">
														<input name="ya_p2p_pw" value="<?php echo trim($ya_p2p_pw); ?>" id="ya_p2p_pw" class="form-control">
													</div>
													<div class="col-sm-9 col-sm-offset-3">
														<?php echo $p2p_text_app_help; ?>
													</div>
												</div>
										</div>
									</div>
									<!-- row -->
									<div class='row'>
										<div class='col-sm-12'>
											<h4 class="form-heading"><?php echo $p2p_text_extra_head; ?></h4>
										</div>
										<div class='col-sm-12'>
											<div class='form-horizontal'>
												<div class="form-group">
													<label for="ya_p2p_log" class="col-sm-3 control-label"><?php echo $p2p_text_debug; ?></label>
													<div class="col-sm-9">
														<label class='radio-inline'>
															<input type="radio" <?php echo (!$ya_p2p_log ? ' checked="checked"' : ''); ?> name="ya_p2p_log" value="0"> <?php echo $p2p_text_off; ?>
														</label>
														<label class='radio-inline'>
															<input type="radio" <?php echo ($ya_p2p_log ? ' checked="checked"' : ''); ?> name="ya_p2p_log" value="1"> <?php echo $p2p_text_on; ?>
														</label>
														<p class="help-block"><?php echo $p2p_text_debug_help; ?></p>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3"><?php echo $p2p_text_status; ?></label>
													<div class='col-sm-8'>
														<select name="ya_p2p_os" id="ya_p2p_os" class="form-control">
															<?php foreach ($order_statuses as $order_status) { ?>
																<?php if ($order_status['order_status_id'] == $ya_p2p_os) { ?>
																<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																<?php } else { ?>
																<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- -->
								</form>
								<?php } else { ?>
								<div class="alert alert-danger">
									<i class="fa fa-exclamation-circle"></i>
									<?php echo $mod_off; ?>
									<button type="button" class="close" data-dismiss="alert">×</button>
								</div>
								<?php }?>
							</div>
							<?php if($mod_status) { ?>
							<div class="clearfix">
								<button type="button" onclick="$('.p2p_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $p2p_sv; ?>
								</button>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane" id="tab-metrika">
						<?php foreach ($metrika_status as $me) { echo $me; } ?>
						<div class="">
							<div class="">
								<?php echo $text_license; ?><br>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="metrika_form form-horizontal">
									<input type="hidden" value="metrika" name="type_data"/>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $active; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_metrika_active ? ' checked="checked"' : ''); ?> name="ya_metrika_active" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_metrika_active ? ' checked="checked"' : ''); ?> name="ya_metrika_active" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_number"><?php echo $metrika_number; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_number" value="<?php echo $ya_metrika_number; ?>" id="ya_metrika_number" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_idapp"><?php echo $metrika_idapp; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_idapp" value="<?php echo $ya_metrika_idapp; ?>" id="ya_metrika_idapp" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_pw"><?php echo $metrika_pw; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_pw" value="<?php echo $ya_metrika_pw; ?>" id="ya_metrika_pw" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_o2auth"><?php echo $metrika_o2auth; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_o2auth" value="<?php echo $ya_metrika_o2auth; ?>" disabled="disabled" id="ya_metrika_o2auth" class="form-control"/>
											<p class="help-block">
												<a href="<?php echo $ya_metrika_callback_url; ?>"><?php echo $metrika_gtoken; ?></a>
											</p>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $metrika_set; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_metrika_webvizor"><input type="checkbox" <?php echo ($ya_metrika_webvizor ? ' checked="checked"' : ''); ?> name="ya_metrika_webvizor" id="ya_metrika_webvizor" class="" value="1"/> <?php echo $metrika_set_1; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_clickmap"><input type="checkbox" <?php echo ($ya_metrika_clickmap ? ' checked="checked"' : ''); ?> name="ya_metrika_clickmap" id="ya_metrika_clickmap" class="" value="1"/> <?php echo $metrika_set_2; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_out"><input type="checkbox" <?php echo ($ya_metrika_out ? ' checked="checked"' : ''); ?> name="ya_metrika_out" id="ya_metrika_out" class="" value="1"/> <?php echo $metrika_set_3; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_otkaz"><input type="checkbox" <?php echo ($ya_metrika_otkaz ? ' checked="checked"' : ''); ?> name="ya_metrika_otkaz" id="ya_metrika_otkaz" class="" value="1"/> <?php echo $metrika_set_4; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_hash"><input type="checkbox" <?php echo ($ya_metrika_hash ? ' checked="checked"' : ''); ?> name="ya_metrika_hash" id="ya_metrika_hash" class="" value="1"/> <?php echo $metrika_set_5; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $metrika_celi; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_metrika_cart"><input type="checkbox" <?php echo ($ya_metrika_cart ? ' checked="checked"' : ''); ?> name="ya_metrika_cart" id="ya_metrika_cart" class="" value="1"/> <?php echo $celi_cart; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_order"><input type="checkbox"<?php echo ($ya_metrika_order ? ' checked="checked"' : ''); ?> name="ya_metrika_order" id="ya_metrika_order" class="" value="1"/> <?php echo $celi_order; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_callback"><?php echo $metrika_callback; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_callback" disabled="disabled" value="<?php echo $ya_metrika_callback; ?>" id="ya_metrika_callback" class="form-control"/>
										</div>
									</div>
								</form>
							</div>
							<div class="clearfix">
								<button type="button" onclick="$('.metrika_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $metrika_sv; ?>
								</button>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-market">
						<?php foreach ($market_status as $m) { echo $m; } ?>
						<div class="panel panel-default">
							<div class="panel-body">
							<?php echo $text_license; ?><br>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="market_form form-horizontal">
									<input type="hidden" value="market" name="type_data"/>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_market_shopname"><?php echo $market_s_name; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_market_shopname" value="<?php echo $ya_market_shopname; ?>" id="ya_market_shopname" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_prostoy; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_prostoy ? ' checked="checked"' : ''); ?> name="ya_market_prostoy" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_market_prostoy ? ' checked="checked"' : ''); ?> name="ya_market_prostoy" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $market_set; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_market_available"><input type="checkbox" <?php echo ($ya_market_available? ' checked="checked"' : ''); ?> name="ya_market_available" id="ya_market_available" class="" value="1"/> <?php echo $market_set_1; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_combination"><input type="checkbox" <?php echo ($ya_market_combination ? ' checked="checked"' : ''); ?> name="ya_market_combination" id="ya_market_combination" class="" value="1"/> <?php echo $market_set_3; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_features"><input type="checkbox" <?php echo ($ya_market_features ? ' checked="checked"' : ''); ?> name="ya_market_features" id="ya_market_features" class="" value="1"/> <?php echo $market_set_4; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_dimensions"><input type="checkbox" <?php echo ($ya_market_dimensions ? ' checked="checked"' : ''); ?> name="ya_market_dimensions" id="ya_market_dimensions" class="" value="1"/> <?php echo $market_set_5; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_allcurrencies"><input type="checkbox" <?php echo ($ya_market_allcurrencies ? ' checked="checked"' : ''); ?> name="ya_market_allcurrencies" id="ya_market_allcurrencies" class="" value="1"/> <?php echo $market_set_6; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_store"><input type="checkbox" <?php echo ($ya_market_store ? ' checked="checked"' : ''); ?> name="ya_market_store" id="ya_market_store" class="" value="1"/> <?php echo $market_set_7; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_delivery"><input type="checkbox" <?php echo ($ya_market_delivery ? ' checked="checked"' : ''); ?> name="ya_market_delivery" id="ya_market_delivery" class="" value="1"/> <?php echo $market_set_8; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_pickup"><input type="checkbox" <?php echo ($ya_market_pickup ? ' checked="checked"' : ''); ?> name="ya_market_pickup" id="ya_market_pickup" class="" value="1"/> <?php echo $market_set_9; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_dostup; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 1 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="1"/> <?php echo $market_dostup_1; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 2 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="2"/> <?php echo $market_dostup_2; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 3 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="3"/> <?php echo $market_dostup_3; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 4 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="4"/> <?php echo $market_dostup_4; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="">Товар в наличии</label>
										<div class="col-sm-8">
											<table class="table">
												<tr>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="ya_market_localcoast"><?php echo $market_d_cost; ?></label>
														<div class="col-sm-8">
															<input type="text" name="ya_market_localcoast" value="<?php echo $ya_market_localcoast; ?>"
																   id="ya_market_localcoast" class="form-control"/>
														</div>
													</div>
												</tr>
												<tr>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="ya_market_localdays"><?php echo $market_d_days; ?></label>
														<div class="col-sm-8">
															<input type="text" name="ya_market_localdays" value="<?php echo $ya_market_localdays; ?>"
																   id="ya_market_localdays" class="form-control"/>
														</div>
													</div>
												</tr>
											</table>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_market_localdays"><?php echo "Товара нет в наличии"; ?></label>
										<div class="col-sm-8">
											<table class="table">
												<tr>
													<th>Статус товара на складе</th>
													<th>Срок доставки</th>
													<th>Стоимость доставки </th>
												</tr>
												<?php
												foreach ($stockstatuses as $stock) {?>
													<tr>
														<td>
															<?php echo $stock['name']; ?>
														</td>
														<td>
															<input type="text" name="ya_market_stock_days[<?php echo $stock['id']; ?>]" value="<?php echo $ya_market_stock_days[$stock['id']]; ?>" class="form-control"/>
														</td>
														<td>
															<input type="text" name="ya_market_stock_cost[<?php echo $stock['id']; ?>]" value="<?php echo $ya_market_stock_cost[$stock['id']]; ?>" class="form-control"/>
														</td>
													</tr>
												<?php } ?>
											</table>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_color_option; ?></label>
										<div class="col-sm-8">
											<div class="scrollbox" style="height: 100px; overflow-x: auto; width: 100%;">
												<?php $class = 'odd'; ?>
												<?php foreach ($options as $option) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($option['option_id'], $ya_market_color_options)) { ?>
													<input type="checkbox" name="ya_market_color_options[]" value="<?php echo $option['option_id']; ?>" checked="checked" />
													<?php echo $option['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="ya_market_color_options[]" value="<?php echo $option['option_id']; ?>" />
													<?php echo $option['name']; ?>
													<?php } ?>
												</div>
												<?php } ?>
											</div>
											<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_size_option; ?><br/><?php echo $market_size_unit; ?></label>
										<div class="col-sm-8">
											<div class="scrollbox" style="height: 160px; overflow-x: auto; width: 100%;">
												<?php $class = 'odd'; ?>
												<?php foreach ($options as $option) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($option['option_id'], $ya_market_size_options)) { ?>
													<input type="checkbox" name="ya_market_size_options[]" value="<?php echo $option['option_id']; ?>" checked="checked" />
													<?php echo $option['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="ya_market_size_options[]" value="<?php echo $option['option_id']; ?>" />
													<?php echo $option['name']; ?>
													<?php } ?>
												</div>
												<?php } ?>
											</div>
											<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_out; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_catall ? ' checked="checked"' : ''); ?> name="ya_market_catall" value="1"/> <?php echo $market_out_all; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_market_catall ? ' checked="checked"' : ''); ?> name="ya_market_catall" value="0"/> <?php echo $market_out_sel; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $market_cat; ?></label>
										<div class="col-sm-8">
											<div class="panel panel-default">
												<div class="tree-panel-heading tree-panel-heading-controls clearfix">
													<div class="tree-actions pull-right">
														<a onclick="hidecatall($('#categoryBox')); return false;" id="collapse-all-categoryBox" class="btn btn-default">
															<i class="icon-collapse-alt"></i><?php echo $market_sv_all; ?>
														</a>
														<a onclick="showcatall($('#categoryBox')); return false;" id="expand-all-categoryBox" class="btn btn-default">
															<i class="icon-expand-alt"></i><?php echo $market_rv_all; ?>
														</a>
														<a onclick="checkAllAssociatedCategories($('#categoryBox')); return false;" id="check-all-categoryBox" class="btn btn-default">
															<i class="icon-check-sign"></i><?php echo $market_ch_all; ?>
														</a>
														<a onclick="uncheckAllAssociatedCategories($('#categoryBox')); return false;" id="uncheck-all-categoryBox" class="btn btn-default">
															<i class="icon-check-empty"></i><?php echo $market_unch_all; ?>
														</a>
													</div>
												</div>
												<ul id="categoryBox" class="tree">
													<?php echo $market_cat_tree; ?>
												</ul>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_market_dynamic"><?php echo $market_lnk_yml; ?></label>
										<div class="col-sm-8">
											<input type="hidden" name="ya_market_dynamic" value="<?php echo $ya_market_lnk_yml; ?>" id="ya_market_dynamic"/>
											<div disabled="disabled" class="form-control">
												<?php echo $ya_market_lnk_yml; ?>
											</div>
										</div>
									</div>
								</form>
								<div class="panel-footer clearfix">
									<button type="button" onclick="$('.market_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
										<i class="process-icon-save"></i> <?php echo $market_sv; ?>
									</button>
									<!-- <button type="submit" class="btn btn-default btn btn-default" name="generatemanual"><i class="process-icon-refresh"></i> <?php echo $market_gen; ?></button> -->
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-pokupki">
						<?php foreach ($pokupki_status as $po) { echo $po; } ?>
						<div class="panel panel-default">
							<div class="panel-body">
								<?php echo $text_license; ?><br>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="pokupki_form form-horizontal">
									<input type="hidden" value="pokupki" name="type_data"/>
									<input type="hidden" name="ya_pokupki_yapi" value="https://api.partner.market.yandex.ru/v2/" id="ya_pokupki_yapi"/>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_stoken"><?php echo $pokupki_stoken; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_stoken" value="<?php echo $ya_pokupki_stoken; ?>" id="ya_pokupki_stoken" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_number"><?php echo $pokupki_number; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_number" value="<?php echo $ya_pokupki_number; ?>" id="ya_pokupki_number" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_idapp"><?php echo $pokupki_idapp; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_idapp" value="<?php echo $ya_pokupki_idapp; ?>" id="ya_pokupki_idapp" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_pw"><?php echo $pokupki_pw; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_pw" value="<?php echo $ya_pokupki_pw; ?>" id="ya_pokupki_pw" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_callback"><?php echo $pokupki_callback; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_callback" disabled="disabled" value="<?php echo $ya_pokupki_callback; ?>" id="ya_pokupki_callback" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_idpickup"><?php echo $pokupki_idpickup; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_idpickup" value="<?php echo $ya_pokupki_idpickup; ?>" id="ya_pokupki_idpickup" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_sapi"><?php echo $pokupki_sapi; ?></label>
										<div class="col-sm-8">
											<input type="text" disabled="disabled" name="ya_pokupki_sapi" value="<?php echo $ya_pokupki_sapi; ?>" id="ya_pokupki_sapi" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $pokupki_method; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_pokupki_yandex"><input type="checkbox" <?php echo ($ya_pokupki_yandex ? ' checked="checked"' : ''); ?> name="ya_pokupki_yandex" id="ya_pokupki_yandex" class="" value="1"/> <?php echo $pokupki_set_1; ?></label>
											</div>
											<!--<div class="checkbox">
												<label for="ya_pokupki_sprepaid"><input type="checkbox" <?php echo ($ya_pokupki_sprepaid ? ' checked="checked"' : ''); ?> name="ya_pokupki_sprepaid" id="ya_pokupki_sprepaid" class="" value="1"/> <?php echo $pokupki_set_2; ?></label>
											</div>-->
											<div class="checkbox">
												<label for="ya_pokupki_cash"><input type="checkbox" <?php echo ($ya_pokupki_cash ? ' checked="checked"' : ''); ?> name="ya_pokupki_cash" id="ya_pokupki_cash" class="" value="1"/> <?php echo $pokupki_set_3; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_pokupki_bank"><input type="checkbox" <?php echo ($ya_pokupki_bank ? ' checked="checked"' : ''); ?> name="ya_pokupki_bank" id="ya_pokupki_bank" class="" value="1"/> <?php echo $pokupki_set_4; ?></label>
											</div>
										</div>
									</div>
									<!-- -->
									<div class="form-horizontal">
										<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $pokupki_text_status; ?></label>
										<div class="col-sm-8">
											<?php foreach(array('pickup','cancelled','delivery','processing','unpaid','delivered') as $val){?>
												<div class="">
													<label class="control-label col-sm-5"><?php echo ${'pokupki_text_status_'.$val}; ?></label>
													<div class='col-sm-7'>
														<select name="ya_pokupki_status_<?php echo $val; ?>" id="ya_pokupki_status_<?php echo $val; ?>" class="form-control">
															<?php foreach ($order_statuses as $order_status) { ?>
																<?php if ($order_status['order_status_id'] == ${'ya_pokupki_status_'.$val}) { ?>
																	<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
																<?php } else { ?>
																	<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
																<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>
											<?php }?>
											</div>
										</div>
									</div>
									<!-- -->
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_token"><?php echo $pokupki_token; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_token" value="<?php echo $ya_pokupki_gtoken; ?>" id="ya_pokupki_token" disabled="disabled" class="form-control"/>
											<p class="help-block">
												<a href="<?php echo $ya_pokupki_callback_url; ?>"><?php echo $pokupki_gtoken; ?></a>
											</p>
										</div>
									</div>
									<?php echo $data_carrier ?>
								</form>
							</div>
							<div class="panel-footer clearfix">
								<button type="button" onclick="$('.pokupki_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $pokupki_sv; ?>
								</button>
							</div>
						</div>
					</div>
				</div>
	</div>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
var step = new Array();
var total = 0;
	triggerInvoiceSetting();
	function triggerInvoiceSetting(){
		if($('input[name=ya_kassa_inv]:checked').val()==1){
			$('.invoice-setting').show();
		}else{
			$('.invoice-setting').hide();
		}
	}
$('input[name=ya_kassa_inv]').bind('click', triggerInvoiceSetting);
$('#mws_csr_gen').bind('click', function(e) {
	if (confirm('<?php echo $lbl_mws_alert; ?>')) {
		e.preventDefault();
		e.stopPropagation();
		$.ajax({
			url: 'index.php?route=tool/mws/generate&token=<?php echo $token; ?>',
			cache: false,
			success: function(json) {
				location.reload();
			}
		});
	}
});
$('#mws_crt_load').on('click', function() {
	$('#form-upload').remove();
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			$('.alert').remove();
			$.ajax({
				url: 'index.php?route=tool/mws/upload&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#mws_crt_load').button('loading');
				},
				complete: function() {
					$('#mws_crt_load').button('reset');
				},
				success: function(json) {
					if (!json.error){
						$('#mws_form').submit();
					} else {
						$('#mws_form').prepend("<div class='alert alert-danger'>"+ json.error +"</div>");
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

</script>

<script type="text/javascript">
    function hideTaxes() {
        var inside = $('input[name="ya_kassa_send_check"]:checked').val();

        if (inside == 1)
        {
            $('.taxesArea').slideDown('slow');
        } else {
            $('.taxesArea').slideUp('slow');
        }
    }

function showcatall($tree)
{
$tree.find("ul.tree").each(
	function()
	{
		$(this).slideDown();
	}
);
}

function hidecatall($tree)
{
$tree.find("ul.tree").each(
	function()
	{
		$(this).slideUp();
	}
);
}
function checkAllAssociatedCategories($tree)
{
$tree.find(":input[type=checkbox]").each(
	function()
	{
		$(this).prop("checked", true);
		$(this).parent().addClass("tree-selected");
	}
);
}

function uncheckAllAssociatedCategories($tree)
{
$tree.find(":input[type=checkbox]").each(
	function()
	{
		$(this).prop("checked", false);
		$(this).parent().removeClass("tree-selected");
	}
);
}

$(document).ready(function(){
	$('.nav-tabs a:first').tab('show');
	var view = $.totalStorage('tab_ya');
	if(view == null)
		$.totalStorage('tab_ya', 'tab-kassa');
	else
		$('.nav-tabs li a[href="#'+ view +'"]').click();

	$('.nav-tabs li').click(function(){
		var view = $(this).find('a').first().attr('href').replace('#', '');
		$.totalStorage('tab_ya', view);
	});

	$('.tree-item-name label').click(function(){
		$(this).parent().find('input').click();
	});

    hideTaxes();
	$('input[name="ya_kassa_send_check"]').change(function(){
	    hideTaxes();
    });

	$('.tree-folder-name input').change(function(){
		if ($(this).prop("checked"))
		{
			$(this).parent().addClass("tree-selected");
			$(this).parents('.tree-folder').first().find('ul input').prop("checked", true).parent().addClass("tree-selected");
		}
		else
		{
			$(this).parent().removeClass("tree-selected");
			$(this).parents('.tree-folder').first().find('ul input').prop("checked", false).parent().removeClass("tree-selected");
		}
	});

	$('.tree-toggler').click(function(){
		$(this).parents('.tree-folder').first().find('ul').slideToggle('slow');
	});

	$('.tree input').change(function(){
		if ($(this).prop("checked"))
		{
			$(this).parent().addClass("tree-selected");
		}
		else
		{
			$(this).parent().removeClass("tree-selected");
		}
	});
	//
	var market_cat = JSON.parse('[<?php echo $ya_market_categories; ?>]');
	console.log(market_cat);
	for (i in market_cat)
		$('#categoryBox input[value="'+ market_cat[i] +'"]').prop("checked", true).change();
    // Show/hide payment methods for some modes
    var $funcMode = function (){
        if ($(":input[name=ya_kassa_paymode][type=radio]:checked").val() == '1') {
            $(".kassa-wo-select").show();
            $(".kassa-w-select").hide();
        } else {
            $(".kassa-w-select").show();
            $(".kassa-wo-select").hide();
        }
    };
    $funcMode.call();
    $(":input[name=ya_kassa_paymode][type=radio]").click($funcMode);
});
</script>
<script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter27746007 = new Ya.Metrika({ id:27746007 }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script>
<noscript><div><img src="//mc.yandex.ru/watch/27746007" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<style>
.bootstrap .tree-panel-heading-controls {
    line-height: 2.2em;
    font-size: 1.1em;
    color: #00aff0;
}

.bootstrap .tree-panel-heading-controls i {
    font-size: 14px;
}

.bootstrap .tree {
    list-style: none;
    padding: 0 0 0 20px;
}

.bootstrap .tree input {
    vertical-align: baseline;
    margin-right: 4px;
    line-height: normal;
}

.bootstrap .tree i {
    font-size: 14px;
}

.bootstrap .tree .tree-item-name,.bootstrap .tree .tree-folder-name {
    padding: 2px 5px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
}

.bootstrap .tree .tree-item-name label,.bootstrap .tree .tree-folder-name label {
    font-weight: 400;
}

.bootstrap .tree .tree-item-name:hover,.bootstrap .tree .tree-folder-name:hover {
    background-color: #eee;
    cursor: pointer;
}

.bootstrap .tree .tree-selected {
    color: #fff;
    background-color: #00aff0;
}

.bootstrap .tree .tree-selected:hover {
    background-color: #009cd6;
}

.bootstrap .tree .tree-selected i.tree-dot {
    background-color: #fff;
}

.bootstrap .panel-footer {
	height: 73px;
	border-color: #eee;
	background-color: #fcfdfe;
}

.bootstrap .tree i.tree-dot {
    display: inline-block;
    position: relative;
    width: 6px;
    height: 6px;
    margin: 0 4px;
    background-color: #ccc;
    -webkit-border-radius: 6px;
    border-radius: 6px;
}

.bootstrap .tree .tree-item-disable,.bootstrap .tree .tree-folder-name-disable {
    color: #ccc;
}

.bootstrap .tree .tree-item-disable:hover,.bootstrap .tree .tree-folder-name-disable:hover {
    color: #ccc;
    background-color: none;
}

.bootstrap .tree-actions {
    display: inline-block;
}

.bootstrap .tree-panel-heading-controls {
    padding: 5px;
    border-bottom: solid 1px #dfdfdf;
}

.bootstrap .tree-actions .twitter-typeahead {
    padding: 0 0 0 4px;
}

.bootstrap #categoryBox {
	padding: 10px 5px 5px 20px;
}

.bootstrap .tree-panel-label-title {
    font-weight: 400;
    margin: 0;
    padding: 0 0 0 8px;
}

.scrollbox > div {
	height: 23px;
}
</style>
<script type="text/javascript" src="view/javascript/jquery.total-storage.js"></script>