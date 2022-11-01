<table width="100%">
	<thead>
		<td width="90">Дата</td>
		<td>Альбом</td>
		<td width="15">Удалить</td>
	</thead>
<?php foreach ($export as $export) {
  ?>
  <tr>
	<td>
		<a target="_blank" href="<?php echo $export['link']; ?>"><?php echo $export['date']; ?></a> 
	</td>
	<td>
		<?php echo $export['category']; ?>
	</td>
	<td>
		<a title="Удалить" onclick="if (!confirm('Действительно удалить?')) return false;" href="<?php echo $export['delete_link'] ?>">[x]</a>
	</td>
  </tr>
  <?php
}
?>
</table>
