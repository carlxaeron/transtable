<ul>
<?php foreach ($data as $_tab_name => $_null){ ?>
<li><?php echo htmlspecialchars($_tab_name); ?></li>
<?php } ?>
</ul>
	
<table id="transtable_table">
	<?php foreach ($data[$folder]['all_indexes'] as $_index => $_null){ ?>
	<tr>
		<td class="transtable_index_cell"><?php echo htmlspecialchars($_index) ?></td>
		<?php foreach ($data[$folder]['translations'] as $_file_name => $_translations){ 
		$_id = 't' + md5($folder . $_file_name . $_index);
		?>
			<td class="transtable_translation_cell" data-transtable-translaion-id="<?php echo $_id ?>" id="transtable_cell_<?php echo $_id ?>"><?php eval('echo htmlspecialchars($_translations' . $translate->get_php_index($_index) . ');'); ?></td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>
<input id="transtable_open_folder" type="hidden" value="<?php echo htmlspecialchars($folder) ?>" />

<script type="text/javascript">
	$(document).ready(function () {
		transtable.init_table();
	});
</script>

