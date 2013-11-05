function get_free_cookie()
{
	return get_max_cookie_browser() - get_cookie();
}


function ctrl_free_cookie(div,msg_alert)
{
	var free_cookie = get_free_cookie();
	if(free_cookie <= 5)
	{
		if(typeof(msg_alert) != 'undefined')
		{
			aff_btn = new Array([get_lib(182)],["close_msgbox();"]);
			generer_msgbox(decodeURIComponent(libelle_common[4]),decodeURIComponent(libelle_common[3]).replace('$x', free_cookie),'warning','msg',aff_btn);
		}
		document.getElementById(div).innerHTML = '<blink class="no_free_cookie">'+decodeURIComponent(libelle_common[3]).replace('$x', free_cookie)+'</blink>';
	}
	else
	{
		document.getElementById(div).innerHTML = decodeURIComponent(libelle_common[3]).replace('$x', free_cookie);
		//document.getElementById(div).innerHTML = decodeURIComponent(uriComponent)libelle_common[3].replace('$x', free_cookie);
	}
}
/**
 * Récupère le nombre de cookie actif pour le domaine en cours.
 * @return Nombre de cookie actif pour le domaine
 */
function get_cookie()
{
	var ca = document.cookie.split(';');
	return ca.length;
}

/**
 * Retourne le nombre maximum de cookie par domaine par rapport au navigateur
 * SEE Also get_max_cookie_browser in session_management.js
 */
function get_max_cookie_browser()
{
	if(strstr(navigator.userAgent,'Safari'))
	{
		if(strstr(navigator.userAgent,'Mac'))
		{
			// Safari Mac
			return 50;
		}
		else
		{
			// Safari Windows
			return 50;
		}
	}
	else
	{	
		if(strstr(navigator.userAgent,'Firefox'))
		{
			if(strstr(navigator.userAgent,'Mac'))
			{
				// Firefox Mac
				return 50;
			}
			else
			{
				// Safari Windows
				return 50;
			}
		}
		else
		{
			if(strstr(navigator.userAgent,'MSIE'))
			{
				// Internet Explorer
				return 50;
			}
			else
			{
				if(strstr(navigator.userAgent,'Opera'))
				{
					// Opera
					return 30;
				}
				else
				{
					// Other browser...
				}
			}
		}
	}
}

/**
 * Purge les cookies qui ne servent plus (objet HS sur le serveur)
 * Envoi la liste des cookies au serveur, le serveur analyse ensuite les nom des cookie (ssid) et vérifie si ils sont tjr actif sur le serveur.
 * 
 * Le serveur retourne la liste des cookies qui ne servent à rien (par rapport à un ssid obsolete)
 * Suppression des cookies 
 * @return
 */
function purger_cookie(reponse)
{
	if(typeof(reponse) == "undefined")
	{	
		/**==================================================================
		 * Mise à jour du champs last_update et récupération des messages en bdd
		 ====================================================================*/	
		var configuration = new Array();	

		configuration['page'] = "includes/common/maj_presence.php";
		configuration['delai_tentative'] = 5000;		// 5 secondes
		configuration['max_tentative'] = 4;
		configuration['type_retour'] = false;			// ResponseText		
		configuration['param'] = "ssid="+ssid+"&id="+ID_code+"&id_temp="+ID_temp+"&type_action="+application;
		configuration['fonction_a_executer_reponse'] = 'signal_presence';

		ajax_call(configuration);
		/**==================================================================*/		
	}
}

function setCookie(nom, valeur, expire, chemin, domaine, securite)
{
	document.cookie = nom + ' = ' + escape(valeur) + '  ' +
			  ((expire == undefined) ? '' : ('; expires = ' + expire.toGMTString())) +
			  ((chemin == undefined) ? '' : ('; path = ' + chemin)) +
			  ((domaine == undefined) ? '' : ('; domain = ' + domaine)) +
			  ((securite == true) ? '; secure' : '');
}


function delete_cookie(nom)
{
   var dtExpireDel = new Date();
   dtExpireDel.setTime(dtExpireDel.getTime() - 1);

   setCookie(nom, '', dtExpireDel, '/');
}

function strstr( haystack, needle, bool ) {

	var pos = 0;

	haystack += '';
	pos = haystack.indexOf( needle );
	if (pos == -1) {
		return false;
	} else{
		if( bool ){
			return haystack.substr( 0, pos );
		} else{
			return haystack.slice( pos );
		}
	}
}