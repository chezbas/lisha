var iKnowDialog = 
{
 	insert : function(value) {
		tinyMCEPopup.execCommand('mceInsertContent', false,value);
		tinyMCEPopup.close();
	},
	update : function(value) {
		var ed = tinyMCEPopup.editor;
		elm = ed.selection.getNode();
		elm = ed.dom.getParent(elm, "A");
		var dom = tinyMCEPopup.editor.dom;
		dom.setAttrib(elm, 'href',value);
	},
	update_span_ikcal : function(value) {
		var ed = tinyMCEPopup.editor;
		elm = ed.selection.getNode();
		var test = (ed.dom.getParent(elm, "span"));
		if(test != null && typeof(test) == 'object' && test.className != 'ikcalc')
		{
			if(test.parentElement.className == 'ikcalc')
			{
				var dom = tinyMCEPopup.editor.dom;
				dom.setHTML(elm.parentElement,value);
			}
		}
		else
		{
			if(test != null && typeof(test.innerHTML) == 'string')
			{
				var dom = tinyMCEPopup.editor.dom;
				dom.setHTML(elm,value);
			}
		}
	}
};
tinyMCEPopup.requireLangPack();
/*tinyMCEPopup.onInit.add(function() {
	Media.init();
});*/

function del_css(file)
{
	/* Get all css file included */
	var css_file=document.getElementsByTagName('link');
	var i = css_file.length;
	
	for(i; i>=0; i--)
	{ 
		if(css_file[i] && css_file[i].getAttribute('href') != null && css_file[i].getAttribute('href').indexOf(file)!= -1)
		{
			/* Delete the file */
			css_file[i].parentNode.removeChild(css_file[i]);
		}
	}
}