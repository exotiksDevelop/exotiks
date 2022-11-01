<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-theme-config" data-toggle="tooltip"
				title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>
				</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
				class="btn btn-default"><i class="fa fa-reply"></i></a></div>
				<h1><?php echo $heading_title; ?></h1>
				<ul class="breadcrumb">
					<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="container-fluid">
			<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php } ?>
			<div class="panel panel-default">
				<div class="panel-body">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"
						id="form-news-setting" class="form-horizontal">
						<div class="form-group required">
							<label class="col-sm-4 control-label"><?php echo $entry_thumb; ?></label>
							<div class="col-sm-8">
								<div class="row">
									<div class="col-sm-4">
										<input name="news_setting[news_thumb_width]" type="text"
										id="input-news-thumb-width" class="form-control" placeholder="<?php echo $entry_width; ?>" value="<?php echo $news_thumb_width; ?>" />
									</div>
									<div class="col-sm-4">
										<input name="news_setting[news_thumb_height]" type="text"
										id="input-news-thumb-height" class="form-control" placeholder="<?php echo $entry_height; ?>" value="<?php echo $news_thumb_height; ?>" />
									</div>
								</div>
								<?php if ($error_thumb) { ?>
								<div class="text-danger"><?php echo $error_thumb; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label"><?php echo $entry_popup; ?></label>
							<div class="col-sm-8">
								<div class="row">
									<div class="col-sm-4">
										<input name="news_setting[news_popup_width]" type="text"
										id="input-news-popup-width" class="form-control" placeholder="<?php echo $entry_width; ?>" value="<?php echo $news_popup_width; ?>" />
									</div>
									<div class="col-sm-4">
										<input name="news_setting[news_popup_height]" type="text"
										id="input-news-popup-height" class="form-control" placeholder="<?php echo $entry_height; ?>" value="<?php echo $news_popup_height; ?>" />
									</div>
								</div>
								<?php if ($error_popup) { ?>
								<div class="text-danger"><?php echo $error_popup; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-4 control-label"><?php echo $entry_limit; ?></label>
							<div class="col-sm-8">
								<div class="row">
							<div class="col-sm-4">
								<input name="news_setting[description_limit]" type="text"
								id="input-description-limit" class="form-control" value="<?php echo $description_limit; ?>" />
							</div>
								</div>
								<?php if ($error_limit) { ?>
								<div class="text-danger"><?php echo $error_limit; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="input-news-share"><?php echo $entry_share; ?></label>
							<div class="col-sm-4">
								<select name="news_setting[news_share]" id="input-news-share" class="form-control">
									<?php if ($news_share) { ?>
									<option value="1" selected="selected"><?php echo $text_yes; ?></option>
									<option value="0"><?php echo $text_no; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_yes; ?></option>
									<option value="0" selected="selected"><?php echo $text_no; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo $entry_news_url; ?></label>
							<div class="col-sm-8">
								<div class="row">
							<div class="col-sm-4">
								<input name="news_url" type="text"
								id="input-news-url" class="form-control" value="<?php echo $news_url; ?>" />
							</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>