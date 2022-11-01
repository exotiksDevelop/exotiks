<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
	<div class="page-header">
		<div class="container-fluid">
		<div class="pull-right">
			<button type="submit" form="form-avitoexport" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
		<h1><?php echo $heading_title; ?></h1>
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
		</div>
	</div>
	
	
  	<div class="container-fluid">
	  	<?php if ($error_warning) { ?>
    		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      			<button type="button" class="close" data-dismiss="alert">&times;</button>
    		</div>
    	<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
        		<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_avitoexport_head ?></h3>
      		</div>
			<div class="panel-body">
				<form class="form-horizontal" action="<?php echo str_replace("amp;","",$action); ?>" method="post" enctype="multipart/form-data" id="form-avitoexport">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="avitoexport_status">
							<?php echo $text_avitoexport_enable; ?>
						</label>
						<div class="col-sm-10">
							<select class="form-control" name="avitoexport_status">
								<option <?php echo($avitoexport_status ? 'selected' : '') ?> value="1" ><?php echo $text_enabled; ?></option>								  
								<option <?php echo(!$avitoexport_status ? 'selected' : '') ?> value="0" ><?php echo $text_disabled;?></option>
							</select>
						</div>
					</div>

						<!-- Contact: AllowEmail -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="avitoexport_contact_mail"><?php echo $text_avitoexport_allowEmail; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_contact_mail">
									<option <?php echo($avitoexport_contact_mail ? 'selected' : '') ?> value="1" ><?php echo $text_yes; ?></option>								  
									<option <?php echo(!$avitoexport_contact_mail ? 'selected' : '') ?> value="0" ><?php echo $text_no;?></option>
								</select>
							</div>
						</div>
						<!-- Contact: ManagerName -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="avitoexport_contact_name"><?php echo $text_avitoexport_name; ?></label>
							<div class="col-sm-10">
								<input class="form-control" name="avitoexport_contact_name" type="text" value="<?php echo (isset($avitoexport_contact_name) ? $avitoexport_contact_name : ""); ?>">
								<span class="alert-danger"><?php echo(isset($error_name) ? $error_name : ''); ?></span>
							</div>
						</div>
						<!-- Contact: PhoneNumber -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="avitoexport_contact_phone"><?php echo $text_avitoexport_phone; ?></label>
							<div class="col-sm-10">
								<input class="form-control" name="avitoexport_contact_phone" required="required" type="text" value="<?php echo (isset($avitoexport_contact_phone) ? $avitoexport_contact_phone : '+7'); ?>">
								<span class="alert-danger"><?php echo(isset($error_phone) ? $error_phone : ''); ?></span>
							</div>
						</div>

						<!-- Location: Region -->
						<div class="form-group" id="region">
							<label class="col-sm-2 control-label" for="avitoexport_location_region"><?php echo $text_avitoexport_region; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_location_region" type="text">
									<option value="0" <?php echo(empty($avitoexport_location_region) || $avitoexport_location_region == 0 ? "selected" : ""); ?>><?php echo $val_avitoexport_undefined; ?></option>
									<?php foreach($regions as $region){ ?>
										<option <?php echo(!empty($avitoexport_location_region) && $avitoexport_location_region == $region["RegionID"]  ? "selected" : "");?> value="<?php echo $region['RegionID'] ?>" >
											<?php echo $region['RegionName'] ?>
										</option>
									<?php } ?>
								</select>
								<span class="alert-danger"><?php echo(isset($error_region) ? $error_region : ''); ?></span>
							</div>
						</div>
						<?php if(!empty($cities) && !in_array($avitoexport_location_region,array("637640","653240"))) { ?>
						<div class="form-group" id="city">
							<label class="col-sm-2 control-label" for="avitoexport_location_city"><?php echo $text_avitoexport_city; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_location_city" type="text">
									<option value="0" <?php echo(empty($avitoexport_location_city) || $avitoexport_location_city == 0 ? "selected" : ""); ?>><?php echo $val_avitoexport_undefined; ?></option>
									<?php foreach($cities as $city){ ?>
										<option <?php echo(!empty($avitoexport_location_city) && $avitoexport_location_city == $city["CityID"]  ? "selected" : "");?> value="<?php echo $city['CityID'] ?>" >
											<?php echo $city['CityName'] ?>
										</option>
									<?php } ?>
								</select>
								<span class="alert-danger"><?php echo(isset($error_location_city) ? $error_location_city : ''); ?></span>							
							</div>
						</div>
						<?php } ?>
						<?php if(!empty($cities) && !empty($city_child)) { ?>
						<div class="form-group" id="cityChild">
							<label class="col-sm-2 control-label" for="avitoexport_location_<?php echo strtolower($city_child[0]['CityChildType']) ?>"><?php echo (strtolower($city_child[0]['CityChildType']) == "district" ? $text_avitoexport_district : $text_avitoexport_subway) ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_location_<?php echo strtolower($city_child[0]['CityChildType']) ?>" type="text">
									<option value="0" selected><?php echo $val_avitoexport_undefined; ?></option>
									<?php foreach($city_child as $cc){ ?>
										<?php if(isset($avitoexport_location_subway)) { ?>
											<option <?php echo(!empty($avitoexport_location_subway) && $avitoexport_location_subway == $cc["CityChildID"]  ? "selected" : "");?> value="<?php echo $cc['CityChildID'] ?>" >
												<?php echo $cc['CityChildName'] ?>
											</option>
										<?php } else if(isset($avitoexport_location_district)){ ?> 
											<option <?php echo(!empty($avitoexport_location_district) && $avitoexport_location_district == $cc["CityChildID"]  ? "selected" : "");?> value="<?php echo $cc['CityChildID'] ?>" >
												<?php echo $cc['CityChildName'] ?>
											</option>
										<?php } ?>
									<?php } ?>
									
								</select>
							</div>
						</div>
						<?php } ?>
						<div class="form-group"></div>
					<h3 class="text-center"><?php echo $section_avitoexport_categories; ?></h3>
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span data-toggle="tooltip" title="<?php echo $hint_avitoexport_categ; ?>">
									<?php echo $text_avitoexport_categ; ?>
								</span>
							</label>
							<div class="col-sm-10" id="avitoexport_categories">
								<div id="controls" class="form-inline">
									<select required style="max-width:300px;min-width:225px" id="cat_from" name="avitoexport_category_from" type="text" class="form-control">
										<option selected value="-1"><?php echo $step_avitoexport_one; ?></option>
										<?php
											foreach($categories as $c){
												if(empty($subcategories[$c['category_id']])) echo '<option style="font-style:italic;font-weight:600;" value="' . $c['category_id'] . '">' . $c['name'] . '</option>';
												else {
													echo '<optgroup label="' . $c["name"] . '">';
													foreach($subcategories[$c['category_id']] as $sub_c){
														if(!empty($sub_c)){
															echo '<option value="' . $sub_c['category_id'] . '"> ' . $sub_c['name'] .' </option>';
														}
													}
													echo "</optgroup>";
												}
											} 
										?>
									</select>
									<select required id="cat_to" name="avitoexport_category_to" type="text" class="form-control">
										<option selected value="-1"><?php echo $step_avitoexport_two; ?></option>
										<?php
											foreach($avito_category as $key => $ac){
												echo '<optgroup label="' . $key . '">';
												foreach($ac as $c){
													echo '<option>' . $c . '</option>';
												}
												echo '</optgroup>';
											} 
										?>
									</select>
									<select required style="width:250px" id="type_to" name="avitoexport_goodtype_to" type="text" class="form-control">
										<option selected value="-1"><?php echo $step_avitoexport_three; ?></option>
									</select>
								
									<div class="avitoexport_buttons">
										<div class="add"><i class="fa fa-plus-circle"></i></div>
									</div>
									<hr>
								</div>
								<?php if(isset($avitoexport_dependence_id_from)){ ?>
									<?php foreach($avitoexport_dependence_id_from as $key => $id){ ?>
										<div style="display:inline-block" data-id="<?php echo $id ?>" class="form-inline">
											<input name="avitoexport_dependence_name_from[]" type="text" readonly  class="avitoexport_dependence from form-control" value="<?php echo $avitoexport_dependence_name_from[$key] ?>">
											<div style="margin:0 6.9px;"><i class="fa fa-angle-double-right"></i></div>
											<input name="avitoexport_dependence_id_from[]" type="hidden" value="<?php echo $id ?>">
											<input name="avitoexport_dependence_to[]" type="text" readonly  class="avitoexport_dependence to form-control" value="<?php echo $avitoexport_dependence_to[$key] ?>">
											<div class="avitoexport_buttons"><div class="delete" data-id="<?php echo $id ?>"><i class="fa fa-minus-circle"></i></div></div>
										</div>
									<?php } ?>
								<?php } ?>
								<span class="warning"><?php echo(isset($warning_category) ? $warning_category : ''); ?></span>							
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $text_avitoexport_adtype; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_adtype">
									<option <?php if ($avitoexport_adtype == '0') echo "selected"; ?> value="0">Товар приобретен на продажу</option>
									<option <?php if ($avitoexport_adtype == '1') echo "selected"; ?> value="1">Товар от производителя</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $section_avitoexport_settings; ?></label>
							<div class="col-sm-10">
								<label class="control-label" for="avitoexport_stock">
									<input id="avitoexport_stock" type="checkbox" name="avitoexport_stock" <?php echo($avitoexport_stock == 'on' ? "checked" : ""); ?> >
									 &nbsp; <?php echo $text_avitoexport_stock; ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $text_avitoexport_package; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_listing_fee">
									<option <?php echo($avitoexport_listing_fee == 'Package' ? "selected" : ""); ?> data-hint="<?php echo $hint_avitoexport_pack_package; ?>" value="Package">Package</option>
									<option <?php echo($avitoexport_listing_fee == 'PackageSingle' ? "selected" : ""); ?> data-hint="<?php echo $hint_avitoexport_pack_pSingle; ?>" value="PackageSingle">PackageSingle</option>
									<option <?php echo($avitoexport_listing_fee == 'Single' ? "selected" : ""); ?> data-hint="<?php echo $hint_avitoexport_pack_single; ?>" value="Single">Single</option>
								</select>
								<div id="avitoexport_hint"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $text_avitoexport_stat; ?></label>
							<div class="col-sm-10">
								<select class="form-control" name="avitoexport_service">
									<option <?php echo($avitoexport_service == 'Free' ? "selected" : ""); ?> value="Free">Обычное объявление</option>
									<option <?php echo($avitoexport_service == 'Premium' ? "selected" : ""); ?> value="Premium">Премиум-объявление</option>
									<option <?php echo($avitoexport_service == 'VIP' ? "selected" : ""); ?> value="VIP">VIP</option>
									<option <?php echo($avitoexport_service == 'PushUp' ? "selected" : ""); ?> value="PushUp">Поднять в поиске</option>
									<option <?php echo($avitoexport_service == 'Highlight' ? "selected" : ""); ?> value="Highlight">Выделение объявления</option>
									<option <?php echo($avitoexport_service == 'TurboSale' ? "selected" : ""); ?> value="TurboSale">Пакет 'Турбо-продажа'</option>
									<option <?php echo($avitoexport_service == 'QuickSale' ? "selected" : ""); ?> value="QuickSale">QuickSale</option>
								</select>
								<div id="avitoexport_hint"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span data-toggle="tooltip" title="<?php echo $hint_avitoexport_ignore; ?>">
									<?php echo $text_avitoexport_ignore; ?>
								</span>
							</label>
							<div class="col-sm-10">
								<textarea class="form-control" cols="50" rows="3" name="avitoexport_ignore"><?php echo (isset($avitoexport_ignore) ? $avitoexport_ignore : ""); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span data-toggle="tooltip" title="<?php echo $hint_avitoexport_delete; ?>">
								<?php echo $text_avitoexport_delete; ?>
								</span>
							</label>
							<div class="col-sm-10">
								<textarea class="form-control" cols="50" rows="7" name="avitoexport_delete"><?php echo (isset($avitoexport_delete) ? $avitoexport_delete : ""); ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="avitoexport_feed"><?php echo $text_avitoexport_feed; ?></label>
							<div class="col-sm-10"><textarea name="avitoexport_feed" readonly cols="10" rows="1" class="form-control"><?php echo (isset($feed) ? $feed : 'Error');?></textarea></div>
						</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
<style>
	#avitoexport_hint {
		position:relative;
		display: none;
		background-color: #000;
		padding: 5px 10px 5px 10px;
		color: white;
		opacity: 0.6;
		width:500px;
		border-radius: 5px; 
	}
	.avitoexport_buttons,#controls {
		display:inline-block;
	}
	.avitoexport_buttons > div{
		padding:5px;
		border-radius:10px;
		position:relative;
		background-size:cover;
		border:1px solid black;
		cursor:pointer;
	}
	.avitoexport_buttons > div:not(.schedule){
		border-radius:50%;
		top:7.5px;		
		padding:0px;
		border:0px;
		width:25px;
		height:25px;
	}
	.avitoexport_buttons > div.add i{
		font-size: 28px;
		color: green;
	}
	.avitoexport_buttons > div.delete{
		font-size: 28px;
		color: red;
	}
	.avitoexport_dependence.from{
		width:250px;
	}
	.avitoexport_dependence.from + div{
		font-size: 28px;
		display:inline-block;
		width:20px;
		height:20px;
		margin:0 10px;
		position:relative;
		top:4px;
		background-size:cover;
	}
	.avitoexport_dependence.to{
		width:400px;
	}
</style>
<script>
	/* jQuery Mask Plugin v1.14.2
	   github.com/igorescobar/jQuery-Mask-Plugin */
	(function(b,q,t){"function"===typeof define&&define.amd?define(["jquery"],b):"object"===typeof exports?module.exports=b(require("jquery")):b(q||t)})(function(b){var q=function(a,e,d){var c={invalid:[],getCaret:function(){try{var h,b=0,e=a.get(0),d=document.selection,f=e.selectionStart;if(d&&-1===navigator.appVersion.indexOf("MSIE 10"))h=d.createRange(),h.moveStart("character",-c.val().length),b=h.text.length;else if(f||"0"===f)b=f;return b}catch(g){}},setCaret:function(h){try{if(a.is(":focus")){var c,
	b=a.get(0);h+=1;b.setSelectionRange?b.setSelectionRange(h,h):(c=b.createTextRange(),c.collapse(!0),c.moveEnd("character",h),c.moveStart("character",h),c.select())}}catch(e){}},events:function(){a.on("keydown.mask",function(h){a.data("mask-keycode",h.keyCode||h.which)}).on(b.jMaskGlobals.useInput?"input.mask":"keyup.mask",c.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){a.keydown().keyup()},100)}).on("change.mask",function(){a.data("changed",!0)}).on("blur.mask",function(){m===
	c.val()||a.data("changed")||a.trigger("change");a.data("changed",!1)}).on("blur.mask",function(){m=c.val()}).on("focus.mask",function(a){!0===d.selectOnFocus&&b(a.target).select()}).on("focusout.mask",function(){d.clearIfNotMatch&&!q.test(c.val())&&c.val("")})},getRegexMask:function(){for(var a=[],c,b,d,f,p=0;p<e.length;p++)(c=g.translation[e.charAt(p)])?(b=c.pattern.toString().replace(/.{1}$|^.{1}/g,""),d=c.optional,(c=c.recursive)?(a.push(e.charAt(p)),f={digit:e.charAt(p),pattern:b}):a.push(d||
	c?b+"?":b)):a.push(e.charAt(p).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));a=a.join("");f&&(a=a.replace(new RegExp("("+f.digit+"(.*"+f.digit+")?)"),"($1)?").replace(new RegExp(f.digit,"g"),f.pattern));return new RegExp(a)},destroyEvents:function(){a.off("input keydown keyup paste drop blur focusout ".split(" ").join(".mask "))},val:function(c){var b=a.is("input")?"val":"text";if(0<arguments.length){if(a[b]()!==c)a[b](c);b=a}else b=a[b]();return b},getMCharsBeforeCount:function(a,c){for(var b=0,d=0,
	f=e.length;d<f&&d<a;d++)g.translation[e.charAt(d)]||(a=c?a+1:a,b++);return b},caretPos:function(a,b,d,l){return g.translation[e.charAt(Math.min(a-1,e.length-1))]?Math.min(a+d-b-l,d):c.caretPos(a+1,b,d,l)},behaviour:function(d){d=d||window.event;c.invalid=[];var e=a.data("mask-keycode");if(-1===b.inArray(e,g.byPassKeys)){var k=c.getCaret(),l=c.val().length,f=c.getMasked(),p=f.length,n=c.getMCharsBeforeCount(p-1)-c.getMCharsBeforeCount(l-1),q=k<l;c.val(f);q&&(8!==e&&46!==e?k=c.caretPos(k,l,p,n):k-=
	1,c.setCaret(k));return c.callbacks(d)}},getMasked:function(a,b){var k=[],l=void 0===b?c.val():b+"",f=0,p=e.length,n=0,q=l.length,r=1,m="push",s=-1,v,t;d.reverse?(m="unshift",r=-1,v=0,f=p-1,n=q-1,t=function(){return-1<f&&-1<n}):(v=p-1,t=function(){return f<p&&n<q});for(var y;t();){var x=e.charAt(f),w=l.charAt(n),u=g.translation[x];if(u)w.match(u.pattern)?(k[m](w),u.recursive&&(-1===s?s=f:f===v&&(f=s-r),v===s&&(f-=r)),f+=r):w===y?y=void 0:u.optional?(f+=r,n-=r):u.fallback?(k[m](u.fallback),f+=r,n-=
	r):c.invalid.push({p:n,v:w,e:u.pattern}),n+=r;else{if(!a)k[m](x);w===x?n+=r:y=x;f+=r}}l=e.charAt(v);p!==q+1||g.translation[l]||k.push(l);return k.join("")},callbacks:function(b){var g=c.val(),k=g!==m,l=[g,b,a,d],f=function(a,b,c){"function"===typeof d[a]&&b&&d[a].apply(this,c)};f("onChange",!0===k,l);f("onKeyPress",!0===k,l);f("onComplete",g.length===e.length,l);f("onInvalid",0<c.invalid.length,[g,b,a,c.invalid,d])}};a=b(a);var g=this,m=c.val(),q;e="function"===typeof e?e(c.val(),void 0,a,d):e;g.mask=
	e;g.options=d;g.remove=function(){var b=c.getCaret();c.destroyEvents();c.val(g.getCleanVal());c.setCaret(b-c.getMCharsBeforeCount(b));return a};g.getCleanVal=function(){return c.getMasked(!0)};g.getMaskedVal=function(a){return c.getMasked(!1,a)};g.init=function(h){h=h||!1;d=d||{};g.clearIfNotMatch=b.jMaskGlobals.clearIfNotMatch;g.byPassKeys=b.jMaskGlobals.byPassKeys;g.translation=b.extend({},b.jMaskGlobals.translation,d.translation);g=b.extend(!0,{},g,d);q=c.getRegexMask();if(h)c.events(),c.val(c.getMasked());
	else{d.placeholder&&a.attr("placeholder",d.placeholder);a.data("mask")&&a.attr("autocomplete","off");h=0;for(var m=!0;h<e.length;h++){var k=g.translation[e.charAt(h)];if(k&&k.recursive){m=!1;break}}m&&a.attr("maxlength",e.length);c.destroyEvents();c.events();h=c.getCaret();c.val(c.getMasked());c.setCaret(h+c.getMCharsBeforeCount(h,!0))}};g.init(!a.is("input"))};b.maskWatchers={};var t=function(){var a=b(this),e={},d=a.attr("data-mask");a.attr("data-mask-reverse")&&(e.reverse=!0);a.attr("data-mask-clearifnotmatch")&&
	(e.clearIfNotMatch=!0);"true"===a.attr("data-mask-selectonfocus")&&(e.selectOnFocus=!0);if(z(a,d,e))return a.data("mask",new q(this,d,e))},z=function(a,e,d){d=d||{};var c=b(a).data("mask"),g=JSON.stringify;a=b(a).val()||b(a).text();try{return"function"===typeof e&&(e=e(a)),"object"!==typeof c||g(c.options)!==g(d)||c.mask!==e}catch(m){}};b.fn.mask=function(a,e){e=e||{};var d=this.selector,c=b.jMaskGlobals,g=c.watchInterval,c=e.watchInputs||c.watchInputs,m=function(){if(z(this,a,e))return b(this).data("mask",
	new q(this,a,e))};b(this).each(m);d&&""!==d&&c&&(clearInterval(b.maskWatchers[d]),b.maskWatchers[d]=setInterval(function(){b(document).find(d).each(m)},g));return this};b.fn.masked=function(a){return this.data("mask").getMaskedVal(a)};b.fn.unmask=function(){clearInterval(b.maskWatchers[this.selector]);delete b.maskWatchers[this.selector];return this.each(function(){var a=b(this).data("mask");a&&a.remove().removeData("mask")})};b.fn.cleanVal=function(){return this.data("mask").getCleanVal()};b.applyDataMask=
	function(a){a=a||b.jMaskGlobals.maskElements;(a instanceof b?a:b(a)).filter(b.jMaskGlobals.dataMaskAttr).each(t)};var s={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,useInput:function(a){var b=document.createElement("div"),d;a="on"+a;d=a in b;d||(b.setAttribute(a,"return;"),d="function"===typeof b[a]);return d}("input"),watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},9:{pattern:/\d/,optional:!0},
	"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}};b.jMaskGlobals=b.jMaskGlobals||{};s=b.jMaskGlobals=b.extend(!0,{},s,b.jMaskGlobals);s.dataMask&&b.applyDataMask();setInterval(function(){b.jMaskGlobals.watchDataMask&&b.applyDataMask()},s.watchInterval)},window.jQuery,window.Zepto);	
</script>
<script>
	$(document).ready(function(){
		$.jMaskGlobals = {translation: {'n': {pattern: /\d/}}};
        $('input[name=avitoexport_contact_phone]').mask('+7 (nnn) nnn-nn-nn');

		const category_pattern = $('div#controls').html();
		$('select[name=avitoexport_location_region] > option[value=<?php echo intval($avitoexport_location_region) ?>]').attr('selected','true');

		$('form').on('click','.add',function(){
			var categories = $('#avitoexport_categories'),
				controls =categories.find('#controls'),
				selects = categories.find('select');
			categories.find('span.warning').detach();
			if($(selects[0]).val() == -1 || $(selects[1]).val() == -1) {
					alert('Пожалуйста, выберете категории!');
			} else {
				var id = $(selects[0]).val();
				var path = $(selects[1]).find(':selected').text() + ($(selects[2]).val() != 404 ? ' / ' +  $(selects[2]).find(':selected').text() : '');
				if(!categories.find('div[data-id=' + id + ']').length){
					
					var new_block = '<div style="display:inline-block" data-id="' + id + '" class="form-inline">';
					new_block += '<input name="avitoexport_dependence_name_from[]" type="text" readonly  class="avitoexport_dependence from form-control" value="' + $(selects[0]).find(':selected').text() + '">';
					new_block += '<div><i class="fa fa-angle-double-right"></i></div>';
					new_block += '<input name="avitoexport_dependence_id_from[]" type="hidden" value="' + $(selects[0]).val() + '">';
					new_block += '<input name="avitoexport_dependence_to[]" type="text" readonly  class="avitoexport_dependence to form-control" value="' + path + '">';
					new_block += '<div class="avitoexport_buttons"><div class="delete" data-id="' + id + '"><i class="fa fa-minus-circle"></i></div></div>';
					new_block += '</div>';

					controls.after(new_block);
					controls.children().detach();
					controls.prepend(category_pattern);
					categories.find('span.error').detach();
				} else {
					controls.append('</br><span style="margin-top: -12px;" class="alert-danger">Произошла ошибка. Пожалуйста, убедитесь, что выбранные вами <u><strong>категории вашего магазина не повторяются!</strong></u></span>');
				}
			}
		})

		$('form').on('click','.delete',function(){
			$(this).parents('div[data-id=' + $(this).attr('data-id') + ']').detach();
		});
		
		$("select[name=avitoexport_location_region]").on("change",function(e){
			var ID = $(this).val();
			element = "";

			$.ajax({
				type:"POST",
				url: '<?php echo str_replace("amp;","",$getLocation); ?>',
				data:"type=region&Id=" + ID,
				beforeSend:function(){
					if(ID == "637640" || ID == "653240"){
						$("#city").detach();
						element = $("#cityChild");
					} else {
						$("#cityChild").detach();
						element = $("#city");
					}
				},
				success: function(data){
					console.log(element);
					if(!element.length) $("#region").after(data);
					else element.replaceWith(data);
				}
			})
		 });
		$("form").on("change","select[name=avitoexport_location_city]",function(e){
			$.ajax({
				type:"POST",
				url: '<?php echo str_replace("amp;","",$getLocation); ?>',
				data:"type=city&Id=" + $(this).val(),
				success: function(data){
					if(!data.length) $("#cityChild").detach();
					else if(!$("#cityChild").length) $("#city").after(data);
					else $("#cityChild").replaceWith(data);
				}
			})
		})
		$("form").on("change","select[name=avitoexport_category_to]",function(e){
			$.ajax({
				type:"POST",
				url: '<?php echo str_replace("amp;","",$getTypeFromCategory); ?>',
				data:"categoryKey=" + $(this).val(),
				success: function(data){
					if(!data) {
						data = "<option value='404' selected>В данной категории нет подкатегорий</option>";
					}
					$("#type_to").html(data);
				}
			})
		})
		$("[data-hint]").on('click',function (eventObject) {
       		$data_hint = $(this).attr("data-hint");
			$(this).parents('div').find("#avitoexport_hint").text($data_hint).show();
		});
	 })
</script>
<?php echo $footer; ?>