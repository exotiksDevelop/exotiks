<?php echo $header; ?>
<div class="container">
	 <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="left"> 
                <?php echo $column_left; ?>
            </div>
        </div>
    
    <div class="col-md-9 col-sm-8 col-xs-12">
                <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
		<div id="content" class="">
			<div class="row">
				<?php if ($thumb) { ?>
			
				<?php } ?>
				<div class="<?php echo $thumb ? 'col-sm-11' : 'col-sm-12'; ?>">
					<h1><?php echo $heading_title; ?></h1>
                    <p><?php echo $posted; ?>&nbsp;<?php echo $viewed; ?></p>
					<div class="tab-content">
						<div class="description">
							<?php echo $description; ?>
						</div><!-- AddThis Button BEGIN -->
                        <p class="delis">Поделись с друзьями: <span class="addthis_inline_share_toolbox"></span></p>
                        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,lj,viber,whatsapp,telegram"></div>
                        <!-- AddThis Button END -->
					</div>
                    <div class="lenta">
                        <?php echo $content_bottom; ?>
                    </div>
				</div>
				<div class="col-sm-12">
					
					<div class="buttons">
					    <div class="pull-left">
							<a class="btn btn-primary" href="<?php echo $news_list; ?>"><?php echo $button_news; ?></a>
						</div>
						<div class="pull-right">
							<a class="btn btn-primary" href="<?php echo $continue; ?>"><?php echo $button_continue; ?></a>
						</div>
					</div>
				</div>
			</div>
            </div>
		</div>
</div>
	<script type="text/javascript"><!--
		$(document).ready(function () {
			$('.thumbnail').magnificPopup({
				type: 'image',
				delegate: 'a',
			});
		});
	//--></script>
</div>
<?php echo $footer; ?>