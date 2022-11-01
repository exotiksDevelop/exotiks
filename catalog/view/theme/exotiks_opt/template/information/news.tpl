<?=$header?>
<div class="container">

	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
		<?php } ?>
	</ul><!-- /.breadcrumb -->

	<!-- <div id="content" class=""> -->
	<div class="row">
		<?php if ($thumb) { ?>

		<?php } ?>
		<h1 class="products__title">
			<?=$heading_title?>
		</h1>
		<p><?=$posted?>&nbsp;<?=$viewed?></p>
		<div class="tab-content">
			<!-- <div class="description"> -->
			<?=$description?>
			<!-- </div> -->

			<!-- AddThis Button BEGIN -->
			<p class="delis">Поделись с друзьями: <span class="addthis_inline_share_toolbox"></span></p>
			<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
			<script src="//yastatic.net/share2/share.js"></script>
			<div class="ya-share2"
				data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,lj,viber,whatsapp,telegram"></div>
			<div AddThis Button END -->
			</div>
			<div class="lenta">
				<?=$content_bottom?>
			</div>
		</div>
		<!-- <div class=""> -->

			<div class="buttons">
				<div class="pull-left">
					<a class="button red" href="<?=$news_list?>"><?=$button_news?></a>
				</div>
				<div class="pull-left">
					<a class="button" href="<?=$continue?>"><?=$button_continue?></a>
				</div>
			</div>
		<!-- </div> -->
	</div>

	<script>
		$(document).ready(function () {
			$('.thumbnail').magnificPopup({
				type: 'image',
				delegate: 'a',
			});
		});
	</script>

	<!-- </div> -->
</div><!-- /.container -->
<?=$footer?>