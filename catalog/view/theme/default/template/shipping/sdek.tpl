<?php if($period) { ?>
	<?php echo $period.'<BR>'; ?>
<?php } ?>

<?php if($delivery_data) { ?>
	<?php echo $delivery_data.'<BR>'; ?>
<?php } ?>

<?php if($usePvz) { ?>
	<div class="sdek_pvz_info">
		<a id="selectCdekPvz" href="javascript:" onclick="cdekPvzClick('<?php echo $code; ?>', '<?php echo $pvzType; ?>');">Выбрать пункт выдачи</a>
		<span class="cdek_selectedPvzInfo" id="cdek_selectedPvzInfo_<?php echo $code; ?>"></span>
	</div>
	<input type="hidden" name="need_pvz[]" value="<?php echo $code; ?>">
<?php } else { ?>
	Доставка курьером
<?php } ?>