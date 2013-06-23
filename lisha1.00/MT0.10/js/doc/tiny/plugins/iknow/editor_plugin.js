(function() {
	var each = tinymce.each;

	tinymce.create('tinymce.plugins.iknow',{
		init : function(ed, url) {
		var t = this;
		t.editor = ed;
		
		ed.onInit.add(function() {
			// Add context menu if it's loaded
			if (ed && ed.plugins.contextmenu) {
				ed.plugins.contextmenu.onContextMenu.add(function(plugin, menu, element) {
					if(element.nodeName == 'SPAN' && (element.className == 'ikcalc' || typeof(element.parentElement) != 'undefined' &&  element.parentElement.className == 'ikcalc'))
						menu.add({title : get_lib(446), icon : 'IKCalc', cmd : 'IKCalc'});
					if(element.nodeName == 'SPAN' && element.className.search('BBVa') != -1)
						menu.add({title : get_lib(447), icon : 'cancel', cmd : 'DeleteVar'});
					if(element.nodeName == 'A' && (element.href.search('icode.php') != -1 || element.href.search('ifiche.php') != -1))
						menu.add({title : get_lib(448), icon : 'LinkiKnow', cmd : 'LinkiKnow'});
				});
			}
		});
		
			/**
			 * Insert Varin plugin
			 */
			ed.addCommand('InsertVarIn', function() {
				ed.windowManager.open({
					file : url + '/InsertVarIn/index.php?ssid='+ed.getParam('ssid')+'&id_step='+ed.getParam('id_etape')+'&lng='+ed.getParam('language'),
					width : ed.getParam('template_popup_width', 840),
					height : ed.getParam('template_popup_height', 450),
					maximizable:true,
					resizable:true,
					inline : 1
				}, {
					plugin_url : url
				});
			});
			
			/**
			 * Insert Varout plugin
			 */
			ed.addCommand('InsertVarOut', function() {
				var_ext_etape();
				ed.windowManager.open({
					file : url + '/InsertVarOut/index.php?ssid='+ed.getParam('ssid')+'&id_step='+ed.getParam('id_etape')+'&lng='+ed.getParam('language'),
					width : ed.getParam('template_popup_width', 840),
					height : ed.getParam('template_popup_height', 610),
					maximizable:true,
					resizable:true,
					inline : 1
				}, {
					plugin_url : url
				});
			});
			
			/**
			 * Insert Special field plugin
			 */
			ed.addCommand('InsertSpecField', function() {
				ed.windowManager.open({
					file : url + '/InsertSpecField/index.php?ssid='+ed.getParam('ssid')+'&id_step='+ed.getParam('id_etape')+'&lng='+ed.getParam('language'),
					width : ed.getParam('template_popup_width', 840),
					height : ed.getParam('template_popup_height', 450),
					maximizable:true,
					resizable:true,
					inline : 1
				}, {
					plugin_url : url
				});
			});
			
			/**
			 * Insert link to step plugin
			 */
			ed.addCommand('LinkStep', function() {
				ed.windowManager.open({
					file : url + '/LinkStep/index.php?ssid='+ed.getParam('ssid')+'&id_step='+ed.getParam('id_etape')+'&lng='+ed.getParam('language'),
					width : ed.getParam('template_popup_width', 840),
					height : ed.getParam('template_popup_height', 450),
					maximizable:true,
					resizable:true,
					inline : 1
				}, {
					plugin_url : url
				});
			});
	
			ed.addCommand('LinkiKnow', function() {
				ed.windowManager.open({
					file : url + '/LinkiKnow/index.php?ssid='+ed.getParam('ssid')+'&id_step='+ed.getParam('id_etape')+'&url='+encodeURIComponent(tinyMCE.activeEditor.dom.getAttrib(tinyMCE.activeEditor.dom.getParent(tinyMCE.activeEditor.selection.getNode(), "A"), 'href'))+'&lng='+ed.getParam('language'),
					width : ed.getParam('template_popup_width', 900),
					height : ed.getParam('template_popup_height', 650),
					maximizable:true,
					resizable:true,
					inline : 1
				}, {
					plugin_url : url
				});
			});
	
			ed.addCommand('LinkPassword', function() {
				ed.windowManager.open({
					file : url + '/LinkPassword/index.php?ssid='+ed.getParam('ssid')+'&lng='+ed.getParam('language')+'&url='+encodeURIComponent(tinyMCE.activeEditor.dom.getAttrib(tinyMCE.activeEditor.dom.getParent(tinyMCE.activeEditor.selection.getNode(), "A"), 'href'))+'&lng='+ed.getParam('language'),
					width : ed.getParam('template_popup_width', 360),
					height : ed.getParam('template_popup_height', 180),
					inline : 1
				}, {
					plugin_url : url
				});
			});	
			
			ed.addCommand('InsertTitle', function() {
				var str = tinyMCE.execCommand('mceReplaceContent',false,'<span class=BBTitre>{$selection}</span></p>');
				ed.execCommand('mceInsertContent', false, str);
			});

			
			ed.addCommand('DeleteVar', function() {
				elm = ed.selection.getNode();
				var span_bbvar = ed.dom.getParent(elm, "span");
				span_bbvar.innerHTML = '';
			});

			
			/**
			 * IKCalc
			 */
			ed.addCommand('IKCalc', function() {
				var test = (tinyMCE.activeEditor.dom.getParent(tinyMCE.activeEditor.selection.getNode(), "span"));
				if(test != null && typeof(test) == 'object' && test.className != 'ikcalc')
				{
					if(test.parentElement.className == 'ikcalc')
					{
						var val_selection = test.parentElement.innerHTML;
					}
					else
					{
						var val_selection = '';
					}
				}
				else
				{
					if(test != null && typeof(test.innerHTML) == 'string')
					{
						var val_selection = test.innerHTML;
					}
					else
					{
						var val_selection = '';
					}
				}
				ed.windowManager.open({
					file : url + '/IKCalc/index.php?ssid='+ed.getParam('ssid')+'&id_step='+ed.getParam('id_etape')+'&lng='+ed.getParam('language')+'&selection='+encodeURIComponent(val_selection),
					width : ed.getParam('template_popup_width', 840),
					height : ed.getParam('template_popup_height', 450),
					maximizable:true,
					resizable:true,
					inline : 1
				});
			});

		ed.addButton('InsertVarIn', {title : get_lib(59), cmd : 'InsertVarIn'});
		ed.addButton('InsertVarOut', {title : get_lib(60), cmd : 'InsertVarOut'});
		ed.addButton('InsertSpecField', {title : get_lib(233), cmd : 'InsertSpecField'});
		ed.addButton('LinkStep', {title : get_lib(210), cmd : 'LinkStep'});
		ed.addButton('LinkiKnow', {title : get_lib(165), cmd : 'LinkiKnow'});
		ed.addButton('LinkPassword', {title : get_lib(213), cmd : 'LinkPassword'});
		ed.addButton('InsertTitle', {title : get_lib(47), cmd : 'InsertTitle'});
		ed.addButton('IKCalc', {title : get_lib(444), cmd : 'IKCalc'});
	}
	});
	tinymce.PluginManager.add('iknow', tinymce.plugins.iknow);
})();