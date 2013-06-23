/**==================================================================
 * Class common Timer
 ====================================================================*/	
function Class_timer() // Constructor
{
	// Nothing
}
/*
 * p_quartz	:	Period in ms ( Had to be greater than 10 )
 * 				default	:	50
 */
Class_timer.prototype.init = function(p_quartz,p_timername)
{
	this.c_status = false; 			// True if all is alright
	this.c_loop = 0;				// Count each loop
	this.c_id = 0;					// Internal id event
	this.c_remove = new Array();	// Internal event to remove
	this.c_message = '';			// Init message

	if (p_timername == undefined)
	{
		this.c_message = "p_timername had to be define !!";
		return this.c_message; // Exit error				
	}
	
	if ( p_quartz != undefined )
	{
		
		if ( isNaN(p_quartz)  ) // Is not a number
		{
			this.c_message = "Quartz is not a numeric !!";
			return this.c_message; // Exit error		
		}
		
		if ( p_quartz < 10) // Deny Of Service protection
		{
			p_quartz = 10;
		}
		this.c_quartz = p_quartz;
	}
	else
	{
		this.c_message = "Quartz is mandatory !!";
		return this.c_message; // Exit error no timer
	}
	
	// Ok, let's go
	//this.queue = new array();
	//this.queue = array(); 
	
	this.c_timername = p_timername;
	
	this.event = new Array(); // Events queue for job
	this.c_status = true;
	this.c_stat = null;
};

Class_timer.prototype.start = function()
{
	if (!this.c_status)
	{
		this.c_message = "Quartz not properly defined.. Have to initialize first ! Error Exit !!";
		return this.c_message;
	}

	this.c_stat = "Running";
	this.timer();
};

Class_timer.prototype.timer = function()
{	
	if (!this.c_status)
	{
		this.c_message = "Abording! Error Exit !!";
		return this.c_message;
	}
	
	var Class_timer = this; 
	
	function timerRelay()
	{ 
		Class_timer.timer(); 
	}
	
	this.c_loop = this.c_loop + 1;
	
	setTimeout(timerRelay, this.c_quartz);

	//*********************************//
	// Proceed job for each item
	//*********************************//
	for(var i=0;i<this.event.length;i++)
	{
		if (this.event[i][2] != undefined)
		{
			
			this.event[i][4] = this.event[i][4] - 1; // count down
			
			if (this.event[i][4] == 0) // Execute only if countdown reach zero
			{
				
				this.event[i][4] = this.event[i][1]; //Reload counter
				
				this.event[i][3] = eval(this.event[i][2]); // Call function to execute

				//===============================
				// Remove event from queue if any
				//===============================
				for(var j=0;j<this.c_remove.length;j++)
				{
					if ( this.c_remove[j] == this.event[i][0] )
					{
						// Found, remove this event from the queue
						this.event[i][3] = false; // Flag to remove item from the queue
						this.c_remove.splice(j, 1);	// Ok, item to remove found... Flag set. Remove it !
					}
					
				}
				//=================================//

				
				//======================================
				// Check flag of return function
				// if flase then remove animation from the queue
				//======================================
				if (!this.event[i][3])  // Return false ? Ok, remove current event from the queue
				{
					this.event.splice(i, 1);
				}
				//=================================//
				
			}
		}
		
	}
	
	//*********************************//
}
/*
 * p_id : Internal c_id to unload from the queue
 */
Class_timer.prototype.remove_event = function(p_id)
{
	if (p_id != undefined && p_id != null)
	{
		this.c_remove.push(p_id); 
	}
}

/*
 * Add new event in main loop
 * p_trigger 	:	count loop before trigged
 * 
 * 	Trigged when when reach p_trigger loop times
 * 
 * 	Do event at each loop : p_trigger = 1
 * 
 * p_function : What function to call
 */
Class_timer.prototype.add_event = function(p_trigger,p_function)
{
	if (!this.c_status)
	{
		this.c_message = "Quartz not properly defined.. Have to initialize first ! Error Exit !!";
		return this.c_message;
	}

	if (p_trigger == undefined )
	{
		p_trigger = 1;
	}
	if (isNaN(p_trigger) || p_trigger == 0) //
	{
		this.c_message = "p_trigger had to be a numeric different of 0 !!";
		return this.c_message; // Exit error no event add		
	}
	/*
	 * Params
	 * 0	:	Internal id event
	 * 1	:	p_trigger
	 * 2	:	Function to call
	 * 3	:	return value: If false then remove event from the queue
	 * 4	:	Internal : Init value of p_trigger was decreased
	 */
	this.x = new Array(this.c_id,p_trigger,p_function,true,p_trigger);
	this.handle = this.event.push(this.x);

	this.c_id = this.c_id + 1; // Next id number Setup 

	return (this.c_id - 1); // Return id to identify the event
}
/**==================================================================*/