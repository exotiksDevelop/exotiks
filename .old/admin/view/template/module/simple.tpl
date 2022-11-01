<?php echo $header; ?>
<?php echo $column_left ?>
<script>
  var simple = {
    version: '<?php echo $version ?>',
    adminEmail: '<?php echo $admin_email ?>',
    opencartVersion: '<?php echo $opencart_version ?>',
    token: '<?php echo $token ?>',
    stoken: '<?php echo $stoken ?>',
    exitUrl: '<?php echo $exit_url ?>',
    adminApi: '<?php echo $admin_api ?>',
    catalogApi: '<?php echo $catalog_api ?>',
    async: true,
    template: '<?php echo $template_system ?>'
  }
</script>
<div id="simple-content">
</div>
<script src="view/javascript/simple.js?v=<?php echo $version ?>"></script>
<?php echo $footer ?>