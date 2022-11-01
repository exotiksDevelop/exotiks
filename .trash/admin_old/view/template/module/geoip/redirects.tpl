<div class="for-general-form">
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-license"><?php echo $entry_disable_redirect; ?></label>

        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="geoip_setting[disable_redirect]" value="1"
                        <?php echo !empty($geoip_setting['disable_redirect']) ? ' checked="checked"' : ''; ?>/>
                <?php echo $text_yes; ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="geoip_setting[disable_redirect]" value="0"
                        <?php echo empty($geoip_setting['disable_redirect']) ? ' checked="checked"' : ''; ?>/>
                <?php echo $text_no; ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-license"><?php echo $entry_domain; ?></label>

        <div class="col-sm-10">
            <input type="text" name="geoip_setting[domain]" class="form-control"
                   value="<?php echo !empty($geoip_setting['main_domain']) ? $geoip_setting['main_domain'] : '' ?>"/>
        </div>
    </div>
</div>
<form action="<?php echo $action_redirects; ?>">
<table id="redirects" class="table table-striped table-bordered">
    <thead>
    <tr>
        <td>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $entry_zone; ?>
                </div>
                <div class="col-md-4">
                    <?php echo $entry_subdomain; ?>
                </div>
            </div>
        </td>
    </tr>
    </thead>
    <tbody>
    <?php $redirect_row = 0; ?>
    <?php foreach ($redirects as $redirect) { ?>
        <tr id="redirect-row<?php echo $redirect_row; ?>">
            <td>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="" value="<?php echo $redirect['fias_name']; ?>" class="row-fias-name form-control"/>
                        <input type="hidden" name="geoip_redirect[<?php echo $redirect_row; ?>][fias_id]"
                               value="<?php echo $redirect['fias_id']; ?>" class="row-fias-id"/>
                        <input type="hidden" name="geoip_redirect[<?php echo $redirect_row; ?>][geoip_redirect_id]"
                               value="<?php echo $redirect['geoip_redirect_id']; ?>"/>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="geoip_redirect[<?php echo $redirect_row; ?>][url]" class="form-control"
                               value="<?php echo $redirect['url']; ?>"/>
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-danger" onclick="$('#redirect-row<?php echo $redirect_row; ?>').remove();">
                            <?php echo $button_remove; ?>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <?php $redirect_row++; ?>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <th>
            <a class="btn btn-success" onclick="addRedirect();">
                <?php echo $button_add; ?>
            </a>
        </th>
    </tr>
    </tfoot>
</table>
</form>
<script type="text/javascript">
    var redirect_row = <?php echo $redirect_row; ?>;

    function addRedirect() {
        var html = '<tr id="redirect-row' + redirect_row + '"><td><div class="row"><div class="col-md-4">';
        html += '<input type="text" name="" class="row-fias-name form-control"/>';
        html += '<input type="hidden" name="geoip_redirect[' + redirect_row + '][fias_id]" class="row-fias-id"/>';
        html += '<input type="hidden" name="geoip_redirect[' + redirect_row + '][geoip_redirect_id]" value=""/>';
        html += '</div><div class="col-md-4">';
        html += '<input type="text" name="geoip_redirect[' + redirect_row + '][url]" value="" class="form-control"/>';
        html += '</div><div class="col-md-1">';
        html += '<a class="btn btn-danger" onclick="$(\'#redirect-row' + redirect_row + '\').remove();"><?php echo $button_remove; ?></a>';
        html += '</div></div></td></tr>';

        $('#redirects').find('tbody').append(html);

        redirect_row++;
    }
</script>