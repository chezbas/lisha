function test_1(internal_id, row_id, mode)
{
	alert('Custom function test_1 called with : '+internal_id+row_id+mode);
}

function custom_new_item_created(internal_id, row_id)
{
//	alert('Custom function test_1 called with : '+internal_id+' id: '+row_id);
//	alert('Id ofLast id created is : '+row_id);
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	

	configuration['page'] = 'ajax/read.php';
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 5000; // 5 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText

	configuration['param'] = 'ssid='+ssid+'&id='+internal_id+'&attribute='+encodeURIComponent('__total_language_nodes');

	configuration['fonction_a_executer_reponse'] = 'read_done';
	//configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";
	//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';


	// Do the call
	ajax_call(configuration);
	//==================================================================

}

function read_done(retour)
{
	document.getElementById('total_nodes').innerHTML = retour;
}

function custom_deletion_item(internal_id, row_id, total)
{
	//alert('Number of rows deleted : '+ total);
	custom_new_item_created(internal_id, row_id);
}