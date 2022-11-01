<?php  $firstLine = true; foreach ($gifts as $key => $gift) { ?> 
	<tr style="background:#FFFDDB; <?php if($firstLine){ ?> border-top: 1px solid;<?php } $firstLine = false;?>" >
		<td class="name"><a href="index.php?route=product/product&product_id=<?php echo $gift['item_id']?>"><?php echo $gift['name']; ?></a></td>
		<td class="model"><?php echo $gift['model']; ?></td>
		<td calss="quantity" style="text-align: right;">1</td>
		<td class="price">Gift</td>
		<td class="price">Gift</td>
	</tr>
<?php } ?>
