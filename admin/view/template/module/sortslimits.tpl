<?php echo $header; ?>
<?php if((float)VERSION < 2) { ?>
<script   src="https://code.jquery.com/jquery-1.9.1.min.js"   integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ="   crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
<?php } else { echo $column_left; } ?>
<div id="content">

  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  <button type="submit" form="form-sorts" class="btn btn-primary" id="save"><i class="fa fa-check"></i></button>
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
	
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-sorts" class="form-horizontal">
		
		<h2><?php echo $header_1;?></h2>

		
		<div class="form-group">
            <label class="col-sm-3 control-label" for="sortslimits"><span data-toggle="tooltip"><?php echo $entry_sortslimits_default; ?></span></label>
            <div class="col-sm-9">
              <select name="sortslimits_default" id="sortslimits_default" class="form-control">
                <?php if ($sortslimits_default == 'p.sort_order') { ?>
                <option value="p.sort_order" selected="selected"><?php echo $sort_order ?></option>
                <?php } else { ?>
                <option value="p.sort_order"><?php echo $sort_order ?></option>
                <?php } ?>
                <?php if ($sortslimits_default == 'p.product_id') { ?>
                <option value="p.product_id" selected="selected"><?php echo $date_added ?></option>
                <?php } else { ?>
                <option value="p.product_id"><?php echo $date_added ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'p.quantity') { ?>
                <option value="p.quantity" selected="selected"><?php echo $quantity; ?></option>
                <?php } else { ?>
                <option value="p.quantity"><?php echo $quantity; ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'p.model') { ?>
                <option value="p.model" selected="selected"><?php echo $model; ?></option>
                <?php } else { ?>
                <option value="p.model"><?php echo $model; ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'rating') { ?>
                <option value="rating" selected="selected"><?php echo $rating; ?></option>
                <?php } else { ?>
                <option value="rating"><?php echo $rating; ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'p.price') { ?>
                <option value="p.price" selected="selected"><?php echo $price; ?></option>
                <?php } else { ?>
				<option value="p.price"><?php echo $price; ?></option>
				<?php } ?>
				<?php if ($sortslimits_default == 'pd.name') { ?>
                <option value="pd.name" selected="selected"><?php echo $name; ?></option>
                <?php } else { ?>
                <option value="pd.name"><?php echo $name; ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'p.viewed') { ?>
                <option value="p.viewed" selected="selected"><?php echo $viewed; ?></option>
                <?php } else { ?>
				<option value="p.viewed"><?php echo $viewed; ?></option>
				<?php } ?>
              </select>
            </div>
        </div>
		<div class="form-group">
            <label class="col-sm-3 control-label" for="sortslimits_default2"><span data-toggle="tooltip"><?php echo $entry_sortslimits_default2; ?></span></label>
            <div class="col-sm-9">
              <select name="sortslimits_default2" id="sortslimits_default2" class="form-control">
                <?php if ($sortslimits_default2 == 'DESC') { ?>
                <option value="DESC" selected="selected"><?php echo $desc ?></option>
                <?php } else { ?>
                <option value="DESC"><?php echo $desc ?></option>
                <?php } ?>
				<?php if ($sortslimits_default2 == 'ASC') { ?>
                <option value="ASC" selected="selected"><?php echo $asc; ?></option>
                <?php } else { ?>
                <option value="ASC"><?php echo $asc; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
		<hr>
		
		<h2><?php echo $header_2;?></h2>		

          <div class="form-group">


				<label class="col-sm-2 control-label"><?=$sort_order?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_order_ASC" name="sortslimits_order_ASC" type="checkbox" value="<?php echo $sortslimits_order_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_order_ASC"></label>
				</div>
				
				
				<label class="col-sm-2 control-label"><?=$name.' ▲'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_name_ASC" name="sortslimits_name_ASC" type="checkbox" value="<?php echo $sortslimits_name_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_name_ASC"></label>
				</div>
				
			

				<label class="col-sm-2 control-label"><?=$name.' ▼'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_name_DESC" name="sortslimits_name_DESC" type="checkbox" value="<?php echo $sortslimits_name_DESC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_name_DESC"></label>
				</div>
					
				
			

				<label class="col-sm-2 control-label"><?=$price.' ▲'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_price_ASC" name="sortslimits_price_ASC" type="checkbox" value="<?php echo $sortslimits_price_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_price_ASC"></label>
				</div>
					
					
			

				<label class="col-sm-2 control-label"><?=$price.' ▼'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_price_DESC" name="sortslimits_price_DESC" type="checkbox" value="<?php echo $sortslimits_price_DESC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_price_DESC"></label>
				</div>
					
			

				<label class="col-sm-2 control-label"><?=$rating.' ▲'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_rating_ASC" name="sortslimits_rating_ASC" type="checkbox" value="<?php echo $sortslimits_rating_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_rating_ASC"></label>
				</div>
					
			
			

				<label class="col-sm-2 control-label"><?=$rating.' ▼'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_rating_DESC" name="sortslimits_rating_DESC" type="checkbox" value="<?php echo $sortslimits_rating_DESC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_rating_DESC"></label>
				</div>
					
			
			

				<label class="col-sm-2 control-label"><?=$model.' ▲'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_model_ASC" name="sortslimits_model_ASC" type="checkbox" value="<?php echo $sortslimits_model_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_model_ASC"></label>
				</div>
					
				
			

				<label class="col-sm-2 control-label"><?=$model.' ▼'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_model_DESC" name="sortslimits_model_DESC" type="checkbox" value="<?php echo $sortslimits_model_DESC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_model_DESC"></label>
				</div>
					
				
			

				<label class="col-sm-2 control-label"><?=$quantity.' ▲'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_quantity_ASC" name="sortslimits_quantity_ASC" type="checkbox" value="<?php echo $sortslimits_quantity_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_quantity_ASC"></label>
				</div>
					
				
			

				<label class="col-sm-2 control-label"><?=$quantity.' ▼'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_quantity_DESC" name="sortslimits_quantity_DESC" type="checkbox" value="<?php echo $sortslimits_quantity_DESC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_quantity_DESC"></label>
				</div>
					
				
			

				<label class="col-sm-2 control-label"><?=$date_added.' ▲'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_date_added_ASC" name="sortslimits_date_added_ASC" type="checkbox" value="<?php echo $sortslimits_date_added_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_date_added_ASC"></label>
				</div>
					
						

				<label class="col-sm-2 control-label"><?=$date_added.' ▼'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_date_added_DESC" name="sortslimits_date_added_DESC" type="checkbox" value="<?php echo $sortslimits_date_added_DESC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_date_added_DESC"></label>
				</div>
				
			

				<label class="col-sm-2 control-label"><?=$pop.' ▲'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_viewed_ASC" name="sortslimits_viewed_ASC" type="checkbox" value="<?php echo $sortslimits_viewed_ASC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_viewed_ASC"></label>
				</div>
					
						

				<label class="col-sm-2 control-label"><?=$pop.' ▼'?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed" id="sortslimits_viewed_DESC" name="sortslimits_viewed_DESC" type="checkbox" value="<?php echo $sortslimits_viewed_DESC ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_viewed_DESC"></label>
				</div>
			</div>		
			
			<div class="form-group">
				<label class="col-sm-3 control-label" for="sortslimits_limits"><?php echo $limits; ?></label>
				<div class="col-sm-9">
				  <input type="text" name="sortslimits_limits" value="<?php echo $sortslimits_limits; ?>" id="sortslimits_limits" class="form-control" />
				</div>
			</div>
			
          
		
			<div class="form-group">
				<label class="col-sm-2 control-label"><?=$hide?></label>
				<div class="col-sm-10 btn-group">
					<input class="tgl tgl-skewed yes" id="sortslimits_hide" name="sortslimits_hide" type="checkbox" value="<?php echo $sortslimits_hide ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_hide"></label>
				</div>
					
			
			
				<label class="col-sm-2 control-label two"><?=$in_stock?></label>
				<div class="col-sm-10 btn-group two">
					<input class="tgl tgl-skewed" id="sortslimits_in_stock" name="sortslimits_in_stock" type="checkbox" value="<?php echo $sortslimits_in_stock ? 1 : 0; ?>">
					<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="sortslimits_in_stock"></label>
				</div>

			
				<label class="col-sm-3 control-label"><?php echo $entry_sortslimits_stock_status; ?></label>
				<div class="col-sm-9">
				  <select name="sortslimits_stock_status" id="input-stock-status" class="form-control">
					<?php foreach ($stock_statuses as $stock_status) { ?>
					<?php if ($sortslimits_stock_status == $stock_status['stock_status_id']) { ?>
					<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select>
				</div>
			</div>
			
			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="sortslimits_get">GET's</label>
				<div class="col-sm-10">
				  <input type="text" name="sortslimits_get" value="<?php echo $sortslimits_get; ?>" id="sortslimits_get" class="form-control" />
				</div>
			</div>

        </form>
      </div>
    </div>
  </div>
</div>
<style>
<?php if((float)VERSION < 2) { ?>
.form-group {
    padding-top: 15px;
    padding-bottom: 15px;
    margin-bottom: 0;
}
#menu{display:none}
.page-header{margin-top:0}
.pull-right{padding-top: 15px}
<?php } ?>
label.control-label span:after {
	color: #000;
	content: none;
	margin-left: 4px;
}
.form-group {
  margin-bottom: 5px;
}
h1 p {color:#29D!important; display:inline}
h1 span{color:#FB5151}
.alert-danger {
  background-color: #FB5151;
  border: none;
  font-size: 1.25em;
  color: #FFF; }
.delborder{border: none!important}

.col-sm-7 > .help-block {margin-bottom: 20px;}
.form-group + .form-group { border-top: 1px solid #ededed; }
.btn-group {margin: 5px 0}
</style>
<script>
$(document).ready(function () {                            
    $(".yes2").change(function () {
        if ($(".yes").prop("checked", true) ) {
            $('.two').hide("slow");
        }else{
			$('.two').show("slow");
		}
    });
<?php if ($sortslimits_hide) { ?>
	$('.two').hide();
<?php } ?>
});

$('input, select').change(function() {
  $('#save').addClass("not_saved");
});

$('#content').on('click', '#reload', function() {
	document.location.reload(true);
});
	
$("input.tgl").on('change', function() {
  if ($(this).is(':checked')) {
    $(this).attr('value', '1');
  } else {
    $(this).attr('value', '0');
  }
  
  $('#checkbox-value').text($('#checkbox1').val());
});

$('input.tgl[value="1"]').attr( 'checked', true );

</script>


<style>

form {
    padding-top: 0!important;
}

#generate{
	padding: 30px;
	font-size: 2em;
}

#container {
    background: #f6f6f6
}

.powered{
  text-align: center;
  font-size: 1.1em;
  padding: .9em;  
}

<?php if((float)VERSION < 2) { ?>
.page-header{margin-top:0}
.pull-right{padding-top: 15px}

<?php } ?>
.powered a{color:#777; border-bottom: 1px dotted;}


.powered a:hover{text-decoration: none; border-bottom: 1px solid;}

h1 p, h2 p, .powered a p {color:#29D!important; display:inline}
h1 span, h2 span, .powered a span{color:#FB5151}
h1, h2 {display: inline}
#redirect_list_edit{font-size:2em; cursor: pointer;}
.go{font-size:1.8em; text-align:center}
.alert-danger {
  background-color: #FB5151;
  border: none;
  font-size: 1.25em;
  color: #FFF; }
/*.form-group{border: none!important}
/*.btn-group > label{padding: 5px 10px;}
.btn-group > label:not(.active){opacity:.5!important;padding: 5px 10px;}*/
.col-sm-7 > .help-block {margin-bottom: 20px;}
.form-group + .form-group { border-top: 1px solid #ededed; }
.yes:hover{cursor: no-drop}
.form-group {
    padding-top: 15px;
    padding-bottom: 15px;
    margin-bottom: 0;
}
.buttons .btn {margin-bottom:5px}
.panel-body {
  padding: 0; 
  padding-top: 0;
background: #eee
  }

.breadcrumb li:last-child a {
    color: #1e91cf;
}
.breadcrumb li a {
    color: #999999;
    font-size: 11px;
    padding: 0px;
    margin: 0px;
}


  .tab-content {
background: #fff;
padding: 10px;
    border: 1px solid #ddd;
    border-top-color: transparent;
  }
  .nav-tabs {
	margin-bottom: 0;
	background: #eee
}

.panel-default {
	border-top: 0
}
.panel-left .nav span {
	padding-top: 5px;
	color: #0dca24;
	padding-bottom: 1px;
	border: 1px solid #ddd;
	background: #e244a7;
}

#header, #column-left, #footer, #menu {
  display: none;

}

.panel-body, .panel, .panel-default{
	filter: none!important;
}

  .nav-tabs > li > a {
    color: #555;
    border: none;
    border-right: 1px solid #ddd;
    margin-right: 0;
    }
.btn-group label.btn { 
  min-width: 43px;
}

  .form-horizontal .form-group {
margin-left: -4px;
margin-right: -4px;
    }
	
.noindex_addon .btn-group{
	min-height: 39px;
}

#import_info{
display: block;
margin: 0;
padding:8px 0;
}

#import{
display: block;
clear: both;
margin: 8px 0;
}

.import{
	min-height: 68px;
	padding-bottom:0;
}

.progress{
    background-color: #f38733;
	margin-bottom: 0;
}

input.war{
	background: #f99;
}

.war label.control-label{
	color: #f55;
}

.breadcrumb {
    background: none;
    padding-left: 0!important;
	display: block;
	margin-top: 5px;
	margin-bottom: 15px;
}

.page-header h1{
	margin-bottom: 0;	
}

input {	
    padding-left: 10px!important;
}
div.btn {
	padding-top: 6px;
}

.page-header {
    padding-bottom: 0;
    margin: 0;
    border-bottom: none;
}

#save, #generate{
	background: #86d993;
	border-color: #a4c5a6;
}


#save:focus, #save:hover{
	background: #76c983;
	border-color: #94b596;
}

.btn:focus {
    outline: none;
}

.not_saved{
	background: #e77!important;
	border-color: #d66!important;
}

#column-left + #content {
    margin: 0px;
}
#content {
    padding-top: 20px;
    transition: all 0.3s;
}

.tab-pane{
	transition: all .2s ease;
}

.table thead td span[data-toggle="tooltip"]:after, label.control-label span:after {
    color: #607D8B;
}

.form-control {
  background-color: #f5f5f5;
  border: 1px solid #f7f1f1;
  box-shadow: none;
}

.form-control:hover {
  background-color: #eee;
  border: 1px solid #f7f1f1;
  box-shadow: none;
}.menu4{	color:#0dca24;
	padding-bottom: 1px;	}


.1tgl-btn:hover{
    opacity: .75;
}

.tgl-skewed:checked + .tgl-btn:hover {
    background: #76b983;
}
.tgl-skewed + .tgl-btn:hover {
    background: #777;
}

#save:hover, .not_saved:hover {
    background: #ec5!important;
  border: 1px solid #db4 !important;
}

.nav-tabs > li:hover, .nav-tabs > li a:hover {
  background: #f4f4f4!important;
}

.nav-tabs > li.active:hover, .nav-tabs > li.active a:hover {
  background: #fff!important;
}
.tgl {
  display: none;
}

.tgl-btn {
  margin-top: 5px;
}

form{
 background: #fff;
 border-top: 1px solid #e8e8e8;padding-top: 20px;
}


  
.tgl + .tgl-btn {
  outline: 0;
  display: block;
  width: 6em;
  height: 2em;
  position: relative;
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}
.tgl + .tgl-btn:after, .tgl + .tgl-btn:before {
  position: relative;
  display: block;
  content: "";
  width: 50%;
  height: 100%;
}
.tgl + .tgl-btn:after {
  left: 0;
}
.tgl + .tgl-btn:before {
  display: none;
}
.tgl:checked + .tgl-btn:after {
  left: 50%;
}

.tgl-skewed + .tgl-btn {
  overflow: hidden;
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-transition: all .2s ease;
  transition: all .2s ease;
  font-family: sans-serif;
  background: #888;
  border-radius: 2px;
}
.tgl-skewed + .tgl-btn:after, .tgl-skewed + .tgl-btn:before {
  display: inline-block;
  -webkit-transition: all .2s ease;
  transition: all .2s ease;
  width: 100%;
  text-align: center;
  position: absolute;
  line-height: 2em;
  font-weight: bold;
  color: #fff;
}
.tgl-skewed + .tgl-btn:after {
  left: 100%;
  content: attr(data-tg-on);
}
.tgl-skewed + .tgl-btn:before {
  left: 0;
  content: attr(data-tg-off);
}
.tgl-skewed + .tgl-btn:active {
  background: #888;
}
.tgl-skewed + .tgl-btn:active:before {
  left: -10%;
}
.tgl-skewed:checked + .tgl-btn {
  background: #86d993;
}
.tgl-skewed:checked + .tgl-btn:before {
  left: -100%;
}
.tgl-skewed:checked + .tgl-btn:after {
  left: 0;
}
.tgl-skewed:checked + .tgl-btn:active:after {
  left: 10%;
}
</style>
 <?php echo $footer; ?>