<div class="container-fluid">
	<div class="row">
  		<div class="col-md-4">
			<?php if (empty($data['giftTeaser']['LicensedOn'])): ?>
    			<div class="licenseAlerts"></div>
    			<div class="licenseDiv"></div>
                <table class="table notLicensedTable">
                	<tr>
                    	<td colspan="2">
                            <div class="form-group">
                                <input type="text" class="licenseCodeBox form-control" name="giftTeaser[LicenseCode]" id="moduleLicense" value="<?php echo !empty($data['giftTeaser']['LicenseCode']) ? $data['giftTeaser']['LicenseCode'] : ''?>" />
                            </div>
                  		</td>
                	</tr>
              	</table>
				<?php 
                    $hostname = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '' ;
                    $hostname = (strstr($hostname,'http://') === false) ? 'http://'.$hostname: $hostname;
                ?>
				<script type="text/javascript">
                var domain='<?php echo base64_encode($hostname); ?>';
                var domainraw='<?php echo $hostname; ?>';
                var timenow=<?php echo time(); ?>;
                var MID = 'BUYYXO1ELH';
                </script>
                <script type="text/javascript" src="view/javascript/val.js"></script>
    		<?php endif; ?>
    
			<?php if (!empty($data['giftTeaser']['LicensedOn'])): ?>
    			<input name="cHRpbWl6YXRpb24ef4fe" type="hidden" value="<?php echo base64_encode(json_encode($data['giftTeaser']['License'])); ?>" />
    			<input name="OaXRyb1BhY2sgLSBDb21" type="hidden" value="<?php echo $data['giftTeaser']['LicensedOn']; ?>" />
    		<?php endif; ?>
  		</div>

	</div>
</div>