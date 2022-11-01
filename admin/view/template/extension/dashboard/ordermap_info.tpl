<div class="panel panel-default panel-map">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-eye"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="panel-body">
  <div class="row">
	<div id="orders-map" class="col-lg-8 col-md-7 col-sm-7 col-xs-12" style="height:330px;"></div>
	<div id="orders-filter" class="col-lg-4 col-md-5 col-sm-5 col-xs-12" style="height:330px;">
		<div id="map_settings" class="map-table">
		  <div class="form-group">
			<label class="col-xs-3 col-sm-4 col-md-4 map-label" for="map_order_qty"><?php echo $entry_count; ?></label>
			<div class="col-xs-9 col-sm-8 col-md-8 param-block">
			  <select name="maporder_order_qty" id="map_order_qty" class="form-control">
				<?php foreach ($order_quantities as $order_qty) { ?>
				<?php if ($order_qty == $maporder_order_qty) { ?>
				<option value="<?php echo $order_qty; ?>" selected="selected"><?php echo $order_qty; ?></option>
				<?php } else { ?>
				<option value="<?php echo $order_qty; ?>"><?php echo $order_qty; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			  <input type="hidden" name="map_setting" id="map_setting" value="<?php echo urlencode(json_encode($map_setting)); ?>" />
			</div>
		  </div>
			<div class="form-group">
			  <label class="col-xs-3 col-sm-4 col-md-4 map-label" for="map_order_min"><span><?php echo $entry_total; ?></span></label>
			  <div class="col-xs-9 col-sm-8 col-md-8 param-block">
				<input type="text" name="maporder_order_min" id="map_order_min" value="<?php echo $maporder_order_min; ?>" placeholder="500" class="form-control" />
				<input type="text" name="maporder_order_max" id="map_order_max" value="<?php echo $maporder_order_max; ?>" placeholder="15000" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-xs-3 col-sm-4 col-md-4 map-label" for="map_order_start"><span><?php echo $entry_period; ?></span></label>
			  <div class="col-xs-9 col-sm-8 col-md-8 param-block">
				<input type="text" name="maporder_order_start" id="map_order_start" value="<?php echo $maporder_order_start; ?>" placeholder="2014-01-01" class="form-control" />
				<input type="text" name="maporder_order_end" id="map_order_end" value="<?php echo $maporder_order_end; ?>" placeholder="2016-12-31" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-xs-3 col-sm-4 col-md-4 map-label" for="map_order_status"><span><?php echo $entry_status; ?></span></label>
			  <div class="col-xs-9 col-sm-8 col-md-8 param-block">
				<div class="well well-sm" style="height:150px;overflow:auto;margin-bottom:0;">
				  <?php foreach ($order_statuses as $order_status) { ?>
				  <div class="checkbox">
					<label>
					  <?php if (in_array($order_status['order_status_id'], $maporder_order_status)) { ?>
					  <input type="checkbox" name="maporder_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
					  <?php echo $order_status['name']; ?>
					  <?php } else { ?>
					  <input type="checkbox" name="maporder_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
					  <?php echo $order_status['name']; ?>
					  <?php } ?>
					</label>
				  </div>
				  <?php } ?>
				</div>
			  </div>
			</div>
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 map-buttons">
					<a id="map_order_submit" class="btn btn-success"><?php echo $button_apply; ?></a>
					<a id="map_order_save" class="btn btn-primary"><?php echo $button_save; ?></a>
				</div>
			</div>
		</div>
	</div>
  </div>
  </div>
</div>
<script src="https://api-maps.yandex.ru/2.1.34/?apikey=<?php echo $apikey; ?>&lang=ru_RU" type="text/javascript"></script>
<style>
.panel.panel-map .panel-body {padding-top: 0;padding-bottom: 0;}
#chart-sale {height: 300px !important;}
#orders-map {float: left;border: 1px solid #DDDDDD;padding: 2px;}
#orders-filter {float: right;padding: 0px 10px 0px 10px;}
#orders-filter .map-label {padding: 0 5px 0 0;line-height: 30px;margin-bottom: 0;}
#map_settings .form-group {padding: 5px 0;overflow: hidden;}
#map_order_qty {height: 30px !important;padding: 5px 13px;}
#map_order_min, #map_order_max, #map_order_start, #map_order_end {width: 47%;padding: 5px 10px;height: 30px !important;float: left;}
#map_order_min, #map_order_start {margin-right: 5%;}
.param-block {padding: 0;}
.map-buttons {text-align: center;}
#map_order_save, #map_order_submit {
    height: 30px !important;
    line-height: 28px;
	padding: 0;
	width: 46%;
	max-width: 90px;
	text-align: center;
}
#map_order_submit {margin-right: 5%;}
.param-block .checkbox {
    min-height: 18px;
    margin-top: 7px;
    margin-bottom: 7px;
    line-height: 1;
}
.param-block .checkbox:first-child {margin-top: 0;}
.param-block input[type="checkbox"] {vertical-align: bottom;}
</style>
<script type="text/javascript"><!--
var myMap = false;

function mapinit() {
	myMap = new ymaps.Map('orders-map', {
		center: [<?php echo $center_latitude; ?>, <?php echo $center_longitude; ?>],
		zoom: <?php echo $maporder_order_zoom; ?>,
		controls: ['geolocationControl', 'zoomControl', 'searchControl', 'typeSelector', 'fullscreenControl']
	}, {
		searchControlProvider: 'yandex#search'
	}),
	objectManager = new ymaps.ObjectManager({
		geoObjectOpenBalloonOnClick: false,
		clusterize: true,
		gridSize: 28
	});

    objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
    myMap.geoObjects.add(objectManager);

    $.ajax({
        url: "index.php?route=extension/dashboard/ordermap/getMapsData&token=<?php echo $token; ?>",
		type: "post",
		data: $("#map_setting"),
		dataType: "json",
    }).done(function(data) {
        objectManager.add(data);
    });
	
    function loadBalloonData(mobject) {
        var dataDeferred = ymaps.vow.defer();
        function resolveData() {
			$.ajax({
				url: 'index.php?route=extension/dashboard/ordermap/getMapBalloonData&order_id='+mobject+'&token=<?php echo $token; ?>',
				type: "post",
				dataType: "json",
			}).done(function(data) {
				dataDeferred.resolve(data);
			});
        }
		resolveData();
        return dataDeferred.promise();
    }

    function hasBalloonData(mobject) {
        return objectManager.objects.getById(mobject).properties.balloonContent;
    }

    objectManager.objects.events.add('click', function(e) {
        var mobject = e.get('objectId');
        if (hasBalloonData(mobject)) {
            objectManager.objects.balloon.open(mobject);
        } else {
            loadBalloonData(mobject).then(function(data) {
                var obj = objectManager.objects.getById(mobject);
                obj.properties.balloonContent = data;
                objectManager.objects.balloon.open(mobject);
            });
        }
    });

	objectManager.clusters.events.add('balloonopen', function(e) {
        var clasterId = e.get('objectId');
		var mcluster = objectManager.clusters.getById(e.get('objectId'));
		var	cobjects = mcluster.properties.geoObjects;
		
		function setwait() {
			objectManager.clusters.state.set('activeObject', cobjects[1]);

			if (objectManager.clusters.balloon.isOpen(clasterId)) {
				setTimeout(function() {
					objectManager.clusters.state.set('activeObject', cobjects[0]);
				}, 500);
			}
		}
		
		$(cobjects).each(function(key, value) {
			var object_id = value.id;
			if (!value.properties.balloonContent) {
				loadBalloonData(object_id).then(function(data) {
					var cobject = cobjects[key];
					cobject.properties.balloonContent = data;
				});
				if (key == 0) {
					setwait();
				}
			}
		});
    });
}

ymaps.ready(mapinit);

$("#map_order_save").on("click", function() {
	$.ajax({
		url:"index.php?route=extension/dashboard/ordermap/saveMapSetting&token=<?php echo $token; ?>",
		type:"post",
		data:$("#map_settings select, #map_settings input:text, #map_settings input:checked"),
		dataType:"json",
		beforeSend:function(){
			$("#map_order_save").attr("disabled",!0)
		},
		complete:function(){
			$("#map_order_save").attr("disabled",!1)
		},
		success:function(a){
			if(a.error) {
				alert(a.error);
			}
			if(a.success) {
				alert(a.success);
			}
		}
	});
});

$("#map_order_submit").on("click", function() {
	$.ajax({
		url:"index.php?route=extension/dashboard/ordermap/getNewMapSetting&token=<?php echo $token; ?>",
		type:"post",
		data:$("#map_settings select, #map_settings input:text, #map_settings input:checked"),
		dataType:"json",
		beforeSend:function(){
			$("#map_order_submit").attr("disabled",!0)
		},
		complete:function(){
			$("#map_order_submit").attr("disabled",!1)
		},
		success:function(a){
			if(a.error) {
				alert(a.error);
			}
			if(a.success) {
				$("#map_setting").val(a.success);
				$("#orders-map").empty();
				myMap = false;
				mapinit();
			}
		}
	});
});

$(window).resize(function(){
	if(myMap){
		myMap.container.fitToViewport();
	}
});

$('#map_order_start').datetimepicker({
	pickTime: false,
	format: "YYYY-MM-DD"
});
$('#map_order_end').datetimepicker({
	pickTime: false,
	format: "YYYY-MM-DD"
});
//--></script>