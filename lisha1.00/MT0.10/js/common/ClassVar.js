/**==================================================================
 * Class common variable
 ====================================================================*/	
/*
 * p_init : Start variable value setup
 * 			if undefined then default value 0 forced
 */
function Class_var(p_init) // Constructor
{
	this.setvalue(p_init);	
};
/*
 * p_add : value to add to the value
 * 			if undefined then nothing appends
 * 			if not numeric then nothing appends
 */
Class_var.prototype.add = function(p_add) // Add value
{
	if ( p_add == undefined )
	{
		return "Nothing to add. Exit";
	}
	
	if ( Number(p_add) != "NaN" )
	{
		this.c_value = this.c_value + p_add;
	}
	else
	{
		return "Not a numeric";
	}
};
/*
 * p_init : Reset value
 * 			if undefined Setup null
 */
Class_var.prototype.setvalue = function(p_init)
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
Class_var.prototype.getvalue = function()
{
	return this.c_value;
};

/**==================================================================*/