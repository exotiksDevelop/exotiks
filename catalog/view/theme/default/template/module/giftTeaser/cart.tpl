<?php $firstLine = true; foreach ($gifts as $key => $gift) { ?> 
	<tr style="background:#FFFDDB; <?php if($firstLine){ ?> border-top: 1px solid;<?php } $firstLine = false;?>" >
		<td class="image">
			<?php if($gift['image']) { ?>
	       	<a href="index.php?route=product/product&product_id=<?php echo $gift['item_id']?>">
				<img src="<?php echo $gift['image']; ?>" alt="<?php echo $gift['name']; ?>" title="<?php echo $gift['name']; ?>" />
	         </a>
	         <?php } ?>
	   	</td>
		<td class="name"><a href="index.php?route=product/product&product_id=<?php echo $gift['item_id']?>"><?php echo $gift['name']; ?></a></td>
		<td class="model"><?php echo $gift['model']; ?></td>
		<td calss="quantity">x1
			<a href="<?php echo $catalogUrl;?>index.php?route=module/giftTeaser/removeGiftFromCart&amp;gift_id=<?php echo $gift['gift_id']; ?>">
				<img src="catalog/view/theme/default/image/remove.png" alt="Remove" title="Remove">
			</a>
		</td>
		<td class="price">Gift</td>
		<td class="price">Gift</td>
	</tr>
<?php } ?>