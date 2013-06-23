var version_soft = '';
		
function inserer_texte() 
{
	var resultat = 'password.php?ID='+document.getElementById('id_password').value+'">'+document.getElementById('lbl_password').value;
	tinyMCEPopup.execCommand('mceInsertContent', false,'<a href="'+resultat+'</a>');
	tinyMCEPopup.close();
}

function update_url() 
{
	iKnowDialog.update('password.php?ID='+document.getElementById('id_password').value);
	tinyMCEPopup.close();
}

function ctrl_id_password(e,id,reponse)
{
	if(id != '')
	{
		if(e.which != 27)
		{
			if(typeof(reponse) == 'undefined')
			{
				if(!Numerique(id))
				{
					// Identifiant incorrect
					document.getElementById('img_verif').style.display = "block";
					document.getElementById('img_verif').src = "error.png";
				}
				else
				{
					document.getElementById('img_verif').src = "load.gif";
					/**==================================================================
					 * RECUPERATION CONTENU ETAPE POUR EDITION
					 *===================================================================*/	
					var configuration = new Array();	
					configuration['page'] = '../../../../../../ajax/ifiche/actions_etapes.php';
					configuration['delai_tentative'] = 5000;
					configuration['max_tentative'] = 4;
					configuration['type_retour'] = false;		// ReponseText
					configuration['param'] = 'ssid='+ssid+'&action=28&id='+id;
					configuration['fonction_a_executer_reponse'] = 'ctrl_id_password';
					configuration['param_fonction_a_executer_reponse'] = false+','+id;
					
					ajax_call(configuration);
					/**==================================================================*/
				}
			}
			else
			{
				reponse_json = get_json(reponse);
				if(decodeURIComponent(reponse_json.parent.erreur) == 'false')
				{
					// pas d'erreur
					document.getElementById('img_verif').style.display = "block";
					document.getElementById('img_verif').src = "ok.png";
					document.getElementById('txt_niveau').innerHTML = decodeURIComponent(reponse_json.parent.niveau);
					document.getElementById('txt_niveau').style.display = 'block';
					document.getElementById('insert').className = '';
					document.getElementById('insert').type = 'submit';  
				}
				else
				{
					// Erreur
					document.getElementById('img_verif').style.display = "block";
					document.getElementById('img_verif').src = "error.png";
					document.getElementById('txt_niveau').innerHTML = decodeURIComponent(reponse_json.parent.niveau);
					document.getElementById('txt_niveau').style.display = 'block';
					document.getElementById('insert').className = 'disable';
					document.getElementById('insert').type = 'button';  
				}
			}
		}
	}
	else
	{
		document.getElementById('img_verif').style.display = "none";
		document.getElementById('txt_niveau').style.display = 'none';
		document.getElementById('insert').className = 'disable';
		document.getElementById('insert').type = 'button';  
	}
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
		if(caracteres_valide.indexOf(caractere) == -1) 	// Le caractère n'a pas été trouvé dans la liste
		{
			numerique = false;
		}
	}
	return numerique;
}