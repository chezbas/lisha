<?php
	require('header_ajax.php');

	switch($_POST['action'])
	{
		case 1:
			// Change page
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_page_change_ajax($_POST['type'],$_POST['selected_lines']);		
			break;
		case 2:
			// Refresh page
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->refresh_page($_POST['selected_lines'],$_POST['qtt']);
			break;
		case 3:
			// Move column
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->move_column($_POST['c_src'],$_POST['c_dst'],$_POST['selected_lines']);					
			break;
		case 4:
			// Change order
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->change_order($_POST['column'],$_POST['order'],$_POST['mode'],$_POST['selected_lines']); 				
			break;		
		case 5:
			// Search column onkeyup
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_input_search_onkeyup($_POST['column'],$_POST['txt'],$_POST['selected_lines']);				
			break;
		case 6:
			// Define a filter on a column
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->define_filter($_POST);																		
			break;
		case 7:
			// Save a new filter
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->save_filter($_POST['name']);																
			break;
		case 8:
			// Change the search mode on a column
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->change_search_mode($_POST['column'],$_POST['type_search'],$_POST['selected_lines']);		
			break;
		case 9:
			// Reset the filter on all column
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->reset_lisha();							
			break;
		case 10:
			// Hide or display a column
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->toggle_column($_POST['column'],$_POST['selected_lines']);																
			break;
		case 11:
			// global_search - Multiple columns search
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->global_search($_POST['value']);
			break;
		case 12:
			// Change the alignment mode on a column
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->change_alignment($_POST['column'],$_POST['type_alignment'],$_POST['selected_lines']);
			break;
		case 13:
			// Edit lines ( Update )
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->edit_lines($_POST['lines']);
			break;
		case 14:
			// Save lines ( Add new )
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->save_lines($_POST['val_json']);
			break;
		case 15:
			// Delete lines
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->delete_lines($_POST['lines']);
			break;
		case 16:
			// Add lines
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->add_line($_POST['val_json']);
			break;
		case 17:
			// Load a filter
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_load_filter($_POST['filter_name']);
			break;
		case 18:
			// Generate a calendar
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_generate_calendar($_POST['column'],$_POST['input']);
			break;
		case 19:
			// Return calendar date
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_generate_calendar($_POST['column'],$_POST['input'],$_POST['year'],$_POST['month'],$_POST['day']);
			break;
		case 20:
			// cancel edition
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_cancel_edit();
			break;
		case 21:
			// Format date
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->db_format($_POST['mode'],$_POST['column'],$_POST['value']);
			break;
		case 22:
			// Recover cell content
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->recover_cell($_POST['arraykey'],$_POST['column']);
			break;
		case 23:
			// Record cell update
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->edit_cell($_POST['arraykey'],$_POST['column'],$_POST['val']);
			break;
		case 24:
			// Excel export total of rows
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->export_list($_POST['lines'], true);
			break;
		case 25:
			// Execute excel export
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->export_list($_POST['lines'], false);
			break;
		case 26:
			// Check __LISTED__ Compel on field
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->check_listed_compel($_POST['column'],$_POST['value']);
			break;
		case 27:
			// Update column hide/show
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->show_column($_POST['selected_lines']);
			break;
		case 28:
			// Check column list user choice
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->check_column_choice();
			break;
		case 29:
			// Get current date hour format on keyboard shortcut CTRL + ;
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->current_date_hours($_POST['column']);
			break;
	}