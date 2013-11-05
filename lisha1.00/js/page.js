/**==================================================================
 * Go to a specific page number
 * @lisha_id		: internal lisha identifier
 * @type			: Action __lisha_NEXT__,__lisha_PREVIOUS__,__lisha_FIRST__,__lisha_LAST__,number)
 * @ajax_return     : Ajax response
 ====================================================================*/
function lisha_page_change_ajax(lisha_id,type,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_display_wait(lisha_id);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&type='+type+'&action=1&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_page_change_ajax';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+type+"'";
		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try 
		{
			var json = get_json(ajax_return);

			// Update new line quantity
			eval('lisha.'+lisha_id+'.qtt_line = "'+json.lisha.qtt_line+'";');

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

			// Restore vertical lift position
			//alert(varlisha_lisha_transaction.scrollTop);
			restore_vertical_lift(lisha_id);

			// Update the json object
			eval('lisha.'+lisha_id+'.total_page = '+json.lisha.total_page+';');
			eval('lisha.'+lisha_id+'.active_page = '+json.lisha.active_page+';');
			eval('lisha.'+lisha_id+'.selected_line = new Object;');
			//eval(decodeURIComponent(json.lisha.json_line));
			eval(decodeURIComponent(json.lisha.json)); // SRX ADD


			lisha_display_wait(lisha_id);
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * restore_vertical_lift : Force vertical lift position from recorder data before
 * @lisha_id		: internal lisha identifier
 ====================================================================*/
function restore_vertical_lift(lisha_id)
{
	document.getElementById('liste_'+lisha_id).scrollTop = eval('varlisha_'+lisha_id+'.scrollTop');
	eval('varlisha_'+lisha_id+'.scrollTop = 0;');
}
/**==================================================================*/


/**==================================================================
 * lisha_input_line_per_page_change_ajax : Change line by page range
 * @lisha_id		: internal lisha identifier
 * @qtt				: number of line by page
 * @ajax_return		: response of ajax call
 ====================================================================*/
function lisha_input_line_per_page_change_ajax(lisha_id,qtt,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_display_wait(lisha_id);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&qtt='+qtt+'&action=2&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_input_line_per_page_change_ajax';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+qtt+"'";
		ajax_call(conf);
		//==================================================================
	}
	else
	{
		// Force a full list refresh
		try
		{
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Update the json object
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

			document.getElementById('liste_'+lisha_id).scrollLeft = document.getElementById('liste_'+lisha_id).scrollLeft - 1; // SRX_UGLY_FIXE DUE TO BROWSER BUG

			// Setup Excel export button
			toolbar_excel_button(lisha_id);

			// Hide the wait div
			lisha_hide_wait(lisha_id);

			lisha_execute_event(__ON_REFRESH__,__AFTER__,lisha_id);
		}
		catch(e)
		{
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * lisha_refresh_page_ajax : Refresh lisha applying filter ( Restor current page to 1 )
 * @lisha_id		: internal lisha identifier
 * @ajax_return		: response of ajax call
 ====================================================================*/
function lisha_refresh_page_ajax(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_display_wait(lisha_id);

		lisha_execute_event(__ON_REFRESH__,__BEFORE__,lisha_id);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&qtt=NA'+'&action=2&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_refresh_page_ajax';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try
		{	
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Update the json object
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

			document.getElementById('liste_'+lisha_id).scrollLeft = document.getElementById('liste_'+lisha_id).scrollLeft - 1; // SRX_UGLY_FIXE DUE TO BROWSER BUG

			// Setup Excel export button
			toolbar_excel_button(lisha_id);

			// Hide the wait div
			lisha_hide_wait(lisha_id);

			lisha_execute_event(__ON_REFRESH__,__AFTER__,lisha_id);
		}
		catch(e) 
		{
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


function toolbar_excel_button(lisha_id)
{
	var td_id = 'lisha_td_toolbar_excel_'+lisha_id;

	var qtt_line = eval('lisha.'+lisha_id+'.qtt_line');

	if(qtt_line == 0)
	{
		if(document.getElementById(td_id).className.search("grey_el") == -1)
		{
			// Not already grey
			document.getElementById(td_id).className = document.getElementById(td_id).className+' grey_el';
		}
	}
	else
	{
		var chaine = document.getElementById(td_id).className;
		document.getElementById(td_id).className =chaine.substr(0,chaine.length-8);
	}
}


/**==================================================================
 * Clear all customer change on columns features ( filter, position, display, order and so on...) and go back to first page
 * @lisha_id	: internal lisha identifier
 * @ajax_return : return ajax if any
 ====================================================================*/
function lisha_reset(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_display_wait(lisha_id);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];
		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=9';
		conf['fonction_a_executer_reponse'] = 'lisha_reset';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try 
		{	
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Update the json object
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

			if(json.lisha.edit_mode == 'false')		// SRX_fix_update_buttons_display_mode
			{
				// Set the content of the toolbar
				lisha_set_innerHTML('lisha_toolbar_'+lisha_id,decodeURIComponent(json.lisha.toolbar));
				if(document.getElementById('lisha_td_toolbar_edit_'+lisha_id) != undefined)
				{
					document.getElementById('lisha_td_toolbar_edit_'+lisha_id).className = 'btn_toolbar grey_el';
				}
			}

			// Setup Excel export button
			toolbar_excel_button(lisha_id);

			// Hide the wait div
			lisha_display_wait(lisha_id);

			// Hide any ajax search wait
			document.getElementById('wait_input_'+lisha_id).style.display = 'none';
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Change start page number
 *
 * @evt 		: catch event
 * @lisha_id 	: internal lisha id
 * @element 	: Input dom item
 ====================================================================*/
function lisha_input_page_change(evt,lisha_id,element)
{
	var evt = (evt)?evt : event;

	if(evt.which == 13)
	{
		if(element.value == '')
		{
			element.value = 1;
		}
		// Change start page number
		lisha_page_change_ajax(lisha_id,element.value);
	}
	else
	{
		if(!isNaN(element.value))
		{
			var max = eval('lisha.'+lisha_id+'.total_page');
			if(element.value > max)
			{
				element.value = max;
			}
			else if (element.value < 1 && element.value != '') 
			{
				element.value = 1;
			}

		}
		else
		{
			element.value = 1;
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Change number of lines per page
 *
 * Call on onkeyup event
 * @evt 		: catch event
 * @lisha_id 	: internal lisha id
 * @element 	: Input dom item
 ====================================================================*/
function lisha_input_line_per_page_change(evt,lisha_id,element)
{
	var evt = (evt)?evt : event;

	if(evt.which == 13)
	{
		// Enter key pressed
		if(element.value == '')
		{
			// Force input to 1
			element.value = 1;
			document.getElementById(lisha_id+'_line_selection_footer').value = 1;
		}
		// Change the number of line per page
		lisha_input_line_per_page_change_ajax(lisha_id,element.value);
	}
	else
	{
		// run on each onkeyup event
		// Protect for numeric input only
		// If not numeric then force max line per page value
		if(element.value == 0 && element.value != '' )
		{
			element.value = 1;
		}

		if(isNaN(element.value) || element.value.indexOf('.') != -1)
		{
			element.value = eval('lisha.'+lisha_id+'.qtt_line');
			document.getElementById(lisha_id+'_line_selection_footer').value = eval('lisha.'+lisha_id+'.qtt_line');
		}
		else if(element.value > eval('lisha.'+lisha_id+'.max_line_per_page'))
		{
			element.value = eval('lisha.'+lisha_id+'.max_line_per_page');
			document.getElementById(lisha_id+'_line_selection_footer').value = eval('lisha.'+lisha_id+'.max_line_per_page');
		}
	}
}
/**==================================================================*/


/**
 * Return the selected lines in a json object.
 */
function get_selected_lines(lisha_id)
{
	return JSON.stringify(eval('lisha.'+lisha_id+'.selected_line'));
}

/**
 * @param lisha_id
 * @return
 */
function count_selected_lines(lisha_id)
{
	var i = 0;
	for(var iterable_element in eval('lisha.'+lisha_id+'.selected_line'))
	{
		if(eval('lisha.'+lisha_id+'.selected_line.'+iterable_element+'.selected') == true)
		{
			i++;
		}
	}
	return i;
}