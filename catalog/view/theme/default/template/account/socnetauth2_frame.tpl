<script type="text/javascript" src="/catalog/view/javascript/jquery/socnetauth2-jquery.js"></script>

<style>
h1
{    
	font-family: "Trebuchet MS","Arial";
	color: #0099FF;
    font-size: 24px;
	padding-left: 5px;
}

p
{
	font-family: "Trebuchet MS","Arial";
	color: #999999;
	padding-left: 5px;
}

td
{
	font-family: "Trebuchet MS","Arial";
	color: #000;
	padding: 5px;
}

input[type=submit]
{
	  background-color: #F0F0F0;
    /* background-image: url("/img/widget/button_bg.gif"); */
    background-repeat: repeat-x;
    border: 1px solid #C4C4C4;
    color: #838383;
    font-family: "Arial";
    font-size: 18px;
    font-weight: bold;
    padding: 5px;
}

input[type=text]
{
	border: 1px #ccc solid;
	width: 160px;
}


input[type=submit]:hover
{
	background-color: #F4FAFD;
    /* background-image: url("http://loginza.ru/img/widget/button_hover_bg.gif"); */
    border: 1px solid #D3ECFD;
    color: #2E9CD8;
}

.err
{
	color: red;
	font-family: "Trebuchet MS","Arial";
	font-weight: bold;
	padding-left: 5px;
}

</style>
<h1><?php echo $header; ?></h1>
<p><?php echo $header_notice; ?></p>
<form action="<?php echo $action; ?>" method="POST">
<table>
<?php if( $is_firstname ) { ?>
<tr>
	<td><?php echo $entry_firstname; ?><?php if( $firstname_required ) echo "*"; ?></td>
	<td><input type="text" name="firstname" value="<?php echo $firstname; ?>"><?php if( $error_firstname ) { ?><span class="err"><?php echo $error_firstname; 
	?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_lastname ) { ?>
<tr>
	<td width=80><?php echo $entry_lastname; ?><?php if( $lastname_required ) echo "*"; ?></td>
	<td><input type="text" name="lastname" value="<?php echo $lastname; ?>"><?php if( $error_lastname ) { ?><span class="err"><?php echo $error_lastname; 
	?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_email ) { ?>
<tr>
	<td width=80><?php echo $entry_email; ?><?php if( $email_required ) echo "*"; ?></td>
	<td><input type="text" name="email" value="<?php echo $email; ?>"><?php if( $error_email ) { ?><span class="err"><?php echo $error_email; 
	?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_telephone ) { ?>
<tr>
	<td width=80><?php echo $entry_telephone; ?><?php if( $telephone_required ) echo "*"; ?></td>
	<td><input type="text" name="telephone" value="<?php echo $telephone; ?>"><?php if( $error_telephone ) { ?><span class="err"><?php echo $error_telephone; 
	?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_company ) { ?>
<tr>
	<td width=80><?php echo $entry_company; ?><?php if( $company_required ) echo "*"; ?></td>
	<td><input type="text" name="company" value="<?php echo $company; ?>">
	<?php if( $error_company ) { ?><span class="err"><?php echo $error_company; ?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_postcode ) { ?>
<tr>
	<td width=80><?php echo $entry_postcode; ?><?php if( $postcode_required ) echo "*"; ?></td>
	<td><input type="text" name="postcode" value="<?php echo $postcode; ?>">
	<?php if( $error_postcode ) { ?><span class="err"><?php echo $error_postcode; ?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_country ) { ?>
<tr>
	<td width=80><?php echo $entry_country; ?><?php if( $country_required ) echo "*"; ?></td>
	<td><select name="country">
              <?php foreach ($countries as $item) { ?>
              <?php if ($item['country_id'] == $country) { ?>
              <option value="<?php echo $item['country_id']; ?>" selected="selected"><?php echo $item['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $item['country_id']; ?>"><?php echo $item['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
	<?php if( $error_country ) { ?><span class="err"><?php echo $error_country; ?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_zone ) { ?>
<tr>
	<td width=80><?php echo $entry_zone; ?><?php if( $zone_required ) echo "*"; ?>
		<?php if( empty($is_country) ) {  ?> 
		<div style="display: none;">
		<select name="country">
              <?php foreach ($countries as $item) { ?>
              <?php if ($item['country_id'] == $country) { ?>
              <option value="<?php echo $item['country_id']; ?>" selected="selected"><?php echo $item['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $item['country_id']; ?>"><?php echo $item['name']; ?></option>
              <?php } ?>
              <?php } ?>
        </select></div>
		<?php } ?>
	
	</td>
	<td><select name="zone">
            </select>
	<?php if( $error_zone ) { ?><span class="err"><?php echo $error_zone; ?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_city ) { ?>
<tr>
	<td width=80><?php echo $entry_city; ?><?php if( $city_required ) echo "*"; ?></td>
	<td><input type="text" name="city" value="<?php echo $city; ?>">
	<?php if( $error_city ) { ?><span class="err"><?php echo $error_city; ?></span><?php } ?></td>
</tr>
<?php } ?>

<?php if( $is_address_1 ) { ?>
<tr>
	<td width=80><?php echo $entry_address_1; ?><?php if( $address_1_required ) echo "*"; ?></td>
	<td><input type="text" name="address_1" value="<?php echo $address_1; ?>">
	<?php if( $error_address_1 ) { ?><span class="err"><?php echo $error_address_1; ?></span><?php } ?></td>
</tr>
<?php } ?>

<tr>
	<td width=80></td>
	<td><input type="submit" value="<?php echo $text_submit; ?>"></td>
</tr>
</table>

<?php if( $is_zone ) { ?>
<script type="text/javascript"><!--
$('select[name=\'country\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/socnetauth2/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},		
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'zone\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country\']').trigger('change');

//--></script> 
<?php } ?>
</form>