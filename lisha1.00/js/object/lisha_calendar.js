/**==================================================================
 * Insert date on head input column field
 * @lisha_id    : Lisha identifier
 * @column      : Column identifier
 * @p_year      : Recover or input year
 * @p_month     : Recover or input month
 * @p_day       : Recover or input day
 ====================================================================*/
function lisha_load_date(lisha_id,column,p_year,p_month,p_day,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		// Display load gif
		document.getElementById('calendar_load_'+lisha_id).style.display = 'block';

		//lisha_display_wait(lisha_id);

		if(typeof(p_year) == 'undefined' || p_year == null)
		{
			p_year = document.getElementById('lisha_cal_year_'+lisha_id).value;
		}

		if(typeof(p_month) == 'undefined' || p_month == null)
		{
			p_month = document.getElementById('lisha_cal_month_'+lisha_id).value;
		}

		if(typeof(p_day) == 'undefined' || p_day == null)
		{
			p_day = document.getElementById('lisha_cal_day_'+lisha_id).value;
		}

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = [];

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 10000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;														// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=19&input=&column='+column+'&year='+encodeURIComponent(p_year)+'&month='+encodeURIComponent(p_month)+'&day='+encodeURIComponent(p_day); // MySQL hard coded date format TODO
		conf['fonction_a_executer_reponse'] = 'lisha_load_date';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+column+"','"+p_year+"','"+p_month+"','"+p_day+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try 
		{
			// Set the content to the calendar
			lisha_set_innerHTML('lis_column_calendar_'+lisha_id, ajax_return);

			// Hide load gif
			document.getElementById('calendar_load_'+lisha_id).style.display = 'none';
		}
		catch(e) 
		{
			lisha_display_error(lisha_id,e);
		}
	}
}
/**==================================================================*/


/**==================================================================
 * Insert date on head input column field
 * @lisha_id : Lisha identifier
 * @column : Column identifier
 * @p_day : back of calendar day
 ====================================================================*/
function lisha_insert_date(lisha_id,column,p_day,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		// Display load gif
		document.getElementById('calendar_load_'+lisha_id).style.display = 'block';

		/**==================================================================
		 * Get date values
		 ====================================================================*/	
		// Year
		var p_year = document.getElementById('lisha_cal_year_'+lisha_id).value;

		// Month
		if(document.getElementById('lisha_cal_month_'+lisha_id).value.length < 2)
		{
			var p_month = '0'+document.getElementById('lisha_cal_month_'+lisha_id).value;
		}
		else
		{
			var p_month = document.getElementById('lisha_cal_month_'+lisha_id).value;
		}

		// Day

		if(p_day.length < 2)
		{
			p_day = '0'+p_day;
		}
		else
		{
			p_day = p_day;
		}

		/**==================================================================*/

		//==================================================================
		// Setup Ajax configuration
		//==================================================================
		var conf = new Array();	

		conf['page'] = eval('lisha.'+lisha_id+'.dir_obj')+'/ajax/ajax_page.php';
		conf['delai_tentative'] = 10000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;														// ReponseText
		conf['param'] = 'lisha_id='+lisha_id+'&ssid='+eval('lisha.'+lisha_id+'.ssid')+'&action=21&mode=date&column='+column+'&value='+p_year+'-'+p_month+'-'+p_day; // Databse format YYYY-MM-DD
		conf['fonction_a_executer_reponse'] = 'lisha_insert_date';
		conf['param_fonction_a_executer_reponse'] = "'"+lisha_id+"','"+column+"','"+p_day+"'";

		ajax_call(conf);
		//==================================================================
	}
	else
	{
		var date_formated = ajax_return;
		// Close the calendar
		lisha_close_calendar(lisha_id);

		// Checkbox forced only if checkbox exists ( means update mode )
		if(document.getElementById('chk_edit_c'+column+'_'+lisha_id) != null)
		{
			document.getElementById('chk_edit_c'+column+'_'+lisha_id).checked=true;
		}

		// Set the value into the search input
		document.getElementById('th_input_'+column+'__'+lisha_id).value = date_formated;

		// Set the focus into the search bloc of the parent column
		document.getElementById('th_input_'+column+'__'+lisha_id).focus();

		// Launch search after return of calendar
		//lisha_define_filter(lisha_id,encodeURIComponent(document.getElementById('th_input_'+column+'__'+lisha_id).value),column,true);
	}
}
/**==================================================================*/


/**==================================================================
 * Close lisha calendar
 ====================================================================*/
function lisha_close_calendar(lisha_id)
{
	document.getElementById('lis_column_calendar_'+lisha_id).style.display = 'none';
	document.getElementById('lis_column_calendar_'+lisha_id).innerHTML = '';

	// Hide the wait div
	lisha_display_wait(lisha_id);
}
/**==================================================================*/