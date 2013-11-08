/**
 * Set the width of an element
 * @param el element id
 * @param size size of the element
 * @param data_type type of size
 */
function lisha_set_el_width(el,size,data_type)
{
	document.getElementById(el).style.width = size+data_type;
}

/**
 * Set the height of an element
 * @param el element id
 * @param size size of the element
 * @param data_type type of size
 */
function lisha_set_el_height(el,size,data_type)
{
	document.getElementById(el).style.height = size+data_type;
}

/**
 * Get the offsetWidth of an element
 * @param el element id
 */
function lisha_get_el_offsetWidth(el)
{
	return document.getElementById(el).offsetWidth;
}

/**
 * Get the client width of an element
 * @param el element id
 */
function lisha_get_el_clientWidth(el)
{
	return document.getElementById(el).clientWidth;
}

/**==================================================================
 * lisha_get_innerHTML : Get innerHTML content of an item
 * @el	: dom identifier
 ====================================================================*/
function lisha_get_innerHTML(el)
{

	try {
		return document.getElementById(el).innerHTML;
	} catch (e) {
		alert('cath error E100 : '+el+' not found in DOM');
	}

}
/**==================================================================*/


/**==================================================================
 * lisha_get_innerText : Get only text content of an item
 * @el	: dom identifier
 ====================================================================*/
function lisha_get_innerText(el)
{

	try {
		return document.getElementById(el).innerText;
	} catch (e) {
		alert('cath error E110 : '+el+' not found in DOM');
	}

}
/**==================================================================*/

/**==================================================================
 * function isFloat(n)
 * @n	: value
 ====================================================================*/
function isFloat(n)
{
	return !isNaN(parseFloat(n)) && isFinite(n);
}
/**==================================================================*/

/**==================================================================
 * function isInteger
 * @n	: value
 ====================================================================*/
function isInteger(n)
{
	return (n.toString().search(/^-?[0-9]+$/) == 0);
}
/**==================================================================*/
