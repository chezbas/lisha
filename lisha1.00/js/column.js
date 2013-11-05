/**==================================================================
 * Change sort order of a column
 *
 * @lisha_id	: lisha_id Id of the lisha
 * @type_order  : ASC or DESC
 * @column      : column number identifier
 * @mode        : always NEW ???
 * @ajax_return : use with ajax return
 ====================================================================*/
function lisha_column_order(lisha_id,type_order,column,mode,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		eval('lisha.'+lisha_id+'.stop_click_event = true;');

		lisha_display_wait(lisha_id);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=4&column='+column+'&order='+type_order+'&mode='+mode+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_column_order';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+type_order+"',"+column+",'"+mode+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try 
		{
			// Change the order status
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			// Update the json object
			eval(decodeURIComponent(json.lisha.json));
			eval(decodeURIComponent(json.lisha.json_line));

			lisha_set_content(lisha_id,decodeURIComponent(json.lisha.content));


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
 * Change kind of search in a column
 *
 * @lisha_id	: lisha_id Id of the lisha
 * @type_search : empty for strict search, % for partial search
 * @column      : column number identifier
 * @ajax_return : use with ajax return
 ====================================================================*/
function lisha_change_search_mode(lisha_id,type_search,column,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		eval('lisha.'+lisha_id+'.stop_click_event = true;');

		// Show div for waiting
		lisha_display_wait(lisha_id);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=8&column='+column+'&type_search='+type_search+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_change_search_mode';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+type_search+"',"+column;

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

			// Hide div for waiting
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
 * Change kind of column alignment
 *
 * @lisha_id	    : lisha_id Id of the lisha
 * @type_alignment  : center, left or right
 * @column          : column number identifier
 * @ajax_return     : use with ajax return
 ====================================================================*/
function lisha_change_col_alignment(lisha_id,type_alignment,column,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		eval('lisha.'+lisha_id+'.stop_click_event = true;');

		lisha_display_wait(lisha_id);

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=12&column='+column+'&type_alignment='+type_alignment+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_change_col_alignment';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+type_alignment+"',"+column;

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

			// Hide the wait div
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
 * Hide or display a column
 *
 * @lisha_id	    : lisha_id Id of the lisha
 * @column          : column number identifier
 * @ajax_return     : use with ajax return
 ====================================================================*/
function lisha_toggle_column(lisha_id,column,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		eval('lisha.'+lisha_id+'.stop_click_event = true;');

		lisha_display_wait(lisha_id);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=10&column='+column+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_toggle_column';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"',"+column;

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

			// Hide the wait div
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
 * Go to the previous or next page
 *
 * @lisha_id	    : lisha_id Id of the lisha
 * @ajax_return     : use with ajax return
 ====================================================================*/
function move_column_ajax(lisha_id,ajax_return)
{
	if(eval('lisha.'+lisha_id+'.destination_column;') != eval('undefined'))
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
			conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=3&c_src='+lisha_column_move+'&c_dst='+eval('lisha.'+lisha_id+'.destination_column;')+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
			conf['fonction_a_executer_reponse'] = 'move_column_ajax';
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

				// Hide the wait div
				lisha_display_wait(lisha_id);

				eval('lisha.'+lisha_id+'.destination_column = undefined;');
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
 * Move the clicked column, called when the user move the cursor
 *
 * @evt	    : Web browser event
 ====================================================================*/
function lisha_move_column(evt)
{
	var evt = (evt)?evt : event;

	// Display the float content
	document.getElementById('lisha_column_move_div_float_'+lisha_id_move).style.display = 'block';

	// Display up and down arrows to mark destination column
	document.getElementById('arrow_move_column_top_'+lisha_id_move).style.display = 'block';
	document.getElementById('arrow_move_column_bottom_'+lisha_id_move).style.display = 'block';

	//==================================================================
	// Place the float content
	//==================================================================

		// Get lisha position
		var pos_el = getElementCoords(document.getElementById('lis__'+eval('lisha.'+lisha_id_move+'.theme')+'__lisha_table_'+lisha_id_move+'__'));

		// Get cursor position
		var cur_x = evt.clientX - pos_el.left + document.getElementById('liste_'+lisha_id_move).scrollLeft;

		// Set the position of the float menu
		document.getElementById('lisha_column_move_div_float_'+lisha_id_move).style.top = evt.clientY - pos_el.top+5+'px';
		document.getElementById('lisha_column_move_div_float_'+lisha_id_move).style.left = cur_x-document.getElementById('liste_'+lisha_id_move).scrollLeft+8+'px';
	//==================================================================

	var i = 1;
	for(var iterable_element in eval('lisha.'+lisha_id_move+'.columns')) 
	{
		if(cur_x >= eval('lisha.'+lisha_id_move+'.columns.'+iterable_element+'.min') && cur_x <= eval('lisha.'+lisha_id_move+'.columns.'+iterable_element+'.max'))
		{
			document.getElementById('arrow_move_column_top_'+lisha_id_move).style.left = eval('lisha.'+lisha_id_move+'.columns.'+iterable_element+'.arrow')-5-document.getElementById('liste_'+lisha_id_move).scrollLeft+'px';

			document.getElementById('arrow_move_column_bottom_'+lisha_id_move).style.left = eval('lisha.'+lisha_id_move+'.columns.'+iterable_element+'.arrow')-5-document.getElementById('liste_'+lisha_id_move).scrollLeft+'px';
			eval('lisha.'+lisha_id_move+'.destination_column = '+iterable_element.replace(/c/g,"")+';');

			// Don't move near the column
			if(i == lisha_column_move || i == lisha_column_move + 1)
			{
				document.getElementById('lisha_column_move_div_float_forbidden_'+lisha_id_move).className = '__'+eval('lisha.'+lisha_id_move+'.theme')+'__float_forbidden';
			}
			else
			{
				document.getElementById('lisha_column_move_div_float_forbidden_'+lisha_id_move).className = '__'+eval('lisha.'+lisha_id_move+'.theme')+'__float_column';
			}
			break;
		}
		i++;
	}	
}
/**==================================================================*/


/**==================================================================
 * Call when release mouse button on column header
 ====================================================================*/
function lisha_move_column_stop()
{
	// Hide float move div
	document.getElementById('lisha_column_move_div_float_'+lisha_id_move).style.display = 'none';

	//==================================================================
	// Reset global javascript variable
	//==================================================================
	cursor_start = 0;
	lisha_column_in_move = false;
	//==================================================================

	//==================================================================
	// Hide up and down move arrow of column
	//==================================================================
	document.getElementById('arrow_move_column_top_'+lisha_id_move).style.display = 'none';
	document.getElementById('arrow_move_column_bottom_'+lisha_id_move).style.display = 'none';
	//==================================================================

	document.body.className = lisha_body_style;

	// IE 
	if(typeof(document.body.onselectstart) != "undefined") 
	{
		document.body.onselectstart = null;
	}

	// Have a real move ?
	if(lisha_column_move != eval('lisha.'+lisha_id_move+'.destination_column;') && eval('lisha.'+lisha_id_move+'.destination_column;') != eval('undefined') && lisha_column_move + 1 != eval('lisha.'+lisha_id_move+'.destination_column;') && eval('lisha.'+lisha_id_move+'.destination_column;') != eval('undefined'))
	{
		move_column_ajax(lisha_id_move);
	}
	else
	{
		if(eval('lisha.'+lisha_id_move+'.destination_column;') == eval('undefined'))
		{
			click_column_order(lisha_id_move,lisha_column_move);
		}
		else
		{
			eval('lisha.'+lisha_id_move+'.destination_column = undefined;'); // SRX_MOVE_COLUMN_THEN_ABORT
		}
		/**==================================================================*/
	}
}
/**==================================================================*/


/**==================================================================
 * User change or add new column sort order ( Control key with right click )
 *
 * @lisha_id	    : lisha internal identifier
 * @column          : Internal column numner 1..2.. and so on
 ====================================================================*/
function click_column_order(lisha_id,column)
{
	/**==================================================================
	 * Order the column
	 ====================================================================*/	
	if(eval('lisha.'+lisha_id+'.selected_column.ctrl') == true)
	{
		/**==================================================================
		 * Add a new order clause
		 ====================================================================*/	
		if(eval('lisha.'+lisha_id+'.columns.c'+column+'.order') == 'ASC')
		{
			eval('lisha.'+lisha_id+'.columns.c'+column+'.order = "DESC"');
		}
		else
		{
			eval('lisha.'+lisha_id+'.columns.c'+column+'.order = "ASC"');
		}

		var mode = __ADD__;
		/**==================================================================*/
	}
	else
	{
		/**==================================================================
		 * Delete other order clause
		 ====================================================================*/	
		var mode = __NEW__;
		for(var iterable_element in eval('lisha.'+lisha_id+'.columns')) 
		{
			if(iterable_element == "c"+column)
			{
				if(eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.order') == 'ASC')
				{
					eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.order = "DESC"');
				}
				else
				{
					eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.order = "ASC"');
				}
			}
			else
			{
				eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.order = "";');
			}
		}
		/**==================================================================*/
	}

	lisha_column_order(lisha_id,eval('lisha.'+lisha_id+'.columns.c'+column+'.order'),column,mode);
}
/**==================================================================*/


/**==================================================================
 * Starting to move a column, call at the begining
 *
 * @evt	    : Web browser event
 * @column  : column id
 * @id      : lisha internal id
 ====================================================================*/
function lisha_move_column_start(evt,column,id)
{
	var evt = (evt)?evt : event;
	/**==================================================================
	 * Initiating global variable
	 ====================================================================*/	
	lisha_column_move = column;				    // The moving column
	lisha_id_move = id;						    // The lisha
	lisha_column_in_move = true;				// Flag column move event in progress
	lisha_body_style = document.body.className;
	/**==================================================================*/

	/**==================================================================
	 * Vertical placement of the arrow
	 ====================================================================*/	
	document.getElementById('arrow_move_column_top_'+lisha_id_move).style.top = document.getElementById('header_'+id).offsetTop-10+'px';
	document.getElementById('arrow_move_column_bottom_'+lisha_id_move).style.top = document.getElementById('header_'+id).offsetHeight+document.getElementById('header_'+id).offsetTop+'px';
	/**==================================================================*/

	/**==================================================================
	 * Display the float content
	 ====================================================================*/	
	// Set the content of the float menu
	lisha_set_innerHTML('lisha_column_move_div_float_content_'+lisha_id_move,lisha_get_innerHTML('th'+lisha_column_move+'_'+lisha_id_move));
	/**==================================================================*/

	/**==================================================================
	 * Disable selection
	 ====================================================================*/	
	// Mozilla & Safari
	document.body.className += ' __body_no_select';

	// IE 
	if(typeof(document.body.onselectstart) != "undefined") 
	{
		document.body.onselectstart = function(){return false;};
	}
	/**==================================================================*/

	eval('lisha.'+lisha_id_move+'.selected_column = new Object();');

	if(evt.ctrlKey)
	{
		eval('lisha.'+lisha_id_move+'.selected_column.ctrl = true;');
	}
	else
	{
		eval('lisha.'+lisha_id_move+'.selected_column.ctrl = false;');
	}
}
/**==================================================================*/


function getCSSProperty(mixed, sProperty) 
{
   var oNode = (typeof mixed == "object") ?  mixed : document.getElementById(mixed);

	if(document.defaultView) {
		return document.defaultView.getComputedStyle(oNode, null).getPropertyValue(sProperty);
	}
	else if(oNode.currentStyle) {
		sProperty = sProperty.replace(/\-(\w)/g, function(m,c){return c.toUpperCase();});
		return oNode.currentStyle[sProperty];
	}
	else {
		return null;
	}
}

/**
 * Retourne les coordonnées d'un élément pour Internet Explorer.
 */
function ieGetCoords(elt) 
{
	var coords = elt.getBoundingClientRect();
	var border = getCSSProperty(document.getElementsByTagName('HTML')[0], 'border-width');
	var border = (border == 'medium') ? 2 : parseInt(border);

	elt.left += Math.max(elt.ownerDocument.documentElement.scrollLeft, elt.ownerDocument.body.scrollLeft) - border;
	elt.top  += Math.max(elt.ownerDocument.documentElement.scrollTop, elt.ownerDocument.body.scrollTop) - border;

	return coords;
}

/** 
 * Retourne les coordonnées d'un élément sur une page en fonction de tous ses éléments parents.
 * 
 * @param objet element
 * @param objet eltRef (optionnel)
 * @return json coords = {left:x, top:x}
 */
function getElementCoords(element, eltReferant) {

	var coords = {left: 0, top: 0};

	// IE pour résoudre le problème des marges (IE comptabilise dans offsetLeft la propriété marginLeft).
	if (element.getBoundingClientRect) {

		coords = ieGetCoords(element);

		if (typeof(eltReferant) == 'object') {
			var coords2 = ieGetCoords(eltReferant);

			coords.left -= coords2.left;
			coords.top  -= coords2.top;

			coords2 = null;
		}
	}
	// Les autres : récursivité sur offsetParent.
	else {

		while (element) {

			if (/^table$/i.test(element.tagName) && element.getElementsByTagName('CAPTION').length == 1 && getCSSProperty(element, 'position').toLowerCase() == 'relative') {
				coords.top += element.getElementsByTagName('CAPTION')[0].offsetHeight;
			}

			coords.left += element.offsetLeft;
			coords.top  += element.offsetTop;
			element      = element.offsetParent;

			if (typeof(eltReferant) == 'object' && element === eltReferant) {
				break;
			}
		}
	}

	return coords;
}