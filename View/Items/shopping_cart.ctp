<table id="shopping-cart-tbl" tabindex="2">
<tr>
	<th>code</th>
	<th>item_subtype_id</th>
	<th>location_id</th>
	<th>state_id</th>
	<th>manufacturer</th>
	<th>project_id</th>
</tr>

<?php foreach($items as $item): ?>
	<tr><?php echo $item['html']; ?></tr>
<?php endforeach; ?>
</table>