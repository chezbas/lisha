/**==================================================================
 * Global variables
====================================================================*/
var lisha_column_in_resize = false;
var lisha_column_in_move = false;
var lisha_size_start = 0;
var cursor_start = 0;
var lisha_column_resize = 0;
var lisha_column_move = 0;
var lisha_id_resize = 0;
var lisha_id_move = 0;
var click_id_column = '';
var lisha_body_style = '';
var lisha_doc_export = '';
var lisha_timer_check_export_done = '';
/**==================================================================*/


/**==================================================================
 * Resize a column to smallest size.
 * @column      : id of the column in resize
 * @lisha_id    : internal lisha identifier
 ====================================================================*/
function lisha_mini_size_column(column, lisha_id) {
	document.getElementById('th' + column + '_' + lisha_id).style.width = document.getElementById('span_' + column + '_' + lisha_id).offsetWidth + 'px';
	lisha_column_resize = column;
	lisha_id_resize = lisha_id;

	lisha_column_in_resize = true;
	lisha_size_start = document.getElementById('th' + column + '_' + lisha_id).offsetWidth;
	click_id_column = 'th' + (column) + '_' + lisha_id;
	size_table(lisha_id);
	lisha_column_in_resize = false;
}
/**==================================================================*/


/**==================================================================
 * TODO : Add comment
====================================================================*/
function addCss(cssCode)
{
	var styleElement = document.createElement("style");

	styleElement.type = "text/css";

	if (styleElement.styleSheet)
	{
		styleElement.styleSheet.cssText = cssCode;
	}
	else
	{
		styleElement.appendChild(document.createTextNode(cssCode));
	}

	document.getElementsByTagName("head")[0].appendChild(styleElement);
}
/**==================================================================*/


/**==================================================================
 * export_list	: Do a local extraction of filter or selected lines
 * @evt			: catch event
 * @line		: line of cell
 * @lisha_id	: internal lisha identifier
 * @ajax_return : return ajax if any
 ====================================================================*/
function export_list(lisha_id,ajax_return)
{

	if(lisha_doc_export == '')
	{
		// Free export ( no export already in progress )
		if(typeof(ajax_return) == 'undefined')
		{
			var excel_button = 'lisha_td_toolbar_excel_'+lisha_id;
			if(document.getElementById(excel_button).className.search("grey_el") == -1)
			{
				// Display the wait div
				lisha_display_wait(lisha_id);

				//==================================================================
				// Setup Ajax configuration
				// Load estimation
				//==================================================================
				var conf = [];

				conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
				conf['delai_tentative'] = 100000;
				conf['max_tentative'] = 2;
				conf['type_retour'] = false;		// ReponseText
				conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=24&lines='+encodeURIComponent(get_selected_lines(lisha_id));
				conf['fonction_a_executer_reponse'] = 'export_list';
				conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";

				conf['fonction_a_executer_cas_non_reponse '] = 'timeout_export_list';
				conf['param_fonction_a_executer_cas_non_reponse'] = "'"+lisha_id+"'";

				ajax_call(conf);
				//==================================================================
			}
			else
			{
				// Export button is grey, nothing to do
			}
		}	
		else
		{
			// Prompt export confirmation with amount of rows
			var retour = JSON.parse(ajax_return);
			if(retour.TOTAL != 0)
			{
				if(retour.MAX == 'NA')
				{
					var prompt_btn = new Array([lis_lib[31],lis_lib[32]],["launch_export('"+lisha_id+"','"+retour.TOTAL+"')","cancel_export('"+lisha_id+"');"]);

					lisha_generer_msgbox(lisha_id,lis_lib[113],lis_lib[114].replace(/\$1/g,retour.TOTAL),'info','msg',prompt_btn,false,false);
				}
				else
				{
					// Max number of rows reached...
					var prompt_btn = new Array([lis_lib[31],lis_lib[32]],["launch_export('"+lisha_id+"','"+retour.MAX+"')","cancel_export('"+lisha_id+"');"]);

					lisha_generer_msgbox(lisha_id,lis_lib[113],lis_lib[130].replace(/\$1/g,retour.TOTAL).replace(/\$2/g,retour.MAX),'info','msg',prompt_btn,false,false);
				}
			}
			else
			{
				// Nothing to export...
				// Force refresh content of lisha
				lisha_page_change_ajax(lisha_id,1);
				var prompt_btn = new Array([lis_lib[31]],["cancel_export('"+lisha_id+"');"]);

				lisha_generer_msgbox(lisha_id,lis_lib[117],lis_lib[116].replace(/\$1/g,ajax_return),'info','msg',prompt_btn,false,false);
			}	
		}
	}
	else
	{
		alert('Export already running...');
	}
}
/**==================================================================*/


/**==================================================================
 * Too many rows to export.... Cancel with Timeout error message
 *  TODO Not yet tested...
 * @lisha_id	: internal lisha identifier
 ====================================================================*/
function timeout_export_list(lisha_id)
{
	var prompt_btn = new Array([lis_lib[31]],["cancel_export('"+lisha_id+"');"]);
	lisha_generer_msgbox(lisha_id,'xxxxxx',lis_lib[116],'info','msg',prompt_btn,false,false);
}
/**==================================================================*/


/**==================================================================
 * cancel_export	: Cancel export on first step
 *
 * @lisha_id	: internal lisha identifier
 ====================================================================*/
function cancel_export(lisha_id)
{
	lisha_doc_export = '';
	lisha_cover_with_filter(lisha_id);
}
/**==================================================================*/


/**==================================================================
 * launch_export	: Launch excel export 
 * @lisha_id	: internal lisha identifier
 * @total 		: Total rows to export
 ====================================================================*/
function launch_export(lisha_id, total)
{
	lisha_cover_with_filter(lisha_id); // Hide current dialogue box

	//==================================================================
	// Download export file to local directory
	//==================================================================
	var file = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_export.php?ssid='+ssid+'&lisha_id='+lisha_id+'&lines='+encodeURIComponent(get_selected_lines(lisha_id));
	// Launch export
	lisha_doc_export = window.open(file,'');
	//==================================================================

	// Keep focus in main window
	this.focus();

	initial_export_box(lisha_id, total);
}
/**==================================================================*/


/**==================================================================
 * Setup export message box
 *
 * @lisha_id	: internal lisha identifier
 * @total 		: Total rows to export
 ====================================================================*/
function initial_export_box(lisha_id, total)
{
	if(lisha_doc_export == undefined)
	{
		var prompt_btn = new Array([lis_lib[31]],["lisha_cover_with_filter('"+lisha_id+"');"]);

		lisha_generer_msgbox(lisha_id,lis_lib[111],lis_lib[112],'erreur','msg', prompt_btn, false, false);

		lisha_doc_export= '';
	}
	else
	{
		// Setup timer
		lisha_timer_check_export_done = setInterval(function(){check_export_downloading(lisha_id);},2000);

		var prompt_btn = new Array([lis_lib[32]],["cancel_export('"+lisha_id+"');"]);		
		lisha_generer_msgbox(lisha_id,lis_lib[115],'','....','msg', prompt_btn, false, true);
		lisha_cover_with_filter(lisha_id);
	}
}
/**==================================================================*/


/**==================================================================
 * check_export_downloading	: check if export is done
 * @lisha_id	: internal lisha identifier
 ====================================================================*/
function check_export_downloading(lisha_id)
{
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var conf = [];

	conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_export_progress.php';
	conf['delai_tentative'] = 100000;
	conf['max_tentative'] = 2;
	conf['type_retour'] = false;		// ReponseText
	conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid');
	conf['fonction_a_executer_reponse'] = 'check_export_list_progress';
	conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";

	ajax_call(conf);
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * Display global message to user ( Message box in lisha )
 * @title	:	Title of message box
 * @body	:	Body detail
 ====================================================================*/
function no_contextual_help(title,body)
{
	var prompt_btn = new Array([lis_lib[31]],["lisha_cover_with_filter('"+g_lisha_id+"');"]);
	lisha_cover_with_filter(g_lisha_id);
	lisha_generer_msgbox(g_lisha_id,title,body,'','msg',prompt_btn);
}
/**==================================================================*/


/**==================================================================
 * export_list_done	: check if export is done
 * @lisha_id	: internal lisha identifier
 * @ajax_return : return ajax if any
 ====================================================================*/
function check_export_list_progress(lisha_id, ajax_return)
{
	ajax_return = JSON.parse(ajax_return);

	// Check export status
	if(ajax_return.STATUS == 'X')
	{
		// Export achieve
		clearInterval(lisha_timer_check_export_done);

		lisha_doc_export.close();
		lisha_doc_export= '';
		lisha_timer_check_export_done = '';

		var prompt_btn = new Array([lis_lib[31]],["lisha_cover_with_filter('"+lisha_id+"');"]);		
		lisha_generer_msgbox(lisha_id,lis_lib[109],lis_lib[110].replace(/\$1/g,'my_export.csv').replace(/\$2/g,ajax_return.TOTAL),'','msg', prompt_btn, false, false);
	}
	else
	{
		if(ajax_return.STATUS == 'C')
		{
			// Downloading export Canceled
		}
		else
		{
			// Downloading in progress ( Doesn't work cause none read only session in php :( )
			//var prompt_btn = new Array([lis_lib[32]],["cancel_export('"+lisha_id+"');"]);		
			//lisha_generer_msgbox(lisha_id,lis_lib[115],ajax_return.CURRENT+' / '+ajax_return.TOTAL,'','msg', prompt_btn, false, false);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Called when a checkbox or a line was clicked to be selected.
 * 
 * @param line Line of the checkbox
 * @param evt : event handle
 * @param checkbox : null if is a line, otherwise checkbox
 * @param id : Unique lisha identifier
====================================================================*/
function lisha_checkbox(line,evt,checkbox,id)
{
	try 
	{
		lisha_click(evt,id);

		var jsoncolorindex = eval('lisha.'+id+'.lines.L'+line+'.colorkey');

		if(checkbox == null)
		{
			// A line was clicked
			if(document.getElementById('chk_l'+line+'_c0_'+id).checked)
			{
				// Unselect
				document.getElementById('l'+line+'_'+id).className = 'lc_'+jsoncolorindex+'_'+id;

				document.getElementById('chk_l'+line+'_c0_'+id).checked = false;

				eval('lisha.'+id+'.selected_line.L'+line+' = lisha.'+id+'.lines.L'+line+';');
				eval('lisha.'+id+'.selected_line.L'+line+'.selected = false;'); // SRX_UNCHECKBOX_JSON_FEATURE

				if(count_selected_lines(id) == 0)
				{
					if(document.getElementById('lisha_td_toolbar_edit_'+id))document.getElementById('lisha_td_toolbar_edit_'+id).className = 'btn_toolbar grey_el';
					if(document.getElementById('lisha_td_toolbar_delete_'+id))document.getElementById('lisha_td_toolbar_delete_'+id).className = 'btn_toolbar toolbar_separator_right grey_el';
				}
			}
			else
			{
				// Select
				if(document.getElementById('lisha_td_toolbar_edit_'+id))document.getElementById('lisha_td_toolbar_edit_'+id).className = 'btn_toolbar';
				if(document.getElementById('lisha_td_toolbar_delete_'+id))document.getElementById('lisha_td_toolbar_delete_'+id).className = 'btn_toolbar toolbar_separator_right';
				if(eval('lisha.'+id+'.return_mode') == __MULTIPLE__)
				{
					document.getElementById('l'+line+'_'+id).className = 'line_selected_color_'+jsoncolorindex+'_'+id;

					document.getElementById('chk_l'+line+'_c0_'+id).checked = true;
					eval('lisha.'+id+'.selected_line.L'+line+' = lisha.'+id+'.lines.L'+line+';');
					eval('lisha.'+id+'.selected_line.L'+line+'.selected = true;');
				}
				else
				{
					if(eval('lisha.'+id+'.mode') == __LMOD__)
					{
						// LMOD click, simple return
						lisha_lmod_click(id,line);

						// Stop the event handler
						lisha_StopEventHandler(evt);
					}
					else
					{
						// A line was clicked
						if(line != null)
						{
							var return_value = lisha_get_innerHTML('div_td_l'+line+'_c'+eval('lisha.'+id+'.c_col_return_id')+'_'+id);
							//var reg = new RegExp('(\\\\)','g');
							//return_value = return_value.replace(reg,"\\\\");
							return_value = protect_json(return_value);

							eval('lisha.'+id+'.col_return_last_value = \''+return_value+'\';');
							lisha_execute_event(__ON_LMOD_INSERT__,__AFTER__,id);
						}
					}
				}
			}
		}
		else
		{
			// A checkbox was clicked
			var evt = (evt) ? evt : event;

			if(evt.shiftKey && eval('lisha.'+id+'.last_checked') != 0)
			{
				// Shift case was pressed
				var min = Math.min(line,eval('lisha.'+id+'.last_checked'));
				var max = Math.max(line,eval('lisha.'+id+'.last_checked'));

				if(!document.getElementById('chk_l'+line+'_c0_'+id).checked)
				{
					for(i=min;i<=max;i++)
					{
						// Unselect
						document.getElementById('chk_l'+i+'_c0_'+id).checked = false;

						var jsoncolorindex = eval('lisha.'+id+'.lines.L'+i+'.colorkey');
						document.getElementById('l'+i+'_'+id).className = 'lc_'+jsoncolorindex+'_'+id;

						eval('lisha.'+id+'.selected_line.L'+i+' = lisha.'+id+'.lines.L'+i+';');
						eval('lisha.'+id+'.selected_line.L'+i+'.selected = false;'); // SRX_UNCHECKBOX_JSON_FEATURE

					}
					if(count_selected_lines(id) == 0)
					{
						if(document.getElementById('lisha_td_toolbar_edit_'+id))document.getElementById('lisha_td_toolbar_edit_'+id).className = 'btn_toolbar grey_el';
						if(document.getElementById('lisha_td_toolbar_delete_'+id))document.getElementById('lisha_td_toolbar_delete_'+id).className = 'btn_toolbar toolbar_separator_right grey_el';
					}
				}
				else
				{
					// Select
					if(document.getElementById('lisha_td_toolbar_edit_'+id))document.getElementById('lisha_td_toolbar_edit_'+id).className = 'btn_toolbar';
					if(document.getElementById('lisha_td_toolbar_delete_'+id))document.getElementById('lisha_td_toolbar_delete_'+id).className = 'btn_toolbar toolbar_separator_right';
					for(i=min;i<=max;i++)
					{
						document.getElementById('chk_l'+i+'_c0_'+id).checked = true;

						var jsoncolorindex = eval('lisha.'+id+'.lines.L'+i+'.colorkey');
						document.getElementById('l'+i+'_'+id).className = 'line_selected_color_'+jsoncolorindex+'_'+id;

						eval('lisha.'+id+'.selected_line.L'+i+' = lisha.'+id+'.lines.L'+i+';');
						eval('lisha.'+id+'.selected_line.L'+i+'.selected = true;');
					}
				}
			}
			else
			{
				if(!document.getElementById('chk_l'+line+'_c0_'+id).checked)
				{
					// Unselect
					document.getElementById('l'+line+'_'+id).className = 'lc_'+jsoncolorindex+'_'+id;

					eval('lisha.'+id+'.selected_line.L'+line+' = lisha.'+id+'.lines.L'+line+';');
					eval('lisha.'+id+'.selected_line.L'+line+'.selected = false;'); // SRX_UNCHECKBOX_JSON_FEATURE

					if(count_selected_lines(id) == 0)
					{
						if(document.getElementById('lisha_td_toolbar_edit_'+id))document.getElementById('lisha_td_toolbar_edit_'+id).className = 'btn_toolbar grey_el';
						if(document.getElementById('lisha_td_toolbar_delete_'+id))document.getElementById('lisha_td_toolbar_delete_'+id).className = 'btn_toolbar toolbar_separator_right grey_el';
					}
				}
				else
				{
					// Select
					if(document.getElementById('lisha_td_toolbar_edit_'+id))document.getElementById('lisha_td_toolbar_edit_'+id).className = 'btn_toolbar';
					if(document.getElementById('lisha_td_toolbar_delete_'+id))document.getElementById('lisha_td_toolbar_delete_'+id).className = 'btn_toolbar toolbar_separator_right';

					document.getElementById('l'+line+'_'+id).className = 'line_selected_color_'+jsoncolorindex+'_'+id;

					eval('lisha.'+id+'.selected_line.L'+line+' = lisha.'+id+'.lines.L'+line+';');
					eval('lisha.'+id+'.selected_line.L'+line+'.selected = true;');
				}
			}
		}

		eval('lisha.'+id+'.last_checked = '+line);
		if(evt != null)
		{
			lisha_StopEventHandler(evt);
		}
	}
	catch(e) 
	{
		lisha_display_error(id,e,'aff1');
	}
}
/**==================================================================*/


/**==================================================================
 * list_columns : Display list of columns to setup
 * @lisha_id		: internal lisha identifier
 * @lisha_type		: Kind of internal sub lisha
 * @ajax_return 	: return ajax if any
 ====================================================================*/
function list_columns(lisha_id, lisha_type, ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_cover_with_filter(lisha_id);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/internal.php';
		conf['delai_tentative'] = 5000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&lisha_type='+lisha_type;
		conf['fonction_a_executer_reponse'] = 'list_columns';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+lisha_type+"'";

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
			lisha_set_innerHTML('internal_lisha_'+lisha_id,decodeURIComponent(json.lisha.content));			

			// Manage ok button hide/show
			if(eval('lisha.'+lisha_id+'_child.button.valide') == 'ok')
			{
				document.getElementById(lisha_id+'_child_valide').style.display = 'block';
			}
			else
			{
				document.getElementById(lisha_id+'_child_valide').style.display = 'none';
			}

			// Display the internal lisha
			document.getElementById('internal_lisha_'+lisha_id).style.display = 'block';

			// Init the opened child flag
			eval('lisha.'+lisha_id+'.lisha_child_opened = 1;');

			// Sub lisha size
			document.getElementById('internal_lisha_'+lisha_id).style.width = '500px';
			document.getElementById('internal_lisha_'+lisha_id).style.top = '24px';


			if(document.getElementById('liste_'+lisha_id).offsetHeight < 422)
			{
				document.getElementById('internal_lisha_'+lisha_id).style.height = document.getElementById('liste_'+lisha_id).offsetHeight+22+'px';
				document.getElementById('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'_child__').style.height = document.getElementById('liste_'+lisha_id).offsetHeight+'px';
			}
			else
			{
				document.getElementById('internal_lisha_'+lisha_id).style.height = '322px';
			}

			// _button_columns_list
			var pos_item = FindXY(document.getElementById(lisha_id+'_button_columns_list'),lisha_id);
			var pos_x = pos_item.x - document.getElementById('liste_'+lisha_id).scrollLeft;

			document.getElementById('internal_lisha_'+lisha_id).style.left =  pos_x+'px';

			// Compute sub lisha real top position
			internal_sub_lisha_toolbar_top_position(lisha_id);

			size_table(lisha_id+'_child');

			//==================================================================
			// Setup default focus
			//==================================================================
			var id_focus = "th_input_"+eval('lisha.'+lisha_id+'_child.default_input_focus')+"__"+lisha_id+'_child';

			try
			{
				document.getElementById(id_focus).focus();
			}
			catch(e)
			{
				// Nothing
			}
			//==================================================================
		}		
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'column list available');
		}
	}
}
/**==================================================================*/


/**
 * Open a lisha to load a filter
 * @param lisha_id  Id of the lisha
 * @param lisha_type Type of internal lisha
 * @param ajax_return return ajax
 */
function lisha_load_filter_lov(lisha_id,lisha_type,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_cover_with_filter(lisha_id);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/internal.php';
		conf['delai_tentative'] = 5000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&lisha_type='+lisha_type;
		conf['fonction_a_executer_reponse'] = 'lisha_load_filter_lov';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+lisha_type+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try 
		{
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Display the internal lisha
			document.getElementById('internal_lisha_'+lisha_id).style.display = 'block';

			// Init the opened child flag
			eval('lisha.'+lisha_id+'.lisha_child_opened = 1;');

			// Update the json object
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_innerHTML('internal_lisha_'+lisha_id,decodeURIComponent(json.lisha.content));

			// Hide the wait div
			document.getElementById('internal_lisha_'+lisha_id).style.width = '500px';
			document.getElementById('internal_lisha_'+lisha_id).style.top = '24px';

			if(document.getElementById('liste_'+lisha_id).offsetHeight < 422)
			{
				document.getElementById('internal_lisha_'+lisha_id).style.height = document.getElementById('liste_'+lisha_id).offsetHeight+22+'px';
				document.getElementById('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'_child__').style.height = document.getElementById('liste_'+lisha_id).offsetHeight+'px';
			}
			else
			{
				document.getElementById('internal_lisha_'+lisha_id).style.height = '322px';
			}

			// _button_load_filter
			var pos_item = FindXY(document.getElementById(lisha_id+'_button_load_filter'),lisha_id);

			var pos_x = pos_item.x - document.getElementById('liste_'+lisha_id).scrollLeft;

			document.getElementById('internal_lisha_'+lisha_id).style.left =  pos_x+'px';

			// Compute sub lisha real top position
			internal_sub_lisha_toolbar_top_position(lisha_id);

			size_table(lisha_id+'_child');

			// Browser bug, force refresh sub lisha
			lisha_reset(lisha_id+'_child');
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'aff2');
		}
	}
}


/**==================================================================
 * Sub internal lisha position of window from toolbar menu
 *
 * @lisha_id	: Internal lisha identifier
 ====================================================================*/
function internal_sub_lisha_toolbar_top_position(lisha_id){
	var myid = 'lisha_toolbar_'+lisha_id;
	var my_title = 'lisha_title_'+lisha_id;

	var title_height = 0;	// title bar disable
	if(document.getElementById(my_title) != undefined)
	{
		// Title bar exist
		title_height = document.getElementById(my_title).offsetHeight;
	}

	document.getElementById('internal_lisha_'+lisha_id).style.top = document.getElementById(myid).offsetHeight + title_height+'px';
}
/**==================================================================*/


/**==================================================================
 * Return left and top position of a HTML item into a lisha
 * @obj 		: from document.getElementById
 * @lisha_id	: Internal lisha identifier
====================================================================*/
function FindXY(obj, lisha_id){
	var x=0,y=0;
	while (obj!=null){
	 x+=obj.offsetLeft-obj.scrollLeft;
	 y+=obj.offsetTop-obj.scrollTop;
	 obj=obj.offsetParent;
	}
	var item = document.getElementById('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__').offsetParent;

	if(item.style.left == "")
	{
		x = x-item.offsetLeft;
	}
	else
	{
		x = x-item.style.left.replace("px","");
	}
	x = x + document.getElementById('liste_'+lisha_id).scrollLeft;

	if(item.style.top == "")
	{
		y = y-item.offsetTop;
	}
	else
	{
		y = y-item.style.top.replace("px","");
	}

	return {x:x,y:y};
	}
/**==================================================================*/


/**
 * Open a lisha to load a filter
 * @param lisha_id  Id of the lisha
 * @param lisha_type Type of internal lisha
 * @param ajax_return return ajax
 */
function lisha_hide_display_col_lov(lisha_id,lisha_type,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_cover_with_filter(lisha_id);
		/**==================================================================
		 * Ajax init
		 ====================================================================*/	
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/internal.php';
		conf['delai_tentative'] = 6000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&lisha_type='+lisha_type;
		conf['fonction_a_executer_reponse'] = 'lisha_load_filter_lov';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+lisha_type+"'";

		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		try 
		{
			var json = get_json(ajax_return);
			document.getElementById('internal_lisha_'+lisha_id).style.display = 'block';

			eval('lisha.'+lisha_id+'.lisha_child_opened = 1;');

			// Update the json object
			eval(decodeURIComponent(json.lisha.json));

			// Set the content of the lisha
			lisha_set_innerHTML('internal_lisha_'+lisha_id,decodeURIComponent(json.lisha.content));

			// Hide the wait div
			document.getElementById('internal_lisha_'+lisha_id).style.width = '500px';
			if(document.getElementById('liste_'+lisha_id).offsetHeight < 422)
			{
				document.getElementById('internal_lisha_'+lisha_id).style.height = document.getElementById('liste_'+lisha_id).offsetHeight+22+'px';
				document.getElementById('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'_child__').style.height = document.getElementById('liste_'+lisha_id).offsetHeight+'px';
			}
			else
			{
				document.getElementById('internal_lisha_'+lisha_id).style.height = '422px';
			}
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'aff3');
		}
	}
}

/**==================================================================
 * function call when user click on a line
 * @lisha_id 	: parent lisha id
 * @filter_name : filter name to restore
 * @ajax_return : filled with ajax call back return
 ====================================================================*/
function lisha_load_filter(lisha_id,filter_name,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		var filter_name = lisha_get_innerText(filter_name);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 6000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;									// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=17&filter_name='+filter_name;
		conf['fonction_a_executer_reponse'] = 'lisha_load_filter';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+filter_name+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		lisha_hide_wait(lisha_id);
		try
		{
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Update the json object
			eval(decodeURIComponent(json.lisha.json));
			eval(decodeURIComponent(json.lisha.json_line));

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
		}
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'aff4');
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Display kick help when mouse hover an area
 * Setup help index if user press CTRL + F11
 *
 * @id_lib      : index of text to display
 * @id_help     : index of help page node to display if CTRL + F11 pressed
 * @lisha_id    : lisha internal id
 * @mode        : if user documentation is active then contextual documentation ( CTRL + F11 ) is active too
 ====================================================================*/
function lisha_lib_hover(id_lib,id_help,lisha_id,mode)
{
	if(!lisha_column_in_resize)
	{
		lisha_set_innerHTML('lis__lisha_help_hover_'+lisha_id+'__',lis_lib[id_lib]);
	}

	// Only if user documentation is turn on
	// copy help page node index to global javascript variable for document event

	if(mode == '1')
	{
		// g_help_page is global javascript variable defined in class_lisha
		g_help_page = id_help;
	}
	else
	{
		// Disable contextual help
		// update lisha id in javascript of page
		g_help_page = '';
		g_lisha_id = lisha_id;
	}
}
/**==================================================================*/


/**
 * Hide the detail of an element
 * 
 * @param lisha_id Id of the lisha
 */
function lisha_lib_out(lisha_id)
{
	if(!lisha_column_in_resize)
	{
		lisha_set_innerHTML('lis__lisha_help_hover_'+lisha_id+'__','');
	}
}


/**==================================================================
 * Resize column width : Call when started to resize
 *
 * @column  :   Column identifier
 * @id      :   Lisha internal identifier
 ====================================================================*/
function lisha_resize_column_start(column,id)
{
	document.getElementById('header_'+id).className += ' __body_no_select';
	if(document.getElementById('lisha_header_page_selection_'+id))
	{
		document.getElementById('lisha_header_page_selection_'+id).className += ' __body_no_select';
	}

	lisha_column_resize = column;
	lisha_id_resize = id;

	lisha_column_in_resize = true;
	lisha_size_start = document.getElementById('th'+column+'_'+id).offsetWidth;
	click_id_column = 'th'+(column)+'_'+id;

	lisha_body_style = document.body.className;
	document.body.className += ' __body_no_select';

	// IE
	if(typeof(document.body.onselectstart) != "undefined") 
	{
		document.body.onselectstart = function(){return false;};
	}
}
/**==================================================================*/


/**==================================================================
 * Resize column width : Call when mouse button release
 *
 ====================================================================*/
function lisha_resize_column_stop()
{
	lisha_set_innerHTML('lis__lisha_help_hover_'+lisha_id_resize+'__','');
	document.getElementById('header_'+lisha_id_resize).className = '__'+eval('lisha.'+lisha_id_resize+'.theme')+'__lisha_header__';
	if(document.getElementById('lisha_header_page_selection_'+lisha_id_resize))
	{
		document.getElementById('lisha_header_page_selection_'+lisha_id_resize).className = '__'+eval('lisha.'+lisha_id_resize+'.theme')+'_lisha_header_page_selection';
	}

	// Resize the column
	size_table(lisha_id_resize);
	lisha_column_in_resize = false;
	document.body.className = lisha_body_style;

	// IE
	if(typeof(document.body.onselectstart) != "undefined") 
	{
		document.body.onselectstart = null;
	}
}
/**==================================================================*/


function lisha_hide_container_click(lisha_id)
{
	if(eval('lisha.'+lisha_id+'.lisha_child_opened') != false)
	{
		 lisha_child_cancel(lisha_id,eval('lisha.'+lisha_id+'.lisha_child_opened'));
	}
}

/**==================================================================
 * lisha_set_content : draw lisha html content
 *
 * @lisha_id : internal lisha identifier
 * @content : HTML lisha result to draw
 ====================================================================*/
function lisha_set_content(lisha_id,content)
{
	// Get the position of the scrollbar
	var scroll_before = document.getElementById('liste_'+lisha_id).scrollLeft;

	// Set the content to the lisha
	lisha_set_innerHTML('lisha_ajax_return_'+lisha_id,content);

	// Compute columns size
	size_table(lisha_id);
	//==================================================================
	// Re-Activate the lisha horizontal scroll
	// Only if main lisha
	//==================================================================
	scroll_before = scroll_before + 2; // SRX_UGLY_WORK_AROUND

	document.getElementById('liste_'+lisha_id).onscroll = function(){lisha_horizontal_scroll(lisha_id);};
	document.getElementById('liste_'+lisha_id).scrollLeft = scroll_before;
	//==================================================================

	//==================================================================
	// Setup default focus
	//==================================================================
	var id_focus = "th_input_"+eval('lisha.'+lisha_id+'.default_input_focus')+"__"+lisha_id;
	try
	{
		document.getElementById(id_focus).focus();
	}
	catch(e)
	{
		// Nothing
	}
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * lisha_resize_column : Resize column
 * @e : event
 ====================================================================*/
function lisha_resize_column(e)
{
	if(cursor_start == 0)
	{
		cursor_start = e.clientX;
	}

	// Resize only if the elements fit into the cell
	if(((lisha_size_start+(e.clientX - cursor_start)) > document.getElementById('span_'+lisha_column_resize+'_'+lisha_id_resize).offsetWidth))
	{
		// lisha_set_innerHTML('lis__lisha_help_hover_'+lisha_id_resize+'__',lisha_size_start+(e.clientX - cursor_start)+" pixels");
		document.getElementById(click_id_column).style.width =  lisha_size_start+(e.clientX - cursor_start)+"px";
	}
}
/**==================================================================*/


/**
 * Handler when the cursor move
 * 
 * @param e event
 */
function lisha_move_cur(e)
{
	if(lisha_column_in_resize)
	{
		lisha_resize_column(e);
	}
	else
	{
		if(lisha_column_in_move)
		{
			lisha_move_column(e);
		}
	}
}

/**==================================================================
 * Catch event on mouseup
 ====================================================================*/
function lisha_mouseup()
{
	if(lisha_column_in_resize)
	{
		lisha_resize_column_stop();
	}
	else
	{
		if(lisha_column_in_move)
		{
			lisha_move_column_stop();
		}
	}
}
/**==================================================================*/


/**
 * Stop the event handler
 * 
 * @param e event
 */
function lisha_StopEventHandler(evt)
{
	var evt = (evt)?evt : event;

	if(typeof(window.event) != 'undefined')
	{
		window.event.cancelBubble = true;
	}
	else
	{
		evt.stopPropagation();
	}
}

/**==================================================================
 * Get the position of an element in reference to the body.
 * @id  : Dom id of element
 * return array position (left,top)
 ====================================================================*/
function lisha_getPosition(id)
{
	var left = 0;
	var top = 0;

	/* Get element */
	var element = document.getElementById(id);

	/* While the element have a parent */
	while(element.offsetParent != undefined && element.offsetParent != null)
	{
		/* Add the position of the parent element */
		if(element.clientLeft != null)
		{
			left += element.offsetLeft + element.clientLeft;
		}

		if(element.clientTop != null)
		{
			top += element.offsetTop + element.clientTop;
		}

		element = element.offsetParent;
	}

	top += element.offsetTop;

	var tab = new Array(left,top);

	return tab;
}
/**==================================================================*/


/**==================================================================
 * Toggle column menu
 * @id      : Internal lisha id
 * @column  : Column to display menu
 ====================================================================*/
function lisha_toggle_header_menu(id,column)
{
	eval('lisha.'+id+'.stop_click_event = true;');

	var div_menu = document.getElementById('lis_column_header_menu_'+id);

	if(div_menu.style.display == 'none' || div_menu.style.display == '')
	{
		// Display the menu icon
		document.getElementById('th_menu_'+column+'__'+id).className = '__'+eval('lisha.'+id+'.theme')+'_menu_header_click';

		// Enable the active menu
		eval('lisha.'+id+'.menu_opened_col = '+column+';');

		//==================================================================
		// Prepare sort column menu list
		//==================================================================
		var obj_order = new lisha_menu(id);

		if(eval('lisha.'+id+'.columns.c'+column+'.order') == 'ASC')
		{
			obj_order.add_line(lis_lib[18],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_sort-ascend',null,false,undefined,142,79);
		}
		else
		{
			obj_order.add_line(lis_lib[18],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_sort-ascend','lisha_column_order(\''+id+'\',__ASC__,'+column+',__NEW__)',true,undefined,141,80);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.order') == 'DESC')
		{
			obj_order.add_line(lis_lib[19],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_sort-descend',null,false,undefined,142,79);
		}
		else
		{
			obj_order.add_line(lis_lib[19],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_sort-descend','lisha_column_order(\''+id+'\',__DESC__,'+column+',__NEW__)',true,undefined,143,81);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.order') == '')
		{
			obj_order.add_line(lis_lib[42],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_sort_delete',null,false,undefined,142,79);
		}
		else
		{
			obj_order.add_line(lis_lib[42],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_sort_delete','lisha_column_order(\''+id+'\',__NONE__,'+column+',__NEW__)',true,undefined,42,82);
		}
		//==================================================================

		//==================================================================
		// Search mode operator
		//==================================================================
		var obj_search_mode = new lisha_menu(id);

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __EXACT__)
		{
			obj_search_mode.add_line(lis_lib[35],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_equal_operator',null,false,undefined,142,65);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[35],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_equal_operator','lisha_change_search_mode(\''+id+'\',__EXACT__,'+column+');',true,undefined,137,65);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __CONTAIN__)
		{
			obj_search_mode.add_line(lis_lib[151],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_contain_operator',null,false,undefined,142,75);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[151],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_contain_operator','lisha_change_search_mode(\''+id+'\',__CONTAIN__,'+column+');',true,undefined,152,75);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __PERCENT__)
		{
			obj_search_mode.add_line(lis_lib[36],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_like_operator',null,false,undefined,142,66);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[36],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_like_operator','lisha_change_search_mode(\''+id+'\',__PERCENT__,'+column+');',true,undefined,140,66);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __GT__)
		{
			obj_search_mode.add_line(lis_lib[132],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_greather_than_operator',null,false,undefined,142,70);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[132],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_greather_than_operator','lisha_change_search_mode(\''+id+'\',__GT__,'+column+');',true,undefined,147,70);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __LT__)
		{
			obj_search_mode.add_line(lis_lib[133],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_less_than_operator',null,false,undefined,142,71);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[133],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_less_than_operator','lisha_change_search_mode(\''+id+'\',__LT__,'+column+');',true,undefined,138,71);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __GE__)
		{
			obj_search_mode.add_line(lis_lib[134],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_greather_or_equal_than_operator',null,false,undefined,142,72);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[134],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_greather_or_equal_than_operator','lisha_change_search_mode(\''+id+'\',__GE__,'+column+');',true,undefined,139,72);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __LE__)
		{
			obj_search_mode.add_line(lis_lib[135],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_less_or_equal_than_operator',null,false,undefined,142,73);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[135],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_less_or_equal_than_operator','lisha_change_search_mode(\''+id+'\',__LE__,'+column+');',true,undefined,149,73);
		}

		if(eval('lisha.'+id+'.columns.c'+column+'.search_mode') == __NULL__)
		{
			obj_search_mode.add_line(lis_lib[136],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_null_operator',null,false,undefined,142,74);
		}
		else
		{
			obj_search_mode.add_line(lis_lib[136],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_null_operator','lisha_change_search_mode(\''+id+'\',__NULL__,'+column+');',true,undefined,150,74);
		}

		//==================================================================

		//==================================================================
		// Prepare alignment sub menu
		//==================================================================
		var obj_alignment = new lisha_menu(id);

		switch(eval('lisha.'+id+'.columns.c'+column+'.alignment'))
		{
		case __LEFT__:
			obj_alignment.add_line(lis_lib[47],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_left',null,false);
			obj_alignment.add_line(lis_lib[48],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_center','lisha_change_col_alignment(\''+id+'\',__CENTER__,'+column+');',true,undefined,48,86);
			obj_alignment.add_line(lis_lib[49],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_right','lisha_change_col_alignment(\''+id+'\',__RIGHT__,'+column+');',true,undefined,49,84);
			break;
		case __CENTER__:
			obj_alignment.add_line(lis_lib[47],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_left','lisha_change_col_alignment(\''+id+'\',__LEFT__,'+column+');',true,undefined,47,85);
			obj_alignment.add_line(lis_lib[48],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_center',null,false);
			obj_alignment.add_line(lis_lib[49],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_right','lisha_change_col_alignment(\''+id+'\',__RIGHT__,'+column+');',true,undefined,49,84);
			break;
		case __RIGHT__:
			obj_alignment.add_line(lis_lib[47],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_left','lisha_change_col_alignment(\''+id+'\',__LEFT__,'+column+');',true,undefined,47,85);
			obj_alignment.add_line(lis_lib[48],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_center','lisha_change_col_alignment(\''+id+'\',__CENTER__,'+column+');',true,undefined,48,86);
			obj_alignment.add_line(lis_lib[49],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_right',null,false);
			break;
		}
		//==================================================================

		//==================================================================
		// Load main menu
		//==================================================================
		var obj = new lisha_menu(id);

		// Calendar contextual menu
		if(eval('lisha.'+id+'.columns.c'+column+'.data_type') == 'date')
		{
			obj.add_line(lis_lib[66],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_calendar','lisha_generate_calendar(\''+id+'\','+column+');',true,undefined,145,31);
			obj.add_sep();
		}

		// Display Sort menu only in Display mode
		if(eval('lisha.'+id+'.edit_mode') == '__DISPLAY_MODE__')
		{
			obj.add_line(lis_lib[39],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_sort-ascend',null,true,obj_order,144,79);
		}

		// Display Alignment menu only in Display mode
		if(eval('lisha.'+id+'.edit_mode') == '__DISPLAY_MODE__')
		{
			// Alignment
			obj.add_line(lis_lib[46],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_left',null,true,obj_alignment,146,76);
		}
		// Advanced column filter menu
		if(eval('lisha.'+id+'.mode') != __CMOD__)
		{
			if(eval('lisha.'+id+'.edit_mode') == '__DISPLAY_MODE__')
			{
				obj.add_sep();
				// Search operator
				obj.add_line(lis_lib[41], '__' + eval('lisha.' + id + '.theme') + '_ico __' + eval('lisha.' + id + '.theme') + '_ico_advanced_search', 'column_advanced_filter(\'' + id + '\',' + column + ');', true, undefined, 41, 1);

				obj.add_line(lis_lib[34], '__' + eval('lisha.' + id + '.theme') + '_ico __' + eval('lisha.' + id + '.theme') + '_ico_filter', null, true, obj_search_mode, 34, 22);
				obj.add_sep();
			}
			var is_lovable = eval('lisha.'+id+'.columns.c'+column+'.is_lovable');

			if(is_lovable != undefined && is_lovable == true)
			{
				var line_enable = true;
			}
			else
			{
				var line_enable = false;
			}

			if(eval('lisha.'+id+'.columns.c'+column+'.lov_title') == eval('undefined'))
			{
				obj.add_line(lis_lib[44],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_values','lisha_display_internal_lis(\''+id+'\',__POSSIBLE_VALUES__,'+column+');',line_enable);
			}
			else
			{
				obj.add_line(eval('lisha.'+id+'.columns.c'+column+'.lov_title'),'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_values','lisha_display_internal_lis(\''+id+'\',__POSSIBLE_VALUES__,'+column+');',line_enable);
			}

			// Hide column contextual menu option
			if(eval('lisha.'+id+'.edit_mode') == '__DISPLAY_MODE__')
			{
				obj.add_sep();
				if (eval('lisha.' + id + '.c_col_return_id') != column && column != eval('lisha.' + id + '.default_input_focus')) {
					obj.add_line(lis_lib[20], '__' + eval('lisha.' + id + '.theme') + '_ico __' + eval('lisha.' + id + '.theme') + '_ico_sort-hide', 'lisha_toggle_column(\'' + id + '\',' + column + ');', true, undefined, 20, 29);
				}
				else
				{
					// Display in grey contextual hide column option because this is the focused column
					obj.add_line(lis_lib[20], '__' + eval('lisha.' + id + '.theme') + '_ico __' + eval('lisha.' + id + '.theme') + '_ico_sort-hide', 'lisha_toggle_column(\'' + id + '\',' + column + ');', false, undefined, 125, 29);
				}
			}
		}
		else
		{
			// SRX
			// Caution _child_child
			// Specific menu for CMOD lisha
			/*obj.add_sep();
			var is_lovable = eval('lisha.'+id+'.columns.c'+column+'.is_lovable');

			if(is_lovable != undefined && is_lovable == true)
			{
				var line_enable = true;
			}
			else
			{
				var line_enable = false;
			}

			if(eval('lisha.'+id+'.columns.c'+column+'.lov_title') == eval('undefined'))
			{
				obj.add_line(lis_lib[44],'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_values','lisha_display_internal_lis(\''+id+'\',__POSSIBLE_VALUES__,'+column+');',line_enable);
			}
			else
			{
				obj.add_line(eval('lisha.'+id+'.columns.c'+column+'.lov_title'),'__'+eval('lisha.'+id+'.theme')+'_ico __'+eval('lisha.'+id+'.theme')+'_ico_values','lisha_display_internal_lis(\''+id+'\',__POSSIBLE_VALUES__,'+column+');',line_enable);
			}
			*/
		}

		//==================================================================

		// Display the menu
		div_menu.style.display = 'block';
		div_menu.innerHTML = obj.display_menu();

		eval('lisha.'+id+'.obj_menu = obj');

		// Positions the menu
		lisha_position_menu(id,column);
	}
	else
	{
		// Hide the menu icon
		var is_lovable = eval('lisha.'+id+'.columns.c'+column+'.is_lovable');
		var lov_perso = eval('lisha.'+id+'.columns.c'+column+'.lov_perso');

		if(lov_perso != undefined && is_lovable != undefined && is_lovable == true)
		{
			document.getElementById('th_menu_'+eval('lisha.'+id+'.columns.c'+column+'.id')+'__'+id).className = '__'+eval('lisha.'+id+'.theme')+'_menu_header_lovable __'+eval('lisha.'+id+'.theme')+'_men_head';
		}
		else
		{
			if(lov_perso != undefined)
			{
				document.getElementById('th_menu_'+eval('lisha.'+id+'.columns.c'+column+'.id')+'__'+id).className = '__'+eval('lisha.'+id+'.theme')+'_menu_header_no_icon __'+eval('lisha.'+id+'.theme')+'_men_head';
			}
			else
			{
				document.getElementById('th_menu_'+eval('lisha.'+id+'.columns.c'+column+'.id')+'__'+id).className = '__'+eval('lisha.'+id+'.theme')+'_menu_header __'+eval('lisha.'+id+'.theme')+'_men_head';
			}
		}

		// Disable the active menu
		eval('lisha.'+id+'.menu_opened_col = false;');

		// Clear the content of the menu
		div_menu.innerHTML = '';

		// Hide the menu
		div_menu.style.display = 'none';
	}
}
/**==================================================================*/

function lisha_generate_calendar(lisha_id,column,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_display_wait(lisha_id);

		var id_read_input = 'th_input_'+column+'__'+lisha_id;
		/**==================================================================
		 * Ajax init
		 ====================================================================*/	
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=18&column='+column+'&input='+document.getElementById(id_read_input).value;
		conf['fonction_a_executer_reponse'] = 'lisha_generate_calendar';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"',"+column;

		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		try 
		{
			// Set the content to the calendar
			lisha_set_innerHTML('lis_column_calendar_'+lisha_id, ajax_return);

			//Display the calendar
			lisha_set_style_display('lis_column_calendar_'+lisha_id, 'block');

			var pos = lisha_getPosition('th_menu_'+column+'__'+lisha_id);

			var pos_th = document.getElementById('th_menu_'+column+'__'+lisha_id).offsetLeft - document.getElementById('liste_'+lisha_id).scrollLeft;
			document.getElementById('lis_column_calendar_'+lisha_id).style.left =  pos_th+'px';

			// Test if the menu is not out of the left corner of the lisha
			if((pos_th+156) > (lisha_get_el_offsetWidth('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__')))
			{
				document.getElementById('lis_column_calendar_'+lisha_id).style.left = '';
				document.getElementById('lis_column_calendar_'+lisha_id).style.right = '0px';
			}

			// Hide the wait div
			//lisha_display_wait(lisha_id);

		}
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'aff5');
		}
	}
}


/**==================================================================
 * Call when user display context column menu
 * @lisha_id	: internal lisha identifier
 * @column      : Column id involved
 ====================================================================*/
function lisha_position_menu(lisha_id,column)
{
	//==================================================================
	// top position
	//==================================================================
	var top_container = document.getElementById('conteneur_menu_'+lisha_id).offsetTop;
	document.getElementById('lis_column_header_menu_'+lisha_id).style.top = top_container+'px';
	//==================================================================

	//==================================================================
	// Left position
	//==================================================================
	var pos_th = document.getElementById('th_menu_'+column+'__'+lisha_id).offsetLeft - document.getElementById('liste_'+lisha_id).scrollLeft;
	document.getElementById('lis_column_header_menu_'+lisha_id).style.left = pos_th-5+'px';
	//==================================================================

	//==================================================================
	// Test menu position
	//==================================================================
	// Get the position of the menu
	var pos_menu = lisha_getPosition('lis_column_header_menu_'+lisha_id);

	// Get the position of the lisha
	var pos_lisha = lisha_getPosition('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__');

	// Test if the menu is not out of the right corner of the lisha
	if((pos_menu[0]+document.getElementById('menu_1_l1').offsetWidth) > (pos_lisha[0]+document.getElementById('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__').offsetWidth))
	{
		document.getElementById('lis_column_header_menu_'+lisha_id).style.left = '';
		document.getElementById('lis_column_header_menu_'+lisha_id).style.right = 0+'px';
	}
	//==================================================================

	eval('lisha.'+lisha_id+'.menu_left = '+document.getElementById('lis_column_header_menu_'+lisha_id).offsetLeft+';');
}
/**==================================================================*/


/**
 * Test if the lisha has a vertical scrollbar
 * @param id Id of the lisha
 * @returns {Boolean} True if vertical scrollbar present, false in other case.
 */
function lisha_has_vertical_scrollbar(id)
{
	/**================================================================== 
	 * Test if the lisha has vertical scrollbar
	 * ====================================================================*/	
	if(document.getElementById('liste_'+id).clientHeight < document.getElementById('liste_'+id).scrollHeight)
	{
		return true;		// Vertical scrollbar present
	}
	else
	{
		return false;		// No vertical scrollbar
	}
	/**==================================================================*/
}


/**
 * Handler when a the lisha is scrolled horizontaly
 * @param id_lisha Id of the lisha
 */
function lisha_horizontal_scroll(id_lisha)
{
	/**================================================================== 
	 * Move the header columns
	 *==================================================================*/	
	document.getElementById('header_'+id_lisha).scrollLeft = document.getElementById('liste_'+id_lisha).scrollLeft;
	/**==================================================================*/	

	/**================================================================== 
	 * Move the opened menu
	 * =================================================================*/	
	if(eval('lisha.'+id_lisha+'.menu_opened_col') != false)
	{
		var pos_th = lisha_getPosition('th_menu_'+eval('lisha.'+id_lisha+'.menu_opened_col')+'__'+id_lisha);

		var pos_menu = lisha_getPosition('lis_column_header_menu_'+id_lisha); // div_menu.offsetLeft;
		var pos_lisha = document.getElementById('lis__'+eval('lisha.'+id_lisha+'.theme')+'__lisha_table_'+id_lisha+'__').offsetLeft;
		if(pos_lisha >= pos_menu[0])
		{
			document.getElementById('lis_column_header_menu_'+id_lisha).style.left = eval('lisha.'+id_lisha+'.menu_left')-document.getElementById('liste_'+id_lisha).scrollLeft+'px';
		}
		else
		{
			 /**==================================================================
			 * Horizontal placement
			 *====================================================================*/
			var pos_th = document.getElementById('th_menu_'+eval('lisha.'+id_lisha+'.menu_opened_col')+'__'+id_lisha).offsetLeft - document.getElementById('liste_'+id_lisha).scrollLeft;
			document.getElementById('lis_column_header_menu_'+id_lisha).style.left = pos_th-document.getElementById('lis_column_header_menu_'+id_lisha).offsetWidth+19+'px';
			/**==================================================================*/
		}
	}
	else if(eval('lisha.'+id_lisha+'.menu_quick_search') != false)
	{
		var pos_th = lisha_getPosition('th_menu_'+eval('lisha.'+id_lisha+'.menu_quick_search_col')+'__'+id_lisha);
		var pos_menu = lisha_getPosition('lis_column_header_menu_'+id_lisha); // div_menu.offsetLeft;
		var pos_lisha = document.getElementById('lis__'+eval('lisha.'+id_lisha+'.theme')+'__lisha_table_'+id_lisha+'__').offsetLeft;
		if(pos_lisha >= pos_menu[0])
		{
			document.getElementById('lis_column_header_menu_'+id_lisha).style.left = eval('lisha.'+id_lisha+'.menu_left')-document.getElementById('liste_'+id_lisha).scrollLeft+'px';
		}
		else
		{
			 /** ==================================================================
			 * Horizontal placement
			 * ====================================================================*/
			var pos_th = document.getElementById('th_1_c'+eval('lisha.'+id_lisha+'.menu_quick_search_col')+'_'+id_lisha).offsetLeft - document.getElementById('liste_'+id_lisha).scrollLeft;
			document.getElementById('lis_column_header_menu_'+id_lisha).style.left = pos_th+'px';
			/** ================================================================== */
		}
	}
	else if(eval('lisha.'+id_lisha+'.lisha_child_opened') != false)
	{
		var pos_th = lisha_getPosition('th_menu_'+eval('lisha.'+id_lisha+'.lisha_child_opened')+'__'+id_lisha);
		var pos_menu = lisha_getPosition('lis_column_header_menu_'+id_lisha); // div_menu.offsetLeft;
		var pos_lisha = document.getElementById('lis__'+eval('lisha.'+id_lisha+'.theme')+'__lisha_table_'+id_lisha+'__').offsetLeft;
		if(pos_lisha >= pos_menu[0])
		{
			document.getElementById('internal_lisha_'+id_lisha).style.left = eval('lisha.'+id_lisha+'.menu_left')-document.getElementById('liste_'+id_lisha).scrollLeft+'px';
		}
		else
		{
			 /** ==================================================================
			 * Horizontal placement
			 * ====================================================================*/
			var pos_th = document.getElementById('th_1_c'+eval('lisha.'+id_lisha+'.menu_quick_search_col')+'_'+id_lisha).offsetLeft - document.getElementById('liste_'+id_lisha).scrollLeft;
			document.getElementById('internal_lisha_'+id).style.left = pos_th+19+'px';
			/** ================================================================== */
		}
	}
	/** ==================================================================*/	
}


function lisha_click(evt,lisha_id)
{
	if(eval('lisha.'+lisha_id) != eval('null'))
	{
		if(eval('lisha.'+lisha_id+'.stop_click_event') == eval('false'))
		{
			if(eval('lisha.'+lisha_id+'.menu_opened_col') != false)
			{
				// The lisha was clicked
				lisha_toggle_header_menu(lisha_id, eval('lisha.'+lisha_id+'.menu_opened_col'));
			}
			else if(eval('lisha.'+lisha_id+'.menu_quick_search') == true)
			{
				close_input_result(lisha_id);
			}
		}
		else
		{
			eval('lisha.'+lisha_id+'.stop_click_event = false;');
		}
	}
}

/**
 * Handler when mousedown
 * @param e event
 */
function lisha_mousedown(evt)
{
	var evt = (evt)?evt : event;

	for(var iterable_element in lisha) 
	{
		// Only if it is a lisha in lmod
		if(eval('lisha.'+iterable_element+'.mode') == 'lmod')
		{
			var id = 'lis__'+eval('lisha.'+iterable_element+'.theme')+'__lisha_table_'+iterable_element+'__';
			if(document.getElementById(id) != null)
			{
				// Get the position of the lisha
				//var pos = lisha_getPosition(id);
				var pos = getElementCoords(document.getElementById(id));

				var width = document.getElementById(id).offsetWidth;
				var height = document.getElementById(id).offsetHeight;

				if((evt.clientY < pos.top || evt.clientY > (pos.top+height)) || (evt.clientX < pos.left || evt.clientX > (pos.left+width)))
				{
					// The cursor is out of the lisha, close the lisha
					if(eval('lisha.'+iterable_element+'.lmod_opened') == true)
					{
						lisha_lmod_click(iterable_element);
					}

				}
			}
		}
	}	
}


/**
 * Display or hide a prompt in the lisha
 * @param id_lisha Id of the lisha
 * @param title Title of the prompt
 * @param txt text of the prompt
 */
function lisha_prompt(id_lisha,title,txt,button)
{
	lisha_cover_with_filter(id_lisha);
	var theme = eval('lisha.'+id_lisha+'.theme');
	if(document.getElementById('lis__'+theme+'__hide_container_'+id_lisha+'__').style.display == '' || document.getElementById('lis__'+theme+'__hide_container_'+id_lisha+'__').style.display == 'none')
	{
		document.getElementById('lis_msgbox_conteneur_'+id_lisha).style.display = 'none';
		lisha_set_innerHTML('lis_msgbox_conteneur_'+id_lisha,'');
	}
	else
	{
		document.getElementById('lis_msgbox_conteneur_'+id_lisha).style.display = '';
		if(isNaN(title))
		{
			lisha_generer_msgbox(id_lisha,title,txt,'','prompt',button);
		}
		else
		{
			lisha_generer_msgbox(id_lisha,lis_lib[title],lis_lib[txt],'','prompt',button);
		}

	}
}


/**==================================================================
 * Cover the lisha with a sub lisha filter (toggle function)
 * @lisha_id	: internal lisha identifier
 ====================================================================*/
function lisha_cover_with_filter(lisha_id)
{
	if(document.getElementById('liste_'+lisha_id) == undefined)
	{
		var scrowl_xpos = 0;
	}
	else
	{
		var scrowl_xpos = document.getElementById('liste_'+lisha_id).scrollLeft;
	}

	var theme = eval('lisha.'+lisha_id+'.theme');
	var footer_height;

	if(document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.display == '' || document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.display == 'none')
	{
		document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.top = document.getElementById('lisha_toolbar_'+lisha_id).offsetTop+'px';
		document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.width = document.getElementById('liste_'+lisha_id).offsetWidth+'px';

		if (document.getElementById('lisha_footer_page_selection_'+lisha_id) != undefined)	// SRX_FOOTER_PAGE_JS_ERROR
		{
			footer_height = document.getElementById('lisha_footer_page_selection_'+lisha_id).offsetTop;
		}
		else
		{
			footer_height = document.getElementById('lis__'+theme+'__lisha_table_'+lisha_id+'__').offsetHeight;
		}
		document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.height = footer_height-document.getElementById('lisha_ajax_return_'+lisha_id).offsetTop+50+'px';

		document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.display = 'block';
	}
	else
	{
		// Hide the cover div
		document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.display = 'none';

		// Erase the content of the wait div
		lisha_set_innerHTML('lis__'+theme+'__wait_'+lisha_id+'__','');

		// Erase the content of the msgbox div
		lisha_set_innerHTML('lis_msgbox_conteneur_'+lisha_id,'');

		// Back to default focued column : SRX_DEFAULT_FOCUS_COLUMN
		var my_default_focus_column = eval('lisha.'+lisha_id+'.default_input_focus');
		if(my_default_focus_column != false) // Check if a focus column is found
		{
			var myfocus = 'th_input_'+my_default_focus_column+'__'+lisha_id;

			// Possible if column is hidden
			if(document.getElementById(myfocus) != undefined)
			{
				document.getElementById(myfocus).focus();
			}
		}
	}

	//==================================================================
	// Work around to fixe lift issue when toggle on sub lisha 
	// SRX_fixe_lift_offset_position
	// Keep order of things
	// do +1
	// then
	// do -1
	//==================================================================
	document.getElementById('liste_'+lisha_id).scrollLeft = scrowl_xpos+1;
	document.getElementById('liste_'+lisha_id).scrollLeft = scrowl_xpos-1;
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * Call when user click to the button : save a filter
 * @lisha_id 	: lisha internal identifier
 ====================================================================*/
function lisha_display_prompt_create_filter(id_lisha)
{
	var prompt_btn = new Array([lis_lib[31],lis_lib[32]],["lisha_save_filter('"+id_lisha+"');","lisha_cover_with_filter('"+id_lisha+"');"]);
	lisha_prompt(id_lisha,3,30,prompt_btn);
}


/**==================================================================
 * Call when user try to save a filter
 * @lisha_id 	: lisha internal identifier
 * @ajax_return : null on original call then contains json php return of ajax call
 ====================================================================*/
function lisha_save_filter(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		//==================================================================
		// Get name of the filter
		//==================================================================
		var input_value = encodeURIComponent(document.getElementById('lisha_'+lisha_id+'_msgbox_prompt_value').value);
		lisha_cover_with_filter(lisha_id);
		//==================================================================

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// Reponse Text
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=7&name='+input_value;
		conf['fonction_a_executer_reponse'] = 'lisha_save_filter';
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
			if(decodeURIComponent(json.lisha.error) == 'true')
			{
				// An error occured
				msgbox(lisha_id,decodeURIComponent(json.lisha.title),decodeURIComponent(json.lisha.message));
			}
		}
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'aff6');
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Call when user click to the button : global search - Multiple columns search
 * @lisha_id 	    : lisha internal identifier
 * @global_search   : String for gloable search
 ====================================================================*/
function lisha_display_prompt_global_search(id_lisha,global_search)
{
	var prompt_btn = new Array([lis_lib[31],lis_lib[32]],["lisha_global_search('"+id_lisha+"');","lisha_cover_with_filter('"+id_lisha+"');"]);
	lisha_prompt(id_lisha,0,65,prompt_btn);

	// Force input value if any
	document.getElementById('lisha_'+id_lisha+'_msgbox_prompt_value').value = global_search.replace(/\'/g,"'");
}
/**==================================================================*/


/**==================================================================
 * Call when user launch a global search - Multiple columns search
 * @lisha_id 	: lisha internal identifier
 * @ajax_return : null on original call then contains json php return of ajax call
 ====================================================================*/
function lisha_global_search(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		// Prepare global search for cancel
		eval('varlisha_'+lisha_id+'globalsearch = "on"');

		//==================================================================
		// Get value for search
		//==================================================================
		var input_value = encodeURIComponent(JSON.stringify(document.getElementById('lisha_'+lisha_id+'_msgbox_prompt_value').value));
		//==================================================================

		var prompt_btn = new Array([lis_lib[32]],["cancel_gloabl_search('"+lisha_id+"');"]);
		lisha_generer_msgbox(lisha_id,lis_lib[153],lis_lib[154].replace(/\$1/g,document.getElementById('lisha_'+lisha_id+'_msgbox_prompt_value').value),'....','msg', prompt_btn, false, true);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// Reponse Text
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=11&value='+input_value;
		conf['fonction_a_executer_reponse'] = 'lisha_global_search';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try
		{
			if(eval('varlisha_'+lisha_id+'globalsearch') == "on")
			{
				// Search not canceled... continue
				eval('varlisha_'+lisha_id+'globalsearch = "off"');

				// Get the ajax return in json format
				var json = get_json(ajax_return);

				// Update the json object
				eval(decodeURIComponent(json.lisha.json));

				// Set the content of the lisha
				lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));

				document.getElementById('liste_'+lisha_id).scrollLeft = document.getElementById('liste_'+lisha_id).scrollLeft - 1; // SRX_UGLY_FIXE DUE TO BROWSER BUG

				// Set the content of the toolbar
				lisha_set_innerHTML('lisha_toolbar_'+lisha_id,decodeURIComponent(json.lisha.toolbar));

				// Setup Excel export button
				toolbar_excel_button(lisha_id);

				// Hide the wait div
				lisha_hide_wait(lisha_id);
			}
		}
		catch(e)
		{
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Call when user abort global search
 *
 * @lisha_id 	: lisha internal identifier
 ====================================================================*/
function cancel_gloabl_search(lisha_id)
{
	// Abort global search return
	eval('varlisha_'+lisha_id+'globalsearch = "off"');

	// Hide prompt window and back to lisha
	lisha_cover_with_filter(lisha_id);
}
/**==================================================================*/


/**==================================================================
 * Standard function to display wait window
 * @lisha_id 	: lisha internal identifier
 ====================================================================*/
function lisha_display_wait(lisha_id )
{
	lisha_cover_with_filter(lisha_id );

	var theme = eval('lisha.'+lisha_id +'.theme');

	if(document.getElementById('lis__'+theme+'__hide_container_'+lisha_id +'__').style.display == '' || document.getElementById('lis__'+theme+'__hide_container_'+lisha_id +'__').style.display == 'none')
	{
		document.getElementById('lis__'+theme+'__wait_'+lisha_id +'__').style.display = 'none';
	}
	else
	{
		document.getElementById('lis__'+theme+'__wait_'+lisha_id +'__').style.display = '';
		document.getElementById('lis__'+theme+'__wait_'+lisha_id +'__').style.margin = ((document.getElementById('lis__'+theme+'__hide_container_'+lisha_id +'__').offsetHeight-document.getElementById('lis__'+theme+'__wait_'+lisha_id +'__').offsetHeight)/2)+'px 0 0 '+((document.getElementById('liste_'+lisha_id ).offsetWidth-document.getElementById('lis__'+theme+'__wait_'+lisha_id +'__').offsetWidth)/2)+'px';
	}
}
/**==================================================================*/


/**==================================================================
 * Standard function to hide wait window
 * @lisha_id 	: lisha internal identifier
 ====================================================================*/
function lisha_hide_wait(lisha_id)
{
	var theme = eval('lisha.'+lisha_id+'.theme');

	// Hide the cover div
	document.getElementById('lis__'+theme+'__hide_container_'+lisha_id+'__').style.display = 'none';

	// Erase the content of the wait div
	lisha_set_innerHTML('lis__'+theme+'__wait_'+lisha_id+'__','');

	// Erase the content of the msgbox div
	lisha_set_innerHTML('lis_msgbox_conteneur_'+lisha_id,'');

	document.getElementById('lis__'+theme+'__wait_'+lisha_id+'__').style.display = 'none';
}
/**==================================================================*/


/**==================================================================
 * Standard message error
 * @lisha_id 	: lisha internal identifier
 * @e           : error message
 * @more        : further details ??
 ====================================================================*/
function lisha_display_error(id_lisha,e,more)
{
	var title = e.message;
	var file;
	var line;

	if(typeof(more) == undefined)
	{
		more = '';
	}

	if(e.sourceURL)
	{
		file = e.sourceURL;
	}
	else
	{
		file = e.fileName;
	}

	if(e.line)
	{
		line = e.line;
	}
	else
	{
		line = e.lineNumber;
	}

	if(document.getElementById('lis__'+eval('lisha.'+id_lisha+'.theme')+'__hide_container_'+id_lisha+'__').style.display == '' || document.getElementById('lis__'+eval('lisha.'+id_lisha+'.theme')+'__hide_container_'+id_lisha+'__').style.display == 'none')
	{
		lisha_cover_with_filter(id_lisha);
	}
	var prompt_btn = new Array([lis_lib[31]],["lisha_cover_with_filter('"+id_lisha+"');"]);

	document.getElementById('lis_msgbox_conteneur_'+id_lisha).style.display = '';

	lisha_generer_msgbox(id_lisha,lis_lib[74],lis_lib[75]+' <b>'+line+'</b> - <b>'+file+'</b><br />'+title+'<br />'+more,'erreur','msg',prompt_btn);

	document.getElementById('lis__'+eval('lisha.'+id_lisha+'.theme')+'__wait_'+id_lisha+'__').style.display = 'none';
}
/**==================================================================*/


/**==================================================================
 * Standard message box
 * @lisha_id    : lisha internal identifier
 * @title       : Title
 * @text        : message body
 ====================================================================*/
function msgbox(id_lisha,title,text)
{
	lisha_cover_with_filter(id_lisha);
	var prompt_btn = new Array([lis_lib[31]],["lisha_cover_with_filter('"+id_lisha+"');"]);

	document.getElementById('lis_msgbox_conteneur_'+id_lisha).style.display = '';
	lisha_generer_msgbox(id_lisha,title,text,'info','msg',prompt_btn);
}
/**==================================================================*/