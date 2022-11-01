<div class="container-fluid">
	<div class="row">
  		<div class="col-md-4">
    		
			<?php if (empty($moduleData['LicensedOn'])): ?>
    			<div class="licenseAlerts"></div>
    			<div class="licenseDiv"></div>
                <table class="table notLicensedTable">
                	<tr>
                    	<td colspan="2">
                            <div class="form-group">
                               
                                <input type="text" class="licenseCodeBox form-control"  name="<?php echo $moduleNameSmall; ?>[LicenseCode]" id="moduleLicense" value="<?php echo !empty($moduleData['LicenseCode']) ? $moduleData['LicenseCode'] : ''?>" />
                            </div>
                            <button type="button" class="btn btn-success btnActivateLicense"><i class="icon-ok"></i></button>
                        	
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
                var MID = 'W3JWXA4LGE';
                </script>
                <script type="text/javascript" src="view/javascript/val.js"></script>
    		<?php endif; ?>
    
			<?php if (!empty($moduleData['LicensedOn'])): ?>
    			<input name="cHRpbWl6YXRpb24ef4fe" type="hidden" value="<?php echo base64_encode(json_encode($moduleData['License'])); ?>" />
    			<input name="OaXRyb1BhY2sgLSBDb21" type="hidden" value="<?php echo $moduleData['LicensedOn']; ?>" />
    		
    		<?php endif; ?>
  		</div>
  
	</div>
</div>