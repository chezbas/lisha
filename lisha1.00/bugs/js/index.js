function switch_lisha(mode) 
{ 	
	if (mode == 1)
	{
		document.getElementById('lisha_trans').style.display = 'none';
		document.getElementById('icon_checked').style.display = 'none';

		document.getElementById('lisha_check').style.display = 'block';
		document.getElementById('icon_transaction').style.display = 'block';
	}
	else
	{
		document.getElementById('lisha_trans').style.display = 'block';
		document.getElementById('icon_checked').style.display = 'block';

		document.getElementById('lisha_check').style.display = 'none';
		document.getElementById('icon_transaction').style.display = 'none';

	}
}

function rebuild_account()
{
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	

	configuration['page'] = 'ajax/account_total.php';

	configuration['delai_tentative'] = 10000; // 10 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	configuration['param'] = 'ssid='+ssid;
	configuration['fonction_a_executer_reponse'] = 'read_done';
	//configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"','"+row_id+"','"+refresh+"'";

	// Do the call
	ajax_call(configuration);
	//==================================================================
}

function read_done(retour)
{
	retour = JSON.parse(retour);
	document.getElementById('amount_valider').innerHTML = retour.A;
	document.getElementById('amount_engager').innerHTML = retour.B;
	document.getElementById('amount_prevu').innerHTML = retour.C;
}