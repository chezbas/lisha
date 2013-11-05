/**==================================================================
 * Get user event type
 *
 * @p_event     : Browser catched event
 ====================================================================*/
function lisha_user_event_get_type(p_event)
{
	//noinspection JSUnresolvedVariable
	if(p_event == __LMOD_OPEN__)
	{
		//noinspection JSUnresolvedVariable
		return __lisha_INTERNAL__;
	}
	else
	{
		//noinspection JSUnresolvedVariable
		return __lisha_EXTERNAL__;
	}
}
/**==================================================================*/


/**==================================================================
 * Lisha user internal action
 *
 * @p_action			: Constant action defined ( __LMOD_OPEN__ )
 * @lisha_id			: internal lisha identifier
 ====================================================================*/
function lisha_user_action(p_action,lisha_id)
{
	//noinspection JSUnresolvedVariable
	switch(p_action)
	{
		case __LMOD_OPEN__:
			lisha_lmod_click(lisha_id);
			break;
	}
}
/**==================================================================*/


function lisha_execute_event(p_event,p_moment,lisha_id)
{
	/* Get the event to do */
	if(typeof(eval('lisha.'+lisha_id+'.event')) != 'undefined')
	{
		for(var lisha_dest in eval('lisha.'+lisha_id+'.event.evt'+p_event))
		{
			for(var lisha_to_event in eval('lisha.'+lisha_id+'.event.evt'+p_event+'.'+lisha_dest))
			{
				for(var actions in eval('lisha.'+lisha_id+'.event.evt'+p_event+'.'+lisha_dest+'.'+lisha_to_event))
				{
					var action_to_do = eval('lisha.'+lisha_id+'.event.evt'+p_event+'.'+lisha_dest+'.'+lisha_to_event+'.'+actions);
					if(lisha_user_event_get_type(action_to_do.exec) == __lisha_INTERNAL__)
					{
						/* Internal action to do */
						if(action_to_do.moment == p_moment)
						{
							lisha_user_action(action_to_do.exec,lisha_dest);
						}
					}
					else
					{
						/* External action to do */
						if(action_to_do.moment == p_moment)
						{
							/* #URL SIBY */
							eval(action_to_do.exec);
						}
					}
				}
			}
		}
	}
}


/**==================================================================
 * event rise on column list sub lisha
 *
 * @lisha_id			: internal lisha identifier
 * @ajax_return			: response of ajax call
 ====================================================================*/
function event_lisha_column_list(lisha_id,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		//lisha_display_wait(lisha_id);

		// Close the child lisha
		//document.getElementById('internal_lisha_'+lisha_id).style.display = 'none';

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 15000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+ssid+'&action=28';
		conf['fonction_a_executer_reponse'] = 'event_lisha_column_list';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"'";
		ajax_call(conf);
		//==================================================================
	}
	else
	{
		ajax_return = JSON.parse(ajax_return);
		//alert(ajax_return.MESSAGE);
		if(ajax_return.STATUT == 'KO')
		{
			document.getElementById(lisha_id+'_child_valide').style.display = 'none';
			msgbox(lisha_id+'_child',decodeURIComponent(lis_lib[122]),decodeURIComponent(ajax_return.MESSAGE));
			//var prompt_btn = new Array([lis_lib[31]],['lisha_cover_with_filter('+lisha_id+')']);
			//lisha_generer_msgbox(lisha_id+'_child',lis_lib[117],lis_lib[116].replace(/\$1/g,ajax_return.MESSAGE),'error','msg',prompt_btn,false,false);

		}
		else
		{
			document.getElementById(lisha_id+'_child_valide').style.display = 'block';
		}
	}
}
/**==================================================================*/