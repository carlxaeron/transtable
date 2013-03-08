
/**
 * transtable object - namespace.
 */
transtable = {}


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
	height : '100px'
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
	var cancel_content = $('<button type="button">Cancel</button>');
	
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
transtable.cancel_edit_translation = function(translation_id){
	$('#transtable_cell_' + translation_id).html(transtable.cancel_content[translation_id]);
	delete transtable.cancel_content[translation_id];
	
}


/**
 * Saves translation
 */
transtable.save_translation = function(translation_id){
	
	console.log(translation_id);
	
	
	var id= language + '-' + variable;
	var x = CKEDITOR.instances[id].getData();
	var data = 't[' + language + ']['+ variable + '] = ' + x;
	
	$.ajax({
		type: 'POST',
		url: 'savetranslation',
		data: data,
		success: function(e){
			var htmlStr = '<div id="' + id + '" onclick="edit(\''+language+'\',\''+variable+'\')">' + x + '</div>';
				$("#"+ id).replaceWith(htmlStr);
				$("#"+ id+1).remove();
				CKEDITOR.instances[id].destroy();
		}
	});
}

