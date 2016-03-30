<?php
	class graphic_lisha
	{
		//==================================================================
		// Define private attributes
		//==================================================================
		private $c_id;					// lisha id
		private $c_ssid;				// Session id
		private $c_lng;					// Language of the lisha
		private $c_dir_obj;				// Directory of the lisha object

		private $c_height;				// lisha height
		private $c_h_unity;				// lisha unity height (px,%)
		private $c_width;				// lisha width
		private $c_w_unity;				// lisha unity width (px,%)
		private $c_nb_line;				// Number of line per page

		public $c_columns_init;		// Original columns values

		private $c_software_version;	// Version of the lisha

		// Page 
		private $c_limit_min;			// Min value of the limit
		private $c_limit_max;			// Max value of the limit

		private $c_recordset_line;		// Total line recorded
		private $c_obj_bdd;
		private $c_id_parent;
		private $c_id_parent_column;
		private $c_type_internal_lisha;
		private $c_navbar_txt_activate;
		private $c_navbar_refresh_button_activate;
		private $c_navbar_nav_button_activate;
		private $c_navbar_txt_line_per_page_activate;
		private $c_lmod_specified_width;


		public $string_global_search;				// String used to do global search

		public $matchcode;				            // Matchcode between internal external call and function name
		public $any_filter;                        // true if any filter recorder in your lisha
		//==================================================================

		//==================================================================
		// Define public attributes
		//==================================================================
		public $c_columns;						// Columns (array())
		public $c_help_button;					// Enable or disable user help button
		public $c_tech_help_button;				// Enable or disable technical help button
		//==================================================================

		/**==================================================================
		 * Builder of lisha graphic class
		 * @$p_software_version				: software version reference
		 * @p_id							: lisha identifier reference
		 * @p_ssid			 				: session token reference
		 * @p_obj_bdd						: database connexion 
		 * @p_dir_obj						: relative path of lisha
		 * @p_columns						: features of columns
		 * @p_selected_line					: Lines selected
		 * @p_type_internal_lisha			: lisha mode ( __LOAD_FILTER__, __COLUMN_MODE__ ... )
		 * @p_lng							: language in use
		 ====================================================================*/
		public function __construct($p_software_version,&$p_id,&$p_ssid,$p_obj_bdd,$p_dir_obj,&$p_columns,&$p_selected_line,&$p_type_internal_lisha,$p_lng)
		{
			$this->c_dir_obj = &$p_dir_obj;
			$this->c_software_version = &$p_software_version;
			$this->c_id = &$p_id;
			$this->c_ssid = &$p_ssid;
			$this->c_obj_bdd = &$p_obj_bdd;
			$this->c_columns = &$p_columns;
			$this->c_selected_lines = &$p_selected_line;
			$this->c_type_internal_lisha = &$p_type_internal_lisha;
			$this->c_lng = $p_lng;
			$this->c_navbar_txt_activate = true;
			$this->c_navbar_refresh_button_activate = true;
			$this->c_navbar_nav_button_activate = true;
			$this->c_navbar_txt_line_per_page_activate = true;
			$this->c_lmod_specified_width = null;

			//==================================================================
			// Define here lisha features that need to be handle with read_attribute() or get_attribute()
			// 2 modes are available
			//	With column name : read_attribute('__column_display_name','id')
			//		That means data is store in this->c_columns
			//
			//	Without column name : read_attribute('__main_query')
			// 		That means it's a general feature and it value is recorded in $this->matchcode array
			//
			// A : All ( Read and Write )
			// R : Read only
			// W : Write only
			//==================================================================
			$this->matchcode = array(
			'__active_user_cells_update'										=> array(true,'A'),
			'__active_read_only_cells_edit'										=> array('','A'),
			'__column_name_group_of_color'										=> array('','A'),
			'__internal_color_mask'												=> array('','A'),
			'__id_theme'														=> array('','A'),
			'__return_column_id'												=> array('','A'),
			'__current_page'													=> array('','A'),
			'__display_mode'													=> array('','A'),
			'__active_top_bar_page'												=> array('','A'),
			'__active_bottom_bar_page'											=> array('','A'),
			'__active_column_separation'										=> array('','A'),
			'__active_row_separation'											=> array('','A'),
			'__title'															=> array('','A'),
			'__active_title'													=> array(true,'A'),
			'__active_readonly_mode'											=> array('','A'),
			'__active_user_doc'													=> array('','A'),
			'__active_tech_doc'													=> array(false,'A'),
			'__active_quick_search'												=> array(true,'A'),
			'__active_global_search'                                            => array(true,'A'),
			'__active_insert_button'                                            => array(true,'A'),
			'__active_delete_button'                                            => array(true,'A'),
			'__background_picture'												=> array('','A'),
			'__background_repeat'												=> array('no-repeat','A'),
			'__active_ticket'													=> array(false,'A')
			);
			//==================================================================

		}
		/**===================================================================*/


		/**==================================================================
		 * Generate the lisha visual content
		 * @p_resultat				: Query results
		 * @p_resultat_header		: lisha header
		 * @p_ajax_call		 		: boolean of ajax call or not
		 * @p_edit					: Edit mode in progress ( true means yes )
		 ====================================================================*/
		public function draw_lisha($p_resultat,$p_result_header = false,$p_ajax_call = null,$p_edit = false)
		{
			$lisha = '';
			if($p_ajax_call == null)
			{
				//==================================================================
				// Create lisha container
				//==================================================================
				$lisha .=  '<div id="lis__'.$this->matchcode['__id_theme'][0].'__lisha_table_'.$this->c_id.'__" onclick="lisha_click(event,\''.$this->c_id.'\')">';
				//==================================================================

				//==================================================================
				// Create message box ( Wait + Msgbox )
				//==================================================================
				// Begin container
				$lisha .= '<div id="lis__'.$this->matchcode['__id_theme'][0].'__hide_container_'.$this->c_id.'__" onclick="lisha_hide_container_click(\''.$this->c_id.'\');" class="__'.$this->matchcode['__id_theme'][0].'_hide_container">';

					// Wait
					$lisha .= '<div id="lis__'.$this->matchcode['__id_theme'][0].'__wait_'.$this->c_id.'__" class="__'.$this->matchcode['__id_theme'][0].'_wait" style="display:none;"></div>';

					// Msgbox
					$str_top = "";
					if($this->c_type_internal_lisha == __LOAD_FILTER__ || $this->c_type_internal_lisha == __COLUMN_LIST__)
					{
						$str_top = "top:150px;";
					}
					$lisha .= '<div class="msgbox_conteneur" id="lis_msgbox_conteneur_'.$this->c_id.'" style="display:none; '.$str_top.'"></div>';

				// End container
				$lisha .= '</div>';
				//==================================================================

				//==================================================================
				// Create the div for column move function
				//==================================================================
				// Arrows
				if($this->matchcode['__display_mode'][0] != __CMOD__)
				{
					$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'__arrow_move_column_top" id="arrow_move_column_top_'.$this->c_id.'"></div>';
					$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'__arrow_move_column_bottom" id="arrow_move_column_bottom_'.$this->c_id.'"></div>';
				}

				// Wait input 
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'__wait_input" id="wait_input_'.$this->c_id.'"></div>';


				// window button bar only on sub lisha
				if(isset($this->c_id_parent))
				{
					$_visible = 'lisha_disp';
					if(isset($this->c_id_parent_column))
					{
						$l_index = $this->c_id_parent_column;
					}
					else
					{
						$l_index = 1;
					}
				}
				else
				{
					$_visible = 'lisha_hide';
					$l_index = 1;
				}
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'__window_button_function '.$_visible.'" id="window_function_'.$this->c_id.'"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_cancel hover" '.$this->hover_out_lib(163,32).' style="float:right;margin-right:5px;" onclick="lisha_child_cancel(\''.substr($this->c_id,0,-6).'\',\''.$l_index.'\')"></div></div>';


				// Float div
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'__float_move_column" id="lisha_column_move_div_float_'.$this->c_id.'">';
				$lisha .= '<table class="shadow">';
				$lisha .= '<tr><td class="shadow_l"></td><td class="shadow">';
				$lisha .= '<table id="table_header_menu_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_column_header_menu">';
				$lisha .= '<tr class="__'.$this->matchcode['__id_theme'][0].'_column_header_menu __'.$this->matchcode['__id_theme'][0].'__float_move_column_content">';
				$lisha .= '<td><div id="lisha_column_move_div_float_forbidden_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'__float_column"></div></td><td><div class="__'.$this->matchcode['__id_theme'][0].'__float_move_column_content" id="lisha_column_move_div_float_content_'.$this->c_id.'">..........</div></td>';
				$lisha .= '</tr>';
				$lisha .= '</table>';
				$lisha .= '</td></tr>';
				$lisha .= '<tr><td class="shadow_l_b"></td><td class="shadow_b"></td></tr></table>';
				$lisha .= '</div>';
				//==================================================================

				//==================================================================
				// Title bar
				//==================================================================
				if($this->matchcode['__active_title'][0] == true)
				{
					$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'_lisha_title" id="lisha_title_'.$this->c_id.'">';
					$lisha .= $this->matchcode['__title'][0];
					$lisha .= '</div>';
				}
				//==================================================================

				//==================================================================
				// Tools bar
				//==================================================================
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'_lisha_toolbar" id="lisha_toolbar_'.$this->c_id.'">';
				$lisha .= $this->generate_toolbar($p_edit,$p_resultat);
				$lisha .= '</div>';
				//==================================================================

				$lisha .= '<div id="lisha_ajax_return_'.$this->c_id.'">';
			}

			//==================================================================
			// Header page selection
			//==================================================================
			if($this->matchcode['__active_top_bar_page'][0] == true && !$p_edit)
			{
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'_lisha_header_page_selection" id="lisha_header_page_selection_'.$this->c_id.'">';
				$lisha .= $this->generate_page_selection($p_resultat,__HEADER__);
				$lisha .= '</div>';
			}
			//==================================================================

			//==================================================================
			// Column header
			//==================================================================
			$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'__lisha_header__" id="header_'.$this->c_id.'">';
			$lisha .= '<table style="border-collapse:collapse;height:46px;" cellpadding="0" cellspacing="0" id="table_header_'.$this->c_id.'">';
			$lisha .= '<tbody>';
			$lisha .= $this->generate_columns_header($p_edit,$p_resultat,$p_result_header);
			$lisha .= '</tbody>';
			$lisha .= '</table>';
			$lisha .= '</div>';
			//==================================================================

			//==================================================================
			// Create the column header menu
			//==================================================================
			$lisha .= '<div class="column_menu" id="conteneur_menu_'.$this->c_id.'">';
			$lisha .= '<div id="lis_column_header_menu_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_column_header_menu">...</div>';
			$lisha .= '</div>';
			$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'__internal_lisha" id="internal_lisha_'.$this->c_id.'"></div>';
			$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'_calendar" id="lis_column_calendar_'.$this->c_id.'" >AAAA</div>';
			//==================================================================

			//==================================================================
			// Data
			//==================================================================
			$lisha .= '<div id="lisha_table_mask_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_table_mask_'.$this->c_id.'"></div>';
			// Updating row in progress ?
			if(!$p_edit)
			{
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'_lisha_content_'.$this->c_id.'" id="liste_'.$this->c_id.'">';
			}
			else
			{
				// Updating in progress... So, never page bar displayed so use independent css style from page tool bar
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'_lisha_content_updating_'.$this->c_id.'" id="liste_'.$this->c_id.'">';
			}
			$lisha .= '<table style="border-collapse:collapse;" cellpadding="0" cellspacing="0" id="table_liste_'.$this->c_id.'">';
			$lisha .= $this->generate_data_content($p_resultat,$p_edit);
			$lisha .= '</table>';
			$lisha .= '</div>';
			//==================================================================


			//==================================================================
			// Footer page selection
			//==================================================================
			if($this->matchcode['__active_bottom_bar_page'][0] == true  && !$p_edit)
			{
				$lisha .= '<div class="__'.$this->matchcode['__id_theme'][0].'_lisha_footer_page_selection" id="lisha_footer_page_selection_'.$this->c_id.'">';
				$lisha .= $this->generate_page_selection($p_resultat,__FOOTER__);
				$lisha .= '</div>';
			}
			//==================================================================

			//==================================================================
			// End of the lisha container
			//==================================================================
			if($p_ajax_call == null)
			{
				$lisha .= '</div>';
				$lisha .= '</div>';
			}
			//==================================================================

			//==================================================================
			// Javascript focus call
			// FOCUS ISSUE ON MATCHCODE THIS CODE DOESN'T RUN BY BROWSER
			//==================================================================
			if(isset($this->c_default_input_focus_id))
			{
				$tmp = "th_input_".$this->c_default_input_focus_id."__".$this->c_id;
				$lisha .= '<script type="text/javascript">
				function lisha_focus_timer'.$this->c_id.'()
				{
					try
					{
						document.getElementById("'.$tmp.'").focus();
						clearInterval(myVar'.$this->c_id.');
					}
					catch (e)
					{
					}
				}
				var myVar'.$this->c_id.'=setInterval(function(){lisha_focus_timer'.$this->c_id.'()},1000);
				lisha_focus_timer'.$this->c_id.'();
				</script>
				';
			}
			// Return content
			return $lisha;
		}
		/**===================================================================*/


		/**==================================================================
		 * Build dynamic CSS needed for lisha
		 *
		 * @p_bal_style		:	true means add css style
		 ====================================================================*/
		public function generate_style($p_bal_style = true)
		{
			$style = '';

			if($p_bal_style)
			{
				$style .= '<style type="text/css">';
			}

			$style .= $this->generate_lisha_global_style();
			$style .= $this->generate_color_line_style();

			if($p_bal_style)
			{
				$style .= '</style>';
			}

			return $style;
		}
		/**===================================================================*/


		/**==================================================================
		 * Build CSS theme for each Set Of Color line ( call by generate_style )
		 *
		 ====================================================================*/
		private function generate_color_line_style()
		{
			$style = '/* ---------- Begin color line style ---------- */';
			foreach($this->matchcode['__internal_color_mask'][0] as $clef => $valeur)
			{
				foreach($valeur as $key => $value)
				{
					// Unselected
					$style .= '.lc_'.$clef.$key.'_'.$this->c_id;
					$style .= '{';
					$style .= 'background-color:#'.$value['color_code'].';';
					$style .= 'color:#'.$value['color_text'].';';
					$style .= 'font-size:'.$value['size_text'].';';
					$style .= 'font-family: tahoma, arial, helvetica, sans-serif;';
					$style .= '}';

					// Unselected and Hover
					$style .= '.lc_'.$clef.$key.'_'.$this->c_id.':hover';
					$style .= '{';
					$style .= 'background-color:#'.$value['color_hover_code'].';';
					$style .= 'font-size:'.$value['size_hover_code'].';';
					$style .= 'font-family: tahoma, arial, helvetica, sans-serif;';
					$style .= 'cursor:pointer;';
					$style .= '}';

					// Selected
					$style .= '.line_selected_color_'.$clef.$key.'_'.$this->c_id;
					$style .= '{';
					$style .= 'background-color:#'.$value['color_selected_code'].';';
					$style .= 'color:#'.$value['color_text_selected'].';';
					$style .= 'font-size:'.$value['size_selected_code'].';';
					$style .= 'font-family: tahoma, arial, helvetica, sans-serif;';
					$style .= 'cursor:pointer;';
					$style .= '}';

					// Selected and Hover
					$style .= '.line_selected_color_'.$clef.$key.'_'.$this->c_id.':hover';
					$style .= '{';
					$style .= 'background-color:#'.$value['color_hover_selected_code'].';';
					$style .= 'color:#'.$value['color_text_selected'].';';
					$style .= 'font-size:'.$value['size_hover_selected_code'].';';
					$style .= 'font-family: tahoma, arial, helvetica, sans-serif;';
					$style .= 'cursor:pointer;';
					$style .= '}';

					//==================================================================
					// Child CSS part
					//==================================================================
					// Unselected
					$style .= '.lc_'.$clef.$key.'_'.$this->c_id.'_child';
					$style .= '{';
					$style .= 'background-color:#'.$value['color_code'].';';
					$style .= 'color:#'.$value['color_text'].';';
					$style .= 'font-size:'.$value['size_text'].';';
					$style .= 'font-family: tahoma, arial, helvetica, sans-serif;';
					$style .= '}';

					// Unselected Hover
					$style .= '.lc_'.$clef.$key.'_'.$this->c_id.'_child'.':hover';
					$style .= '{';
					$style .= 'background-color:#'.$value['color_hover_code'].';';
					$style .= 'font-size:'.$value['size_hover_code'].';';
					$style .= 'cursor:pointer;';
					$style .= '}';

					// Selected
					$style .= '.line_selected_color_'.$clef.$key.'_'.$this->c_id.'_child';
					$style .= '{';
					$style .= 'background-color:#'.$value['color_selected_code'].';';
					$style .= 'color:#'.$value['color_text_selected'].';';
					$style .= 'font-size:'.$value['size_selected_code'].';';
					$style .= 'font-family: tahoma, arial, helvetica, sans-serif;';
					$style .= 'cursor:pointer;';
					$style .= '}';	

					// Selected Hover
					$style .= '.line_selected_color_'.$clef.$key.'_'.$this->c_id.'_child:hover';
					$style .= '{';
					$style .= 'background-color:#'.$value['color_hover_selected_code'].';';
					$style .= 'color:#'.$value['color_text_selected'].';';
					$style .= 'font-size:'.$value['size_hover_selected_code'].';';
					$style .= 'font-family: tahoma, arial, helvetica, sans-serif;';
					$style .= 'cursor:pointer;';
					$style .= '}';					
				}
			}

			$style .= '/* ---------- End color line style ---------- */';
			return $style;
		}
		/**===================================================================*/


		/**==================================================================
		 * Build main CSS theme of lisha ( red, green, blue, grey... )  ( call by generate_style )
		 *
		 ====================================================================*/
		private function generate_lisha_global_style()
		{
			$style = '#lis__'.$this->matchcode['__id_theme'][0].'__lisha_table_'.$this->c_id.'__';
			$style .= '{';
			$style .= 'width:'.$this->c_width.$this->c_w_unity.';';
			$style .= 'height:'.$this->c_height.$this->c_h_unity.';';
			$style .= 'border:0 solid #777;';
			$style .= 'overflow:hidden;';
			$style .= 'font-size:15px;';

			if($this->c_h_unity == '%')
			{
				$style .= 'position:absolute;';
			}
			else
			{
				$style .= 'position:relative;';
			}

			$style .= '}';																																		

			$style .= '#lis__'.$this->matchcode['__id_theme'][0].'__lisha_table_'.$this->c_id.'_child__';
			$style .= '{';
			$style .= 'width:100%;';
			$style .= 'height:300px;';
			$style .= 'border:0 solid #777;';
			$style .= 'overflow:hidden;';
			$style .= 'position:relative;';
			$style .= '}';																																		

			//==================================================================
			// Build dynamic css background style for body data
			//==================================================================
			$style .= '.__'.$this->matchcode['__id_theme'][0].'_lisha_content_'.$this->c_id;
			$style .= '{';
			$style .= 'width: 100%;';
			$style .= 'overflow-x: auto;';
			$style .= 'overflow-y: auto;';

			switch($this->matchcode['__id_theme'][0])
			{
				case 'red';
					$bg_color = "#fff2f2";
					break;
				case 'grey';
					$bg_color = "#EEEEEE";
					break;
				case 'green';
					$bg_color = "#ebfadc";
					break;
				case 'blue';
					$bg_color = "#CEDDEF";
					break;
				default:
					$bg_color = "#E8E8E8";
			}

			if($this->matchcode['__background_picture'][0] == '')
			{
				$style .= 'background-color: '.$bg_color.';';
			}
			else
			{
				$style .= 'background: '.$bg_color.' url('.$this->matchcode['__background_picture'][0].') '.$this->matchcode['__background_repeat'][0].' center center;';
			}

			if($this->c_h_unity == '%')
			{
				$style .= 'position: absolute;';
				$top_height = 71;
				$bottom_size = 0;
				if($this->matchcode['__active_title'][0]) $top_height += 22;
				if($this->matchcode['__active_top_bar_page'][0]) $top_height += 25;
				$style .= 'top:'.$top_height.'px;';
				if($this->matchcode['__active_bottom_bar_page'][0]) $bottom_size += 25;
				$style .= 'bottom:'.$bottom_size.'px;';
			}
			else
			{
				$style .= 'position: relative;';
				$height_size = $this->c_height - 142;
				if(!$this->matchcode['__active_top_bar_page'][0]) $height_size += 25;
				if(!$this->matchcode['__active_bottom_bar_page'][0]) $height_size += 25;
				if(!$this->matchcode['__active_title'][0]) $height_size += 22;
				$style .= 'height: '.$height_size.$this->c_h_unity.';';
			}

			$style .= '}';
			//==================================================================

			//==================================================================
			// Build dynamic css background style for body data updating mode
			//==================================================================
			$style .= '.__'.$this->matchcode['__id_theme'][0].'_lisha_content_updating_'.$this->c_id;
			$style .= '{';
			$style .= 'width: 100%;';
			$style .= 'overflow-x: auto;';
			$style .= 'overflow-y: auto;';

			switch($this->matchcode['__id_theme'][0])
			{
				case 'red';
					$bg_color = "#fff2f2";
					break;
				case 'grey';
					$bg_color = "#EEEEEE";
					break;
				case 'green';
					$bg_color = "#ebfadc";
					break;
				case 'blue';
					$bg_color = "#CEDDEF";
					break;
				default:
					$bg_color = "#E8E8E8";
			}


			if($this->matchcode['__background_picture'][0] == '')
			{
				$style .= 'background-color: '.$bg_color.';';
			}
			else
			{
				$style .= 'background: '.$bg_color.' url('.$this->matchcode['__background_picture'][0].') '.$this->matchcode['__background_repeat'][0].' center center;';
			}

			if($this->c_h_unity == '%')
			{
				$style .= 'position: absolute;';
				$top_height = 71;
				$bottom_size = 0;
				if($this->matchcode['__active_title'][0]) $top_height += 22;
				//if($this->matchcode['__active_top_bar_page'][0]) $top_height += 25;
				$style .= 'top:'.$top_height.'px;';
				//if($this->matchcode['__active_bottom_bar_page'][0]) $bottom_size += 25;
				$style .= 'bottom:'.$bottom_size.'px;';
			}
			else
			{
				$style .= 'position: relative;';
				$height_size = $this->c_height - 142;
				if(!$this->matchcode['__active_top_bar_page'][0]) $height_size += 25;
				if(!$this->matchcode['__active_bottom_bar_page'][0]) $height_size += 25;
				if(!$this->matchcode['__active_title'][0]) $height_size += 22;
				$style .= 'height: '.$height_size.$this->c_h_unity.';';
			}

			$style .= '}';
			//==================================================================

			//==================================================================
			// Build dynamic css ...
			//==================================================================
			$style .= '.__'.$this->matchcode['__id_theme'][0].'_table_mask_'.$this->c_id;
			$style .= '{';
			$style .= 'width: 100%;';
			$style .= 'overflow-x: auto;';
			$style .= 'overflow-y: auto;';
			$style .= 'z-index: 1;';
			$style .= 'display: none;';

			if($this->c_h_unity == '%')
			{
				$style .= 'position: absolute;';
				$top_height = 71;
				$bottom_size = 0;
				if($this->matchcode['__active_title'][0]) $top_height += 22;
				if($this->matchcode['__active_top_bar_page'][0]) $top_height += 25;
				$style .= 'top:'.$top_height.'px;';
				if($this->matchcode['__active_bottom_bar_page'][0]) $bottom_size += 25;
				$style .= 'bottom:'.$bottom_size.'px;';
			}
			else
			{
				$style .= 'position: relative;';
				$height_size = $this->c_height - 142;
				if(!$this->matchcode['__active_top_bar_page'][0]) $height_size += 25;
				if(!$this->matchcode['__active_bottom_bar_page'][0]) $height_size += 25;
				if(!$this->matchcode['__active_title'][0]) $height_size += 22;
				$style .= 'height: '.$height_size.$this->c_h_unity.';';
			}

			$style .= '}';
			//=================================================================

			//==================================================================
			// Build dynamic css Background style for children
			//==================================================================
			$style .= '.__'.$this->matchcode['__id_theme'][0].'_lisha_content_'.$this->c_id.'_child';
			$style .= '{';
			$style .= 'width: 100%;';
			$style .= 'overflow-x: auto;';
			$style .= 'overflow-y: auto;';

			if($this->matchcode['__background_picture'][0] == '')
			{
				$style .= 'background-color: '.$bg_color.';';
			}
			else
			{
				$style .= 'background: '.$bg_color.' url('.$this->matchcode['__background_picture'][0].') '.$this->matchcode['__background_repeat'][0].' center center;';
			}

			if($this->c_h_unity == '%')
			{
				$top_height_size = 118;
				if(!$this->matchcode['__active_top_bar_page'][0]) $top_height_size -= 25;
				$style .= 'position: absolute;';
				$style .= 'top:'.$top_height_size.'px;';
				$style .= 'bottom:25px;';
			}
			else
			{
				$style .= 'position: relative;';
				$style .= 'height: '.($this->c_height - 143).$this->c_h_unity.';';
			}

			$style .= '}';	
			//==================================================================

			//==================================================================
			// Footer
			//==================================================================
			if($this->c_h_unity == '%')
			{
				$style .= '#lisha_footer_page_selection_'.$this->c_id;
				$style .= '{';
				$style .= 'position: absolute;';
				$style .= 'bottom: 0;';
				$style .= '}';
			}

			$style .= '#lisha_footer_page_selection_'.$this->c_id.'_child';
			$style .= '{';
			$style .= 'position: absolute;';
			$style .= 'bottom: 0;';
			$style .= '}';
			//==================================================================

			return $style;
		}
		/**===================================================================*/


		/**==================================================================
		 * Build navigation page bar
		 *
		 * @p_resultat		:   query result
		 * @p_type			:	__HEADER__ or __FOOTER__
		 ====================================================================*/
		private function generate_page_selection($p_resultat,$p_type)
		{
			$style_ln = '';
			$style_fp = '';

			$class_ln = ' c_pointer';
			$class_fp = ' c_pointer';
			$onclick_fp_first = '';
			$onclick_fp_previous = '';
			$onclick_ln_last = '';
			$onclick_ln_next = '';

			$hover_fp_first = '';
			$hover_fp_previous = '';
			$hover_ln_last = '';
			$hover_ln_next = '';

			if($this->matchcode['__current_page'][0] == 1)
			{
				// first, previous
				$class_fp = ' grey_el';

				if(ceil($this->c_recordset_line / $this->c_nb_line) <= 1)
				{
					// No next or previous page
					$class_ln = ' grey_el';
				}
				else 
				{
					$onclick_ln_last = 'onclick="lisha_page_change_ajax(\''.$this->c_id.'\',__lisha_LAST__);"';
					$onclick_ln_next = 'onclick="lisha_page_change_ajax(\''.$this->c_id.'\',__lisha_NEXT__);"';
					$hover_ln_last = $this->hover_out_lib(15,15);
					$hover_ln_next = $this->hover_out_lib(14,14);
				}
			}
			else
			{ 
				if($this->matchcode['__current_page'][0] == ceil($this->c_recordset_line / $this->c_nb_line))
				{
					$class_ln = ' grey_el';

				}
				else 
				{
					$onclick_ln_last = 'onclick="lisha_page_change_ajax(\''.$this->c_id.'\',__lisha_LAST__);"';
					$onclick_ln_next = 'onclick="lisha_page_change_ajax(\''.$this->c_id.'\',__lisha_NEXT__);"';
					$hover_ln_last = $this->hover_out_lib(15,15);
					$hover_ln_next = $this->hover_out_lib(14,14);
				}
				$onclick_fp_first = 'onclick="lisha_page_change_ajax(\''.$this->c_id.'\',__lisha_FIRST__);"';
				$onclick_fp_previous = 'onclick="lisha_page_change_ajax(\''.$this->c_id.'\',__lisha_PREVIOUS__);"';
				$hover_fp_first = $this->hover_out_lib(12,12);
				$hover_fp_previous = $this->hover_out_lib(13,13);
			}


			$nb_line = $this->c_obj_bdd->rds_num_rows($p_resultat);

			if($this->matchcode['__current_page'][0] == ceil($this->c_recordset_line / $this->c_nb_line))
			{
				$to = $this->c_limit_min + $nb_line;
			}
			else
			{
				$to = ($this->c_nb_line + $this->c_limit_min);
			}

			$to = number_format($to,0,'', htmlentities($_SESSION[$this->c_ssid]['lisha']['thousand_symbol']));

			$onkeyup_page = '';
			$onkeyup_line = '';
			if($p_type == __HEADER__)
			{
				if($this->matchcode['__active_bottom_bar_page'][0])
				{
					$onkeyup_page = 'document.getElementById(\''.$this->c_id.'_page_selection_footer\').value = this.value;';
					$onkeyup_line = 'document.getElementById(\''.$this->c_id.'_line_selection_footer\').value = this.value;';
				}
			}
			else
			{
				if($this->matchcode['__active_top_bar_page'][0])
				{
					$onkeyup_page = 'document.getElementById(\''.$this->c_id.'_line_selection_header\').value = this.value;';
					$onkeyup_line = 'document.getElementById(\''.$this->c_id.'_line_selection_footer\').value = this.value;';
				}
			}

			$html  = '<table class="__'.$this->matchcode['__id_theme'][0].'_table_info" cellpadding="0" cellspacing="0">';
			$html .= '<tr>';
			if($this->c_navbar_nav_button_activate)
			{
				$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar"><div '.$hover_fp_first.' '.$style_fp.' '.$onclick_fp_first.' class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_first __'.$this->matchcode['__id_theme'][0].'_table_page_selection '.$class_fp.'" ></div></td>';
				$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar __'.$this->matchcode['__id_theme'][0].'_infobar_separator_right"><div '.$style_fp.' '.$onclick_fp_previous.' class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_previous __'.$this->matchcode['__id_theme'][0].'_table_page_selection '.$class_fp.'" '.$hover_fp_previous.'></div></td>';
			}

			//==================================================================
			// Compute size of input page index
			//==================================================================
			$nb_page_max = number_format(ceil($this->c_recordset_line / $this->c_nb_line),0,'', htmlentities($_SESSION[$this->c_ssid]['lisha']['thousand_symbol']));
			$str_length_page = strlen($nb_page_max);

			$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar __'.$this->matchcode['__id_theme'][0].'_infobar_separator_right __'.$this->matchcode['__id_theme'][0].'_infobar_separator_left"> '.$this->lib(22).' <input id="'.$this->c_id.'_page_selection_'.$p_type.'" class="__'.$this->matchcode['__id_theme'][0].'_input_text __'.$this->matchcode['__id_theme'][0].'__input_h" onkeyup="'.$onkeyup_page.'lisha_input_page_change(event,\''.$this->c_id.'\',this);" type="text" size='.$str_length_page.' value="'.$this->matchcode['__current_page'][0].'"/> '.$this->lib(23).' '.$nb_page_max.'</td>';
			//==================================================================

			if($this->c_navbar_nav_button_activate)
			{
				$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar __'.$this->matchcode['__id_theme'][0].'_infobar_separator_left"><div '.$style_ln.' '.$onclick_ln_next.' class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_next __'.$this->matchcode['__id_theme'][0].'_table_page_selection '.$class_ln.'" '.$hover_ln_next.'></div></td>';
				$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar __'.$this->matchcode['__id_theme'][0].'_infobar_separator_right"><div '.$style_ln.' '.$onclick_ln_last.' class="__'.$this->matchcode['__id_theme'][0].'_table_page_selection '.$class_ln.' __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_last" '.$hover_ln_last.'></div></td>';
			}
			$str_length_page = strlen($this->c_nb_line)+1;
			$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar __'.$this->matchcode['__id_theme'][0].'_infobar_separator_right __'.$this->matchcode['__id_theme'][0].'_infobar_separator_left"><input id="'.$this->c_id.'_line_selection_'.$p_type.'" type="text" onkeyup="'.$onkeyup_line.'lisha_input_line_per_page_change(event,\''.$this->c_id.'\',this);" value="'.$this->c_nb_line.'" size="'.$str_length_page.'" class="__'.$this->matchcode['__id_theme'][0].'_input_text __'.$this->matchcode['__id_theme'][0].'__input_h"/> ';
			if($this->c_navbar_txt_line_per_page_activate)
			{
				$html .= $this->lib(24);
			}

			$html .= '</td>';
			if($this->c_navbar_refresh_button_activate)
			$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar __'.$this->matchcode['__id_theme'][0].'_infobar_separator_left __'.$this->matchcode['__id_theme'][0].'_infobar_separator_right"><div onclick="lisha_refresh_page_ajax(\''.$this->c_id.'\');" class="c_pointer __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_refresh" '.$this->hover_out_lib(11,11).'></div></td>';

			if($this->matchcode['__display_mode'][0] != __CMOD__ && $this->c_navbar_txt_activate)
			{
				$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_infobar __'.$this->matchcode['__id_theme'][0].'_infobar_separator_left">'.$this->lib(25).' : '.number_format(($this->c_limit_min + 1),0,'', htmlentities($_SESSION[$this->c_ssid]['lisha']['thousand_symbol'])).' '.$this->lib(26).' '.$to.' '.$this->lib(27).' '.number_format($this->c_recordset_line,0,'', htmlentities($_SESSION[$this->c_ssid]['lisha']['thousand_symbol'])).' '.$this->lib(28).' ';

				if($this->matchcode['__current_page'][0] == ceil($this->c_recordset_line / $this->c_nb_line) && ceil($this->c_recordset_line / $this->c_nb_line) > 1)
					$html .= str_replace('$x',$nb_line,$this->lib(29));

				$html .= '</td>';
			}
			$html .= '</tr>';
			$html .= '</table>';

			return $html;
		}
		/**===================================================================*/


		/**==================================================================
		 * Generate columns header
		 * @p_edit			: false if not in edit mode, true in other case
		 * @p_resultat		: database results
		 * @p_result_header : ???
		 ====================================================================*/
		private function generate_columns_header($p_edit,&$p_resultat,&$p_result_header)
		{
			// Create a new line
			$html = '<tr id="tr_header_title_'.$this->c_id.'">';

			//==================================================================
			// Create the first column (checkbox and edit button)
			//==================================================================
			$html .= '<td align="left" id="header_th_0__'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'__cell_opt_h"><div id="th0_'.$this->c_id.'">';

			if(1==1) // Display lisha version or not todo
			{
				// Create the first column (checkbox and edit button)
				$html .= '<div id="thf0_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'__lisha_version" ';
				if($this->matchcode['__active_ticket'][0])
				{
					$html .= ' style="cursor: pointer;" '.$this->hover_out_lib(79,79).' onclick="window.open(\''.$this->c_dir_obj.'/bugs\');"';
				}
				$html .= '>'.$this->c_software_version.'</div>';
			}

			$html .= '</div></td><td></td>';
			//==================================================================

			// Quantify of order clause
			$qtt_order = $this->get_nbr_order();

			//==================================================================
			// Display the resize cursor or not
			//==================================================================
			if($this->matchcode['__display_mode'][0] != __CMOD__)
			{
				$cursor = ' cur_resize';
			}
			else
			{
				$cursor = '';
			}
			//==================================================================

			$ondblclick = '';
			$onmousedown = '';

			//==================================================================
			// Browse all columns
			//==================================================================
			foreach($this->c_columns as $key_col => $val_col)
			{
				if($val_col['display'])
				{
					//==================================================================
					// Define order icon
					//==================================================================
					if($this->c_columns[$key_col]['order_by'] != false)
					{
						// An order clause is defined
						if($this->c_columns[$key_col]['order_by'] == __ASC__)
						{
							// ASC icon
							$class_order = ' __'.$this->matchcode['__id_theme'][0].'_ico_sort-ascend __'.$this->matchcode['__id_theme'][0].'_ico';
						}
						else 
						{
							// DESC icon
							$class_order = ' __'.$this->matchcode['__id_theme'][0].'_ico_sort-descend __'.$this->matchcode['__id_theme'][0].'_ico';
						}

						// Display the number of order only if there is more than one order clause
						($qtt_order > 1) ? $order_prio = $this->c_columns[$key_col]['order_priority'] : $order_prio = '';
					}
					else
					{
						// No order clause defined
						$class_order = '';
						$order_prio = '';
					}
					//==================================================================

					// Order column
					$html .= '<td class="__'.$this->matchcode['__id_theme'][0].'_bloc_empty'.$class_order.'"><span class="__lisha_txt_mini_ lisha_txt_top">'.$order_prio.'</span></td>';

					//==================================================================
					// Define order icon
					//==================================================================
					if($this->matchcode['__display_mode'][0] != __CMOD__)
					{
						$onmousedown = 'onmousedown="lisha_resize_column_start('.$key_col.',\''.$this->c_id.'\');"';
						$ondblclick = 'ondblclick="lisha_mini_size_column('.$key_col.',\''.$this->c_id.'\');" ';
						$lib_redim = $this->hover_out_lib(17,17);

						if($p_edit)
						{
							// No columns move available when updating rows
							$event = '';
						}
						else
						{
							$event = $this->hover_out_lib(40,76).' onmousedown="lisha_move_column_start(event,'.$key_col.',\''.$this->c_id.'\');"';
						}
					}
					else
					{
						$onmousedown = '';
						$ondblclick = '';
						$lib_redim = '';

						if($p_edit)
						{
							// No columns move available when updating rows
							$event = '';
						}
						else
						{
							$event = $this->hover_out_lib(40,76).' '.'onmousedown="click_column_order(\''.$this->c_id.'\','.$key_col.');"';
						}
					}
					//==================================================================

					$watermark = '';
					// Cell edition only on non __FORBIDDEN__ column
					if(isset($val_col["rw_flag"]) && $val_col["rw_flag"] == __FORBIDDEN__ || $val_col['sql_as'] == $this->matchcode['__column_name_group_of_color'][0])
					{
						// limited action
						// Display mode ??
						if(!$this->matchcode['__active_readonly_mode'][0])
						{
							$watermark = 'style="opacity:	0.6;"';
						}
						else
						{
							$watermark = '';
						}
					}
					//==================================================================

					//==================================================================
					// Column title
					//==================================================================
					switch($val_col['search_mode'])
					{
						case __EXACT__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_equal_operator_little';
							break;
						case __CONTAIN__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_contain_operator_little';
							break;
						case __PERCENT__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_like_operator_little';
							break;
						case __GT__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_greather_than_operator_little';
							break;
						case __LT__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_less_than_operator_little';
							break;
						case __GE__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_greather_or_equal_than_operator_little';
							break;
						case __LE__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_less_or_equal_than_operator_little';
							break;
						case __NULL__:
							$operator_class = '__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_null_operator_little';
							break;
					}
					$ope_div = '<span class="lisha_operator_title '.$operator_class.'"></span>';

					$html .= '<td align="left" class="__'.$this->matchcode['__id_theme'][0].'__cell_h nowrap" id="header_th_'.$key_col.'__'.$this->c_id.'"><div '.$event.' class="align_'.$this->c_columns[$key_col]['alignment'].' __'.$this->matchcode['__id_theme'][0].'_column_title" id="th'.$key_col.'_'.$this->c_id.'" '.$watermark.'><span id="span_'.$key_col.'_'.$this->c_id.'">'.$this->c_columns[$key_col]['name'].$ope_div.'</span></div></td>';
					//==================================================================

					//==================================================================
					// Display other column
					//==================================================================
					if($p_edit != false) $html .= '<td style="padding:0;margin:0;"></td>';

					$html .= '<td '.$lib_redim.' '.$ondblclick.' '.$onmousedown.' class="__'.$this->matchcode['__id_theme'][0].'__cell_h_resizable'.$cursor.'"><div class="__'.$this->matchcode['__id_theme'][0].'__cell_resize"></div></td>';
					$html .= '<td '.$lib_redim.' '.$ondblclick.' '.$onmousedown.' class="__'.$this->matchcode['__id_theme'][0].'__sep_h'.$cursor.'"></td>';
					$html .= '<td id="right_mark_'.$key_col.'_'.$this->c_id.'" '.$lib_redim.' '.$ondblclick.' '.$onmousedown.' class="__'.$this->matchcode['__id_theme'][0].'__cell_h_resizable'.$cursor.'"><div class="__'.$this->matchcode['__id_theme'][0].'__cell_resize"></div></td>';
					//==================================================================
				}
			}
			//==================================================================

			$html .= '</tr>';
			//==================================================================
			// Input for search on the column
			//==================================================================
			// Create a new line
			$html .= '<tr id="tr_header_input_'.$this->c_id.'">';

			// Create the first column (checkbox and edit button)
			$html .= '<td align="left" class="__'.$this->matchcode['__id_theme'][0].'__cell_opt_h"><div id="thf0_'.$this->c_id.'" ></div></td>';

			// Id column display counter
			$id_col_display = 0;

			//==================================================================
			// Browse all columns
			//==================================================================
			foreach($this->c_columns as $key_col => $val_col)
			{
				if($val_col['display'])
				{
					if($id_col_display == 0)
					{
						$html .= '<td id="th_0_c'.$key_col.'_'.$this->c_id.'"></td>';
					}
					else 
					{
						$html .= '<td id="th_0_c'.($key_col).'_'.$this->c_id.'" '.$this->hover_out_lib(17,17).' '.$ondblclick.' '.$onmousedown.' class="__'.$this->matchcode['__id_theme'][0].'__cell_h_resizable'.$cursor.'"><div class="__'.$this->matchcode['__id_theme'][0].'__cell_resize"></div></td>';
					}

					$onmousedown = 'onmousedown="lisha_resize_column_start('.$key_col.',\''.$this->c_id.'\');"';
					$ondblclick = 'ondblclick="lisha_mini_size_column('.$key_col.',\''.$this->c_id.'\');" ';

					//==================================================================
					// Define the filter value
					//==================================================================
					$filter_input_value = '';
					$state_filter_input = '';

					if(isset($val_col['filter']['input']))
					{
						// A filter was defined by the user
						if($val_col['data_type'] == 'date')
						{
							$filter_input_value = $val_col['filter']['input']['filter_display'];
						}
						else
						{
							$filter_input_value = $val_col['filter']['input']['filter'];
						}
					}
					else
					{
						// No filter was defined by the user

						// Check if lisha was in edit mode
						if(
							$p_edit != false
							&& !isset($val_col['rw_flag'])
							&& $val_col['sql_as'] != $this->matchcode['__column_name_group_of_color'][0]
							|| ($p_edit != false && isset($val_col['rw_flag']) && $val_col['rw_flag'] != __FORBIDDEN__ && $val_col['sql_as'] != $this->matchcode['__column_name_group_of_color'][0])
						  )
						{
							// The lisha was in edit mode, search all same value in the column

							// Place the cursor on the first row of the recordset
							if($this->c_obj_bdd->rds_num_rows($p_result_header) > 0)
							{
								$this->c_obj_bdd->rds_data_seek($p_result_header,0);
							}
							// TODO Use a DISTINCT QUERY - lisha 1.0
							$key_cold_line = 0;
							$last_value = '';
							$flag_same = false;

							while($row = $this->c_obj_bdd->rds_fetch_array($p_result_header))
							{
								if($key_cold_line > 0)
								{
									if($last_value == $row[$val_col['sql_as']])
									{
										$flag_same = true;
									}
									else
									{
										$flag_same = false;
										// The value is not the same of the previous, stop browsing data 
										break;
									}
								}
								else
								{
									$flag_same = true;
								}

								$last_value = $row[$val_col['sql_as']];
								$key_cold_line = $key_cold_line + 1;
							}

							if($flag_same)
							{
								$filter_input_value = $last_value;
							}
							else 
							{
								$filter_input_value = '';
							}
						}
						else 
						{
							if(
								$p_edit != false
								&& (isset($val_col['rw_flag']) || $val_col['sql_as'] == $this->matchcode['__column_name_group_of_color'][0])
								&& (isset($val_col['rw_flag']) && $val_col['rw_flag'] == __FORBIDDEN__ || $val_col['sql_as'] == $this->matchcode['__column_name_group_of_color'][0] ) 
							  )
							{
								$state_filter_input = 'disabled';									// Disable the input because edition is forbidden
							}
						}
					}
					//==================================================================

					if(isset($val_col['filter']))
					{
						$class_btn_menu = '__'.$this->matchcode['__id_theme'][0].'_menu_header_on __'.$this->matchcode['__id_theme'][0].'_men_head';
					}
					else 
					{
						if(isset($val_col['lov']) && isset($val_col['is_lovable']) && $val_col['is_lovable'] == true)
						{
							$class_btn_menu = '__'.$this->matchcode['__id_theme'][0].'_menu_header_lovable __'.$this->matchcode['__id_theme'][0].'_men_head';
						}
						else
						{
							if(isset($val_col['lov']))
							{
								$class_btn_menu = '__'.$this->matchcode['__id_theme'][0].'_menu_header_no_icon __'.$this->matchcode['__id_theme'][0].'_men_head';
							}
							else
							{
								$class_btn_menu = '__'.$this->matchcode['__id_theme'][0].'_menu_header __'.$this->matchcode['__id_theme'][0].'_men_head';
							}
						}
					}

					//==================================================================
					// Menu button oncontextmenu
					//==================================================================
					if($this->c_type_internal_lisha == false)
					{
						// Principal lisha, display internal lisha
						$oncontextmenu = 'lisha_display_internal_lis(\''.$this->c_id.'\',__POSSIBLE_VALUES__,'.$key_col.');return false;';
					}
					else
					{
						// Internal lisha, doesn't display other internal lisha
						$oncontextmenu = 'return false;';
					}
					//==================================================================

					$html .= '<td id="th_1_c'.$key_col.'_'.$this->c_id.'" class="__lisha_unselectable" style="width:20px;"><div style="width:20px;margin:0;" '.$this->hover_out_lib(21,78).' oncontextmenu="'.$oncontextmenu.'" class="'.$class_btn_menu.'" onclick="lisha_toggle_header_menu(\''.$this->c_id.'\','.$key_col.',\''.$this->matchcode['__active_user_doc'][0].'\');" id="th_menu_'.$key_col.'__'.$this->c_id.'"></div></td>';


					$html .= '<td id="th_2_c'.$key_col.'_'.$this->c_id.'" align="left" class="__'.$this->matchcode['__id_theme'][0].'__cell_h">';


					// Add quick way to open calendar
					if($val_col['data_type'] == 'date')
					{
						$contextual_input = 'oncontextmenu="lisha_generate_calendar(\''.$this->c_id.'\','.$key_col.');return false;"';
					}
					else
					{
						$contextual_input = 'return false;';
					}

					// Active or not quick search on change into input text box area
					// Event call when get/lost focus on inpiut box
					if($this->matchcode['__active_quick_search'][0])
					{
						$onchange = 'onchange="lisha_col_input_change(\''.$this->c_id.'\','.$key_col.');"';
					}
					else
					{
						$onchange = '';
					}

					$html .= '<div style="margin:0 3px;"><input value="'.str_replace('"','&quot;',$filter_input_value).'" class="__'.$this->matchcode['__id_theme'][0].'__input_h full_width" '.$state_filter_input.' id="th_input_'.$key_col.'__'.$this->c_id.'" '.$contextual_input.' type="text" style="margin: 2px 0;" size=1 onkeyup="lisha_input_keyup_pressed(event)" onkeydown="lisha_input_keydown_pressed(event,\''.$this->c_id.'\','.$key_col.',\''.$this->matchcode['__active_quick_search'][0].'\',\''.$p_edit.'\');" onkeypress="if(document.getElementById(\'chk_edit_c'.$key_col.'_'.$this->c_id.'\') && event.keyCode != 13){document.getElementById(\'chk_edit_c'.$key_col.'_'.$this->c_id.'\').checked=true;};lisha_input_keydown(event,this,\''.$this->c_id.'\','.$key_col.',\''.$this->matchcode['__active_quick_search'][0].'\',\''.$p_edit.'\');"'.$onchange.'/></div>';


					if($p_edit != false)
					{
						if($state_filter_input == '' && $val_col['sql_as'] != $this->matchcode['__column_name_group_of_color'][0])
						{
							$html .= '<td style="width:10px;padding:0;margin:0;"><input '.$this->hover_out_lib(76,76).' type="checkbox" id="chk_edit_c'.$key_col.'_'.$this->c_id.'" style="height:11px;width:11px;margin: 0 5px 0 2px;display:block;"/></td>';
						}
						else
						{
							$html .= '<td style="width:0;padding:0;margin:0;"></td>';
						}
					}

					$html .= '</td>';
					$html .= '<td id="th_3_c'.$key_col.'_'.$this->c_id.'" '.$this->hover_out_lib(17,17).' '.$ondblclick.' '.$onmousedown.' class="__'.$this->matchcode['__id_theme'][0].'__cell_h_resizable'.$cursor.'"><div class="__'.$this->matchcode['__id_theme'][0].'__cell_resize"></div></td>';
					$html .= '<td id="th_4_c'.$key_col.'_'.$this->c_id.'" '.$this->hover_out_lib(17,17).' '.$ondblclick.' '.$onmousedown.' class="__'.$this->matchcode['__id_theme'][0].'__sep_h'.$cursor.'"></td>';
				}
				$id_col_display = $id_col_display + 1;
			}
			//==================================================================

			$html .= '<td id="th_0_c'.($id_col_display+1).'_'.$this->c_id.'" '.$this->hover_out_lib(17,17).' '.$ondblclick.' '.$onmousedown.' class="__'.$this->matchcode['__id_theme'][0].'__cell_h_resizable'.$cursor.'"><div class="__'.$this->matchcode['__id_theme'][0].'__cell_resize"></div></td>';
			$html.= '<td><div style="width:200px"></div></td>';
			$html .= '</tr>';

			// Place the cursor on the first row of the recordset
			if($this->c_obj_bdd->rds_num_rows($p_resultat) > 0)
			{
				$this->c_obj_bdd->rds_data_seek($p_resultat,0);
			}

			return $html;
		}
		/**===================================================================*/


		/**==================================================================
		 * generate_data_content
		 * Generate lisha line
		 * @p_resultat			: main lisha query results
		 * @p_edit				: edit mode = true
		 ====================================================================*/
		private function generate_data_content($p_resultat,$p_edit)
		{
			// Flag for the line color
			$i_color = 0;
			$current_group_value = null;

			// Line counter
			$line = 1;

			// lisha content
			$lisha = '';

			// Quantity of rows
			$num_rows = $this->c_recordset_line;

			// Last displayed column
			$last_display_col = $this->get_last_display_column();

			//==================================================================
			// Define the columns & rows style
			//==================================================================
			($this->matchcode['__active_column_separation'][0]) ? $sep_column = '__'.$this->matchcode['__id_theme'][0].'_col_sep_on' : $sep_column = '__'.$this->matchcode['__id_theme'][0].'_col_sep_off';
			($this->matchcode['__active_column_separation'][0]) ? $sep_column_end = '__'.$this->matchcode['__id_theme'][0].'_col_sep_end_on' : $sep_column_end = '__'.$this->matchcode['__id_theme'][0].'_col_sep_end_off';
			($this->matchcode['__active_row_separation'][0]) ? $sep_cell = '__'.$this->matchcode['__id_theme'][0].'_cell_sep' : $sep_cell = '';
			//==================================================================

			// Parsing sql result
			// Move to right line
			while($row = $this->c_obj_bdd->rds_fetch_array($p_resultat))
			{
				// Read current row
				/*$row = $this->c_obj_bdd->rds_fetch_array($p_resultat);
				if(!$row)
				{
					// No more record
					// Todo
					// ...
					//$this->c_page_qtt_line = $i;
				}
			   */
				// Manages the end of line, add a cell_sep if it is the last line
				if($num_rows == $line) $sep_cell = '__'.$this->matchcode['__id_theme'][0].'_cell_sep';

				//==================================================================
				// Build group color
				//==================================================================

				// Keep last group in memory
				$last_group = $current_group_value;

				if(isset($row[$this->matchcode['__column_name_group_of_color'][0]]))
				{
					$current_group_value = $row[$this->matchcode['__column_name_group_of_color'][0]];
					if(!isset($this->matchcode['__internal_color_mask'][0][$current_group_value]))
					{
						// Ok, you try to use group color with no serie of color defined, so back to default group 0
						$current_group_value = 0;
					}
				}
				else
				{
					$current_group_value = 0;
				}

				// Change group color detected
				// So, reset color counter
				if($last_group != $current_group_value)
				{
					$i_color = 0;
				}

				// Cound total lines in current group
				if(isset($this->matchcode['__internal_color_mask'][0][$current_group_value]))
				{
					$max_line_in_group = count($this->matchcode['__internal_color_mask'][0][$current_group_value]);
				}
				else
				{
					// This group color value doesn't exist
					$max_line_in_group = 1;
				}

				if($i_color > $max_line_in_group - 1)
				{
					$i_color = 0;
				}
				//==================================================================

				//==================================================================
				// Line selected management
				//==================================================================
				if(is_array($this->c_selected_lines['key_concat']) && in_array($row['lisha_internal_key_concat'],$this->c_selected_lines['key_concat']))
				{
					// The line is selected
					$line_selected_class = 'line_selected_color_'.$current_group_value.$i_color.'_'.$this->c_id;
					$checked = 'checked';
				}
				else
				{
					// The line isn't selected
					$line_selected_class = 'lc_'.$current_group_value.$i_color.'_'.$this->c_id;
					$checked = '';
				}
				//==================================================================

				//==================================================================
				// Create a new line
				//==================================================================
				if(!$this->c_type_internal_lisha)
				{
					// Principal lisha
					if(!$p_edit)
					{
						$lisha .= '<tr onclick="lisha_checkbox('.$line.',event,null,\''.$this->c_id.'\');" id="l'.$line.'_'.$this->c_id.'" class="'.$line_selected_class.'">';
					}
					else
					{
						$lisha .= '<tr id="l'.$line.'_'.$this->c_id.'" class="'.$line_selected_class.'">';
					}
				}
				else
				{
					// Internal lisha
					switch($this->c_type_internal_lisha) 
					{
						case '__POSSIBLE_VALUES__':
							$lisha .= '<tr onclick="lisha_child_insert_into_parent(\'div_td_l'.$line.'_c'.$this->get_id_col_lov($this->matchcode['__return_column_id'][0]).'_'.$this->c_id.'\',\''.$this->c_id_parent.'\','.$this->c_id_parent_column.');" id="l'.$line.'_'.$this->c_id.'" class="lc_'.$current_group_value.$i_color.'_'.$this->c_id.'">';
							break;
						case '__ADV_FILTER__':
							$lisha .= '<tr id="l'.$line.'_'.$this->c_id.'" class="lc_'.$current_group_value.$i_color.'_'.$this->c_id.'">';
							break;
						case '__LOAD_FILTER__':
							$lisha .= '<tr onclick="lisha_load_filter(\''.$this->c_id_parent.'\',\'div_td_l'.$line.'_c'.$this->get_id_col_lov($this->matchcode['__return_column_id'][0]).'_'.$this->c_id.'\');" id="l'.$line.'_'.$this->c_id.'" class="lc_'.$current_group_value.$i_color.'_'.$this->c_id.'" >';
							break;
						case '__COLUMN_LIST__':
							if($p_edit)
							{
								$lisha .= '<tr id="l'.$line.'_'.$this->c_id.'" class="lc_'.$current_group_value.$i_color.'_'.$this->c_id.'">';
							}
							else
							{
								$lisha .= '<tr onclick="lisha_checkbox('.$line.',event,null,\''.$this->c_id.'\');" id="l'.$line.'_'.$this->c_id.'" class="lc_'.$current_group_value.$i_color.'_'.$this->c_id.'">';
							}
							break;
					}
				}
				//==================================================================

				//==================================================================
				// Create the first column (checkbox and edit button)
				//==================================================================
				$lisha .= '<td align="left" id="td_l'.$line.'_c0_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'__cell_opt '.$sep_cell.'"><div id="div_td_l'.$line.'_c0_'.$this->c_id.'"><table><tr>';
				if(!$p_edit)
				{
					$lisha .= '<td><input class="lisha_checkbox" '.$checked.' onclick="lisha_checkbox('.$line.',event,true,\''.$this->c_id.'\');" id="chk_l'.$line.'_c0_'.$this->c_id.'" type="checkbox" '.$this->hover_out_lib(77,77).'/></td>';
				}
				else
				{
					$lisha .= '<td></td>';
				}

				// If the lisha is in read & write mode, add the edit button
				if($this->matchcode['__active_readonly_mode'][0] == __RW__ && !$p_edit)
				{
					$lisha .= '<td style="padding:0;vertical-align:middle;"><div '.$this->hover_out_lib(16,16).' onclick="edit_lines(event,'.$line.',\''.$this->c_id.'\');" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_page_edit"></div></td>';
				}
				else
				{
					$lisha .= '<td style="padding:0;vertical-align:middle;"><div '.$this->hover_out_lib(16,16).' onclick="edit_lines(event,'.$line.',\''.$this->c_id.'\');" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_page_edit" style="visibility:hidden;"></div></td>';
				}

				$lisha .= '</tr></table></div></td>';
				//==================================================================

				//==================================================================
				// Create all data columns
				//==================================================================
				foreach($this->c_columns as $key_col => $val_col)
				{
					if($val_col['display'])
					{
						($line == 1) ? $key_cold_l = 'id="td_0_c'.$key_col.'_'.$this->c_id.'"' : $key_cold_l = '';


						$lisha .= '<td '.$key_cold_l.' class="'.$sep_cell.$this->c_columns[$key_col]['nowrap'].'"><div class="__'.$this->matchcode['__id_theme'][0].'__cell_resize"></div></td>';

						$content = $this->get_data_in_html($key_col,$row[$this->c_columns[$key_col]['sql_as']]);
						// Data column
						if($line == 1)
						{
							$key_cold_id_l1 = 'id="td_1_c'.$key_col.'_'.$this->c_id.'"';
							$key_cold_id_l2 = 'id="td_2_c'.$key_col.'_'.$this->c_id.'"';
							$key_cold_id_l3 = 'id="td_3_c'.$key_col.'_'.$this->c_id.'"';
						}
						else
						{
							$key_cold_id_l1 = '';
							$key_cold_id_l2 = '';
							$key_cold_id_l3 = '';
						}

						//==================================================================
						// Column format data type
						//==================================================================
						if($this->c_columns[$key_col]["data_type"] == __CHECKBOX__ )
						{
							if($content == 0)
							{
								$content = '<input type="checkbox"';
							}
							else
							{
								$content = '<input type="checkbox" checked';
							}

							// Disable checkbox if you are currently updating set of rows
							// Disable checkbox if cell update disable
							if($p_edit || $this->matchcode['__active_read_only_cells_edit'][0] || $this->matchcode['__active_readonly_mode'][0])
							{
								$content .= ' DISABLED>';
							}
							else
							{
								$content .= '>';
							}
						}

						// for __INT__ or __FLOAT__
						// if $content is null, don't display 0,00 or 0
						if($content)
						{
							if($this->c_columns[$key_col]["data_type"] == __INT__)
							{
								$content = number_format($content,0,$_SESSION[$this->c_ssid]['lisha']['decimal_symbol'],''.$_SESSION[$this->c_ssid]['lisha']['thousand_symbol']);
							}

							if($this->c_columns[$key_col]["data_type"] == __FLOAT__)
							{
								if(isset($this->c_columns[$key_col]['number_of_decimal']))
								{
									$content = number_format($content,$this->c_columns[$key_col]['number_of_decimal'],$_SESSION[$this->c_ssid]['lisha']['decimal_symbol'],''.$_SESSION[$this->c_ssid]['lisha']['thousand_symbol']);
								}
								else
								{
									$content = number_format($content,$_SESSION[$this->c_ssid]['lisha']['number_of_decimal'],$_SESSION[$this->c_ssid]['lisha']['decimal_symbol'],''.$_SESSION[$this->c_ssid]['lisha']['thousand_symbol']);
								}
							}
						}
						//==================================================================

						//==================================================================
						// Cell edition
						//==================================================================
						$edit_cell = '';
						// Cell edition only on non __FORBIDDEN__ column
						if(
							isset($this->c_columns[$key_col]["rw_flag"])
							&& $val_col["rw_flag"] == __FORBIDDEN__
							|| $val_col['sql_as'] == $this->matchcode['__column_name_group_of_color'][0]
							|| $p_edit
						  )
						{
							// limited action
							// Display mode ??
							if(!$this->matchcode['__active_readonly_mode'][0])
							{
								$watermark = 'style="opacity:	0.6;"';
							}
							else
							{
								$watermark = '';
							}
							$edit_cell = '';
						}
						else
						{
							// OK for cell edition
							$watermark = '';

							// Compel on column
							if(!isset($this->c_columns[$key_col]["rw_flag"]))
							{
								$this->c_columns[$key_col]["rw_flag"] = __NONE__;
							}

							// Currently forbidden any cell update if __LISTED__ compel on column
							if($this->matchcode['__active_readonly_mode'][0] == __RW__ && !$p_edit && !$this->matchcode['__active_read_only_cells_edit'][0] && $this->c_columns[$key_col]["rw_flag"] != __LISTED__)
							{
								// Do not read stat of cell update button if sub lisha
								if(isset($this->c_id_parent))
								{
									$edit_cell = 'onclick=lisha_StopEventHandler(event);edit_cell(event,\''.$line.'\',\''.$key_col.'\',\''.$this->c_id.'\',\''.$this->c_columns[$key_col]["data_type"].'\');';
								}
								else
								{
									// Get direct stat of direct cell button to enable or disable direct update
									$edit_cell = 'onclick="lisha_StopEventHandler(event);if(document.getElementById(\'lisha_td_toolbar_cells_'.$this->c_id.'\').className.substr(-2) == \'on\'){edit_cell(event,\''.$line.'\',\''.$key_col.'\',\''.$this->c_id.'\',\''.$this->c_columns[$key_col]["data_type"].'\');}"';
								}
							}
						}
						//==================================================================

						// if no visible HTML content
						// then click on whole <td>
						// TODO Manage invisible HTML string
						$my_tmp = str_replace(' ','',$content); // Work around about blank string
						if(strlen($content) == 0 || strlen($my_tmp) == 0)
						{
							$lisha .= '<td '.$edit_cell.$key_cold_id_l1.' align="'.$this->c_columns[$key_col]['alignment'].'"  class="__'.$this->matchcode['__id_theme'][0].'__cell '.$sep_cell.''.$this->c_columns[$key_col]['nowrap'].'"><div id="div_td_l'.$line.'_c'.$key_col.'_'.$this->c_id.'" class="div_content" '.$watermark.'>'.$content.'</div></td>';
						}
						else
						{
							$lisha .= '<td '.$key_cold_id_l1.' align="'.$this->c_columns[$key_col]['alignment'].'"  class="__'.$this->matchcode['__id_theme'][0].'__cell '.$sep_cell.''.$this->c_columns[$key_col]['nowrap'].'"><div id="div_td_l'.$line.'_c'.$key_col.'_'.$this->c_id.'" class="div_content" '.$watermark.'><span '.$edit_cell.'>'.$content.'</span></div></td>';
						}

						// Right border column
						$lisha .= '<td class="'.$sep_cell.$this->c_columns[$key_col]['nowrap'].'" '.$key_cold_id_l2.'><div class="__'.$this->matchcode['__id_theme'][0].'__cell_resize"></div></td>';

						// Sep column
						if($key_col != $last_display_col)
						{
							$lisha .= '<td '.$key_cold_id_l3.' class="__'.$this->matchcode['__id_theme'][0].'__sep '.$sep_column.'"></td>';
						}
						else 
						{
							$lisha .= '<td '.$key_cold_id_l3.' class="__'.$this->matchcode['__id_theme'][0].'__sep '.$sep_column_end.'"></td>';
						}
					}
				}
				//==================================================================

				$lisha .= '</tr>';

				$line = $line + 1;
				$i_color = $i_color + 1;
			}

			return $lisha;
		}
		/**===================================================================*/


		/**==================================================================
		 * Get the data in html format
		 *
		 * @p_column	: Column identifier
		 * @p_data		: data to display in column cell
		====================================================================*/
		private function get_data_in_html($p_column,$p_data)
		{
			switch($this->c_columns[$p_column]['data_type'])
			{
				case __BBCODE__:
                    $p_data = str_replace(' ','&nbsp;',$p_data);
					return $this->convertBBCodetoHTML($p_data);
					break;
                case __TEXT__:
                    $p_data = str_replace(' ','&nbsp;',$p_data);
                case __RAW__:
                    // No transformation, no translation.. Raw data as recorded
				default:
                    // Undefined type means no transformation ( like __RAW__ format )
					return $p_data;
					break;
			}
		}
		/**===================================================================*/


		/**==================================================================
		 * Get id number of last displayed column
		====================================================================*/
		private function get_last_display_column()
		{
			$last_display_col = 0;

			foreach($this->c_columns as $key_col => $val_col)
			{
				if($val_col['display'])
				{
					$last_display_col = $key_col;
				}
			}

			return $last_display_col;
		}
		/**===================================================================*/


		/**==================================================================
		 * get_id_col_lov
		 * Return column name in query
		====================================================================*/
		private function get_id_col_lov($p_name)
		{
			$i = 1;
			foreach($this->c_columns as $value)
			{
				if($p_name == $value['sql_as'])
				{
					return $i;
				}
				$i = $i + 1;
			}
			// Standard distinct return column always 1
			return 1;
		}
		/**===================================================================*/


		/**==================================================================
		 * Build lisha toolbar
		 *
		 * @$p_edit      : true means edit mode
		 * @$p_resultat  : query result
		====================================================================*/
		public function generate_toolbar($p_edit = false, $p_resultat = false)
		{
			$html  = '<table style="border:0 margin:0;padding:0;height:22px;" cellpadding="0" cellspacing="0">';
			$html .= '<tr style="border:0;">';

			if($this->matchcode['__active_global_search'][0] == true)
			{
				if(count($this->c_columns) > 1)
				{
					if($p_edit == false)
					{
						// Change global search button then fill in
						if($this->string_global_search != "")
						{
							$w_search_icon = '_ico_search_mode_active';
						}
						else
						{
							$w_search_icon = '_ico_search_mode';
						}
						$html .= '<td class="btn_toolbar toolbar_separator_right"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].$w_search_icon.'" '.$this->hover_out_lib(0,57).' onclick="lisha_display_prompt_global_search(\''.$this->c_id.'\',\''.$this->string_global_search.'\');"></div></td>';
					}
					else
					{
						$html .= '<td class="btn_toolbar toolbar_separator_right grey_el"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_search_mode" '.$this->hover_out_lib(0,57).'></div></td>';
					}
				}
			}

			if($this->matchcode['__display_mode'][0] != __CMOD__)
			{
				if($p_edit == false)
				{
					//$html .= '<td class="btn_toolbar toolbar_separator_right"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_column_display" onclick="/*lisha_hide_display_col_lov(\''.$this->c_id.'\',__HIDE_DISPLAY_COLUMN__);*/" '.$this->hover_out_lib(1,1).'></div></td>';
					$html .= '<td class="toolbar_separator_right btn_toolbar"><div id="'.$this->c_id.'_button_columns_list" onclick="list_columns(\''.$this->c_id.'\',__COLUMN_LIST__);" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_col_feat" '.$this->hover_out_lib(1,26).'></div></td>';

					$html .= '<td class="btn_toolbar toolbar_separator_left"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_save_pref" '.$this->hover_out_lib(3,54).' onclick="lisha_display_prompt_create_filter(\''.$this->c_id.'\');"></div></td>';
					if($this->any_filter)
					{
						// Load custom lov available
						$load_lov_grey = '"'.$this->hover_out_lib(4,62).' onclick="lisha_load_filter_lov(\''.$this->c_id.'\',__LOAD_FILTER__);"';
					}
					else
					{
						// Load custom disable
						$load_lov_grey = 'grey_el"'.$this->hover_out_lib(129,62);
					}
					$html .= '<td class="btn_toolbar toolbar_separator_right"><div id="'.$this->c_id.'_button_load_filter" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_load '.$load_lov_grey.'></div></td>';
				}
				else
				{
					$html .= '<td class="btn_toolbar toolbar_separator_left"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_save_pref grey_el" '.$this->hover_out_lib(3,54).'></div></td>';
					$html .= '<td class="btn_toolbar toolbar_separator_right"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_load grey_el" '.$this->hover_out_lib(4,6).'></div></td>';
				}
			}

			if($p_edit)
			{
				$html .= '<td class="toolbar_separator_left toolbar_separator_right btn_toolbar"><div onclick="lisha_cancel_edit(\''.$this->c_id.'\');" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_back" '.$this->hover_out_lib(78,53).'></div></td>';
			}
			else
			{
				$html .= '<td class="toolbar_separator_left btn_toolbar"><div onclick="lisha_reset(\''.$this->c_id.'\');" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_display_table" '.$this->hover_out_lib(5,56).'></div></td>';
			}

			if($this->matchcode['__active_readonly_mode'][0] == __RW__)
			{
				if($this->matchcode['__active_insert_button'][0] == true && $this->c_type_internal_lisha != __LOAD_FILTER__ && $this->c_type_internal_lisha != __COLUMN_LIST__)
				{
					$html .= '<td class="toolbar_separator_left btn_toolbar"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_add_line" onclick="add_line(\''.$this->c_id.'\');" '.$this->hover_out_lib(6,42).'></div></td>';
				}

				if($p_edit != false)
				{
					$html .= '<td class="btn_toolbar toolbar_separator_left"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_save" onclick="save_lines(event,\''.$this->c_id.'\',\'add\');" '.$this->hover_out_lib(50,54).'></div></td>';
				}
				else
				{
					$html .= '<td class="btn_toolbar grey_el" id="lisha_td_toolbar_edit_'.$this->c_id.'"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_page_edit " onclick="if(count_selected_lines(\''.$this->c_id.'\') > 0)edit_lines(event,null,\''.$this->c_id.'\');" '.$this->hover_out_lib(7,49).'></div></td>';
				}

				if($this->matchcode['__active_delete_button'][0] == true && $this->c_type_internal_lisha != __COLUMN_LIST__)
				{
					$html .= '<td class="btn_toolbar toolbar_separator_right grey_el" id="lisha_td_toolbar_delete_'.$this->c_id.'"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_delete" onclick="if(count_selected_lines(\''.$this->c_id.'\') > 0)delete_lines(\''.$this->c_id.'\',false);" '.$this->hover_out_lib(8,48).'></div></td>';
				}
				else
				{
					$html .= '<td class="toolbar_separator_right"></td>';
				}

				if($this->matchcode['__active_read_only_cells_edit'][0] == false && $p_edit == false && $this->c_type_internal_lisha != __COLUMN_LIST__)
				{
					if($this->matchcode['__active_user_cells_update'][0] == true)
					{
						$html .= '<td class="btn_toolbar toolbar_separator_right"><div id="lisha_td_toolbar_cells_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_cells_update_on" onclick="switch_user_cell_update(\''.$this->c_id.'\');" '.$this->hover_out_lib(161,90).'></div></td>';
					}
					else
					{
						$html .= '<td class="btn_toolbar toolbar_separator_right"><div id="lisha_td_toolbar_cells_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_cells_update_off" onclick="switch_user_cell_update(\''.$this->c_id.'\');" '.$this->hover_out_lib(162,90).'></div></td>';
					}
				}
			}

			//==================================================================
			// Excel export button
			//==================================================================
			// Export not available under conditon

			// Quantity of rows
			//error_log(print_r($p_resultat,true));
			if($p_resultat)
			{
				$num_rows = $this->c_obj_bdd->rds_num_rows($p_resultat);
			}
			else
			{
				$num_rows = 0;
			}	


			if($num_rows != 0)
			{
				$available_excel_export = '';
			}
			else
			{
				$available_excel_export = 'grey_el';
			}

			$html .= '<td id="lisha_td_toolbar_excel_'.$this->c_id.'" onclick="export_list(\''.$this->c_id.'\');" class="toolbar_separator btn_toolbar toolbar_separator_left '.$available_excel_export.'"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_excel" '.$this->hover_out_lib(9,61).'></div></td>';

			//==================================================================

			if($this->matchcode['__active_user_doc'][0])
			{
				$html .= '<td class="btn_toolbar"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_help" onclick="window.open(\''.$this->c_dir_obj.'\');" '.$this->hover_out_lib(10,1).'></div></td>';
			}
			if($this->matchcode['__active_tech_doc'][0])
			{
				$html .= '<td class="btn_toolbar"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_tech_help" onclick="window.open(\''.$this->c_dir_obj.'/indextech.php\');" '.$this->hover_out_lib(80,1).'></div></td>';
			}
			$html .= '<td><div id="lis__lisha_help_hover_'.$this->c_id.'__" class="nowrap"></div></td>';
			$html .= '</tr>';
			$html .= '</table>';

			return $html;
		}
		/**===================================================================*/


		/**==================================================================
		 * hover_out_lib
		 * Generate an onmouseover & onmouseout event for help
		 *
		 * @id_lib      : Id of text to display in quick extension test area
		 * @id_help     : Index of node help page
		====================================================================*/
		private function hover_out_lib($id_lib,$id_help)
		{
			return 'onmouseout="lisha_lib_out(\''.$this->c_id.'\');" onmouseover="lisha_lib_hover('.$id_lib.','.$id_help.',\''.$this->c_id.'\',\''.$this->matchcode['__active_user_doc'][0].'\');"';
		}
		/**===================================================================*/

		public function generate_lmod_header()
		{
			$html = '<div id="lisha_lmod_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_lisha_lmod">
				<table class="shadow" summary="">
					<tr>
						<td class="shadow_l_t"></td>
						<td colspan=2 rowspan=2 class="no_pad_marg"><div id="lmod_lisha_container_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_lmod_container"></div></td>
					</tr>
					<tr>
						<td class="shadow_l"></td>
					</tr>
					<tr>
						<td class="shadow_l_b"></td>
						<td class="shadow_b"></td>
						<td class="shadow_r_b"></td>
					</tr>	
				</table>
			</div>';

			return $html;
		}

		public function generate_lmod_form()
		{
			if($this->c_lmod_specified_width != null)
			{
				$lisha = '<div class="lisha_lmod_container gradient" style="width:'.$this->c_lmod_specified_width.'px;">';
				$lisha .= '<div class="lisha_lmod" onmousedown="lisha_lmod_click(\''.$this->c_id.'\');">
					<input id="lst_'.$this->c_id.'" readonly onmousedown="lisha_StopEventHandler(event);" type="text" class="lisha_input_lmod gradient" style="width:'.($this->c_lmod_specified_width-20).'px;"/>
				</div>
			</div>';

			}
			else
			{
				$lisha = '<div class="lisha_lmod_container gradient">';
				$lisha .= '<div class="lisha_lmod" onmousedown="lisha_lmod_click(\''.$this->c_id.'\');">
					<input id="lst_'.$this->c_id.'" readonly onmousedown="lisha_StopEventHandler(event);" type="text" class="lisha_input_lmod gradient"/>
				</div>
			</div>';			
			}



			return $lisha;
		}


		/**==================================================================
		 * remove all columns order by
		 ====================================================================*/
		public function clear_all_order()
		{
			foreach($this->c_columns as &$value)
			{
				$value["order_by"] = false;
				$value["order_priority"] = false;
			}
		}
		/**===================================================================*/


		/**==================================================================
		 * lisha_generate_calendar
		 * @p_column 		: column identifier
		 * @p_year 			: numeric year in for digits
		 * @p_month			: numeric month two digits
		 * @p_day			: numeric day of month
		 ====================================================================*/
		public function lisha_generate_calendar($p_column,$p_year,$p_month,$p_day)
		{
			$p_month = ltrim($p_month,'0');
			$p_day = ltrim($p_day,'0');

			$actual_year = $p_year;
			$actual_month = $p_month;
			if(!is_numeric($p_day))
			{
			$p_day = 1;
		}
			$actual_day = $p_day;

			//==================================================================
			// Limit year, month, day values
			//==================================================================
			if($actual_month > 12) $actual_month = 12;
			if($actual_month < 1) $actual_month = 1;
			if($actual_year < 1) $actual_year = 1;
			$nbr_day_of_month = cal_days_in_month(CAL_GREGORIAN, $actual_month, $actual_year);
			if($actual_day > $nbr_day_of_month) $actual_day = $nbr_day_of_month;
			if($actual_day < 1) $actual_day = 1;
			//==================================================================

			//==================================================================
			// Get the first day of the month
			//==================================================================
			$date=mktime(0,0,0,$actual_month,1,$actual_year);
			$first_day = date("N",$date);
			//==================================================================

			//==================================================================
			// Get the day of this date
			//==================================================================
			$date=mktime(0,0,0,$actual_month,$actual_day,$actual_year);
			$day_of_date = date("N",$date);
			//==================================================================

			//==================================================================
			// Define previous and next Year
			//==================================================================
			if($actual_year == 1)
			{
				$previous_year_class = 'grey_el';
				$previous_year_click = '';
			}
			else
			{
				$previous_year = $actual_year - 1;
				$previous_year_class = '';
				$previous_year_click = 'lisha_load_date(\''.$this->c_id.'\','.$p_column.','.$previous_year.',null,null);';
			}

			$next_year = $actual_year + 1;	
			//==================================================================

			//==================================================================
			// Define previous and next Month
			//==================================================================
			if($actual_month == 1)
			{
				$previous_month_class = 'grey_el';
				$previous_month_click = '';
			}
			else
			{
				$previous_month = $actual_month - 1;
				$previous_month_class = '';
				$previous_month_click = 'lisha_load_date(\''.$this->c_id.'\','.$p_column.',null,'.$previous_month.',null);';
			}

			if($actual_month == 12)
			{
				$next_month_class = 'grey_el';
				$next_month_click = '';
			}
			else
			{
				$next_month = $actual_month + 1;
				$next_month_class = '';
				$next_month_click = 'lisha_load_date(\''.$this->c_id.'\','.$p_column.',null,'.$next_month.',null);';
			}
			//==================================================================

			//==================================================================
			// Define previous and next day
			//==================================================================
			if($actual_day == 1)
			{
				$previous_day_class = 'grey_el';
				$previous_day_click = '';
			}
			else
			{
				$previous_day = $actual_day - 1;
				$previous_day_class = '';
				$previous_day_click = 'lisha_load_date(\''.$this->c_id.'\','.$p_column.',null,null,'.$previous_day.');';
			}

			if($actual_day == $nbr_day_of_month)
			{
				$next_day_class = 'grey_el';
				$next_day_click = '';
			}
			else
			{
				$next_day = $actual_day+1;
				$next_day_class = '';
				$next_day_click = 'lisha_load_date(\''.$this->c_id.'\','.$p_column.',null,null,'.$next_day.');';
			}
			//==================================================================

			$calendar = '<div class="__'.$this->matchcode['__id_theme'][0].'_date_select" style="/*text-align:center;*/">
							<form style="width:100px;margin:0 auto;" action="javascript:lisha_load_date(\''.$this->c_id.'\','.$p_column.');">';
			$calendar .= '<div class="__'.$this->matchcode['__id_theme'][0].'__window_button_function lisha_disp" id="window_function_'.$this->c_id.'"><div class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_cancel hover" '.$this->hover_out_lib(164,32).' style="float:right;margin-right:5px;" onclick="lisha_close_calendar(\''.$this->c_id.'\')"></div></div>';
			$calendar .= '	<table style="border-collapse:collapse;">
									<input type="submit" value="" style="border:0;width:0;height:0;margin:0;padding:0;visibility:hidden;float:left;"/>
										<tr>
											<td><div class="'.$previous_year_class.' c_pointer __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_top_calendar" '.$this->hover_out_lib(73,73).' onclick="'.$previous_year_click.'"></div></td>
											<td><div class="'.$previous_month_class.' c_pointer __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_top_calendar" '.$this->hover_out_lib(68,68).' onclick="'.$previous_month_click.'"></div></td>
											<td><div class="'.$previous_day_class.' c_pointer __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_top_calendar" '.$this->hover_out_lib(70,70).' onclick="'.$previous_day_click.'"></div></td>
										</tr>
										</tr>
											<td><input id="lisha_cal_year_'.$this->c_id.'" type="text" class="__'.$this->matchcode['__id_theme'][0].'__input_h" style="width:40px;text-align:center;" value="'.$actual_year.'"/></td>
											<td><input id="lisha_cal_month_'.$this->c_id.'" type="text" class="__'.$this->matchcode['__id_theme'][0].'__input_h" style="width:20px;text-align:center;" value="'.$actual_month.'"/></td>
											<td><input id="lisha_cal_day_'.$this->c_id.'" type="text" class="__'.$this->matchcode['__id_theme'][0].'__input_h" style="width:20px;text-align:center;" value="'.$actual_day.'"/></td>
										</tr>
										<tr>
											<td><div class="c_pointer __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_bottom_calendar" '.$this->hover_out_lib(72,72).' onclick="lisha_load_date(\''.$this->c_id.'\','.$p_column.','.$next_year.',null,null);"></div></td>
											<td><div class="'.$next_month_class.' c_pointer __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_bottom_calendar" '.$this->hover_out_lib(67,67).' onclick="'.$next_month_click.'"></div></td>
											<td><div class="'.$next_day_class.' c_pointer __'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_bottom_calendar" '.$this->hover_out_lib(71,71).' onclick="'.$next_day_click.'"></div></td>
										</tr>
								</table>
							</form>
						</div>
						<div id="calendar_load_'.$this->c_id.'" class="lisha_calendar_load">
							<div class="lisha_calendar_load_icon"></div>
						</div><table class="lisha_calendar">';

			//==================================================================
			// Construct the calendar (header)
			//==================================================================
			$calendar .= '<tr class="lisha_calendar">';

			for($i=1; $i <=7; $i++)
			{
				if($i == $day_of_date)
				{
					$calendar .= '<th class="lisha_calendar"><div class="lisha_txt_bold lisha_calendar_h">'.$_SESSION[$this->c_ssid]['lisha']['lib'][$i+99].'</div></th>';
				}
				else
				{
					$calendar .= '<th class="lisha_calendar"><div class="lisha_txt_lighter lisha_calendar_h">'.$_SESSION[$this->c_ssid]['lisha']['lib'][$i+99].'</div></th>';
				}

			}
			$calendar .= '</tr>';
			//==================================================================

			//==================================================================
			// Construct the calendar (days)
			//==================================================================
			$calendar .= '<tr class="lisha_calendar">';
			$day_in_line = 1;

			// Go to the first day
			for($day = 1; $day < $first_day ;$day++)
			{
				$calendar .= '<td class="lisha_calendar"></td>';
				$day_in_line++;
			}


			for($day = 1; $day <= $nbr_day_of_month ;$day++)
			{
				if($day_in_line > 7)
				{
					$calendar .= '</tr>';
					$calendar .= '<tr>';
					$day_in_line = 1;
				}

				if($day == $actual_day)
				{
					$calendar .= '<td '.$this->hover_out_lib(69,69).' class="lisha_calendar"><div onclick="lisha_insert_date(\''.$this->c_id.'\','.$p_column.',\''.$day.'\');" class="lisha_calendar_actual_day lisha_calendar_day">'.$day.'</div></td>';
				}
				else
				{
					$calendar .= '<td '.$this->hover_out_lib(69,69).' class="lisha_calendar"><div onclick="lisha_insert_date(\''.$this->c_id.'\','.$p_column.',\''.$day.'\');" class="lisha_calendar_day">'.$day.'</div></td>';
				}
				$day_in_line++;
			}
			$calendar .= '</tr>';
			//==================================================================

			$calendar .= '</table>';
			$calendar .= '<div class="__'.$this->matchcode['__id_theme'][0].'_calendar_footer">
							<table width=100%>
								<tr class="lisha_calendar">
									<td class="lisha_calendar"><div style="width:100%;font-family: arial, sans-serif;font-size: 11px;overflow: hidden;height:15px;">'.$_SESSION[$this->c_ssid]['lisha']['lib'][$day_of_date+92].' '.$actual_day.' '.$_SESSION[$this->c_ssid]['lisha']['lib'][$actual_month+80].' '.$actual_year.'</div></td>
								</tr>
							</table>
						</div>';
			echo $calendar;
			return null;
		}
		/**===================================================================*/


		/**==================================================================
		 * Setter
		 ====================================================================*/	
		public function set_recordser_line($nbr)
		{
			$this->c_recordset_line = $nbr;
		}
		/**===================================================================*/


		public function define_parent($p_parent,$p_column)
		{
			$this->c_id_parent = $p_parent;
			$this->c_id_parent_column = $p_column;
		}
		/**===================================================================*/


		/**==================================================================
		 * Setup lisha width and height size 
		 * @p_width 			: Integer define area width
		 * @p_w_unity			: Unit of width ( px, % )
		 * @p_height 			: Integer define area height
		 * @p_h_unity			: Unit of height ( px, % )
		 ====================================================================*/
		public function define_size($p_width,$p_w_unity,$p_height,$p_h_unity)
		{
			$this->c_width = $p_width;
			$this->c_w_unity = $p_w_unity;
			$this->c_height = $p_height;
			$this->c_h_unity = $p_h_unity;
		}
		/**===================================================================*/


		/**==================================================================
		 * define_line_theme
		 * @p_color_hex				:	Background color of unselected line no mouse hover
		 * @p_size_hex				:	font size unselect line no mouse hover
		 * @p_color_hover_hex 		:	Background line color when mouse hover
		 * @p_size_hover_hex		:	font size unselect line whith mouse hover
		 * @p_color_selected		:	Background line color when line selected
		 * @p_size_selected			:	font size selected line no mouse hover
		 * @p_color_hover_selected	:	Background line color when line selected and mouse hover
		 * @p_size_hover_selected	:	font size selected line with mouse hover
		 * @p_color_text			:	Color of text
		 * @p_color_text_selected	:	Color of text when it is selected
		 * @p_group					:	group to define severals set of color ( optional, null means no group )
		====================================================================*/
		public function define_line_theme(	$p_color_hex,
											$p_size_hex,
											$p_color_hover_hex,
											$p_size_hover_hex,
											$p_color_selected,
											$p_size_selected,
											$p_color_hover_selected,
											$p_size_hover_selected,
											$p_color_text,
											$p_color_text_selected,
											$p_group)
		{
			$this->matchcode['__internal_color_mask'][0][$p_group][] = array("color_code" => $p_color_hex,
													"size_text" => $p_size_hex,
													"color_hover_code" => $p_color_hover_hex,
													"size_hover_code" => $p_size_hover_hex,
													"color_selected_code" => $p_color_selected,
													"size_selected_code" => $p_size_selected,
													"color_hover_selected_code" => $p_color_hover_selected,
													"size_hover_selected_code" => $p_size_hover_selected,
													"color_text" => $p_color_text,
													"color_text_selected" => $p_color_text_selected);
		}
		/**===================================================================*/


		/**==================================================================
		 * General or column define attributes fonction
		 * @p_attribute		: attribute name
		 * @p_value			: value to setup into attribute
		 * @p_matched 		: true means matched once in class_lisha
		 * @p_column_name	: column name from query execution
		 * @p_column_id		: id of p_column_name
		 * @p_to_init		: true means reset backup value reference with new value
		 * ----------------------------------------------
		 * Performance 		: Use only for external call
		 ====================================================================*/
		public function define_attribute($p_attribute,$p_value, $p_matched, $p_column_name = null, $p_column_id = null, $p_to_init = true)
		{
			if(!isset($this->matchcode[$p_attribute]))
			{
				if(!$p_matched)
				{
					error_log(__FILE__.' name '.$p_attribute.' not defined in matchcode array');die();
				}	
			}
			else
			{
				if($this->matchcode[$p_attribute][1] == 'A' || $this->matchcode[$p_attribute][1] == 'W')
				{
					if(!isset($p_column_name))
					{
						// No column name that means use internal feature recorded in matchcode array
						$this->matchcode[$p_attribute][0] = $p_value;
					}
					else
					{
						// Column name exists that means record feature in c_columns array
						$var = $this->matchcode[$p_attribute][0];

						$this->c_columns[$p_column_id][$var] = $p_value;

						// Keep last column value in memory
						if($p_to_init)
						{
							$this->c_columns_init[$p_column_id][$var] = $p_value;
						}

					}
				}
				else
				{
					error_log(__FILE__.' name '.$p_attribute.' no write access');die();
				}
			}
		}
		/**===================================================================*/


		/**==================================================================
		 * General or column reader attributs fonction
		 * @p_attribute		: attribute name
		 * @p_matched 		: true means matched once in class_lisha
		 * @p_column_name 	: Column name if it's a column feature ( null means main feature )
		 * @p_column_id		: id of p_column_name
		 * Performance : Use only for external call
		 ====================================================================*/
		public function read_attribute($p_attribute, $p_matched, $p_column_name = null, $p_column_id = null)
		{
			if(!isset($this->matchcode[$p_attribute]) )
			{
				if(!$p_matched)
				{
					error_log(__FILE__.' name '.$p_attribute.' not defined in matchcode array');
					die();
				}	
			}
			else
			{
				if($this->matchcode[$p_attribute][1] == 'A' || $this->matchcode[$p_attribute][1] == 'R')
				{
					if(!isset($p_column_name))
					{
						// No column name that means use internal feature is recorded in matchcode array
						return $this->matchcode[$p_attribute][0];
					}
					else
					{
						// Column name exists that means record feature in c_columns array
						$var = $this->matchcode[$p_attribute][0];
						return $this->c_columns[$p_column_id][$var];
					}	
				}
				else
				{
					error_log(__FILE__.' name '.$p_attribute.' not readable');die();
				}
			}
			return null;
		}
		/**===================================================================*/


		/**
		 * Define the state of the text on the navbar
		 * @param boolean $p_state true : display / false : hidden
		 */
		public function define_navbar_txt_activate($p_state)
		{
			$this->c_navbar_txt_activate = $p_state;
		}

		/**
		 * Define the state of the refresh button on the navbar
		 * @param boolean $p_state true : display / false : hidden
		 */
		public function define_navbar_refresh_button_activate($p_state)
		{
			$this->c_navbar_refresh_button_activate = $p_state;
		}

		/**
		 * Define the state of the nav button on the navbar
		 * @param boolean $p_state true : display / false : hidden
		 */
		public function define_navbar_nav_button_activate($p_state)
		{
			$this->c_navbar_nav_button_activate = $p_state;
		}

		/**
		 * Define the state of the line per page text on the navbar
		 * @param boolean $p_state true : display / false : hidden
		 */
		public function define_navbar_txt_line_per_page_activate($p_state)
		{
			$this->c_navbar_txt_line_per_page_activate = $p_state;
		}


		/**==================================================================
		 * define_nb_line
		 * Number of row by page
		 * @p_nb_line 			: Number of lines by page
		 ====================================================================*/
		public function define_nb_line($p_nb_line)
		{
			$this->c_nb_line = $p_nb_line;
			$this->c_limit_max = $p_nb_line;
		}		
		/**===================================================================*/



		public function define_limit_min($page)
		{
			$this->c_limit_min = $page;
		}

		public function define_limit_max($page)
		{
			$this->c_limit_max = $page;
		}

		public function define_lmod_width($p_width)
		{
			$this->c_lmod_specified_width = $p_width;
		}		

		/**===================================================================*/


		/**==================================================================
		 * Return lisha caption
		 *
		 * @p_id    :   id of text to return
		 ====================================================================*/
		private function lib($p_id)
		{
			return $_SESSION[$this->c_ssid]['lisha']['lib'][$p_id];
		}
		/**===================================================================*/


		/**==================================================================
		 * Count number of order by defined
		 *
		 * return      : integer
		====================================================================*/
		private function get_nbr_order()
		{
			$qtt = 0;
			foreach ($this->c_columns as $value)
			{
				if($value['order_by'] != false)
				{
					$qtt = $qtt + 1;
				}
			}
			return $qtt;
		}
		/**===================================================================*/


		/**==================================================================
		 * Translate BBcode string to HTML string
		 *
		 * @p_text	:	String to translate
		====================================================================*/
		private function convertBBCodetoHTML($p_text)
		{
			// Email
			$p_text = preg_replace_callback('`\[email\](.*?(\[/email\]\[/email\].*?)*)\[/email\](?!(\[/email\]))`i',
				function($matches) {
					return "<a href=\"mailto:".str_replace("[/email][/email]","[/email]",$matches[1])."</a>";
				},$p_text);

			// Bold
			$p_text = preg_replace_callback('`\[b\](.*?(\[/b\]\[/b\].*?)*)\[/b\](?!(\[/b\]))`i',
				function($matches) {
					return "<b>".str_replace("[/b][/b]","[/b]",$matches[1])."</b>";
				},$p_text);

			// Italic
			$p_text = preg_replace_callback('`\[i\](.*?(\[/i\]\[/i\].*?)*)\[/i\](?!(\[/i\]))`i',
				function($matches) {
					return "<i>".str_replace("[/i][/i]","[/i]",$matches[1])."</i>";
				},$p_text);

			// Underline
			$p_text = preg_replace_callback('`\[u\](.*?(\[/u\]\[/u\].*?)*)\[/u\](?!(\[/u\]))`i',
				function($matches) {
					return "<u>".str_replace("[/u][/u]","[/u]",$matches[1])."</u>";
				},$p_text);

			// Strike
			$p_text = preg_replace_callback('`\[s\](.*?(\[/s\]\[/s\].*?)*)\[/s\](?!(\[/s\]))`i',
				function($matches) {
					return "<s>".str_replace("[/s][/s]","[/s]",$matches[1])."</s>";
				},$p_text);

			// Alignment : Center
			$p_text = preg_replace_callback('`\[center\](.*?(\[/center\]\[/center\].*?)*)\[/center\](?!(\[/center\]))`i',
				function($matches) {
					return "<p style=\"text-align: center;\">".str_replace("[/center][/center]","[/center]",$matches[1])."</p>";
				},$p_text);

			// Alignment : Left
			$p_text = preg_replace_callback('`\[left\](.*?(\[/left\]\[/left\].*?)*)\[/left\](?!(\[/left\]))`i',
				function($matches) {
					return "<p style=\"text-align: left;\">".str_replace("[/left][/left]","[/left]",$matches[1])."</p>";
				},$p_text);

			// Alignment : Right
			$p_text = preg_replace_callback('`\[right\](.*?(\[/right\]\[/right\].*?)*)\[/right\](?!(\[/right\]))`i',
				function($matches) {
					return "<p style=\"text-align: right;\">".str_replace("[/right][/right]","[/right]",$matches[1])."</p>";
				},$p_text);

			// Image / Picture
			$p_text = preg_replace_callback('`\[img\](.*?(\[/img\]\[/img\].*?)*)\[/img\](?!(\[/img\]))`i',
				function($matches) {
					return "<img src=\"".str_replace("[/img][/img]","[/img]",$matches[1])."\" />";
				},$p_text);

			// Image with specific width
			$p_text = preg_replace_callback('`\[img=(.*?)\](.*?(\[/img\]\[/img\].*?)*)\[/img\](?!(\[/img\]))`i',
				function($matches) {
					return "<img width=\"".$matches[1]."\" src=\"".str_replace("[/img][/img]","[/img]",$matches[2])."\" />";
				},$p_text);

			// Text color
			$p_text = preg_replace_callback('`\[color=(.*?)\](.*?(\[/color\]\[/color\].*?)*)\[/color\](?!(\[/color\]))`i',
				function($matches) {
					return "<font color=\"".$matches[1]."\">".str_replace("[/color][/color]","[/color]",$matches[2])."</font>";
				},$p_text);

			// Background color
			$p_text = preg_replace_callback('`\[bg=(.*?)\](.*?(\[/bg\]\[/bg\].*?)*)\[/bg\](?!(\[/bg\]))`i',
				function($matches) {
					return "<font style=\"background-color:".$matches[1]."\">".str_replace("[/bg][/bg]","[/bg]",$matches[2])."</font>";
				},$p_text);
			//$p_text = preg_replace('`\[size=(.*?)\](.*?(\[/size\]\[/size\].*?)*)\[/size\](?!(\[/size\]))`ie','"<font size=\"\\1\">".str_replace("[/size][/size]","[/size]","\\2")."</font>"',$p_text);
			$p_text = preg_replace_callback('`\[size=(.*?)\](.*?(\[/size\]\[/size\].*?)*)\[/size\](?!(\[/size\]))`i',
				function($matches) {
					return "<font size=\"".$matches[1]."\">".str_replace("[/size][/size]","[/size]",$matches[2])."</font>";
				},$p_text);

			// Font family
			$p_text = preg_replace_callback('`\[font=(.*?)\](.*?(\[/font\]\[/font\].*?)*)\[/font\](?!(\[/font\]))`i',
				function($matches) {
					return "<font face=\"".$matches[1]."\">".str_replace("[/font][/font]","[/font]",$matches[2])."</font>";
				},$p_text);

			// URL with only address
			$p_text = preg_replace_callback('`\[url\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`i',
				function($matches) {
					return "<a target=\"_blank\" href=\"".str_replace("[/url][/url]","[/url]",$matches[1])."</a>";
				},$p_text);

			// URL with address and display string
			//$p_text = preg_replace('`\[url=(.*?)\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`ie','"<a target=\"_blank\" href=\"\\1\">".str_replace("[/url][/url]","[/url]","\\2")."</a>"',$p_text);
			$p_text = preg_replace_callback('`\[url=(.*?)\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`i',
				function($matches) {
					return "<a target=\"_blank\" href=\"".$matches[1]."\">".str_replace("[/url][/url]","[/url]",$matches[2])."</a>";
				},$p_text);

			// Two URL and disable cascaded call using lisha_StopEventHandler
			$p_text = preg_replace_callback('`\[urloff\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`i',
				function($matches) {
					return "<a target=\"_blank\" onclick=\"lisha_StopEventHandler(event);\" href=\"".str_replace("[/url][/url]","[/url]",$matches[1])."</a>";
				},$p_text);
			$p_text = preg_replace_callback('`\[urloff=(.*?)\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`i',
				function($matches) {
					return "<a target=\"_blank\" onclick=\"lisha_StopEventHandler(event);\" href=\"".$matches[1]."\">".str_replace("[/url][/url]","[/url]",$matches[2])."</a>";
				},$p_text);

			// div
			$p_text = preg_replace_callback('`\[div=(.*?)\](.*?(\[/div\]\[/div\].*?)*)\[/div\](?!(\[/div\]))`i',
				function($matches) {
					return "<div class=\"".$matches[1]."\">".str_replace("[/div][/div]","[/div]",$matches[2])."</div>";
				},$p_text);


			// Found a randomized string do not exists in string to convert
			$temp_str = '7634253332';while(stristr($p_text,$temp_str)){$temp_str = mt_rand();}
			$p_text = str_replace('[br][br]',$temp_str,$p_text);
			//$p_text = preg_replace('`(?<!\[br\])\[br\](?!(\[br\]))`ie','str_replace("[br]","<br>","\\0")',$p_text);
			$p_text = preg_replace_callback('`(?<!\[br\])\[br\](?!(\[br\]))`i',
				function($matches) {
					return str_replace("[br]","<br>",$matches[0]);
				},$p_text);
			$p_text = str_replace($temp_str,'[br]',$p_text);

			// Found a randomized string do not exists in string to convert
			$temp_str = '7634253332';while(stristr($p_text,$temp_str)){$temp_str = mt_rand();}
			$p_text = str_replace('[hr][hr]',$temp_str,$p_text);
			//$p_text = preg_replace('`(?<!\[hr\])\[hr\](?!(\[hr\]))`ie','str_replace("[hr]","<hr>","\\0")',$p_text);
			$p_text = preg_replace_callback('`(?<!\[hr\])\[hr\](?!(\[hr\]))`i',
				function($matches) {
					return str_replace("[hr]","<hr>",$matches[0]);
				},$p_text);
			$p_text = str_replace($temp_str,'[hr]',$p_text);

			return $p_text;
		}
		/**===================================================================*/


	}