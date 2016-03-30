/**==================================================================
 * Call when you delete rows into load custom filter
 * @lisha_id		: id of lisha parent
 ====================================================================*/
function lisha_child_delete_load_entry(lisha_id)
{
	lisha_child_cancel(lisha_id,1);
	lisha_refresh_page_ajax(lisha_id);
}
/**==================================================================*/


/**==================================================================
 * Return clicked value to parent
 * @el              : Clicked row
 * @parent		    : id of lisha parent
 * @parent_column   : parent column identifier
 ====================================================================*/
function lisha_child_insert_into_parent(el,parent,parent_column)
{
	eval('lisha.'+parent+'.lisha_child_opened = false;');

	//alert(eval('lisha.'+parent+'.columns.c'+parent_column+'.data_type'));

	// Get the value of the selected line and insert it into the parent lisha
	if(document.all)
	{ 
		// IE :(
		var valeur = lisha_get_innerHTML(el);
		if(eval('lisha.'+parent+'.columns.c'+parent_column+'.data_type') == __CHECKBOX__)
		{
			if(lisha_get_innerHTML(el).search("checked") == -1)
			{
				valeur = "0";
			}
			else
			{
				valeur = "1";
			}
		}	
		document.getElementById('th_input_'+parent_column+'__'+parent).value = valeur;
	}
	else
	{
		var valeur = document.getElementById(el).textContent;
		valeur = valeur.replace(/[\xA0]/g,' '); // TextContent return issue

		if(eval('lisha.'+parent+'.columns.c'+parent_column+'.data_type') == __CHECKBOX__)
		{
			if(lisha_get_innerHTML(el).search("checked") == -1)
			{
				valeur = "0";
			}
			else
			{
				valeur = "1";
			}
		}
		document.getElementById('th_input_'+parent_column+'__'+parent).value = valeur;
	}

	// Set the focus into the search bloc of the parent column
	document.getElementById('th_input_'+parent_column+'__'+parent).focus();

	// Close the child lisha
	document.getElementById('internal_lisha_'+parent).style.display = 'none';
	lisha_set_innerHTML('internal_lisha_'+parent,'');

	// Kill the object of the child lisha (json)
	eval('delete lisha.'+parent+'_child;');

	// Search for other lmod openable
	eval('lisha.'+parent+'.time_input_search = true;');
	lisha_input_search(parent,document.getElementById('th_input_'+parent_column+'__'+parent).value,parent_column,false);

	if(document.getElementById('chk_edit_c'+parent_column+'_'+parent))
	{
		document.getElementById('chk_edit_c'+parent_column+'_'+parent).checked = true;
	}

	// Keep search filter options in column input header
	lisha_define_filter(parent,encodeURIComponent(document.getElementById('th_input_'+parent_column+'__'+parent).value),parent_column,false);

	// Hide the cover
	lisha_cover_with_filter(parent);
	//lisha_display_wait(lisha_id);
}
/**==================================================================*/


/**==================================================================
 * lisha_child_list_column_ok : event when item is selected
 * @lisha_id		: id of lisha parent
 * @ajax_return		: response of ajax call
 ====================================================================*/
function lisha_child_list_column_ok(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		lisha_display_wait(lisha_id);

		// Close the child lisha
		document.getElementById('internal_lisha_'+lisha_id).style.display = 'none';

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+ssid+'&action=27&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_child_list_column_ok';
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
			eval(decodeURIComponent(json.lisha.json_line));

			// Set the content of the lisha
			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));			
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,'ERROR 130 :'+e);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * event call when item is selected
 * @parent			: id of lisha parent
 * @parent_column	: column id ( if no column, jsut force 1 )
 ====================================================================*/
function lisha_child_cancel(parent,parent_column)
{
	eval('lisha.'+parent+'.lisha_child_opened = false;');

	// Set the focus into the seach bloc of the parent column
	document.getElementById('th_input_'+parent_column+'__'+parent).focus();

	// Close the child lisha
	document.getElementById('internal_lisha_'+parent).style.display = 'none';

	// Kill the object of the child lisha (json)
	eval('delete lisha.'+parent+'_child;');

	lisha_cover_with_filter(parent);
}
/**==================================================================*/


/**==================================================================
 * Internal lisha call for sub function
 * @lisha_id		: Internal lisha identifier
 * @lisha_type		: Kind of lisha object
 * @column          : Column identifier
 * @ajax_return     : Ajax call return
 ====================================================================*/
function lisha_display_internal_lis(lisha_id,lisha_type,column,ajax_return)
{
	var is_lovable = eval('lisha.'+lisha_id+'.columns.c'+column+'.is_lovable');
	if(is_lovable != undefined && is_lovable == true)
	{
		if(typeof(ajax_return) == 'undefined')
		{
			lisha_cover_with_filter(lisha_id);
			//==================================================================
			// Setup Ajax configuration
			// Load estimation
			//==================================================================
			var conf = [];

			conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/internal.php';
			conf['delai_tentative'] = 6000;
			conf['max_tentative'] = 4;
			conf['type_retour'] = false;		// ReponseText
			conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&column='+column+'&lisha_type='+lisha_type;
			conf['fonction_a_executer_reponse'] = 'lisha_display_internal_lis';
			conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+lisha_type+"',"+column;

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
				lisha_set_style_display('internal_lisha_'+lisha_id,'block');

				// Set the lisha opened child flag
				eval('lisha.'+lisha_id+'.lisha_child_opened = '+column+';');

				// Update the json object
				eval(decodeURIComponent(json.lisha.json));

				// Set the content of the lisha
				lisha_set_innerHTML('internal_lisha_'+lisha_id,decodeURIComponent(json.lisha.content));

				lisha_set_el_width('internal_lisha_'+lisha_id,500,'px');

				if(document.getElementById('liste_'+lisha_id).offsetHeight < 422)
				{
					document.getElementById('internal_lisha_'+lisha_id).style.height = document.getElementById('liste_'+lisha_id).offsetHeight+22+'px';
					document.getElementById('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'_child__').style.height = document.getElementById('liste_'+lisha_id).offsetHeight+'px';

				}
				else
				{
					lisha_set_el_height('internal_lisha_'+lisha_id,318,'px');
				}


				var pos = lisha_getPosition('th_menu_'+column+'__'+lisha_id);

				var pos_th = document.getElementById('th_menu_'+column+'__'+lisha_id).offsetLeft - document.getElementById('liste_'+lisha_id).scrollLeft;
				document.getElementById('internal_lisha_'+lisha_id).style.left =  pos_th-4+'px';

				// Back to default focued column : SRX_DEFAULT_FOCUS_COLUMN_CHILD
				var my_default_focus_column = eval('lisha.'+lisha_id+'_child.default_input_focus');
				if(my_default_focus_column != false) // Check if a focus column is found
				{
					var myfocus = 'th_input_'+my_default_focus_column+'__'+lisha_id+'_child';
					document.getElementById(myfocus).focus();
				}

				//==================================================================
				// Test child lisha position
				//==================================================================
				// Get the position of the child lisha
				var pos_child_lisha = lisha_getPosition('internal_lisha_'+lisha_id); 

				// Get the position of the parent lisha
				var pos_lisha = lisha_getPosition('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__'); 

				// Test if the menu is not out of the left corner of the lisha
				if((pos_child_lisha[0]+500) > (pos_lisha[0]+lisha_get_el_offsetWidth('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__')))
				{
					document.getElementById('internal_lisha_'+lisha_id).style.left = '';
					document.getElementById('internal_lisha_'+lisha_id).style.right = 0+'px';
				}
				//==================================================================


				// Compute sub lisha real top position
				var myid = 'th_menu_'+column+'__'+lisha_id;
				var pos_item = FindXY(document.getElementById(myid),lisha_id);

				document.getElementById('internal_lisha_'+lisha_id).style.top = (pos_item.y+22)+'px';

				eval('lisha.'+lisha_id+'.menu_left = '+document.getElementById('internal_lisha_'+lisha_id).offsetLeft+';');
				size_table(lisha_id+'_child');

				// Browser bug, force refresh sub lisha
				lisha_reset(lisha_id+'_child');
			}
			catch(e) 
			{
				lisha_display_error(lisha_id,e);
			}
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Call when you try to manage advanced filter option on a column
 * @lisha_id		: internal lisha identifier
 * @lisha_type      : Kind of sub lisha
 * @column		    : Column id
 * @ajax_return 	: return ajax if any
 ====================================================================*/
function column_advanced_filter(lisha_id, column, ajax_return)
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
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&lisha_type=__ADV_FILTER__&column='+column;
		conf['fonction_a_executer_reponse'] = 'column_advanced_filter';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+column+"'";

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
				document.getElementById('internal_lisha_'+lisha_id).style.height = '360px';
			}

			// _button_columns_list
			var pos_item = FindXY(document.getElementById('th_menu_'+column+'__'+lisha_id),lisha_id);
			var pos_x = pos_item.x - document.getElementById('liste_'+lisha_id).scrollLeft;

			document.getElementById('internal_lisha_'+lisha_id).style.left =  pos_x+'px';

			// Compute sub lisha advanced option real top position
			var pos_y = pos_item.y+ document.getElementById('tr_header_input_'+lisha_id).offsetHeight;
			document.getElementById('internal_lisha_'+lisha_id).style.top =  pos_y+'px';

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