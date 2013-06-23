var iobject_selected = false;

function vimofy_tiny_insert_value(p_value)
{
	iKnowDialog.insert(p_value);
}

function clear_elements()
{
	document.getElementById('vimofy_iobjet_id').innerHTML = '';
	document.getElementById('vimofy_iobjet_version').innerHTML = '';
	document.getElementById('vim_link_param_tiny').innerHTML = '';
	document.getElementById('url_param').style.visibility = 'hidden';
	document.getElementById('tabbar').innerHTML = "";
	document.getElementById('div_options').style.display = 'none';
}

function enable_fields()
{
	document.getElementById('url_param').style.visibility = 'visible';
	document.getElementById('div_options').style.display = 'block';
}

function load_vim_id(p_type,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		iobject_selected = p_type;
		/**==================================================================
		 * Ajax init 
		 ====================================================================*/	
		var conf = new Array();	
		
		conf['page'] = "vim_ID.php";
		conf['delai_tentative'] = 2000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = "ssid="+ssid+'&p_type='+p_type;
		conf['fonction_a_executer_reponse'] = 'load_vim_id';
		conf['param_fonction_a_executer_reponse'] = p_type;
		
		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		try 
		{
			var reponse_json = get_json(ajax_return); 
			document.getElementById('vimofy_iobjet_id').innerHTML = decodeURIComponent(reponse_json.parent.header)+decodeURIComponent(reponse_json.parent.vimofy);
			addCss(decodeURIComponent(reponse_json.parent.css));
			eval(decodeURIComponent(reponse_json.parent.json));
			vimofy_open_lmod('vimofy_iobjet_id');
		} 
		catch(e) 
		{
			alert('err 1 \n'+e.message+'\n');
		}
	}
}

function select_ik_cartridge()
{
	
}

function select_ik_valmod(p_ik_valmod)
{
	if(p_ik_valmod != undefined)
	{
		switch (p_ik_valmod)
		{
			case 1:
				document.getElementById('chk_default').checked = true;
				document.getElementById('chk_neutre').checked = false;	
				break;
			case 2:
				document.getElementById('chk_neutre').checked = true;	
				document.getElementById('chk_default').checked = false;	
				break;
			case 3:
				document.getElementById('chk_default').checked = true;
				document.getElementById('chk_neutre').checked = true;	
				break;
		}
	}
}

function load_vim_version(p_type,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		/**==================================================================
		 * Ajax init
		 ====================================================================*/	
		var conf = new Array();	
		
		conf['page'] = "vim_Version.php";
		conf['delai_tentative'] = 2000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = "ssid="+ssid+'&p_type='+p_type+'&object_id='+document.getElementById('lst_vimofy_iobjet_id').value;
		conf['fonction_a_executer_reponse'] = 'load_vim_version';
		conf['param_fonction_a_executer_reponse'] = p_type;
		
		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		try
		{
			var reponse_json = get_json(ajax_return);
			document.getElementById('vimofy_iobjet_version').innerHTML = decodeURIComponent(reponse_json.parent.header)+decodeURIComponent(reponse_json.parent.vimofy);
			addCss(decodeURIComponent(reponse_json.parent.css));
			eval(decodeURIComponent(reponse_json.parent.json));
			vimofy_open_lmod('vimofy_iobjet_version');
		} 
		catch(e) 
		{
			alert('err 2 \n'+e.message);
		}
	}
}

function load_vim_parameters(p_type,ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		//==================================================================
		// * Ajax init
		//==================================================================
		var conf = new Array();	
		
		conf['page'] = "vim_Parameters.php";
		conf['delai_tentative'] = 2000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = "ssid="+ssid+'&p_type='+p_type+'&object_id='+document.getElementById('lst_vimofy_iobjet_id').value+'&object_version='+document.getElementById('lst_vimofy_iobjet_version').value+'&id_step='+id_step;
		conf['fonction_a_executer_reponse'] = 'load_vim_parameters';
		conf['param_fonction_a_executer_reponse'] = p_type;
		
		ajax_call(conf);
		//==================================================================
	}
	else
	{
		try
		{
			var reponse_json = get_json(ajax_return);
			document.getElementById('vim_link_param_tiny').innerHTML = decodeURIComponent(reponse_json.parent.vimofy);
			addCss(decodeURIComponent(reponse_json.parent.css));
			eval(decodeURIComponent(reponse_json.parent.json));
			gen_tab();
			gen_cartouche();
			//vimofy_open_lmod('vimofy_iobjet_version');
			document.getElementById('ikcalc_insert_button').disabled = false;
		} 
		catch(e) 
		{
			alert('err 3 \n'+e.message);
		}
	}
}

function insert_url(ajax_return)
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
		conf['fonction_a_executer_reponse'] = 'insert_url';
		
		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		try
		{
			var reponse_json = get_json(ajax_return);
			tinyMCEPopup.execCommand('mceInsertContent', false,'<a href="'+decodeURIComponent(reponse_json.parent.url)+type_valo_selected()+tab_selected()+'">'+document.getElementById('lib_link').value+'</a>');
			tinyMCEPopup.close();
		} 
		catch(e) 
		{
			alert('err 4.5 \n'+e.message);
		}
	}
}

function purger_url(ajax_return)
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
		conf['param'] = 'action=34&ssid='+ssid;
		conf['fonction_a_executer_reponse'] = 'purger_url';
		
		ajax_call(conf);
		/**==================================================================*/	
	}
	else
	{
		vimofy_refresh('vim_link_param_tiny');
	}
}

function tab_selected()
{
	if(iobject_selected == __IFICHE__)
	{
		var tabbar_link_selected = tabbar_link.getActiveTab();
		var tabbar_header_selected = tabbar_header.getActiveTab();
		var tabbar_step_selected = tabbar_step.getActiveTab();
		
		if(tabbar_link_selected == 1)
		{
			if(tabbar_header_selected == '1_1')
			{
				return '';
			}
			else
			{
				return '&tab-level='+tabbar_header_selected;
			}
		}
		else
		{
			if(tabbar_step_selected == '2_2')
			{
				return '&tab-level='+tabbar_step_selected+'_'+document.getElementById('dest_line').value;
			}
			else
			{
				return '&tab-level='+tabbar_step_selected+'#'+document.getElementById('dest_line').value;
			}
		}
	}
	else
	{
		var tabbar_link_selected = tabbar_link.getActiveTab();
		if(tabbar_link_selected != '2')
		{
			return '&tab-level='+tabbar_link_selected;
		}
		else
		{
			return '';
		}
	}
}

function type_valo_selected()
{
	if(document.getElementById('chk_neutre').checked && document.getElementById('chk_default').checked)
	{
		// Default & Neutre
		return "&IK_VALMOD=3";
	}
	
	if(document.getElementById('chk_default').checked)
	{
		// Default & Neutre
		return "&IK_VALMOD=1";
	}	
	
	if(document.getElementById('chk_neutre').checked)
	{
		// Default & Neutre
		return "&IK_VALMOD=2";
	}	
	
	return '';
}

function rapprocher_var(ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		/**==================================================================
		  * Ajax init
		 ====================================================================*/	
		var conf = new Array();	
		
		conf['page'] = '../../../../../../ajax/ifiche/actions_etapes.php';
		conf['delai_tentative'] = 5000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'ssid='+ssid+'&action=22&id_dst='+document.getElementById('lst_vimofy_iobjet_id').value+'&v_dst='+document.getElementById('lst_vimofy_iobjet_version').value+'&type_lien='+iobject_selected;
		conf['fonction_a_executer_reponse'] = 'rapprocher_var';
		
		ajax_call(conf);
		/**==================================================================*/	
			
	}
	else
	{
		//alert(ajax_return);
		vimofy_reset('vim_link_param_tiny');
		//loadTable('<?php echo $_GET['ssid']; ?>','lmod_param_url','../../../../../../objet_table','mode=0',null,false);
		//generer_url();	
	}
}

/*
// have a look to function_dynamic_js.php
function gen_tab() --> Move to function_dynamic_js.php
{
*/

function gen_cartouche(ajax_return)
{
	return false;
	if(typeof(ajax_return) == 'undefined')
	{
		//==================================================================
		//* Ajax init
		//==================================================================	
		var conf = new Array();	
		
		conf['page'] = 'cartouche.php';
		conf['delai_tentative'] = 5000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = 'ssid='+ssid+'&action=22&id_dst='+document.getElementById('lst_vimofy_iobjet_id').value+'&v_dst='+document.getElementById('lst_vimofy_iobjet_version').value+'&type_lien='+iobject_selected;
		conf['fonction_a_executer_reponse'] = 'gen_cartouche';
		
		ajax_call(conf);
		//==================================================================	
	}
	else
	{
		document.getElementById('cartouche').innerHTML = ajax_return;
		//loadTable('<?php echo $_GET['ssid']; ?>','lmod_param_url','../../../../../../objet_table','mode=0',null,false);
		//generer_url();	
	}
}


function toggle_div(div)
{
	if(document.getElementById(div).style.display == 'none')
	{
		// Afficher
		document.getElementById(div).style.display = '';
	}
	else
	{
		// Masquer
		document.getElementById(div).style.display = 'none';
	}
}
