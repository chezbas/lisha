	function lisha_msgbox_hover(id,lisha_id)
	{
		document.getElementById('btn_'+lisha_id+'_ok_g'+id).style.backgroundPosition = '0 -63px';
		document.getElementById('btn_'+lisha_id+'_ok_m'+id).style.backgroundPosition = '0 -105px';
		document.getElementById('btn_'+lisha_id+'_ok_d'+id).style.backgroundPosition = '0 -84px';
	}

	function lisha_msgbox_out(id,lisha_id)
	{
		document.getElementById('btn_'+lisha_id+'_ok_g'+id).style.backgroundPosition = '0 0px';
		document.getElementById('btn_'+lisha_id+'_ok_m'+id).style.backgroundPosition = '0 -42px';
		document.getElementById('btn_'+lisha_id+'_ok_d'+id).style.backgroundPosition = '0 -21px';
	}

	/**
	 *
	 * @param titre
	 * @param contenu
	 * @param icone
	 * @param type confirm,prompt,password,wait,msg
	 * @anim_wait : false means hide wait animation div
	 * @return
	 */
	function lisha_generer_msgbox(lisha_id,titre,contenu,icone,type,aff_btn,empecher_fermeture,anim_wait)
	{
		var msgbox_html = null;
		var focus = null;

		if(typeof(anim_wait) == 'undefined')
		{
			anim_wait = false;
		}

		var theme = eval('lisha.'+lisha_id+'.theme');
		if(!anim_wait)
		{
			document.getElementById('lis__'+theme+'__wait_'+lisha_id+'__').style.backgroundImage = 'none';
		}
		else
		{
			document.getElementById('lis__'+theme+'__wait_'+lisha_id+'__').style.backgroundImage = 'url(./'+eval('lisha.'+lisha_id+'.dir_obj')+'/images/object/msgbox/wait.gif)';
		}

		if(typeof(aff_btn) == 'undefined')
		{
			aff_btn = false;
		}
		if(typeof(empecher_fermeture) == 'undefined')
		{
			empecher_fermeture = false;
		}

		contenu = '<span id="lisha_'+lisha_id+'_msgbox_contenu_texte">'+contenu+'</span>';
		msgbox_html = '<div class="lisha_msgbox_hg">';
		msgbox_html += '<div class="lisha_msgbox_hd">';
		msgbox_html += '<div class="lisha_msgbox_hm">';
		msgbox_html += '<div class="lisha_msgbox_c">';
		if(empecher_fermeture == false)
		{
			msgbox_html += '<div class="lisha_msgbox_btn_quitter" onclick="lisha_cover_with_filter(\''+lisha_id+'\');"></div>';
		}
		msgbox_html += '<div class="lisha_msgbox_titre">'+titre+'</div>';
		if(icone == '')
		{
			msgbox_html += '<div class="lisha_msgbox_message">';
		}
		else
		{
			msgbox_html += '<div class="lisha_msgbox_icon_'+icone+' lisha_msgbox_message">';
		}

		switch (type)
		{
			case 'confirm':
				//aff_btn = true;
				break;
			case 'prompt':
				contenu += '<div class="lisha_msgbox_prompt"><input type="text" id="lisha_'+lisha_id+'_msgbox_prompt_value" onkeypress="lisha_msgbox_keypress(event,\''+lisha_id+'\');"/></div>';
				focus = 'lisha_'+lisha_id+'_msgbox_prompt_value';
				break;
			case 'password':
				contenu += '<div class="lisha_msgbox_prompt"><input type="password" id="lisha_'+lisha_id+'_msgbox_prompt_value"/></div>';
				focus = 'lisha_'+lisha_id+'_msgbox_prompt_value';
				break;
			case 'wait':
				contenu += '<div class="lisha_msgbox_wait"></div>';
				break;
			case 'msg':
				break;
			default:
				break;
		}
		msgbox_html += '<div class="lisha_msgbox_contenu" id="lisha_'+lisha_id+'_msgbox_contenu">'+contenu+'</div>';

		if(aff_btn)
		{
			msgbox_html += '<div style="text-align:center;">';
			msgbox_html += '<table class="lisha_msgbox_tab_cont_btn">';
			msgbox_html += '<tr>';
			// Mise en place des boutons
			for ( var i = 0; i < aff_btn[0].length; i++)
			{
				msgbox_html += '<td>';
				msgbox_html += '<table class="lisha_msgbox_tab_btn">';
				msgbox_html += '<tr tabindex=10000 id="lisha_'+lisha_id+'_msgbox_btn'+i+'" onmouseover="lisha_msgbox_hover('+i+',\''+lisha_id+'\');" onmouseout="lisha_msgbox_out('+i+',\''+lisha_id+'\');" onMouseDown="return false;" onkeypress="lisha_msgbox_keypress(event,\''+lisha_id+'\');" onclick="javascript:'+aff_btn[1][i]+'">'; //SRX_ADD_ONKEYDOWN_EVENT
				msgbox_html += '<td id="btn_'+lisha_id+'_ok_g'+i+'" class="btn_ok_g"></td>';
				msgbox_html += '<td id="btn_'+lisha_id+'_ok_m'+i+'" class="btn_ok_m">'+aff_btn[0][i]+'</td>';
				msgbox_html += '<td id="btn_'+lisha_id+'_ok_d'+i+'" class="btn_ok_d"></td>';
				msgbox_html += '</tr>';
				msgbox_html += '</table>';
				msgbox_html += '</td>';
			}
			msgbox_html += '</tr>';
			msgbox_html += '</table>';
			msgbox_html += '</div>	';
		}
		msgbox_html += '</div>';
		msgbox_html += '</div>';
		msgbox_html += '</div>';
		msgbox_html += '</div>';
		msgbox_html += '</div>';
		msgbox_html += '<div class="lisha_msgbox_content_bg">';
		msgbox_html += '<div class="lisha_msgbox_content_bd">';
		msgbox_html += '<div class="lisha_msgbox_content_bm"></div>';
		msgbox_html += '</div>';
		msgbox_html += '</div>';

		// Affichage du fond
		//document.getElementById('lis_msgbox_background_'+lisha_id).style.display = 'block';

		// Creation de la messagebox
		document.getElementById('lis_msgbox_conteneur_'+lisha_id).innerHTML = msgbox_html;

		// Affichage de la messagebox
		document.getElementById('lis_msgbox_conteneur_'+lisha_id).style.display = '';

		// Focus
		if(focus != null)
		{
			document.getElementById(focus).focus();
		}

		if(type == 'msg') // Only ok button
		{
			var focus = 'lisha_'+lisha_id+'_msgbox_btn0';
			document.getElementById(focus).focus();
		}

		if(type == 'wait')
		{
			document.getElementById('lisha_'+lisha_id+'_msgbox_contenu').style.margin = '0';
		}
	}

	function lisha_close_msgbox(lisha_id)
	{

		// Suppression de la messagebox
		document.getElementById('lis_msgbox_conteneur_'+lisha_id).innerHTML = '';

		// Masquage de la messagebox
		document.getElementById('lis_msgbox_conteneur_'+lisha_id).style.display = 'none';

		// Masquage du fond
		//document.getElementById('lis_msgbox_background_'+lisha_id).style.display = 'none';

	}


	function lisha_changer_message_msgbox(message,lisha_id)
	{
		document.getElementById('lisha_'+lisha_id+'_msgbox_contenu_texte').innerHTML = message;
	}

	function lisha_msgbox_keypress(code_touche,lisha_id)
	{
		if(code_touche.keyCode == 13)
		{
			// Enter key, valid the msgbox
			eval(document.getElementById('lisha_'+lisha_id+'_msgbox_btn0').onclick+'  onclick();');
		}
		else if(code_touche.keyCode == 27)
		{
			// Esc key, close the msgbox
			eval(document.getElementById('lisha_'+lisha_id+'_msgbox_btn1').onclick+'  onclick();');
		}
		else
		{
			if(code_touche.keyCode == 27) eval(document.getElementById('lisha_'+lisha_id+'_msgbox_btn1').onclick+'onclick();return false;');
		}
	}


	function lisha_collapse_menu(id)
	{
		if(document.getElementById(id).style.display == 'block')
		{
			document.getElementById(id).style.display = 'none';
		}
		else
		{
			document.getElementById(id).style.display = 'block';
		}
	}
