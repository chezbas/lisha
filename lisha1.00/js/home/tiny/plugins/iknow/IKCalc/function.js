function ikcalc_tiny_insert_value(p_value)
{
	iKnowDialog.insert('<span class="ikcalc">'+p_value+'</span>');
}


function ikcalc_tiny_update_value(p_value)
{
	iKnowDialog.update_span_ikcal(p_value);
	tinyMCEPopup.close();
}

function insert_var(var_name)
{
	var el = document.getElementById('formule');

	el.selectionStart;
	el.selectionEnd;

	var el_value = el.value;
	var val_after_cursor = el.value.substring(el.selectionEnd);
	
	val_before_cursor = el.value.replace(val_after_cursor,"");
	el.value = val_before_cursor+var_name+val_after_cursor;
	el.focus();
	document.getElementById('formule_div').innerHTML = el.value;
}