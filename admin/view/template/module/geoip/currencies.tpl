<form action="<?php echo $action_currencies; ?>">
<table id="currencies" class="table table-striped table-bordered">
    <thead>
    <tr>
        <td>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $entry_country; ?>
                </div>
                <div class="col-md-4">
                    <?php echo $entry_currency; ?>
                </div>
            </div>
        </td>
    </tr>
    </thead>
    <tbody>
    <?php $currency_row = 0; ?>
    <?php foreach ($geoip_currencies as $geoip_currency) { ?>
        <tr id="currency-row<?php echo $currency_row; ?>">
            <td>
                <div class="row">
                    <div class="col-md-4">
                        <select name="geoip_currency[<?php echo $currency_row; ?>][country_id]" class="form-control">
                            <option value="0"><?php echo $text_none; ?></option>
                            <?php foreach ($countries as $country) { ?>
                                <option value="<?php echo $country['country_id']; ?>"
                                        <?php echo $country['country_id'] == $geoip_currency['country_id'] ? 'selected' : ''; ?>>
                                    <?php echo $country['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="geoip_currency[<?php echo $currency_row; ?>][code]" class="form-control">
                            <option value="0"><?php echo $text_none; ?></option>
                            <?php foreach ($currencies as $currency) { ?>
                                <option value="<?php echo $currency['code']; ?>"
                                        <?php echo $currency['code'] == $geoip_currency['code'] ? 'selected' : ''; ?>>
                                    <?php echo $currency['title']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a onclick="$('#currency-row<?php echo $currency_row; ?>').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a>
                    </div>
                </div>
            </td>
        </tr>
        <?php $currency_row++; ?>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <th>
            <a class="btn btn-success" onclick="addCurrency();">
                <?php echo $button_add; ?>
            </a>
        </th>
    </tr>
    </tfoot>
</table>
</form>

<select id="select-countries" style="display: none;">
    <option value="0"><?php echo $text_none; ?></option>
    <?php foreach ($countries as $country) { ?>
        <option value="<?php echo $country['country_id']; ?>">
            <?php echo $country['name']; ?>
        </option>
    <?php } ?>
</select>
<select id="select-currencies" style="display: none;">
    <option value="0"><?php echo $text_none; ?></option>
    <?php foreach ($currencies as $currency) { ?>
        <option value="<?php echo $currency['code']; ?>">
            <?php echo $currency['title']; ?>
        </option>
    <?php } ?>
</select>

<script type="text/javascript">
    var currency_row = <?php echo $currency_row; ?>;

    function addCurrency() {
        var html = '<tr id="currency-row' + currency_row + '"><td><div class="row"><div class="col-md-4">';
        html += '<select name="geoip_currency[' + currency_row + '][country_id]" class="form-control">';
        html += $('#select-countries').html();
        html += '</select>';
        html += '</div><div class="col-md-4">';
        html += '<select name="geoip_currency[' + currency_row + '][code]" class="form-control">';
        html += $('#select-currencies').html();
        html += '</select>';
        html += '</div><div class="col-md-1">';
        html += '<a onclick="$(\'#currency-row' + currency_row + '\').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a>';
        html += '</div></div></td></tr>';

        $('#currencies').find('tbody').append(html);

        currency_row++;
    }
</script>