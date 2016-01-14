var isCtrl = false;
/**==================================================================
 * onkeydown event to catch special key that not provide visible digit like delete, suppr function key f1...f12, escape and so on...
 * @evt				    : catch browser event
 ====================================================================*/
function lisha_input_keyup_pressed(evt)
{
	isCtrl=false;
}
/**==================================================================*/


/**==================================================================
 * onkeydown event to catch special key that not provide visible digit like delete, suppr function key f1...f12, escape and so on...
 * @evt				    : catch browser event
 * @lisha_id		    : internal lisha identifier
 * @column      	    : Column of the lisha
 * @quick_search_mode 	: true or false ( true means quick search on )
 * @edit_mode           : true means row(s) under updating
 ====================================================================*/
function lisha_input_keydown_pressed(evt,lisha_id,column, quick_search_mode, edit_mode)
{
	if(evt.keyCode == 17)
	{
		isCtrl=true;
	}

	if(evt.keyCode == 186 || evt.keyCode == 59 || evt.keyCode == 190) // key ; for Safari, FireFox and Chrome !!
	{
		if(isCtrl)
		{
			var div_root_updating = 'th_input_'+column+'__'+lisha_id;
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
			conf['param_fonction_a_executer_reponse'] = "'"+div_root_updating+"'";
			ajax_call(conf);
			//==================================================================
		}
	}

	if( evt.keyCode == 8 || evt.keyCode == 46 ) // Del or suppr
	{
		//==================================================================
		// Deletion of digit, please update
		//====================================================================
		if(quick_search_mode == true && !edit_mode)
		{
			// Clear the last timeout
			eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');

			// Set a timeout before send the query
			eval('lisha.'+lisha_id+'.time_input_search = setTimeout(\'lisha_input_search(\\\''+lisha_id+'\\\','+column+')\', 750)');
		}
		//==================================================================
		document.getElementById('liste_'+lisha_id).scrollLeft = document.getElementById('header_'+lisha_id).scrollLeft;

		if(edit_mode)
		{
			// Active update checkbox
			document.getElementById('chk_edit_c'+column+'_'+lisha_id).checked = true;
		}
	}

	if(evt.keyCode == 27 ) // Escape
	{
		// Clear the last timeout
		eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');

		// Hide ajax wait
		document.getElementById('wait_input_'+lisha_id).style.display = 'none';
	}
}
/**==================================================================*/


 /**==================================================================
 * lisha_input_keydown  : Key pressed on column header input area
 * @evt				    : catch browser event
 * @el			        : element ???
 * @lisha_id		    : internal lisha identifier
 * @column      	    : Column of the lisha
 * @quick_search_mode 	: true or false ( true means quick search on )
 * @edit_mode           : true means row(s) under updating
 ====================================================================*/
function lisha_input_keydown(evt,el,lisha_id,column, quick_search_mode, edit_mode)
{
	try
	{	
		if((evt.keyCode != 13 && evt.keyCode != 9 && evt.keyCode != 40 && evt.keyCode != 39 && evt.keyCode != 38 && evt.keyCode != 37 && evt.keyCode != 27 && evt.keyCode != 18 && evt.keyCode != 16 && evt.keyCode != 20))		
		{
			//==================================================================
			// A key was pressed (letter,number,backspace or espace)
			// No rows current in update
			//====================================================================
			if(quick_search_mode == true && !edit_mode)
			{
				// Clear the last timeout
				eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');

				// Set a timeout before send the query
				eval('lisha.'+lisha_id+'.time_input_search = setTimeout(\'lisha_input_search(\\\''+lisha_id+'\\\','+column+')\', 750)');
			}
			//==================================================================
			document.getElementById('liste_'+lisha_id).scrollLeft = document.getElementById('header_'+lisha_id).scrollLeft;
		}
		else
		{
			if(evt.keyCode == 40)
			{
				//==================================================================
				// Bottom arrow pressed
				//====================================================================
				if(eval('lisha.'+lisha_id+'.menu_quick_search') && eval('lisha.'+lisha_id+'.input_search_selected_line') < 6)
				{
					if(eval('lisha.'+lisha_id+'.input_search_selected_line') == 0)
					{
						document.getElementById('lisha_input_result_1_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result';
					}

					var test_div = document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line + 1')+'_'+lisha_id);

					if(test_div != null)
					{
						if(eval('lisha.'+lisha_id+'.input_search_selected_line') > 0)
							document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result';

						eval('lisha.'+lisha_id+'.input_search_selected_line += 1');

						document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result_hover';
					}
				}
				//====================================================================
			}
			else
			{
				if(evt.keyCode == 38)
				{
					//====================================================================
					// Top arrow pressed
					//====================================================================
					if(eval('lisha.'+lisha_id+'.input_search_selected_line') > 1)
					{
						document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result';
						eval('lisha.'+lisha_id+'.input_search_selected_line -= 1');
						document.getElementById('lisha_input_result_'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'_'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_column_header_input_result_hover';
					}
				//====================================================================
				}
				else
				{
					if(evt.keyCode == 27)
					{
						//====================================================================
						// Esc key pressed
						//====================================================================
						lisha_cancel_edit(lisha_id);
						//====================================================================
					}
					else
					{
						// Other key was pressed, close the input search result
						eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');
						if(evt.keyCode == 13)
						{
							//====================================================================
							// Carriage return pressed
							//====================================================================
							if(edit_mode == true)
							{
								//eval('clearTimeout(lisha.'+lisha_id+'.time_input_search);');
								document.getElementById('th_input_'+column+'__'+lisha_id).blur();
								save_lines(evt,lisha_id,'update');
							}
							else
							{
								// Hide any ajax search wait
								document.getElementById('wait_input_'+lisha_id).style.display = 'none';

								document.getElementById('th_input_'+column+'__'+lisha_id).blur();
								if(eval('lisha.'+lisha_id+'.input_search_selected_line') > 0)
								{
									//alert(0);
									eval('document.getElementById(\''+lisha_id+'_rapid_search_l'+eval('lisha.'+lisha_id+'.input_search_selected_line')+'\').onclick();');
								}
								else
								{
									if(eval('lisha.'+lisha_id+'.menu_quick_search') && eval('lisha.'+lisha_id+'.input_search_selected_line') == 0)
									{
										//alert(1);
										lisha_define_filter(lisha_id,encodeURIComponent(el.value),column,true);
									}
									else
									{
										//alert(2);
										lisha_define_filter(lisha_id,encodeURIComponent(el.value),column,true);
									}
								}
							}
							//====================================================================
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
		alert(e);
		lisha_display_error(lisha_id,e,'input_col_1');
	}
}
/**==================================================================*/


/**==================================================================
 * Hide or display progression bar of ajax call in input box
 * @lisha_id		    : internal lisha identifier
 * @column      	    : Column id where something changed
 ====================================================================*/
function toggle_wait_input(lisha_id,column)
{
	if(document.getElementById('wait_input_'+lisha_id).style.display != 'block')
	{
		//==================================================================
		// Get lisha position and the input
		//==================================================================
		try
		{
			var pos_input = lisha_getPosition('th_input_'+column+'__'+lisha_id);
		}
		catch (e)
		{
			//alert('zzzzzzzth_input_'+column+'__'+lisha_id);
		}

		var pos_lisha = lisha_getPosition('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__');
		//==================================================================

		// Set the size of the wait picture
		document.getElementById('wait_input_'+lisha_id).style.width = document.getElementById('th_input_'+column+'__'+lisha_id).offsetWidth-4+'px';

		// Vertical placement
		document.getElementById('wait_input_'+lisha_id).style.top = pos_input[1]-pos_lisha[1]+document.getElementById('th_input_'+column+'__'+lisha_id).offsetHeight-6+'px';

		// Horizontal placement
		document.getElementById('wait_input_'+lisha_id).style.left = pos_input[0]-pos_lisha[0]-document.getElementById('liste_'+lisha_id).scrollLeft+'px';

		 // Hide the wait picture
		document.getElementById('wait_input_'+lisha_id).style.display = 'block';
	}
	else
	{
		 // Hide the wait picture
		document.getElementById('wait_input_'+lisha_id).style.display = 'none';
	}
}
/**==================================================================*/


/**==================================================================
 * Call when column input box lost focus
 * Use to show first distinct entries found
 *
 * @lisha_id 	: lisha internal identifier
 * @column      : number of column where change is done
 ====================================================================*/
function lisha_col_input_change(lisha_id,column)
{
	// Do not set a filter when currently updating lines
	if(eval('lisha.'+lisha_id+'.edit_mode') != __EDIT_MODE__)
	{
		//alert(3);
		//lisha_define_filter(lisha_id,document.getElementById(encodeURIComponent('th_input_'+column+'__'+lisha_id)).value,column,false);
	}
}
/**==================================================================*/


 /**==================================================================
 * Call when change column contain to display quick first matched results
 * Use to show first distinct entries found
 *
 * @lisha_id 	    : lisha internal identifier
 * @column          : number of column where change is done
 * @quick_search    : is enable quick search
 * @ajax_return     : Filled up on ajax call back
 ====================================================================*/
function lisha_input_search(lisha_id,column,quick_search,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		if(typeof(quick_search) == 'undefined')
		{
			quick_search = true;
		}

		// Display ajax call in input area
		toggle_wait_input(lisha_id,column);

		// Recover last value input by user in header column input box
		var txt = encodeURIComponent(document.getElementById('th_input_'+column+'__'+lisha_id).value);
		txt = txt.replace(/\'/g,"\\\\\\\\\\\\\\'");

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=5&column='+column+'&txt='+txt+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_input_search';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+column+"','"+quick_search+"'";

		ajax_call(conf);
		//==================================================================

		// Active red marble animation
		wait_column_bullet(lisha_id);
	}
	else
	{
		try 
		{
			toggle_wait_input(lisha_id,column);

			if(eval('lisha.'+lisha_id+'.time_input_search;') != eval('false'))
			{
				//==================================================================
				// Display query result
				//==================================================================

				// Get the ajax return in json format
				var json = get_json(ajax_return);

				// Update the json object
				eval(decodeURIComponent(json.lisha.json));
				eval('lisha.'+lisha_id+'.lmod_opened = true;');

				update_column_bullet(lisha_id);

				if(quick_search)
				{
					// Quick search flag
					eval('lisha.'+lisha_id+'.menu_quick_search = true;');
					eval('lisha.'+lisha_id+'.menu_quick_search_col = column;');
					eval('lisha.'+lisha_id+'.lmod_opened = true;');
					var div_menu = document.getElementById('lis_column_header_menu_'+lisha_id);

					if(typeof(json.lisha.content) != 'object')
					{
						// Prepare the div
						var html = '<table class="shadow">';
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
				//==================================================================
			}
		} 
		catch(e) 
		{
			lisha_display_error(lisha_id,e,'input_col_2');
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Manage marble on column header
 * Marble appears when lov query is solvable
 *  red     : custom lov
 *  white   : standard distinct list value ( by default on every column without any custom lov defined )
 *
 *  if no marble that means that custom lov contain still external reference ( ||TAGLOV_ ) that can't be replace with specific value )
 *
 * @lisha_id 	    : lisha internal identifier
 ====================================================================*/
function update_column_bullet(lisha_id)
{
	try 
	{
		for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
		{
			if(document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id))
			{
				// is_lovable = true means that distinct values query is solvable ( means no more TAGLOV )
				var is_lovable = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.is_lovable');

				// lov_perso = true means that you have defined custom lov on column
				var lov_perso = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.lov_perso');

				// SRX try to enhance marble part SRX_MANAGE_MARBLE_ON_INPUT_CHANGE
				 if(is_lovable == true && lov_perso != 'undefined')
				{
					document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
				}

				if(is_lovable == true && lov_perso == true)
				{
					document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header_lovable __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
				}

				if(is_lovable == 'undefined' || is_lovable == false)
				{
					document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
				}
			}
		}
	}
	catch(e) 
	{
		lisha_display_error(lisha_id,e,'input_col_3  '+'th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id'));
	}
}
/**==================================================================*/


/**==================================================================
 * Manage wait gif of red marbles on column header
 *
 * @lisha_id 	    : lisha internal identifier
 ====================================================================*/
function wait_column_bullet(lisha_id)
{
	for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
	{
		var is_lovable = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.is_lovable');
		var lov_perso = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.lov_perso');

		if(lov_perso != undefined && is_lovable != undefined && is_lovable == true)
		{
			//alert(iterable_element+'-'+lov_perso);
			document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header_check_is_lovable __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
		}
		else
		{
			if(lov_perso != undefined )
			{
				//alert(eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id'));
				document.getElementById('th_menu_'+eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id')+'__'+lisha_id).className = '__'+eval('lisha.'+lisha_id+'.theme')+'_menu_header_check_is_lovable __'+eval('lisha.'+lisha_id+'.theme')+'_men_head';
			}
			else
			{
				//alert(iterable_element+'-'+is_lovable+lov_perso);
			}
		}
	}	
}
/**==================================================================*/


/**==================================================================
 * Build new filter option input by user in php session
 * @lisha_id	: Internal lisha identifier
 * @txt         : Input text box content in column
 * @column      : Number of column where input running
 * @display     : true means force reload content of lisha, false means no display refresh
 * @ajax_return : null on first call then contains php return of ajax call
 ====================================================================*/
function lisha_define_filter(lisha_id,txt,column,display,ajax_return)
{
	// Display wait background
	lisha_display_wait(lisha_id);

	if(typeof(ajax_return) == 'undefined')
	{
		// Get all updated filter
		var url_filter = '&filter_col='+column+'&filter='+encodeURIComponent(txt);
		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// Reponse Text
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=6'+url_filter+'&selected_lines='+encodeURIComponent(get_selected_lines(lisha_id));
		conf['fonction_a_executer_reponse'] = 'lisha_define_filter';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"',null,"+column+','+display;

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		// Get the ajax return in json format
		//var json = get_json(ajax_return);

		var quick_search = true;
		// Update the json object
		try
		{
			if(eval('lisha.'+lisha_id+'.menu_quick_search'))
			{
				quick_search = true;
				var quick_search_column = eval('lisha.'+lisha_id+'.menu_quick_search_col');
			}
			else
			{
				quick_search = false;
			}

			if(display)
			{
				//eval(decodeURIComponent(json.lisha.json));
				//eval(decodeURIComponent(json.lisha.json_line));
				eval('lisha.'+lisha_id+'.lmod_opened = true;');
			}

			// SRX todo marble check
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
/**==================================================================*/


/**==================================================================
 * Called on column quick search list
 * @lisha_id		: internal lisha identifier
 * @column          : Column id in lisha
 * @line            : line updated
 * @txt             : text
 ====================================================================*/
function lisha_input_result_click(lisha_id,column,line,txt)
{
	// Insert the text clicked on the input search
	document.getElementById('th_input_'+column+'__'+lisha_id).value = txt;

	// Close the search result
	close_input_result(lisha_id);

	// Shadow on back screen
	lisha_cover_with_filter(lisha_id);

	// Launch automatic Search
	//alert(4);
	lisha_define_filter(lisha_id,encodeURIComponent(document.getElementById('th_input_'+column+'__'+lisha_id).value),column,true);
}
/**==================================================================*/


/**==================================================================
 * To analyse....
 * Close the column input result
 *
 * @lisha_id		: internal lisha identifier
 ====================================================================*/
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
/**==================================================================*/


/**==================================================================
 * To analyse....
 *
 * @lisha_id		: internal lisha identifier
 * @column          : Column id in lisha
 ====================================================================*/
function position_input_result(lisha_id,column)
{
	// Vertical position
	var top_container = document.getElementById('conteneur_menu_'+lisha_id).offsetTop;
	document.getElementById('lis_column_header_menu_'+lisha_id).style.top = top_container+'px';

	// Horizontal position
	var pos_th = document.getElementById('th_2_c'+column+'_'+lisha_id).offsetLeft - document.getElementById('liste_'+lisha_id).scrollLeft;
	document.getElementById('lis_column_header_menu_'+lisha_id).style.left = pos_th+'px';

	//==================================================================
	// Get menu position
	//==================================================================
	// Get the position of the menu
	var pos_menu = lisha_getPosition('lis_column_header_menu_'+lisha_id);

	// Get the position of the lisha
	var pos_lisha = lisha_getPosition('lis__'+eval('lisha.'+lisha_id+'.theme')+'__lisha_table_'+lisha_id+'__');

	// Test if the menu is not out of the left corner of the lisha
	if(pos_lisha[0] > pos_menu[0])
	{
		document.getElementById('lis_column_header_menu_'+lisha_id).style.left = 0+'px';
	}
	//==================================================================

	eval('lisha.'+lisha_id+'.menu_left = '+document.getElementById('lis_column_header_menu_'+lisha_id).offsetLeft+';');
}
/**==================================================================*/