<?php echo $header; ?>
<div class="container">
	 <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left"> 
                <?php echo $column_left; ?>
            </div>
        </div>
    <div class="col-md-8 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
		<div id="content" class="">
			<h1><?php echo $heading_title; ?></h1>
			<?php if ($news_list) { ?>
			
			<div class="row">
				<?php foreach ($news_list as $news_item) { ?>
				<div class="product-layout product-list col-xs-12">
					
							<div class="caption">
								<h4><a href="<?php echo $news_item['href']; ?>"><?php echo $news_item['title']; ?></a></h4>
                                <p><?php echo $news_item['posted']; ?></p>
								
                                <?php echo $news_item['viewed']; ?>
							</div>
							<div class="product-thumb">
						<?php if($news_item['thumb']) { ?>
						<div class="image"><a href="<?php echo $news_item['href']; ?>"><img src="<?php echo $news_item['thumb']; ?>" alt="<?php echo $news_item['title']; ?>" title="<?php echo $news_item['title']; ?>" class="img-responsive" /></a></div>
						<?php }?>
						<div>
							<div class="button-group">
								<button type="button" onclick="location.href = ('<?php echo $news_item['href']; ?>');" data-toggle="tooltip" title="<?php echo $text_more; ?>"><i class="fa fa-share"></i>&nbsp;<span class="hidden-xs hidden-sm hidden-md"><?php echo $text_more; ?></span></button>
								</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
				<div class="col-sm-6 text-right"><?php echo $results; ?></div>
			</div>
			<?php } else { ?>
			<p><?php echo $text_empty; ?></p>
			<div class="buttons">
				<div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
</div>
<?php echo $footer; ?>