<div class="alert alert-info">
    <?php echo $text_regions_info; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<form action="<?php echo $action_regions; ?>">
<table class="table table-striped table-bordered">
    <tbody>
    <?php $country_fias_row = 0; ?>
    <?php foreach ($country_fias as $cf) { ?>
        <tbody id="country-fias-row<?php echo $country_fias_row; ?>">
        <tr>
            <td>
                <div class="row">
                    <div class="col-md-1">
                        <?php echo $cf['fias_name']; ?>
                        <input type="hidden" name="geoip_country_fias[<?php echo $country_fias_row; ?>][fias_id]"
                               value="<?php echo $cf['fias_id']; ?>" class="row-fias-id"/>
                    </div>
                    <div class="col-md-4 form-inline">
                        <select class="country-fias-country-id form-control"
                                name="geoip_country_fias[<?php echo $country_fias_row; ?>][country_id]">
                            <option value="0"><?php echo $text_none; ?></option>
                            <?php foreach ($countries as $country) { ?>
                                <option value="<?php echo $country['country_id']; ?>"<?php echo $country['country_id'] == $cf['country_id'] ? ' selected' : ''; ?>>
                                    <?php echo $country['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
        <?php $country_fias_row++; ?>
    <?php } ?>
</table>
<table id="zone_fias" class="table table-striped table-bordered">
    <tbody>
    <?php $zone_fias_row = 0; ?>
    <?php foreach ($zone_fias as $zf) { ?>
        <tbody id="zone-fias-row<?php echo $zone_fias_row; ?>">
        <tr>
            <td>
                <div class="row">
                    <div class="col-md-1">
                        <?php echo $zf['fias_name']; ?>
                        <input type="hidden" name="geoip_zone_fias[<?php echo $zone_fias_row; ?>][fias_id]"
                               value="<?php echo $zf['fias_id']; ?>" class="row-fias-id"/>
                    </div>
                    <div class="col-md-4 form-inline">
                        <select class="zone-fias-country-id form-control">
                            <option value="0"><?php echo $text_none; ?></option>
                            <?php foreach ($countries as $country) { ?>
                                <option value="<?php echo $country['country_id']; ?>"<?php echo $country['country_id'] == $zf['country_id'] ? ' selected' : ''; ?>>
                                    <?php echo $country['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <select name="geoip_zone_fias[<?php echo $zone_fias_row; ?>][zone_id]"
                                class="zone-fias-zone-id form-control" data-zone_id="<?php echo $zf['zone_id']; ?>">
                            <option value="0"><?php echo $text_none; ?></option>
                            <?php if (!empty($country_zones[$zf['country_id']])) { ?>
                                <?php foreach ($country_zones[$zf['country_id']] as $zone) { ?>
                                    <option value="<?php echo $zone['zone_id'] ?>"<?php echo $zone['zone_id'] == $zf['zone_id'] ? ' selected' : ''; ?>>
                                        <?php echo $zone['name'] ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
        <?php $zone_fias_row++; ?>
    <?php } ?>
</table>
</form>
<script type="text/javascript"><!--
    $(function() {
        $('#zone_fias').on('change', '.zone-fias-country-id', function() {
            var zone = $(this).siblings('.zone-fias-zone-id');
            zone.load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=' + zone.data('zone_id'));
        });
    });
//--></script>