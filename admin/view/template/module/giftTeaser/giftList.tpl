<div>
	<table>
		<thead id="giftListHead">
			<tr>
				<td class="left">
					<?php if ($sort == 'pd.name') { ?>
						<a href="<?php echo $name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_product; ?></a>
					<?php } else { ?>
						<a href="<?php echo $name; ?>"><?php echo $text_product; ?></a>
					<?php } ?>
				</td>
				<td class="left" width="40px">
					<div data-toggle="tooltip" data-placement="top" title="<?php echo $tooltip_quantity?>">
						<?php if ($sort == 'p.quantity') { ?>
							<a href="<?php echo $quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_quantity ?></a>
						<?php } else { ?>
							<a href="<?php echo $quantity; ?>"><?php echo $text_quantity ?></a>
						<?php } ?>
					</div>
				</td>	
				<td class="left" width="160px">
					<div data-toggle="tooltip" data-placement="top" title="<?php echo $text_condition; ?>">
						<?php if ($sort == 'gt.condition_type') { ?>
							<a href="<?php echo $condition_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_condition; ?></a>
						<?php } else { ?>
							<a href="<?php echo $condition_type; ?>"><?php echo $text_condition; ?></a>
						<?php } ?>
					</div>
				</td>
				<td class="left" width="192px">
					<div data-toggle="tooltip" data-placement="top" title="<?php echo $tooltip_start_date; ?>">
						<?php if ($sort == 'gt.start_date') { ?>
							<a href="<?php echo $start_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_start_date; ?></a>
						<?php } else { ?>
							<a href="<?php echo $start_date; ?>"><?php echo $text_start_date; ?></a>
						<?php } ?>
					</div>
				</td>

				<td class="left" width="192px">
					<div data-toggle="tooltip" data-placement="top" title="<?php echo $tooltip_end_date; ?>">
						<?php if ($sort == 'gt.end_date') { ?>
							<a href="<?php echo $end_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_end_date; ?></a>
						<?php } else { ?>
							<a href="<?php echo $end_date; ?>"><?php echo $text_end_date; ?></a>
						<?php } ?>
					</div>
				</td>
				<td class="left" width="20px">
					<div data-toggle="tooltip" data-placement="top" title="<?php echo $tooltip_sort_order; ?>">
						<?php if ($sort == 'gt.sort_order') { ?>
							<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_sort_order; ?></a>
						<?php } else { ?>
							<a href="<?php echo $sort_order; ?>"><?php echo $text_sort_order; ?></a>
						<?php } ?>
					</div>
				</td>
				<td width="160px"><?php echo $text_actions; ?></td>
			</tr>
		</thead>
		<tbody id="giftList">
			<?php $giftRow = 0; if(!empty($gifts)){
				foreach($gifts as $gift) { ?>
				<tr class="ListRow" id="<?php echo $gift['gift_id']; ?>">
					<td>
						<a href="../index.php?route=product/product&product_id=<?php echo $gift['product_id'];?>" target="_blank" data-image="<?php echo $gift['image']; ?>" id="item_<?php echo $gift['gift_id']?>">
							<span><?php echo $gift['name']; ?></span>
						</a>
					</td>
					<td class="left"><span><?php echo $gift['quantity']; ?></span></td>
					<td class="left condition"><span><?php echo $gift['condition_type']; ?></span></td>
					<td class="left startDate"><span><?php echo date('Y-m-d H:i', $gift['start_date']); ?></span></td>
					<td class="left endDate"><span><?php echo date('Y-m-d H:i', $gift['end_date']); ?></span></td>
					<td class="left sortOrder"><span><?php echo $gift['sort_order']; ?></span></td>
					<td class="left">
						<a class="btn btn-warning btn-sm editGift" gift-id="<?php echo $gift['gift_id']; ?>" item-id="<?php echo $gift['item_id']; ?>" item-name="<?php echo $gift['name']?>" onclick="editItem($(this).attr('gift-id'), $(this).attr('item-id'),$(this).attr('item-name') );"><?php echo $button_edit; ?></a> 
						<a class="btn btn-danger btn-sm removeGift" data-toggle="confirmation-singleton" data-placement="left" data-original-title="" title="" gift-id="<?php echo $gift['gift_id']; ?>" onclick="removeGift($(this), false);"><?php echo $button_remove; ?></a> 
					</td>
				</tr>
			<?php $giftRow++; }} else { ?>
				<tr class="noResults">
					<td colspan="9" class="center">Нет результатов!</td>
				</tr>
			<?php }?>
		</tbody>
	</table>
    <?php echo $pagination; ?>
</div>
