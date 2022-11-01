<form action="<?php echo $action_messages; ?>">
<table id="rules" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <div class="row">
                <div class="col-md-3">
                    <?php echo $entry_key; ?>
                </div>
                <div class="col-md-3">
                    <?php echo $entry_zone; ?>
                </div>
                <div class="col-md-5">
                    <?php echo $entry_value; ?>
                </div>
            </div>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php $rule_row = 0; ?>
    <?php foreach ($rules as $rule) { ?>
        <tr id="rule-row<?php echo $rule_row; ?>">
            <td>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="geoip_rule[<?php echo $rule_row; ?>][key]" value="<?php echo $rule['key']; ?>"/>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="" value="<?php echo $rule['fias_name']; ?>" class="row-fias-name form-control"/>
                        <input type="hidden" name="geoip_rule[<?php echo $rule_row; ?>][fias_id]" value="<?php echo $rule['fias_id']; ?>" class="row-fias-id"/>
                        <input type="hidden" name="geoip_rule[<?php echo $rule_row; ?>][geoip_rule_id]" value="<?php echo $rule['geoip_rule_id']; ?>"/>
                    </div>
                    <div class="col-md-5">
                        <textarea class="form-control" name="geoip_rule[<?php echo $rule_row; ?>][value]"><?php echo $rule['value']; ?></textarea>
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-danger" onclick="$('#rule-row<?php echo $rule_row; ?>').remove();">
                            <?php echo $button_remove; ?>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <?php $rule_row++; ?>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <th>
            <a class="btn btn-success" onclick="addRule();"> <?php echo $button_add; ?></a>
        </th>
    </tr>
    </tfoot>
</table>
</form>
<script type="text/javascript">
    var rule_row = <?php echo $rule_row; ?>;

    function addRule() {
        var html = '<tr id="rule-row' + rule_row + '"><td><div class="row"><div class="col-md-3">';
        html += '<input type="text" name="geoip_rule[' + rule_row + '][key]"/>';
        html += '</div><div class="col-md-3">';
        html += '<input type="text" name="" class="row-fias-name form-control"/>';
        html += '<input type="hidden" name="geoip_rule[' + rule_row + '][fias_id]" class="row-fias-id"/>';
        html += '<input type="hidden" name="geoip_rule[' + rule_row + '][geoip_rule_id]" value=""/>';
        html += '</div><div class="col-md-5">';
        html += '<textarea class="form-control" name="geoip_rule[' + rule_row + '][value]"></textarea>';
        html += '</div><div class="col-md-1">';
        html += '<a class="btn btn-danger" onclick="$(\'#rule-row' + rule_row + '\').remove();"><?php echo $button_remove; ?></a>';
        html += '</div></div></td></tr>';

        $('#rules').find('tbody').append(html);

        rule_row++;
    }
</script>