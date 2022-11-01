<table class="table">
	<tbody>
		<tr>
	  		<td class="col-xs-2"><h5><span class="required">* </span><?php echo $entry_code; ?>:</h5></td>
	  		<td>
				<div class="col-xs-3">
					<select name="giftTeaser[Enabled]" class="form-control">
						  <option value="yes" <?php echo (!empty($data['giftTeaser']['Enabled']) && $data['giftTeaser']['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
						  <option value="no"  <?php echo (empty($data['giftTeaser']['Enabled']) || $data['giftTeaser']['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
					</select>
			  	</div>
			</td>
		</tr>
    <tr>
      <td class="col-xs-2"><h5><span class="required">* </span><?php echo $entry_free_gift_label; ?>:</h5></td>
      <td>
        <div class="col-xs-3">
          <?php foreach ($languages as $language) : ?>
            <div class="input-group" style="margin:10px auto;">
              <div class="input-group-addon"><img src="<?php echo $language['flag_url']; ?>" title="<?php echo $language['name']; ?>" /></div>
              <input placeholder="Free gifts" class="form-control" type="text" name="giftTeaser[FreeGiftLabel][<?php echo $language['language_id']; ?>]" value="<?php echo !empty($data['giftTeaser']['FreeGiftLabel'][$language['language_id']]) ? $data['giftTeaser']['FreeGiftLabel'][$language['language_id']] : 'Free Gift'; ?>" />
            </div>
          <?php endforeach; ?>
        </div>
      </td>
    </tr>
	</tbody>      
</table>