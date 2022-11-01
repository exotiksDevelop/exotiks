<?php if(isset($data['Enabled']) && $data['Enabled'] == 'yes' && !empty($gifts)) {  ?>
<style type="text/css">
	<?php if(isset($data['customDesign']) && $data['customDesign'] == 'custom'){ ?>
		.giftTeaserWidget.gt-custom {
			background: <?php if(isset($data['BackgroundColor'])){ echo $data['BackgroundColor'];}?>;
			border: 1px solid <?php if(isset($data['BorderColor'])){ echo $data['BorderColor'];}?>;
			color: <?php if(isset($data['FontColor'])){ echo $data['FontColor'];}?>;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			-khtml-border-radius: 5px;
			border-radius: 5px;
		}
		.giftTeaserWidget.gt-custom .panel-heading {
			background-color: <?php if(isset($data['headingBackground'])){ echo $data['headingBackground'];  }?>;
			color: <?php if(isset($data['FontColor'])){ echo $data['FontColor'];}?>;
			border: 1px solid <?php if(isset($data['BorderColor'])){ echo $data['BorderColor'];}?>
		}
		<?php }
		if(isset($data['customCss'])) {
			echo $data['customCss'];
		}
	?>
</style>
<div class="panel panel-default giftTeaserWidget <?php if(isset($data['customDesign']) && $data['customDesign'] == 'custom'){ echo "gt-custom";}?>">
    <div class="<?php if(isset($data['widget']) && $data['widget'] == 'no') echo 'panel-heading-custom'; else echo 'panel-heading';?>"><?php echo html_entity_decode($data['headtitle_' . $language]);?></div>
    <div class="<?php if(isset($data['widget']) && $data['widget'] == 'no') echo 'panel-content-custom'; else echo 'panel-content';?>"> 
		<div class="notificationMessage"><?php echo html_entity_decode($data['notification_' . $language]);?></div>
		<div class="box-product">
			<?php   foreach($gifts as $key => $gift) {?>
				<div class="gift">
					<div class="image">
						<a href="<?php echo $gift['url']; ?>"><img src="<?php echo $gift['image']; ?>" alt="<?php echo $gift['name']; ?>" /></a>
					</div>
					 <div class="name">
						<a href="<?php echo $gift['url']; ?>"><?php echo $gift['name']; ?></a>							
					 </div>
					 <div class="gt-description"><?php echo $gift['description']; ?></div>
				</div>
			<?php } ?>
		</div>
    </div>
</div> 
<?php } ?>