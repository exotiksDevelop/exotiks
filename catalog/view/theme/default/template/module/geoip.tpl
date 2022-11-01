<div id="<?php echo $block_id; ?>"></div>
<script>
$(document).on('geoipModuleLoaded', function() {
	$('#<?php echo $block_id; ?>').geoipModule({
        useAjax: <?php echo $from_ajax ? 'true' : 'false'; ?>,
        confirmRegion: <?php echo $confirm_region ? 'true' : 'false'; ?>,
        httpServer: '<?php echo $http_server; ?>',
        lang: {
            yourZone: '<?php echo $text_zone; ?>',
            confirmRegion: '<?php echo $text_confirm_region; ?>',
            zoneName: '<?php echo $zone; ?>',
            btnYes: '<?php echo $text_yes; ?>',
            btnNo: '<?php echo $text_no; ?>'
        }
    });
});
</script>