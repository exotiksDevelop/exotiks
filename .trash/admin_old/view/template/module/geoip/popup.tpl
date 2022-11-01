<div class="for-general-form">
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-license"><?php echo $entry_popup_active; ?></label>

        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="geoip_setting[popup_active]" value="1"
                        <?php echo !empty($geoip_setting['popup_active']) ? ' checked="checked"' : ''; ?>/>
                <?php echo $text_yes; ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="geoip_setting[popup_active]" value="0"
                        <?php echo empty($geoip_setting['popup_active']) ? ' checked="checked"' : ''; ?>/>
                <?php echo $text_no; ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-license"><?php echo $entry_popup_cookie_time; ?></label>

        <div class="col-sm-10">
            <input type="text" name="geoip_setting[popup_cookie_time]" class="form-control"
                   value="<?php echo !empty($geoip_setting['popup_cookie_time']) ? $geoip_setting['popup_cookie_time'] : '' ?>"/>
        </div>
    </div>
</div>

<h3><?php echo $text_popup_cities; ?></h3>
<form action="<?php echo $action_popups; ?>">
<table id="cities" class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $entry_city; ?>
                    </div>
                    <div class="col-md-1">
                        <?php echo $entry_sort; ?>
                    </div>
                </div>
            </td>
        </tr>
    </thead>
    <tbody>
    <?php $city_row = 0; ?>
    <?php foreach ($cities as $city) { ?>
        <tr id="city-row<?php echo $city_row; ?>">
            <td>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="geoip_city[<?php echo $city_row; ?>][name]" value="<?php echo $city['name']; ?>" class="row-fias-name form-control"/>
                        <input type="hidden" name="geoip_city[<?php echo $city_row; ?>][fias_id]" value="<?php echo $city['fias_id']; ?>" class="row-fias-id"/>
                        <input type="hidden" name="geoip_city[<?php echo $city_row; ?>][geoip_city_id]" value="<?php echo $city['geoip_city_id']; ?>"/>
                    </div>
                    <div class="col-md-1">
                        <input type="text" name="geoip_city[<?php echo $city_row; ?>][sort]" value="<?php echo $city['sort']; ?>" class="form-control"/>
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-danger" onclick="$('#city-row<?php echo $city_row; ?>').remove();">
                            <?php echo $button_remove; ?>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <?php $city_row++; ?>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <th>
            <a class="btn btn-success" onclick="addCity();">
                <?php echo $button_add; ?>
            </a>
        </th>
    </tr>
    </tfoot>
</table>
</form>
<script type="text/javascript">
    var city_row = <?php echo $city_row; ?>;

    function addCity() {
        var html = '<tr id="city-row' + city_row + '"><td><div class="row"><div class="col-md-4">';
        html += '<input type="text" name="geoip_city[' + city_row + '][name]" class="row-fias-name form-control"/>';
        html += '<input type="hidden" name="geoip_city[' + city_row + '][fias_id]" class="row-fias-id"/>';
        html += '<input type="hidden" name="geoip_city[' + city_row + '][geoip_city_id]" value=""/></div>';
        html += '<div class="col-md-1"><input type="text" name="geoip_city[' + city_row + '][sort]" value="" class="form-control"/></div>';
        html += '<div class="col-md-1"><a class="btn btn-danger" onclick="$(\'#city-row' + city_row + '\').remove();"><?php echo $button_remove; ?></a></div>';
        html += '</div></td></tr>';

        $('#cities').find('tbody').append(html);

        city_row++;
    }
</script>