<?php if ($instruction) { ?>   
    <?php if ($header) { ?>
    <div class="checkout-heading"><?php echo $header ?></div>
    <?php } ?>
    <div class="content well well-sm">
      <?php echo $instruction; ?>   
    </div>
<?php } ?>
<div class="buttons">
  <div class="pull-right right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button btn btn-primary" />
  </div>
</div>
<script type="text/javascript">
$('#button-confirm').on('click', function() {
    $.ajax({
        type: 'get',
        url: 'index.php?route=<?php echo $route ?>',
        success: function() {
            location = '<?php echo $continue; ?>';
        }
    });
});
</script>
