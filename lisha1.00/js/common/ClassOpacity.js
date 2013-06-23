/**==================================================================
 * Class Opacity Effect
 ====================================================================*/	
function Class_opacity(begin,step,p_cycle,id,p_focus) // Constructor
{
	this.init(begin,step,p_cycle,id,p_focus);	
};

Class_opacity.prototype.getvalue = function()
{
	return this.c_value;
};

Class_opacity.prototype.setvalue = function(p_init)
{
	if ( p_init != undefined )
	{
		this.c_value = p_init;
	}
	else
	{
		this.c_value = null;
	}
};

/*
 * Start / Resume animationeffect
 */
Class_opacity.prototype.start = function() // Start opacity effect
{
	if (!this.status || this.status == undefined)
	{
		this.message = "Wrong initialization. Abord start function !!";
		return this.status; // Exit No opacity effect possible	
	}
	
	this.actif = true; 	// true : Effect in progress, false : No effect in progress
	
	if ( (this.mode == 1) && (this.c_value == 0) )
	{
		document.getElementById(this.id).style.display = "block";
		//alert(this.c_setfocus);
		if (this.c_setfocus != null || this.c_setfocus != undefined )
		{
			document.getElementById(this.c_setfocus).focus(); // Setup focus if any
		}
	}
	if ( (this.mode == -1) )
	{
		//document.getElementById(this.id).style.opacity = this.c_value;
		//document.getElementById(this.id).style.display = "block";
		if (this.c_setfocus != null || this.c_setfocus != undefined)
		{
			document.getElementById(this.c_setfocus).focus(); // Setup focus if any
		}
	}
};

Class_opacity.prototype.stop = function() // Stop opacity effect
{
	if (!this.status)
	{
		this.message = "Wrong initialization. Abord stop function !!";
		return this.status; // Exit No opacity effect possible	
	}
	this.actif = false; 	// true : Effect in progress, false : No effect in progress	
};

/*
 * Do effect and prepare next step
 */
Class_opacity.prototype.effect = function() // Do opacity effect
{
	/*======================================================
	 * Check up if all is alright
	 ======================================================*/
	if (!this.actif)
	{
		this.message = "Not enable.. You have to start to continue !!";
		return this.message; // Exit No opacity effect possible	
	}	

	if (!this.status)
	{
		this.message = "Wrong initialization. Abord effect function !!";
		return this.message; // Exit No opacity effect possible	
	}
	/*======================================================*/
	
	// Apply current opacity to your div
	document.getElementById(this.id).style.opacity = this.c_value;

	// Do opacity effect step
	this.c_value = this.c_value + ( this.mode * this.step );
	
	// Totaly hidden : Force opacity low value
	if ( (this.c_value < 0) && (this.mode == -1) ) 
	{
		this.c_value = 0;
		document.getElementById(this.id).style.display = "none";
		this.stop();
	}

	// Totaly show : Force opacity max value
	if ( (this.c_value > 1) && (this.mode == 1) ) 
	{
		this.c_value = 1;
		document.getElementById(this.id).style.display = "block";
		this.stop();
	}

	// Count cycle and repeat mode
	if (this.c_value == 0 || this.c_value == 1)
	{		
		if ( this.c_cycle == -1)
		{
			this.c_value = 1;
		}
		if ( this.c_cycle > 0 )
		{
			this.c_value = 1;
			this.c_cycle = this.c_cycle - 1;
		}
		if ( this.c_cycle == 0 )
		{
			if (this.c_value == 0 && this.mode == -1)
			{
				document.getElementById(this.id).style.display = "none";
			}

			if (this.c_value == 0 && this.mode == 1)
			{
				document.getElementById(this.id).style.display = "block";
			}
			
			this.stop();
		}
	}
};

/*
 * begin :	show : Means to be displayed
 * 			hide : Means to be undisplayed
 * 
 * step	:	absolute step value for opacity speed
 * 			default	: 0.1
 * 
 * p_cycle :	0 	: 	No repeat
 * 				n	:	Numeric positif : repeat n times
 * 				-1	:	Infinite loop 
 * 
 * id	: Id of div to setup display mode ( block or none )
 * 
 * p_focus : 	Id to setup focus when show effect start
 * 				If undefined, setup no focus
 */
Class_opacity.prototype.init = function(begin,step,p_cycle,id,p_focus)
{
	this.c_value = -1;
	this.c_cycle = 0;
	this.step = 0.1;
	this.message = "";			// Error message for any trouble
	this.status = false; 		// True if all is alright
	this.actif = false; 		// true : Effect in progress, false : No effect in progress
	this.mode = false; 			// Currently move : 1 : increase, -1 : Decrease	
	this.c_setfocus = null;	// Id for focus when effect start
	
	if ( p_focus != null && p_focus != undefined )
	{
		this.c_setfocus = p_focus;
	}
	
	// Div id
	if (id == undefined)
	{
		this.message = "id parameter had to be define !!";
		return this.message; // Exit No opacity effect possible
	}
	else
	{
		this.id = id;		
	}
	
	if ( (begin == undefined) || (step == undefined) )
	{
		// Init sequence error
		this.message = "begin parameter and step parameters had to be define !!";
		return this.message; // Exit No opacity effect possible
	}

	if ( (p_cycle == undefined) || (p_cycle < -1) )
	{
		// Init sequence error
		this.message = "repeat parameters had to be define and be upper than -1 !!";
		return this.message; // Exit No opacity effect possible
	}
	
	if ( isNaN(p_cycle)  ) // Is not a number
	{
		// Init sequence error
		this.message = "repeat parameters had to be a numeric !!";
		return this.message; // Exit No opacity effect possible
	}
	
	if ( (begin != "show") && (begin != "hide") )
	{
		// Wrong begin parameter value
		// For memory, 2 values possible
		// show : Means to be displayed
		// hide : Means to be undisplayed
		this.message = "begin parameter had to be setup by <show> or <hide> !!";
		return this.status; // Exit No opacity effect possible	
	}

	this.c_cycle = p_cycle;
	
	if ( begin == "show")
	{
		this.c_value = 0;
		this.mode = 1;
	}
	else
	{
		this.c_value = 1;
		this.mode = -1;
	}
	
	this.step = step;
	
	this.message = "Initialization Done !!";
	this.status = true;
};
/**==================================================================*/