<div class="simplecheckout-block" id="simplecheckout_comment">
    <?php if ($display_header) { ?>
      <div class="checkout-heading panel-heading"><?php echo $label ?></div>
    <?php } ?>
    <div class="simplecheckout-block-content">
      <textarea class="form-control" name="comment" id="comment" placeholder="<?php echo $placeholder ?>" data-reload-payment-form="true"><?php echo $comment ?></textarea>
    </div>
</div>