<div class="prmn-cmngr-settings__block">
  <h4><?= $text_popup_guessed ?></h4>
  <div class="for-general-form">
    <div class="form-group form-inline">
      <label class="control-label"><?= $entry_popup_cookie_time; ?></label>
      <select name="setting[popup_cookie_time]" class="form-control">
          <?php foreach ($cookie_time_values as $key => $value) { ?>
            <option value="<?= $key; ?>"<?= $popup_cookie_time === $key ? ' selected' : ''; ?>>
                <?= $value; ?>
            </option>
          <?php } ?>
      </select>
    </div>

    <div class="form-group">
      <label class="control-label">
        <input name="setting[popup_user_answer]" value="1" type="checkbox"<?= !empty($settings['popup_user_answer']) ? ' checked="checked"' : ''; ?>>
        <?= $entry_popup_user_answer; ?>
      </label>
    </div>
  </div>
</div>

<div class="prmn-cmngr-settings__block">
  <h4><?= $text_popup_cities; ?></h4>
  <div class="for-general-form">
    <div class="form-group">
      <label class="control-label">
        <input name="setting[cities_in_source]" value="1" type="checkbox"
          <?= !empty($settings['cities_in_source']) ? ' checked="checked"' : ''; ?>">
          <?= $entry_cities_in_source; ?>
      </label>
    </div>
  </div>
  <form action="<?= $action_popups; ?>" class="main-form" data-submit="savePopups">
    <table id="cities" class="table table-striped table-bordered">
      <thead>
      <tr>
        <td>
          <div class="row">
            <div class="col-xs-6">
                <?= $entry_city; ?>
            </div>
            <div class="col-xs-3">
                <?= $entry_sort; ?>
            </div>
          </div>
        </td>
      </tr>
      </thead>
      <tbody>
      <?php $city_row = 0; ?>
      <?php foreach ($cities as $city) { ?>
        <tr id="city-row<?= $city_row; ?>">
          <td>
            <div class="row">
              <div class="col-xs-6">
                <input type="text" name="popup_cities[<?= $city_row; ?>][name]" value="<?= $city['name']; ?>" class="row-fias-name form-control"
                       data-short="1"/>
                <input type="hidden" name="popup_cities[<?= $city_row; ?>][fias_id]" value="<?= $city['fias_id']; ?>" class="row-fias-id"/>
                <input type="hidden" name="popup_cities[<?= $city_row; ?>][id]" value="<?= $city['id']; ?>"/>
              </div>
              <div class="col-xs-3">
                <input type="text" name="popup_cities[<?= $city_row; ?>][sort]" value="<?= $city['sort']; ?>" class="form-control"/>
              </div>
              <div class="col-xs-2">
                <a class="btn btn-danger" onclick="$('#city-row<?= $city_row; ?>').remove();">
                  <i class="fa fa-remove visible-xs"></i>
                  <span class="hidden-xs"><?= $button_remove; ?></span>
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
              <?= $button_add; ?>
          </a>
        </th>
      </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
    var city_row = <?= $city_row; ?>;

    function addCity() {
        var html = '<tr id="city-row' + city_row + '"><td><div class="row">';
        html += '<div class="col-xs-6">';
        html += '<input type="text" name="popup_cities[' + city_row + '][name]" class="row-fias-name form-control" data-short="1"/>';
        html += '<input type="hidden" name="popup_cities[' + city_row + '][fias_id]" class="row-fias-id"/>';
        html += '<input type="hidden" name="popup_cities[' + city_row + '][id]" value=""/></div>';
        html += '<div class="col-xs-3"><input type="text" name="popup_cities[' + city_row + '][sort]" value="" class="form-control"/></div>';
        html += '<div class="col-xs-2"><a class="btn btn-danger" onclick="$(\'#city-row' + city_row + '\').remove();">';
        html += '<i class="fa fa-remove visible-xs"></i><span class="hidden-xs"><?= $button_remove; ?></span></a>';
        html += '</div>';
        html += '</div></td></tr>';

        $('#cities').find('tbody').append(html);

        city_row++;
    }

    function savePopups(callback) {
        var form = $('#tab-popups').find('form');
        form.find('.text-danger').remove();
        $.post(form.attr('action'), form.serialize(),
            function(json) {
                if (json.errors) {
                    for (i in json.errors.cities) {
                        $('#city-row' + i).find('input[name="popup_cities\[' + i + '\]\[name\]"]')
                            .after('<p class="text-danger">' + json.errors.cities[i] + '</p>');
                    }
                    $('#tabs').find('a[href="#tab-popups"]').tab('show');
                }

                if (callback) {
                    callback.call(this, !json.errors);
                }
            }, 'json');
    }
</script>