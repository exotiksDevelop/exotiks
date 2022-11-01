<div class="news_block">
<?php if($show_title) { ?>
<h2><?php echo $show_icon ? '<i class="fa fa-newspaper-o fa-3x"></i>&nbsp;' : ''; ?><?php echo $heading_title; ?></h3>
<?php } ?>
<div class="news_item">
	<?php foreach ($news as $news_item) { ?>	
			<?php if($news_item['thumb']) { ?>
			<?php } ?>
			<div class="caption">
				<h3><a href="<?php echo $news_item['href']; ?>"><?php echo $news_item['title']; ?></a></h3>
				<p><?php echo $news_item['description']; ?></p>
			</div>
			
		
	<?php } ?>
</div>
</div>