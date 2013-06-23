function session_management(p_file_presence,p_ssid,p_dir_object,p_file_presence_param) 
{ 
	this.file_presence = p_file_presence;
	this.dir_object = p_dir_object;
	this.ssid = p_ssid;
	this.file_presence_param = p_file_presence_param;
	this.settimeout_session = null;
	this.time_maj_titre = false;
	this.titre = '';
	this.titre_state;
	this.message_alert_affiche = false;
	this.time_maj_icon_timer = null;
	this.start_maj_icon_timer = false;
	this.icon_timer_red = false;
	
	this.time_maj_icon_cookie = null;
	this.start_maj_icon_cookie = false;
	this.icon_cookie_red = false;
	
	this.counter_session_update = function(ajax_return)
	{
		if(typeof(ajax_return) == "undefined")
		{	
			//==================================================================
			// Ajax configuration
			//==================================================================
			var configuration = new Array();	
			
			if(this.file_presence != '')
			{
				configuration['page'] = this.file_presence;
			}
			else
			{
				configuration['page'] = this.dir_object+'maj_presence_session.php';
			}
			configuration['delai_tentative'] = 5000;		// 5 secondes
			configuration['max_tentative'] = 4;
			configuration['type_retour'] = false;			// ResponseText	
			if(this.file_presence_param == '')
			{
				configuration['param'] = "ssid="+this.ssid;
			}
			else
			{
				configuration['param'] = this.file_presence_param;
			}
			configuration['fonction_a_executer_reponse'] = 'footer.obj_session.counter_session_update';

			ajax_call(configuration);
			//==================================================================
		}
		else
		{
			if(ajax_return != '')
			{
				// Ctrl free cookie
				this.ctrl_free_cookie('free_cookie');
				
				// XML to JSON
				var reponse_json = get_json(ajax_return); 
				this.compteur_end_visu(reponse_json.parent.date,reponse_json.parent.time);
				
				if(typeof(reponse_json.parent.erreur) != "undefined")
				{
					//==================================================================
					// Check if session lost : erreur = true means session lost
					//==================================================================
					if(reponse_json.parent.erreur)
					{
						// No more ssid in lock table
						// Popup to lock screen wih error message
						clearInterval(this.settimeout_session);			// Stop Ajax timer call
				    	generer_msgbox(decodeURIComponent(libelle_common[17]),decodeURIComponent(libelle_common[18]),'erreur','msg',false,true);
					}
				}

			}
		}
	};
	
	
	
	this.dateDiff = function(firstdate,firsttime,seconddate,secondtime) 
	{
		date1 = new Date();
		date2 = new Date();
		diff  = new Date();
		
		date1temp = new Date(firstdate + " " + firsttime);
		date1.setTime(date1temp.getTime());
		
		date2temp = new Date(seconddate + " " + secondtime);
		date2.setTime(date2temp.getTime());
		
		// sets difference date to difference of first date and second date
		
		diff.setTime(Math.abs(date1.getTime() - date2.getTime()));
		
		timediff = diff.getTime();
		
		weeks = Math.floor(timediff / (1000 * 60 * 60 * 24 * 7));
		timediff -= weeks * (1000 * 60 * 60 * 24 * 7);
		
		days = Math.floor(timediff / (1000 * 60 * 60 * 24)); 
		timediff -= days * (1000 * 60 * 60 * 24);
		
		hours = Math.floor(timediff / (1000 * 60 * 60)); 
		timediff -= hours * (1000 * 60 * 60);
		
		mins = Math.floor(timediff / (1000 * 60)); 
		timediff -= mins * (1000 * 60);
		
		secs = Math.floor(timediff / 1000); 
		timediff -= secs * 1000;

		
		if(hours == 0 && mins <= 10 && this.message_alert_affiche == false)
		{
			this.message_alert_affiche = true;
			
			texte_action = decodeURIComponent(libelle_common[2]).replace('$x', mins);
			
			aff_btn = new Array(['Ok'],["close_msgbox();"]);
	    	generer_msgbox('',texte_action,'question','msg',aff_btn);
		}
			
			
		if(hours < 10)
		{
			hours = '0'+hours;
		}

		if(mins < 10)
		{
			mins = '0'+mins;
		}

		if(hours == 0 && mins <= 10)
		{
			if(this.start_maj_icon_timer == false && mins != 0)
			{
				this.start_maj_icon_timer = true;
				this.time_maj_icon_timer = setInterval("footer.obj_session.blink_icon_timer();",1500);
				
			}
			
			this.maj_titre_lifetime(hours,mins);
			
			if(mins == 0)
			{
				// Dépassement du temps imparti. L'objet est HS.
				var newImage = "url("+this.dir_object+"chronometer_red.png)";
				document.getElementById('lifetime').style.backgroundImage = newImage;
				clearInterval(this.settimeout_session);			// On arrête d'envoyer des tops de présence.
		    	generer_msgbox('',decodeURIComponent(libelle_common[5]),'erreur','msg',false,true);
		    	clearTimeout(this.time_maj_titre);
		    	document.title = decodeURIComponent(libelle_common[7]);
			}
		}
		else
		{
			document.getElementById('lifetime').style.backgroundColor = "";
		}

		document.getElementById('lifetime').innerHTML = hours + ":" + mins;
	};

	this.blink_icon_timer = function()
	{
		if(this.icon_timer_red == false)
		{
			var newImage = "url("+this.dir_object+"chronometer_red.png)";
			this.icon_timer_red = true;
		}
		else
		{
			var newImage = "url("+this.dir_object+"chronometer.png)";
			this.icon_timer_red = false;
		}
		document.getElementById('lifetime').style.backgroundImage = newImage;
	};
	
	this.compteur_end_visu = function(date,time)
	{
		this.dateDiff(date,time,end_visu_date,end_visu_time);	
	};
	
	
	/**
	 * Executé lorsque le temps imparti pour modifier la fiche est critique.
	 * Change le titre pour indiquer de sauvegarder l'objet.
	 * NR_IKNOW_9_
	 */
	this.maj_titre_lifetime = function(hours,mins)
	{
		if(this.time_maj_titre == false)
		{
			this.titre = document.title;
			this.time_maj_titre = setInterval("footer.obj_session.maj_titre_lifetime();",4500);
			this.titre_state = 1;
		}
		else
		{
			if(this.titre_state == 1)
			{
				document.title = this.titre;
				this.titre_state = 2;
			}
			else
			{
				document.title = decodeURIComponent(libelle_common[6]);
				this.titre_state = 1;
			}
		}
	};
	
	/**==================================================================
	 * COOKIE MANAGEMENT
	 ====================================================================*/	
	this.get_free_cookie = function()
	{
		return this.get_max_cookie_browser() - this.get_cookie();
	};

	this.ctrl_free_cookie = function(div,msg_alert)
	{
		
		var free_cookie = this.get_free_cookie();
		if(free_cookie <= 5)
		{
			if(typeof(msg_alert) != 'undefined')
			{
				aff_btn = new Array(['Ok'],["close_msgbox();"]);
		        generer_msgbox(decodeURIComponent(libelle_common[4]),decodeURIComponent(libelle_common[3]).replace('$x', free_cookie),'warning','msg',aff_btn);
			}
			
			document.getElementById(div).innerHTML = '<blink class="no_free_cookie">'+decodeURIComponent(libelle_common[3]).replace('$x', free_cookie)+'</blink>';
			
			if(this.start_maj_icon_cookie == false)
			{
				this.start_maj_icon_cookie = true;
				this.time_maj_icon_cookie = setInterval("footer.obj_session.blink_icon_cookie();",1500);
				
			}
		}
		else
		{
			document.getElementById(div).innerHTML = decodeURIComponent(libelle_common[3]).replace('$x', free_cookie);
		}
	};
	
	
	this.blink_icon_cookie = function()
	{
		if(this.icon_cookie_red == false)
		{
			var newImage = "url("+this.dir_object+"cookie_red.png)";
			this.icon_cookie_red = true;
		}
		else
		{
			var newImage = "url("+this.dir_object+"cookie.png)";
			this.icon_cookie_red = false;
		}
		document.getElementById('free_cookie').style.backgroundImage = newImage;
	};
	
	
	/**
	 * Récupère le nombre de cookie actif pour le domaine en cours.
	 * @return Nombre de cookie actif pour le domaine
	 */
	this.get_cookie = function()
	{
		var ca = document.cookie.split(';');
		return ca.length;
	};

	/**
	 * Retourne le nombre maximum de cookie par domaine par rapport au navigateur
	 */
	this.get_max_cookie_browser = function()
	{
		if(navigator.userAgent.search('Safari') != "-1")
		{
			if(navigator.userAgent.search('Mac') != "-1")
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
			if(navigator.userAgent.search('Firefox') != "-1")
			{
				if(navigator.userAgent.search('Mac') != "-1")
				{
					// Firefox Mac
					return 50;
				}
				else
				{
					// Firefox Windows
					return 50;
				}
			}
			else
			{
				if(navigator.userAgent.search('MSIE') != "-1")
				{
					// Internet Explorer
					return 50;
				}
				else
				{
					if(navigator.userAgent.search('Opera') != "-1")
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
	};
	/**==================================================================*/	
	
	this.strstr = function(haystack,needle,bool)
	{
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
	};
}