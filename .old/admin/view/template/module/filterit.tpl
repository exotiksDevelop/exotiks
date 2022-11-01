<?php echo $header; ?>
<?php echo $column_left ?>
<script>
  var filterit = {
    token: '<?php echo $token ?>',
    versionHash: '<?php echo $version_hash ?>',
    stoken: '<?php echo $stoken ?>',
    simple: '<?php echo $simple ?>',
    version: '<?php echo $version ?>',
    exitUrl: '<?php echo $exit_url ?>',
    adminApi: '<?php echo $admin_api ?>',
    catalogApi: '<?php echo $catalog_api ?>',
    opncartVersion: '<?php echo $opencart_version ?>',
    async: true
  }
</script>
<div id="filterit-content">
</div>
<script src="view/javascript/filterit.js?v=<?php echo $version ?>"></script>
<?php echo $footer ?>