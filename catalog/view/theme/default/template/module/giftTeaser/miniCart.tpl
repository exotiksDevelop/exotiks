<?php if($data['giftTeaser']['Enabled'] == 'yes' && !empty($gifts)) { ?>
<div class="giftTeaserMiniCart">
	<h2>Gifts!</h2>
	<table style="background: yellow; width:100%" ><?php foreach ($gifts as $key => $gift) { ?> 
		<tr><td class="image">
			<?php if($gift['image']) { ?>
	             	<a href="index.php?route=product/product&product_id=<?php echo $gift['item_id']?>">
			           	<img src="<?php echo $gift['image']; ?>" alt="<?php echo $gift['name']; ?>" title="<?php echo $gift['name']; ?>" style="border: 1px solid #EEEEEE"
		            </a>	      
		    <?php } ?>
		</td>	
	     <td class="name">
	     	<a href="index.php?route=product/product&product_id=<?php echo $gift['item_id']?>"><?php echo $gift['name']?></a>
	     </td>
	     <td class="quantity">x&nbsp;1</td>
	    <tr>
	   <?php } ?>
	</table>
</div>
<?php } ?>