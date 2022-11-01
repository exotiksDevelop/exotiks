<?php echo $header; ?>
    <?php if(!empty($moduleData['CustomListingCSS'])): ?>
        <style>
            <?php echo htmlspecialchars_decode($moduleData['CustomListingCSS']); ?>
        </style>
    <?php endif; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php echo $content_top; ?>
    <div class="iblog-header">
        <div class="iblog-search">
            <div class="input-group">
                <input type="text" name="search" value="" class="search-field form-control" placeholder="<?php echo $search_placeholder; ?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" id="iblog-search-button" type="button"><?php echo $search_button; ?></button>
                </span>
            </div>
        </div>
        <h2><?php echo $heading_title; ?></h2>
    </div>
	<div class="iblog-clear"></div>
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
			<?php if (isset($posts)) { ?>
				<div class="iblog-filter row">
                	<div class="col-md-7">
                    </div>
					<div class="form-group col-md-3">
                    	<label for="iblog-sort" class="col-sm-4 control-label"><?php echo $text_sort; ?></label>
                    	<div class="col-sm-8">
                      		<select class="form-control" id="iblog-sort" onchange="location = this.value;">
                                <?php foreach ($sorts as $sorts) { ?>
                                    <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                                        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                      		</select>
                    	</div>
                    </div>
                	<div class="form-group col-md-2">
                    	<label for="iblog-limit" class="col-sm-4 control-label"><?php echo $text_limit; ?></label>
                    	<div class="col-sm-8">
                      		<select class="form-control" id="iblog-limit" onchange="location = this.value;">
                                <?php foreach ($limits as $limits) { ?>
                                    <?php if ($limits['value'] == $limit) { ?>
                                        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                    	</div>
                  	</div>

				</div>
				<div class="iblog-posts-list">
					<?php foreach ($posts as $post) { ?>
						<div>
                      		<?php if ($post['image']) { ?>
                      			<div class="image"><a href="<?php echo $post['href']; ?>"><img src="<?php echo $post['image']; ?>" title="<?php echo $post['title']; ?>" alt="<?php echo $post['title']; ?>" /></a></div>
                      		<?php } ?>
                      		<div class="right">
                        		<div class="name"><a href="<?php echo $post['href']; ?>"><?php echo $post['title']; ?></a></div>
                        		<div class="description"><?php echo $post['excerpt']; ?></div>
                        		<div class="iblog-button"><a href="<?php echo $post['href']; ?>" class="btn btn-primary"><?php echo $iblog_button; ?></a></div>
                      		</div>
                    	</div>
                    <?php } ?>
				</div>
				<div class="row iblog-pagination">
					<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
					<div class="col-sm-6 text-right"><?php echo $results; ?></div>
				</div>
			<?php } ?>
			<?php if (!isset($posts)) { ?>
  				<div class="content"> <?php echo $text_iblog_empty; ?> </div>
  				<div class="pull-right">
    				<div class="right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
  				</div>
  			<?php } ?>
        </div>
  		<?php echo $content_bottom; ?>
	</div>
    <?php echo $column_right; ?>
</div>
<script>
$(document).ready(function() {
	/*  */
	$('.iblog-search button[id=\'iblog-search-button\']').parent().find('button').on('click', function(e) {
		url = $('base').attr('href') + 'index.php?route=module/iblog/search';
		var value = $('.iblog-search input[name=\'search\']').val();
		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}
		location = url;
	});
	$('.iblog-search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			debugger;
			$('.iblog-search button[id=\'iblog-search-button\']').parent().find('button').trigger('click');
		}
	});
});
</script>
<?php echo $footer; ?>