function lisha_menu(id_lisha) 
{ 
    this.id_lisha = id_lisha;
    this.line = new Object(); 
    this.qtt_line = 0;
    this.html = '';
    this.hover_tempo;
    this.el;
    this.opened_child = false;
    this.opened_child_line = false;
    this.opened_child_parent = false;
    this.opened_child_parent_line = false;
    this.parent = false;
    this.menu_id = 'menu_1';
    
    /**
     * Display menu
     */
    this.display_menu = function()
    { 
    	this.html = '<table class="shadow">';
		this.html += '<tr><td class="shadow_l"></td><td colspan=2 class="shadow">';
		this.html += '<table id="table_header_menu_'+this.id_lisha+'" class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu">';

    	for(j = 1;j <= this.qtt_line;j++)
    	{
    		if(eval('this.line.l'+j+'.type') == "LINE")
    		{
	    		this.html += '<tr class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu" '+eval('this.line.l'+j+'.event')+' id="'+this.menu_id+'_l'+j+'" onmouseover="lisha.'+this.id_lisha+'.obj_menu.onmouseover(this,'+j+',\''+eval('this.parent')+'\',\''+this.menu_id+'_'+j+'\');">';
	    		this.html += '<td class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu_icon"><div class="'+eval('this.line.l'+j+'.class_icon')+'"></div></td>';
	    		this.html += '<td class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu_sep"></td>';
	    		this.html += '<td class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu_lib">'+eval('this.line.l'+j+'.title')+'</td>';
	    		if(eval('this.line.l'+j+'.child') != undefined)
	    		{
	    			// The line is parent, display an arrow
	    			this.html += '<td><div id="arrow_'+this.menu_id+'_l'+j+'" class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_ico __'+eval('lisha.'+this.id_lisha+'.theme')+'_ico_arrow_menu_child"></div></td>';
	    		}
	    		else
	    		{
	    			this.html += '<td></td>';
	    		}
	    		
	    		this.html += '</tr>';
    		}
    		else
    		{
    			// Line separator
	    		this.html += '<tr class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu lisha_line_separator">';
	    		this.html += '<td class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu_icon_sep"></td>';
	    		this.html += '<td colspan=3 class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu_line_sep_top"></td>';
	    		this.html += '</tr>';
	    		this.html += '<tr class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu lisha_line_separator">';
	    		this.html += '<td class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu_icon_sep"></td>';
	    		this.html += '<td colspan=3 class="__'+eval('lisha.'+this.id_lisha+'.theme')+'_column_header_menu_line_sep_bottom"></td>';
	    		this.html += '</tr>';
    		}
    	}

    	this.html += '</table>';
		this.html += '</td></tr>';
		this.html += '<tr><td class="shadow_l_b"></td><td class="shadow_b"></td><td class="shadow_r_b"></td></tr></table>';

		/**==================================================================
		 * Child menu
		 ====================================================================*/	
		var i = 1;
		for(i = 1;i <= this.qtt_line;i++)
    	{
    		if(eval('this.line.l'+i+'.child') != undefined)
    		{
    			eval('this.html += \'<div id="'+eval('this.line.l'+i+'.child.menu_id')+'" style="width:0;display:none;position: relative;">\'+this.line.l'+i+'.child.display_menu()+\'</div>\'');
    		}
    	}
		/**==================================================================*/
		
		
		return this.html;
    };

    /**
     * Add a new line
     */
    this.add_line = function(title,class_icon,event,enable,child)
    {
    	// Increment the number of line
    	this.qtt_line++;
    	
    	// Create the line
    	eval('this.line.l'+this.qtt_line+' = new Object();');
    	eval('this.line.l'+this.qtt_line+'.type = "LINE"');
    	if(enable)
    	{
    		eval('this.line.l'+this.qtt_line+'.title = "'+title+'"');
    	}
    	else
    	{
    		eval('this.line.l'+this.qtt_line+'.title = "<span style=\\"color:grey;\\">'+title+'</span>"');
    	}
        
    	
        
        eval('this.line.l'+this.qtt_line+'.id = "l_'+this.qtt_line+'"');
        if(enable)
        {
        	eval('this.line.l'+this.qtt_line+'.event = "onclick=\\"lisha_toggle_header_menu(\''+this.id_lisha+'\','+eval('lisha.'+this.id_lisha+'.menu_opened_col;')+');'+event+'\\";"');
        	eval('this.line.l'+this.qtt_line+'.class_icon = "'+class_icon+'"');
        }
        else
        {
        	eval('this.line.l'+this.qtt_line+'.event = ""');
        	eval('this.line.l'+this.qtt_line+'.class_icon = "'+class_icon+' grey_el"');
        }
        if(typeof(child) != 'undefined')
        {
        	// The line have a submenu
        	eval('this.line.l'+this.qtt_line+'.child = child');
        	eval('this.line.l'+this.qtt_line+'.child.parent = \''+this.menu_id+'\'');
        	eval('this.line.l'+this.qtt_line+'.event = "onclick=\\"lisha.'+this.id_lisha+'.obj_menu.display_child(this,'+this.qtt_line+',\''+this.menu_id+'\'); /**/\\" onmouseout=\\"lisha.'+this.id_lisha+'.obj_menu.onmouseout('+this.qtt_line+');\\""');
        	this.set_menu_id(this.qtt_line,this.menu_id);
        	//this.set_line_id(this.qtt_line);
        }
    };
    
    this.set_menu_id = function(line,parent_id)
    {
    	eval('this.line.l'+line+'.child.menu_id = \''+parent_id+'_'+line+'\'');
    };
    
    this.set_line_id = function(line,parent_id)
    {
    	//alert(eval('this.line.l'+line+'.child.line'));
    	//return '';
    	/*for(var iterable in eval('this.line.l'+line+'.child.line'))
    	{
    		//alert(eval('this.line.l'+line+'.child.line.'+iterable+'.event'));
    		
		}*/
    	
    };
    
    this.display_child = function(el,line_id,menu_id)
    {
    	clearTimeout(this.hover_tempo);
    	
    	if(this.opened_child != menu_id+'_'+line_id)
    	{
	    	if(el != null)
	    	{
	    		this.el = el;
	    	}
	    	
	    	this.opened_child = menu_id+'_'+line_id;
	    	this.opened_child_parent = menu_id;
	    	this.opened_child_parent_line = menu_id+'_l'+line_id;
	    	this.opened_child_line = line_id;
	    	this.el.style.backgroundColor = '#D0D0D0';
	    	document.getElementById('arrow_'+menu_id+'_l'+line_id).className = '__'+eval('lisha.'+this.id_lisha+'.theme')+'_ico __'+eval('lisha.'+this.id_lisha+'.theme')+'_ico_arrow_menu_child_hover';
	
	    	eval('lisha.'+this.id_lisha+'.stop_click_event = true');
	    	this.place_child();
	    	
	    	
	    	
	    	//alert((lisha_getPosition(this.opened_child)));
    	}
    };
    
    this.hide_child = function(line,el)
    {
    	document.getElementById(this.opened_child_parent_line).style.backgroundColor = '';
    	document.getElementById('arrow_'+this.opened_child_parent+'_l'+line).className = '__'+eval('lisha.'+this.id_lisha+'.theme')+'_ico __'+eval('lisha.'+this.id_lisha+'.theme')+'_ico_arrow_menu_child';
    	clearTimeout(this.hover_tempo);
    	child_tmp = this.opened_child;
    	this.opened_child = false;
    	this.opened_child_line = false;
    	if(typeof(el) != 'undefined')
    	{
    		this.el = el;
    	}
    	

    	
    	eval('lisha.'+this.id_lisha+'.stop_click_event = true');
    	document.getElementById(child_tmp).style.display = 'none';
    };
    
    
    /**
     * Onmouseover event on a line
     */
    this.onmouseover = function(el,line,parent_menu,submenu)
    {
    	clearTimeout(this.hover_tempo);
    	
    	this.el = el;
    	
    	if(document.getElementById(this.menu_id+'_'+line) != null && this.opened_child == false)
    	{
    		// This is a parent
    		this.hover_tempo = setInterval('lisha.'+this.id_lisha+'.obj_menu.display_child(null,'+line+',\''+this.menu_id+'\');',500);
    	}
    	else
    	{
    		// This is not a parent
    		if(this.opened_child != false  && parent_menu != this.opened_child_parent && this.opened_child_line != line)
    		{
    			this.hide_child(this.opened_child_line);
    			this.onmouseover(el,line,parent_menu,submenu);
    		}
    	}
    };
    
    this.onmouseout = function(line)
    {
    	//this.hide_child(line);
    };
    
    this.place_child = function()
    {
    	// Vertical alignment
    	document.getElementById(this.opened_child).style.top = (this.el.offsetTop)-document.getElementById('lis_column_header_menu_'+this.id_lisha).offsetHeight+'px';
    	
    	// Display the menu
    	document.getElementById(this.opened_child).style.display = 'block';
    	
    	// Get lisha position
    	lisha_pos = lisha_getPosition('lis__'+eval('lisha.'+this.id_lisha+'.theme')+'__lisha_table_'+this.id_lisha+'__');
    	
    	// Get lisha width
    	lisha_width = document.getElementById('lis__'+eval('lisha.'+this.id_lisha+'.theme')+'__lisha_table_'+this.id_lisha+'__').offsetWidth;
    	
    	// Get menu position
    	menu_pos = lisha_getPosition(this.el.id);
    	
    	// Get menu width
    	menu_width = document.getElementById(this.el.id).offsetWidth;
    	
    	// Get submenu width 
    	submenu_width = document.getElementById(this.opened_child+'_l1').offsetWidth;
    	
    	if((lisha_width-(menu_pos[0]-lisha_pos[0])-menu_width)<submenu_width)
    	{
    		// no enter
    		document.getElementById(this.opened_child).style.left = '-'+(document.getElementById(this.opened_child+'_l1').offsetWidth-2)+'px';
    	}
    	else
    	{
    		// enter
    		document.getElementById(this.opened_child).style.left = (this.el.offsetWidth-3)+'px';
    	}
    	
    };
    
    /**
     * Add a line separator
     */
    this.add_sep = function()
    { 
    	// Increment the number of line
    	this.qtt_line++;
    	
    	// Create the line
    	eval('this.line.l'+this.qtt_line+' = new Object();');
        eval('this.line.l'+this.qtt_line+'.type = "SEP"');
    };
} 