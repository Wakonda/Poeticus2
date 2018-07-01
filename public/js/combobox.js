function setComboboxEdit(path, table, field, val, func)
{
	$('.combobox').ajaxComboBox(
		path,
		{
			lang: 'fr',
			db_table: table,
			per_page: 20,
			navi_num: 10,
			no_image: true,
			select_only: true,
			init_record: val,
			bind_to: 'selectElement'
		}
	).bind('selectElement', { 'func' : func }, function(e, is_enter_key) {
		if(!is_enter_key) {
			e.data.func($(field).val());
		}
	});
}

function setComboboxNew(path, table, field, func)
{
	var options = {
		lang: 'fr',
		db_table: table,
		per_page: 20,
		navi_num: 10,
		no_image: true,
		bind_to: 'selectElement'
	};
	
	if($(field).val() != "")
		options.init_record = $(field).val();
		
	$('.combobox').ajaxComboBox(
		path,
		options
	).bind('selectElement', { 'func' : func }, function(e, is_enter_key) {
		if(!is_enter_key) {
			e.data.func($(field).val());
		}
	});
}