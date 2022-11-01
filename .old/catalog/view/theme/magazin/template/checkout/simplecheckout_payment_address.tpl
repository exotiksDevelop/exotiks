<div class="simplecheckout-block" id="simplecheckout_payment_address" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
  <?php if ($display_header) { ?>
  <div class="checkout-heading panel-heading"><?php echo $text_checkout_payment_address ?></div>
  <?php } ?>
  <div class="simplecheckout-block-content">
    <?php foreach ($rows as $row) { ?>
      <?php echo $row ?>
    <?php } ?>
    <?php foreach ($hidden_rows as $row) { ?>
      <?php echo $row ?>
    <?php } ?>
  </div>
  <?php if ($display_address_same) { ?>
    <div class="simplecheckout-customer-same-address">
      <label><input type="checkbox" name="address_same" value="1" <?php echo $address_same ? 'checked="checked"' : '' ?> data-onchange="reloadAll"><?php echo $entry_address_same ?></label>
    </div>
  <?php } ?>
</div>