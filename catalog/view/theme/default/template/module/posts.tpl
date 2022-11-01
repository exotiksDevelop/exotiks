<div class="panel panel-default">
  <div class="panel-heading"><?php echo $heading_title; ?></div>
  <div class="panel-body">
  <?php foreach ($all_posts as $posts) { ?>
	<div style="margin-bottom:10px; padding-bottom: 5px; border-bottom:1px solid #eee;">
	  <a href="<?php echo $posts['view']; ?>"><?php echo $posts['title']; ?></a><span style="float:right;"><?php echo $posts['date_added']; ?></span><br />
	  <?php echo $posts['description']; ?>
	</div>
  <?php } ?>
  </div>
</div>