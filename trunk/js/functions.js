
/**
 * transtable object - namespace.
 */
transtable = {}


/**
 * Temporary storage for original content while editing
 */
transtable.cancel_content = {};


/**
 * CKeditor settings
 */
transtable.CKeditor_config = {
	customConfig : '',
	language : 'en',
	toolbar : 'Transtable',
	toolbar_Transtable :
		[
			['Source'],
			['Cut','Copy','Paste','PasteText','PasteFromWord'],
			['Undo','Redo','-','SelectAll','RemoveFormat'],
			'/',
			['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
			['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
			['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
			['Link','Unlink'],
		],
	height : '100px',
	enterMode : CKEDITOR.ENTER_BR,
	shiftEnterMode: CKEDITOR.ENTER_P
};


/**
 * Initialize translation table
 */
transtable.init_table = function(){
	$('#transtable_table').on('click', '[data-transtable-translaion-id]', function(e){		
		transtable.edit_translation($(e.target).attr('data-transtable-translaion-id'));
	});
}


/**
 * Edits translation
 */
transtable.edit_translation = function(translation_id){
	
	//console.log(translation_id);
	
	var cell = $('#transtable_cell_' + translation_id);
	var textarea = $('<textarea id="transtable_edit_' + translation_id + '" >' + cell.html() + '</textarea>');
	var save_button = $('<button type="button">Save</button>');
	var cancel_button = $('<button type="button">Cancel</button>');
	
	save_button.on('click', function(e){		
		transtable.save_translation($(e.target).parent('td').attr('data-transtable-translaion-id'));
	});
	
	cancel_button.on('click', function(e){		
		transtable.cancel_edit_translation($(e.target).parent('td').attr('data-transtable-translaion-id'));
	});
	
	transtable.cancel_content[translation_id] = cell.html();
	
	cell.html('');
	cell.append(textarea, save_button, cancel_button);
	//$("#"+id).focus();
	CKEDITOR.replace('transtable_edit_' + translation_id, transtable.CKeditor_config);
}


/**
 * Cancels editing
 */
transtable.cancel_edit_translation = function(translation_id, translation){
	
	if(translation)
		transtable.cancel_content[translation_id] = translation;
	
	if(CKEDITOR.instances['transtable_edit_' + translation_id])
		CKEDITOR.instances['transtable_edit_' + translation_id].destroy();
	
	$('#transtable_cell_' + translation_id).html(transtable.cancel_content[translation_id]);
	delete transtable.cancel_content[translation_id];
	
}


/**
 * Saves translation
 */
transtable.save_translation = function(translation_id){
	
	var cell = $('#transtable_cell_' + translation_id);
	
	var column = cell[0].cellIndex;
	var row = cell[0].parentNode.rowIndex;
		
	var file_name = $.trim($('#transtable_file_name' + column).html());
	var index = $.trim($('#transtable_trans_index' + row).html());
	
	
	// if ck editor is enabled
	if(CKEDITOR.instances['transtable_edit_' + translation_id]){
		var ck_instance = CKEDITOR.instances['transtable_edit_' + translation_id];
		var translation = ck_instance.getData();
	}
	else
		translation = $('transtable_edit_' + translation_id).val();
	
	$.ajax({
		type: 'POST',
		url: '?transtable_action=savetranslation',
		data: {file_name:file_name, index:index, translation:translation},
		success: function(e){
			transtable.cancel_edit_translation(translation_id, translation);
		}
	});
}

