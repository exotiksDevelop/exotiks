<!-- Samdev http://free-it.ru -->
<?php echo $header; ?>
<div class="container">
  	<div class="row">
		<div class="col-xs-12">
			<div class="content-in">
				<div class="row">
				  	<?php echo $column_left; ?>
				    <?php if ($column_left && $column_right) { ?>
				    <?php $class = 'col-sm-6'; ?>
				    <?php } elseif ($column_left || $column_right) { ?>
				    <?php $class = 'col-sm-9'; ?>
				    <?php } else { ?>
				    <?php $class = 'col-sm-12'; ?>
				    <?php } ?>
				    <div id="content" class="<?php echo $class; ?>">
						<ul class="breadcrumb">
							<?php foreach ($breadcrumbs as $breadcrumb) { ?>
							<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
							<?php } ?>
						</ul>
						<?php echo $content_top; ?>
				      	<h2 class="page-title"><?php echo $heading_title; ?></h2>
					  	<p class="text-pub">
					  		<?php if ($image) { ?>
					  			<img class="single-image pull-left" src="<?php echo $image; ?>" alt="<?php echo $heading_title; ?>" />
					  		<?php } ?>
					  		<?php echo $description; ?>
					  	</p>
					  	<p class="date-pub"><?php echo $date_added; ?></p>
					  <?php echo $content_bottom; ?>
					</div>
				    <?php echo $column_right; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?> 