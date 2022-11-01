<div class="giftSelector">
	<div class="col-md-5 sp-serviceBar">
		<label class="control-label"><?php echo $add_new_gift;?></label>
		<div class="switcher">
			<input class="form-control" name="product" id="search-item-by-name" placeholder="Search..."/>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<table id="module" class="table list giftForm">
	<thead id="giftListHead"></thead>
  	<tbody id="newGifts"></tbody>
  	<tbody id="giftList"></tbody>
</table>
<div class="pagination"></div> 
<div class="modal fade" id="modal" tabindex="-1"  aria-labelledby="myModalLabel"></div>
<script type="text/javascript">
	autocompleteProduct($('#search-item-by-name'), 'form');
</script>