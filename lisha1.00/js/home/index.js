//==================================================================
//Blink 
//==================================================================
function blink()
{
	var blinkme = document.getElementsByClassName("blink_off");
	var found = false;
	while(blinkme[0]) // Always item 0 ( pointer )
	{
		found = true;
		blinkme[0].setAttribute('class',"blink_on");
	}

	if(!found)
	{
		var blinkme = document.getElementsByClassName("blink_on");
		while(blinkme[0]) // Always item 0 ( pointer )
		{
			found = true;
			blinkme[0].setAttribute('class',"blink_off");
		}
	}

	return true; // Always active
}
//==================================================================


//==================================================================
//Bounce tools bar
//==================================================================
var pos_sinus = 0;
var bonce_sinus_move = [];
var bonce_sinus_move = {
		"0" : 0,
		"1" : 0.156,
		"2" : 0.309,
		"3" : 0.454,
		"4" : 0.588,
		"5" : 0.707,
		"6" : 0.809,
		"7" : 0.891,
		"8" : 0.951,
		"9" : 0.988,
		"10" : 1,
		"11" : 1,
		"12" : 1,
		"13" : 1,
		"14" : 1,
		"15" : 1,
		"16" : 1,
		"17" : 1,
		"18" : 0.988,
		"19" : 0.951,
		"20" : 0.891,
		"21" : 0.809,
		"22" : 0.707,
		"23" : 0.588,
		"24" : 0.454,
		"25" : 0.309,
		"26" : 0.156,
		"27" : 0
		};
function bounce_tool_bar(amplitude)
{
	if(MainTimer.c_loop >= 60)
	{
		if(bonce_sinus_move[pos_sinus] != undefined)
		{
			var hauteur = bonce_sinus_move[pos_sinus]*amplitude;
			document.getElementById('headdetails').style.height = hauteur+"px";
			pos_sinus = pos_sinus + 1;
			resize_details();
		}
		else
		{
			return false; // End animation
		}

	}
	return true;
}
//==================================================================

//==================================================================
//Reduce tools bar
//==================================================================
function reduce_tool_bar()
{
	if(MainTimer.c_loop >= 60)
	{

		if(document.getElementById('headdetails').offsetHeight > 1)
		{
			document.getElementById('slideh').style.display = "block";
			var hauteur = document.getElementById('headdetails').offsetHeight;
			hauteur = hauteur - 1;
			document.getElementById('headdetails').style.height = hauteur+"px";
			resize_details();
		}
		else
		{
			return false;
		}
	}
	return true;
}
//==================================================================

function active_expand_tools_bar()
{
	MainTimer.add_event(1,"expand_tool_bar()");
	document.getElementById('slideh').style.display = "none";
}

function expand_tool_bar()
{
	if(document.getElementById('headdetails').offsetHeight < 20)
	{
		var hauteur = document.getElementById('headdetails').offsetHeight;
		hauteur = hauteur + 1;
		document.getElementById('headdetails').style.height = hauteur+"px";
		resize_details();
		resize_details();
	}
	else
	{
		document.getElementById('slideh').style.display = "none";
		return false;
	}
	return true;	
}

var tree_flap = false; // Means open
var tree_position = 430;
function active_expand_navigation_tree()
{
	MainTimer.add_event(1,"expand_navigation_tree()");
}

function expand_navigation_tree()
{
	if(tree_flap)
	{
		// Open flap
		document.getElementById('ikdoc').style.display = "block";
		document.getElementById('gauche').style.width = "30%";
		//document.getElementById('slidev').style.left = tree_position+"px";
		//document.getElementById('details').style.left = tree_position+"px";		
		tree_flap = false;
		resize_details();
		resize_details();
		return false;
	}
	else
	{
		// Close flap
		document.getElementById('ikdoc').style.display = "none";
		document.getElementById('gauche').style.width = "0";
		//document.getElementById('details').style.left = "0px";		
		tree_flap = true;
		resize_details();
		resize_details();
		return false;
	}
	return true;	
}


function init_load(language_tiny,focus)
{
	init_load_tiny(language_tiny);
}

function read_details(internal_id, row_id, mode, refresh)
{
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = [];
	configuration['page'] = 'ajax/doc/display_html.php';

	configuration['delai_tentative'] = 10000; // 10 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	configuration['param'] = 'ssid='+ssid+'&language='+language+'&mode='+mode+'&internal_id='+internal_id+'&IDitem='+row_id+'&pathname='+window.location.pathname;
	configuration['fonction_a_executer_reponse'] = 'read_done';
	configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"','"+row_id+"','"+refresh+"'";

	// Do the call
	ajax_call(configuration);
	//==================================================================
}

function lib_hover(p_lib)
{
	document.getElementById('txt_help').innerHTML = p_lib;
}

function lib_out()
{
	document.getElementById('txt_help').innerHTML = '';
}

function read_done(internal_id,row_id,refresh,retour)
{
	retour = JSON.parse(retour);
	document.getElementById('details_tiny').style.display = 'none';

	document.getElementById('details').innerHTML = retour.HTML;
	document.getElementById('details').style.display = 'block';

	if(refresh == "D")
	{
		refresh_display(internal_id,row_id);
		// Try to focus on tree node
		try
		{
			document.getElementById(internal_id+row_id).focus();
		}
		catch (e)
		{
			// No focus enable.. doesn't matter.. continue
		}
	}
	try
	{
		document.getElementById('boutton_back').style.display = 'none';
		document.getElementById('boutton_edit').style.display = 'block';
	}
	catch (e)
	{
		// Display mode... continue
	}
	resize_details();
	resize_details();
}

function resize_details()
{
	// 2 times please !!
	document.getElementById('main_details').style.width = document.body.offsetWidth - document.getElementById('gauche').offsetWidth + "px";
	document.getElementById('details_body').style.height = document.body.offsetHeight - document.getElementById('head_path').offsetHeight - document.getElementById('slideh').offsetHeight - document.getElementById('headdetails').offsetHeight - document.getElementById('footer').offsetHeight + "px";
	//
	document.getElementById('ikdoc').style.width = document.getElementById('gauche').offsetWidth + "px";
	document.getElementById('slidev').style.left = document.getElementById('gauche').offsetWidth + "px";
}


window.onresize = function() {
	resize_details();
};

//==================================================================
// Call setup screen
//==================================================================
/**==================================================================
 * Jump to screen with ssid identifier
 *
 * @page        :   internal lisha identifier
 * @extend_url  :   Add an extension to your root path
 ====================================================================*/
function jump_screen(page,extend_url)
{
	if(extend_url == undefined)
	{
		extend_url = '?';
	}
	var my_root_path = window.location.origin;
	if (my_root_path == undefined)
		{
			// Opera exception
			my_root_path = '';
		}
	var href = my_root_path+extend_url+page+'&IKLNG='+language+'&ssid='+ssid;
	window.location = href;
}
//==================================================================

function user_tree_mode(id_page)
{
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = [];

	configuration['page'] = 'ajax/doc/tree_mode.php';

	configuration['delai_tentative'] = 10000; // 10 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	configuration['param'] = 'ssid='+ssid;
	configuration['fonction_a_executer_reponse'] = 'tree_mode_back_done';
	//configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"','"+row_id+"','"+refresh+"'";

	// Do the call
	ajax_call(configuration);
	//==================================================================
}

function tree_mode_back_done(retour)
{
	window.location = window.location;
	//retour = JSON.parse(retour);
	//document.getElementById('amount_valider').innerHTML = retour.A;
	//document.getElementById('amount_engager').innerHTML = retour.B;
	//document.getElementById('amount_prevu').innerHTML = retour.C;
}

function html_detail_display(internal_id)
{
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = [];

	configuration['page'] = 'ajax/doc/get_current_page.php';

	configuration['delai_tentative'] = 10000; // 10 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	configuration['param'] = 'ssid='+ssid;
	configuration['fonction_a_executer_reponse'] = 'get_current_page_done';
	configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"'";

	// Do the call
	ajax_call(configuration);
	//==================================================================
}
function get_current_page_done(internal_id,retour)
{
	document.getElementById('boutton_back').style.display = 'none';
	read_details(internal_id,retour,'U','D');
}

//==================================================================
//Initilialize tinyMCE
//==================================================================
function show_tiny(language)
{
	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = [];

	configuration['page'] = 'ajax/doc/recover_page_for_update.php';

	configuration['delai_tentative'] = 10000; // 10 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText
	configuration['param'] = 'ssid='+ssid+'&language='+language;
	configuration['fonction_a_executer_reponse'] = 'read_for_tiny';
	//configuration['param_fonction_a_executer_reponse'] = "'"+internal_id+"','"+row_id+"','"+refresh+"'";

	// Do the call
	ajax_call(configuration);
	//==================================================================
}
//==================================================================

function read_for_tiny(retour)
{
	document.getElementById('details').style.display = 'none';
	tinymce.get('elm1').setContent(retour);
	document.getElementById('details_tiny').style.display = 'block';	
	document.getElementById('boutton_back').style.display = 'block';
	document.getElementById('boutton_edit').style.display = 'none';
	document.getElementById('boutton_back').style.display = 'block';
}

function sauvegarder(ssid, language)
{
	var chaine = encodeURIComponent(document.getElementById("elm1").value);

	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = [];

	configuration['page'] = 'ajax/doc/record.php';
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 3000; // 3 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText

	configuration['param'] = 'ssid='+ssid+'&corps='+chaine+'&language='+language;
	configuration['fonction_a_executer_reponse'] = 'sauvegarde_retour';
	//configuration['fonction_a_executer_cas_non_reponse'] = 'end_load_ajax';

	// Usage : Always 3 parameters for HTML_TreeReturn !!!
	//				"'div_id','tree_item_prefixe','item_number_to_focus'"
	// if no focus	"'div_id',null,null"
	//configuration['param_fonction_a_executer_reponse'] = "";

	// Do the call
	ajax_call(configuration);
	//==================================================================
}

function sauvegarde_retour(retour)
{
	alert(retour);
}

function go_to_ifiche(language)
{
	if(document.getElementById('input_id_ifiche').value != '')
	{
		window.open('ifiche.php?ID='+document.getElementById('input_id_ifiche').value+language);
	}
}

function go_to_icode(language)
{
	if(document.getElementById('input_id_icode').value != '')
	{
		window.open('icode.php?ID='+document.getElementById('input_id_icode').value+language);
	}
}	
//==================================================================
// Initilialize tinyMCE
//==================================================================
function init_load_tiny(tiny_lang)
{
	tinyMCE.init({
		// General options
		mode : "textareas",
		language : tiny_lang,
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		//theme_advanced_styles : "Code=codeStyle;Quote=quoteStyle",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/home//tiny_details.css",
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "34834854"
		}
	});
}
//==================================================================