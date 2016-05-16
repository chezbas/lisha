<?php

class lisha extends class_sgbd
{
	/**==================================================================
	 * Attributes
	====================================================================*/
	private $c_id;								// Lisha Id
	private $c_ssid;							// Session Id
	private $c_lng;								// Lisha language
	private $c_ident;							// DB Identification
	private $c_columns;							// Columns (array())
	private $c_columns_init;					// Original columns values
	private $c_height;							// Lisha height
	private $c_h_unity;							// Lisha unity height (px,%)
	private $c_width;							// Lisha width
	private $c_w_unity;							// Lisha unity width (px,%)
	private $c_nb_line;							// Number of line per page
	private $c_default_nb_line;					// Number of line per page
	private $c_obj_graphic;						// Graphic instance of Lisha
	private $c_limit_min;						// Min value of the limit
	private $c_limit_max;						// Max value of the limit
	private $c_recordset_line;					// Total line of the recordset
	private $c_page_qtt_line;					// Total line of the page
	private $c_id_parent;
	private $c_id_parent_column;
	private $c_db_keys;							// list of Primary key ( Array )
	private $c_selected_lines;
	private $c_prepared_query;
	private $c_edit_mode;
	private $c_lisha_action;
	private $c_time_timer_refresh;				// Timer for refresh auto
	private $c_lmod_specified_width;
	private $order_priority;                    // Order priority index for main query
	private $order_priority_lov;                // Order priority index for current column lov
	private $order_priority_lov_column_id;		// Last column id identifier for order priority
	public $matchcode;				            // Matchcode between internal external call and function name

	private $string_global_search;				// String used to do global search


	private $last_page_position;                // Keep page number position
	private $last_limit_min;                    // Keep down limit
	//==================================================================

	//==================================================================
	// Define public attributes
	//==================================================================
	public $export_status;						// Export status null: no export in progress, 1 in progress, 2 done
	public $export_total;						// Total of rows to export
	public $filter_name;                        // Custom user filter to display
	public $c_dir_obj;							// Directory of lisha object
	//==================================================================

	/**===================================================================*/


	/**==================================================================
	 * Constructor of lisha class
	 * @p_id                    : Internal lisha identifier
	 * @p_ssid                  : Token string
	 * @p_db_engine             : Database engine to use
	 * @p_ident                 : Array of database connexion ( array('user' => 'my_database_user,'password' => 'my_database_password','host' => 'localhost','schema' => 'lisha') )
	 * @p_dir_obj               : Relative path to lisha directory
	 * @p_type_internal_lisha   : Kind of lisha ( __LOAD_FILTER__, __COLUMN_MODE__ ... )
	====================================================================*/
	public function __construct($p_id,$p_ssid,$p_db_engine,$p_ident,$p_dir_obj,$p_type_internal_lisha = false)
	{
		if(!isset($_GET['lng']))
		{
			if(isset($_SESSION[$p_ssid]['lisha']['configuration']))
			{
				$this->c_lng = $_SESSION[$p_ssid]['lisha']['configuration'][1]; // Default language from config table
			}
		}
		else
		{
			$this->c_lng = $_GET['lng'];
		}

		// Recover relative path to lisha
		$this->c_dir_obj = $p_dir_obj;


		$this->c_id = $p_id;
		$this->c_ssid = $p_ssid;
		$this->c_ident = $p_ident;

		parent::__construct($p_db_engine,$p_ident,$p_dir_obj);

		$this->db_connect();

		$this->c_type_internal_lisha = $p_type_internal_lisha;


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
			// -- Beginning of columns features
			'__column_date_format'												=> array('date_format','A'),
			'__column_number_of_decimal'										=> array('number_of_decimal','A'),
			'__column_display_name'												=> array('name','A'),
			'__column_search_mode'												=> array('search_mode','A'),
			'__column_no_wrap'													=> array('nowrap','A'),
			'__column_text_alignment'											=> array('alignment','A'),
			'__column_data_type'												=> array('data_type','A'),
			'__column_display_mode'												=> array('display','A'),
			'__column_input_check_update'										=> array('rw_flag','A',''),
			// -- Beginning of main features
			'__internal_HTML_position'											=> array('','A'),
			'__internal_color_mask'												=> array('','A'),
			'__update_table_name'												=> array('','A'),
			'__key_url_custom_view'												=> array('','A'),
			'__current_page'													=> array('','A'),
			'__return_column_id'												=> array('','A'),
			'__column_name_group_of_color'										=> array('','A'),
			'__column_name_focus'												=> array(null,'A'),
			'__return_mode'														=> array(__MULTIPLE__,'A'),
			'__display_mode'													=> array(__NMOD__,'A'),
			'__active_top_bar_page'												=> array('','A'),
			'__active_bottom_bar_page'											=> array('','A'),
			'__max_lines_by_page'												=> array('','A'),
			'__active_column_separation'										=> array(true,'A'),
			'__active_row_separation'											=> array(true,'A'),
			'__id_theme'														=> array('grey','A'),
			'__active_user_doc'													=> array(true,'A'),
			'__main_query'														=> array('','A')
		);
		//==================================================================

		//==================================================================
		// Build graphic part
		//==================================================================
		$this->c_obj_graphic = new graphic_lisha(__LISHA_APPLICATION_RELEASE__,$this->c_id,$this->c_ssid,$this,$p_dir_obj,$this->c_columns,$this->c_selected_lines,$this->c_type_internal_lisha,$this->c_lng);
		//==================================================================

		//==================================================================
		// Initial DEFAULT values
		//==================================================================
		$this->define_limit_min(0);
		$this->define_attribute('__active_readonly_mode',__R__);

		$this->define_attribute('__title',$p_id);
		$this->define_attribute('__active_title',true);

		$this->define_attribute('__active_column_separation',true);
		$this->define_attribute('__active_row_separation',true);

		$this->define_nb_line(20);
		$this->define_size(50,'%',50,'%');

		$this->define_attribute('__max_lines_by_page',200);

		$this->define_attribute('__active_top_bar_page',true);
		$this->define_attribute('__active_bottom_bar_page',true);

		$this->define_input_focus(null);						// No input focus

		$this->define_attribute('__display_mode',__NMOD__);		// Display in normal mode ( full display )
		$this->define_attribute('__return_mode',__MULTIPLE__);	// return mode if any


		$this->define_attribute('__current_page',1);			// Setup active page to 1
		$this->define_attribute('__active_bottom_bar_page',true);

		$this->define_attribute('__key_url_custom_view',$_SESSION[$this->c_ssid]['lisha']['configuration'][8]);


		$this->define_attribute('__column_name_group_of_color', $_SESSION[$this->c_ssid]['lisha']['configuration'][9]);

		$this->define_attribute('__internal_HTML_position',__RELATIVE__);			// HTML div position

		$this->define_attribute('__active_read_only_cells_edit', __RW__);			// Enable cells edition

		$this->define_attribute('__return_column_id', 1);
		$this->order_priority = 1;
		$this->order_priority_lov = 1;
		$this->order_priority_lov_column_id = null;

		//==================================================================
		// Feature not setup in your lisha setup file
		// If attribute is both defined in graphic and this part
		// then use in first priority default attribute from here
		//==================================================================
		foreach($this->matchcode as $clef => $my_val)
		{
			if(isset($this->c_obj_graphic->matchcode[$clef]))
			{
				$this->c_obj_graphic->matchcode[$clef][0] = $this->matchcode[$clef][0];
			}
		}
		//==================================================================


		// Check if any filter is already recorded
		$this->c_obj_graphic->any_filter = $this->any_filter();

		$this->c_time_timer_refresh = null;
		$this->c_lmod_specified_width = null;
		$this->c_edit_mode = __DISPLAY_MODE__;

		// Reset page position navigation
		$this->last_page_position = null;
		$this->last_limit_min = null;

		// Dynamic pointer activated
		$this->c_obj_graphic->string_global_search = &$this->string_global_search;

		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * Magic function to restore database connexion on serialize object
	 * DO NOT REMOVE
	====================================================================*/
	public function __wakeup()
	{
		// Connect to database
		$this->db_connect();
	}
	/**===================================================================*/


	/**==================================================================
	 * Generate common HTML header part
	====================================================================*/
	static function generate_common_html_header($p_ssid)
	{
		echo '<script type="text/javascript">';
		echo "var ssid = '".$p_ssid."';";
		echo "var g_help_page = '';";        // Global javascript variable for help page
		echo "var g_lisha_id = '';";        // lisha id of last lisha action in page
		echo '</script>';
	}
	/**===================================================================*/


	/**==================================================================
	 * Generate common HTML bottom part
	 * Build javascript part for help ( CTRL + F11 )
	 *
	 * @p_dir_obj			:	Relative path access of Lisha
	 * @p_get_url_directive	:	GET Keyword used for language
	 * @p_i18n_id			:	3 digits localization to use
	====================================================================*/
	static function generate_common_html_bottom($p_dir_obj,$p_get_url_directive,$p_i18n_id)
	{
		echo '
			<script type="text/javascript">
			var isCtrl = false;

			document.onkeydown = function(e)
			{
				e = e || event;

				//Detection du CTRL
				if(e.keyCode == 17)
				{
					isCtrl=true;
				}

				// Help - CTRL+F11
				if(e.keyCode == 122 && isCtrl == true)
				{
					if((g_help_page) && (g_help_page!=""))
					{
						window.open("'.$p_dir_obj.'/index.php?'.$p_get_url_directive.'='.$p_i18n_id.'&id="+g_help_page);
					}
					else
					{
						// No contextual help
						no_contextual_help(lis_lib[59],lis_lib[160]);
					}
				}
			};	// End onkeydown
			</script>
			';
	}
	/**===================================================================*/


	/**==================================================================
	 * define_nb_line
	 * Number of row by page
	 * @p_nb_line 			: Number of lines by page
	 * @p_selected_lines	: lines ares selected ? default = false
	====================================================================*/
	public function define_nb_line($p_nb_line,$p_selected_lines = false)
	{
		$this->c_default_nb_line = $p_nb_line;
		$this->change_nb_line($p_nb_line,$p_selected_lines);
	}
	/**===================================================================*/


	/**==================================================================
	 * Change lines by page
	 * @p_nb_line 			: Number of lines by page
	 * @p_selected_lines	: lines ares selected ? default = false
	====================================================================*/
	public function change_nb_line($p_nb_line,$p_selected_lines = false)
	{
		// Set the selected lines to edit
		if($p_selected_lines != false)
		{
			$this->define_selected_line($p_selected_lines);
		}
		$this->c_nb_line = $p_nb_line;
		$this->c_limit_max = $this->c_nb_line;

		$this->c_obj_graphic->define_nb_line($p_nb_line);
		$this->define_attribute('__current_page',1);
		$this->define_limit_min(0);
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
		$this->c_obj_graphic->define_size($p_width,$p_w_unity,$p_height,$p_h_unity);
	}
	/**===================================================================*/


	/**==================================================================
	 * db_format : database date format
	 * @p_mode					: date for date conversion
	 * @p_column				: column id
	 * @p_value					: input string
	====================================================================*/
	public function db_format($p_mode,$p_column,$p_value)
	{
		switch($p_mode)
		{
			case 'date':
				// Setup customer filter
				$tmp_final = $this->convert_database_date_to_localized_format($p_column,$p_value);
				if($tmp_final == "-1")
				{
					// No localized date format conversion possible then keep original value
					$tmp_final = $p_value;
				}

				$this->c_columns[$p_column]['filter']['input'] = array('filter' =>  $p_value,
																	   'filter_display' => $tmp_final
				);
				echo $tmp_final;
				break;
			default:
				echo 'Error pmode '.$p_mode;
				die();
		}
	}
	/**===================================================================*/


	/**==================================================================
	 * define_key
	 * Define keys design primary access in your main table
	====================================================================*/
	public function define_key($p_array_keys)
	{
		foreach($p_array_keys as $value)
		{
			$column_id = $this->get_id_column($value);

			if(!is_null($column_id))
			{
				// The column exist
				$this->c_columns[$column_id]['is_key_part'] = $value;
			}
			else
			{
				// The column does not exist
				$this->define_column('NA',$value,$value,__TEXT__,__WRAP__,__CENTER__,__CONTAIN__,__HIDE__);
				$col_name = $this->get_id_column($value);
				$this->c_columns[$col_name]['is_key_part'] = $value;
				$this->c_columns[$col_name]['auto_create_column'] = true;
			}
		}
		$this->c_db_keys = $p_array_keys;
	}
	/**===================================================================*/


	/**==================================================================
	 * define_input_focus
	 * Convert name to id for graphic part
	====================================================================*/
	public function define_input_focus($column_name)
	{
		$this->define_attribute('__column_name_focus',$column_name);

		$tmp_focus_id_column = $this->get_id_column($column_name);
		$this->c_obj_graphic->c_default_input_focus_id = $tmp_focus_id_column;
	}
	/**==================================================================*/


	/**==================================================================
	 * General or column define attributes fonction
	 * Check first matchcode list of class_lisha
	 * if not exists then try matchcode of class_graphic
	 *
	 * @p_attribute		: External attribute name
	 * @p_value			: value to setup to attribute
	 * @p_column_name	: Column name if it's a column feature ( null means main feature )
	 * @p_to_init		: true means reset backup value reference with new value
	====================================================================*/
	public function define_attribute($p_attribute,$p_value,$p_column_name = null, $p_to_init = true)
	{
		if(!isset($this->matchcode[$p_attribute]))
		{
			// Not define in this class matchcode, go further in graphic class
			$this->c_obj_graphic->define_attribute($p_attribute,$p_value, false, $p_column_name, $this->get_id_column($p_column_name), $p_to_init);
		}
		else
		{
			if($this->matchcode[$p_attribute][1] == 'A' || $this->matchcode[$p_attribute][1] == 'W')
			{
				$this->c_obj_graphic->define_attribute($p_attribute,$p_value, true, $p_column_name, $this->get_id_column($p_column_name),$p_to_init);

				if($p_column_name == null)
				{
					// No column name that means use internal feature is recorded in matchcode array
					$this->matchcode[$p_attribute][0] = $p_value;
				}
				else
				{
					// Column name exists that means record feature in c_columns array
					$var = $this->matchcode[$p_attribute][0];

					$this->c_columns[$this->get_id_column($p_column_name)][$var] = $p_value;

					// Record column feature in memory
					if($p_to_init)
					{
						$this->c_columns_init[$this->get_id_column($p_column_name)][$var] = $p_value;
					}

				}
			}
			else
			{
				error_log(__FILE__.' name '.$p_attribute.' no write access');die();
			}
		}
	}
	/**==================================================================*/


	/**==================================================================
	 * General or column reader attributs fonction
	 * Check first matchcode list of class_lisha
	 * if not exists then try matchcode in class class_graphic
	 *
	 * @p_attribute : External attribute name to match with internal name
	 * @p_column_name : Column name if it's a column feature ( null means main feature )
	====================================================================*/
	public function read_attribute($p_attribute,$p_column_name = null)
	{
		if(!isset($this->matchcode[$p_attribute]) )
		{
			$this->c_obj_graphic->read_attribute($p_attribute, false);
		}
		else
		{
			if($this->matchcode[$p_attribute][1] == 'A' || $this->matchcode[$p_attribute][1] == 'R')
			{
				$this->c_obj_graphic->read_attribute($p_attribute, true, $p_column_name, $this->get_id_column($p_column_name));

				if(!isset($p_column_name))
				{
					// No column name that means use internal feature is recorded in matchcode array
					return $this->matchcode[$p_attribute][0];
				}
				else
				{
					// Column name exists that means record feature in c_columns array
					$var = $this->matchcode[$p_attribute][0];
					return $this->c_columns[$this->get_id_column($p_column_name)][$var];
				}
			}
			else
			{
				error_log(__FILE__.' name '.$p_attribute.' not readable');die();
			}
		}
		return true;
	}
	/**==================================================================*/


	/**
	 * Define the state of the text on the navbar
	 * @param boolean $p_state true : display / false : hidden
	 */
	public function define_navbar_txt_activate($p_state)
	{
		$this->c_obj_graphic->define_navbar_txt_activate($p_state);
	}

	/**
	 * Define the state of the refresh button on the navbar
	 * @param boolean $p_state true : display / false : hidden
	 */
	public function define_navbar_refresh_button_activate($p_state)
	{
		$this->c_obj_graphic->define_navbar_refresh_button_activate($p_state);
	}

	/**
	 * Define the state of the nav button on the navbar
	 * @param boolean $p_state true : display / false : hidden
	 */
	public function define_navbar_nav_button_activate($p_state)
	{
		$this->c_obj_graphic->define_navbar_nav_button_activate($p_state);
	}

	/**
	 * Define the state of the line per page text on the navbar
	 * @p_state true : display / false : hidden
	 */
	public function define_navbar_txt_line_per_page_activate($p_state)
	{
		$this->c_obj_graphic->define_navbar_txt_line_per_page_activate($p_state);
	}


	/**==================================================================
	 * Initial definition of column
	 * Most important part, be strict and keep in mind that before AS part can be different with after AS part
	 *
	 * @p_before_as     : Define part before as of column
	 * @p_column_id		: Name column output by query : Key name used for all others column features
	 * @p_name			: Column title name
	 * @p_data_type		: Column data type
	 * @p_nowrap 		: true nowrap enabled, false nowrap disabled
	 * @p_alignment		: text alignment
	 * @p_display		: display or hide column
	====================================================================*/
	public function define_column($p_before_as,$p_column_id,$p_name,$p_data_type = __BBCODE__,$p_nowrap = __NOWRAP__,$p_alignment = __CENTER__,$p_search_mode = __CONTAIN__,$p_display = __DISPLAY__)
	{
		$column_id = count($this->c_columns)+1;
		$this->c_columns[$column_id] = array(	"before_as" => $p_before_as,
												 "sql_as" => $p_column_id,
												 "order_by" => false,
												 "order_priority" => false,
												 "quick_help" => false,
												 "original_order" => $column_id
		);
		$this->define_attribute('__column_search_mode',$p_search_mode,$p_column_id);
		$this->define_attribute('__column_no_wrap',$p_nowrap,$p_column_id);
		$this->define_attribute('__column_text_alignment',$p_alignment,$p_column_id);
		$this->define_attribute('__column_data_type',$p_data_type,$p_column_id);
		$this->define_attribute('__column_display_name',$p_name,$p_column_id);
		$this->define_attribute('__column_display_mode',$p_display,$p_column_id);

		// Record column feature in memory
		$this->c_columns_init[$column_id] = $this->c_columns[$column_id];
	}
	/**==================================================================*/


	/**==================================================================
	 * Define over load custom function for WRITE access
	 * Example : MD5(__COL_VALUE__) -> MD5(value)
	 *
	 * @p_column		: Column identifier ( have to define column in your lisha and hide )
	 * @p_function    	: MD5(__COL_VALUE__),SHA1(__COL_VALUE__),ENCODE("__COL_VALUE__","454fdf")...
	====================================================================*/
	public function define_col_rw_function($p_column,$p_function)
	{
		$column_id = $this->get_id_column($p_column);
		$this->c_columns[$column_id]['rw_function'] = $p_function;

		// Keep original key value in memory
		$this->c_columns_init[$column_id]['rw_function'] = $this->c_columns[$column_id]['rw_function'];
	}
	/**==================================================================*/


	/**==================================================================
	 * Define over load custom function for READ access
	 * Example : MD5(__COL_VALUE__) -> MD5(value)
	 *
	 * @p_column		: Column identifier ( have to define column in your lisha and hide )
	 * @p_function    	: MD5(__COL_VALUE__),SHA1(__COL_VALUE__),ENCODE("__COL_VALUE__","454fdf")...
	====================================================================*/
	public function define_col_select_function($p_column,$p_function)
	{
		$column_id = $this->get_id_column($p_column);
		$this->c_columns[$column_id]['select_function'] = $p_function;

		// Keep original key value in memory
		$this->c_columns_init[$column_id]['select_function'] = $this->c_columns[$column_id]['select_function'];
	}
	/**==================================================================*/


	/**==================================================================
	 * Method to define external key field
	 * Kind of extra field to defined primary key
	 *
	 * @p_column		: Column identifier ( have to define column in your lisha and hide )
	 * @p_value     	: Constant to setup in this key field
	====================================================================*/
	public function define_col_value($p_column,$p_value)
	{
		$column_id = $this->get_id_column($p_column);
		$this->c_columns[$column_id]['predefined_value'] = $p_value;

		// Keep original key value in memory
		$this->c_columns_init[$column_id]['predefined_value'] = $this->c_columns[$column_id]['predefined_value'];
	}
	/**==================================================================*/


	/**==================================================================
	 * Define column list of value or matchcode
	 *
	 * @p_sql			: Query to build list
	 * @p_title			: Title of query window
	 * @p_before_as	    : string before as of field
	 * @p_col_return	: name of column return by query
	====================================================================*/
	public function define_lov($p_sql,$p_title,$p_before_as,$p_col_return)
	{
		$column_id = count($this->c_columns);
		$this->c_columns[$column_id]['lov']['sql'] = $p_sql;
		$this->c_columns[$column_id]['lov']['title'] = $p_title;
		$this->c_columns[$column_id]['lov']['before_as'] = $p_before_as;
		$this->c_columns[$column_id]['lov']['col_return'] = $p_col_return;

		// Keep initial value in memory
		$this->c_columns_init[$column_id]['lov']['sql'] = $p_sql;
		$this->c_columns_init[$column_id]['lov']['title'] = $p_title;
		$this->c_columns_init[$column_id]['lov']['before_as'] = $p_before_as;
		$this->c_columns_init[$column_id]['lov']['col_return'] = $p_col_return;

		//==================================================================
		// Populated taglov of column
		//==================================================================
		$motif = '#\|\|TAGLOV_([^\|]+)\*\*([^\|]+)\|\|#';
		preg_match_all($motif,$p_sql,$out);

		foreach($out[1] as $key => $value)
		{
			$this->c_columns[$column_id]['lov']['taglov'][$key]['column'] = $value;

			// Keep initial value in memory
			$this->c_columns_init[$column_id]['lov']['taglov'][$key]['column'] = $value;


			$this->c_columns[$column_id]['lov']['taglov'][$key]['column_return'] = $out[2][$key];

			// Keep initial value in memory
			$this->c_columns_init[$column_id]['lov']['taglov'][$key]['column_return'] = $out[2][$key];
		}
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * Define initial lov column definition
	 *
	 * @p_before_as     : Define part before as of column
	 * @p_column_id		: Field name output by query ( KeyName )
	 * @p_name			: Window title
	 * @p_data_type		: column data type
	 * @p_nowrap		: Kind of values : __NOWRAP__ or __WRAP__
	 * @p_alignment		: Content alignment __CENTER__, __LEFT__ or __RIGHT__
	 * @p_search_mode	: Search mode : See operator list
	 * @p_display		: Show or hide column __DISPLAY__ or __HIDE__
	 * @p_focus			: identifier column to setup focus
	====================================================================*/
	public function define_column_lov($p_before_as, $p_column_id, $p_name,$p_data_type = __TEXT__,$p_nowrap = __WRAP__,$p_alignment = __CENTER__,$p_search_mode = __CONTAIN__,$p_display = __DISPLAY__, $p_focus = null)
	{
		$column_id = count($this->c_columns);
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['before_as'] = $p_before_as;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['sql_as'] = $p_column_id;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['name'] = $p_name;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['data_type'] = $p_data_type;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['nowrap'] = $p_nowrap;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['alignment'] = $p_alignment;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['search_mode'] = $p_search_mode;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['display'] = $p_display;
		$this->c_columns[$column_id]['lov']['columns'][$p_column_id]['focus'] = $p_focus;

		// Keep in memory
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['before_as'] = $p_before_as;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['sql_as'] = $p_column_id;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['name'] = $p_name;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['data_type'] = $p_data_type;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['nowrap'] = $p_nowrap;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['alignment'] = $p_alignment;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['search_mode'] = $p_search_mode;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['display'] = $p_display;
		$this->c_columns_init[$column_id]['lov']['columns'][$p_column_id]['focus'] = $p_focus;
	}
	/**==================================================================*/


	/**==================================================================
	 * define feature of line in a group ( group by default is zero )
	 *
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
										  $p_group = 0)
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

		$this->c_obj_graphic->define_line_theme($p_color_hex,
			$p_size_hex,
			$p_color_hover_hex,
			$p_size_hover_hex,
			$p_color_selected,
			$p_size_selected,
			$p_color_hover_selected,
			$p_size_hover_selected,
			$p_color_text,
			$p_color_text_selected,
			$p_group);
	}
	/**==================================================================*/


	public function define_limit_min($page)
	{
		$this->c_limit_min = $page;
		$this->c_obj_graphic->define_limit_min($this->c_limit_min);
	}

	public function define_limit_max($page)
	{
		$this->c_limit_max = $page;
		$this->c_obj_graphic->define_limit_max($this->c_limit_max);
	}

	public function define_lmod_width($p_width)
	{
		$this->c_lmod_specified_width = $p_width;
		$this->c_obj_graphic->define_lmod_width($p_width);
	}

	/**
	 * Define the type of quick help.
	 * @p_column Column number
	 * @p_mode boolean false : percent,true : strict
	 */
	public function define_col_quick_help($p_column,$p_mode)
	{
		$this->c_columns[$this->get_id_column($p_column)]['quick_help'] = $p_mode;
	}


	/**==================================================================
	 * Define javascript extra event user call
	 *
	 * @p_event		: Kind of internal event ( eg : __ON_LMOD_INSERT__...)
	 * @p_moment	: Id of column ( __BEFORE__, __AFTER__ )
	 * @p_lisha		: Id of target lisha in your page
	 * @p_action	: Javascript function to call
	====================================================================*/
	public function define_lisha_action($p_event,$p_moment,$p_lisha,$p_action)
	{
		$this->c_lisha_action[$p_event][] = Array('lisha' => $p_lisha,'MOMENT' => $p_moment,'ACTION' => $p_action);
	}
	/**==================================================================*/


	/**==================================================================
	 * Setup parent information for sub lisha
	 *
	 * @p_parent		: Name of column definition
	 * @p_column		: id of column
	====================================================================*/
	public function define_parent($p_parent,$p_column = null)
	{
		$this->c_id_parent = $p_parent;
		$this->c_id_parent_column = $p_column;

		// Copy parent information in graphic part
		$this->c_obj_graphic->define_parent($p_parent,$p_column);
	}
	/**==================================================================*/


	/**==================================================================
	 * define column order
	 * Just add new call to define a new column order with lower priority
	 *
	 * @column_name		: Name of column definition
	 * @order			: __ASC__ or __DESC__
	 * @id_column		: id of column ( optional )
	 * @p_keep_in_init  : Keep this in reference array ( true means yes )
	====================================================================*/
	public function define_order_column($column_name,$order,$id_column = null, $p_keep_in_init = true)
	{
		// Get the id column if not define by $id_column
		if(!is_numeric($id_column))
		{
			$id_column = $this->get_id_column($column_name);
		}

		$this->c_columns[$id_column]['order_priority'] = $this->order_priority;
		$this->c_columns[$id_column]['order_by'] = $order;

		// Keep last column value in memory
		if($p_keep_in_init)
		{
			$this->c_columns_init[$id_column]['order_priority'] = $this->order_priority;
			$this->c_columns_init[$id_column]['order_by'] = $order;
		}

		$this->order_priority = $this->order_priority + 1;
	}
	/**==================================================================*/


	/**==================================================================
	 * define_column_lov_order
	 *
	 * @column_name			:	Column name
	 * @order			 	:	__ASC__ or __DESC__
	====================================================================*/
	public function define_column_lov_order($column_name,$order)
	{
		// Get current id column
		$id_column = count($this->c_columns);

		// column switch ?
		if($this->order_priority_lov_column_id != $id_column)
		{
			$this->order_priority_lov = 1;
			$this->order_priority_lov_column_id = $id_column;
		}

		$this->c_columns[$id_column]['lov']['columns'][$column_name]['order']['order_priority'] = $this->order_priority_lov;
		$this->c_columns[$id_column]['lov']['columns'][$column_name]['order']['order_by'] = $order;

		// Keep in memory
		$this->c_columns_init[$id_column]['lov']['columns'][$column_name]['order']['order_priority'] = $this->order_priority_lov;
		$this->c_columns_init[$id_column]['lov']['columns'][$column_name]['order']['order_by'] = $order;

		$this->order_priority_lov = $this->order_priority_lov + 1;
	}
	/**==================================================================*/


	/**==================================================================
	 * Define count down value in ms before refreshing lisha
	 * @p_time          : Count downtime in ms
	====================================================================*/
	public function define_auto_refresh_timer($p_time)
	{
		if($p_time < 3000)
		{
			$this->c_time_timer_refresh = 3000;
		}
		else
		{
			$this->c_time_timer_refresh = $p_time;
		}
	}
	/**===================================================================*/


	/**==================================================================
	 * define_filter just typing by user in input box
	 * @post			:	Array of whole $_POST
	====================================================================*/
	public function define_filter($post)
	{
		// Set the selected lines to edit
		$this->define_selected_line($post['selected_lines']);

		//==================================================================
		// Browse the updated filter
		//==================================================================
		$column = $post['filter_col'];

		if($post['filter'] == '')
		{
			unset($this->c_columns[$column]['filter']['input']);
		}
		else
		{
			if($this->c_columns[$column]['data_type'] == __DATE__)
			{
				$tmp_result = $this->convert_localized_date_to_database_format($column,$post['filter']);
				if($tmp_result == "-1")
				{
					// No localized date format conversion possible then keep original value
					$tmp_result = $post['filter'];
				}

				$this->c_columns[$column]['filter']['input'] = array('filter' =>  rawurldecode($tmp_result),
																	 'filter_display' => rawurldecode($post['filter'])
				);
			}
			else
			{
				if ($this->c_columns[$column]['data_type'] == __FLOAT__ || $this->c_columns[$column]['data_type'] == __INT__)
				{
					$tmp_result = str_replace($_SESSION[$this->c_ssid]['lisha']['thousand_symbol'],"",rawurldecode($post['filter']));
					$this->c_columns[$column]['filter']['input'] = array('filter' => $tmp_result,
																		 'filter_display' => rawurldecode($post['filter'])
					);
				}
				else
				{
					$this->c_columns[$column]['filter']['input'] = array('filter' => rawurldecode($post['filter']),
																		 'filter_display' => rawurldecode($post['filter'])
					);
				}
			}

			if(isset($this->c_columns[$column]['lov']))
			{
				// Replace all taglov
				$sql_src = $this->solve_lov_main_query($column);

				$sql = 'SELECT * FROM ('.$sql_src.') t WHERE '.$this->c_columns[$column]['lov']['col_return'].' LIKE "%'.$this->protect_sql(rawurldecode($post['filter']),$this->link).'%"';

				$resultat = $this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);
				if($this->c_columns[$column]['quick_help'])
				{
					if($this->rds_num_rows($resultat) == 1)
					{
						$this->c_columns[$column]['lov']['taglov_possible'] = true;

						while($row = $this->rds_fetch_array($resultat))
						{
							$this->c_columns[$column]['filter']['input']['taglov'] = $row;
						}
					}
					else
					{
						unset($this->c_columns[$column]['lov']['taglov_possible']);
					}
				}
				else
				{
					$sql = '	SELECT
										*
									FROM
										('.$sql_src.') t
									WHERE 1 = 1
										AND '.$this->c_columns[$column]['lov']['col_return'].' = "'.$this->protect_sql(rawurldecode($post['filter']),$this->link).'"
								';
					$res = $this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

					if($res->num_rows > 0)
					{
						$this->c_columns[$column]['taglov_possible'] = true;

						while($row = $this->rds_fetch_array($res))
						{
							$this->c_columns[$column]['filter']['input']['taglov'] = $row;
						}
					}
					else
					{
						unset($this->c_columns[$column]['lov']['taglov_possible']);
					}
				}
			}
		}

		$this->set_is_lovable_columns();

		if(isset($this->c_columns[$column]['filter']) && count($this->c_columns[$column]['filter']) == 0)
		{
			unset($this->c_columns[$column]['filter']);
		}
		//==================================================================

		//==================================================================
		// Force active page to 1
		//==================================================================
		$this->define_attribute('__current_page',1);
		$this->define_limit_min(0);
		//==================================================================

		//==================================================================
		// Execute the query and display the elements
		//==================================================================
		$this->prepare_query();       // SRX optimization

		$json = $this->generate_lisha_json_param();
		$json_line = $this->generate_json_line();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<json_line>".$this->protect_xml($json_line)."</json_line>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * Compute and set up all columns is_lovable feature
	 *
	 * is_lovable = true means that you will have a marble
	 *      red     : means a custom lov is defined
	 *      white   : means standard distinct value list ( by default )
	====================================================================*/
	private function set_is_lovable_columns()
	{
		//==================================================================
		// Check if column is lovable
		//==================================================================
		foreach($this->c_columns as $key => $value_col)
		{
			if(isset($value_col['lov']['taglov']))
			{
				// Check if it's solvable
				$sql = $this->solve_lov_main_query($key);

				if(stripos($sql,'||TAGLOV_') === false)
				{
					// No more TAGLOV in query that means it's solvable
					$possible = true;

					// Check if all dependencies entries return exactly 1 row
					foreach($value_col['lov']['taglov'] as $value)
					{
						if(isset($this->c_columns[$this->get_id_column($value['column'])]['matched_rows']))
						{
							if ($this->c_columns[$this->get_id_column($value['column'])]['matched_rows'] <> 1)
								$possible = false;
							break;
						}
					}
				}
				else
				{
					$possible = false;
				}

				if(isset($possible) && $possible == true)
				{
					$this->c_columns[$key]['is_lovable'] = true;
					$this->c_columns_init[$key]['is_lovable'] = true;
				}
				else
				{
					$this->c_columns[$key]['is_lovable'] = false;
					$this->c_columns_init[$key]['is_lovable'] = false;
				}
			}
			else
			{
				$this->c_columns[$key]['is_lovable'] = true;
				$this->c_columns_init[$key]['is_lovable'] = true;
			}
		}
		//==================================================================
	}
	/**===================================================================*/


	/**
	 * Generate js color table
	 */
	public function generate_table_color()
	{
		$html = 'array_js_color_selected_code = new Array();';
		$html .= 'array_js_color_text_selected = new Array();';
		foreach($this->matchcode['__internal_color_mask'][0] as $clef => $valeur)
		{
			foreach ($valeur as $key => $value)
			{
				$html .= 'array_js_color_selected_code['.$clef.$key.'] = \''.$value['color_selected_code'].'\';';
				$html .= 'array_js_color_text_selected['.$clef.$key.'] = \''.$value['color_text_selected'].'\';';
			}
		}
		return $html;
	}


	/**==================================================================
	 * new_graphic_lisha
	 * Create the lisha graphic object
	====================================================================*/
	public function new_graphic_lisha()
	{
		// Get the filter option
		$this->get_and_set_filter();

		// Prepare the query
		$this->prepare_query();

		return null;
	}
	/**===================================================================*/


	/**==================================================================
	 * get_and_set_filter
	 * Save and restore customer filter
	 * @param null $filter_name		: Define filter name if any
	====================================================================*/
	private function get_and_set_filter($filter_name = null)
	{
		// Verify if a filter exist in the URL
		if(isset($_GET[$this->matchcode['__key_url_custom_view'][0]]) || !is_null($filter_name))
		{

			if(is_null($filter_name))
			{
				$filter_name = $_GET[$this->matchcode['__key_url_custom_view'][0]];
				$this->filter_name = $filter_name;
			}

			//==================================================================
			// Get filter values from the database
			//==================================================================
			$sql = 'SELECT '.$this->get_quote_col('id_column').','.$this->get_quote_col('type').','.$this->get_quote_col('val1').','.$this->get_quote_col('val2').','.$this->get_quote_col('val3').'
						FROM '.__LISHA_TABLE_FILTER__.'
						WHERE '.$this->get_quote_col('name').' = '.$this->get_quote_string($this->protect_sql($filter_name,$this->link)).'
						AND '.$this->get_quote_col('id').' = '.$this->get_quote_string($this->protect_sql($this->c_id,$this->link));

			$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
			//==================================================================

			if($this->rds_num_rows($this->resultat) > 0)
			{
				//==================================================================
				// Get filter values from the database
				//==================================================================
				foreach($this->c_columns as &$val_column)
				{
					$val_column['display'] = __DISPLAY__;
					$val_column['order_by'] = false;
					$val_column['order_priority'] = false;
				}

				$column_temp = array();

				while($row = $this->rds_fetch_array($this->resultat))
				{
					$result_array[$row['type']][$row['id_column']] = $row;
				}
				//==================================================================

				//==================================================================
				// Get global features
				//==================================================================
				if(isset($result_array['IEQ']["##GLOBALF##"]))
				{
					if($result_array['IEQ']["##GLOBALF##"]['type'] == 'IEQ')
					{
						$this->string_global_search = $result_array['IEQ']["##GLOBALF##"]['val1'];
					}
				}
				//==================================================================

				//==================================================================
				// Restore column position : CPS
				//==================================================================
				if(isset($result_array['CPS']))
				{
					foreach($result_array['CPS'] as $row)
					{
						$column_temp[$row['val1']] = $this->c_columns[$this->get_id_column($row['id_column'])];
					}
				}
				//==================================================================

				//==================================================================
				// Restore order attribute : ORD
				//==================================================================
				if(isset($result_array['ORD']))
				{
					foreach($result_array['ORD'] as $row)
					{
						$column_temp[$row['val3']]['order_by'] = $row['val2'];
						$column_temp[$row['val3']]['order_priority'] = $row['val1'];
					}
				}
				//==================================================================

				//==================================================================
				// Restore display Mode attribut : DMD
				//==================================================================
				if(isset($result_array['DMD']))
				{
					foreach($result_array['DMD'] as $row)
					{
						($row['val2'] == '') ? $val_dmd = false : $val_dmd = true;
						$val_dmd = false;
						$column_temp[$row['val1']]['display'] = $val_dmd;
					}
				}
				//==================================================================

				//==================================================================
				// Recover input search string : QSC
				//==================================================================
				if(isset($result_array['QSC']))
				{
					foreach($result_array['QSC'] as $row)
					{
						if($column_temp[$row['val1']]['data_type'] == __DATE__)
						{
							$tmp_result = $this->convert_database_date_to_localized_format($row['val1'],$row['val2']);
							if($tmp_result == "-1")
							{
								// No localized date format conversion possible then keep original value
								$tmp_result = $row['val2'];
							}

							$column_temp[$row['val1']]['filter']['input'] = array('filter' => $row['val2'],
																				  'filter_display' => $tmp_result
							);
						}
						else
						{
							$column_temp[$row['val1']]['filter']['input']['filter'] = $row['val2'];
						}
					}
				}
				//==================================================================

				//==================================================================
				// Recover search mode : SMD
				// Todo operator list
				// __STRICT__, __PERCENT__
				//==================================================================
				if(isset($result_array['SMD']))
				{
					foreach($result_array['SMD'] as $row)
					{
						$column_temp[$row['val1']]['search_mode'] = $row['val2'];
					}
				}
				//==================================================================

				//==================================================================
				// Recover column alignment : ALI
				//==================================================================
				if(isset($result_array['ALI']))
				{
					foreach($result_array['ALI'] as $row)
					{
						$column_temp[$row['val1']]['alignment'] = $row['val2'];
					}
				}
				//==================================================================

				/*foreach($result_array as $key => $row)
					{
						switch($row['type'])
						{
							case 'IEQ':
								;
								break;
							case 'IBT':
								;
								break;
							case 'EEQ':
								;
								break;
							case 'EBT':
								;
								break;
							case 'DMD':
								// Display Mode
								($row['val2'] == '') ? $val_dmd = false : $val_dmd = true;
								$val_dmd = false;
								$column_temp[$row['val1']]['display'] = $val_dmd;
								break;
							case 'QSC':
								// Quick search
								$column_temp[$row['val1']]['filter']['input']['filter'] = $row['val1'];
								break;
							case 'SMD':
								// Search mode
								$column_temp[$row['val1']]['search_mode'] = $row['val2'];
								break;
							case 'CPS':
								// Column position
								// For each column set the position
								$column_temp[$row['val1']] = $this->c_columns[$this->get_id_column($row['id_column'])];
								break;
							case 'ORD':
								// Order
								$column_temp[$row['val3']]['order_by'] = $row['val2'];
								$column_temp[$row['val3']]['order_priority'] = $row['val1'];
								break;
							case 'SIZ':
								;
								break;
						}
					}
					/**===================================================================*/
				ksort($column_temp);

				$this->c_columns = $column_temp;
			}
		}
	}
	/**===================================================================*/

	/**==================================================================
	 * Check if any custom view is already recorded
	 * Return true if any filter recorded
	====================================================================*/
	private function any_filter()
	{
		//==================================================================
		// Count custom filter recorded in your current lisha
		//==================================================================
		$sql = "
							SELECT
								1
							FROM
								".__LISHA_TABLE_FILTER__."
							WHERE 1 = 1
								AND `id` = '".$this->protect_sql($this->c_id,$this->link)."'
								";
		$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
		//==================================================================

		if($this->rds_num_rows($this->resultat) > 0)
		{
			// Not grey
			return true;
		}
		else
		{
			// Greyed
			return false;
		}
	}
	/**===================================================================*/

	/**==================================================================
	 * in_array_lisha
	 * Search value into Array(Array(table0,field0),Array(table1,field1)...)
	 * @valeur      :   Name of filed to find
	 * @tab         :   Multi dimensional array
	 * return table or alias name or false if field name not found
	====================================================================*/
	private function in_array_lisha($valeur,&$tab)
	{
		foreach ($tab as $val)
		{
			if($val[1]==$valeur)
			{
				if($val[0] == null)
				{
					$retour = 'null';
				}
				else
				{
					$retour = $val[0];
				}

				return $retour;
			}
		}
		return false;
	}
	/**===================================================================*/


	/**==================================================================
	 * prepare_query
	 * build and run query with all WHERE condition
	 * @ad_where			: Extra clause condition
	 * @p_only_count        : only return numbers of rows found
	====================================================================*/
	private function prepare_query($add_where = '',$p_active_limit = true, $p_only_count = false)
	{
		//==================================================================
		// Get Column filter
		//==================================================================
		$sql_filter = '';

		foreach($this->c_columns AS $column_value)
		{
			if(isset($column_value['filter']))
			{
				foreach ($column_value['filter'] AS $filter_value)
				{
					//==================================================================
					// localization decimal symbol
					//==================================================================
					//float or numeric (same)
					if ($column_value['data_type'] == __FLOAT__)
					{
						$decimal_symbol = $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'];
						if ($decimal_symbol != '.')
						{
							// if not decimal point, transform decimal symbol in point for search in database
							$filter_value = str_replace($decimal_symbol, '.', $filter_value);
						}
					}
					//==================================================================
					switch($column_value['search_mode'])
					{
						case __EXACT__:
							$sql_filter .= " AND ".$column_value['before_as']." = '".$this->protect_sql($this->replace_chevrons($filter_value['filter']),$this->link)."'";
							break;
						case __CONTAIN__:
							$sql_filter .= ' AND '.$column_value['before_as'].' '.$this->get_like(__PERCENT__.$this->protect_sql($this->replace_chevrons(str_replace('_','\\_',str_replace('%','\\%',str_replace("\\","\\\\",$filter_value['filter']))),true),$this->link).__PERCENT__);
							break;
						case __PERCENT__:
							$sql_filter .= ' AND '.$column_value['before_as'].' '.$this->get_like($this->protect_sql($this->replace_chevrons($filter_value['filter']),$this->link));
							break;
						case __GT__:
							$sql_filter .= " AND ".$column_value['before_as']." > '".$this->protect_sql($this->replace_chevrons($filter_value['filter']),$this->link)."'";
							break;
						case __LT__:
							$sql_filter .= " AND ".$column_value['before_as']." < '".$this->protect_sql($this->replace_chevrons($filter_value['filter']),$this->link)."'";
							break;
						case __GE__:
							$sql_filter .= " AND ".$column_value['before_as']." >= '".$this->protect_sql($this->replace_chevrons($filter_value['filter']),$this->link)."'";
							break;
						case __LE__:
							$sql_filter .= " AND ".$column_value['before_as']." <= '".$this->protect_sql($this->replace_chevrons($filter_value['filter']),$this->link)."'";
							break;
						case __NULL__:
							$sql_filter .= ' AND '.$column_value['before_as'].' IS NULL ';
							break;
					}
				}
			}
		}
		//==================================================================

		//==================================================================
		// Merge query condition for global search
		// Only if a global search string is defined
		//==================================================================
		if($this->string_global_search != "")
		{
			$sql_filter_global = ' AND (0=1';

			foreach($this->c_columns AS $column_value)
			{
				if($column_value['display'] != __HIDE__)
				{
					// Only displayed column are involved

					$tmp_result = $this->string_global_search;

					// Transform string for date localization
					if($column_value['data_type'] == __DATE__)
					{
						$tmp_result = $this->convert_localized_date_to_database_format($column_value["original_order"],$this->string_global_search);
						if($tmp_result == "-1")
						{
							// No localized date format conversion possible then keep original value
							$tmp_result = $this->string_global_search;
						}
					}

					$sql_filter_global .= ' OR '.$column_value['before_as'].' '.$this->get_like(__PERCENT__.$this->protect_sql($this->replace_chevrons(str_replace('_','\\_',str_replace('%','\\%',str_replace("\\","\\\\",$tmp_result))),true),$this->link).__PERCENT__);
				}
			}
			$sql_filter_global .= ')';

			$sql_filter = $sql_filter.$sql_filter_global;
		}
		//==================================================================

		$query_final_pos = strripos($this->matchcode['__main_query'][0], $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);
		$my_query = 'SELECT COUNT(1) AS `TOTAL` '.substr($this->matchcode['__main_query'][0],$query_final_pos).$sql_filter.$add_where;
		$this->exec_sql($my_query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

		$row = $this->rds_fetch_array($this->resultat);
		$this->c_recordset_line = $row['TOTAL'];

		$this->c_obj_graphic->set_recordser_line($this->c_recordset_line);

		if($p_only_count)
		{
			$my_query = 'SELECT COUNT(1) AS `TOTAL` '.substr($this->matchcode['__main_query'][0],$query_final_pos).$sql_filter.$add_where;
			$this->exec_sql($my_query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

			$row = $this->rds_fetch_array($this->resultat);
			$this->c_recordset_line = $row['TOTAL'];

			$this->c_obj_graphic->set_recordser_line($this->c_recordset_line);

			return $this->c_recordset_line;
		}
		//==================================================================

		//==================================================================
		// Build column order array
		//==================================================================
		$order = '';
		$array_order = array();

		foreach($this->c_columns as $key => $value)
		{
			if($value['order_by'] != false)
			{
				$array_order[$value['order_priority']] = array("column" => $key,"order_by" => $value['order_by']);
			}
		}

		// Order by priority
		ksort($array_order);
		//==================================================================

		//==================================================================
		// Build ORDER BY string for database engine
		//==================================================================
		$i = 0;
		foreach($array_order AS $value)
		{
			if($i == 0)
			{
				//$order .= ' ORDER BY '.$str_before.$this->get_quote_col($this->c_columns[$value['column']]['sql_as']).$str_after.' '.$value['order_by'];
				$order .= ' ORDER BY '.$this->c_columns[$value['column']]['before_as'].' '.$value['order_by'];
			}
			else
			{
				//$order .= ','.$str_before.$this->get_quote_col($this->c_columns[$value['column']]['sql_as']).$str_after.' '.$value['order_by'];
				$order .= ','.$this->c_columns[$value['column']]['before_as'].' '.$value['order_by'];
			}
			$i = $i + 1;
		}
		//==================================================================

		//==================================================================
		// Build query primary key string by simple concatenation
		//==================================================================
		$key_concatenation = "''";

		if(is_array($this->c_db_keys))
		{
			foreach($this->c_db_keys as $key_value)
			{
				$key_concatenation .= ',`'.$key_value.'`';
			}
		}
		//==================================================================

		//==================================================================
		// Custom column format ( Date, currency... )
		//==================================================================
		$temp_columns = '';
		foreach($this->c_columns as $value)
		{

			if ($value['data_type'] == __DATE__)
			{
				if(isset($value['date_format']))
				{
					$final_date_format = $value['date_format'];
				}
				else
				{
					// No custom date format then use country localization format
					$final_date_format = $_SESSION[$this->c_ssid]['lisha']['date_format'];
				}

				$str_final = $this->get_date_format($this->get_quote_col($value['sql_as']),$final_date_format);
			}
			else
			{
				// In all other case
				$str_final = $this->get_quote_col($value['sql_as']);
			}
			$temp_columns .= $str_final.' AS `'.$value['sql_as'].'`,';
		}
		$temp_columns = substr($temp_columns,0,-1);
		//==================================================================

		//==================================================================
		// Switch on / off page limit
		//==================================================================
		if($p_active_limit)
		{
			$my_limit = $this->get_limit($this->c_limit_min,$this->c_limit_max);
		}
		else
		{
			$my_limit = '';
		}
		//==================================================================

		//==================================================================
		// Execute query
		//==================================================================
		$prepared_query = 'SELECT '.$temp_columns.',CONCAT('.$key_concatenation.') AS `lisha_internal_key_concat` FROM ('.$this->matchcode['__main_query'][0].$sql_filter.' '.$add_where.' '.$order.' '.$my_limit.') deriv WHERE 1 = 1 ';

		$this->c_prepared_query = $prepared_query;

		$this->exec_sql($prepared_query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

		$this->c_page_qtt_line = $this->rds_num_rows($this->resultat);
		//==================================================================

	}
	/**===================================================================*/


	/**==================================================================
	 * Convert a localized date into database format
	 *
	 * @p_column			: column id to get custom date format if any
	 * @p_input				: input area in column header
	====================================================================*/
	public function convert_localized_date_to_database_format($p_column,$p_input)
	{
		if(isset($this->c_columns[$p_column]['date_format']))
		{
			$final_date_format = $this->c_columns[$p_column]['date_format'];
		}
		else
		{
			// No custom date format then use country localization format
			$final_date_format = $_SESSION[$this->c_ssid]['lisha']['date_format'];
		}

		// Year format
		//SELECT STR_TO_DATE('00/00/2013','%d/%m/%Y')  AS `result`

		$str_final = $this->get_str_to_date_format(rawurldecode($p_input),$final_date_format);

		$query = "SELECT ".$str_final."  AS `result`";

		$result = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);
		$row = $this->rds_fetch_array($result);
		if($row['result'] == '' || strpos($row['result'],'0000') !== false || strlen($p_input) < 7 )
		{
			// No conversion possible.... return -1 as error
			return "-1";
		}
		else
		{
			return $row['result'];
		}
	}
	/**===================================================================*/


	/**==================================================================
	 * Convert a database date to localized date format
	 *
	 * @p_column			: column id to get custom date format if any
	 * @p_input				: date to convert
	====================================================================*/
	public function convert_database_date_to_localized_format($p_column,$p_input)
	{
		if(isset($this->c_columns[$p_column]['date_format']))
		{
			$final_date_format = $this->c_columns[$p_column]['date_format'];
		}
		else
		{
			// No custom date format then use country localization format
			$final_date_format = $_SESSION[$this->c_ssid]['lisha']['date_format'];
		}

		// Year format
		//SELECT STR_TO_DATE('00/00/2013','%d/%m/%Y')  AS `result`

		$str_final = $this->get_date_format("'".rawurldecode($p_input)."'",$final_date_format);

		// Build query
		$query = "SELECT ".$str_final."  AS `result`";

		$result = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);
		$row = $this->rds_fetch_array($result);

		if($row['result'] == '' || strpos($row['result'],'0000') !== false || strlen($p_input) < 7)
		{
			// No conversion possible.... return -1 as error
			return "-1";
		}
		else
		{
			return $row['result'];

		}
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_lisha
	 * Need a comment...
	====================================================================*/
	public function generate_lisha()
	{
		// Draw the lisha
		$this->set_is_lovable_columns();

		return $this->c_obj_graphic->draw_lisha($this->resultat,false,null,false);
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_lmod_content
	 * Need a comment...
	====================================================================*/
	public function generate_lmod_content()
	{
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat))."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_lmod_form
	 * Internal iterative call to class_graphic
	====================================================================*/
	public function generate_lmod_form()
	{
		return $this->c_obj_graphic->generate_lmod_form();
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_style
	 * @p_bal_style		: To analyse...
	 * Internal iterative call to class_graphic
	====================================================================*/
	public function generate_style($p_bal_style = true)
	{
		return $this->c_obj_graphic->generate_style($p_bal_style);
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_lisha_json_param
	 * @p_generate_column	: To analyse...
	 * @p_generate_line		: To analyse...
	====================================================================*/
	public function generate_lisha_json_param($p_generate_column = true,$p_generate_line = true, $p_ok_button = 'ok')
	{
		$json_base = 'lisha.'.$this->c_id;

		$json = '';

		if($p_generate_column && $p_generate_line) $json  = $json_base.' = new Object();';
		$json .= $json_base.'.software_version = \''.__LISHA_APPLICATION_RELEASE__.'\';';
		$json .= $json_base.'.dir_obj = \''.$this->c_dir_obj.'\';';
		$json .= $json_base.'.ssid = \''.$this->c_ssid.'\';';
		$json .= $json_base.'.qtt_column = '.$this->get_qtt_column().';';
		$json .= $json_base.'.total_page = '.ceil($this->c_recordset_line / $this->c_nb_line).';';
		$json .= $json_base.'.active_page = '.$this->matchcode['__current_page'][0].';';
		$json .= $json_base.'.theme = \''.$this->matchcode['__id_theme'][0].'\';';
		$json .= $json_base.'.width = '.$this->c_width.';';
		$json .= $json_base.'.width_unity = \''.$this->c_w_unity.'\';';
		$json .= $json_base.'.height = '.$this->c_height.';';
		$json .= $json_base.'.height_unity  = \''.$this->c_h_unity.'\';';
		$json .= $json_base.'.menu_opened_col  = false;';					// Column opened on a menu
		$json .= $json_base.'.menu_quick_search = false;';					// Quick search menu open
		$json .= $json_base.'.menu_quick_search_col = false;';				// Quick search column
		$json .= $json_base.'.menu_left = 0;';								// Left value for menu on display menu
		$json .= $json_base.'.last_checked = 0;';							// Last line checked
		$json .= $json_base.'.mode = "'.$this->matchcode['__display_mode'][0].'";';					// LMOD or NMOD
		$json .= $json_base.'.lmod_opened = true;';							// LMOD or NMOD or __CMOD__
		$json .= $json_base.'.return_mode = "'.$this->matchcode['__return_mode'][0].'";';	// Mode of return (in LMOD mode)
		$json .= $json_base.'.c_col_return = "'.$this->matchcode['__return_column_id'][0].'";';
		$json .= $json_base.'.TypeOfChild = "'.$this->c_type_internal_lisha.'";';   // Kind of sub lisha type
		$json .= $json_base.'.c_col_return_id = "'.$this->get_id_column($this->matchcode['__return_column_id'][0]).'";';
		$json .= $json_base.'.c_position_mode = "'.$this->matchcode['__internal_HTML_position'][0].'";';
		$json .= $json_base.'.user_doc = "'.$this->matchcode['__active_user_doc'][0].'";';

		$json .= $json_base.'.button = new Object();';
		$json .= $json_base.'.button.valide = "'.$p_ok_button.'";';


		if(!is_null($this->matchcode['__column_name_focus'][0]))
		{
			$json .= $json_base.'.default_input_focus = '.$this->get_id_column($this->matchcode['__column_name_focus'][0]).';';
		}
		else
		{
			$json .= $json_base.'.default_input_focus = false;';
		}

		if($p_generate_column)
		{
			$json .= $json_base.'.columns = new Object();';
			$json .= $json_base.'.selected_column = new Object();';
		}

		if($p_generate_line)
		{
			$json .= $json_base.'.selected_line = new Object();';
			$i = 1;

			if(isset($this->c_selected_lines) && is_array($this->c_selected_lines))
			{
				foreach($this->c_selected_lines['keys'] as $value)
				{
					$json .= $json_base.'.selected_line.L'.$i.' = new Object();';
					$json .= $json_base.'.selected_line.L'.$i.'.key = new Object();';
					foreach($value as $key_key => $value_key)
					{
						$json .= $json_base.'.selected_line.L'.$i.'.key.'.$key_key.' = \''.$this->protect_js_txt($value_key).'\';';
					}
					$json .= $json_base.'.selected_line.L'.$i.'.selected = true;';
					$i = $i + 1;
				}
			}
		}

		$json .= $json_base.'.stop_click_event = false;';
		$json .= $json_base.'.time_input_search = false;';
		$json .= $json_base.'.input_search_selected_line = 0;';
		$json .= $json_base.'.qtt_line = '.$this->c_page_qtt_line.';';
		$json .= $json_base.'.max_line_per_page = '.$this->matchcode['__max_lines_by_page'][0].';';
		$json .= $json_base.'.lisha_child_opened = false;';
		$json .= $json_base.'.edit_mode = '.$this->c_edit_mode.';';

		//==================================================================
		// Lisha events
		//==================================================================
		if(is_array($this->c_lisha_action))
		{
			$json .= $json_base.'.event = new Object();';

			foreach($this->c_lisha_action as $event => $value_event)
			{
				foreach ($value_event as $value)
				{
					$json .= $json_base.'.event.evt'.$event.' = new Object();';
					$json .= $json_base.'.event.evt'.$event.'.'.$value['lisha'].' = new Object();';
					$json .= $json_base.'.event.evt'.$event.'.'.$value['lisha'].'.action = new Object();';

					$i = 0;
					foreach ($value['ACTION'] as $action)
					{
						$json .= $json_base.'.event.evt'.$event.'.'.$value['lisha'].'.action.a'.$i.' = new Object();';
						$json .= $json_base.'.event.evt'.$event.'.'.$value['lisha'].'.action.a'.$i.'.exec = \''.str_replace("'","\'",$action).'\';';
						$json .= $json_base.'.event.evt'.$event.'.'.$value['lisha'].'.action.a'.$i.'.moment = '.$value['MOMENT'].';';

						$i = $i + 1;
					}
				}
			}
		}
		//==================================================================

		if($p_generate_column) $json .= $this->generate_json_column();
		if($p_generate_line) $json .= $this->generate_json_line();

		return $json;
	}
	/**===================================================================*/


	/**==================================================================
	 * get_qtt_column
	 * Return total of displayed columns
	====================================================================*/
	private function get_qtt_column()
	{
		$qtt = 0;
		foreach ($this->c_columns as $value)
		{
			if($value['display'] == __DISPLAY__)
			{
				$qtt = $qtt + 1;
			}
		}
		return $qtt;
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_json_column
	====================================================================*/
	public function generate_json_column()
	{
		$this->set_is_lovable_columns();

		$json_base = 'lisha.'.$this->c_id;

		$json = $json_base.'.columns = new Object();';

		foreach($this->c_columns as $key => $value)
		{
			if($value['display'] == __DISPLAY__)
			{
				$json .= $json_base.'.columns.c'.$key.' = new Object();';
				$json .= $json_base.'.columns.c'.$key.'.order = "'.$value['order_by'].'";';
				$json .= $json_base.'.columns.c'.$key.'.id = '.$key.';'; // Used by columns move
				$json .= $json_base.'.columns.c'.$key.'.idorigin = '.$value['original_order'].';'; // SRX add original column position
				$json .= $json_base.'.columns.c'.$key.'.search_mode = \''.$value['search_mode'].'\';';
				$json .= $json_base.'.columns.c'.$key.'.alignment = \''.$value['alignment'].'\';';
				$json .= $json_base.'.columns.c'.$key.'.data_type = \''.$value['data_type'].'\';';

				if(isset($value['filter']) && count($value['filter']) > 0)
				{
					$json .= $json_base.'.columns.c'.$key.'.advanced_filter = true;';
				}
				else
				{
					$json .= $json_base.'.columns.c'.$key.'.advanced_filter = false;';
				}

				if($value['data_type'] == __DATE__)
				{
					if(!isset($value['date_format']))
					{
						// No custom date format defined, choose default one
						$tmp = $_SESSION[$this->c_ssid]['lisha']['date_format'];
					}
					else
					{
						$tmp = $value['date_format'];
					}
					$json .= $json_base.'.columns.c'.$key.'.date_format = \''.$tmp.'\';';
				}

				if(isset($value['lov']))
				{
					$json .= $json_base.'.columns.c'.$key.'.lov_perso = true;';
					$json .= $json_base.'.columns.c'.$key.'.lov_title = \''.str_replace("'","\'",$value['lov']['title']).'\';';

					//==================================================================
					// Check if the column is lovable
					// Means that all TAGLOV are solvable
					//==================================================================
					if($value['is_lovable'])
					{

						$json .= $json_base.'.columns.c'.$key.'.is_lovable = true;';

					}
					else
					{
						$json .= $json_base.'.columns.c'.$key.'.is_lovable = false;';
					}
					//==================================================================
				}
				else
				{
					$json .= $json_base.'.columns.c'.$key.'.is_lovable = true;';
				}
			}
		}

		$json .= $json_base.'.last_column = '.($this->get_last_column()+1).';';

		return $json;
	}
	/**===================================================================*/


	/**==================================================================
	 * get_last_column
	 * return key of last column
	====================================================================*/
	private function get_last_column()
	{
		return count($this->c_columns); // SRX optimization
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_json_line
	 * Build json structure to transfer php data to javascript space
	====================================================================*/
	public function generate_json_line()
	{
		$json_base = 'lisha.'.$this->c_id;
		$json = $json_base.'.lines = new Object();';

		$i = 1;

		if($this->rds_num_rows($this->resultat) > 0)
		{
			$this->rds_data_seek($this->resultat,0);
		}

		// Reset color variables
		$current_group_value = null;
		$i_color = 0;

		// Browse the recorset
		while($row = $this->rds_fetch_array($this->resultat))
		{
			$json .= $json_base.'.lines.L'.$i.' = new Object();';
			$json .= $json_base.'.lines.L'.$i.'.key = new Object();';
			$json .= $json_base.'.lines.L'.$i.'.colorkey = new Object();';

			// Browse the PRIMARY key
			if(is_array($this->c_db_keys))
			{
				foreach($this->c_db_keys as $value)
				{
					$json .= $json_base.'.lines.L'.$i.'.key.'.$value.' = \''.str_replace("'","\\'",$row[$value]).'\';';
				}
			}

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

			// Build json color structure for each line
			$json .= $json_base.'.lines.L'.$i.'.colorkey = \''.$current_group_value.$i_color.'\';';

			$i = $i + 1;
			$i_color = $i_color + 1;
		}

		// Place the cursor on the first row of the recordset
		if($this->rds_num_rows($this->resultat) > 0)
		{
			$this->rds_data_seek($this->resultat,0);
		}
		return $json;
	}
	/**===================================================================*/


	/**
	 * Generate the javascript body
	 */
	public function generate_js_body()
	{
		$js = $this->generate_table_color();
		$js .= $this->generate_lisha_json_param();

		if($this->matchcode['__display_mode'][0] == __NMOD__)
		{
			$js .= "document.getElementById('liste_".$this->c_id."').onscroll = function(){lisha_horizontal_scroll('".$this->c_id."');};";
		}

		return $js;
	}



	public function draw_lisha_js()
	{
		if($this->matchcode['__display_mode'][0] != __LMOD__)
		{
			$js = "size_table('".$this->c_id."');";
		}
		else
		{
			$js = '';
		}


		return $js;
	}

	/**==================================================================
	 * generate_header
	 * Build HTML header for each lisha ( style... )
	====================================================================*/
	public function generate_header($p_return_str = false)
	{
		if($p_return_str)
		{
			$header = $this->generate_style(false);		// Unique
		}
		else
		{
			$header = $this->generate_style(true);
		}

		$header .= $this->new_graphic_lisha();	// Unique

		if($p_return_str)
		{
			return $header;
		}
		else
		{
			echo $header;
			return true;
		}
	}
	/**===================================================================*/

	public function generate_public_header()
	{
		$this->include_stylesheet();	// Doublon
		$this->include_js_files();		// Doublon
		$this->generate_text();			// Doublon

		$this->javascript_variable();
	}

	public function lisha_generate_js_body($p_return_str = false)
	{
		$js = '';
		if(!$p_return_str)
		{
			$js .= '<script type="text/javascript">';
		}


		$js .= $this->generate_js_body();
		$js .= $this->draw_lisha_js();

		if($this->c_time_timer_refresh != null)
		{
			$js .= 'lisha.'.$this->c_id.'.refresh_auto = window.setInterval(\'lisha_refresh(\\\''.$this->c_id.'\\\');\','.$this->c_time_timer_refresh.');';
		}

		if(!$p_return_str)
		{
			$js .= '</script>';
		}

		if($p_return_str)
		{
			return $js;
		}
		else
		{
			echo $js;
			return false;
		}
	}


	/**==================================================================
	 * javascript_variable
	 * Build javascript global variable for each lisha
	====================================================================*/
	private function javascript_variable()
	{
		$html = '<script type="text/javascript">';

		$html .= 'varlisha_'.$this->c_id.' = new Object();';
		$html .= 'varlisha_'.$this->c_id.'_child = new Object();';

		$html .= 'varlisha_'.$this->c_id.'.CurrentCellUpdate = "";';
		$html .= 'varlisha_'.$this->c_id.'.CellUpdateOriginValue = "";';
		$html .= 'varlisha_'.$this->c_id.'.CurrentCellCompel = "";';
		$html .= 'varlisha_'.$this->c_id.'.CurrentCellName = "";';
		$html .= 'varlisha_'.$this->c_id.'.scrollTop = 0;';
		$html .= 'varlisha_'.$this->c_id.'.globalsearch = "off";';
		//$html .= 'varlisha_'.$this->c_id.'.lock = "";';

		$html .= 'varlisha_'.$this->c_id.'_child.CurrentCellUpdate = "";';
		$html .= 'varlisha_'.$this->c_id.'_child.CellUpdateOriginValue = "";';
		$html .= 'varlisha_'.$this->c_id.'_child.CurrentCellCompel = "";';
		$html .= 'varlisha_'.$this->c_id.'_child.CurrentCellName = "";';
		$html .= 'varlisha_'.$this->c_id.'_child.scrollTop = 0;';
		$html .='</script>';
		echo $html;
	}
	/**===================================================================*/


	/**==================================================================
	 * generate_text
	 * Build text in php and javascript memory
	====================================================================*/
	private function generate_text()
	{
		$html = '<script type="text/javascript">';
		$html .= 'lisha = new Object();';
		$html .= 'lis_lib = new Array();';

		$i18n_js =  file_get_contents($this->c_dir_obj.'/language/'.$this->c_lng.'.json');
		$i18n = json_decode($i18n_js,true);

		foreach($i18n as $key => $value)
		{
			$_SESSION[$this->c_ssid]['lisha']['lib'][$key] = $value;
			$html .= '			lis_lib['.$key.'] = \''.str_replace("'","\'",$value).'\';';
		}

		$html .='</script>';

		echo $html;
	}
	/**===================================================================*/

	public function include_stylesheet()
	{
		$link =  '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/blue/main.css">';
		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/blue/icon.css">';

		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/green/main.css">';
		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/green/icon.css">';

		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/grey/main.css">';
		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/grey/icon.css">';

		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/red/main.css">';
		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/red/icon.css">';

		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/lisha_common.css">';
		$link .= '<link rel="stylesheet" type="text/css" href="'.$this->c_dir_obj.'/css/object/lisha_msgbox.css">';

		echo $link;
	}

	public function include_js_files()
	{
		$dir = $this->c_dir_obj;

		echo '	<script type="text/javascript" src="'.$dir.'/js/dom.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/macro.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/event.js"></script>
				 ';

		//==================================================================
		// Defined all user php constants in javascript space
		//==================================================================
		$defined_user_constants = get_defined_constants(true);
		echo '<script type="text/javascript">';
		foreach($defined_user_constants['user'] as $key => $value)
		{
			// Security issue !!!!!! Remove database information and access from javascript
			if	(
				strpos($key, 'ACCESS') == false
				and strpos($key, 'DATABASE') == false
				)
			{
				echo "var ".$key." = '".$value."';
				";
			}
		}
		echo "</script>";
		//==================================================================

		echo '
					<script type="text/javascript" src="'.$dir.'/js/json2.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/affichage.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/page.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/table.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/column.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/lmod.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/input_col.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/lisha_child.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/object/lisha_menu.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/object/lisha_calendar.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/object/lisha_msgbox.js"></script>
					<script type="text/javascript" src="'.$dir.'/js/edit.js"></script>
					<script type="text/javascript" src="'.$dir.'/ajax/common/ajax_generique_dev.js"></script>';
	}

	public function generate_lmod_header($p_display = true)
	{
		if($p_display)
		{
			echo $this->c_obj_graphic->generate_lmod_header();
		}
		else
		{
			return $this->c_obj_graphic->generate_lmod_header();
		}

		return null;
	}


	/**==================================================================
	 * get_id_column
	 * @param $column_name
	 * @return column id or null if not found
	 */
	private function get_id_column($column_name)
	{
		if(!is_null($column_name) && isset($this->c_columns))
		{
			foreach($this->c_columns as $key => $value)
			{
				if(isset($value["sql_as"]) && $value["sql_as"] == $column_name)
				{
					return $key;
				}
			}
		}
		else
		{
			// Nothing found
			return null;
		}
		return null;
	}
	/**===================================================================*/


	/**==================================================================
	 * remove all columns order by
	====================================================================*/
	private function clear_all_order()
	{
		foreach($this->c_columns as &$value)
		{
			$value["order_by"] = false;
			$value["order_priority"] = false;
		}

		$this->c_obj_graphic->clear_all_order();

		return null;
	}
	/**===================================================================*/


	/**==================================================================
	 * lisha_generate_calendar
	 * @p_column 		: column identifier
	 * @p_input			: input string of column header
	 * @p_year 			: numeric year in for digits
	 * @p_month			: numeric month two digits
	 * @p_day			: numeric day of month
	====================================================================*/
	public function lisha_generate_calendar($p_column,$p_input,$p_year = null,$p_month = null,$p_day = null)
	{
		if(strlen($p_input) > 0)
		{
			$sortie = $this->convert_localized_date_to_database_format($p_column,$p_input);
			if($sortie != '-1')
			{
				$p_year = substr($sortie,0,4);
				$p_month = substr($sortie,5,2);
				$p_day = substr($sortie,8,2);
			}
		}

		if(is_null($p_year)) $p_year = date('Y');
		if(is_null($p_month)) $p_month = date('n');
		if(is_null($p_day)) $p_day = date('j');

		return $this->c_obj_graphic->lisha_generate_calendar($p_column,$p_year,$p_month,$p_day);
	}
	/**===================================================================*/


	/**==================================================================
	 * recover_cell method
	 * Return content of clicked cell
	 * @param $array_key		: clicked cell json format of primary key
	 * @param $column			: relative column position
	====================================================================*/
	public function recover_cell($array_key, $column)
	{

		$array_key = json_decode($array_key);

		$string_where = '';

		foreach($array_key as $clef => $value)
		{
			$string_where .= ' AND `'.$this->matchcode['__update_table_name'][0].'`.`'.$clef.'` = \''.str_replace("'","\\'",$value).'\'';
		}

		$column_compel = '';
		$temp_columns = '';
		$column_data_type = $this->c_columns[$column]['data_type'];

		$column_name = $this->c_columns[$column]["sql_as"];

		if(isset($this->c_columns[$column]["rw_flag"]))
		{
			$column_compel = $this->c_columns[$column]["rw_flag"];
		}

		$column_display_name = $this->c_columns[$column]["name"];


		if(isset($this->c_columns[$column]['select_function']))
		{
			// Special select function defined
			$temp_columns .= str_replace('__COL_VALUE__',$this->get_quote_col($column_name),$this->c_columns[$column]['select_function']).' AS '.$this->get_quote_col($column_name);
		}
		else
		{
			// Localization date format
			if ($this->c_columns[$column]['data_type'] == __DATE__)
			{
				if(isset($this->c_columns[$column]['date_format']))
				{
					$final_date_format = $this->c_columns[$column]['date_format'];
				}
				else
				{
					// No custom date format then use country localization format
					$final_date_format = $_SESSION[$this->c_ssid]['lisha']['date_format'];
				}

				$str_final = $this->get_date_format('`'.$this->matchcode['__update_table_name'][0].'`.`'.$column_name.'`',$final_date_format);
				$temp_columns .= $str_final.' AS `'.$column_name.'`';
			}
			else
			{
				// all other columns
				$temp_columns .= '`'.$this->matchcode['__update_table_name'][0].'`.`'.$column_name.'` AS `'.$column_name.'`';
			}
		}

		$query_final_pos = strripos($this->matchcode['__main_query'][0], $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);
		//$sql =  'SELECT COUNT( DISTINCT '.$this->c_columns[$column]['before_as'].' ) AS `total` '.substr($this->matchcode['__main_query'][0],$query_final_pos).' AND '.$this->c_columns[$column]['before_as'].' '.$this->get_like($this->c_columns[$column]['search_mode'].$this->protect_sql($this->escape_special_char($txt),$this->link).$this->c_columns[$column]['search_mode']).' '.$sql_filter;
		$prepared_query = 'SELECT DISTINCT '.$temp_columns.substr($this->matchcode['__main_query'][0],$query_final_pos).$string_where;


		$p_result_header = $this->exec_sql($prepared_query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

		$row = $this->rds_fetch_array($p_result_header);

		$html = current($row);

		// Checkbox
		if($column_data_type == __CHECKBOX__)
		{
			if($html == "0")
			{
				$html = "1";
			}
			else
			{
				$html = "0";
			}

			$set_string = '`'.$column_name.'` = \''.$html.'\'';

			$prepared_query = 'UPDATE '.$this->matchcode['__update_table_name'][0].' SET '.$set_string.' WHERE 1 = 1 '.$string_where;
			$this->exec_sql($prepared_query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);
		}

		// Localization Float / Numeric format
		if($column_data_type == __FLOAT__)
		{
			$decimal_symbol = $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'];
			if ($decimal_symbol != '.')
			{
				// if not decimal point, transform point in decimal symbol for display
				$html = str_replace('.', $decimal_symbol, $html);
			}
		}

		$retour = array(
			"HTML" => $html,
			"COMPEL" => $column_compel,
			"DISPLAY_NAME" => $column_display_name,
			"DATA_TYPE" => $column_data_type,
			"DECIMAL_SYMBOL" => $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'],
			"THOUSAND_SYMBOL" => $_SESSION[$this->c_ssid]['lisha']['thousand_symbol']
		);
		echo json_encode($retour);
	}
	/**===================================================================*/


	/**==================================================================
	 * On cell update, check if __LISTED__ column value is in lov feature query
	 *
	 * @column			: id column origin
	 * @p_value			: New value for cell to update
	====================================================================*/
	public function check_listed_compel($column,$p_value)
	{
		//error_log($column."-".$p_value);
		//$retour = array("STATUS" => $status,"VALUE" => 0, "TOTAL" => number_format($_SESSION[$ssid]['lisha'][$lisha_id]->export_total,0,'', htmlentities($_SESSION[$ssid]['lisha']['thousand_symbol'])));
		//echo json_encode($retour);

		// Need to recover all line information to do compel check


		//==================================================================
		// Search column based on idorigin
		//==================================================================
		/*
			foreach($tab_val_col as $valeur)
			{
				if($column == $value_col['original_order'])
				{
					// __LISTED__ ??
					if(isset($value_col['lov']['sql']) && $value_col['rw_flag'] == __LISTED__)
					{
					// Check if lov is solvable
					$sql_lov = $this->solve_lov_main_query($value_col['original_order']);
					// And condition to check if input value is in list
					$sql_lov = $sql_lov." AND ".$value_col['lov']['before_as']." = '".$valeur['value']."'";
					}
				}
			}
			*/
		$retour = array("STATUS" => false); // False update disable, true update enable
		echo json_encode($retour);
	}
	/**===================================================================*/


	/**==================================================================
	 * Return current date / hours format
	 *
	 * @column			: id column origin
	====================================================================*/
	public function current_date_hours($p_column)
	{
		// Localization date format
		if(isset($this->c_columns[$p_column]['date_format']))
		{
			$final_date_format = $this->c_columns[$p_column]['date_format'];
		}
		else
		{
			// No custom date format then use country localization format
			$final_date_format = $_SESSION[$this->c_ssid]['lisha']['date_format'];
		}

		$str_final = $this->get_date_format("NOW()",$final_date_format);

		$query = "SELECT ".$str_final."  AS `result`";
		$result = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);
		$row = $this->rds_fetch_array($result);

		// replace php date function by Database engine base transformation
		$my_date = $row["result"];
		$retour = array("DATE" => $my_date);
		echo json_encode($retour);
	}
	/**===================================================================*/


	/**==================================================================
	 * edit_cell method
	 * Update cell content
	 * @array_key 		: json format of array primary key of cell to update
	 * @column			: relative column position
	 * @p_value			: New value for cell to update
	====================================================================*/
	public function edit_cell($array_key, $column, $p_value)
	{
		$array_key = json_decode($array_key);

		// Force Json UTF8 protection for unknown server issue
		// TODO Have to do investigation on this point
		$p_value = json_decode($p_value);
		$string_where = '';
		$custom_function = '';

		//==================================================================
		// Build primary key query part to update right row
		//==================================================================
		foreach($array_key as $clef => $value)
		{
			$string_where .= 'AND `'.$clef.'` = \''.str_replace("'","\\'",$value).'\'';
		}
		$string_where = substr($string_where,4);
		//==================================================================

		$column_name = $this->c_columns[$column]["sql_as"];

		// Localization date format
		if($this->c_columns[$column]['data_type'] == __DATE__)
		{
			$my_date_value = $this->convert_localized_date_to_database_format($column, $p_value);
			if( $my_date_value != "-1")
			{
				$p_value = $my_date_value;
			}
			else
			{
				// Date error conversion
			}
		}

		// Over load with custom function : rw_function
		if(isset($this->c_columns[$column]['rw_function']))
		{
			// Special select function defined
			$p_value = str_replace('__COL_VALUE__',$this->protect_sql($this->my_htmlentities($p_value),$this->link),$this->c_columns[$column]['rw_function']);
			$custom_function = 'X';
		}

		// Localization Float / numeric format
		if($this->c_columns[$column]['data_type'] == __FLOAT__)
		{
			$decimal_symbol = $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'];
			if ($decimal_symbol != '.')
			{
				// if not decimal point, transform decimal symbol in point for update database
				$p_value = str_replace($decimal_symbol, '.', $p_value);
			}
		}

		// Issue of htmlentities between &hearts; and ? and <html></html>
		if($custom_function == "")
		{
			$p_value = $this->protect_sql($this->my_htmlentities($p_value),$this->link);
		}

		if($p_value == "")
		{
			$set_string = '`'.$column_name.'` = NULL ';
		}
		else
		{
			if($custom_function == "")
			{
				$set_string = '`'.$column_name.'` = \''.$p_value.'\'';
			}
			else
			{
				$set_string = '`'.$column_name.'` = '.$p_value;
			}
		}

		$prepared_query = 'UPDATE '.$this->matchcode['__update_table_name'][0].' SET '.$set_string.' WHERE '.$string_where;

		$this->exec_sql($prepared_query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

		echo $prepared_query;
	}
	/**===================================================================*/


	/**==================================================================
	 * edit_lines method
	 * Edit selected lines
	 * @json_lines		: json format of selected lines
	====================================================================*/
	public function edit_lines($json_lines)
	{
		// Define the lisha mode
		$this->c_edit_mode = __EDIT_MODE__;

		// Set the selected lines to edit
		$this->define_selected_line($json_lines);

		// Record current page position
		$this->last_page_position = $this->read_attribute('__current_page');
		$this->last_limit_min =  $this->c_limit_min;

		// Reset the filters
		$this->reset_filters();

		// Mandatory to stay on first page when updating data ( no more navigation tool bar )
		// Force first page for edition // SRX_fix_edit_row_on_not_first_page
		$this->define_attribute('__current_page',1);
		$this->define_limit_min(0);

		//==================================================================
		// Build query SET part
		//==================================================================
		$sql = 'SELECT ';
		$i_sql = 0;

		foreach($this->c_columns as $val_col)
		{
			// Special column $this->matchcode['__column_name_group_of_color'][0]
			if(
				$val_col['sql_as'] != $this->matchcode['__column_name_group_of_color'][0]
				&& !isset($val_col['rw_flag']) || (isset($val_col['rw_flag'])
					&& $val_col['rw_flag'] != __FORBIDDEN__
					&& $val_col['display']))
			{
				if($i_sql > 0)
				{
					$sql .= ',';
				}

				$temp_columns = '';

				if ($val_col['data_type'] == __DATE__)
				{
					// Localization date format
					if(isset($val_col['date_format']))
					{
						$final_date_format = $val_col['date_format'];
					}
					else
					{
						// No custom date format then use country localization format
						$final_date_format = $_SESSION[$this->c_ssid]['lisha']['date_format'];
					}

					$str_final = $this->get_date_format($this->get_quote_col($val_col['sql_as']),$final_date_format);
				}
				elseif ($val_col['data_type'] == __FLOAT__)
				{
					// Localization FLOAT Format
					// replace decimal symbol for localization
					$decimal_symbol = $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'];
					if ($decimal_symbol != '.')
					{
						$str_final = $this->get_replace($this->get_quote_col($val_col['sql_as']),'.',$decimal_symbol);
					}
					else
					{
						$str_final = $this->get_quote_col($val_col['sql_as']);
					}

				}
				else
				{
					// all other case
					$str_final = $this->get_quote_col($val_col['sql_as']);
				}

				$temp_columns .= $str_final.' AS '.$this->get_quote_col($val_col['sql_as']);

				if(isset($val_col['select_function']))
				{
					// Special select function defined
					$sql .= str_replace('__COL_VALUE__',$this->get_quote_col($val_col['sql_as']),$val_col['select_function']).' AS '.$this->get_quote_col($val_col['sql_as']);
				}
				else
				{
					// TODO MIZ, les contrôles sur la localisation seront à mettre ici pour
					//   éviter de rentrer en conflit avec les functions spécifiques (select_function)
					$sql .= $temp_columns;
				}
				$i_sql = $i_sql + 1;
			}
		}
		//==================================================================
		$only_selected_lines = ' AND ((';

		//==================================================================
		// Build query WHERE part
		//==================================================================
		$i = 0;
		foreach($this->c_selected_lines['keys'] as $value)
		{
			if($i > 0)
			{
				$only_selected_lines .= ' OR (';
			}
			$j = 0;
			foreach ($value as $key => $value_key)
			{
				if($j == 0)
				{
					$only_selected_lines .= $this->get_quote_col($this->matchcode['__update_table_name'][0]).'.`'.$key.'` = "'.str_replace('"','\"',$value_key).'"';
				}
				else
				{
					$only_selected_lines .= ' AND '.$this->get_quote_col($this->matchcode['__update_table_name'][0]).'.`'.$key.'` = "'.str_replace('"','\"',$value_key).'"';
				}

				$j = $j + 1;
			}
			$only_selected_lines .= ')';
			$i = $i + 1;
		}
		$only_selected_lines .= ')';

		//==================================================================


		//==================================================================
		// Assembly, run and return xml final query
		//==================================================================
		$sql .= ' FROM '.$this->matchcode['__update_table_name'][0].' WHERE 1 = 1 '.$only_selected_lines;
		$p_result_header = $this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

		$this->prepare_query($only_selected_lines);

		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,$p_result_header,true,true))."</content>";
		$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(true)).'</toolbar>';
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * export_list method
	 * Export filter or selected lines to local file
	 * @json_lines		: json format of selected lines
	 * @p_only_count	: if true return only count of lines to export
	====================================================================*/
	public function export_list($json_lines, $p_only_count = false)
	{
		$this->export_status = 1;

		// Set the selected lines to edit
		$this->define_selected_line($json_lines);

		$only_selected_lines = ' AND ((';
		//==================================================================
		// Build WHERE part on selected lines if any
		//==================================================================
		if(isset($this->c_selected_lines['keys']))
		{
			$i = 0;
			foreach($this->c_selected_lines['keys'] as $value)
			{
				if($i > 0)
				{
					$only_selected_lines .= ' OR (';
				}
				$j = 0;
				foreach ($value as $key => $value_key)
				{
					// found root column name
					foreach($this->c_columns as $val_col)
					{
						if($val_col['sql_as'] == $key)
						{
							$my_key = $val_col['before_as'];
							break;
						}
					}
					if($j == 0)
					{
						$only_selected_lines .= $my_key.' = "'.$value_key.'"';
					}
					else
					{
						$only_selected_lines .= ' AND '.$my_key.' = "'.$value_key.'"';
					}

					$j = $j + 1;
				}
				$only_selected_lines .= ')';
				$i = $i + 1;
			}
		}
		$only_selected_lines .= ')';
		//==================================================================

		// Filtering selected lines if any
		if($only_selected_lines != ' AND (()')
		{
			$this->prepare_query($only_selected_lines, false,$p_only_count);
		}
		else
		{
			$this->prepare_query('',false,$p_only_count);
		}

		// If only total of lines
		if($p_only_count)
		{
			$max = 'NA';
			$this->export_total = $this->c_recordset_line;
			if($this->c_recordset_line > $_SESSION[$this->c_ssid]['lisha']['configuration'][11])
			{
				$max = number_format($_SESSION[$this->c_ssid]['lisha']['configuration'][11],0,'', htmlentities($_SESSION[$this->c_ssid]['lisha']['thousand_symbol']));
			}

			$retour = array("TOTAL" => number_format($this->export_total,0,'', htmlentities($_SESSION[$this->c_ssid]['lisha']['thousand_symbol'])),"MAX" => $max);
			return json_encode($retour);
			// Exit in count mode
		}

		//==================================================================
		// Build first row : column header names
		//==================================================================
		$row = $this->rds_fetch_array($this->resultat);

		$exclude_column = '';
		$i=0;
		$export = '';

		if($row != null)
		{
			foreach($row as $clef => $valeur)
			{
				foreach($this->c_columns as $val_col)
				{
					if($val_col['sql_as'] == $clef)
					{
						// ok, key column found
						//if($val_col['sql_as'] != $this->matchcode['__column_name_group_of_color'][0] && $val_col['display'])
						if($val_col['display'])
						{
							// ok, column to export
							$export .= '"'.str_replace('"','""',html_entity_decode($val_col['name'],ENT_QUOTES,"UTF-8")).'";';
						}
						else
						{
							//  Do not export this column
							$exclude_column[] = $i;
						}
					}
				}
				$i = $i + 1;
			}
			// Remove last separator ;
			$export = substr($export,0,-1);
			$export .= chr(13);

			if(isset($exclude_column[0]))
			{
				$exclude_column = array_flip($exclude_column);
			}
		}
		else
		{
			$this->export_total = 0;
		}
		//==================================================================


		// Write head columns
		echo $export;

		// Reset query to it first position
		$this->rds_data_seek($this->resultat,0);

		// Build limit of columns
		$max_column = $this->get_last_column() - count($exclude_column);

		//==================================================================
		// Create data lines
		//==================================================================
		$k=0;
		while($row = $this->rds_fetch_array($this->resultat))
		{
			$j=0; // Displayed column
			$i=0;
			$export = '';

			foreach($row as $valeur)
			{
				if($this->export_status == -1)
				{
					// Cancel export
					echo "Export canceled";
					break;
				}

				if(!isset($exclude_column[$i]))
				{
					$export .= '"'.str_replace('"','""',html_entity_decode($valeur,ENT_QUOTES,"UTF-8")).'";';
					if($j==$max_column)
					{
						break;
					}
					$j = $j + 1;
				}
				$i = $i + 1;
			}
			$k = $k + 1;
			// Remove last separator ;
			$export = substr($export,0,-1);

			// New line, insert break line
			$export .= chr(13);

			echo $export;

			// Check if limit is reached
			if($k >= $_SESSION[$this->c_ssid]['lisha']['configuration'][11])
			{
				break;
			}
		}
		//==================================================================

		// Total lines to export with limit
		$this->export_total = $k;

		$this->export_status = 2; // Export done
		return null;
	}
	/**===================================================================*/


	/**==================================================================
	 * lisha_cancel_edit method
	 * Cancel any update in progress
	====================================================================*/
	public function lisha_cancel_edit()
	{
		$this->c_edit_mode = __DISPLAY_MODE__;

		// Clear selected line
		$this->c_selected_lines = false;

		$this->restore_page_position();

		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,true,true))."</content>";
		$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * delete_lines
	 * Remove all selected lines
	 * @json_lines		: selected line to remove in json format
	====================================================================*/
	public function delete_lines($json_lines)
	{
		// Define the lisha mode
		$this->c_edit_mode = __DISPLAY_MODE__;

		// Set the selected lines to edit
		$this->define_selected_line($json_lines);

		//==================================================================
		// Build query
		//==================================================================
		$sql_delete = 'DELETE FROM '.$this->matchcode['__update_table_name'][0].' WHERE (';
		$i = 0;
		foreach($this->c_selected_lines['keys'] as $value)
		{
			if($i > 0)
			{
				$sql_delete .= ' OR (';
			}
			$j = 0;
			foreach ($value as $key => $value_key)
			{
				if($j == 0)
				{
					$sql_delete .= '`'.$key.'` = "'.$value_key.'"';
				}
				else
				{
					$sql_delete .= ' AND `'.$key.'` = "'.$value_key.'"';
				}

				$j = $j + 1;
			}
			$sql_delete .= ')';
			$i = $i + 1;
		}
		//==================================================================

		// Execute query
		if($i > 0)
		{
			$this->exec_sql($sql_delete,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
		}

		// Unselected lines
		$this->c_selected_lines = false;

		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,true,true))."</content>";
		$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * Check consistency of data on a row
	 *
	 * @tab_val_col :   Array of field to check
	====================================================================*/
	private function update_data_check(&$tab_val_col)
	{
		if(count($tab_val_col) != 0)
		{
			// Any column updated ?? ( updating data only )
			$ctrl_ok = true;
			$error_str = '<table>';
			$error_line = array();

			foreach($this->c_columns as $key_col => $value_col)
			{
				// Check required
				if(isset($value_col['rw_flag']) &&
					($value_col['rw_flag'] == __REQUIRED__ || $value_col['rw_flag'] == __LISTED__ || $value_col['data_type'] == __INT__ || $value_col['data_type'] == __FLOAT__) &&
					!isset($value_col['auto_create_column'])
				)
				{
					//==================================================================
					// Search idorigin in list return by javascript part
					//==================================================================
					foreach($tab_val_col as &$valeur)
					{
						if($valeur['idorigin'] == $value_col['original_order'])
						{
							//==================================================================
							// __FLOAT__ or __INT__
							//==================================================================
							// __INT__
							if ($value_col['data_type'] == __INT__
								&& filter_var($valeur['value'],FILTER_VALIDATE_INT) === false
								&& $valeur['value'] != ''
							)
							{
								$error_line['c'.$key_col]['status'] = __INT__;
								$error_str .='<tr><td align=left>'.str_replace('$value',$valeur['value'],str_replace('$name','<b>'.$value_col['name'].'</b>',$this->lib(156))).'</td></tr>';
								// error for all case
								$error_line['c'.$key_col]['status'] = $this->c_columns[$key_col]['data_type'];
								$error_line['c'.$key_col]['id'] = $key_col;
								$ctrl_ok = false;
								break;
							}

							// float or numeric
							if ($value_col['data_type'] == __FLOAT__)
							{
								// recovery decimal symbol
								$decimal_symbol = $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'];
								if ($decimal_symbol != '.')
								{
									// if not decimal point, transform decimal symbol in point for insert database
									$valeur['value'] = str_replace($decimal_symbol, '.', $valeur['value']);
								}
								// recovery thousand symbol
								$thousand_symbol = $_SESSION[$this->c_ssid]['lisha']['thousand_symbol'];
								// if not decimal point, transform decimal symbol in point for insert database
								$valeur['value'] = str_replace($thousand_symbol, '', $valeur['value']);

								// validate data float
								if (filter_var($valeur['value'],FILTER_VALIDATE_FLOAT) === false
									&& $valeur['value'] != '')
								{
									$error_line['c'.$key_col]['status'] = __FLOAT__;
									$error_str .='<tr><td align=left>'.str_replace('$value',$valeur['value'],str_replace('$name','<b>'.$value_col['name'].'</b>',$this->lib(157))).'</td></tr>';
									// error for all case
									$error_line['c'.$key_col]['status'] = $this->c_columns[$key_col]['data_type'];
									$error_line['c'.$key_col]['id'] = $key_col;
									$ctrl_ok = false;
									break;
								}
							}
							//==================================================================

							//==================================================================
							// __LISTED__
							//==================================================================
							if(isset($value_col['lov']['sql']) && $value_col['rw_flag'] == __LISTED__)
							{
								// LOV defined, ok continue

								// Check if lov is solvable
								$sql_lov = $this->solve_lov_main_query($value_col['original_order']);

								// And condition to check if input value is in list

								if($valeur['value'] == '')
								{
									$sql_lov = $sql_lov." AND ".$value_col['lov']['before_as']." IS NULL";
								}
								else
								{
									$sql_lov = $sql_lov." AND ".$value_col['lov']['before_as']." = '".$valeur['value']."'";
								}

								// Request query
								$this->exec_sql($sql_lov,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

								// This value is enable ?
								if($this->rds_num_rows($this->resultat) == 1)
								{
									// Value exists in LOV
								}
								else
								{
									// Rise an error
									// This value doesn't exist in custom lov
									$ctrl_ok = false;
									$error_str .='<tr><td align=left>'.str_replace('$value',$valeur['value'],str_replace('$name','<b>'.$value_col['name'].'</b>',$this->lib(126))).'</td></tr>';

									$error_line['c'.$key_col]['id'] = $key_col;
									$error_line['c'.$key_col]['status'] = __LISTED__;
								}
								break;
							}
							//==================================================================

							//==================================================================
							// __REQUIRED__
							//==================================================================
							if($value_col['rw_flag'] == __REQUIRED__
								&& $valeur['value'] == ''
							)
							{
								$ctrl_ok = false;
								$error_str .='<tr><td align=left>'.str_replace('$name','<b>'.$value_col['name'].'</b>',$this->lib(57)).'</td></tr>';

								$error_line['c'.$key_col]['id'] = $key_col;
								$error_line['c'.$key_col]['status'] = __REQUIRED__;
								break;
							}
							//==================================================================

							break; // Only one line possible, so exit foreach
						}
					}
					// because reference
					unset($valeur);
					//==================================================================
				}
				else
				{
					if(!isset($value_col['auto_create_column']))
					{
						if($value_col['display'] && isset($tab_val_col['c'.$value_col['original_order']]))
						{
							if(isset($value_col['rw_flag']) && $value_col['rw_flag'] == __FORBIDDEN__ && $tab_val_col['c'.$value_col['original_order']]['value'] != '')
							{
								$ctrl_ok = false;
								$error_str .='<tr><td align=left>'.str_replace('$name','<b>'.$value_col['name'].'</b>',$this->lib(58)).'</td></tr>';
							}
							if(isset($value_col['rw_flag']) && $value_col['rw_flag'] == __FORBIDDEN__)
							{
								$error_line['c'.$key_col]['id'] = $key_col;
								$error_line['c'.$key_col]['status'] = __FORBIDDEN__;
							}
						}
					}
				}
			}

			$error_str .= '</table>';
			//==================================================================

			if($ctrl_ok)
			{
				$ctrl_ok = 'true';
			}
			else
			{
				$ctrl_ok = 'false';
			}
		}
		else
		{
			// None value updated
			$ctrl_ok = 'true';
			$error_str = '';
			$error_line = '';
		}
		return array($ctrl_ok,$error_str,$error_line);
	}
	/**===================================================================*/


	/**==================================================================
	 * Record a new line
	 * @json				: content of js input area in json format
	====================================================================*/
	public function add_line($json)
	{
		// Transform input area line to php array
		$tab_val_col = json_decode($json,true);

		// Don't restore original navigation position because could be wrong, just stay on update page means 1
		$this->clear_page_position_recorded();

		//==================================================================
		// Data control
		//==================================================================
		$check = $this->update_data_check($tab_val_col);

		//==================================================================

		if($check[0] == 'true')
		{
			//==================================================================
			// No error
			// Prepare insert query
			//==================================================================

			// Always return to first page
			$this->define_attribute('__current_page',1);
			$this->define_limit_min(0);

			// Control line OK, add the line
			$sql_insert = 'INSERT INTO '.$this->matchcode['__update_table_name'][0].'(';
			$sql_insert_values = '';

			$i = 0;

			// Browse all columns
			foreach($tab_val_col as $value)
			{
				$value['value'] = $this->replace_chevrons($value['value'],true);
				if(!isset($this->c_columns[$value['id']]['rw_flag']) || $this->c_columns[$value['id']]['rw_flag'] != __FORBIDDEN__)
				{
					if($value['value'] != "")
					{
						// Add a , if necessary
						if($i > 0)
						{
							$sql_insert .= ',';
							$sql_insert_values .= ',';
						}


						$sql_insert .= $this->get_quote_col($this->c_columns[$value['id']]['sql_as']);

						if($this->c_columns[$value['id']]['data_type'] == __DATE__)
						{
							$my_date_value = $this->convert_localized_date_to_database_format($value['id'], $value['value']);
							if($my_date_value != "-1")
							{
								$value['value'] = $my_date_value;
							}
							else
							{
								// Date error conversion
							}
						}

						// Values
						if(isset($this->c_columns[$value['id']]['rw_function']))
						{
							// Special update function defined on the column
							$sql_insert_values .= str_replace('__COL_VALUE__',$this->protect_sql($value['value'],$this->link),$this->c_columns[$value['id']]['rw_function']);
						}
						else
						{
							if($this->c_columns[$value['id']]['data_type'] == __FLOAT__
								|| $this->c_columns[$value['id']]['data_type'] == __INT__
							)
							{
								$sql_insert_values .= $this->protect_sql($value['value'],$this->link);
							}
							else
							{
								$sql_insert_values .= $this->get_quote_string($this->protect_sql($value['value'],$this->link));
							}
						}

						$i = $i + 1;
					}
				}
			}

			foreach($this->c_columns as $column_value)
			{
				if(isset($column_value['predefined_value']))
				{
					$sql_insert .= ','.$this->get_quote_col($column_value['sql_as']);
					$sql_insert_values .= ','.$this->get_quote_string($this->protect_sql($column_value['predefined_value'],$this->link));
				}
			}

			$sql_insert .= ') VALUES ('.$sql_insert_values.');';

			// Insert the line
			$this->exec_sql($sql_insert,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

			$this->prepare_query();
			$this->c_selected_lines = false;
			$json = $this->generate_lisha_json_param();

			// XML return
			header("Content-type: text/xml");
			$xml = "<?xml version='1.0' encoding='UTF8'?>";
			$xml .= "<lisha>";
			$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
			$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
			$xml .= "<json>".$this->protect_xml($json)."</json>";
			$xml .= "<error>".$check[0]."</error>";
			$xml .= "</lisha>";
		}
		else
		{
			// Error in XML return
			header("Content-type: text/xml");
			$xml = "<?xml version='1.0' encoding='UTF8'?>";
			$xml .= "<lisha>";
			$xml .= "<error>".$check[0]."</error>";
			$xml .= "<error_str>".$this->protect_xml($check[1])."</error_str>";
			$xml .= "<error_col>".$this->protect_xml(json_encode($check[2]))."</error_col>";
			$xml .= "</lisha>";
		}

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * save_lines
	 * @val_col			: content of js input area in json format
	====================================================================*/
	public function save_lines($val_col)
	{
		// First, cancel update mode for further action
		$this->c_edit_mode = __DISPLAY_MODE__;

		// Transform input area line to php array
		$tab_val_col = json_decode($val_col,true);

		$this->restore_page_position();

		//==================================================================
		// Data control
		//==================================================================
		$check = $this->update_data_check($tab_val_col);
		//==================================================================

		if($check[0] == 'true' && $this->c_selected_lines != '')
		{
			//==================================================================
			// No error
			// Update all selected lines with new values
			//==================================================================

			//==================================================================
			// Build update set of values
			//==================================================================
			$sql_update = 'UPDATE '.$this->matchcode['__update_table_name'][0].' ';

			$i = 0;

			// Browse all column to update
			foreach($tab_val_col as $value)
			{
				//$value['value'] = htmlentities($value['value'],ENT_QUOTES,'UTF-8');
				$value['value'] = $this->replace_chevrons($value['value'],true);
				// Add a SET if necessary or a ,
				($i > 0) ? $sql_update .= ',' : $sql_update .= 'SET ';

				if($this->c_columns[$value['id']]['data_type'] == __DATE__)
				{
					$my_date_value = $this->convert_localized_date_to_database_format($value['id'], $value['value']);
					if($my_date_value != "-1")
					{
						$value['value'] = $my_date_value;
					}
					else
					{
						// Date error conversion
					}
				}

				if(isset($this->c_columns[$value['id']]['rw_function']))
				{
					// Special update function defined on the column
					$sql_update .= $this->get_quote_col($this->c_columns[$value['id']]['sql_as']).' = '.str_replace('__COL_VALUE__',$this->protect_sql($value['value'],$this->link),$this->c_columns[$value['id']]['rw_function']);
				}
				else
				{
					// No special function
					if($this->c_columns[$value['id']]['data_type'] == __FLOAT__
						|| $this->c_columns[$value['id']]['data_type'] == __INT__
					)
					{
						// is a numeric
						if($value['value'] == "")
						{
							$sql_update .= $this->get_quote_col($this->c_columns[$value['id']]['sql_as']).' = NULL';
						}
						else
						{
							$sql_update .= $this->get_quote_col($this->c_columns[$value['id']]['sql_as']).' = '.$this->protect_sql($value['value'],$this->link);
						}
					}
					else
					{
						// Other case
						$sql_update .= $this->get_quote_col($this->c_columns[$value['id']]['sql_as']).' = '.$this->get_quote_string($this->protect_sql($value['value'],$this->link));
					}
				}

				unset($this->c_columns[$value['id']]['filter']);

				// Counter of the column
				$i = $i + 1;
			}
			//==================================================================

			if($i > 0)
			{
				// A column has changed, execute the query update

				//==================================================================
				// Build WHERE query part
				//==================================================================
				$sql_update .= chr(10).' WHERE';

				$i = 0;
				// Browse all selected lines for includes in where clause
				foreach($this->c_selected_lines['keys'] as $value)
				{
					$j = 0;

					// Add OR if necessary
					($i > 0) ? $sql_update .= ') OR (' : $sql_update .= ' (';

					// Browse all keys of the selected lines
					foreach($value as $selected_key => $selected_value)
					{
						// Add AND if necessary
						($j > 0) ? $sql_update .= ' AND ' : $sql_update .= '';

						// WHERE clause
						$sql_update .= $this->get_quote_col($this->c_columns[$this->get_id_column($selected_key)]['sql_as']).' = '.$this->get_quote_string(str_replace('"','\"',$selected_value));

						// Counter of the selected line keys
						$j = $j + 1;
					}

					// Counter of the selected line
					$i = $i + 1;
				}

				if($i > 0)
				{
					$sql_update .= ')';		// Close the where clause
				}
				//==================================================================

				// Execute the update query
				$this->exec_sql($sql_update,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
			}
			//==================================================================

			// Unselect the lines
			$this->c_selected_lines = false;

			$this->prepare_query();
			$json = $this->generate_lisha_json_param();

			// XML return
			header("Content-type: text/xml");
			$xml = "<?xml version='1.0' encoding='UTF8'?>";
			$xml .= "<lisha>";
			$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,true,true))."</content>";
			$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
			$xml .= "<json>".$this->protect_xml($json)."</json>";
			$xml .= "</lisha>";
		}
		else
		{
			// Error in XML return
			header("Content-type: text/xml");
			$xml = "<?xml version='1.0' encoding='UTF8'?>";
			$xml .= "<lisha>";
			$xml .= "<error>".$check[0]."</error>";
			$xml .= "<error_str>".$this->protect_xml($check[1])."</error_str>";
			$xml .= "<error_col>".$this->protect_xml(json_encode($check[2]))."</error_col>";
			$xml .= "</lisha>";
		}

		echo $xml;
	}
	/**===================================================================*/


	/**
	 * Change the alignment of a column
	 * @param integer $p_column ID of the column
	 * @param string $p_type_alignment Alignment (__CENTER__,__LEFT__ or __RIGHT__)
	 * @param string $p_selected_lines Selected lines
	 */
	public function change_alignment($p_column,$p_type_alignment,$p_selected_lines)
	{
		// Set the selected lines to edit
		$this->define_selected_line($p_selected_lines);
		$this->c_columns[$p_column]['alignment'] = $p_type_alignment;

		/**==================================================================
		 * Execute the query and display the elements
		====================================================================*/
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,true,true))."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
		/**===================================================================*/
	}


	/**==================================================================
	 * generate_page
	 * @p_ajax				: To analyse...
	====================================================================*/
	public function generate_page($p_ajax = null)
	{
		// Build and execute query
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,true,$p_ajax))."</content>";
		$xml .= "<total_page>".ceil($this->c_recordset_line / $this->c_nb_line)."</total_page>";
		$xml .= "<active_page>".$this->matchcode['__current_page'][0]."</active_page>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "<qtt_line>".$this->c_page_qtt_line."</qtt_line>";
		$xml .= "</lisha>";

		echo $xml;
	}


	/**==================================================================
	 * lisha_page_change_ajax
	 * Change page navigation
	 * @type				: Kind of action __PREVIOUS__, __NEXT__, __FIRST__, __LAST__ or page number to refresh
	 * @p_selected_lines	: Selected lines if any in json format
	====================================================================*/
	public function lisha_page_change_ajax($type,$p_selected_lines)
	{
		// Set the selected lines to edit
		$this->define_selected_line($p_selected_lines);

		// Build value $this->c_page_qtt_line
		$this->prepare_query();

		switch ($type)
		{
			case  '__NEXT__':
				// Next page requested
				if($this->c_recordset_line < $this->c_nb_line)
				{
					$this->define_attribute('__current_page',1);
				}
				else
				{
					$this->define_attribute('__current_page',$this->matchcode['__current_page'][0]+1);
				}
				$this->define_limit_min($this->c_limit_min + $this->c_nb_line);
				break;
			case  '__PREVIOUS__':
				// Previous page requested
				if($this->c_recordset_line < $this->c_nb_line)
				{
					$this->define_attribute('__current_page',1);
					$this->define_limit_min($this->c_limit_min);
				}
				else
				{
					$this->define_attribute('__current_page',$this->matchcode['__current_page'][0]-1);
					$this->define_limit_min($this->c_limit_min - $this->c_nb_line);
				}

				break;
			case  '__FIRST__':
				// First page requested
				$this->define_attribute('__current_page',1);
				$this->define_limit_min(0);
				break;
			case  '__LAST__':
				// Last page requested
				if($this->c_page_qtt_line > 0)
				{
					$this->define_attribute('__current_page',ceil($this->c_recordset_line / $this->c_nb_line));
					$this->define_limit_min((ceil($this->c_recordset_line / $this->c_nb_line) - 1) * $this->c_nb_line);
				}
				else
				{
					$this->define_attribute('__current_page',1);
				}
				break;
			default:
				// Page number
				$this->define_attribute('__current_page',$type);
				$this->define_limit_min(($type * $this->c_nb_line) - $this->c_nb_line);
				break;
		}

		$this->define_limit_max($this->c_nb_line);

		//==================================================================
		// Build main query and generate json content
		//==================================================================
		$this->prepare_query();

		//$json_line = $this->generate_json_line();
		$json = $this->generate_lisha_json_param(); //SRX ADD

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,true,true))."</content>";
		$xml .= "<total_page>".ceil($this->c_recordset_line / $this->c_nb_line)."</total_page>";
		$xml .= "<active_page>".$this->matchcode['__current_page'][0]."</active_page>";
		//$xml .= "<json_line>".$this->protect_xml($json_line)."</json_line>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "<qtt_line>".$this->c_page_qtt_line."</qtt_line>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * refresh_page
	 * @p_selected_lines	: selected lines
	 * @p_nb_line 			: Number of lines by page ( NA means no line by page changed )
	====================================================================*/
	public function refresh_page($p_selected_lines,$p_nb_line = null)
	{
		if($p_nb_line != 'NA')
		{
			$this->change_nb_line($p_nb_line,$p_selected_lines);
		}

		// Rebuild flag for load custom filter
		$this->c_obj_graphic->any_filter = $this->any_filter();

		// Define the selected lines
		$this->define_selected_line($p_selected_lines);

		// Go to the first page
		//$this->define_attribute('__current_page',1);
		//$this->define_limit_min(0);

		$this->define_limit_max($this->c_nb_line);

		//==================================================================
		// Build query and generate xml content
		//==================================================================
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * define_selected_line
	 * Build full primary key of selected line
	 * @p_selected_lines	: selected line in json format
	====================================================================*/
	private function define_selected_line($p_selected_lines)
	{
		// Transform JSON to Array
		$tab_selected_var = json_decode($p_selected_lines,true);
		if(is_array($tab_selected_var))
		{
			//==================================================================
			// Browse the selected lines
			//==================================================================
			foreach($tab_selected_var as $value_tab_selected_var)
			{
				//==================================================================
				// Build primary key concatenation
				//==================================================================
				$key_concat = '';
				foreach($value_tab_selected_var['key'] as $value)
				{
					$key_concat .= $value;
				}
				//==================================================================

				// It is a selected line or not ?
				if($value_tab_selected_var['selected'])
				{
					// Line selected
					$this->c_selected_lines['keys'][$key_concat] = $value_tab_selected_var['key'];
					$this->c_selected_lines['key_concat'][$key_concat] = $key_concat;
				}
				else
				{
					// Line unselected
					unset($this->c_selected_lines['keys'][$key_concat]);
					unset($this->c_selected_lines['key_concat'][$key_concat]);
				}
			}
			//==================================================================
		}
	}
	/**===================================================================*/


	/**==================================================================
	 * move_column
	 * Method called when column moved
	 * @pc_src				: Column id source position
	 * @p_dst				: Column id target postion
	 * @p_selected_lines	: Selected line in json format
	====================================================================*/
	public function move_column($c_src,$c_dst,$p_selected_lines)
	{
		// Define the selected lines
		$this->define_selected_line($p_selected_lines);

		// Move left to right
		if($c_dst > $c_src)
		{
			$c_dst = $c_dst - 1;
		}

		// Copy content of the column source and destination
		$temp_src = $this->c_columns[$c_src];

		// Move each column between the source and destination
		if($c_src > $c_dst)
		{
			$i = $c_src;
			while($i  > $c_dst){
				$this->c_columns[$i] = $this->c_columns[$i - 1];
				$i = $i - 1;
			}
		}
		else
		{
			$i = $c_src;
			while($i  < $c_dst){
				$this->c_columns[$i] = $this->c_columns[$i + 1];
				$i = $i + 1;
			}
		}

		// Copy column source to destination
		$this->c_columns[$c_dst] = $temp_src;

		//==================================================================
		// Build query and generate xml content
		//==================================================================
		$this->prepare_query();

		$json_line = $this->generate_json_line();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "<json_line>".$this->protect_xml($json_line)."</json_line>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * change_order
	 * Method called when change column sort by mode
	 * @p_column			: Column id
	 * @p_order				: __ASC__,__DESC__ or __NONE__
	 * @p_mode				: __ADD__ or __NEW__
	 * @p_selected_lines	: Selected line in json format
	====================================================================*/
	public function change_order($p_column,$p_order,$mode,$p_selected_lines)
	{
		// Define the selected lines
		$this->define_selected_line($p_selected_lines);

		if($mode == __NEW__)
		{
			// New order, delete other clause
			$this->clear_all_order();
		}

		//==================================================================
		// Setup sort by priority
		//==================================================================
		if($this->c_columns[$p_column]['order_priority'] != false)
		{
			$this->order_priority = $this->c_columns[$p_column]['order_priority'];
		}
		else
		{
			$this->order_priority = $this->get_max_priority() + 1;
		}
		//==================================================================

		$this->define_order_column(null,$p_order,$p_column,false);

		//==================================================================
		// Build query and generate xml content
		//==================================================================
		$this->prepare_query();

		$json_line = $this->generate_json_line();
		$json = $this->generate_json_column();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "<json_line>".$this->protect_xml($json_line)."</json_line>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * change_search_mode
	 * Method called when change column search mode
	 * @p_column			: Column id
	 * @p_type_search		: See operator list : __CONTAIN__, __PERCENT__, __EXACT__...
	 * @p_selected_lines	: Selected line in json format
	====================================================================*/
	public function change_search_mode($p_column,$p_type_search,$p_selected_lines)
	{
		// Define the selected lines
		$this->define_selected_line($p_selected_lines);

		// Define the search mode
		$this->c_columns[$p_column]['search_mode'] = $p_type_search;

		//==================================================================
		// Build query and generate xml content
		//==================================================================
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(true,$this->resultat)).'</toolbar>';
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * Call when user type entry in input column head
	 * This method return entries that can match with input contain
	 *
	 * @column		    	: column id
	 * @txt				    : input search on current column
	 * @p_selected_lines	: selected line in json format
	====================================================================*/
	public function lisha_input_search_onkeyup($column,$txt,$p_selected_lines)
	{
		// Define the selected lines
		$this->define_selected_line($p_selected_lines);

		unset($this->c_columns[$column]['lov_key_recover']);

		if(isset($this->c_columns[$column]['filter']) && count($this->c_columns[$column]['filter']) == 0)
		{
			unset($this->c_columns[$column]['filter']);
		}

		//==================================================================
		// Recover input filter for each columns
		//==================================================================
		$sql_filter = '';

		// Scan custom filter defined on each column
		foreach($this->c_columns as $column_key => $column_value)
		{
			if(isset($column_value['filter']) && $column_key != $column)
			{
				foreach ($column_value['filter'] as $my_key => $filter_value)
				{
					// operator_build_query_condition
					$sql_filter .= $this->operator_build_query_condition($my_key,$column_value,$filter_value['filter']);
				}
			}
		}
		//==================================================================

		//==================================================================
		// Browse the updated filter
		//==================================================================
		if($txt == '')
		{
			// No filter defined, clear the filter clause
			unset($this->c_columns[$column]['filter']['input']);
			unset($this->c_columns[$column]['lov']['taglov_possible']);

			$html = '';
		}
		else
		{
			// Something input in head column input box, so continue

			//==================================================================
			// Convert date in native form if any and if it's possible
			//==================================================================
			if($this->c_columns[$column]['data_type'] == __DATE__)
			{
				$tmp_result = $this->convert_localized_date_to_database_format($column,$txt);
				if($tmp_result == "-1")
				{
					// No localized date format conversion possible then keep original value
					$tmp_result = $txt;
				}

				$this->c_columns[$column]['filter']['input'] = array('filter' => $tmp_result,
																	 'filter_display' => rawurldecode($txt)
				);
			}
			else
			{
				$this->c_columns[$column]['filter']['input'] = array('filter' => rawurldecode($txt),
																	 'filter_display' => rawurldecode($txt)
				);
			}
			//==================================================================

			if(isset($this->c_columns[$column]['lov']))
			{
				// A custom lov is defined
				// PERCENT
				$sql_lov = $this->solve_lov_main_query($column);

				// operator_build_query_condition
				$sql_condition_quick_search = $this->operator_build_query_condition($column,$this->c_columns[$column],$txt,'lov_return');

				$query_final_pos = strripos($sql_lov, $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);

				$sql =  'SELECT 1 AS `total` '.substr($sql_lov,$query_final_pos).$sql_condition_quick_search;

				$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

				if($this->rds_num_rows($this->resultat) == 1)
				{
					// One row found, ok recover key field -- Not possible from here !!
					$this->c_columns[$column]['lov']['taglov_possible'] = true;
				}
				else
				{
					// No quick result window possible
					$this->c_columns[$column]['lov']['taglov_possible'] = false;
				}

				//operator_build_query_condition
				$sql_condition_quick_search = $this->operator_build_query_condition($column,$this->c_columns[$column],$txt,'lov');

				if(isset($this->c_columns[$column]['lov']['taglov_possible']) || !isset($this->c_columns[$column]['lov']['taglov']))
				{
					// No TAGLOV dependency

					//==================================================================
					// Count matching rows when user type something in input box ( Custom lov )
					//==================================================================
					$query_final_pos = strripos($this->c_columns[$column]['lov']['sql'], $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);

					$sql =  'SELECT COUNT( DISTINCT '.$this->c_columns[$column]['lov']['before_as'].' ) AS `total` '.substr($this->c_columns[$column]['lov']['sql'],$query_final_pos).$sql_condition_quick_search;

					$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__, $this->link);
					$row = $this->rds_fetch_array($this->resultat);
					$count = $row['total'];

					$this->c_columns[$column]['matched_rows'] = $count; // Record matched results number
					//==================================================================

					//==================================================================
					// Few first rows found
					//==================================================================
					$query_final_pos = strripos($this->c_columns[$column]['lov']['sql'], $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);
					$sql =  'SELECT DISTINCT '.$this->c_columns[$column]['lov']['before_as'].'  AS '.$this->get_quote_col($this->c_columns[$column]['sql_as']).substr($this->c_columns[$column]['lov']['sql'],$query_final_pos).$sql_condition_quick_search;
					$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
					//==================================================================
				}
				else
				{
					// With TAGLOV dependency
					$sql_lov = $this->solve_lov_main_query($column);

					//==================================================================
					// Count matching rows when user type something in input box ( Custom lov )
					//==================================================================
					$query_final_pos = strripos($sql_lov, $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);

					$sql =  'SELECT COUNT( DISTINCT '.$this->c_columns[$column]['lov']['before_as'].' ) AS `total` '.substr($sql_lov,$query_final_pos).$sql_condition_quick_search;
					$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__, $this->link);
					$row = $this->rds_fetch_array($this->resultat);
					$count = $row['total'];
					//==================================================================

					//==================================================================
					// Few first rows found
					//==================================================================
					$query_final_pos = strripos($sql_lov, $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);
					$sql =  'SELECT DISTINCT '.$this->c_columns[$column]['lov']['before_as'].'  AS '.$this->get_quote_col($this->c_columns[$column]['sql_as']).substr($sql_lov,$query_final_pos).$sql_condition_quick_search;
					$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
					//==================================================================
				}
			}
			else
			{
				// No custom lov defined
				$this->c_columns[$column]['matched_rows'] = 0;

				//==================================================================
				// Count matching rows when user type something in input box
				//==================================================================
				$query_final_pos = strripos($this->matchcode['__main_query'][0], $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);

				//operator_build_query_condition
				$sql_condition_quick_search = $this->operator_build_query_condition($column,$this->c_columns[$column],$txt);

				$sql =  'SELECT COUNT( DISTINCT '.$this->c_columns[$column]['before_as'].' ) AS `total` '.substr($this->matchcode['__main_query'][0],$query_final_pos).$sql_condition_quick_search.$sql_filter;

				$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__, $this->link);
				$row = $this->rds_fetch_array($this->resultat);
				$count = $row['total'];
				//==================================================================
				// Localization date format
				if ($this->c_columns[$column]['data_type'] == __DATE__)
				{
					if(isset($this->c_columns[$column]['date_format']))
					{
						$final_date_format = $this->c_columns[$column]['date_format'];
					}
					else
					{
						// No custom date format then use country localization format
						$final_date_format = $_SESSION[$this->c_ssid]['lisha']['date_format'];
					}

					$str_final = $this->get_date_format($this->c_columns[$column]['before_as'], $final_date_format);
				}
				else
				{
					// all other case
					$str_final = $this->c_columns[$column]['before_as'];

					//==================================================================
					// localization decimal symbol
					//==================================================================
					//float or numeric (same)
					if ($this->c_columns[$column]['data_type'] == __FLOAT__)
					{
						$decimal_symbol = $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'];
						//if ($decimal_symbol != '.')
						//{
						// if not decimal point, transform decimal symbol in point for search in database
						$str_final = "REPLACE(REPLACE(FORMAT(".$this->c_columns[$column]['before_as'].",".$_SESSION[$this->c_ssid]['lisha']['number_of_decimal']."),',','".$_SESSION[$this->c_ssid]['lisha']['thousand_symbol']."'),'.','".$decimal_symbol."')";
						//}
					}
					//==================================================================
				}
				//==================================================================
				// Few first rows found
				//==================================================================
				$sql =  'SELECT DISTINCT ('.$str_final.' ) AS '.$this->get_quote_col($this->c_columns[$column]['sql_as']).' '.substr($this->matchcode['__main_query'][0],$query_final_pos).$sql_condition_quick_search.$sql_filter.' ORDER BY 1 ASC LIMIT 6';
				//==================================================================

				$this->exec_sql($sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
			}
			//==================================================================

			//==================================================================
			// Build result
			//==================================================================
			$html = '<div id="div_input_search_'.$this->c_id.'" class="__'.$this->matchcode['__id_theme'][0].'_div_input_search"><table style="width:100%;" style="border-collapse:collapse;">';
			$i = 1;
			$motif = '#'.$this->protect_expreg($txt).'#i';

			while($row = $this->rds_fetch_array($this->resultat))
			{
				if($this->c_columns[$column]['data_type'] == __BBCODE__)
				{
					$result = $this->clearBBCode($row[$this->c_columns[$column]['sql_as']]);
				}
				else
				{
					$result = $this->clearHTML($row[$this->c_columns[$column]['sql_as']]);
					$result = $this->replace_chevrons($result,false);
				}
				$html .= '<tr style="margin:0;padding:0;border-collapse:collapse;" id="'.$this->c_id.'_rapid_search_l'.$i.'" onclick="lisha_input_result_click(\''.$this->c_id.'\','.$column.','.$i.',\''.$this->protect_js_txt($result).'\');"><td style="margin:0;padding:0;"><div class="__'.$this->matchcode['__id_theme'][0].'_column_header_input_result" id="lisha_input_result_'.$i.'_'.$this->c_id.'">'.preg_replace($motif,'<b>$0</b>',$result).'</div></td></tr>';
				$i = $i + 1;
			}
			//==================================================================

			if($count > 6)
			{
				$qtt = $count - 6;
				($count == 1) ? $lib = $qtt.' '.$this->lib(37) : $lib = $qtt.' '.$this->lib(38);

				$html .= '<tr style="margin:0;padding:0;border-collapse:collapse;"><td class="__'.$this->matchcode['__id_theme'][0].'_column_header_menu_line_sep_top"></td></tr>';
				$html .= '<tr style="margin:0;padding:0;border-collapse:collapse;"><td style="margin:0;padding:0;"><div style="font-family: tahoma, arial, helvetica, sans-serif;font-size: 0.7em;">'.$lib.' ...</div></td></tr>';
			}

			if($i == 1)
			{
				$html .= '<tr style="margin:0;padding:0;border-collapse:collapse;" id="'.$this->c_id.'_rapid_search_l'.$i.'"><td style="margin:0;padding:0;"><div class="__'.$this->matchcode['__id_theme'][0].'_column_header_input_result" id="lisha_input_result_'.$i.'_'.$this->c_id.'">'.$this->lib(43).'</div></td></tr>';
			}

			$html.= '</table></div>';
		}
		//==================================================================
		//error_log(print_r($this->c_columns,true));
		// Check if columns lov query are solvable
		$this->set_is_lovable_columns();

		//==================================================================
		// Build json param and xml content
		//==================================================================
		$json = $this->generate_lisha_json_param(true,false); // SRX_MANAGE_MARBLE_ON_INPUT_CHANGE

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "<content>".$this->protect_xml($html)."</content>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================

	}
	/**===================================================================*/


	/**==================================================================
	 * Build sql part on condition with setup operator
	 *
	 * @p_column_id			: Id column identifier
	 * @p_current_column 	: [Array] pointer on current column fields
	 * @p_input_text	    : Input string in input head column
	 * @p_lov            	: false means main before_as field, lov or lov_return
	====================================================================*/
	private function operator_build_query_condition($p_column_id,$p_current_column,$p_input_text,$p_lov = 'no')
	{
		$sql_filter = "";
		//==================================================================
		// localization decimal symbol
		//==================================================================
		//float or numeric (same)
		if ($p_current_column['data_type'] == __FLOAT__)
		{
			$decimal_symbol = $_SESSION[$this->c_ssid]['lisha']['decimal_symbol'];
			if ($decimal_symbol != '.')
			{
				// if not decimal point, transform decimal symbol in point for search in database
				$p_input_text = str_replace($decimal_symbol, '.', $p_input_text);
			}
		}
		//==================================================================

		//==================================================================
		// localization date
		//==================================================================
		if($p_current_column['data_type'] == __DATE__)
		{
			$tmp_result = $this->convert_localized_date_to_database_format($p_column_id,$p_input_text);
			if($tmp_result != "-1")
			{
				$p_input_text = $tmp_result;
			}
		}
		//==================================================================

		switch($p_lov)
		{
			case 'no':
				$my_array = $p_current_column['before_as'];
				break;
			case 'lov':
				$my_array = $p_current_column['lov']['before_as'];
				break;
			case 'lov_return';
				$my_array = $p_current_column['lov']['before_as'];
				break;
		}

		switch($p_current_column['search_mode'])
		{
			case __EXACT__:
				$sql_filter = " AND ".$my_array." = '".$this->protect_sql($this->replace_chevrons($p_input_text),$this->link)."'";
				break;
			case __CONTAIN__:
				$sql_filter = ' AND '.$my_array.' '.$this->get_like(__PERCENT__.$this->protect_sql($this->replace_chevrons(str_replace('_','\\_',str_replace('%','\\%',str_replace("\\","\\\\",$p_input_text))),true),$this->link).__PERCENT__);
				break;
			case __PERCENT__:
				$sql_filter = ' AND '.$my_array.' '.$this->get_like($this->protect_sql($this->replace_chevrons($p_input_text),$this->link));
				break;
			case __GT__:
				$sql_filter = " AND ".$my_array." > '".$this->protect_sql($this->replace_chevrons($p_input_text),$this->link)."'";
				break;
			case __LT__:
				$sql_filter = " AND ".$my_array." < '".$this->protect_sql($this->replace_chevrons($p_input_text),$this->link)."'";
				break;
			case __GE__:
				$sql_filter = " AND ".$my_array." >= '".$this->protect_sql($this->replace_chevrons($p_input_text),$this->link)."'";
				break;
			case __LE__:
				$sql_filter = " AND ".$my_array." <= '".$this->protect_sql($this->replace_chevrons($p_input_text),$this->link)."'";
				break;
			case __NULL__:
				$sql_filter = ' AND '.$my_array.' IS NULL ';
				break;
		}
		return $sql_filter;
	}
	/**===================================================================*/

	/**==================================================================
	 * protect_js_txt
	 * protect
	 *@p_txt	:	input string to protect
	====================================================================*/
	private function protect_js_txt($p_txt)
	{
		$p_txt = str_replace('\\','\\\\',$p_txt);
		$p_txt = str_replace("'","\'",$p_txt);

		return $p_txt;
	}
	/**===================================================================*/


	/**==================================================================
	 * toggle_column
	 * Hide or display column
	 *@p_column			:	column id
	 *@p_selected_lines	:	selected line in json format
	====================================================================*/
	public function toggle_column($p_column,$p_selected_lines)
	{
		// Set the selected lines to edit
		$this->define_selected_line($p_selected_lines);

		// Switch Hide / Display
		if($this->c_columns[$p_column]['display'] == __DISPLAY__)
		{
			$this->c_columns[$p_column]['display'] = __HIDE__;
		}
		else
		{
			$this->c_columns[$p_column]['display'] = __DISPLAY__;
		}

		//==================================================================
		// Run query and build xml content
		//==================================================================
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	private function protect_expreg($p_txt)
	{
		// ^ . [ ] $ ( ) * + ? | { } \
		$p_txt = str_replace('\\', '\\\\', $p_txt);
		$p_txt = str_replace('^', '\^', $p_txt);
		$p_txt = str_replace('.', '\.', $p_txt);
		$p_txt = str_replace('[', '\[', $p_txt);
		$p_txt = str_replace(']', '\]', $p_txt);
		$p_txt = str_replace('(', '\(', $p_txt);
		$p_txt = str_replace(')', '\)', $p_txt);
		$p_txt = str_replace('$', '\$', $p_txt);
		$p_txt = str_replace('*', '\*', $p_txt);
		$p_txt = str_replace('+', '\+', $p_txt);
		$p_txt = str_replace('?', '\?', $p_txt);
		$p_txt = str_replace('|', '\|', $p_txt);
		$p_txt = str_replace('{', '\{', $p_txt);
		$p_txt = str_replace('}', '\}', $p_txt);

		return $p_txt;
	}

	private function escape_special_char($p_txt)
	{
		$p_txt = str_replace('_', '\_', $p_txt);
		$p_txt = str_replace('%', '\%', $p_txt);

		return $p_txt;
	}


	/**==================================================================
	 * reset_filters
	 * Clear all filter column options
	====================================================================*/
	private function reset_filters()
	{
		foreach ($this->c_columns as $key_column => &$val_column)
		{
			if(isset($val_column['filter']))
			{
				unset($val_column['filter']);
			}
			unset($this->c_columns[$key_column]['lov']['taglov_possible']);
		}

		$this->set_is_lovable_columns();

		// Reset global search
		$this->string_global_search = "";
	}
	/**===================================================================*/


	/**==================================================================
	 * Called when reset button in toolbar is left clicked
	 *
	 * Restore all features defined by default or from a loaded preference forced by url directive
	====================================================================*/
	public function reset_lisha()
	{
		// Reset filter on all columns
		$this->reset_filters();

		//==================================================================
		// Reset all columns features
		//==================================================================

		// Reload general lisha features
		$this->c_columns = $this->c_columns_init;

		// Reload graphic lisha feature if not defined in main lisha
		if(is_array($this->c_obj_graphic->c_columns_init))
		{
			foreach($this->c_obj_graphic->c_columns_init as $key => $value)
			{
				foreach($value as $clef => $valeur)
				{
					//error_log(print_r())
					$this->c_columns[$key][$clef] = $this->c_obj_graphic->c_columns_init[$key][$clef];
				}
			}
		}
		//==================================================================


		//error_log(print_r($this->c_columns_init,true));

		// Reset selected lines
		$this->c_selected_lines = false;

		$this->change_nb_line($this->c_default_nb_line);

		// Define active page to 1
		$this->define_attribute('__current_page',1);
		$this->define_limit_min(0);

		// Reload configuration from URL directive if any
		if($this->filter_name != null)
		{
			$this->get_and_set_filter($this->filter_name);
		}

		// Reset columns sort order
		$this->order_priority = 1;


		//==================================================================
		// Run query and build xml content
		//==================================================================
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		if($this->c_edit_mode == __EDIT_MODE__)
		{
			// Define the lisha mode
			$this->c_edit_mode = __DISPLAY_MODE__;
			$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
			$xml .= "<edit_mode>true</edit_mode>";
		}
		else
		{
			$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
			$xml .= "<edit_mode>false</edit_mode>";
		}

		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * Called when user wants to do a general search
	 *
	 * @p_value  :   Filter value for global search - Multiple columns search
	====================================================================*/
	public function global_search($p_value)
	{
		$this->string_global_search = json_decode($p_value);
		$this->prepare_query();
		$this->string_global_search = $this->protect_js_txt(json_decode($p_value));

		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false)).'</toolbar>';
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * Called when user wants to record his custom view
	 * @p_name  :   Filter name from form
	====================================================================*/
	public function save_filter($p_name)
	{
		//==================================================================
		// Check if filter already exists
		// You can't record on a file name already created
		//==================================================================
		$s_sql = '  SELECT
							1
						FROM
							'.$this->get_quote_col(__LISHA_TABLE_FILTER__).'
						WHERE 1 = 1
							AND '.$this->get_quote_col('name').' = '.$this->get_quote_string($p_name).'
							AND '.$this->get_quote_col('id').' = '.$this->get_quote_string($this->c_id);

		$this->exec_sql($s_sql,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
		//==================================================================

		if($this->rds_num_rows($this->resultat) > 0)
		{
			// Filter already exists
			// Rise an error message to user
			header("Content-type: text/xml");
			$xml = "<?xml version='1.0' encoding='UTF8'?>";
			$xml .= "<lisha>";
			$xml .= "<error>true</error>";
			$xml .= "<title>".$this->protect_xml($this->lib(63))."</title>";
			$xml .= "<message>".$this->protect_xml($this->lib(62))."</message>";
			$xml .= "</lisha>";

			echo $xml;
		}
		else
		{
			// Filter name free, go on
			$s_sql = array();

			foreach($this->c_columns as $col_key => &$val_key)
			{
				//==================================================================
				// Clause order (ORD - order)
				//==================================================================
				if(is_numeric($val_key['order_priority']))
				{
					$s_sql[] = $this->prepare_query_insert_filter($p_name,$val_key['sql_as'],'ORD',$val_key['order_priority'],$val_key['order_by'],$col_key);
				}
				//==================================================================

				//==================================================================
				// Column position
				//==================================================================
				$s_sql[] = $this->prepare_query_insert_filter($p_name,$val_key['sql_as'],'CPS',$col_key);
				//==================================================================

				//==================================================================
				// Column input head filter (QSC Quick search)
				//==================================================================
				if(isset($val_key['filter']['input']))
				{
					/*if($val_key['data_type'] == __DATE__)
						{
							error_log(print_r($val_key['filter']['input']['filter'],true));
							//$val_key['filter']['input']['filter'] = $this->convert_localized_date_to_database_format($col_key,$val_key['filter']['input']['filter']);
						}
						*/
					$s_sql[] = $this->prepare_query_insert_filter($p_name,$val_key['sql_as'],'QSC',$col_key,$val_key['filter']['input']['filter']);
				}
				//==================================================================

				//==================================================================
				// Show or Hide column (DMD - Display mode)
				//==================================================================
				if($val_key['display'] == __HIDE__)
				{
					$s_sql[] = $this->prepare_query_insert_filter($p_name,$val_key['sql_as'],'DMD',$col_key,$val_key['display']);
				}
				//==================================================================

				//==================================================================
				// Column search mode (SMD - Search Mode)
				//==================================================================
				$s_sql[] = $this->prepare_query_insert_filter($p_name,$val_key['sql_as'],'SMD',$col_key,$val_key['search_mode']);
				//==================================================================

				//==================================================================
				// Column Alignment (ALI - Alignment)
				//==================================================================
				$s_sql[] = $this->prepare_query_insert_filter($p_name,$val_key['sql_as'],'ALI',$col_key,$val_key['alignment']);
				//==================================================================

				//==================================================================
				// Column size (SIZ - Size)
				//==================================================================

				//==================================================================
				// Column filter (IEQ - Include equal)
				//==================================================================

				//==================================================================
				// Column filter (IBT - Include between)
				//==================================================================

				//==================================================================
				// Column filter (EEQ - Exclude equal)
				//==================================================================

				//==================================================================
				// Column filter (EBT - Exclude between)
				//==================================================================
			}

			//==================================================================
			// Save global features
			//==================================================================
			// Global search
			$s_sql[] ='INSERT INTO '.__LISHA_TABLE_FILTER__.' (`name`, `id`, `id_column`, `type`, `val1`, `val2`, `val3`) VALUES ("'.$p_name.'","'.$this->c_id.'","##GLOBALF##","IEQ","'.$this->string_global_search.'","","")';
			//==================================================================

			//==================================================================
			// Get the filter definition to create query
			//==================================================================
			foreach($s_sql as $value)
			{
				$this->exec_sql($value,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
			}
			//==================================================================

			// Available load custom recorded
			$this->c_obj_graphic->any_filter = true;

			// Build XML return
			header("Content-type: text/xml");
			$xml = "<?xml version='1.0' encoding='UTF8'?>";
			$xml .= "<lisha>";
			$xml .= "<error>false</error>";
			$xml .= "</lisha>";

			echo $xml;
		}
	}
	/**===================================================================*/


	/**==================================================================
	 * Advanced filter on column
	 * Popup window to add advanced filter on column ( include a range, add list of specific value and so on...)
	 * @p_column    : Column identifier
	====================================================================*/
	public function lisha_internal_adv_filter($p_column)
	{
		$id_child = $this->c_id.'_child';

		// Create an instance of a lisha
		$_SESSION[$this->c_ssid]['lisha'][$id_child] = new lisha($id_child, $this->c_ssid, $this->c_db_engine,$this->c_ident,$this->c_dir_obj,__ADV_FILTER__);

		//==================================================================
		// Sub lisha of all include values
		//==================================================================
		$adv_filter_query_in_single = "	SELECT
								`".__LISHA_TABLE_INTERNAL_FILTER__."`.`id` AS `id`,
								`".__LISHA_TABLE_INTERNAL_FILTER__."`.`name` AS `name`,
								`".__LISHA_TABLE_INTERNAL_FILTER__."`.`type` AS `type`,
								`".__LISHA_TABLE_INTERNAL_FILTER__."`.`count` AS `count`,
								`".__LISHA_TABLE_INTERNAL_FILTER__."`.`operator` AS `operator`,  -- OPERATOR
								`".__LISHA_TABLE_INTERNAL_FILTER__."`.`low` AS `low`
							".$_SESSION[$this->c_ssid]['lisha']['configuration'][10]."
								`".__LISHA_TABLE_INTERNAL_FILTER__."`
							WHERE 1 = 1
								AND `".__LISHA_TABLE_INTERNAL_FILTER__."`.`id` = '".$this->c_ssid.$this->c_id."'
								AND `".__LISHA_TABLE_INTERNAL_FILTER__."`.`type` = 'IEQ'
								AND `".__LISHA_TABLE_INTERNAL_FILTER__."`.`name` = '".$this->c_columns[$p_column]["sql_as"]."'
						  ";

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_parent($this->c_id,$p_column);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__main_query',$adv_filter_query_in_single);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__title',$this->lib(131));

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__display_mode',__CMOD__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_readonly_mode',__RW__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_size(100,'%',100,'%');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__id_theme',$this->matchcode['__id_theme'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__internal_color_mask',$this->matchcode['__internal_color_mask'][0]);

		// Depend on Build dynamic css Background style for children in class_graphic.php
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_top_bar_page', $this->matchcode['__active_top_bar_page'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_bottom_bar_page', $this->matchcode['__active_bottom_bar_page'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_nb_line(50);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_tech_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_user_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_ticket', false);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__update_table_name', __LISHA_TABLE_INTERNAL_FILTER__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_column_separation',$this->matchcode['__active_column_separation'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_row_separation',$this->matchcode['__active_row_separation'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_read_only_cells_edit', __RW__);
		//==================================================================

		//==================================================================
		// Define columns
		//==================================================================
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL_FILTER__.'`.`id`','id','myid',__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'id');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_display_mode',false,'id');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL_FILTER__.'`.`name`','name','myname',__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'name');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_display_mode',false,'name');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL_FILTER__.'`.`type`','type', 'mytype',__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'type');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_display_mode',false,'type');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL_FILTER__.'`.`count`','count', 'count',__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'count');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_display_mode',false,'count');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL_FILTER__.'`.`operator`','operator', 'operator',__TEXT__, __WRAP__, __LEFT__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL_FILTER__.'`.`low`','low', 'value',__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __REQUIRED__, 'low');

		//==================================================================

		// Internal event
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_lisha_action(__ON_UPDATE__,__AFTER__,$id_child,Array('event_lisha_column_list(\''.$this->c_id.'\')'));

		// Order
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_order_column('low',__ASC__);

		// Table key
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_key(Array('id','name','type','count'));
		//==================================================================

		// External key auto increment
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_col_value('id',$this->c_ssid.$this->c_id);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_col_value('name',$this->c_columns[$p_column]["sql_as"]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_col_value('type','IEQ');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_col_value('count','');


		$_SESSION[$this->c_ssid]['lisha'][$id_child]->new_graphic_lisha();

		//==================================================================
		// Generate query results in XML
		//==================================================================
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->prepare_query();
		$json = $_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha_json_param();

		$html = "<div class='__".$this->matchcode['__id_theme'][0]."_lisha_tab_filter_ind_included'>IN</div><div class='__".$this->matchcode['__id_theme'][0]."_lisha_tab_filter_ind_excluded grey_el'>OUT</div><div class='__".$this->matchcode['__id_theme'][0]."_lisha_tab_filter_ind_excluded grey_el'>RANGE IN</div><div class='__".$this->matchcode['__id_theme'][0]."_lisha_tab_filter_ind_excluded grey_el'>RANGE OUT</div><div>".$_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha().'
						<div style="float:left;">ADVANCED FILTER on '.$this->c_columns[$p_column]["name"].'</div>
						<div style="float:right;">
							<table style="margin:0;padding:0;border-collapse:collapse;">
								<tr>
								<td style="margin:0;padding:0;"><div id="'.$this->c_id.'_child_valide" onclick="lisha_child_list_column_ok(\''.$this->c_id.'\');" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_valide hover" '.$this->hover_out_lib(121,121,'_child').' style="margin-right:5px;"></div></td>
								</tr>
							</table>
						</div>
					</div>
					';

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($html)."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * Sub module that can solve lov query if any
	 *
	 * @column	: Column identifier
	 * Return lov sql
	====================================================================*/
	private function solve_lov_main_query($column)
	{
		$sql = $this->c_columns[$column]['lov']['sql'];

		if(isset($this->c_columns[$column]['lov']['taglov']))
		{
			foreach($this->c_columns[$column]['lov']['taglov'] as $value)
			{
				if(isset($this->c_columns[$this->get_id_column($value['column'])]['filter']['input']['filter']))
				{
					//==================================================================
					// Get key
					$input_value = $this->c_columns[$this->get_id_column($value['column'])]['filter']['input']['filter'];

					$sql_lov = $this->c_columns[$this->get_id_column($value['column'])]['lov']['sql'];
					$query_final_pos = strripos($sql_lov, $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);

					$sql_condition = " AND ".$this->c_columns[$this->get_id_column($value['column'])]['lov']['before_as']." LIKE '%".$input_value."%'";

					$sql_lov_source =  'SELECT '.$value['column_return'].' AS `result` '.substr($sql_lov,$query_final_pos).$sql_condition;

					// Execute lov source sql
					$resultat = $this->exec_sql($sql_lov_source,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);
					$my_row = $this->rds_fetch_array($resultat);

					//==================================================================
					$sql = str_replace('||TAGLOV_'.$value['column'].'**'.$value['column_return'].'||',$my_row['result'],$sql);
				}
			}
		}
		return $sql;
	}
	/**===================================================================*/


	/**==================================================================
	 * Display distinct values or specific query defined by lisha creator on specific column
	 * Appears on right click on column red marble
	 *
	 * @column	: Column identifier
	====================================================================*/
	public function lisha_lov($column)
	{
		$id_child = $this->c_id.'_child';

		// Create a lisha instance
		$_SESSION[$this->c_ssid]['lisha'][$id_child] = new lisha($id_child,$this->c_ssid,$this->c_db_engine,$this->c_ident,$this->c_dir_obj,__POSSIBLE_VALUES__);

		if(isset($this->c_columns[$column]['lov']) && $this->c_columns[$column]['lov'])
		{
			// A custom LOV was defined
			$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__return_column_id',$this->c_columns[$column]['lov']['col_return']);
			$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__title',$this->c_columns[$column]['lov']['title']);

			// Solve lov query with TAGLOV or not
			$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__main_query',$this->solve_lov_main_query($column));

			// Browse each column of sub lisha
			foreach($this->c_columns[$column]['lov']['columns'] as $key => $lov_col)
			{
				// Focus
				if($lov_col['focus'])
				{
					$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_input_focus($lov_col['sql_as']);
				}

				$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column($lov_col['before_as'],$key,$lov_col['name'],$lov_col['data_type'],$lov_col['nowrap'], $lov_col['alignment']);

				if(isset($lov_col['order']))
				{
					$this->order_priority = $lov_col['order']['order_priority'];
					$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_order_column($key,$lov_col['order']['order_by']);
				}
			}
		}
		else
		{
			// No custom LOV defined : Display distinct values list by default
			$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__title',$this->lib(44));

			// Force specified distinct list on checkbox field
			if($this->c_columns[$column]['data_type'] == __CHECKBOX__)
			{
				$my_query = " 	SELECT
									`main`.`A` AS ".$this->get_quote_col($this->c_columns[$column]['sql_as'])."
									".$_SESSION[$this->c_ssid]['lisha']['configuration'][10]."
										(
										SELECT
											'0' AS `A`
										UNION ALL
										SELECT
											'1' AS `A`
										) `main`
										WHERE 1 = 1
								";
			}
			else
			{
				$query_final_pos = strripos($this->matchcode['__main_query'][0], $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);
				$my_query =  'SELECT DISTINCT '.$this->c_columns[$column]['before_as'].' AS '.$this->get_quote_col($this->c_columns[$column]['sql_as']).' '.substr($this->matchcode['__main_query'][0],$query_final_pos);
			}
			$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__main_query',$my_query);

			// To continue....
			if($this->c_columns[$column]['data_type'] == __CHECKBOX__)
			{
				$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`main`.`A`',$this->c_columns[$column]['sql_as'], $this->c_columns[$column]['name'],$this->c_columns[$column]['data_type'], __WRAP__, __LEFT__);
			}
			else
			{
				$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column($this->c_columns[$column]['before_as'],$this->c_columns[$column]['sql_as'], $this->c_columns[$column]['name'],$this->c_columns[$column]['data_type'], __WRAP__, __LEFT__);
			}

			$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_input_focus($this->c_columns[$column]['sql_as']);

			// Column in date format
			if($this->c_columns[$column]['data_type'] == __DATE__)
			{
				if(!isset($this->c_columns[$column]['date_format']))
				{
					// Use default system date format if no one
					$mydate = $_SESSION[$this->c_ssid]['lisha']['date_format'];
				}
				else
				{
					$mydate = $this->c_columns[$column]['date_format'];
				}
				$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_date_format',$mydate,$this->c_columns[$column]['sql_as']);
			}

			// Define order column id
			$this->order_priority = 1;
			$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_order_column($this->c_columns[$column]['sql_as'],__ASC__);
		}

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_parent($this->c_id,$column);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__display_mode',__CMOD__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__return_mode',__SIMPLE__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_readonly_mode',__R__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_size(100,'%',100,'%');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__id_theme',$this->matchcode['__id_theme'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__internal_color_mask',$this->matchcode['__internal_color_mask'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_nb_line(12);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_top_bar_page', $_SESSION[$this->c_ssid]['lisha'][$this->c_id]->read_attribute('__active_top_bar_page'));
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_bottom_bar_page', true);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_user_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_tech_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_ticket', false);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->new_graphic_lisha();

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_column_separation',$this->matchcode['__active_column_separation'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_row_separation',$this->matchcode['__active_row_separation'][0]);

		//==================================================================
		// Execute the query and display the elements
		//==================================================================

		// Prepare the query
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->prepare_query();

		$json = $_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha())."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * column_list
	 * List of available column ( column features )
	====================================================================*/
	public function column_list()
	{
		$id_child = $this->c_id.'_child';

		//==================================================================
		// Manage internal table
		//==================================================================

		// CLEAN TIMEOUT LINES IN DATABASE
		$query = 'DELETE
						FROM `'.__LISHA_TABLE_INTERNAL__.'`
						WHERE TIMEDIFF(NOW(),`tlu`) > \''.gmdate("H:i:s",$_SESSION[$this->c_ssid]['lisha']['configuration'][3]).'\'
					 ';
		$this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);


		// DELETE ALL LINES ALREADY EXISTS IN CURRENT KEY
		$query = 'DELETE
						FROM `'.__LISHA_TABLE_INTERNAL__.'`
						WHERE `id` = \''.$this->c_ssid.$this->c_id.'\'
					 ';
		$this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

		// OPTIMIZE TABLE
		$query = 'OPTIMIZE TABLE  `'.__LISHA_TABLE_INTERNAL__.'`;';
		$this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

		// BUILD ALL ARRAY ENTRIES IN TABLE
		foreach($this->c_columns as $key => $value)
		{
			if($value["display"] == "")
			{
				$value["display"] = 0;
			}

			// Display feature : low
			// Search mode : low1
			$query = 'INSERT
								INTO `'.__LISHA_TABLE_INTERNAL__.'`
								(`id`, `name`, `display`, `low`, `low1`, `ordre`)
								VALUES ("'.$this->c_ssid.$this->c_id.'", "'.$value["sql_as"].'", "'.$value["name"].'", "'.$value["display"].'", "'.$value["search_mode"].'", "'.$key.'")
						 ';

			$this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);

		}
		//==================================================================

		// Create an instance of a lisha
		$_SESSION[$this->c_ssid]['lisha'][$id_child] = new lisha($id_child, $this->c_ssid, $this->c_db_engine, $this->c_ident,$this->c_dir_obj,__COLUMN_LIST__);

		//==================================================================
		// Lisha display setup
		//==================================================================
		$main_query = "	SELECT
								`".__LISHA_TABLE_INTERNAL__."`.`id` AS `id`,
								`".__LISHA_TABLE_INTERNAL__."`.`name` AS `name`,
								`".__LISHA_TABLE_INTERNAL__."`.`display` AS `display`,
								`".__LISHA_TABLE_INTERNAL__."`.`low` AS `low`,
								`".__LISHA_TABLE_INTERNAL__."`.`low1` AS `low1`,
								`".__LISHA_TABLE_INTERNAL__."`.`ordre` AS `ordre`
							".$_SESSION[$this->c_ssid]['lisha']['configuration'][10]."
								`".__LISHA_TABLE_INTERNAL__."`
							WHERE 1 = 1
								AND `".__LISHA_TABLE_INTERNAL__."`.`id` = '".$this->c_ssid.$this->c_id."'
						  ";

		// Setup parent information
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_parent($this->c_id);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__main_query',$main_query);


		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__title',$this->lib(118));

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__display_mode',__CMOD__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__return_mode',__MULTIPLE__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_readonly_mode',__RW__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_size(100,'%',100,'%');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__id_theme',$this->matchcode['__id_theme'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__internal_color_mask',$this->matchcode['__internal_color_mask'][0]);

		// Depend on Build dynamic css Background style for children in class_graphic.php
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_top_bar_page', $this->matchcode['__active_top_bar_page'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_bottom_bar_page', $this->matchcode['__active_bottom_bar_page'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_nb_line(50);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_tech_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_user_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_ticket', false);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__update_table_name', __LISHA_TABLE_INTERNAL__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_column_separation',$this->matchcode['__active_column_separation'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_row_separation',$this->matchcode['__active_row_separation'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_read_only_cells_edit', __RW__);
		//==================================================================

		//==================================================================
		// Define columns
		//==================================================================
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL__.'`.`display`','display',$this->lib(119),__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'display');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL__.'`.`low`','low',$this->lib(120),__CHECKBOX__, __WRAP__, __CENTER__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __REQUIRED__, 'low');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_input_focus('low');					// Focused

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL__.'`.`low1`','low1',$this->lib(34),__BBCODE__, __WRAP__, __CENTER__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __REQUIRED__, 'low1');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL__.'`.`ordre`','ordre','sorted',__TEXT__, __WRAP__, __CENTER__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'ordre');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_display_mode',false,'ordre');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL__.'`.`id`','id','myid',__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'id');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_display_mode',false,'id');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`'.__LISHA_TABLE_INTERNAL__.'`.`name`','name','myname',__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'name');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_display_mode',false,'name');

		//==================================================================

		// Internal event
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_lisha_action(__ON_UPDATE__,__AFTER__,$id_child,Array('event_lisha_column_list(\''.$this->c_id.'\')'));

		// Order
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_order_column('ordre',__ASC__);

		// Table key
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_key(Array('id','name'));
		//==================================================================

		//==================================================================
		// Line theme mask ( fordidden to define for sub lisha )
		// Default color group used is 0
		// No Regeneration of generate_style
		//==================================================================
		// default group color with no group defined
		//$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_line_theme("FFFFFF","0.7em","9999CC","0.7em","99c3ed","0.7em","6690ba","0.7em","000","FFF");
		//$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_line_theme("EEEEEE","0.7em","AAAAAA","0.7em","336086","0.7em","003053","0.7em","000","888");


		$_SESSION[$this->c_ssid]['lisha'][$id_child]->new_graphic_lisha();

		//==================================================================
		// Execute query and build json content
		//==================================================================

		// All columns hidden ??
		$query = "  SELECT
							`name`  AS  'name',
							`low`   AS  'low'
						FROM
							`".__LISHA_TABLE_INTERNAL__."`
						WHERE 1 = 1
							AND `id` = '".$this->c_ssid.$this->c_id."'
							AND	`low`= 1
					 ";
		$resultat = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

		if($resultat->num_rows == 0)
		{
			$button_ok = "no";
		}
		else
		{
			$button_ok = "ok";
		}

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->prepare_query();
		$html = '<div style="float:right;">
						<table style="margin:0;padding:0;border-collapse:collapse;">
							<tr>
							<td style="margin:0;padding:0;"><div id="'.$this->c_id.'_child_valide" onclick="lisha_child_list_column_ok(\''.$this->c_id.'\');" class="__'.$this->matchcode['__id_theme'][0].'_ico __'.$this->matchcode['__id_theme'][0].'_ico_valide hover" '.$this->hover_out_lib(121,121,'_child').' style="margin-right:5px;"></div></td>
							</tr>
						</table>
					</div>
					';

		$json = $_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha_json_param(true,true,$button_ok);

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha().$html)."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * check_column_choice ( column_list sublisha )
	 * Return information about what it's possible or not
	====================================================================*/
	public function check_column_choice()
	{
		$statut = 'OK';
		$message = '';

		//==================================================================
		// All columns hidden ??
		//==================================================================
		$query = "  SELECT
							`name`  AS   'name',
							`low`   AS    'low'
						FROM
							`".__LISHA_TABLE_INTERNAL__."`
						WHERE 1 = 1
							AND `id` = '".$this->c_ssid.$this->c_id."'
							AND	`low`= 1
					 ";
		$resultat = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

		if($resultat->num_rows == 0)
		{
			$message .= $_SESSION[$this->c_ssid]['lisha']['lib'][124].'<hr>';
			$statut = 'KO';
		}
		//==================================================================

		//==================================================================
		// Try to hide a focus column ??
		//==================================================================
		$query = "  SELECT
							`name`  AS   'name',
							`low`   AS   'low'
						FROM
							`".__LISHA_TABLE_INTERNAL__."`
						WHERE 1 = 1
							AND `id` = '".$this->c_ssid.$this->c_id."'
							AND	`low` = 1
							AND `name` = '".$this->matchcode['__column_name_focus'][0]."'
					 ";
		$resultat = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

		if($resultat->num_rows == 0)
		{
			foreach($this->c_columns as $value)
			{
				if($this->matchcode['__column_name_focus'][0] == $value['sql_as'])
				{

					$message .= str_replace('$1',$value['name'],$_SESSION[$this->c_ssid]['lisha']['lib'][123]).'<hr>';
					$statut = 'KO';
					break;
				}
			}
		}
		//==================================================================

		$retour = array("STATUT" => $statut,"MESSAGE" => $message);
		echo json_encode($retour);
	}
	/**===================================================================*/


	/**==================================================================
	 * Hide/Show column
	 * Method called when valid column list management
	 * @p_selected_lines	: Selected line in json format
	====================================================================*/
	public function show_column($p_selected_lines)
	{
		// Define the selected lines
		$this->define_selected_line($p_selected_lines);

		//==================================================================
		// Recover data from table
		// Browse all columns
		//==================================================================
		$query = "SELECT
							`name`  AS  'name',
							`low`   AS  'low'
						FROM
							`".__LISHA_TABLE_INTERNAL__."`
						WHERE   1 = 1
							AND `id` = '".$this->c_ssid.$this->c_id."'
							AND	`low`= 1
					 ";
		$resultat = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

		if($resultat->num_rows == 0 && $this->matchcode['__column_name_focus'][0] == null)
		{
			// All rows hidden
			// Will never happens...
		}
		else
		{
			$query = "SELECT
								`name`  AS  'name',
								`low`   AS  'low',
								`low1`  AS  'low1'
							FROM
								`".__LISHA_TABLE_INTERNAL__."`
							WHERE 1 = 1
								AND `id` = '".$this->c_ssid.$this->c_id."'
						 ";
			$resultat = $this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link,false);

			while($row = $this->rds_fetch_array($resultat))
			{
				foreach($this->c_columns as $clef => $valeur)
				{
					if($valeur['sql_as'] == $row['name'])
					{
						// Record display
						$this->c_columns[$clef]['display'] = $row['low'];

						// Record search mode
						$this->c_columns[$clef]['search_mode'] = $row['low1'];
						break;
					}
				}
			}

		}

		// DELETE LINE AFTER USE
		$query = "  DELETE
						FROM
							`".__LISHA_TABLE_INTERNAL__."`
						WHERE `id` = '".$this->c_ssid.$this->c_id."'
					 ";
		$this->exec_sql($query,__LINE__,__FILE__,__FUNCTION__,__CLASS__,$this->link);
		//==================================================================

		//==================================================================
		// Build query and generate xml content
		//==================================================================
		$this->prepare_query();

		$json_line = $this->generate_json_line();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "<json_line>".$this->protect_xml($json_line)."</json_line>";
		$xml .= "</lisha>";
		//==================================================================
		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * lisha_load_filter_lov
	 * List of custom filter recorded
	====================================================================*/
	public function lisha_load_filter_lov()
	{
		$column = 1;
		$id_child = $this->c_id.'_child';
		// Create an instance of a lisha
		$_SESSION[$this->c_ssid]['lisha'][$id_child] = new lisha($id_child, $this->c_ssid, $this->c_db_engine, $this->c_ident,$this->c_dir_obj,__LOAD_FILTER__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__title',$this->lib(4).' ('.$this->matchcode['__key_url_custom_view'][0].')');
		$query_lov = "  SELECT
								`main`.`A` AS `name`,
								`main`.`B` AS `date`,
								`main`.`C` AS `id`
							".$_SESSION[$this->c_ssid]['lisha']['configuration'][10]."
							(
								SELECT
									DISTINCT
									`main`.`name` AS `A`,
									`main`.`date` AS `B`,
									`main`.`id` AS `C`
								FROM
									`".__LISHA_TABLE_FILTER__."` `main`
								WHERE 1 = 1
									AND `main`.`id` = '".$this->c_id."'
							) `main`
							WHERE 1 = 1
						 ";
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__main_query',$query_lov);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`main`.`A`','name',$this->lib(60),__TEXT__, __WRAP__, __LEFT__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_input_focus('name');					// Focused
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_column('`main`.`B`','date',$this->lib(61),__TEXT__, __WRAP__, __LEFT__);


		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_parent($this->c_id,$column);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__display_mode',__CMOD__);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__return_mode',__MULTIPLE__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_readonly_mode',__RW__);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_size(100,'%',100,'%');

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__id_theme',$this->matchcode['__id_theme'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__internal_color_mask',$this->matchcode['__internal_color_mask'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_top_bar_page', $_SESSION[$this->c_ssid]['lisha'][$this->c_id]->read_attribute('__active_top_bar_page'));
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_bottom_bar_page', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_nb_line(17);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->new_graphic_lisha();

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_tech_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_user_doc', false);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_ticket', false);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->new_graphic_lisha();

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_column_separation',$this->matchcode['__active_column_separation'][0]);
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_row_separation',$this->matchcode['__active_row_separation'][0]);

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__active_read_only_cells_edit', __R__);

		//==================================================================
		// Update table
		//==================================================================
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__update_table_name', __LISHA_TABLE_FILTER__);

		// Columns check update mode
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __REQUIRED__, 'name');
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_attribute('__column_input_check_update', __FORBIDDEN__, 'date');

		// Table key
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_key(Array('name','id'));
		//==================================================================

		$_SESSION[$this->c_ssid]['lisha'][$id_child]->define_lisha_action(__ON_DELETE__,__AFTER__,$id_child,Array('lisha_child_delete_load_entry(\''.$this->c_id.'\');'));


		//==================================================================
		// Execute query and build json content
		//==================================================================
		$_SESSION[$this->c_ssid]['lisha'][$id_child]->prepare_query();

		$json = $_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($_SESSION[$this->c_ssid]['lisha'][$id_child]->generate_lisha())."</content>";
		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";

		echo $xml;
		//==================================================================
	}
	/**===================================================================*/


	/**==================================================================
	 * rebuild_fast_query
	 * Rebuild query to get fast count of all rows
	 * @query	: Query to transform
	====================================================================*/
	public function rebuild_fast_query($query)
	{
		$indice = strripos($query, $_SESSION[$this->c_ssid]['lisha']['configuration'][10]);
		if(!$indice)
		{
			return false;
		}
		$where = substr($query,$indice);
		return $where;
	}
	/**===================================================================*/



	/**==================================================================
	 * Load user custom view
	 * @filter_name	: Filter name to load
	====================================================================*/
	public function lisha_load_filter($filter_name)
	{
		$this->get_and_set_filter($filter_name);

		//==================================================================
		// Run query and build xml content
		//==================================================================
		$this->prepare_query();
		$json = $this->generate_lisha_json_param();

		// XML return
		header("Content-type: text/xml");
		$xml = "<?xml version='1.0' encoding='UTF8'?>";
		$xml .= "<lisha>";
		$xml .= "<content>".$this->protect_xml($this->c_obj_graphic->draw_lisha($this->resultat,false,true))."</content>";
		if($this->c_edit_mode == __EDIT_MODE__)
		{
			// Define the lisha mode
			$this->c_edit_mode = __DISPLAY_MODE__;
			$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
			$xml .= "<edit_mode>true</edit_mode>";
		}
		else
		{
			$xml .= '<toolbar>'.$this->protect_xml($this->c_obj_graphic->generate_toolbar(false,$this->resultat)).'</toolbar>';
			$xml .= "<edit_mode>false</edit_mode>";
		}

		$xml .= "<json>".$this->protect_xml($json)."</json>";
		$xml .= "</lisha>";
		//==================================================================

		echo $xml;
	}
	/**===================================================================*/


	/**==================================================================
	 * Build internal query to record user filter
	====================================================================*/
	private function prepare_query_insert_filter($p_name,$p_id_column,$p_type,$p_val1,$p_val2 = '',$p_val3 = '')
	{
		if($p_val1 == '')
		{
			$p_val2 = 'NULL';
			$p_val3 = 'NULL';
		}
		else
		{
			$p_val2 = '"'.$p_val2.'"';
			$p_val3 = '"'.$p_val3.'"';
		}

		return 'INSERT INTO '.__LISHA_TABLE_FILTER__.' (`name`, `id`, `id_column`, `type`, `val1`, `val2`, `val3`) VALUES ("'.$p_name.'","'.$this->c_id.'","'.$p_id_column.'","'.$p_type.'","'.$p_val1.'",'.$p_val2.','.$p_val3.')';
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


	/**==================================================================
	 * protect_xml
	 * @txt string      :   String with xml protection
	====================================================================*/
	private function protect_xml($txt)
	{
		$txt = rawurlencode($txt);
		return $txt;
	}
	/**===================================================================*/


	/**
	 * Return the max column priority
	 */
	private function get_max_priority()
	{
		$priority = 0;
		foreach ($this->c_columns as $value)
		{
			if($value['order_priority'] > $priority)
			{
				$priority = $value['order_priority'];
			}
		}
		return $priority;
	}

	/**==================================================================
	 * Clear BBCode content of a string
	 * Useful if some BBCode are adding into your Query with CONCAT('[b]',....,'[/b]')
	 *
	 * @p_txt       :   String to remove BBCode
	====================================================================*/
	private function clearBBCode($p_text)
	{
		//$p_text = preg_replace('`\[EMAIL\]([^\[]*)\[/EMAIL\]`i','<a href="mailto:\\1">\\1</a>',$p_text);
		//$p_text = preg_replace('`\[EMAIL\](.*?)\[/EMAIL\]`i','<a href="mailto:\\1">\\1</a>',$p_text);
		// Information : Double [/email][/email] to avoid end of email area
		$p_text = preg_replace('`\[email\](.*?(\[/email\]\[/email\].*?)*)\[/email\](?!(\[/email\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[b\]([^\[]*)\[/b\]`i','<b>\\1</b>',$p_text);    // Generation 1 : First [ or ] encounter stop translation
		//$p_text = preg_replace('`\[b\](.*?)\[/b\]`i','<b>\\1</b>',$p_text);       // Generation 2 : Can't bold string [/b]
		// Generation 3 : Information : Double [/b][/b] to avoid end of bold area
		$p_text = preg_replace('`\[b\](.*?(\[/b\]\[/b\].*?)*)\[/b\](?!(\[/b\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[i\]([^\[]*)\[/i\]`i','<i>\\1</i>',$p_text);
		//$p_text = preg_replace('`\[i\](.*?)\[/i\]`i','<i>\\1</i>',$p_text);
		// Information : Double [/i][/i] to avoid end of italic area
		$p_text = preg_replace('`\[i\](.*?(\[/i\]\[/i\].*?)*)\[/i\](?!(\[/i\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[u\]([^\[]*)\[/u\]`i','<u>\\1</u>',$p_text);
		//$p_text = preg_replace('`\[u\](.*?)\[/u\]`i','<u>\\1</u>',$p_text);
		// Information : Double [/u][/u] to avoid end of underline area
		$p_text = preg_replace('`\[u\](.*?(\[/u\]\[/u\].*?)*)\[/u\](?!(\[/u\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[s\]([^\[]*)\[/s\]`i','<s>\\1</s>',$p_text);
		//$p_text = preg_replace('`\[s\](.*?)\[/s\]`i','<s>\\1</s>',$p_text);
		// Information : Double [/s][/s] to avoid end of stroke line area
		$p_text = preg_replace('`\[s\](.*?(\[/s\]\[/s\].*?)*)\[/s\](?!(\[/s\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[center\]([^\[]*)\[/center\]`','<p style="text-align: center;">\\1</p>',$p_text);
		//$p_text = preg_replace('`\[center\](.*?)\[/center\]`','<p style="text-align: center;">\\1</p>',$p_text);
		// Information : Double [/center][/center] to avoid end of center line area
		$p_text = preg_replace('`\[center\](.*?(\[/center\]\[/center\].*?)*)\[/center\](?!(\[/center\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[left\]([^\[]*)\[/left\]`i','<p style="text-align: left;">\\1</p>',$p_text);
		//$p_text = preg_replace('`\[left\](.*?)\[/left\]`i','<p style="text-align: left;">\\1</p>',$p_text);
		// Information : Double [/left][/left] to avoid end of left line area
		$p_text = preg_replace('`\[left\](.*?(\[/left\]\[/left\].*?)*)\[/left\](?!(\[/left\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[right\]([^\[]*)\[/right\]`i','<p style="text-align: right;">\\1</p>',$p_text);
		//$p_text = preg_replace('`\[right\](.*?)\[/right\]`i','<p style="text-align: right;">\\1</p>',$p_text);
		// Information : Double [/right][/right] to avoid end of right line area
		$p_text = preg_replace('`\[right\](.*?(\[/right\]\[/right\].*?)*)\[/right\](?!(\[/right\]))`i','\\1',$p_text);

		//$p_text = preg_replace('`\[img\]([^\[]*)\[/img\]`i','<img src="\\1" />',$p_text);
		//$p_text = preg_replace('`\[img\](.*?)\[/img\]`i','<img src="\\1" />',$p_text);
		// Information : Double [/img][/img] to avoid end of picture area
		$p_text = preg_replace('`\[img\](.*?(\[/img\]\[/img\].*?)*)\[/img\](?!(\[/img\]))`i','\\1',$p_text);
		$p_text = preg_replace('`\[img=(.*?)\](.*?(\[/img\]\[/img\].*?)*)\[/img\](?!(\[/img\]))`ie','"<img width=\"\\1\" src=\"".str_replace("[/img][/img]","[/img]","\\2")."\" />"',$p_text);

		//$p_text = preg_replace('`\[color=([^[]*)\]([^[]*)\[/color\]`i','<font color="\\1">\\2</font>',$p_text);
		//$p_text = preg_replace('`\[color=(.*?)\](.*?)\[/color\]`i','<font color="\\1">\\2</font>',$p_text);
		// Information : Double [/color][/color] to avoid end of color area
		$p_text = preg_replace('`\[color=(.*?)\](.*?(\[/color\]\[/color\].*?)*)\[/color\](?!(\[/color\]))`i','\\2',$p_text);

		//$p_text = preg_replace('`\[bg=([^[]*)\]([^[]*)\[/bg\]`i','<font style="background-color: \\1;">\\2</font>',$p_text);
		//$p_text = preg_replace('`\[bg=(.*?)\](.*?)\[/bg\]`i','<font style="background-color: \\1;">\\2</font>',$p_text);
		// Information : Double [/bg][/bg] to avoid end of background color area
		$p_text = preg_replace('`\[bg=(.*?)\](.*?(\[/bg\]\[/bg\].*?)*)\[/bg\](?!(\[/bg\]))`i','\\2',$p_text);

		//$p_text = preg_replace('`\[size=([^[]*)\]([^[]*)\[/size\]`i','<font size="\\1">\\2</font>',$p_text);
		//$p_text = preg_replace('`\[size=(.*?)\](.*?)\[/size\]`i','<font size="\\1">\\2</font>',$p_text);
		// Information : Double [/size][/size] to avoid end of font size area
		$p_text = preg_replace('`\[size=(.*?)\](.*?(\[/size\]\[/size\].*?)*)\[/size\](?!(\[/size\]))`i','\\2',$p_text);

		//$p_text = preg_replace('`\[font=([^[]*)\]([^[]*)\[/font\]`i','<font face="\\1">\\2</font>',$p_text);
		//$p_text = preg_replace('`\[font=(.*?)\](.*?)\[/font\]`i','<font face="\\1">\\2</font>',$p_text);
		// Information : Double [/font][/font] to avoid end of font area
		$p_text = preg_replace('`\[font=(.*?)\](.*?(\[/font\]\[/font\].*?)*)\[/font\](?!(\[/font\]))`i','\\2',$p_text);

		// Information : Double [/url][/url] to avoid end of URL area
		$p_text = preg_replace('`\[url\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`i','\\1',$p_text);
		$p_text = preg_replace('`\[url=(.*?)\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`i','\\2',$p_text);

		// Found a randomized string do not exists in string to convert
		$temp_str = '7634253332';while(stristr($p_text,$temp_str)){$temp_str = mt_rand();}
		$p_text = str_replace('[br][br]',$temp_str,$p_text);
		$p_text = preg_replace('`(?<!\[br\])\[br\](?!(\[br\]))`i','\\',$p_text);
		$p_text = str_replace($temp_str,'',$p_text);

		// Found a randomized string do not exists in string to convert
		$temp_str = '7634253332';while(stristr($p_text,$temp_str)){$temp_str = mt_rand();}
		$p_text = str_replace('[hr][hr]',$temp_str,$p_text);
		$p_text = preg_replace('`(?<!\[hr\])\[hr\](?!(\[hr\]))`i','\\',$p_text);
		$p_text = str_replace($temp_str,'',$p_text);

		return $p_text;
	}
	/**===================================================================*/


	private function clearHTML($txt)
	{
		$remplacement=true;
		while($remplacement)
		{
			$remplacement=false;
			$oldtxt=$txt;
			$txt = preg_replace('`<span([^>]*)>`i','',$txt);
			$txt = preg_replace('`</span>`i','',$txt);
			$txt = preg_replace('`<a([^>]*)>`i','',$txt);
			$txt = preg_replace('`</a>`i','',$txt);
			if ($oldtxt<>$txt)
			{
				$remplacement=true;
			}
		}
		return $txt;
	}
	/**===================================================================*/


	/**==================================================================
	 * Restore page navigation position
	====================================================================*/
	private function restore_page_position()
	{
		if($this->last_page_position != null)
		{
			$this->define_attribute('__current_page',$this->last_page_position);
			$this->define_limit_min($this->last_limit_min);
		}

		$this->clear_page_position_recorded();
	}
	/**===================================================================*/


	/**==================================================================
	 * Clear page navigation position recorded
	====================================================================*/
	private function clear_page_position_recorded()
	{
		$this->last_page_position = null;
		$this->last_limit_min =  null;
	}
	/**===================================================================*/


	/**==================================================================
	 * Try to solved htmlentities issue
	 *
	 * @p_str       :   String to convert
	====================================================================*/
	private function my_htmlentities($p_str)
	{
		$p_str = str_replace("&","&amp;",$p_str);
		$p_str = str_replace("<","&lt;",$p_str);
		$p_str = str_replace(">","&gt;",$p_str);

		return $p_str;
	}
	/**===================================================================*/


	private function lib($id)
	{
		return $_SESSION[$this->c_ssid]['lisha']['lib'][$id];
	}

	private function replace_chevrons($p_txt,$p_to_entity = true)
	{
		if($p_to_entity)
		{
			$p_txt = str_replace('&','&amp;',$p_txt);
			$p_txt = str_replace('>','&gt;',$p_txt);
			$p_txt = str_replace('<','&lt;',$p_txt);
		}
		else
		{
			$p_txt = str_replace('&','&amp;',$p_txt);
			$p_txt = str_replace('&gt;','>',$p_txt);
			$p_txt = str_replace('&lt;','<',$p_txt);
		}

		return $p_txt;
	}

	/**===================================================================*/
}