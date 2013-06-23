var __IFICHE__ = '__IFICHE__';
var __ICODE__ = '__ICODE__';
var __EXT__ = '__EXT__';


function select_object(p_object)
{
	switch (p_object) 
	{
		case __IFICHE__:
			document.getElementById('rd_ifiche').checked = true;
			break;
		case __ICODE__:
			document.getElementById('rd_icode').checked = true;
			break;
	}
}

function set_tab_level(p_level)
{
	p_split = p_level.split('_');
	
	tabbar_link.setTabActive(p_split[0]);
	
	if(p_split[0] == 1)
	{
		// Header
		tabbar_header.setTabActive('1_'+p_split[1]);
	}
	else
	{
		// Step
		tabbar_step.setTabActive('2_'+p_split[1]);
	}
	
	
	switch(p_split.length) 
	{
		case 2:
			break;
		case 3:
			document.getElementById('dest_by_step').value = p_split[2];
			document.getElementById('dest_line').value = p_split[2];
			
			break;

	}
}


function update_url(ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		
		/**==================================================================
		 * Ajax init
		 ====================================================================*/	
		var conf = new Array();	

		conf['page'] = "../../../../../../ajax/ifiche/actions_etapes.php";
		conf['delai_tentative'] = 5000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		
		conf['param'] = 'action=17&ssid='+ssid+'&iobject='+iobject_selected+'&id_dst='+document.getElementById('lst_vimofy_iobjet_id').value+'&v_dst='+document.getElementById('lst_vimofy_iobjet_version').value;
		conf['fonction_a_executer_reponse'] = 'update_url';
		
		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		try
		{
			var reponse_json = get_json(ajax_return);
			iKnowDialog.update(decodeURIComponent(reponse_json.parent.url)+type_valo_selected()+tab_selected());
			tinyMCEPopup.close();
			
			//tinyMCEPopup.execCommand('mceInsertContent', false,'<a href="'+decodeURIComponent(reponse_json.parent.url)+type_valo_selected()+tab_selected()+'">'+document.getElementById('lib_link').value+'</a>');
		} 
		catch(e) 
		{
			alert('err 4.4 \n'+e.message);
		}
	}
}