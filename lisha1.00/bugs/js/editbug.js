/**==================================================================
 * init_load : Eecute when page is loaded
 ====================================================================*/
function init_load(tiny_lang)
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

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
}

function sauvegarder(ssid,id)
{
	var chaine = encodeURIComponent(document.getElementById("elm1").value);
	var solution = encodeURIComponent(document.getElementById("solution").value);

	//==================================================================
	// Setup Ajax configuration
	//==================================================================
	var configuration = new Array();	

	configuration['page'] = 'record_ajax.php?ssid='+ssid;
	//configuration['div_wait'] = 'ajax_load_etape'+id_etape;
	//configuration['div_wait_nbr_tentative'] = 'ajax_step_qtt_retrieve'+id_etape;
	configuration['delai_tentative'] = 3000; // 3 seconds max
	configuration['max_tentative'] = 2;
	configuration['type_retour'] = false;		// ReponseText

	configuration['param'] = 'ssid='+ssid+'&corps='+chaine+'&solution='+solution+'&ID='+id;
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