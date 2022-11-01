<?php if(!empty($custom_css)): ?>
	<style>
		<?php echo htmlspecialchars_decode($custom_css); ?>
    </style>
<?php endif; ?>
	<?php if ($featured_post!== false && $featured=='yes') { ?>
    	<div class="panel panel-default iblog-widget">
			<div class="panel-heading"><div class="bundle-title"><a href="<?php echo $featured_post['href']; ?>"><?php echo $featured_post['title']; ?></a></div></div>
			<div class="panel-body iblog-box-content">
				<?php if (!empty($featured_post['image'])) : ?>
					<a href="<?php echo $featured_post['href']; ?>"><img src="<?php echo $featured_post['image']; ?>" class="iblog-featured-image" alt="<?php echo $featured_post['title']; ?>" /></a>
				<?php endif; ?>
                <div class="iblog-featured-description">
                	<?php echo $featured_post['excerpt']; ?>
                </div>
                <div class="iblog-button">
                	<a href="<?php echo $featured_post['href']; ?>" class="btn btn-primary"><?php echo $iblog_button; ?></a>
                </div>
			</div>
		</div>
	<?php } ?>

<div class="panel panel-default iblog-panel">
	<div class="panel-heading"><?php echo $heading_title; ?></div>
	<div class="panel-content iblog-box-content">
        <?php if (!empty($posts)) { ?>
			<ul class="iblog-box-post">
				<?php foreach ($posts as $post) { ?>
					<li>
						<div class="iblog-post">
							<a href="<?php echo $post['href']; ?>"<?php echo ($post['post_id'] == $post_id) ? ' class="active"' : ''; ?>><?php echo $post['title']; ?></a>
						</div>
					</li>
				<?php } ?>
			</ul>
		<?php } else { ?>
				<div class="iblog-noposts"><?php echo $no_posts; ?></div>
		<?php } ?>
	</div>
</div>