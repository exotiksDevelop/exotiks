<?php if($show_title) { ?>
<h3><?php echo $show_icon ? '<i class="fa fa-newspaper-o fa-3x"></i>&nbsp;' : ''; ?><?php echo $heading_title; ?></h3>
<?php } ?>
<div class="row">
	<?php foreach ($news as $news_item) { ?>
	<div class="product-layout col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="product-thumb transition">
			<?php if($news_item['thumb']) { ?>
			<div class="image"><a href="<?php echo $news_item['href']; ?>"><img src="<?php echo $news_item['thumb']; ?>" alt="<?php echo $news_item['title']; ?>" title="<?php echo $news_item['title']; ?>" class="img-responsive" /></a></div>
			<?php } ?>
			<div class="caption">
				<h4><a href="<?php echo $news_item['href']; ?>"><?php echo $news_item['title']; ?></a></h4>
				<p><?php echo $news_item['description']; ?></p>
			</div>
			<div class="button-group">
				<button onclick="location.href = ('<?php echo $news_item['href']; ?>');" data-toggle="tooltip" title="<?php echo $text_more; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_more; ?></span></button>
				<button type="button" data-toggle="tooltip" title="<?php echo $news_item['posted']; ?>"><i class="fa fa-clock-o"></i></button>
				<button type="button" data-toggle="tooltip" title="<?php echo $news_item['viewed']; ?>"><i class="fa fa-eye"></i></button>
			</div>
		</div>
	</div>
	<?php } ?>
</div>