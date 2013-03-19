<!-- navigation (all folders with translations) -->

<?php 
if(count($data) > 1 )
foreach ($data as $_tab_name => $_null){ 
?>
<a class="transtable_btt transtable_btt_white transtable_btt_rounded transtable_btt_small" href="?transtable_folder=<?php echo urlencode($_tab_name) ?>"><?php echo htmlspecialchars($_tab_name); ?></a>
<?php } ?>

<!-- table with translations -->
<table border="0" cellpadding="2" cellspacing="5" id="transtable_table">
	
	<?php 
	$_row_index = 0;
	// for all translation indexes
	foreach ($data[$folder]['all_indexes'] as $_index => $_null){ 
	?>
	
	
	<!-- first row with file names -->
	<?php if($_row_index == 0){ ?>
	<tr>
		<?php if($enable_delete_translation){ ?>
		<th>&nbsp;</th>
		<?php } ?>

		<th class="transtable_header_cell">index</th>
		<?php 
		$_column_index = 1;
		foreach ($data[$folder]['translations'] as $_file_name => $_translations){ 
		?>
		<th class="transtable_header_cell">
			<?php echo substr($_file_name, 0, strrpos($_file_name, '.')); ?>
			<input id="transtable_file_name<?php echo $_column_index ?>" type="hidden" value="<?php echo htmlspecialchars($_file_name) ?>" />
		</th>
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
	<tr id="transtable_row<?php echo $_translation_id ?>" data-transtable-translation-id="<?php echo $_translation_id ?>">
		
		<?php if($enable_delete_translation){ ?>
		<td class="transtable_del_cell"><a href="#" class="transtable_del_link" title="Delete translation">&times;</a></td>
		<?php } ?>
		
		<td class="transtable_index_cell">
			<div id="transtable_index_div<?php echo $_translation_id ?>">
				<?php echo htmlspecialchars($_index) ?>
			</div>
			<input id="transtable_index<?php echo $_translation_id ?>" type="hidden" value="<?php echo htmlspecialchars($_index) ?>" />
		</td>
		<?php 
		foreach ($data[$folder]['translations'] as $_file_name => $_translations){ 
		?>
		<td class="transtable_translation_cell">
			<div contenteditable="true" class="transtable_edit_div">
				<?php @eval('echo ($_translations' . $translate->get_php_index($_index) . ');'); ?>
			</div>
		</td>
		<?php } ?>
	</tr>
	<?php 
		$_row_index++;
	} 
	?>
	
</table>
<?php if($enable_add_translation){ ?>
<button class="transtable_btt transtable_btt_orange transtable_btt_medium" type="button" id="transtable_add_index">Add new translation</button>
<?php } ?>

<div class="transtable_help">
Double click on translation to edit.
</div>

<input id="transtable_open_folder" type="hidden" value="<?php echo htmlspecialchars($folder) ?>" />
<input id="transtable_enable_html_editor" type="hidden" value="<?php echo $enable_html_editor?1:0 ?>" />

<script type="text/javascript">
	$(document).ready(function () {
		transtable.init_table();
	});
</script>

