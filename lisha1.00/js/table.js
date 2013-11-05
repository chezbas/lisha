/**
 * Get the size of the header field
 * @param column column to get header size
 * @param lisha_id lisha id
 * @returns {Number}
 */
function lisha_get_size_header(column,lisha_id)
{
	/**==================================================================
	 * Get the size of the header
	 ====================================================================*/	
	var PosHeader_th0 = lisha_getPosition('th_0_'+column+'_'+lisha_id);
	var PosHeader_th4 = lisha_getPosition('th_4_'+column+'_'+lisha_id);

	return PosHeader_th4[0] - PosHeader_th0[0];
	/**==================================================================*/	
}

/**
 * Get the size of the data field
 * @param column column to get data size
 * @param lisha_id lisha id
 * @returns {Number}
 */
function lisha_get_size_data(column,lisha_id)
{
	/**==================================================================
	 * Get the size of the data
	 ====================================================================*/	
	var PosData_th0 = lisha_getPosition('td_0_'+column+'_'+lisha_id);
	var PosData_th3 = lisha_getPosition('td_3_'+column+'_'+lisha_id);

	return PosData_th3[0] - PosData_th0[0];
	/**==================================================================*/	
}



/**
 * Set the innerHTML of an element
 * @param el element id
 * @param val Value to set
 */
function lisha_set_innerHTML(el,val)
{
	if(document.getElementById(el) != null)
	{
		document.getElementById(el).innerHTML = val;
	}
	else
	{
		// Error on lis__lisha_help_hover_lisha_transaction_child__
		//alert(el);
	}
}

/**
 * Set the display style of an element
 * @param el element id
 * @param val Value to set
 */
function lisha_set_style_display(el,val)
{
	document.getElementById(el).style.display = val;
}

/**
 * Size the table of the lisha
 * @param lisha_id lisha ID
 */
function size_table(lisha_id)
{
	if(eval('lisha.'+lisha_id+'.qtt_line') > 0)
	{
		/**==================================================================
		 * Size the first column (checkbox column)
		 ====================================================================*/
		if(lisha_get_el_offsetWidth('div_td_l1_c0_'+lisha_id) > lisha_get_el_offsetWidth('th0_'+lisha_id))
		{
			// Data is larger than header, resize header
			lisha_set_el_width('th0_'+lisha_id,lisha_get_el_offsetWidth('div_td_l1_c0_'+lisha_id),'px');
		}
		else
		{
			// Header is larger than data, resize data
			lisha_set_el_width('div_td_l1_c0_'+lisha_id,lisha_get_el_offsetWidth('th0_'+lisha_id),'px');
		}
		/**==================================================================*/

		// Size min of a column (in px)
		var size_min = 100;

		/**==================================================================
		 * Browse and size all columns
		 ====================================================================*/
		for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
		{
			if(eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id') != undefined)
			{
				element_id = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id');

				// Get the size of the header
				var size_header = lisha_get_size_header(iterable_element,lisha_id);

				// Get the size of the data
				var size_data = lisha_get_size_data(iterable_element,lisha_id);

				if(size_data < size_min && size_header < size_min)
				{
					// Header and data are too short. Initialise to the size_min value

					// Resize the header
					lisha_set_el_width('th'+element_id+'_'+lisha_id,lisha_get_el_offsetWidth('th'+element_id+'_'+lisha_id)+size_min-size_header,'px');

					// Resize the data (-11 is the size of td_0_c1_lisha_bug + td_1_c1_lisha_bug padding + td_2_c1_lisha_bug + td_3_c1_lisha_bug)
					lisha_set_el_width('div_td_l1_c'+element_id+'_'+lisha_id,lisha_get_size_header(iterable_element,lisha_id)-11,'px');
				}
				else
				{
					if(size_data >= size_header)
					{
						// Data content is larger than header
						if(lisha_column_in_resize == true && lisha_column_resize == element_id)
						{
							/**==================================================================
							 * Column manual resize
							 ====================================================================*/
							if(size_header < size_min)
							{
								// Header is too short. Initialise to the size_min value

								// Resize the header
								lisha_set_el_width('th'+element_id+'_'+lisha_id,lisha_get_el_offsetWidth('th'+element_id+'_'+lisha_id)+size_min-size_header,'px');

								// resize data content
								lisha_set_el_width('div_td_l1_c'+element_id+'_'+lisha_id,lisha_get_size_header(iterable_element,lisha_id)-11,'px');
							}
							else
							{
								// resize data content
								window.status = size_header-(size_header-lisha_get_el_offsetWidth('th'+element_id+'_'+lisha_id));
								lisha_set_el_width('div_td_l1_c'+element_id+'_'+lisha_id,lisha_get_size_header(iterable_element,lisha_id)-11,'px');
							}


							// Control the size of the data
							if(lisha_get_el_offsetWidth('div_td_l1_c'+element_id+'_'+lisha_id) < lisha_get_el_offsetWidth('div_td_l2_c'+element_id+'_'+lisha_id))
							{
								// Resize the header
								lisha_set_el_width('th'+element_id+'_'+lisha_id,(lisha_get_size_data(iterable_element,lisha_id)-(lisha_get_size_header(iterable_element,lisha_id)-lisha_get_el_offsetWidth('th'+element_id+'_'+lisha_id))),'px');
								lisha_set_el_width('div_td_l1_c'+element_id+'_'+lisha_id,lisha_get_el_offsetWidth('div_td_l2_c'+element_id+'_'+lisha_id),'px');
							}
							/**==================================================================*/
						}
						else
						{
							// Set header to the size of data
							lisha_set_el_width('th'+element_id+'_'+lisha_id,lisha_get_el_offsetWidth('th'+element_id+'_'+lisha_id)+size_data-size_header,'px');
						}
					}
					else
					{
						// Header content is larger than data, set data to the size of the header
						lisha_set_el_width('div_td_l1_c'+element_id+'_'+lisha_id,lisha_get_size_header(iterable_element,lisha_id)-11,'px');
					}
				}
			}
		}
		/**==================================================================*/

		/**==================================================================
		 * Control free size
		 ====================================================================*/
		// Get the free size of the lisha

		var free_size = lisha_get_el_clientWidth('liste_'+lisha_id) - lisha_get_el_clientWidth('table_liste_'+lisha_id);

		if(free_size > 0)
		{
			// Free size available, share free size on all columns

			var qtt_column = eval('lisha.'+lisha_id+'.qtt_column');

			// Get the size to add on each column
			var size = Math.floor(free_size / qtt_column);

			// Get the free size residue
			var residue = free_size - (size * qtt_column);

			/**==================================================================
			 * Browse all column
			 ====================================================================*/
			for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
			{
				// Get the element id
				element_id = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id');

				// SRX : Generate dom error on sub lisha with hidden columns if your <tr> is incompleted
				// It seems that somethings missing in you sublisha
				// see around switch($this->c_type_internal_lisha) in class_graphic.php
				// SRX
				if(element_id != undefined && (lisha_get_el_offsetWidth('liste_'+lisha_id) - (lisha_get_el_offsetWidth('l1_'+lisha_id) + size)) >= 0)
				//if(element_id != undefined && (lisha_get_el_offsetWidth('liste_'+lisha_id) - size) >= 0)
				{
					(residue > 1) ? res = 1 : res = 0;

					// Set data size
					lisha_set_el_width('div_td_l1_c'+element_id+'_'+lisha_id,(lisha_get_el_offsetWidth('div_td_l1_c'+element_id+'_'+lisha_id) + size + res),'px');
					// Set header size
					lisha_set_el_width('th'+element_id+'_'+lisha_id,lisha_get_el_offsetWidth('th'+element_id+'_'+lisha_id)+size+res,'px');

					residue--;
				}
			}
			/**==================================================================*/
		}
		/**==================================================================*/

		/**==================================================================
		 * Initialise position flag of the columns
		 ====================================================================*/
		var element_id = 0;
		for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
		{
			if(eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id') != undefined)
			{
				if(element_id == 0)
				{
					i_last = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id');
				}
				else
				{
					i_last = element_id;
				}

				// Get the element id
				element_id = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id;');

				if(element_id == 1)
				{
					eval('lisha.'+lisha_id+'.columns.c'+element_id+'.min = 0;');
				}
				else
				{
					eval('lisha.'+lisha_id+'.columns.c'+element_id+'.min = '+eval('lisha.'+lisha_id+'.columns.c'+(i_last)+'.max +1'));
				}

				/**==================================================================
				 * Get the total header width of the column
				 ====================================================================*/
				var width = lisha_get_size_header(iterable_element,lisha_id);
				/**==================================================================*/

				// Get the left position of the column
				var left = document.getElementById('th_0_c'+element_id+'_'+lisha_id).offsetLeft;

				eval('lisha.'+lisha_id+'.columns.c'+element_id+'.max = '+((width/2)+left));
				eval('lisha.'+lisha_id+'.columns.c'+element_id+'.arrow = '+left);
			}
		}
		//==================================================================
		// Initialise position flag of the last column
		//==================================================================
		var last_col = eval('lisha.'+lisha_id+'.last_column;');
		eval('lisha.'+lisha_id+'.columns.c'+(last_col)+' = new Object();');
		eval('lisha.'+lisha_id+'.columns.c'+(last_col)+'.min = '+eval('lisha.'+lisha_id+'.columns.c'+element_id+'.max +1'));
		eval('lisha.'+lisha_id+'.columns.c'+(last_col)+'.max = '+document.getElementById('th_0_c'+(last_col)+'_'+lisha_id).offsetLeft);
		eval('lisha.'+lisha_id+'.columns.c'+(last_col)+'.arrow = '+document.getElementById('th_0_c'+(last_col)+'_'+lisha_id).offsetLeft);
		//==================================================================

		/**==================================================================*/
	}
	else
	{
		// No line on the lisha
		/**==================================================================
		 * Control free size
		 ====================================================================*/
		free_size = 0;

		// Get the free size of the lisha header
		free_size = lisha_get_el_clientWidth('liste_'+lisha_id) - lisha_get_el_clientWidth('tr_header_input_'+lisha_id)+203;

		if(free_size > 0)
		{
			// Free size available, share free size on all columns

			var qtt_column = eval('lisha.'+lisha_id+'.qtt_column');

			// Get the size to add on each column
			var size = Math.floor(free_size / qtt_column);
			// Get the free size residue
			var residue = free_size - (size * qtt_column);
			/**==================================================================
			 * Browse all column
			 ====================================================================*/
			for(var iterable_element in eval('lisha.'+lisha_id+'.columns'))
			{
				// Get the element id
				element_id = eval('lisha.'+lisha_id+'.columns.'+iterable_element+'.id');

				if(element_id != undefined)
				{
					(residue > 1) ? res = 1 : res = 0;
					// Set header size
					lisha_set_el_width('th'+element_id+'_'+lisha_id,lisha_get_el_offsetWidth('th'+element_id+'_'+lisha_id)+size+res,'px');

					residue--;
				}
			}
			/**==================================================================*/
		}
		/**==================================================================*/
	}
}