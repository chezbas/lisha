var message_alert_affiche;
message_alert_affiche = false;
//NR_IKNOW_9_
function dateDiff(firstdate,firsttime,seconddate,secondtime) 
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


	if(hours == 0 && mins <= 10 && message_alert_affiche == false)
	{
		message_alert_affiche = true;

		if(application == 1 || application == 3)
		{
			texte_action = decodeURIComponent(libelle_common[1]).replace('$x', mins);
		}
		else
		{
			texte_action = decodeURIComponent(libelle_common[2]).replace('$x', mins);
		}

		aff_btn = new Array([get_lib(182)],["close_msgbox();"]);
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
		document.getElementById('lifetime').style.backgroundColor = "red";
		maj_titre_lifetime(hours,mins);
		if(mins == 0)
		{
			// Dépassement du temps imparti. L'objet est HS.
			clearInterval(set_interval_presence);			// On arrête d'envoyer des tops de présence.
			generer_msgbox('',decodeURIComponent(libelle_common[5]),'erreur','msg',false,true);
			clearTimeout(time_maj_titre);
			document.title = decodeURIComponent(libelle_common[7]);
		}
	}
	else
	{
		document.getElementById('lifetime').style.backgroundColor = "";
	}

	document.getElementById('lifetime').innerHTML = hours + ":" + mins;
}

function compteur_end_visu(date,time)
{
	dateDiff(date,time,end_visu_date,end_visu_time);	
}

/**
 * Executé lorsque le temps imparti pour modifier la fiche est critique.
 * Change le titre pour indiquer de sauvegarder l'objet.
 * NR_IKNOW_9_
 */
var time_maj_titre = false;
var titre = '';
var titre_state;
function maj_titre_lifetime(hours,mins)
{
	if(time_maj_titre == false)
	{
		titre = document.title;
		time_maj_titre = setInterval("maj_titre_lifetime();",2000);
		titre_state = 1;
	}
	else
	{
		if(titre_state == 1)
		{
			document.title = titre;
			titre_state = 2;
		}
		else
		{
			document.title = decodeURIComponent(libelle_common[6]);
			titre_state = 1;
		}
	}
}