<p>Whether you need some new BU Law merchandise for yourself to show off your school pride or for your parents because they won't stop bugging you, we have what you're looking for in the SGA Office. Stop by and see what we have available. <strong>Cash only and abolutely no refunds</strong>.</p>

<?php
	$merch_array = array(
		'T-shirts' => 10,
		'V-necks' => 15,
		'Long-sleeve Shirts' => 15,
		'Sweatpants' => 20,
		'Sweatshirts' => 25,
		'Nylon Jacket' => 25,
		'Beanies' => 10,
		'Mugs' => 5,
		'Key Chains' => 1,
		'Car Decals' => 5,
		'Shotglasses' => '1 for $3 / 2 for $5'
	);
?>

<table class="sga-table">
	<tr>
		<th>Item</th>
		<th>Price</th>
	</tr>
	<?php 
	$i = 0;
	foreach($merch_array as $name => $price) { 
		$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
		$price = (is_numeric($price)) ? '$ ' . number_format($price, 2) : $price;
		?>
		<tr class="<?php echo $evenodd; ?>">
			<td><?php echo $name; ?></td>
			<td><?php echo $price; ?></td>
		</tr>
	<?php $i++; 
	} //endforeach ?>
</table>