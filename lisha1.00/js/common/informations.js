	var viewer_aide = '';

	//==================================================================
	// if global = 'X', then id_libelle come from common iknow text
	// if text exists ans global not set, then put raw text
	// if only id_libelle is define, then replace by local iobject text
	// if chaine is not null that means &1 string in text will be replace by chaine
	// if extended_help not null then means CTRL+F11 available
	//==================================================================
	function set_text_help(id_libelle,texte,global,chaine,extended_help)
	{
		if(typeof(global) == 'undefined' || global == '')
		{
			if(typeof(texte) == 'undefined' || texte == '')
			{
				var libelle_temp = decodeURIComponent(libelle[id_libelle]);                    
			}
			else
			{
				var libelle_temp = texte;
			}
		}
		else
		{
			var libelle_temp = decodeURIComponent(libelle_common[id_libelle]);
		}

		if(typeof(chaine) ==  'undefined' || chaine == '') //SIBY
		{
			// No replace
		}
		else
		{
			libelle_temp = libelle_temp.replace(/&1/g,chaine); // Replace all " by &quot;
		}
		/**==================================================================
		 * Display is extended help is available
		 ====================================================================*/	
		if(extended_help != 'undefined' && extended_help)
		{
			libelle_temp = libelle_temp + ' (' + decodeURIComponent(libelle_common[205]) + ')';
		}
		/**==================================================================*/			

		/**==================================================================
		 * Display help text in footer
		 ====================================================================*/	
		iknow_panel_set_sous_titre(libelle_temp);
		/**==================================================================*/			
	}
	//==================================================================

	function unset_text_help()
	{
		/**==================================================================
		 * MASQUE LE TEXTE D'AIDE DE LA BARRE D'INFORMATIONS
		 ====================================================================*/		
		iknow_panel_set_sous_titre('&nbsp;');
		/**==================================================================*/	
	}

	/**
	 * 
	 * @param id_div	Identifiant de la page dans la documentation (appelé lors d'un CTRL+F1)
	 * @return
	 */
	function ikdoc(id_div)
	{
		if(typeof(id_div) == 'undefined')
		{
			viewer_aide = '';
		}
		else
		{
			viewer_aide = id_div;
		}
	}

	function addslashes(ch) {
		ch = ch.replace(/\\/g,"\\\\");
		ch = ch.replace(/\'/g,"\\'");
		ch = ch.replace(/\"/g,"\\\"");
		return ch;
	}

	//==================================================================
	// id_doc id page of documentation linked up, if none, put false
	// id_help : message to display
	// texte : hard codede messgae
	// global : if X means that id_help come from iknow space text
	// Eg to use global iknow text over(false,86,'-','X')
	//==================================================================
	function over(id_doc,id_help,texte,global,chaine)
	{
		ikdoc(id_doc);// Add call of doc page
		set_text_help(id_help,texte,global,chaine,id_doc);
	}

	function get_lib(id)
	{
		return decodeURIComponent(libelle[id]);
	}

	var g_blink_error_msg = null;
	function blink_error_msg_start(id_object)
	{
		if(typeof(id_object) == 'undefined') id_object = 'iknow_ctrl_title';
		if(g_blink_error_msg == null)
		{
			g_blink_error_msg_timer_increment = 0;
			g_blink_error_msg = new iknow_timer(300,'blink_error_msg(\''+id_object+'\')');
			g_blink_error_msg.start(id_object);
			document.getElementById(id_object).style.fontWeight = 'bold';
		}
	}

	function blink_error_msg(id_object)
	{
		if(g_blink_error_msg_timer_increment == 18)
		{
			document.getElementById(id_object).style.color = 'red';
			/*document.getElementById(id_object).style.fontWeight = 'normal';*/
			g_blink_error_msg.stop();
			g_blink_error_msg = null;
			return true;
		}

		g_blink_error_msg_timer_increment += 1;

		if(g_blink_error_msg_timer_increment & 1)
		{
			document.getElementById(id_object).style.color = 'red';
		}
		else
		{
			document.getElementById(id_object).style.color = 'black';
		}
	}

	function iknow_toggle_control()
	{
		if(document.getElementById('iknow_ctrl_arrow') != null)
		{
			if(document.getElementById('iknow_ctrl_container').style.display != 'block')
			{
				// Afficher
				document.getElementById('iknow_ctrl_arrow').style.backgroundPosition = '0 -75px';
			}
			else
			{
				// Masquer
				document.getElementById('iknow_ctrl_arrow').style.backgroundPosition = '0 -60px';
			}
		}	
		iknow_toggle_el('iknow_ctrl_container','iknow_ctrl_internal_container');
	}