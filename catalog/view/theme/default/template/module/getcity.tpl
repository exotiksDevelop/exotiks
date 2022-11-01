<?php if ($cities) { ?>
<div class="results_cities_container">
	<div class="result_list">
		<ul class="list_cities_container">
			<?php foreach ($cities as $city) { ?>
				<li title="<?php echo $city['name']; ?>" area="<?php echo $city['area']; ?>" region="<?php echo $city['region']; ?>" zone_id="<?php echo $city['zone_id']; ?>">
					<?php echo $city['name']; ?>
					<span><?php echo ($city['area'] ? $city['area'] . ", " : ""); ?><?php echo ($city['region'] ? $city['region'] : ""); ?></span>	
				</li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php } ?>

