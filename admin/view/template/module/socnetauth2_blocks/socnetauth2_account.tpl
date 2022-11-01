<?php if( !empty( $socnetauth2_socnets ) ) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/socnetauth2.css" />
<?php /* ==================== Крупные значки таблицей ======================= */ ?>
<?php if( $socnetauth2_format == 'kvadrat' ) { ?>
	<style>
	a.socnetauth2_buttons:hover img
	{
		opacity: 0.8;
	}
	</style>	
			
		<?php if( count($socnetauth2_socnets)<5 ) { ?>
		<table>
			<tr>
				<?php if( !empty($socnetauth2_socnets[0]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[0]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[0]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[1]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[1]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[1]['short']; ?>45.png"></a></td><?php } ?>
			</tr>
			<tr>
				<?php if( !empty($socnetauth2_socnets[2]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[2]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[2]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[3]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[3]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[3]['short']; ?>45.png"></a></td><?php } ?>
			</tr>
		</table>	
		<?php } else { ?>
		
		<table>
			<tr>
				<?php if( !empty($socnetauth2_socnets[0]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[0]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[0]['short']; ?>45.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[1]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[1]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[1]['short']; ?>45.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[2]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[2]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[2]['short']; ?>45.png"></a></td><?php } ?>
				
				
			</tr>
			<tr>
				<?php if( !empty($socnetauth2_socnets[3]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[3]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[3]['short']; ?>45.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[4]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[4]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[4]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[5]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[5]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?><?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[5]['short']; ?>45.png"></a></td><?php } ?>
			</tr>
		</table>	
		<?php } ?>
<?php } ?>
<?php /* ====================  / END Крупные значки таблицей ======================= */ ?>

<?php /* ==================== Кнопка Логинзы ============== */ ?>
<?php if( $socnetauth2_format == 'bline' ) { ?>
<div class="content account_socnetauth2_bline_content" style="min-height: 0px;">
	<style>
	a.socnetauth2_buttons:hover img
	{
		opacity: 0.8;
	}
	</style>	
	#socnetauth2_label#	
	<div  class="account_socnetauth2_bline_links">
		<table>
			<tr>
				<?php if( !empty($socnetauth2_socnets[0]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[0]['key']; ?>.php?first=1"><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[0]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[1]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[1]['key']; ?>.php?first=1"><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[1]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[2]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[2]['key']; ?>.php?first=1"><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[2]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[3]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[3]['key']; ?>.php?first=1"><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[3]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[4]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[4]['key']; ?>.php?first=1"><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[4]['short']; ?>45.png"></a></td><?php } ?>
				<?php if( !empty($socnetauth2_socnets[5]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[5]['key']; ?>.php?first=1"><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[5]['short']; ?>45.png"></a></td><?php } ?>
			</tr>
		</table>
	</div>
</div>			
<?php } ?>
		  
<?php /* ====================  / END Кнопка Логинзы ============== */ ?>		  

<?php /* ==================== Маленькие иконки ============== */ ?>
		  
<?php if( $socnetauth2_format == 'lline' ) { ?>
<div class="content account_socnetauth2_icons_content" style="min-height: 0px;">
	<div  class="account_socnetauth2_lline_links">
		<table>
			<tr>
				<td style="padding-right: 10px; padding-top: 10px;">#socnetauth2_label#</td>
				<?php if( !empty($socnetauth2_socnets[0]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[0]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[0]['short']; ?>16.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[1]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[1]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[1]['short']; ?>16.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[2]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[2]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[2]['short']; ?>16.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[3]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[3]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[3]['short']; ?>16.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[4]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[4]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[4]['short']; ?>16.png"></a></td><?php } ?>
				
				<?php if( !empty($socnetauth2_socnets[5]) ) { ?><td style="padding-right: 10px; padding-top: 10px;"><a class="socnetauth2_buttons" 
				href="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/<?php echo $socnetauth2_socnets[5]['key']; ?>.php?first=1"
				><img src="<?php echo $socnetauth2_shop_folder; ?>/socnetauth2/icons/<?php echo $socnetauth2_socnets[5]['short']; ?>16.png"></a></td><?php } ?>
			</tr>
		</table>
	</div> 
</div>
<?php } ?>
<?php /* ==================== / END Маленькие иконки ============== */ ?>
<?php } ?>