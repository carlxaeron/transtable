<!-- navigation (all folders with translations) -->
<ul>
<?php foreach ($data as $_tab_name => $_null){ ?>
<li><?php echo htmlspecialchars($_tab_name); ?></li>
<?php } ?>
</ul>

<!-- table with translations -->
<table id="transtable_table">
	
	<?php 
	$_row_index = 0;
	// for all translation indexes
	foreach ($data[$folder]['all_indexes'] as $_index => $_null){ 
	?>
	
	
	<!-- first row with file names -->
	<?php if($_row_index == 0){ ?>
	<tr>
		<td>&nbsp;</td>
		<?php 
		$_column_index = 1;
		foreach ($data[$folder]['translations'] as $_file_name => $_translations){ 
		?>
		<td id="transtable_file_name<?php echo $_column_index ?>">
			<?php echo $_file_name; ?>
		</td>
		<?php
			$_column_index++;
		} 
		?>
	</tr>
	<?php 
		$_row_index++;
	} 
	?>
	
	<!-- rows with translations -->
	<tr>
		<td class="transtable_index_cell" id="transtable_trans_index<?php echo $_row_index ?>">
			<?php echo htmlspecialchars($_index) ?>
		</td>
		<?php 
		$_column_index = 1;
		foreach ($data[$folder]['translations'] as $_file_name => $_translations){ 
			$_id = 't' . md5($folder . $_file_name . $_index);
		?>
		<td class="transtable_translation_cell" data-transtable-translaion-id="<?php echo $_id ?>" id="transtable_cell_<?php echo $_id ?>">
			<?php eval('echo htmlspecialchars($_translations' . $translate->get_php_index($_index) . ');'); ?>
		</td>
		<?php 
			$_column_index++;
		} 
		?>
	</tr>
	<?php 
		$_row_index++;
	} 
	?>
</table>
<?php if($enable_add_index){ ?>
<button type="button" id="transtable_add_index">Add translation</button>
<?php } ?>

<input id="transtable_open_folder" type="hidden" value="<?php echo htmlspecialchars($folder) ?>" />

<script type="text/javascript">
	$(document).ready(function () {
		transtable.init_table();
	});
</script>

