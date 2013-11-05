/**==================================================================
 * ??? To analyse
 *
 * @lisha_id	    : lisha_id Id of the lisha
 * @p_line	        : ???
 * @ajax_return     : use with ajax return
 ====================================================================*/
function lisha_lmod_click(lisha_id,p_line,ajax_return)
{
	//alert('lisha_lmod_'+lisha_id);
	if(typeof(ajax_return) == 'undefined')
	{
		if(typeof(p_line) == 'undefined')
		{
			p_line = null;
		}	

		if(document.getElementById('lisha_lmod_'+lisha_id).style.display == 'none' || document.getElementById('lisha_lmod_'+lisha_id).style.display == '')
		{
			//==================================================================
			// Setup Ajax configuration
			//==================================================================
			var conf = [];

			conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_lmod.php';
			conf['delai_tentative'] = 15000;
			conf['max_tentative'] = 4;
			conf['type_retour'] = false;		// ReponseText
			conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=1';
			conf['fonction_a_executer_reponse'] = 'lisha_lmod_click';
			conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"',"+p_line;
			ajax_call(conf);
			//==================================================================
		}
		else
		{
			// A line was clicked

			// Hide the lisha
			document.getElementById('lisha_lmod_'+lisha_id).style.display = 'none';
			document.getElementById('lisha_lmod_'+lisha_id).style.left = '';
			document.getElementById('lisha_lmod_'+lisha_id).style.right = '';
			document.getElementById('lisha_lmod_'+lisha_id).style.top = '';

			// Turn off the flag lmod opened
			eval('lisha.'+lisha_id+'.lmod_opened = false;');

			if(p_line != null)
			{
				document.getElementById('lst_'+lisha_id).value = lisha_get_innerHTML('div_td_l'+p_line+'_c'+eval('lisha.'+lisha_id+'.c_col_return_id')+'_'+lisha_id);
				lisha_execute_event(__ON_LMOD_INSERT__,__AFTER__,lisha_id);
			}
		}
	}
	else
	{
		try 
		{	
			// Get the ajax return in json format
			var json = get_json(ajax_return);

			lisha_set_innerHTML('lmod_lisha_container_'+lisha_id,decodeURIComponent(json.lisha.content));
			lisha_lmod_place(lisha_id);


			// Update the json object
			eval(decodeURIComponent(json.lisha.json));

			document.getElementById('liste_'+lisha_id).onscroll = function(){lisha_horizontal_scroll(lisha_id);};
			size_table(lisha_id);
			eval('lisha.'+lisha_id+'.lmod_opened = true;');
			lisha_execute_event(__ON_LMOD_OPEN__,__AFTER__,lisha_id);

		} 
		catch(e) 
		{
			alert(e.message);
			// lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


function lisha_lmod_place(lisha_id)
{
	var pos = lisha_getPosition('lst_'+lisha_id);
	document.getElementById('lisha_lmod_'+lisha_id).style.display = 'block';
	if(eval('lisha.'+lisha_id+'.c_position_mode') == '__RELATIVE__')
	{
		// __RELATIVE__
		document.getElementById('lisha_lmod_'+lisha_id).style.top = pos[1] + document.getElementById('lst_'+lisha_id).offsetHeight + 'px';
		document.getElementById('lisha_lmod_'+lisha_id).style.left = pos[0]-7+'px';

		//==================================================================
		// Test lisha position
		//==================================================================

			// Get the position of the lisha
			var pos_lisha = lisha_getPosition('lisha_lmod_'+lisha_id);
			// Get the width of the screen
			var body_width = document.body.offsetWidth;
			// Test if the lisha is not out of the left corner of the screen
			if(pos_lisha[0]+document.getElementById('lisha_lmod_'+lisha_id).offsetWidth > body_width)
			{
				document.getElementById('lisha_lmod_'+lisha_id).style.left = '';
				document.getElementById('lisha_lmod_'+lisha_id).style.right = 0+'px';
				//alert('trop grand\nlisha : '+(pos_lisha[0]+document.getElementById('lisha_lmod_'+lisha_id).offsetWidth)+'\nbody : '+body_width);
			}
		//==================================================================
	}
	else
	{
		// __ABSOLUTE__
		document.getElementById('lisha_lmod_'+lisha_id).style.top = document.getElementById('lst_'+lisha_id).offsetTop + document.getElementById('lst_'+lisha_id).offsetHeight + 'px';
		var pos_lmod = lisha_getPosition('lisha_lmod_'+lisha_id);
		document.getElementById('lisha_lmod_'+lisha_id).style.left = pos[0] - pos_lmod[0]-7+'px';
	}

}

function lisha_clear_value(lisha_id)
{
	try
	{
		document.getElementById(lisha_id).value = '';
	}
	catch(e)
	{
		alert('ID '+lisha_id+' doesn\'t exist');
	}
}