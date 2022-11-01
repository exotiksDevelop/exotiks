<?php echo $header; ?>
	<?php if(!empty($moduleData['CustomPostCSS'])): ?>
		<style>
            <?php echo htmlspecialchars_decode($moduleData['CustomPostCSS']); ?>
        </style>
	<?php endif; ?>
<div itemscope itemprop="blogPost" itemType="http://schema.org/BlogPosting" class="container">
	<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php echo $content_top; ?>
    <div class="iblog-post-title">
        <h2 itemprop="headline"><?php echo $heading_title; ?></h2>
    </div>
	<div class="row">
		<?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?> iblog-post-info">
            <?php if ($thumb && isset($moduleData['MainImageEnabled']) && ($moduleData['MainImageEnabled']=='yes')) { ?>
                <div class="iblog-post-image thumbnails"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="thumbnail"><img itemprop="image" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
            <?php } ?>
            <div >
                <div class="iblog-author-info">
					<?php if (isset($moduleData['AddThisEnabled']) && ($moduleData['AddThisEnabled']=='yes')) { ?>
                        <div class="iblog-share-links">
                            <a href="http://www.addthis.com/bookmark.php?v=250" class="addthis_button"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125"  height="16" border="0" alt="Share" /></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
                        </div>
                    <?php } ?>
                    <div class="iblog-author-data">
                        <strong><?php echo $text_author; ?></strong><span itemprop="author"><span itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo $author; ?></span></span></span> | <strong><?php echo $text_date_created; ?></strong> <?php echo $date_created; ?> <meta itemprop="datePublished" content="<?php echo $date_created; ?>"/>
                    </div>
                </div>
                <div class="iblog-post-description" itemprop="articleBody">
                    <?php echo $body; ?>
                </div>
                <div class="iblog-post-keywords">
					<span class="iblog-keywords-string"><?php echo $iblog_keywords; ?></span> <span itemprop="keywords"><?php echo $keywords; ?></span>
                </div>
                <?php if (isset($moduleData['DisqusEnabled']) && ($moduleData['DisqusEnabled']=='yes')) {?>
                    <hr />
                    <div class="iblog-post-comments">
                        <script type="text/javascript">	
                            var disqus_shortname = '<?php echo $moduleData['DisqusShortName']; ?>';
                               (function() {
                                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                            })();
                        </script>
                        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                        <div id="disqus_thread"></div>
                    </div>
                <?php } ?>
            </div>
            
        </div>
        <?php echo $content_bottom; ?> 
    </div>
    <?php echo $column_right; ?>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script> 
<?php echo $footer; ?>