/**
 * Handler when keydown on column input search 
 * @param evt event
 * @param el element
 * @param lisha_id ID of the lisha
 * @param column Column of the lisha
 */
function lisha_input_keydown(evt,el,lisha_id,column)
{
	try 
	{	
		if((evt.keyCode != 13 && evt.keyCode != 9 && evt.keyCode != 40 && evt.keyCode != 39 && evt.keyCode != 38 && evt.keyCode != 37 && evt.keyCode != 27 && evt.keyCode != 18 && evt.keyCode != 16 && evt.keyCode != 20))		
		{
			/**==================================================================
			 * A key was pressed (leter,number,backspace or espace)
			 ====================================================================*/	
			// Clear the last timeout
			eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');
	
			// Set a timeout before send the query
			eval('lisha.'+lisha_id+'.time_input_search = setTimeout(\'lisha_input_search(\\\''+lisha_id+'\\\',\\\''+encodeURIComponent(el.value).replace(/\'/g,"\\\\\\\\\\\\\\'")+'\\\','+column+')\', 750)');
			/**==================================================================*/
			
			document.getElementById('liste_'+lisha_id).scrollLeft = document.getElementById('header_'+lisha_id).scrollLeft;		
		}
		else
		{
			if(evt.keyCode == 40)
			{
				/**==================================================================
				 * Bottom arrow pressed
				 ====================================================================*/	
				if(eval('lisha.'+lisha_id+'.menu_quick_search') && eval('lisha.'+lisha_id+'.input_search_selected_line') < 6)
				{
					if(eval('lisha.'+lisha_id+'.input_search_selected_line') == 0)
					{
						document.getElementById('lisha_input_result_1_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result';
					}
					
					if(eval('lisha.'+lisha_id+'.input_search_selected_line') < 6)
					{
						if(eval('lisha.'+lisha_id+'.input_search_selected_line') > 0)
							document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result';
						eval('lisha.'+lisha_id+'.input_search_selected_line += 1');
						document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result_hover';
					}
				}
				/**==================================================================*/	
			}
			else
			{
				if(evt.keyCode == 38)
				{
					/**==================================================================
					 * Top arrow pressed
					 ====================================================================*/	
					if(eval('lisha.'+lisha_id+'.input_search_selected_line') > 1)
					{
						document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result';
						eval('lisha.'+lisha_id+'.input_search_selected_line -= 1');
						document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result_hover';
					}
					/**==================================================================*/	
				}
				else
				{
					if(evt.keyCode == 27)
					{
						// esc key
						lisha_cancel_edit(lisha_id);
					}
					else
					{
						// Other key was pressed, close the input search result
						eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');
						if(evt.keyCode == 13)
						{
							//eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');
							//toggle_wait_input(lisha_id,column);
							/**==================================================================
							 * Cariage return pressed
							 ====================================================================*/
							if(eval('lisha.'+lisha_id+'.edit_mode') == __EDIT_MODE__)
							{
								//eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');
								save_lines(lisha_id);
							}
							else
							{
								if(eval('lisha.'+lisha_id+'.input_search_selected_line') > 0)
								{
									eval('document.getElementById(\''+lisha_id+'_rapid_search_l'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'\').onclick();');
								}
								else
								{
									if(eval('lisha.'+lisha_id+'.menu_quick_search') && eval('lisha.'+lisha_id+'.input_search_selected_line') == 0)
									{
										lisha_define_filter(lisha_id,encodeURIComponent(el.value),column,true);
									}
									else
									{
										lisha_define_filter(lisha_id,encodeURIComponent(el.value),column,true);
									}
								}
							}
							/**==================================================================*/	
						}
						else
						{
							if(evt.keyCode != 16)
							{
								close_input_result(lisha_id);
							}
							else
							{
								//alert("passe");
							}
						}
					}
				}
			}
		}
	} 
	catch(e) 
	{
		lisha_display_error(lisha_id,e,'input_col_1');
	}
}

function toggle_wait_input(lisha_id,column)
{
	if(document.getElementById('wait_input_'+lisha_id).style.display != 'block')
	{
		// Display
		/**==================================================================
		 * Get the position of the lisha and the input
		 ====================================================================*/	
		try {
			var pos_input = lisha_getPosition('th_input_'+column+'__'+lisha_id); 
		} catch (e) {
			alert('th_input_'+column+'__'+lisha_id);
		}
		
		var pos_lisha = lisha_getPosition('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__'); 
		/**==================================================================*/	
		
		/**==================================================================
		 * Set the size of the wait picture
		 ====================================================================*/	
		document.getElementById('wait_input_'+lisha_id).style.width = document.getElementById('th_input_'+column+'__'+lisha_id).offsetWidth-4+'px';
		/**==================================================================*/	
		
		/**==================================================================
		 * Vertical placement
		 ====================================================================*/	
		document.getElementById('wait_input_'+lisha_id).style.top = pos_input[1]-pos_lisha[1]+document.getElementById('th_input_'+column+'__'+lisha_id).offsetHeight-6+'px';
		/**==================================================================*/	

		/**==================================================================
		 * Horizontal placement
		 ====================================================================*/	
		document.getElementById('wait_input_'+lisha_id).style.left = pos_input[0]-pos_lisha[0]-document.getElementById('liste_'+lisha_id).scrollLeft+'px';
		/**==================================================================*/
		
		/**==================================================================
		 * Display the wait picture
		 ====================================================================*/	
		document.getElementById('wait_input_'+lisha_id).style.display = 'block';
		/**==================================================================*/
	}
	else
	{
		/**==================================================================
		 * Hide the wait picture
		 ====================================================================*/	
		document.getElementById('wait_input_'+lisha_id).style.display = 'none';
		/**==================================================================*/
	}
}


/**
 * Handler when the input of the lisha change
 * @param lisha_id ID of the lisha
 * @param column Column of the lisha
 */
function lisha_col_input_change(lisha_id,column)
{
	lisha_define_filter(lisha_id,document.getElementById(encodeURIComponent('th_input_'+column+'__'+lisha_id)).value,column,false);
}

 /**
  * Search on the column result
  * @param lisha_id ID of the lisha
  * @param txt text to search
  * @param column Column of the lisha
  * @param ajax_return response of the server
  */
function lisha_input_search(lisha_id,txt,column,quick_search,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
Z		if(typeof(quick_search) == 'undefined') quick_search = true;
		toggle_wait_input(lisha_id,column);

		/**==================================================================
		 * Ajax init
		 ====================================================================*/	
		var conf = new Array();	

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=5&column='+column+'&txt='+txt+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_input_search';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+txt+"',"+column+','+quick_search;
		ajax_call(conf);
		/**==================================================================*/
		
		wait_column_bullet(lisha_id);
	}
	else
	{
		try 
		{
			toggle_wait_input(lisha_id,column);

			if(eval('lisha.'+lisha_id+'.time_input_search;') != eval('false'))
			{
				/**==================================================================
				 * Display the query result
				 ====================================================================*/	
				
				// Get the ajax return in json format
				var json = get_json(ajax_return);

				// Update the json object
				eval(decodeURIComponent(json.lisha.json));
				
				update_column_bullet(lisha_id);
				
				if(quick_search)
				{
					// Quick search flag
					eval('lisha.'+lisha_id+'.menu_quick_search = true;');
					eval('lisha.'+lisha_id+'.menu_quick_search_col = column;');
					
					var div_menu = document.getElementById('lis_column_header_menu_'+lisha_id);
	
					if(typeof(json.lisha.content) != 'object')
					{
						// Prepare the div
						html = '<table class="shadow">';
						html += '<tr>';
						html += '<td class="shadow_l"></td>';
						html += '<td colspan=2 class="shadow">';
						html += decodeURIComponent(json.lisha.content);
						html += '</td></tr>';
						html += '<tr><td class="shadow_l_b"></td><td class="shadow_b"></td><td class="shadow_r_b"></td></tr></table>';
						 
						// Set the result to the menu
						div_menu.innerHTML = html;
				
						// Display the menu
						div_menu.style.display = 'block';
						
						// Positions the menu
						position_input_result(lisha_id,column);
						
						// Initialise the selected line
						eval('lisha.'+lisha_id+'.input_search_selected_line = 0');
					}
					else
					{
						if(div_menu.style.display == 'block')
						{
							div_menu.style.display = 'none';
						}
					}
				}
				// Re-Activate the lisha horizontal scroll
				document.getElementById('liste_'+lisha_id).onscroll = function(){lisha_horizontal_scroll(lisha_id);};
				/**==================================================================*/
			}
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'input_col_2');
		}
	}
}

function update_column_bullet(lisha_id)
{
	try 
	{
		for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
		{
			if(document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id))
			{
				var is_lovable = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.is_lovable');
				var lov_perso = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.lov_perso');
				
				if(lov_perso != undefined && is_lovable != undefined && is_lovable == true)
				{
					document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header_lovable __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
				}
				else
				{
					if(lov_perso != undefined)
					{
						document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
					}
					else
					{
						
						document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
					}
				}
			}
		}
	}
	catch(e) 
	{
		lisha_display_error(lisha_id,e,'input_col_3  '+'th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id'));
	}
}

function wait_column_bullet(lisha_id)
{
	for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
	{
		var is_lovable = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.is_lovable');
		var lov_perso = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.lov_perso');
		
		if(lov_perso != undefined && is_lovable != undefined && is_lovable == true)
		{
			document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header_check_is_lovable __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
		}
		else
		{
			if(lov_perso != undefined)
			{
				document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header_check_is_lovable __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
			}
		}
	}	
}


/**
 * Search on the column result
 * @param lisha_id ID of the lisha
 * @param txt text to search
 * @param column Column of the lisha
 * @param ajax_return response of the server
 */
function lisha_define_filter(lisha_id,txt,column,display,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		/**==================================================================
		 * Get all updated filter
		 ====================================================================*/	
		url_filter = '&filter_col='+column+'&filter='+encodeURIComponent(txt);
		/**==================================================================*/
		
		/**==================================================================
		 * Ajax init
		 ====================================================================*/	
		var conf = new Array();	
		
		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=6'+url_filter+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_define_filter';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"',null,"+column+','+display;

		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		// Get the ajax return in json format
		var json = get_json(ajax_return);

		// Update the json object
		try
		{
			if(eval('lisha.'+lisha_id+'.menu_quick_search'))
			{
				var quick_search = true;
				var quick_search_column = eval('lisha.'+lisha_id+'.menu_quick_search_col');
			}
			else
			{
				var quick_search = false;
			}

			if(display)
			{
				eval(decodeURIComponent(json.lisha.json));
				eval(decodeURIComponent(json.lisha.json_line));
			}
			
			update_column_bullet(lisha_id);
			
			if(quick_search)
			{
				eval('lisha.'+lisha_id+'.menu_quick_search = true;');
				eval('lisha.'+lisha_id+'.menu_quick_search_col = '+quick_search_column);
			}
			
			if(display)
			{
				lisha_refresh_page_ajax(lisha_id);
			}
		}
		catch(e)
		{
			lisha_display_error(lisha_id,e,'input_col_4');
		}
	}
}

/**
 * Handler when a line result was clicked
 * @param lisha_id ID of the lisha
 * @param column Column of the lisha
 * @param line Line of the result
 */
function lisha_input_result_click(lisha_id,column,line,txt)
{
	// Insert the text clicked on the input search
	document.getElementById('th_input_'+column+'__'+lisha_id).value = txt;
	
	// Close the search result
	close_input_result(lisha_id);
	
	// Search
	alert(6);
    lisha_define_filter(lisha_id,encodeURIComponent(document.getElementById('th_input_'+column+'__'+lisha_id).value),column,true);
}


/**
 * Close the column input result
 * @param lisha_id ID of the lisha
 */
function close_input_result(lisha_id)
{
	// Unset the data of the menu
	lisha_set_innerHTML('lis_column_header_menu_'+lisha_id,'');
	
	// Hide the menu
	document.getElementById('lis_column_header_menu_'+lisha_id).style.display = 'none';
	
	// Reset the selected line
	eval('lisha.'+lisha_id+'.input_search_selected_line = 0;');
	
	// Quick search flag
	eval('lisha.'+lisha_id+'.menu_quick_search = false;');
	eval('lisha.'+lisha_id+'.menu_quick_search_col = false;');
}


/**
 * Place the input result correctly on the lisha
 * @param id ID of the lisha
 * @param column Column of the menu
 */
function position_input_result(id,column)
{
	/**==================================================================
	 * Vertical placement
	 ====================================================================*/	
	var top_container = document.getElementById('conteneur_menu_'+id).offsetTop;
	document.getElementById('lis_column_header_menu_'+id).style.top = top_container+'px';
	/**==================================================================*/	
	
	/**==================================================================
	 * Horizontal placement
	 ====================================================================*/	
	var pos_th = document.getElementById('th_2_c'+column+'_'+id).offsetLeft - document.getElementById('liste_'+id).scrollLeft;
	document.getElementById('lis_column_header_menu_'+id).style.left = pos_th+'px';
	/**==================================================================*/

	/**==================================================================
	 * Test the position of the menu
	 ====================================================================*/	
	// Get the position of the menu
	var pos_menu = lisha_getPosition('lis_column_header_menu_'+id); 
	
	// Get the position of the lisha
	var pos_lisha = lisha_getPosition('lis__'+eval('lisha.'+id+'.theme')+'__lisha_table_'+id+'__'); 

	// Test if the menu is not out of the left corner of the lisha
	if(pos_lisha[0] > pos_menu[0])
	{
		document.getElementById('lis_column_header_menu_'+id).style.left = 0+'px';
	}	
	/**==================================================================*/
	
	eval('lisha.'+id+'.menu_left = '+document.getElementById('lis_column_header_menu_'+id).offsetLeft+';');
}