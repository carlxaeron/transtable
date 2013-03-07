<table border="1">
	<tr>
	<th>Index</th>
	<?php foreach ($languages as $language){ ?>
			<th><?php echo $language ?></th>
	<?php } ?>
	</tr>
	
	
	<?php foreach ($variables as $variable){ ?>
	<tr>
		<td><?php echo $variable ?></td>
		<?php foreach ($languages as $language){ ?>
			<td><div id="'.$language.'-'.$variable.'" onclick="edit(\''.$language.'\',\''.$variable.'\')">'.@$translate[$language][$variable].'</div></td>
		<?php } ?>
	</tr>
	<?php } ?>
	
</table> 

