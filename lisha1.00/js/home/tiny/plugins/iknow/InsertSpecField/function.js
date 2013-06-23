var version_soft = '';

function inserer_texte() 
{
	var resultat = document.getElementById('forme_champ').value;
	iKnowDialog.insert('<span class="BBField">'+resultat+'</span>');
}



//Cette fonction vérifie que la chaine est bien numérique
function Numerique(chaine)
{
	var caracteres_valide = "0123456789.,";
	var numerique = true;
	var caractere;
	
	for(i = 0; i < chaine.length && numerique == true; i++)
	{
		caractere = chaine.charAt(i); 						// on récupère le caractère en position i
		if(caracteres_valide.indexOf(caractere) == -1) 		// Le caractère n'a pas été trouvé dans la liste
		{
			numerique = false;
		}
	}
	return numerique;

}


function get_forme_champ(id)
{
	switch(id)
	{
		case '1':
			return '__TOTAL_ETAPE__';
			break;
		case '2':
			return '__NUM_ETAPE__';
			break;
		case '3':
			return '__TOTAL_VARIN__';
			break;
		case '4':
			return '__TOTAL_VAROUT__';
			break;
		case '5':
			return '__TOTAL_VAROUT_ETAPE__';
			break;
		case '6':
			return '__DATE(FORMAT)__';
			break;
		case '7':
			return '__VERSION_FICHE__';
			break;
		case '8':
			return '__ID_FICHE__';
			break;
		case '9':
			return '__TOTAL_TAG__';
			break;
			
			
	}

}	


function set_forme_champ(id)
{
	document.getElementById('forme_champ').value = get_forme_champ(id);
	document.getElementById('val_possible').innerHTML = get_val_possible(id);
}


function render_field(ajax_return)
{
	if(typeof(ajax_return) == 'undefined')
	{
		/**==================================================================
		 * Ajax init 
		 ====================================================================*/	
		var conf = new Array();	
		
		conf['page'] = "calcul.php";
		conf['delai_tentative'] = 2000;
		conf['max_tentative'] = 4;
		conf['type_retour'] = false;		// ReponseText
		conf['param'] = "ssid="+ssid+'&forme_champ='+encodeURIComponent(document.getElementById('forme_champ').value);
		conf['fonction_a_executer_reponse'] = 'render_field';
		
		ajax_call(conf);
		/**==================================================================*/
	}
	else
	{
		try 
		{
			document.getElementById('rendu').value = ajax_return;
		} 
		catch(e) 
		{
			alert('err 1 \n'+e.message+'\n');
		}
	}
}