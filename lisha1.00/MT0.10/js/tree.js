//==================================================================
/* Keep it out of local function */
var MT_internal_id = '';
var MT_row_id = '';
var MT_caption_old = '';

var MT_drdr_start = false;
var MT_drdr_actif = false;
var MT_drdr_source = '';
var MainTimerMT = '';
var MT_count_row_deleted = 0;
//==================================================================


/**==================================================================
 * MT_selected_item : event when item is selected
 * @evt : catch event
 * @internal_id : Tree id root
 * @row_id : Node unique identifier
 * @mode : U : Update, D : Display
 * @refresh : D: means reload tree with focus
 ====================================================================*/
function MT_selected_item(evt,internal_id, row_id, mode, refresh)
{
	try
	{
		eval(internal_id+'call_clicked');
		// Customer function call if any
		window[eval(internal_id+'call_clicked')](internal_id, row_id, mode, refresh);
	}
	catch (e)
	{
		// No custom function define
		// Nothing special to do
	}
	
	// Stop click inheritance
	var e=(evt)?evt:windows.event;
	if (window.event)
	{
		e.cancelBubble = true;
	}
	else
	{
		e.stopPropagation();
	}
}
/**==================================================================*/


/**==================================================================
 * MT_add_item : event when a new item is created
 * @evt : catch event
 * @internal_id : Tree id root
 * @row_id : Node unique identifier
 ====================================================================*/
function MT_add_item(evt,internal_id, row_id)
{
	try
	{
		eval(internal_id+'call_new_item');
		// Customer function call if any
		window[eval(internal_id+'call_new_item')](internal_id, row_id);
	}
	catch (e)
	{
		// No custom function define
		// Nothing special to do
	}
	
	// Stop click inheritance
	var e=(evt)?evt:windows.event;
	if (window.event)
	{
		e.cancelBubble = true;
	}
	else
	{
		e.stopPropagation();
	}
	
}
/**==================================================================*/


/**==================================================================
 * MT_delete_item : event when an item was deleted
 * @evt : catch event
 * @internal_id : Tree id root
 * @row_id : Node unique identifier
 ====================================================================*/
function MT_delete_item(evt,internal_id, row_id)
{
	try
	{
		eval(internal_id+'call_delete_item');
		// Customer function call if any
		window[eval(internal_id+'call_delete_item')](internal_id, row_id);
	}
	catch (e)
	{
		// No custom function define
		// Nothing special to do
	}
	
	// Stop click inheritance
	var e=(evt)?evt:windows.event;
	if (window.event)
	{
		e.cancelBubble = true;
	}
	else
	{
		e.stopPropagation();
	}
	
}
/**==================================================================*/


/**==================================================================
 * clear_node_move : Cancel any move in progress
 * @internal_id : internal div tree id
 ====================================================================*/
function clear_node_move(internal_id)
{
	// Display source item to move
	var id_to_collapse = internal_id + MT_drdr_source;
	document.getElementById(id_to_collapse).style.display = "block";	
	
	MT_drdr_start = false;
	MT_drdr_actif = false;
	MT_drdr_source = '';
	MainTimerMT = '';
	var fly_popup = internal_id + "move_item";
	document.getElementById(fly_popup).style.display = "none";
	document.getElementById(fly_popup).innerHTML = "";	
}
/**==================================================================*/


/**==================================================================
 * mouse_down_item : Called then left mouse down
 * @internal_id : Tree id root
 * @row_id : Number of node
 ====================================================================*/
function mouse_down_item(internal_id,row_id)
{	
	if(!MT_drdr_start)
	{
		MT_drdr_start = true;
		MT_drdr_source = row_id;
		MainTimerMT = setTimeout(function (){keydownhold(internal_id,row_id);}, 1000); // 1 second
	}
}
/**==================================================================*/


/**==================================================================
 * out_of_item : call if mouse is out of the item
 * @internal_id : Tree id root
 * @row_id : Number of node
 ====================================================================*/
function out_of_item(internal_id,row_id)
{
	mouse_up_item();
}
/**==================================================================*/


/**==================================================================
 * mouse_up_item : Called then left mouse up
 ====================================================================*/
function mouse_up_item()
{	
	if(!MT_drdr_actif)
	{
		// Move node in progress
		clearTimeout(MainTimerMT);
		MT_drdr_start = false;
	}	
}
/**==================================================================*/


/**==================================================================
 * keydownhold : Wait xx seconds that mouse is down on same id
 * @internal_id : Tree id root
 * @row_id : Number of node
 ====================================================================*/
function keydownhold(internal_id,row_id)
{
	clearTimeout(MainTimerMT);
	
	// Hide item we catch to move
	var id_to_collapse = internal_id + row_id;
	document.getElementById(id_to_collapse).style.display = "none";	
		
	MT_drdr_actif = true;
	MT_drdr_source = row_id;
	
	var fly_popup = internal_id + "move_item";
	document.getElementById(fly_popup).innerHTML = row_id+ " : "+ document.getElementById("up_cap_"+internal_id+row_id).innerHTML +"<hr>"; 
	document.getElementById(fly_popup).style.display = "block";
}
/**==================================================================*/


/**==================================================================
 * input_main_key : Manage keypress on tree
 * @evt : catch browser event
 * @internal_id : internal div tree id
 ====================================================================*/
function input_main_key(evt,internal_id)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	
	if(charCode == 27) // Escape
	{
		clear_node_move(internal_id);
	}
}
/**==================================================================*/

/**==================================================================
 * item_icon_update : Only one icon update at time
 * @internal_id : Tree id root
 * @row_id : Number of node
 * Need global javascript variable below
 * MT_row_id, MT_internal_id, MT_caption_old
 ====================================================================*/
function item_icon_update(internal_id,row_id,style)
{	
	if(row_id != MT_row_id && !MT_drdr_actif) // Action only if different
	{
		var string_id = 'icon_'+internal_id+row_id;
		var myclass = document.getElementById(string_id).className;
		
		var DefaultClass = style+'_empty_icon'; //TreeView_dev_empty_icon
		if(myclass == DefaultClass)
		{
			myclass = '';
		}
		/*
		caption = caption.replace(/"/g,"&quot;"); // Replace all " by &quot;
		if( MT_row_id != '')
		{
			var string_id = 'up_cap_'+MT_internal_id+MT_row_id;
			document.getElementById(string_id).innerHTML = MT_caption_old;
			MT_row_id = ''; // Unlock current update
		}
	
		var string_id = 'up_cap_'+internal_id+row_id;
		
		MT_row_id = row_id; // Lock current update
		MT_internal_id = internal_id;
		*/
		
		// Keep current innerHTML before any modification
		//MT_caption_old = myclass;
		
		var input_id = internal_id+'input';
		//var parent_id = internal_id+'parentclick';
		document.getElementById(string_id).style.width = "130px";
		document.getElementById(string_id).innerHTML = '<input id="'+input_id+'" onkeydown="return input_key_manager_icon(event,\''+internal_id+'\',\''+row_id+'\',\''+style+'\')" type="text" size=20 value="'+myclass+'">';
		document.getElementById(input_id).focus();
	}
}	
/**==================================================================*/


/**==================================================================
 * item_update : Only one row update at the same time or add new child and current row_id is his parent
 * Update a node
 * @internal_id : Tree id root
 * @row_id : Number of node
 * Need global javascript variable below
 * MT_row_id, MT_internal_id, MT_caption_old
 ====================================================================*/
function item_update(internal_id,row_id,style,full_update)
{	
	if(row_id != MT_row_id && !MT_drdr_actif) // Action only if different
	{
		//var caption = document.getElementById('up_cap_'+internal_id+row_id).innerHTML;
		var caption = document.getElementById(internal_id+row_id+"_origin").innerHTML;

        var caption_length = caption.length;
        // Protect minimum length of input field
        if(caption_length < 20)
        {
            caption_length = 20;
        }

		caption = caption.replace(/"/g,"&quot;"); // Replace all " by &quot;
		if( MT_row_id != '')
		{
			//var string_id = 'up_cap_'+MT_internal_id+MT_row_id;
			var string_id = MT_internal_id+MT_row_id+'_origin';
			document.getElementById(string_id).innerHTML = MT_caption_old;
			MT_row_id = ''; // Unlock current update
		}
	
		var string_id = 'up_cap_'+internal_id+row_id;
		
		MT_row_id = row_id; // Lock current update
		MT_internal_id = internal_id;
		
		
		// Keep current innerHTML before any modification
		MT_caption_old = document.getElementById(string_id).innerHTML;
		
		var input_id = internal_id+'input';
		var parent_id = internal_id+'parentclick';
		
		if(full_update)
		{
			document.getElementById(string_id).innerHTML = '<input id="'+input_id+'" onkeydown="return input_key_manager(event,\''+internal_id+'\',\''+row_id+'\')" type="text" size='+caption_length+' value="'+caption+'"><div class="'+style+'_add_child_button" id="'+parent_id+'" onClick="add_new_child(\''+internal_id+'\',\''+row_id+'\')"></div><div class="'+style+'_delete_item_button" id="'+parent_id+'" onClick="delete_item(\''+internal_id+'\',\''+row_id+'\')"></div>';
		}
		else
		{
			document.getElementById(string_id).innerHTML = '<input id="'+input_id+'" onkeydown="return input_key_manager(event,\''+internal_id+'\',\''+row_id+'\')" type="text" size='+caption_length+' value="'+caption+'">';
		}
		
		document.getElementById(input_id).focus();
	}
	
	// Drop item
	if(MT_drdr_actif && ( row_id != MT_drdr_source) )
	{
		wait_ajax_effect(true,internal_id);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var configuration = new Array();	
		
		configuration['page'] = mt_root_path+'ajax/move_row.php';
		//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
		//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
		configuration['delai_tentative'] = 5000; // 5 seconds max
		configuration['max_tentative'] = 2;
		configuration['type_retour'] = false;		// ReponseText
		
		configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&target_id='+row_id+'&source_id='+MT_drdr_source+'&mode=C'; // Children
				
		configuration['fonction_a_executer_reponse'] = 'row_move_done';
		configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
		//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
		
		
		// Do the call
		ajax_call(configuration);
		//==================================================================
	}
}
/**==================================================================*/


/**==================================================================
 * add_new_child : Add new item like first child of current item
 * @internal_id : internal div tree id
 * @row_id : current row id updated
 ====================================================================*/
function add_new_child(internal_id,row_id)
{
	var final_id = internal_id + "INT_" + row_id;
	var input_id = internal_id + "input";

	
	var string_value = encodeURIComponent(document.getElementById(input_id).value);
	
	if(string_value.length == 0)
	{
		infobulle_display(internal_id,decodeURIComponent(mt_libelle_global["MTET_14"]),decodeURIComponent(mt_libelle_global["MTMT_14"]),input_id);
	}
	else
	{
		wait_ajax_effect(true,internal_id);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var configuration = new Array();	
		
		configuration['page'] = mt_root_path+'ajax/add_row.php';
		//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
		//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
		configuration['delai_tentative'] = 5000; // 5 seconds max
		configuration['max_tentative'] = 2;
		configuration['type_retour'] = false;		// ReponseText
		
		configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&caption='+string_value+'&mode=C'; // Child item
				
		configuration['fonction_a_executer_reponse'] = 'row_add_done';
		//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
		
		configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
		
		
		// Do the call
		ajax_call(configuration);
		//==================================================================
	}
}
/**==================================================================*/


/**==================================================================
 * delete_item : Delete item and all recursif
 * @internal_id : internal div tree id
 * @row_id : current row id to delete
 ====================================================================*/
function delete_item(internal_id,row_id)
{
	wait_ajax_effect(true,internal_id);
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	
	
	configuration['page'] = mt_root_path+'ajax/delete_and_mark.php';
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 30000; // 30 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	
	configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&action=0';
	configuration['fonction_a_executer_reponse'] = 'rows_delete_message';
	configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";

	//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
	
	// Do the call
	ajax_call(configuration);
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * rows_delete_message : Ajax return number of row before submit any deletion
 * @internal_id : tecnhical root id identifier
 * @retour : Ajax return code
 ====================================================================*/
function rows_delete_message(internal_id,retour)
{
	wait_ajax_effect(false,internal_id);
	
	retour = JSON.parse(retour);
	
	if(retour.TOTAL == 1)
	{
		var message_title = decodeURIComponent(mt_libelle_global["MTMT_16"]);
		message_title = message_title.replace(/&2/g,retour.ID);
		var message_body = decodeURIComponent(mt_libelle_global["MTET_16"]);
		message_body = message_body.replace(/&2/g,retour.ID);
	}
	else
	{
		var message_title = decodeURIComponent(mt_libelle_global["MTMT_15"]);
		message_title = message_title.replace(/&1/g,retour.TOTAL);
		var message_body = decodeURIComponent(mt_libelle_global["MTET_15"]);
		message_body = message_body.replace(/&1/g,retour.TOTAL);
		message_body = message_body.replace(/&2/g,retour.ID);
	}
	
	var ok_button_param = internal_id+","+retour.ID;
	infobulle_display(internal_id,message_body,message_title,internal_id+'input','confirm_delete_row',ok_button_param);
}
/**==================================================================*/


/**==================================================================
 * confirm_delete_row : Confirm rows deletion
 * @id_node : item id to delete
 ====================================================================*/
function confirm_delete_row(param)
{
	var xtab = param.split(',');
	
	var internal_id = xtab[0];
	var row_id = xtab[1];
	
	wait_ajax_effect(true,internal_id);
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	
	
	configuration['page'] = mt_root_path+'ajax/delete_and_mark.php';
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 5000; // 5 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	
	configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&action=D';
		
	configuration['fonction_a_executer_reponse'] = 'rows_delete_done';
	configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";

	//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
	
	// Do the call
	ajax_call(configuration);
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * rows_delete_done : Ajax return when new node is added
 * @internal_id : tecnhical root id identifier
 * @retour : Ajax return code
 ====================================================================*/
function rows_delete_done(internal_id,retour)
{
	retour = JSON.parse(retour);
	refresh_display(internal_id, retour.ID, 'D');
	MT_count_row_deleted = retour.TOTAL;
		
	MT_row_id = ''; // Unlock current update
}
/**==================================================================*/



/**==================================================================
 * input_key_button_infobulle : Manage keypress on infobulle button
 * @evt : catch browser event
 * @internal_id : internal div tree id
 * @button_name : from wich button (cancel, ok)
 ====================================================================*/
function input_key_button_infobulle(evt,internal_id,button_name)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if(charCode == 13) // Enter
	{
		if(button_name == 'cancel')
		{
			infobulle_display(internal_id,'','',internal_id+'input'); // Hide infobulle and return focus into current node
		}
	}
}
/**==================================================================*/


/**==================================================================
 * input_key_manager_icon : Manage keypress on input icon
 * @evt : catch browser event
 * @internal_id : internal div tree id
 * @row_id : current row id updated
 * @style : tree theme
 ====================================================================*/
function input_key_manager_icon(evt,internal_id,row_id,style)
{
	var string_id = 'icon_'+internal_id+row_id;
	var charCode = (evt.which) ? evt.which : event.keyCode;
	
	if(charCode == 27) // Escape
	{
		// Restore old innerHTML content
		document.getElementById(string_id).innerHTML = "";
		document.getElementById(string_id).style.width = "";
		MT_row_id = ''; // Unlock current update
	}

	if(charCode == 13) // Enter
	{
		var string_value = encodeURIComponent(document.getElementById(internal_id+'input').value);

		// Force no icon, restore original class style name
		if(string_value.length == 0)
		{
			string_value_final = style+'_empty_icon'; //TreeView_dev_empty_icon
		}
		else
		{
			string_value_final = string_value;
		}
		
		
		wait_ajax_effect(true,internal_id);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var configuration = new Array();	
		
		configuration['page'] = mt_root_path+'ajax/update_row.php'; // icon
		//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
		//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
		configuration['delai_tentative'] = 5000; // 5 seconds max
		configuration['max_tentative'] = 2;
		configuration['type_retour'] = false;		// ReponseText
		
		configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&mode=I&caption='+string_value;


		configuration['fonction_a_executer_reponse'] = 'row_update_icon_done';
		//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
		
		configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
		
		
		// Do the call
		ajax_call(configuration);
		//==================================================================

		// Update HTML content
		var update_node = document.getElementById(internal_id+'input').value;
		document.getElementById(string_id).innerHTML = "";
		document.getElementById(string_id).style.width = "";
		document.getElementById(string_id).className = string_value_final;
	}
}
/**==================================================================*/

/**==================================================================
 * row_update_icon_done : Ajax return when icon update is done in database
 * @internal_id : tecnhical root id identifier
 * @retour : Ajax return code
 ====================================================================*/
function row_update_icon_done(internal_id,retour)
{
	hide_ajax_waiting_effect(internal_id,retour);
	MT_row_id = ''; // Unlock current update
}
/**==================================================================*/


/**==================================================================
 * input_key_manager : Manage keypress on input item label
 * @evt : catch browser event
 * @internal_id : internal div tree id
 * @row_id : current row id updated
 ====================================================================*/
function input_key_manager(evt,internal_id,row_id)
{
	//var string_id = 'up_cap_'+internal_id+row_id;
	var string_id = internal_id+row_id+'_origin';
	var charCode = (evt.which) ? evt.which : event.keyCode;
	
	if(charCode == 27) // Escape
	{
		// Restore old innerHTML content
		var string_id = 'up_cap_'+internal_id+row_id;
		document.getElementById(string_id).innerHTML = MT_caption_old;
		MT_row_id = ''; // Unlock current update
		clear_node_move(internal_id);
	}

	if(charCode == 13) // Enter
	{
		var string_value = encodeURIComponent(document.getElementById(internal_id+'input').value);
		
		if(string_value.length == 0)
		{
			infobulle_display(internal_id,decodeURIComponent(mt_libelle_global["MTET_14"]),decodeURIComponent(mt_libelle_global["MTMT_14"]));
		}
		else
		{
			wait_ajax_effect(true,internal_id);
			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var configuration = new Array();	
			
			configuration['page'] = mt_root_path+'ajax/update_row.php';
			//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
			//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
			configuration['delai_tentative'] = 5000; // 5 seconds max
			configuration['max_tentative'] = 2;
			configuration['type_retour'] = false;		// ReponseText
			
			configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&mode=L&caption='+string_value;
	
	
			configuration['fonction_a_executer_reponse'] = 'row_update_done';
			//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
			
			configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"','"+row_id+"'";
			
			
			// Do the call
			ajax_call(configuration);
			//==================================================================
	
			// Update HTML content
			//var update_node = htmlEntities(document.getElementById(internal_id+'input').value);
			/*
			var all_blank = trim(update_node);
			
			if(all_blank == '')
			{
				update_node = update_node.replace(/ /g,'&nbsp;');
			}
			document.getElementById(string_id).innerHTML = update_node;
			*/
		}
	}
}
/**==================================================================*/


/**==================================================================
 * row_update_done : Ajax return when update is done in database
 * @internal_id : tecnhical root id identifier
 * @row_id : node id
 * @retour : Ajax return code
 ====================================================================*/
function row_update_done(internal_id,row_id,retour)
{
	retour = JSON.parse(retour);
	var string_id = 'up_cap_'+internal_id+row_id;
	var string_origin = internal_id+row_id+'_origin';
	
	document.getElementById(string_id).innerHTML = retour.RENDERING;
	document.getElementById(string_origin).innerHTML = retour.RAW;	// Update done successfully, update origin 

	hide_ajax_waiting_effect(internal_id,row_id);
	MT_row_id = ''; // Unlock current update
}
/**==================================================================*/


/**==================================================================
 * manage_mark
 * Mark if unmarked and unmark if marked
 * className form : xxxx_xxx_mark or xxxx_xxx_unmark
 * @internal_id : tecnhical root id identifier
 * @row_id : tree node id clicked
====================================================================*/
function manage_mark(internal_id,row_id)
{
	wait_ajax_effect(true,internal_id);
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	
		
	configuration['page'] = mt_root_path+'ajax/delete_and_mark.php';
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 30000; // 30 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	
	configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&action=0';
	configuration['fonction_a_executer_reponse'] = 'rows_mark_message';
	configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";

	//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
	
	// Do the call
	ajax_call(configuration);
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * rows_mark_message : Ajax return number of row before submit any deletion
 * @internal_id : tecnhical root id identifier
 * @retour : Ajax return code
 ====================================================================*/
function rows_mark_message(internal_id,retour)
{
	wait_ajax_effect(false,internal_id);
	
	retour = JSON.parse(retour);
	
	var ok_button_param = internal_id+","+retour.ID;

	if(retour.TOTAL > 1)
	{
		// Is it a check or unckeck action ?
		if(retour.MARK == 1)
		{
			var checktext = decodeURIComponent(mt_libelle_global["MTET_18"]);
		}
		else
		{
			var checktext = decodeURIComponent(mt_libelle_global["MTET_19"]);
		}

		var message_title = decodeURIComponent(mt_libelle_global["MTMT_17"]);
		message_title = message_title.replace(/&1/g,retour.ID);
		message_title = message_title.replace(/&3/g,checktext);
		
		var message_body = decodeURIComponent(mt_libelle_global["MTET_17"]);
		message_body = message_body.replace(/&1/g,retour.ID);
		message_body = message_body.replace(/&2/g,retour.TOTAL);
		
		
		message_body = message_body.replace(/&3/g,checktext);

		infobulle_display(internal_id,message_body,message_title,internal_id+'input','confirm_mark_row',ok_button_param);
	}
	else
	{
		confirm_mark_row(ok_button_param);
	}
	
}
/**==================================================================*/


/**==================================================================
 * confirm_mark_row : Confirm rows to mark or unmark
 * @param : dynamic parameters
 ====================================================================*/
function confirm_mark_row(param)
{
	var xtab = param.split(',');
	
	var internal_id = xtab[0];
	var row_id = xtab[1];
	
	wait_ajax_effect(true,internal_id);
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	
	
	configuration['page'] = mt_root_path+'ajax/delete_and_mark.php';
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 5000; // 5 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	
	configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&action=M';
		
	configuration['fonction_a_executer_reponse'] = 'rows_mark_done';
	configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";

	//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
	
	// Do the call
	ajax_call(configuration);
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * rows_mark_done : Ajax return when all item are mark or unmark
 * @internal_id : tree identifier
 * @retour : Ajax return code
 ====================================================================*/
function rows_mark_done(internal_id,retour)
{
	retour = JSON.parse(retour);
	refresh_display(internal_id, retour.ID, 'X');
	MT_row_id = ''; // Unlock current update
}
/**==================================================================*/


/**==================================================================
 * toogle_nodes
 * @internal_id : tecnhical root id identifier
 * @id_expansion : Format internal Tree ID + _ + ID item
 * @id : id of item
 * @update_mode : 0 means display, 1 means modify mode
 * 				  In mode 1, we record each expansion modification in php memory session
 ====================================================================*/
function toogle_nodes(internal_id,id_expansion,id,update_mode)
{
	var action;

	if(document.getElementById(id_expansion).className == "expanded")
	{
		document.getElementById(id_expansion).className="collapsed";
		action = '0';
	}
	else
	{
		document.getElementById(id_expansion).className="expanded";
		action = '1';
	}

	if(update_mode == '1')
	{
		wait_ajax_effect(true,internal_id);
		
		// Ajax call to update php session for expansion list
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var configuration = new Array();	
		
		configuration['page'] = mt_root_path+'ajax/expandlist.php';
		//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
		//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
		configuration['delai_tentative'] = 5000; // 5 seconds max
		configuration['max_tentative'] = 2;
		configuration['type_retour'] = false;		// ReponseText
		
		configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+id+'&action='+action;

		configuration['fonction_a_executer_reponse'] = 'hide_ajax_waiting_effect';
		//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
		
		configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
		
		// Do the call
		ajax_call(configuration);
		//==================================================================
	}
}
/**==================================================================*/


/**==================================================================
 * hide_ajax_waiting_effect : remove waiting window effect
 * @internal_id : tecnhical root id identifier
 * @update_mode : 0 means display, 1 means modify mode
 ====================================================================*/
function hide_ajax_waiting_effect(internal_id,retour)
{
	wait_ajax_effect(false,internal_id);
}
/**==================================================================*/


/**==================================================================
 * reduce_expand_all
 * @internal_id : div id javascript
 * @mode : true : Expand all else collapse all
 ====================================================================*/
function reduce_expand_all(internal_id,mode)
{
	if(mode)
	{
		var mytab = document.getElementById(internal_id).getElementsByClassName("collapsed");
		var mode = "expanded";
	}
	else
	{
		var mytab = document.getElementById(internal_id).getElementsByClassName("expanded");
		var mode = "collapsed";
	}
	
	while(mytab[0]) // Always item 0 ( pointer )
	{
		mytab[0].setAttribute('class',mode);
	}
}
/**==================================================================*/


/**==================================================================
 * HTML_TreeReturn
 * @internal_id : 	HTML div id to draw tree
 * @myfocus :	id item to focus ( null to disable focus )
 * @action  :	Which action call this HTML result ?
 * 				0 : none
 * 				A : Add node
 * 				D : Delete node
 * 				M : node moved
 * 				X : node flag marked
 * @retour	:	Have to be always last parameter. Final HTML to draw in browser
 ====================================================================*/
function HTML_TreeReturn(internal_id, myfocus, action, retour)
{
	document.getElementById(internal_id).innerHTML = retour;

	wait_ajax_effect(false,internal_id);
	
	// Manage focus if any
	if(myfocus != undefined)
	{
		try
		{
			document.getElementById(internal_id+myfocus).focus();
		}
		catch (e)
		{
			// Item id not found, doesn't matter, no focus set, go on
		}
	}

	//==================================================================
	// Call custom javascript function if defined
	//==================================================================
	switch (action)
	{
		case 'A':
			try
			{
				eval(internal_id+'call_new_item');
				// Customer function call if any
				window[eval(internal_id+'call_new_item')](internal_id, myfocus);
			}
			catch (e)
			{
				// No custom function define
				// Nothing special to do
			}
		break;	
		case 'D':
			try
			{
				eval(internal_id+'call_delete_item');
				// Customer function call if any
				window[eval(internal_id+'call_delete_item')](internal_id, myfocus, MT_count_row_deleted);
				MT_count_row_deleted = 0; // Reset total of rows deleted
			}
			catch (e)
			{
				// No custom function define
				// Nothing special to do
			}
		break;
		default:
			// Nothing
	}
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * display_message_tree
 * @div_id : HTML div id to display message
 ====================================================================*/
function display_message_tree(div_id,message)
{
	document.getElementById(div_id).innerHTML = message;
}

/**==================================================================
 * search_on_tree
 * @internal_id : with extension _searchinput, locate text to search in tree
 ====================================================================*/
function search_on_tree(internal_id)
{
	wait_ajax_effect(true,internal_id);

	// Initialize navigation focus offset
	var navi_offset = internal_id+"navig";
	eval(navi_offset+' = 0;');
	
	var id_input = internal_id+'_searchinput';
	var id_message = internal_id+'_message';
	
	var string_input = document.getElementById(id_input).value;
	//string_input = string_input.replace(/ /g,'&nbsp;'); // space to  &nbsp;
	var string_to_find = string_input;
	
	// Launch search only if input string has a minimum length
	// Special behaviour if length = 0 : Just clear search
	if(conf_tree[4] > string_to_find.length && string_to_find.length != 0)
	{
		document.getElementById(id_message).innerHTML = decodeURI(mt_libelle_global["MTLT_4"]);
		wait_ajax_effect(false,internal_id);
	}
	else
	{
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var configuration = new Array();	
		
		configuration['page'] = mt_root_path+'ajax/display.php';
		//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
		//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
		configuration['delai_tentative'] = 30000; // 30 seconds max
		configuration['max_tentative'] = 2;
		configuration['type_retour'] = false;		// ReponseText
		if(string_to_find.length == 0)
		{
			configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&expend_mode=0&action=2';
		}
		else
		{
			configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&expend_mode=0&action=1&searchinput='+encodeURIComponent(string_to_find);
		}
		
		configuration['fonction_a_executer_reponse'] = 'HTML_TreeReturn_focused';
		configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
		//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
		

		// Do the call
		ajax_call(configuration);
		//==================================================================
	}
}
/**==================================================================*/


/**==================================================================
 * HTML_TreeReturn_focused
 * @internal_id : 	Mandatory
 * @retour	:	Have to be always last parameter. Final HTML to draw in browser
 ====================================================================*/
function HTML_TreeReturn_focused(internal_id,retour)
{
	// Draw HTML tree
	document.getElementById(internal_id).innerHTML = retour;

	var main = internal_id+"main";
	var nav_bar = internal_id+"_bar_navigation";
	
	var style = document.getElementById(main).getAttribute("class","false");
	
	var final_style = style + "_item_caption_highlight";

	try
	{
		var ref = document.getElementById(main).getElementsByClassName(final_style)[0].getAttribute("id");
		var found = true;
	}
	catch (e)
	{
		// Item id not found, doesn't matter, no focus set, go on
		found = false;
	}
	
	wait_ajax_effect(false,internal_id);

	if(!found) // Nothing found hide navigation bar
	{
		document.getElementById(nav_bar).style.display = "none";
	}
	else
	{	// Ok at least one item found	
		document.getElementById(nav_bar).style.display = "block";

		var myid = ref.substr(7,ref.length-7); // 7 is the lenght off that prefixe: up_cap_

		document.getElementById(myid).focus();

		document.getElementById(myid).onclick(event); // Force onclick when navigate on search items		
	}
	
}
/**==================================================================*/


/**==================================================================
 * Navigation_focus
 * @internal_id : id of current tree
 * @action		: Mean last, first, next, before
 ====================================================================*/
function navigation_focus(internal_id,action)
{
	var navi_offset = internal_id+"navig";

	var message = document.getElementById(internal_id+"_message");
	
	var main = internal_id+"main";
	var nav_bar = internal_id+"_bar_navigation";
	
	var style = document.getElementById(main).getAttribute("class","false");
	
	var final_style = style + "_item_caption_highlight";
	
	switch(action)
	{
		case "first":
			eval(navi_offset+' = 0;');
			var ref = document.getElementById(main).getElementsByClassName(final_style)[0].getAttribute("id");
		break;
		case "next":
			var navi_max = document.getElementById(internal_id+"search_focus_last").innerHTML; // recover max item found
			if(eval(navi_offset) >= (navi_max-1))
			{
				// Last item reached
				var text = decodeURIComponent(mt_libelle_global["MTMT_24"]);
				message.innerHTML = text;
				eval(navi_offset+' = 0;');
			}
			else
			{
				eval(navi_offset+' = '+navi_offset+' + 1;');
				var text = decodeURIComponent(mt_libelle_global["MTMT_25"]);
				text = text.replace(/&1/g,parseInt(eval(navi_offset))+1);
				message.innerHTML = text.replace(/&2/g,parseInt(navi_max));
			}
			var ref = document.getElementById(main).getElementsByClassName(final_style)[eval(navi_offset)].getAttribute("id");			
		break;
		case "before":
			var navi_max = document.getElementById(internal_id+"search_focus_last").innerHTML; // recover max item found
			if(eval(navi_offset) == 0)
			{
				// First item reached
				var text = decodeURIComponent(mt_libelle_global["MTMT_26"]);
				message.innerHTML = text;
				eval(navi_offset+' = '+(navi_max-1)+';');
			}
			else
			{
				eval(navi_offset+' = '+navi_offset+' - 1;');
				var text = decodeURIComponent(mt_libelle_global["MTMT_25"]);
				text = text.replace(/&1/g,parseInt(eval(navi_offset))+1);
				message.innerHTML = text.replace(/&2/g,parseInt(navi_max));
			}	
			var ref = document.getElementById(main).getElementsByClassName(final_style)[eval(navi_offset)].getAttribute("id");			
		break;
		case "last":
			var navi_last = document.getElementById(internal_id+"search_focus_last").innerHTML;
			eval(navi_offset+' = '+navi_last+' - 1;');
			var ref = document.getElementById(main).getElementsByClassName(final_style)[eval(navi_offset)].getAttribute("id");			
		break;
		default:
			alert("Unknown action");
		break;
	}
	
	var myid = ref.substr(7,ref.length-7); // 7 is the lenght off that prefixe: up_cap_

	document.getElementById(myid).focus();

	document.getElementById(myid).onclick(event); // Force onclick when navigate on search items
}
/**==================================================================*/


/**==================================================================
 * HTML_focused : Focus a node into a tree
 * @internal_id : id of item selected
 * @prefixe : ????
 * retour : Ajax return information
 ====================================================================*/
function HTML_focused(internal_id,retour)
{
	// Manage focus if any
	if(retour != undefined)
	{
		try
		{
			document.getElementById(internal_id+retour).focus();
		}
		catch (e)
		{
			// Item id not found, doesn't matter, no focus set, go on
		}
	}
	wait_ajax_effect(false,internal_id);
}
/**==================================================================*/


/**==================================================================
 * infobulle_display
 * Call this function to diplay or hide infobulle message
 * @internal_id : Div id to display tree
 * @message_body : Content of html body message
 * @message_title : Content of htlm title message
 * @id_focus_back : No value means no focus when hide infobulle
 * @b_ok : No value means no button displayed : Put here name of called javascript function if pressed
 * @b_ok_param : parameters to add to function b_ok
 * @b_cancel : No value means no button displayed
 * if only message_body is set, display this into HTML content
 * if both message_body and message_title is set, then special <hr> added
 ====================================================================*/
function infobulle_display(internal_id,message_body,message_title,id_focus_back,b_ok,b_ok_param,b_cancel)
{
	var div_cancel = internal_id+'infobulle_cancel';
	var div_ok_button = internal_id+'infobulle_ok';
	var div_message = internal_id+'infobulle';
	var div_mask = internal_id+'mask';
	
	var style = 'default';

	if(document.getElementById(div_cancel).style.display == "block")
	{
		document.getElementById(div_cancel).style.display = "none";
		document.getElementById(div_ok_button).style.display = "none";
		document.getElementById(div_message).style.display = "none";
		document.getElementById(div_mask).style.display = "none";
		if(id_focus_back != undefined && id_focus_back != null )
		{
			document.getElementById(id_focus_back).focus();
		}	
	}
	else
	{
		document.getElementById(div_cancel).style.display = "block";
		if(b_ok != undefined && b_ok != null )
		{
			document.getElementById(div_ok_button).style.display = "block";
			var to_exec = b_ok+"('"+b_ok_param+"')";
			//document.getElementById(div_ok_button).onclick = function() {confirm_delete_row('RR');};
			document.getElementById(div_ok_button).setAttribute('onclick',to_exec); // Setup witch function to launch onclick button
		}
		document.getElementById(div_message).style.display = "block";
		
		if(message_title != "")
		{
			document.getElementById(div_message).innerHTML = message_title+ "<hr>"+message_body;	
		}
		else
		{
			document.getElementById(div_message).innerHTML = message_body;
		}	
		
		document.getElementById(div_mask).style.display = "block";
		document.getElementById(div_cancel).focus();
		
	}
}
/**==================================================================*/


/**==================================================================
 * mt_key_manager
 * @evt : Keypress event
 * @internal_id : From which DOM come from the key event
 ====================================================================*/
function mt_key_manager(evt,internal_id)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if(charCode == 13) // Return key pressed
	{
		clear_node_move(internal_id);
		search_on_tree(internal_id);
	}
}
/**==================================================================*/


/**==================================================================
 * wait_ajax_effect
 * @mode : true means display else means hide
 * @internal_id : Div id to display final HTML result
 ====================================================================*/
function wait_ajax_effect(mode,internal_id)
{
	var final_div = internal_id+'main_wait_ajax';
	var final_div_logo = internal_id+'wait_ajax';
	if(mode)
	{
		document.getElementById(final_div).style.display = "block";		
		document.getElementById(final_div_logo).style.display = "block";		
	}
	else
	{
		document.getElementById(final_div).style.display = "none";		
		document.getElementById(final_div_logo).style.display = "none";		
	}
}
/**==================================================================*/

/**==================================================================
 * node_move
 * @internal_id : Div id to display final HTML result
 * @evt 		: Catch document event
 * TODO : Optimization
 ====================================================================*/
function node_move(internal_id,evt)
{

	if(MT_drdr_actif) // Move flying popup
	{
		var evt = (evt)?evt : event;
		
		// Shortcut for main and move item div
		var target_div = internal_id + 'move_item';
		var target_div_limit = internal_id + 'head';
		var area_headbar = internal_id + 'headbar';
		var area_bottom = internal_id + 'speed_lift_ctrl_bottom';
		var area_top = internal_id + 'speed_lift_ctrl_top';
					
		
		// Offset of move item div from mouse
		var offsetx = 10;
		var offsety = 40;	
		
		// Height and Width of main div tree
		var maxheight = document.getElementById(target_div_limit).offsetHeight;
		var maxwidth = document.getElementById(target_div_limit).offsetWidth;
	
		
		// Height and Width of move item div
		var moveheight = document.getElementById(target_div).offsetHeight;
		var movewidth = document.getElementById(target_div).offsetWidth;
		
		// Left and top limit
		var limx = maxwidth - movewidth - offsetx;
		var limy = maxheight - moveheight - offsety;
	
		// Relative position of mouse in current div tree
		var cur_x = evt.clientX - getPos(document.getElementById(target_div_limit)).x;
		var cur_y = evt.clientY - getPos(document.getElementById(target_div_limit)).y;
		
		// Setup move item x div position
		if(cur_x > limx)
		{
			document.getElementById(target_div).style.left = (limx+offsetx)+"px";
		}
		else
		{
			document.getElementById(target_div).style.left = (cur_x+offsetx)+"px";		
		}
	
		// Setup move item y div position
		if(cur_y > (limy+offsety))
		{
			document.getElementById(target_div).style.top = (limy+offsety+offsety)+"px";
		}
		else
		{
			document.getElementById(target_div).style.top = (cur_y+offsety)+"px";		
		}
	}
}
/**==================================================================*/

/**==================================================================
 * getPos(el)
 * @param el should be a document.getElementById(my_div_id)
 * Return the absolut position of an element from document
 * can be used with mouse position return by event
 ====================================================================*/
function getPos(el)
{
	var left = 0;
	var top = 0;
	
	/* While the element have a parent */
	while(el.offsetParent != undefined && el.offsetParent != null)
	{
		/* Add the position of the parent element */
		if(el.clientLeft != null)
		{
			left += el.offsetLeft + el.clientLeft;
		}
		
		if(el.clientTop != null)
		{
			top += el.offsetTop + el.clientTop;
		}
		
		el = el.offsetParent;
	}
	
	top += el.offsetTop;
	
	return {x: left, y: top};
}
/**==================================================================*/

/**==================================================================
 * add_new_node(el)
 * @internal_id :internal id identifier
 * @row_id : node identifier just before the clicked line
  ====================================================================*/
function add_new_node(internal_id,row_id)
{
	if (!MT_drdr_actif)
	{
		var final_id = internal_id + "INT_" + row_id;
		var input_id = internal_id+'input';
		document.getElementById(final_id).innerHTML = '<input id="'+input_id+'" onkeydown="return input_key_manager_add(event,\''+internal_id+'\',\''+row_id+'\')" type="text" size=30>';
		
		document.getElementById(final_id).style.height = "1.5em";
		document.getElementById(final_id).style.opacity = "1";
		document.getElementById(final_id).style.backgroundColor = "#fff";
		document.getElementById(input_id).focus();
	}
	else // Drag and drop... So, drop !!
	{
		if(MT_drdr_source == row_id)
		{
			// Source = target : Define action, message...
		}
		else
		{
			wait_ajax_effect(true,internal_id);
			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var configuration = new Array();	
			
			configuration['page'] = mt_root_path+'ajax/move_row.php';
			//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
			//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
			configuration['delai_tentative'] = 5000; // 5 seconds max
			configuration['max_tentative'] = 2;
			configuration['type_retour'] = false;		// ReponseText
			
			configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&target_id='+row_id+'&source_id='+MT_drdr_source+'&mode=B'; // Brother
					
			configuration['fonction_a_executer_reponse'] = 'row_move_done';
			configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
			//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
			
			
			// Do the call
			ajax_call(configuration);
			//==================================================================
		}
	}
}
/**==================================================================*/


/**==================================================================
 * row_move_done : Ajax return when node moved
 * @internal_id : tecnhical root id identifier
 * @retour : Ajax return code
 ====================================================================*/
function row_move_done(internal_id,retour)
{
	clear_node_move(internal_id);
	MT_row_id = ''; // Unlock current update

	refresh_display(internal_id, retour, 'M');	
	
	wait_ajax_effect(false,internal_id);
}
/**==================================================================*/


/**==================================================================
 * input_key_manager_add : Manage keypress on add input item label
 * @evt : catch browser event
 * @internal_id : internal div tree id
 * @row_id : current row id updated // TODO
 ====================================================================*/
function input_key_manager_add(evt,internal_id,row_id)
{
	var final_id = internal_id + "INT_" + row_id;
	var input_id = internal_id + "input";
	
	var charCode = (evt.which) ? evt.which : event.keyCode;
	
	if(charCode == 27) // Escape
	{
		// restore default style heritage and remove input box
		document.getElementById(final_id).style.height = "";
		document.getElementById(final_id).style.backgroundColor = "";
		document.getElementById(final_id).style.opacity = "";
		document.getElementById(final_id).innerHTML = "";
		clear_node_move(internal_id);
		
	}

	if(charCode == 13) // Enter
	{
		var string_value = encodeURIComponent(document.getElementById(input_id).value);
		
		if(string_value.length == 0)
		{
			infobulle_display(internal_id,decodeURIComponent(mt_libelle_global["MTET_14"]),decodeURIComponent(mt_libelle_global["MTMT_14"]),input_id);
		}
		else
		{
			wait_ajax_effect(true,internal_id);
			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var configuration = new Array();	
			
			configuration['page'] = mt_root_path+'ajax/add_row.php';
			//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
			//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
			configuration['delai_tentative'] = 5000; // 5 seconds max
			configuration['max_tentative'] = 2;
			configuration['type_retour'] = false;		// ReponseText
			
			configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&IDitem='+row_id+'&caption='+string_value+'&mode=B'; // Brother item
					
			configuration['fonction_a_executer_reponse'] = 'row_add_done';
			configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
			//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
			
			
			// Do the call
			ajax_call(configuration);
			//==================================================================
		}
		
	}
}
/**==================================================================*/


/**==================================================================
 * row_add_done : Ajax return when new node is added
 * @internal_id : tecnhical root id identifier
 * @retour : id of last node created
 ====================================================================*/
function row_add_done(internal_id,retour)
{
	refresh_display(internal_id, retour, 'A');
		
	MT_row_id = ''; // Unlock current update
}
/**==================================================================*/


/**==================================================================
 * Refresh display : Refresh contents of tree
 * @internal_id : Tree identifier
 * @row_id : id to focus if needed
 * @action : see function HTML_TreeReturn for further details
 ====================================================================*/
function refresh_display(internal_id,row_id, action)
{
	wait_ajax_effect(true,internal_id);

	var id_input = internal_id+'_searchinput';
	var string_to_find = unescape(document.getElementById(id_input).value);

	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	
	
	configuration['page'] = mt_root_path+'ajax/display.php';
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 30000; // 30 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	
	configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&action=4&searchinput='+encodeURIComponent(string_to_find)+'&focus='+row_id;
	configuration['fonction_a_executer_reponse'] = 'HTML_TreeReturn';
	configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"','"+row_id+"','"+action+"'";
	
	//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';
	
	
	// Do the call
	ajax_call(configuration);
	//==================================================================
}
/**==================================================================*/


/**==================================================================
 * htmlEntities : Protect HTML entities
 * @str : String to protect
 ====================================================================*/

function htmlEntities(str)
{
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
/**==================================================================*/