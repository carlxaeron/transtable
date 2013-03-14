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
		<?php if($enable_delete_translation){ ?>
		<td>&nbsp;</td>
		<?php } ?>

		<td>&nbsp;</td>
		<?php 
		$_column_index = 1;
		foreach ($data[$folder]['translations'] as $_file_name => $_translations){ 
		?>
		<td id="transtable_file_name<?php echo $_column_index ?>">
			<?php echo substr($_file_name, 0, strrpos($_file_name, '.')); ?>
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
	<?php 
		$_translation_id = 't' . md5($_file_name . rand() . microtime());
	?>
	<tr id="transtable_row<?php echo $_translation_id ?>">
		
		<?php if($enable_delete_translation){ ?>
		<td><a href="#" class="transtable_del_link" data-transtable-translation-id="<?php echo $_translation_id ?>">X</a></td>
		<?php } ?>
		
		<td class="transtable_index_cell" id="transtable_trans_index<?php echo $_translation_id ?>">
			<?php echo htmlspecialchars($_index) ?>
		</td>
		<?php 
		$_column_index = 1;
		foreach ($data[$folder]['translations'] as $_file_name => $_translations){ 
		?>
		<td class="transtable_translation_cell" data-transtable-translation-id="<?php echo $_translation_id ?>" id="transtable_cell_<?php echo $_translation_id ?>">
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
	
	<?php if($enable_add_translation){ ?>
	<!-- row for new translation -->
	<tr id="transtable_new_template" class="transtable_hidden">
		<?php if($enable_delete_translation){ ?>
		<td><a href="#" class="transtable_del_link" data-transtable-translation-id="<?php echo $_translation_id ?>">X</a></td>
		<?php } ?>
		<td class="transtable_index_cell" id="transtable_trans_index##ID##">_##ID##</td>
		<?php foreach ($data[$folder]['translations'] as $_file_name => $_translations){ ?>
		<td class="transtable_translation_cell" data-transtable-translation-id="##ID##" id="transtable_cell_##ID##"></td>
		<?php } ?>
	</tr>
	<?php } ?>
	
</table>
<?php if($enable_add_translation){ ?>
<button type="button" id="transtable_add_index">Add translation</button>
<?php } ?>

<input id="transtable_open_folder" type="hidden" value="<?php echo htmlspecialchars($folder) ?>" />

<script type="text/javascript">
	$(document).ready(function () {
		transtable.init_table();
	});
</script>

