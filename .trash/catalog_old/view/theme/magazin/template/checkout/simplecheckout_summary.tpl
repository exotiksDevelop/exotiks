<div class="simplecheckout-block" id="simplecheckout_summary">
    <?php if ($display_header) { ?>
        <div class="checkout-heading panel-heading"><?php echo $text_summary ?></div>
    <?php } ?>
    <div class="table-responsive">
        <table class="simplecheckout-cart">
            <colgroup>
                <col class="image">
                <col class="name">
                <col class="model">
                <col class="quantity">
                <col class="price">
                <col class="total">
            </colgroup>
            <thead>
                <tr>
                    <th class="image"><?php echo $column_image; ?></th>
                    <th class="name"><?php echo $column_name; ?></th>
                    <th class="model"><?php echo $column_model; ?></th>
                    <th class="quantity"><?php echo $column_quantity; ?></th>
                    <th class="price"><?php echo $column_price; ?></th>
                    <th class="total"><?php echo $column_total; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                    <tr>
                        <td class="image">
                            <?php if ($product['thumb']) { ?>
                                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                            <?php } ?>
                        </td>
                        <td class="name">
                            <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                            <div class="options">
                            <?php foreach ($product['option'] as $option) { ?>
                            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                            <?php } ?>
                            </div>
                            <?php if ($product['reward']) { ?>
                            <small><?php echo $product['reward']; ?></small>
                            <?php } ?>
                        </td>
                        <td class="model"><?php echo $product['model']; ?></td>
                        <td class="quantity"><?php echo $product['quantity']; ?></td>
                        <td class="price"><?php echo $product['price']; ?></td>
                        <td class="total"><?php echo $product['total']; ?></td>
                    </tr>
                <?php } ?>
                <?php foreach ($vouchers as $voucher_info) { ?>
                    <tr>
                        <td class="image"></td>
                        <td class="name"><?php echo $voucher_info['description']; ?></td>
                        <td class="model"></td>
                        <td class="quantity">1</td>
                        <td class="price"><?php echo $voucher_info['amount']; ?></td>
                        <td class="total"><?php echo $voucher_info['amount']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php foreach ($totals as $total) { ?>
        <div class="simplecheckout-cart-total" id="total_<?php echo $total['code']; ?>">
            <span><b><?php echo $total['title']; ?>:</b></span>
            <span class="simplecheckout-cart-total-value"><?php echo $total['text']; ?></span>
        </div>
    <?php } ?>

    <?php if ($summary_comment) { ?>
    <table class="simplecheckout-cart simplecheckout-summary-info">
      <thead>
        <tr>
          <th class="name"><?php echo $text_summary_comment; ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $summary_comment; ?></td>
        </tr>
      </tbody>
    </table>
    <?php } ?>
    <?php if ($summary_payment_address || $summary_shipping_address) { ?>
    <table class="simplecheckout-cart simplecheckout-summary-info">
      <thead>
        <tr>
          <?php if ($summary_payment_address) { ?>
          <th class="name"><?php echo $text_summary_payment_address; ?></th>
          <?php } ?>
          <?php if ($summary_shipping_address) { ?>
          <th class="name"><?php echo $text_summary_shipping_address; ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php if ($summary_payment_address) { ?>
          <td><?php echo $summary_payment_address; ?></td>
          <?php } ?>
          <?php if ($summary_shipping_address) { ?>
          <td><?php echo $summary_shipping_address; ?></td>
          <?php } ?>
        </tr>
      </tbody>
    </table>
    <?php } ?>
</div>