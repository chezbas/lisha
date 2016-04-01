var isCtrl = false;

/**==================================================================
 * edit_cell : Recover cells value to update
 * @evt				: catch event
 * @line			: line of cell
 * @column			: Column of cell
 * @lisha_id		: internal lisha identifier
 * @column_format	: format of column eg: __CHECKBOX__
 * @ajax_return 	: return ajax if any
 ====================================================================*/
function edit_cell(evt,line,column,lisha_id, column_format, ajax_return)
{
	if(eval('varlisha_'+lisha_id+'.CurrentCellUpdate') == "") // Not already cell updating ??
	{
		// Display the wait div
		lisha_display_wait(lisha_id);

		var div_adresse = 'div_td_l'+line+'_c'+column+'_'+lisha_id;

		if(typeof(ajax_return) == 'undefined')
		{
			if(column_format == __CHECKBOX__ )
			{
				// Display the wait div
				//lisha_display_wait(lisha_id);
			}

			var array_primary_key= JSON.stringify(eval('lisha.'+lisha_id+'.lines.L'+line+'.key'));

			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var conf = [];

			conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
			conf['delai_tentative'] = 15000;
			conf['max_tentative'] = 4;
			conf['type_retour'] = false;		// ReponseText
			conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=22&arraykey='+encodeURIComponent(array_primary_key)+'&column='+column;
			conf['fonction_a_executer_reponse'] = 'edit_cell';
			conf['param_fonction_a_executer_reponse'] = "'"+evt+"',"+line+",'"+column+"','"+lisha_id+"','"+column_format+"'";

			ajax_call(conf);
			//==================================================================
		}
		else
		{	
			// Ajax return
			var retour = JSON.parse(ajax_return);

			if(column_format != __CHECKBOX__)
			{
				// Record current cell updating reference
				eval('varlisha_'+lisha_id+'.CurrentCellUpdate = "'+div_adresse+'"');

				// Keep cell original value in memory
				lis_lib[57].replace(/\$name/g,eval('varlisha_'+lisha_id+'.CurrentCellName'));

				eval('varlisha_'+lisha_id+'.CellUpdateOriginValue = "'+document.getElementById(div_adresse).innerHTML.replace(/[\\]/g, '\\\\').replace(/"/g,'\\"')+'"');

				// Compel of column
				eval('varlisha_'+lisha_id+'.CurrentCellCompel = "'+retour.COMPEL+'"');

				// Name of column
				eval('varlisha_'+lisha_id+'.CurrentCellName = "'+retour.DISPLAY_NAME+'"');

				// Column data type
				eval('varlisha_'+lisha_id+'.CurrentCellDataType = "'+retour.DATA_TYPE+'"');

				// decimal_symbol
				eval('varlisha_'+lisha_id+'.CurrentDecimalSymbol = "'+retour.DECIMAL_SYMBOL+'"');

				// thousand_symbol
				eval('varlisha_'+lisha_id+'.CurrentThousandSymbol = "'+retour.THOUSAND_SYMBOL+'"');

				var html = retour.HTML;

				if(html != null) // Check if null
				{
					html = html.replace(/"/g,'&quot;');
				}
				else
				{
					html = '';
				}

				var input_size = 8;
				//==================================================================
				// Compute input box size
				// minimum size length = 8
				//==================================================================
				if(html.length < 8)
				{
					input_size = 8;
				}
				else
				{
					input_size = html.length;
				}
				//==================================================================

				document.getElementById(div_adresse).innerHTML = '<input type="text" onclick="lisha_StopEventHandler(event);" size="'+input_size+'" onkeyup="input_key_up_manager(event)" onkeydown="return input_key_manager(event,\''+lisha_id+'\',\''+line+'\',\''+column+'\')" id=\''+div_adresse+'_input\' value="'+html+'"><div id=\''+div_adresse+'_input_message\' style="display:none; color:red; background-color:white; border-radius: 5px;"></div>';
			}
			else
			{
				// checkbox case

				// Display the wait div
				lisha_display_wait(lisha_id);

				// Record vertical lift position
				eval('varlisha_'+lisha_id+'.scrollTop = '+document.getElementById('liste_'+lisha_id).scrollTop);

				// Refresh page
				lisha_page_change_ajax(lisha_id,eval('lisha.'+lisha_id+'.active_page'));

				// Display the wait div
				lisha_display_wait(lisha_id);

				// Call extra user event if any
				lisha_execute_event(__ON_UPDATE__,__AFTER__,lisha_id);
			}

			// Force focus
			if(document.getElementById(div_adresse+'_input') != undefined)
			{
				document.getElementById(div_adresse+'_input').focus();
			}
		}
	}
	else
	{
		if(eval('varlisha_'+lisha_id+'.CurrentCellUpdate') != div_adresse )
		{
			// Click another cell, then cancel this one

			// Restore original innerHTML content
			var divadr = eval('varlisha_'+lisha_id+'.CurrentCellUpdate');
			document.getElementById(divadr).innerHTML = eval('varlisha_'+lisha_id+'.CellUpdateOriginValue');

			// Clear current cell value
			eval('varlisha_'+lisha_id+'.CurrentCellUpdate = ""');

		}
	}
}
/**==================================================================*/


/**==================================================================
 * input_key_up_manager : Manage keypress up on input cell
 * @evt : catch browser event
 ====================================================================*/
function input_key_up_manager(evt)
{
	isCtrl=false;
}
/**==================================================================*/


/**==================================================================
 * Update mode
 * input_key_manager : Manage keypress on input cell
 * @evt : catch browser event
 ====================================================================*/
function input_key_manager(evt,lisha_id,line,column)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;

	var div_root_updating = 'div_td_l'+line+'_c'+column+'_'+lisha_id;

	if(charCode == 27) // Escape... Cancel action
	{
		// Restore original innerHTML content
		var divadr = eval('varlisha_'+lisha_id+'.CurrentCellUpdate');
		document.getElementById(divadr).innerHTML = eval('varlisha_'+lisha_id+'.CellUpdateOriginValue');

		// Clear tempo current cell value
		eval('varlisha_'+lisha_id+'.CurrentCellUpdate = ""');
	}

	if(charCode == 13) // Enter
	{
		var input_updating = document.getElementById(div_root_updating+'_input').value;

		// Force UTF8 protection using json
		eval('val = new Object();');
		eval('val.value = \''+protect_json(document.getElementById(div_root_updating+'_input').value)+'\';');

		//==================================================================
		// VALUE FLOAT OR INT
		//==================================================================
		if (val.value != '')
		{
			if(eval('varlisha_'+lisha_id+'.CurrentCellDataType') == __INT__ )
			{
				// replace Thousand Symbol in Nothing
				var re = new RegExp(eval('varlisha_'+lisha_id+'.CurrentThousandSymbol'),"g");
				val.value = val.value.replace(re,'');

				if (!isInteger(val.value))
				{
					// Flag error
					var message = lis_lib[158].replace(/\$name/g,eval('varlisha_'+lisha_id+'.CurrentCellName')); // Replace $name;
					document.getElementById(div_root_updating+'_input_message').innerHTML = message;
					document.getElementById(div_root_updating+'_input_message').style.display = 'block';
					return false;
				}
			}

			if(eval('varlisha_'+lisha_id+'.CurrentCellDataType') == __FLOAT__ )
			{
				// replace decimal Symbol in Point
				val.value = val.value.replace(eval('varlisha_'+lisha_id+'.CurrentDecimalSymbol'),'.');
				// replace Thousand Symbol in Nothing
				var re = new RegExp(eval('varlisha_'+lisha_id+'.CurrentThousandSymbol'),"g");
				val.value = val.value.replace(re,'');

				if (!isFloat(val.value))
				{
					// Flag error
					var message = lis_lib[159].replace(/\$name/g,eval('varlisha_'+lisha_id+'.CurrentCellName')); // Replace $name;
					document.getElementById(div_root_updating+'_input_message').innerHTML = message;
					document.getElementById(div_root_updating+'_input_message').style.display = 'block';
					return false;
				}
			}
		}
		//==================================================================

		//==================================================================
		// Check Compel
		//==================================================================
		// NO COMPEL
		//==================================================================
		if(eval('varlisha_'+lisha_id+'.CurrentCellCompel') == "" )
		{
			do_cell_call_update(div_root_updating,lisha_id,line,column,val.value);
			return true;
		}
		//==================================================================

		//==================================================================
		// VALUE REQUIRED
		//==================================================================
		if(eval('varlisha_'+lisha_id+'.CurrentCellCompel') == __REQUIRED__ )
		{
			if(input_updating == "")
			{
				// Flag error
				var message = lis_lib[57].replace(/\$name/g,eval('varlisha_'+lisha_id+'.CurrentCellName')); // Replace $name;
				document.getElementById(div_root_updating+'_input_message').innerHTML = message;
				document.getElementById(div_root_updating+'_input_message').style.display = 'block';
			}
			else
			{
				do_cell_call_update(div_root_updating,lisha_id,line,column,val.value);
			}
			return true;
		}
		//==================================================================

		//alert(eval('varlisha_'+lisha_id+'.CurrentCellCompel'));

		//==================================================================
		// VALUE LISTED
		//==================================================================
		if(eval('varlisha_'+lisha_id+'.CurrentCellCompel') == __LISTED__)
		{
			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var conf = [];

			var col_origin = eval("lisha."+lisha_id+".columns.c"+column+".idorigin");
			conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
			conf['delai_tentative'] = 15000;
			conf['max_tentative'] = 4;
			conf['type_retour'] = false;		// ReponseText
			conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=26&column='+col_origin+'&value='+encodeURIComponent(JSON.stringify(val.value));
			conf['fonction_a_executer_reponse'] = 'check_compel';
			conf['param_fonction_a_executer_reponse'] = "'"+div_root_updating+"',"+line+","+column+",'"+val.value+"','"+lisha_id+"'";
			ajax_call(conf);
			//==================================================================
			return true;
		}
		//==================================================================
	}
	if(charCode == 17) { isCtrl=true; }

	if(charCode == 186  || evt.keyCode == 59 || evt.keyCode == 190) // key ; for Safari, FireFox and Chrome !!
	{
		if(isCtrl)
		{
			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var conf = [];

			var col_origin = eval("lisha."+lisha_id+".columns.c"+column+".idorigin");
			conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
			conf['delai_tentative'] = 15000;
			conf['max_tentative'] = 4;
			conf['type_retour'] = false;		// ReponseText
			conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=29&column='+col_origin;
			conf['fonction_a_executer_reponse'] = 'write_current_date';
			conf['param_fonction_a_executer_reponse'] = "'"+div_root_updating+"_input'";
			ajax_call(conf);
			//==================================================================
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Write current date / hours information in input head column
 * @evt : catch browser event
 ====================================================================*/
function write_current_date(div_root_updating,ajax_return)
{
	ajax_return = JSON.parse(ajax_return);
	document.getElementById(div_root_updating).value = document.getElementById(div_root_updating).value+ajax_return.DATE;
}
/**==================================================================*/



/**==================================================================
 * Check Compel
 *
 * @div_root_updating 	: div for display value
 * @line				: line number
 * @column				: relative column position
 * @val					: New value for cell to update
 * @lisha_id			: internal lisha identifier
 * @ajax_return			: return ajax if any
 *
 ====================================================================*/
function check_compel(div_root_updating,line,column,val,lisha_id,ajax_return)
{
	ajax_return = JSON.parse(ajax_return);

	if(ajax_return.STATUS == true)
	{
		// This value is in lov
		do_cell_call_update(div_root_updating,lisha_id,line,column,val);
	}
	else
	{
		// Display error message
		var input_updating = document.getElementById(div_root_updating+'_input').value;

		var message = lis_lib[126].replace(/\$name/g,eval('varlisha_'+lisha_id+'.CurrentCellName')); // Replace $name;
		message = message.replace(/\$value/g,input_updating);
		document.getElementById(div_root_updating+'_input_message').innerHTML = message;
		document.getElementById(div_root_updating+'_input_message').style.display = 'block';
	}
}
/**==================================================================*/


/**==================================================================
 * Do Cell update
 *
 * @div_root_updating 	: div for display value
 * @lisha_id			: internal lisha identifier
 * @line				: line number
 * @column				: relative column position
 * @val					: New value for cell to update
 *
 ====================================================================*/
function do_cell_call_update(div_root_updating,lisha_id,line,column,val)
{
	// Remove focus from input box to avoid extra input
	document.getElementById(div_root_updating+'_input').blur();

	// Hide error message div
	document.getElementById(div_root_updating+'_input_message').style.display = 'none';


	var array_primary_key= JSON.stringify(eval('lisha.'+lisha_id+'.lines.L'+line+'.key'));
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var conf = [];

	conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
	conf['delai_tentative'] = 15000;
	conf['max_tentative'] = 4;
	conf['type_retour'] = false;		// ReponseText
	conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=23&arraykey='+array_primary_key+'&column='+column+'&val='+encodeURIComponent(JSON.stringify(val));
	conf['fonction_a_executer_reponse'] = 'ok_edit_cell';
	conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";

	ajax_call(conf);
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * Cell update done successfully
 *
 * @lisha_id		: internal lisha identifier
 * @ajax_return		: ajax return of standard object
 ====================================================================*/
function ok_edit_cell(lisha_id,ajax_return)
{	
	// Clear tempo current cell value
	eval('varlisha_'+lisha_id+'.CurrentCellUpdate = ""');

	// Record vertical lift position
	eval('varlisha_'+lisha_id+'.scrollTop = '+document.getElementById('liste_'+lisha_id).scrollTop);

	// Refresh page
	lisha_page_change_ajax(lisha_id,eval('lisha.'+lisha_id+'.active_page'));

	lisha_execute_event(__ON_UPDATE__,__AFTER__,lisha_id);
}
/**==================================================================*/


/**==================================================================
 * Switch direct cell update button access
 * @lisha_id	: internal lisha identifier
 * @status		: if undefined, then just switch. if 0 force disable and 1 force enable
 ====================================================================*/
function switch_user_cell_update(lisha_id, status)
{
	var l_ref = document.getElementById('lisha_td_toolbar_cells_'+lisha_id).className;

	if(status == undefined)
	{
		if(l_ref.substr(-2) == 'on')
		{
			l_ref = l_ref.substr(0,l_ref.length - 1)+'ff';
			document.getElementById('lisha_td_toolbar_cells_'+lisha_id).onmouseover = function() {lisha_lib_hover(162,90,lisha_id,'1')};
			lisha_lib_hover(162,90,lisha_id,'1'); // Direct caption update
		}
		else
		{
			l_ref = l_ref.substr(0,l_ref.length - 2)+'n';
			document.getElementById('lisha_td_toolbar_cells_'+lisha_id).onmouseover = function() {lisha_lib_hover(161,90,lisha_id,'1')};
			lisha_lib_hover(161,90,lisha_id,'1'); // Direct caption update
		}
	}
	else
	{
		if(status)
		{
			// Force on
			document.getElementById('lisha_td_toolbar_cells_'+lisha_id).onmouseover = function() {lisha_lib_hover(161,90,lisha_id,'1')};
			lisha_lib_hover(161,90,lisha_id,'1'); // Direct caption update
		}
		else
		{
			// Force off
			document.getElementById('lisha_td_toolbar_cells_'+lisha_id).onmouseover = function() {lisha_lib_hover(162,90,lisha_id,'1')};
			lisha_lib_hover(162,90,lisha_id,'1'); // Direct caption update
		}
	}
	document.getElementById('lisha_td_toolbar_cells_'+lisha_id).className = l_ref;
}
/**==================================================================*/


/**==================================================================
 * edit_lines : update a full line
 * @evt			: catch event
 * @line		: line of cell
 * @lisha_id	: internal lisha identifier
 * @ajax_return : return ajax if any
 ====================================================================*/
function edit_lines(evt,line,lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		if(count_selected_lines(lisha_id) > 0 || line != null)
		{
			//var jsoncolorindex = eval('lisha.'+lisha_id+'.lines.L'+line+'.colorkey');
			lisha_StopEventHandler(evt);
			if(line != null && eval('lisha.'+lisha_id+'.return_mode') != __SIMPLE__ && !document.getElementById('chk_l'+line+'_c0_'+lisha_id).checked)
			{
				// Edit button was clicked on the line
				lisha_checkbox(line,evt,null,lisha_id);	
			}
			else
			{
				if(line != null)
				{
					//document.getElementById('l'+line+'_'+id).className = 'line_selected_color_'+jsoncolorindex+'_'+id;

					document.getElementById('chk_l'+line+'_c0_'+lisha_id).checked = true;
					eval('lisha.'+lisha_id+'.selected_line.L'+line+' = lisha.'+lisha_id+'.lines.L'+line+';');
					eval('lisha.'+lisha_id+'.selected_line.L'+line+'.selected = true;');
				}
			}

			// Display background wait window
			lisha_display_wait(lisha_id);

			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var conf = [];

			conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
			conf['delai_tentative'] = 15000;
			conf['max_tentative'] = 4;
			conf['type_retour'] = false;		// ReponseText
			conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=13&lines='+encodeURIComponent(get_selected_lines(lisha_id));
			conf['fonction_a_executer_reponse'] = 'edit_lines';
			conf['param_fonction_a_executer_reponse'] = "'"+evt+"',"+line+",'"+lisha_id+"'";

			ajax_call(conf);
			//==================================================================
		}
		else
		{
			msgbox(lisha_id,lis_lib[53],lis_lib[52]);
		}
	}
	else
	{
		try 
		{
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Update the json
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

			// Disable delete button when try to update rows
			eval('lisha.'+lisha_id+'.selected_line = "";');

			// Set the content of the toolbar
			lisha_set_innerHTML('lisha_toolbar_'+lisha_id,decodeURIComponent(json.lisha.toolbar));

			// Set the focus to the default input
			if(eval('lisha.'+lisha_id+'.default_input_focus') != false)
			{
				document.getElementById('th_input_'+eval('lisha.'+lisha_id+'.default_input_focus')+'__'+lisha_id).focus();
			}

			// Hide the wait div
			lisha_display_wait(lisha_id);

			document.getElementById('lisha_table_mask_'+lisha_id).style.display = 'block';
		} 
		catch(e)
		{
			lisha_display_error(lisha_id,e,ajax_return);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Call when user cancel current edit mode
 * @lisha_id	: internal lisha identifier
 * @ajax_return : return ajax if any
 ====================================================================*/
function lisha_cancel_edit(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		// Display the wait div
		lisha_display_wait(lisha_id);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 3000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=20';
		conf['fonction_a_executer_reponse'] = 'lisha_cancel_edit';
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

			// Update the json
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

			// Set the content of the toolbar
			lisha_set_innerHTML('lisha_toolbar_'+lisha_id,decodeURIComponent(json.lisha.toolbar));

			// Hide the wait div
			lisha_display_wait(lisha_id);
		} 
		catch (e)
		{

		}
	}
}
/**==================================================================*/


/**==================================================================
 * Call when user delete selected lines
 * @lisha_id	: internal lisha identifier
 * @confirm     : true means yes
 * @ajax_return : return ajax if any
 ====================================================================*/
function delete_lines(lisha_id,confirm,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		if(count_selected_lines(lisha_id) > 0)
		{
			if(!confirm)
			{
				lisha_cover_with_filter(lisha_id);
				var prompt_btn = new Array([lis_lib[31],lis_lib[32]],["delete_lines('"+lisha_id+"',true)","lisha_cover_with_filter('"+lisha_id+"');"]);

				document.getElementById('lis_msgbox_conteneur_'+lisha_id).style.display = '';
				lisha_generer_msgbox(lisha_id,lis_lib[54],lis_lib[55].replace('$x',count_selected_lines(lisha_id)),'info','msg',prompt_btn);
			}
			else
			{
				lisha_execute_event(__ON_DELETE__,__BEFORE__,lisha_id);

				// Hide the promptbox
				lisha_cover_with_filter(lisha_id);

				// Display the wait div
				lisha_display_wait(lisha_id);

				//==================================================================
				// Setup Ajax configuration
				//==================================================================
				var conf = [];

				conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
				conf['delai_tentative'] = 15000;
				conf['max_tentative'] = 4;
				conf['type_retour'] = false;		// ReponseText
				conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=15&lines='+encodeURIComponent(get_selected_lines(lisha_id));
				conf['fonction_a_executer_reponse'] = 'delete_lines';
				conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"',true";

				ajax_call(conf);
				//==================================================================
			}
		}
		else
		{
			msgbox(lisha_id,lis_lib[51],lis_lib[52]);
		}
	}
	else
	{
		try 
		{
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Update the json
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

			lisha_set_innerHTML('lisha_toolbar_'+lisha_id,decodeURIComponent(json.lisha.toolbar));

			// Hide the cover div
			var theme = eval('lisha.'+lisha_id+'.theme');
			document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.display = 'none';

			// Erase the content of the wait div
			lisha_set_innerHTML('lis__'+theme+'__wait_'+lisha_id+'__','');

			// Erase the content of the msgbox div
			lisha_set_innerHTML('lis_msgbox_conteneur_'+lisha_id,'');

			lisha_execute_event(__ON_DELETE__,__AFTER__,lisha_id);
		} 	
		catch(e)
		{
			lisha_display_error(lisha_id,e);
			alert(ajax_return);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Call when user add a new line
 * @lisha_id	: internal lisha identifier
 * @ajax_return : return ajax if any
 ====================================================================*/
function add_line(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_execute_event(__ON_ADD__,__BEFORE__,lisha_id);

		// Display the wait div
		lisha_display_wait(lisha_id);

		//==================================================================
		// Build json columns values only those are checked
		//==================================================================
		var val = new Object();
		for(var iterable_element in eval('lisha.'+lisha_id+'.columns')) 
		{
			var i = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id');
			if(i != undefined)
			{
				eval('val.'+iterable_element+' = new Object();');
				eval('val.'+iterable_element+'.value = \''+protect_json(document.getElementById('th_input_'+i+'__'+lisha_id).value)+'\';');
				eval('val.'+iterable_element+'.id = '+i+';'); // Count column  1....2....3 and so on
				eval('val.'+iterable_element+'.idorigin = '+eval('lisha.'+lisha_id+'.columns.c'+i+'.idorigin')+';'); // Original id position of column
			}
		}
		//==================================================================

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];
		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=16&val_json='+encodeURIComponent(JSON.stringify(val));
		conf['fonction_a_executer_reponse'] = 'add_line';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try 
		{
			// Hide the wait div
			lisha_display_wait(lisha_id);

			// Get the ajax return in json format
			var json = get_json(ajax_return);

			if(json.lisha.error == 'false')
			{
				// An error has occured
				eval('var test = '+decodeURIComponent(json.lisha.error_col));
				for(var iterable_element in test)
				{
					if(eval('test.'+iterable_element+'.status') == __FORBIDDEN__)
					{
						// forbidden
						document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).value = '';
						document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).disabled = 'true';
					}
					else
					{
						if(eval('test.'+iterable_element+'.status') == __LISTED__)
						{
							// listed
							document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).style.backgroundColor = '#D4D4FF';
						}
						else
						{
							// Required
							document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).style.backgroundColor = '#FFD4D4';
						}
					}
				}

				lisha_cover_with_filter(lisha_id);
				prompt_btn = new Array([lis_lib[31]],["lisha_cover_with_filter('"+lisha_id+"');"]);

				document.getElementById('lis_msgbox_conteneur_'+lisha_id).style.display = '';
				lisha_generer_msgbox(lisha_id,lis_lib[56],decodeURIComponent(json.lisha.error_str),'erreur','msg',prompt_btn);
			}
			else
			{
				// No error

				// Update the json
				eval(decodeURIComponent(json.lisha.json));

				// Set the content of the lisha
				lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

				// Set the content of the toolbar
				lisha_set_innerHTML('lisha_toolbar_'+lisha_id,decodeURIComponent(json.lisha.toolbar));

			}

			document.getElementById('wait_input_'+lisha_id).style.display = 'none';

			lisha_execute_event(__ON_ADD__,__AFTER__,lisha_id);
		}
		catch(e) 
		{
			alert(ajax_return);
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Called when user save update on selected lines
 * @evt			: event of web browser
 * @lisha_id	: lisha_id Id of the lisha
 * @up_mode     : update or add
 * @ajax_return : use with ajax return
====================================================================*/
function save_lines(evt,lisha_id,up_mode,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{

		// Display the wait div
		lisha_display_wait(lisha_id);

		lisha_execute_event(__ON_UPDATE__,__BEFORE__,lisha_id);

		//==================================================================
		// Build json columns values only those are checked
		//==================================================================
		var val = new Object();
		for(var iterable_element in eval('lisha.'+lisha_id+'.columns')) 
		{
			var i = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id');
			if(i != undefined)
			{
				if(document.getElementById('chk_edit_c'+i+'_'+lisha_id) && document.getElementById('chk_edit_c'+i+'_'+lisha_id).checked == true)
				{
					eval('val.'+iterable_element+' = new Object();');
					eval('val.'+iterable_element+'.value = \''+protect_json(document.getElementById('th_input_'+i+'__'+lisha_id).value)+'\';');
					eval('val.'+iterable_element+'.id = '+i+';');
					eval('val.'+iterable_element+'.idorigin = '+eval('lisha.'+lisha_id+'.columns.c'+i+'.idorigin')+';'); // Original id position of column
				}
			}
		}
		//==================================================================

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=14&val_json='+encodeURIComponent(JSON.stringify(val));
		conf['fonction_a_executer_reponse'] = 'save_lines';
		conf['param_fonction_a_executer_reponse'] = "'"+evt+"','"+lisha_id+"','"+up_mode+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try
		{
			// Hide the wait div
			lisha_display_wait(lisha_id);

			// Get the ajax return in json format
			var json = get_json(ajax_return);


			if(json.lisha.error == 'false')
			{
				// An error has occured
				eval('var test = '+decodeURIComponent(json.lisha.error_col));

				for(var iterable_element in test) 
				{
					if(eval('test.'+iterable_element+'.status') == __FORBIDDEN__)
					{
						// forbidden
						document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).value = '';
						document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).disabled = 'true';
					}
					else
					{
						if(eval('test.'+iterable_element+'.status') == __LISTED__)
						{
							// listed
							document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).style.backgroundColor = '#D4D4FF';
						}
						else
						{
							// Required
							document.getElementById('th_input_'+eval('test.'+iterable_element+'.id')+'__'+lisha_id).style.backgroundColor = '#FFD4D4';
						}

					}
				}

				prompt_btn = new Array([lis_lib[31]],["lisha_cover_with_filter('"+lisha_id+"');"]);

				document.getElementById('lis_msgbox_conteneur_'+lisha_id).style.display = '';
				lisha_generer_msgbox(lisha_id,lis_lib[56],decodeURIComponent(json.lisha.error_str),'erreur','msg',prompt_btn);

				lisha_cover_with_filter(lisha_id);
			}
			else
			{
				// No error

				// Update the json
				eval(decodeURIComponent(json.lisha.json));

				// Set the content of the lisha
				lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

				// Set the content of the toolbar
				lisha_set_innerHTML('lisha_toolbar_'+lisha_id,decodeURIComponent(json.lisha.toolbar));

				document.getElementById('wait_input_'+lisha_id).style.display = 'none';

				switch(up_mode)
				{
					case 'update':
						// Call extra user event on update if any
						lisha_execute_event(__ON_UPDATE__,__AFTER__,lisha_id);
					break;
					case 'add':
						lisha_execute_event(__ON_ADD__,__AFTER__,lisha_id);
					break;
					default:
						alert('unknown mode !!');
				}
			}
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


function protect_json(p_value)
{
	p_value = p_value.replace(new RegExp('\\\\','g'),"\\\\");
	p_value = p_value.replace(new RegExp("'",'g'),"\\'");
	return p_value;
}