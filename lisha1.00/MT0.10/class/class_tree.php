<?php
/**==================================================================
 * __FILE_COMPRESSOR_DIRECTIVE_ON__
 * Main tree class and methode
 ====================================================================*/
	class itree extends class_bdd
	{
		//==================================================================
		// Define private attributs
		//==================================================================
		private $matchcode;				// Matchcode between internal external call and function name

		private $tree_conf;				// Define table name of configuration
		private $tree_text;				// Define table name of common tree text
		public  $tree_language;			// Define table name of language available

		private $total_node;			// Number of nodes in current language
		
		private $ssid;					// ssid
		private $internal_id;			// tree internal identifier
		private $application_release;	// Application release version
		
		private $code_offset;			// Décalage à droite du code source ( obsolete )
		private $language;				// Language of tree
		private $text;					// Application text
		private $fulllistexpand;		// Each item to expand
		private $message;				// Message to display for end user
		private $enable_ticket_link;	// True means link to tickets list is available
		private $display_version;		// True means display version of magic tree
		//==================================================================

		//==================================================================
		// Define public attributs
		//==================================================================

		public $tree_node;										// Define node table name
		public $tree_caption;									// Define caption table name
		public $tree_extra;										// Define extra features table name
		
		public $root_language;									// Language reference define in configuration table

		public $html_result;									// Final tree HTML content
		public $branch_id;										// Branch id
		public $search_name;									// Search field
		public $style;											// Style CSS
		public $expand_mode;									// true means for expand all node, false collapse all
		public $displaycheckbox;								// true means display checkbox else no display
		public $listcheckbox;									// List of items to mark
		public $listinvolved;									// Set of all items involved by an parent id
		public $listexpand;										// Set of items to expand to reach root item
		public $offsettabindex;									// Offset to start tabindex
																// Need to be larger than total of tree item Eg : 1000
		public $myfocus;										// null means no focus
		public $count_item_found;								// Number of item found in last search
		public $listhighlight;									// Set of items which match with $inputsearch
		public $edit_mode;										// False means display only : true means update available
		public $auto_flag_tree;									// true means automatic update in tree mark field, else means external manage
		public $inputsearch;									// String to search in tree
		public $action;											// Extra action to do on tree

		public $doc_user;										// true display user documentation button
		public $doc_tech;										// true display technical documentation button
		
		//public $html_entities;									// true means HTML tag will be displayed as typed
		public $use_bbcode;										// true means BBCode will be used
		
		public $event_functionCall_on_click_item;				// Javascript custom fonction name for selected item
		public $event_functionCall_on_new_item;					// Javascript custom fonction name for new item
		public $event_functionCall_on_delete_item;				// Javascript custom fonction name for item deletion
		//==================================================================
		
		
		/**==================================================================
		 * Constructor
		 * @p_ssid 					: ssid session identifier
		 * @p_tree_id				: tree identifier
		 * @p_tree_conf			 	: table name of configuration
		 * @p_tree_text				: table name for text tree message
		 * @p_tree_language			: table name for list of available localization
		 ====================================================================*/
		public function __construct($p_ssid,$p_tree_id,$p_tree_conf,$p_tree_text,$p_tree_language,$p_application_release)
		{
			$this->ssid = $p_ssid;
			$this->internal_id = $p_tree_id;
			$this->application_release = $p_application_release;
			
			// Reference to main table
			$this->tree_conf = $p_tree_conf;
			$this->tree_text = $p_tree_text;
			$this->tree_language = $p_tree_language;
			
			
			// Call the parent constructor
			parent::__construct($p_ssid);
			
			// From extends class : Database connexion
			$this->db_connexion();

			
			//==================================================================
			// Define matchcode array between external and internal attribut name
			// For internal call, use direct acces by $this->internal_name_attribut
			// A : All ( Read and Write
			// R : Read only
			// W : Write only
			//==================================================================
			$this->matchcode = array(
			'__automatic_flag_manage'		 									=> array('auto_flag_tree','A'),
			'__node_table_name' 												=> array('tree_node','A'),
			'__caption_table_name' 												=> array('tree_caption','A'),
			'__extra_table_name'												=> array('tree_extra','A'),
			'__language_id'														=> array('language','A'),
			'__active_edit'														=> array('edit_mode','A'),
			'__theme'															=> array('style','A'),
			'__focus_id'														=> array('myfocus','A'),
			'__caption_search'													=> array('search_name','A'),
			'__active_ticket_link'												=> array('enable_ticket_link','A'),
			'__active_display_version'											=> array('display_version','A'),
			'__html_base_index_focus'											=> array('offsettabindex','A'),
			'__display_check_box'												=> array('displaycheckbox','A'),
			'__list_checkbox_to_mark_id'										=> array('listcheckbox','A'),
			'__list_expand_root_id'												=> array('listexpand','A'),
			'__active_expand_all'												=> array('expand_mode','A'),
			'__active_user_doc'													=> array('doc_user','A'),
			'__active_tech_doc'													=> array('doc_tech','A'),
			'__action_id'														=> array('action','A'),
			'__active_bbcode'													=> array('use_bbcode','A'),
			'__on_click_item'													=> array('event_functionCall_on_click_item','W'),
			'__on_new_item'														=> array('event_functionCall_on_new_item','W'),
			'__on_delete_item'													=> array('event_functionCall_on_delete_item','W'),
			'__total_language_nodes'											=> array('total_node','R')
			);
			//==================================================================
			
			//==================================================================
			// Load tree configuration
			//==================================================================
			$query = "SELECT
						`valeur` AS 'valeur'  
					FROM 
						`".$this->tree_conf."`
					WHERE 1 = 1
						AND `id_conf` = 1
						AND `application_release` = '".$this->application_release."'";
		
			$result = $this->link_mt->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$this->root_language = $row['valeur'];

			//==================================================================
			// DEFAULT FACTORY SETTINGS
			// Load default factory features values
			// Do not use magic function ( __get, __set... ) for performance reasons !!!
			//==================================================================
			$this->tree_node = 'mt_tree';											// Table name of tree nodes
			$this->tree_caption = 'mt_caption';										// Table name of text localization
			$this->edit_mode = false;												// Display mode
			$this->auto_flag_tree = true;											// Flag are managed automaticaly
			$this->myfocus = null;													// null means no focus
			$this->enable_ticket_link = false;										// Tickets list acces deny
			$this->display_version = true;											// Display label of magictree version
			$this->tab_index_offset = 100;											// Technical parameters: 100 by default
            $this->language = $this->root_language;									// Get language from configuration table
            $this->style = 'TreeView_default';										// CSS style
			$this->search_name = '';												// No caption for input search
			$this->displaycheckbox = false;											// No checkbox displayed
			$this->listcheckbox = Array();											// None active checkbox
			$this->listexpand = Array();											// None special expanding node
			$this->expand_mode = false;												// Don't force all node expand mode
			$this->action = 0;														// Draw action, no specific action
			$this->code_offset = '';
			$this->branch_id = 0;
			$this->inputsearch = '';
			$this->listhighlight = '';												// None item to highlight ( research found item )

			$this->doc_user = true;													// Active user documentation
			$this->doc_tech = false;												// disable technical documentation
			
			$this->use_bbcode = false;												// No BBCode conversion
			//$this->html_entities = false;											// Use HTML translation
			
			// Set of custom function call
			$this->event_functionCall_on_click_item = null;						
			$this->event_functionCall_on_new_item = null;						
			$this->event_functionCall_on_delete_item = null;						

			$this->clear_variable();												// reset all working data
			//==================================================================			
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * Clear internal working variable
		 ====================================================================*/
		private function clear_variable()
		{
			$this->count_item_found = 'none';									// No item found
			$this->fulllistexpand = Array();									// No item list to expand
			$this->sqllistinvolved = '';										// ??? TODO
			$this->listinvolved = Array();
			$this->html_result = '';
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Magic function to restore database connexion on unserialize object
		 * DO NOT REMOVE
		 ====================================================================*/
		public function __wakeup()
		{
			// Restore database connexion in parent
			$this->db_connexion();	
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * Return number of all tree nodes in current language
		 ====================================================================*/
		public function count_node()
		{
			$query = "SELECT
						COUNT(1) AS 'total'  
					FROM 
						`".$this->tree_caption."`
					WHERE 1 = 1
						AND `language` = '".$this->language."'
						AND `application_release` = '".$this->application_release."'";
			
			$result = $this->link_mt->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			$this->total_node = $row['total'];
			
			return $this->total_node;
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Generate instance HTML header part
		 * Load customer event function call if defined
		 ====================================================================*/
		public function generate_html_header()
		{
			echo '<script type="text/javascript">
			var '.$this->internal_id.'navig= 0;';
			
			if($this->event_functionCall_on_click_item != null)
			{
				echo "var ".$this->internal_id."call_clicked = '".$this->event_functionCall_on_click_item."';";
			}
			if($this->event_functionCall_on_new_item != null)
			{
				echo "var ".$this->internal_id."call_new_item = '".$this->event_functionCall_on_new_item."';";
			}
			if($this->event_functionCall_on_delete_item != null)
			{
				echo "var ".$this->internal_id."call_delete_item = '".$this->event_functionCall_on_delete_item."';";
			}
			echo '</script>
			';
			
			// Build initial javascript function call of tree with hard coded focus ;)
			echo '
			<script type="text/javascript">
			function MagicTree_'.$this->internal_id.'()
			{
				//==================================================================
				// Setup Ajax configuration
				//==================================================================
				var configuration = new Array();	
				
				configuration["page"] = mt_root_path+"ajax/display.php";
				configuration["delai_tentative"] = 30000; // 30 seconds max
				configuration["max_tentative"] = 2;
				configuration["type_retour"] = false;		// ReponseText
				configuration["param"] = "ssid="+ssid+"&id="+"'.$this->internal_id.'";
			
				configuration["fonction_a_executer_reponse"] = "HTML_TreeReturn";
				configuration["param_fonction_a_executer_reponse"] = "\''.$this->internal_id.'\',\''.$this->myfocus.'\',\'0\'";
				
				// Do the call
				ajax_call(configuration);
				//==================================================================
			}
			</script>
			';
		}
		/*===================================================================*/	

				
		/**==================================================================
		 * Generate common HTML header part
		 * Load general tree text in php session
		 * Load javascript text
		 * @p_link			:	Active database connexion from your main page
		 * @p_ssid			:	url token value
		 * @p_table_text	:	name of magictree text table
		 * @p_table_conf	:	name of magictree configuration table
		 * @p_application	:	application release
		 * @p_language		:	current language of page ( Eg : ENG )
		 * @p_path 			: 	relatif path for includes
		 ====================================================================*/
		static function generate_common_html_header($p_link,$p_ssid,$p_table_text,$p_table_conf,$p_application,$p_language,$p_path = '')
		{
			//==================================================================
			// Read all general text in php and javascript session
			//==================================================================		
			$query = "SELECT
						`id_texte` 		AS 'ID',
						`long_text` 	AS 'LT',
						`medium_text` 	AS 'MT',
						`short_text` 	AS 'ST',
						`extended_text` AS 'ET'
					FROM 
						`".$p_table_text."`  
					WHERE 1 = 1
						AND `application_release` 	= '".$p_application."'
						AND `id_lang` 				= '".$p_language."' 
					ORDER BY 
						`id_texte`
				   ";
			$result = $p_link->query($query);
		
			$w_libelle_common = '';
			while ($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$_SESSION[$p_ssid]['MT']['global_text'][$row['ID']]['LT'] = $row['LT']; // Load long text
				$_SESSION[$p_ssid]['MT']['global_text'][$row['ID']]['MT'] = $row['MT']; // Load medium text
				$_SESSION[$p_ssid]['MT']['global_text'][$row['ID']]['ST'] = $row['ST']; // Load short text
				$_SESSION[$p_ssid]['MT']['global_text'][$row['ID']]['ET'] = $row['ET']; // Load short text
				
				$w_libelle_common .= 'mt_libelle_global["MTLT_'.$row['ID'].'"]=\''.rawurlencode($row['LT']).'\';';
				$w_libelle_common .= 'mt_libelle_global["MTMT_'.$row['ID'].'"]=\''.rawurlencode($row['MT']).'\';';
				$w_libelle_common .= 'mt_libelle_global["MTST_'.$row['ID'].'"]=\''.rawurlencode($row['ST']).'\';';
				$w_libelle_common .= 'mt_libelle_global["MTET_'.$row['ID'].'"]=\''.rawurlencode($row['ET']).'\';';
				
			}
			echo '<script type="text/javascript">var mt_libelle_global = new Array();
			var mt_root_path = \''.$p_path.$p_application.'/\';
			'.$w_libelle_common.'
			</script>
			';
			//==================================================================

			
			//==================================================================
			// Load CSS style
			//==================================================================
			echo '
			<link rel="stylesheet" href="'.$p_path.$p_application.'/theme/default/main.css" type="text/css"> <!-- * load default style * -->
			<link rel="stylesheet" href="'.$p_path.$p_application.'/theme/dev/main.css" type="text/css"> <!-- * load dev style * -->
			';
			//==================================================================
			
			
			//==================================================================
			// Load tree configuration in javascript session
			//==================================================================
			$query = "SELECT
						`id_conf` AS 'id_conf',
						`valeur` AS 'valeur'  
					FROM 
						`".$p_table_conf."`
					WHERE 1 = 1
						AND `application_release` = '".$p_application."'";
		
			$result = $p_link->query($query);
			
			// Build configuration javascript string
			$s_conf = '
			<script type="text/javascript">
			var conf_tree = new Array();';	
		
			while ($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$s_conf .= 'conf_tree['.$row['id_conf'].']=\''.rawurlencode($row['valeur']).'\';';	
			}
			
			echo $s_conf."</script>";
			//==================================================================

			
			echo '<script  type="text/javascript" src="'.$p_path.$p_application.'/js/tree.js"></script> <!-- javascript of magictree module -->
			<script  type="text/javascript" src="'.$p_path.$p_application.'/js/json.js"></script> <!-- javascript json module -->
			<script  type="text/javascript" src="'.$p_path.$p_application.'/js/ajax/common/ajax_generique_dev.js"></script> <!-- * Include Ajax connexion * -->
			<script  type="text/javascript" src="'.$p_path.$p_application.'/js/common/main_functions.js"></script> <!-- * Include main js functions * -->
			';
		}	
		/*===================================================================*/	
			
		
		
		/**==================================================================
		 * Main define attributs fonction
		 * Performance : Use only for external call
		 ====================================================================*/
		public function define_attribute($attribute,$value)
		{
			if(!isset($this->matchcode[$attribute]))
			{
				error_log(__FILE__.' name '.$attribute.' not defined in matchcode array');die();
			}
			else
			{
				if($this->matchcode[$attribute][1] == 'A' || $this->matchcode[$attribute][1] == 'W')
				{
					$var = $this->matchcode[$attribute][0];
					$this->$var = $value;
				}
				else
				{
					error_log(__FILE__.' name '.$attribute.' no write access');die();
				}
			}
		}
		/*===================================================================*/	


		/**==================================================================
		 * Main reader attributs fonction
		 * Performance : Use only for external call
		 ====================================================================*/
		public function read_attribute($attribute)
		{
			if(!isset($this->matchcode[$attribute]) )
			{
				error_log(__FILE__.' name '.$attribute.' not defined in matchcode array');die();
			}
			else
			{
				if($this->matchcode[$attribute][1] == 'A' || $this->matchcode[$attribute][1] == 'R')
				{
					$var = $this->matchcode[$attribute][0];
					return $this->$var;
				}
				else
				{
					error_log(__FILE__.' name '.$attribute.' not readable');die();
				}
			}
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Mark and delete sub node from a source node
		 * @p_mode :	D means delete node 
		 * 				Others values means mark or unmark node
		 * @node_id :	input id node action
		 ====================================================================*/
		public function move_node($p_mode,$source_id,$target_id)
		{
			$item_node_info = $this->get_id_info($source_id); 
			
			$parent_source = $item_node_info[0];
			$order_source = $item_node_info[1];
			//==================================================================
			
			
			if($p_mode == 'B') // BROTHER
			{
				//==================================================================
				$item_node_info = $this->get_id_info($target_id);
				
				if($target_id == 0 )
				{
					// First item of tree
					$parent_target = 0;
					$order_target = 0;
				}
				else
				{
					$parent_target = $item_node_info[0];
					$order_target = $item_node_info[1]+1;
				}
				//==================================================================
		
				//==================================================================
				// renumbering order from target id to prepare place
				//==================================================================
				$query = "UPDATE `".$this->tree_node."`
							SET `order` = `order` + 1
							WHERE
								`application_release` = '".$this->application_release."'
								AND `parent` = ".$parent_target."
								AND `order` >= ".$order_target."
							ORDER BY `order` desc";
				$result = $this->link_mt->query($query);
				//==================================================================
			
				//==================================================================
				// move item !!!!!
				//==================================================================
				$query = "UPDATE `".$this->tree_node."`
							SET `parent` = ".$parent_target.",
								`order` = ".$order_target."
							WHERE
								`application_release` = '".$this->application_release."'
								AND `id` = ".$source_id."
						 ";
				
				$result = $this->link_mt->query($query);
				//==================================================================	
		
				//==================================================================
				// renumbering order to fill hole
				//==================================================================
				$query = "UPDATE `".$this->tree_node."`
							SET `order` = `MTN`.`order` - 1
							WHERE
								`application_release` = '".$this->application_release."'
								AND `parent` = ".$parent_target."
								AND `order` > ".$order_source." 
							ORDER BY `order` asc";
				$result = $this->link_mt->query($query);
				//==================================================================
			}
			else // CHILDREN
			{
				//==================================================================
				// Recover information of target id
				//==================================================================
				$parent_target = $target_id;
				$order_target = 0;
				//==================================================================
		
				//==================================================================
				// renumbering order from target id to prepare place
				//==================================================================
				$query = "UPDATE `".$this->tree_node."`
							SET `order` = `order` + 1
							WHERE
								`application_release` = '".$this->application_release."'
								AND `parent` = ".$parent_target."
								AND `order` >= ".$order_target."
								ORDER BY `order` desc";
				
				$result = $this->link_mt->query($query);
				//==================================================================
			
				//==================================================================
				// move item !!!!!
				//==================================================================
				$query = "UPDATE `".$this->tree_node."`
							SET `parent` = ".$parent_target.",
								`order` = ".$order_target."
							WHERE
								`application_release` = '".$this->application_release."'
								AND `id` = ".$source_id."
						 ";
				
				$result = $this->link_mt->query($query);
				//==================================================================
			
				//==================================================================
				// renumbering order from source id to fill hole
				//==================================================================
				$query = "UPDATE `".$this->tree_node."`
							SET `order` = `order` - 1
							WHERE
								`application_release` = '".$this->application_release."'
								AND `parent` = ".$parent_source."
								AND `order` >= ".$order_source."
								ORDER BY `order` asc";
				
				$result = $this->link_mt->query($query);
				//==================================================================
			}
			
			$this->myfocus = $source_id; // Focus on target item
			echo $source_id;
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Mark and delete sub node from a source node
		 * @p_mode :	D means delete node 
		 * 				Others values means mark or unmark node
		 * @node_id :	input id node action
		 ====================================================================*/
		public function iterative_mark_delete($p_mode,$node_id)
		{

			//==================================================================
			// Recover mark status of clicked item
			//==================================================================
			$item = $this->get_id_info($node_id); 
			
			switch ($item[2]) // Test mark
			{
				case null: // Unknown, do mark
					$value_marked = '1';
				break;
				case 0: // Unmark, do mark
					$value_marked = '1';
				break;
				case 1: // Mark, do unmark
					$value_marked = "NULL";
				break;
			}
			//==================================================================
			
	
			// count how many rows involved
			$nb_ligne = $this->mass_scan_level($node_id,$this->get_any_child($node_id),1,$p_mode,$value_marked);
			
			//==================================================================
			// Renumbering order to avoid hole on clicked item level
			//==================================================================
			if ($p_mode == "D")
			{
				$query = "UPDATE `".$this->tree_node."`
					SET `order` = `order` - 1
					WHERE
						`application_release` = '".$this->application_release."'
						AND `parent` = ".$item[0]."
						AND `order` > ".$item[1]." order by `order` asc";
				$result = $this->link_mt->query($query);
			}
			//==================================================================
			
			$this->total_node = $this->count_node();
				
			$retour = array("ID" => $node_id, "TOTAL" => $nb_ligne,"MARK" => $value_marked);
			echo json_encode($retour);
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * Iteratif scan to mark or delete node
		 * @id			: ID identifier
		 * @is_parent	: true means has at least one child else false
		 * @counter		: Counter offset start, by default 1
		 * @action 		: false means only count scan nodes
		 * @mark		: Mark of item clicked
		 ====================================================================*/
		private function mass_scan_level($id,$is_parent,$counter = 1,$action = false,$mark = null) 
		{
			//==================================================================
			// Recover all level children's with id parent = $id
			// Means recover all items on current level
			//==================================================================
			$query = "	SELECT
							`MTN`.`id` 			AS 'id',
							`MTN`.`parent` 		AS 'parent',
							`MTN`.`order`			AS 'order'
			   			FROM
			   				`".$this->tree_node."` `MTN`
			   			WHERE 1 = 1
			   				AND `MTN`.`application_release` = '".$this->application_release."'
			   				AND `MTN`.`parent` = ".$id." 
			   				ORDER BY `MTN`.`order` ASC
			   		";
			
			$result = $this->link_mt->query($query);
			//==================================================================
	
			while($row = $result->fetch_array(MYSQLI_ASSOC)) 
			{
				$counter = $counter + 1;
				if($this->get_any_child($row['id']))
				{ // Parent node, go deeper in tree
					$counter = $this->mass_scan_level($row['id'], true,$counter,$action,$mark);
				}
				
				if ($action == "D") // Delete item
				{
					// Delete current tree node item
					$query2 = "	DELETE FROM `".$this->tree_node."`
			   					WHERE 1 = 1
			   					AND `application_release` = '".$this->application_release."'
			   					AND `id` = ".$row['id']." 
							 ";	
					$result2 = $this->link_mt->query($query2);
					// Delete caption item for all language
					$query2 = "	DELETE FROM `".$this->tree_caption."`
			   					WHERE 1 = 1
			   					AND `application_release` = '".$this->application_release."'
			   					AND `id` = ".$row['id']." 
							 ";	
					$result2 = $this->link_mt->query($query2);
					// Extra features table ( optional )
					if($this->tree_extra != null)
					{
						// Delete extra features item ( primary key : application_release + id
						$query2 = "	DELETE FROM `".$this->tree_extra."`
				   					WHERE 1 = 1
				   					AND `application_release` = '".$this->application_release."'
				   					AND `id` = ".$row['id']." 
								 ";	
						$result2 = $this->link_mt->query($query2);
					}
					
				}
				if ($action == "M") // Update item mark
				{
					// update current tree item
					$query2 = "	UPDATE `".$this->tree_node."`
									SET `mark` = ".$mark."
								WHERE 1 = 1								
					   				AND `application_release` = '".$this->application_release."'
					   				AND `id` = ".$row['id']."
					   		";
					$result2 = $this->link_mt->query($query2);
				}
			}
			
			//==================================================================
			// Update item mark of current level
			//==================================================================
			if ($action == "M")
			{
				// update current tree item
				$query = "	UPDATE `".$this->tree_node."`
								SET `mark` = ".$mark."
							WHERE 1 = 1								
				   				AND `application_release` = '".$this->application_release."'
				   				AND `id` = ".$id."
				   		";
				$result = $this->link_mt->query($query);
			}
			//==================================================================
	
			//==================================================================
			// And delete last item of current level
			//==================================================================
			if ($action == "D")
			{
				// Delete first selected item of node table
				$query = "	DELETE FROM `".$this->tree_node."`
		   					WHERE 1 = 1
		   					AND `application_release` = '".$this->application_release."'
		   					AND `id` = ".$id." 
						 ";
				$result = $this->link_mt->query($query);
				
				// Delete first selected item of caption table for all language
				$query = "	DELETE FROM `".$this->tree_caption."`
		   					WHERE 1 = 1
		   					AND `application_release` = '".$this->application_release."'
		   					AND `id` = ".$id." 
						 ";
				$result = $this->link_mt->query($query);
				
				// Delete first selected item of extra features table
				if(isset($this->tree_extra))
				{
					if($this->tree_extra != '')
					{
						// Delete extra features item ( primary key : application_release + id )
						$query = "	DELETE FROM `".$this->tree_extra."`
				   					WHERE 1 = 1
				   					AND `application_release` = '".$this->application_release."'
				   					AND `id` = ".$id." 
								 ";	
						$result = $this->link_mt->query($query);
					}
				}
			}
			//==================================================================
			
			return $counter;
		}
		/*===================================================================*/	

	
		/**==================================================================
		 * get_id_info return info of id
		 * @id			: Identifier of item
		 * return	: array(parent_id,order_id,mark_id)
		 ====================================================================*/
		private function get_id_info($id)
		{
			$query = "	SELECT
							`MTN`.`parent` AS 'parent',
							`MTN`.`order` AS 'order',
							`MTN`.`mark` AS 'mark' 
						FROM 
							`".$this->tree_node."` `MTN`
						WHERE 1 = 1
							AND `MTN`.`application_release`	= '".$this->application_release."'
							AND `MTN`.`id`		= ".$id."
							LIMIT 1
							-- AND `MTN``order`		= 0 -- PERF !! // TODO FORCE exact order list item
					";
			$result = $this->link_mt->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			if($row['parent'] == null)
			{
				$row['parent'] = 0;
				$row['order'] = 0;
				$row['mark'] = null;
			}
			
			return array($row['parent'],$row['order'],$row['mark']);		
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Update node title
		 * @p_mode :	L means manage in Local language only
		 * @node_id :	input id node action
		 * @caption : 	input made by user
		 ====================================================================*/
		public function update_node($p_mode,$node_id,$p_caption)
		{

			// MySQL injection protection
			$caption= str_replace("'","''",$this->mysql_protect($p_caption));
					
			// Full space string -- Caution not visible in HTML rendering
			$blank_string = trim($caption);
			
			if($p_mode == "L")
			{	
				// This language already exists in database ?
				$query = "SELECT 1 AS `output` FROM 
							`".$this->tree_caption."`
						WHERE
							 `application_release` = '".$this->application_release."'
							 AND `id` = ".$node_id."
							 AND `language` = '".$this->language."'
							 ";
				$result = $this->link_mt->query($query);
				
				$row = $result->fetch_array(MYSQLI_ASSOC); // Read current row
				
				if($row['output'] == 1)
				{
				// Caption update
				$query = "
							UPDATE `".$this->tree_caption."`
								SET `title` = '".$caption."'
							WHERE
								`application_release` = '".$this->application_release."'
							 	AND `id` = ".$node_id."
							 	AND `language` = '".$this->language."'
							 ";
				}
				else
				{
					// Local language does'nt exist, then create new title in local language
					$query = "
							INSERT 
							INTO `".$this->tree_caption."`
							(
							 `application_release`,
							 `id`,
							 `language`,
							 `title`
							)
							VALUES
							(
							 '".$this->application_release."',
							 ".$node_id.",
							 '".$this->language."',
							 '".$caption."'
							)
						";
				}
			}
			else
			{	// Icon update
				if($caption == '')
				{
					$caption = 'NULL';
				}
				else
				{
					$caption = "'".$caption."'";
				}
				$query = "UPDATE `".$this->tree_node."`
							SET `icon` = ".$caption."
						WHERE
							 `application_release` = '".$this->application_release."'
							 AND `id` = ".$node_id."
							 ";
			}
		
			$result = $this->link_mt->query($query);			
		}
		/*===================================================================*/	


		/**==================================================================
		 * Add new node
		 * @p_mode :	B means Brother
		 * 				C means Children
		 * @node_id :	input id node action
		 * @caption : 	input made by user
		 ====================================================================*/
		public function add_node($p_mode,$node_id,$p_caption)
		{
			// MySQL injection protection
			$caption= $this->mysql_protect($p_caption);
	
			switch ($p_mode)
			{
				case "B": // Insert a brother item
					// Get id parent
					$query = "	SELECT
									`MTN`.`parent` AS 'parent',
									`MTN`.`order` AS 'order'
								FROM
									`".$this->tree_node."` `MTN`
								WHERE 1 = 1
								AND `MTN`.`application_release` = '".$this->application_release."'
								AND `MTN`.`id` = ".$node_id."
								LIMIT 1
								";
					
					$result = $this->link_mt->query($query);
					
					$row = $result->fetch_array(MYSQLI_ASSOC);
		
					//==================================================================
					// If no item in tree, force initialization
					//==================================================================
					if($row['parent'] == null)
					{
						$row['parent'] = 0;
						$row['order'] = -1;
					}
					//==================================================================
					
					$id_parent = $row['parent'];
					$id_order = $row['order'];
				break;
				case "C": // Insert a children item
					$id_parent = $node_id; // Current item is the futur parent
					$id_order = -1; // First item
				break;	
			}
		
			// Add a place for new element in current tree level
			$query = "UPDATE `".$this->tree_node."`
						SET `order` = `order` + 1
						WHERE
							`application_release` = '".$this->application_release."'
							AND `parent` = ".$id_parent."
							AND `order` > ".$id_order."
						ORDER BY `order` desc";
			
			$result = $this->link_mt->query($query);
		
			//==================================================================
			// Add new element in table
			//==================================================================
			// No flag setup by default
			$new_order = $id_order+1;
			// Nodes table
			$query = " INSERT INTO `".$this->tree_node."`
							 (
							  `application_release`,
							  `id`,
							  `parent`,
							  `order`,
							  `mark`
							 )
							 VALUES 
							 (
							  '".$this->application_release."',
							  NULL,
							  ".$id_parent.",
							  ".$new_order.",
							  NULL
							 )
					";
			
			$result = $this->link_mt->query($query);
			
			// Recover auto increment id
			$this->myfocus = $this->link_mt->insert_id;
			
			// Caption table
			$query = " INSERT INTO `".$this->tree_caption."`
							 (
							  `application_release`,
							  `id`,
							  `language`,
							  `title`,
							  `description`
							 )
							 VALUES
							 (
							  '".$this->application_release."',
							  '".$this->myfocus."',
							  '".$this->language."',
							  '".$caption."',
							  '".$caption."'
							 )
					";
			
			$result = $this->link_mt->query($query);
		
			// Extra features table ( optional )
			if(isset($this->tree_extra) && $this->tree_extra != '')
			{
				$query = " INSERT INTO `".$this->tree_extra."`
								 (
								  `application_release`,
								  `id`
								 )
								 VALUES
								 (
								  '".$this->application_release."',
								  '".$this->myfocus."'
								 )
						";
				
				$result = $this->link_mt->query($query);
			}

		$this->total_node = $this->count_node();
		
		// Return id of new item and setup focus
		echo $this->myfocus;
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * Draw tree
		 ====================================================================*/
		public function draw_tree()
		{
			// First step
			$this->clear_variable();			
			//==================================================================		
			// Set of action in display
			//==================================================================		
			switch ($this->action)
			{
				case 0: // No action
					$this->build_full_array_expansion();
				break;	
				case 1: // Do search
					$this->search_items();
				break;
				case 2: // Clear search
					$this->listexpand = ''; 
					$this->listhighlight = '';
					$this->message = $_SESSION[$this->ssid]['MT']['global_text'][7]['LT'];
				break;
				case 3: // No more in use available
				break;	
				case 4: // Refresh display
					if ($this->listhighlight != '')
					{
						$this->listexpand = $this->listhighlight;
					}
					else
					{
						// No search
						// Keep current listexpand
					}
					$this->build_full_array_expansion();
				break;
				default:
				break;
			}
			//==================================================================		
			// Build tree
			$this->build_tree();

			//==================================================================
			// Manage number of item found in search
			//==================================================================
			if($this->count_item_found != 'none')
			{
				if($this->count_item_found == 'NoItemFound')
				{
					$this->message = str_replace('$s',htmlentities($this->inputsearch,ENT_QUOTES,'UTF-8'),$_SESSION[$this->ssid]['MT']['global_text'][5]['LT']);
				}
				else
				{
					$this->message = str_replace('$s',htmlentities($this->inputsearch,ENT_QUOTES,'UTF-8'),str_replace('$i',$this->count_item_found,$_SESSION[$this->ssid]['MT']['global_text'][6]['LT']));
				}
			}
			//==================================================================
			
			
			//==================================================================
			// Catch mouse event on body div
			//==================================================================
			if(!$this->edit_mode)
			{
				$flying_popup = '';
			}
			else
			{
				$flying_popup = ' onmousemove="node_move(\''.$this->internal_id.'\',event);" ';
			}
			//==================================================================
			
			// Define HTML Div structure
			echo '
			<div id="'.$this->internal_id.'main" class="'.$this->style.'">
				
				<div id="'.$this->internal_id.'headbar" class="'.$this->style.'_headbar">';
				if($this->display_version)
				{
					echo '	<div id="'.$this->internal_id.'_version" class="'.$this->style.'_version"';
					if ($this->enable_ticket_link)
					{
						echo ' style="cursor: pointer;" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][27]['LT']).'\')" onClick="window.open(\''.$this->application_release.'/bugs\');"';
					}
					echo '>'.$this->application_release.'</div>';
				}	
					echo '<div id="'.$this->internal_id.'_search" class="'.$this->style.'_search">'.$this->search_name.' <input id="'.$this->internal_id.'_searchinput" onkeydown="return mt_key_manager(event,\''.$this->internal_id.'\')" type="text" value="'.htmlentities($this->inputsearch,ENT_QUOTES,'UTF-8').'" size=30 maxlength=50></div>
					<div id="'.$this->internal_id.'_bar_button" class="'.$this->style.'_bar_button">
					<div id="'.$this->internal_id.'_button_search" class="'.$this->style.'_button_search" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][3]['LT']).'\')" onClick="search_on_tree(\''.$this->internal_id.'\')"></div>
								<div id="'.$this->internal_id.'_bar_navigation" class="'.$this->style.'_bar_navigation" ';
			if($this->listhighlight != '')
			{
				// Always display navigation bar if some items highlighted 
				echo 'style="display:block;"';		
			}
			echo '>					
									<div id="'.$this->internal_id.'_button_navigation_first" class="'.$this->style.'_button_navigation_first" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][20]['LT']).'\')" onClick="navigation_focus(\''.$this->internal_id.'\',\'first\')"></div>
									<div id="'.$this->internal_id.'_button_navigation_before" class="'.$this->style.'_button_navigation_before" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][21]['LT']).'\')" onClick="navigation_focus(\''.$this->internal_id.'\',\'before\')"></div>
									<div id="'.$this->internal_id.'_button_navigation_next" class="'.$this->style.'_button_navigation_next" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][22]['LT']).'\')" onClick="navigation_focus(\''.$this->internal_id.'\',\'next\')"></div>
									<div id="'.$this->internal_id.'_button_navigation_last" class="'.$this->style.'_button_navigation_last" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][23]['LT']).'\')" onClick="navigation_focus(\''.$this->internal_id.'\',\'last\')"></div>
								</div>
						<div id="'.$this->internal_id.'_button_off" class="'.$this->style.'_button_collapse" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][2]['LT']).'\')" onClick="reduce_expand_all(\''.$this->internal_id.'\',false)"></div>
						<div id="'.$this->internal_id.'_button_on" class="'.$this->style.'_button_expand" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][1]['LT']).'\')" onClick="reduce_expand_all(\''.$this->internal_id.'\',true)"></div>
						<div id="'.$this->internal_id.'_button_refresh" class="'.$this->style.'_button_refresh" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][13]['LT']).'\')" onClick="refresh_display(\''.$this->internal_id.'\',\''.$this->myfocus.'\')"></div>
						';
				if($this->doc_user)
				{
					echo '<div id="'.$this->internal_id.'_button_doc_user" class="'.$this->style.'_button_doc_user" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][28]['ST']).'\')" onClick="window.open(\''.$this->application_release.'\')"></div>';
				}
				if($this->doc_tech)
				{
					echo '<div id="'.$this->internal_id.'_button_doc_tech" class="'.$this->style.'_button_doc_tech" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.$this->javascript_protect($_SESSION[$this->ssid]['MT']['global_text'][29]['ST']).'\')" onClick="window.open(\''.$this->application_release.'/indextech.php\')"></div>';
				}
				echo ' </div>';			
			// Add specific toolbar in update mode
			/*if ($this->edit_mode)
			{
			echo '			
					<div id="'.$this->internal_id.'_bar_edit" class="'.$this->style.'_bar_edit">
						<div id="'.$this->internal_id.'_button_add_brother_item" class="'.$this->style.'_button_add_brother_item" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.str_replace("'","\'",$_SESSION[$this->ssid]['MT']['global_text'][11]['LT']).'\')" onClick="reduce_expand_all(\''.$this->internal_id.'\',true)"></div>
						<div id="'.$this->internal_id.'_button_add_child_item" class="'.$this->style.'_button_add_child_item" onmouseout="display_message_tree(\''.$this->internal_id.'_message\',\'\')" onmouseover="display_message_tree(\''.$this->internal_id.'_message\',\''.str_replace("'","\'",$_SESSION[$this->ssid]['MT']['global_text'][12]['LT']).'\')" onClick="reduce_expand_all(\''.$this->internal_id.'\',true)"></div>
					</div>
				';
			}
			*/
			
			
			echo '	
				<div id="'.$this->internal_id.'_message" class="'.$this->style.'_message">'.$this->message.'</div>
				<div id="'.$this->internal_id.'search_focus_last" style="display:none;">'.$this->count_item_found.'</div>
					
				</div>	
				<div id="'.$this->internal_id.'head" '.$flying_popup.' class="'.$this->style.'_overflow'.'">'.$this->html_result.'</div>
				<div id="'.$this->internal_id.'mask" class="'.$this->style.'_mask"></div>
					
					<div id="'.$this->internal_id.'infobulle" class="'.$this->style.'_infobulle"></div>
					<div tabindex=1 id="'.$this->internal_id.'infobulle_cancel" class="'.$this->style.'_infobulle_cancel" onclick="infobulle_display(\''.$this->internal_id.'\')" onkeydown="return input_key_button_infobulle(event,\''.$this->internal_id.'\',\'cancel\')"></div>
					<div tabindex=2 id="'.$this->internal_id.'infobulle_ok" class="'.$this->style.'_infobulle_ok"></div>
				
				<div id="'.$this->internal_id.'main_wait_ajax" class="'.$this->style.'_main_wait_ajax"></div>
				<div id="'.$this->internal_id.'wait_ajax" class="'.$this->style.'_wait_ajax"></div>
				
				<div id="'.$this->internal_id.'move_item" '.$flying_popup.' class="'.$this->style.'_move_item"></div>
				
			</div>';
			
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Get all childrens from a starting point ( id )
		 * @id : parent id to begin scan
		 ====================================================================*/
		private function get_all_childrens($id_source)
		{
			unset($this->listinvolved);
			$this->sqllistinvolved = "('".$id_source."',";
			$this->listinvolved[$id_source] = true; // first, record source id in array
			$this->scan_level_item($id_source,$this->get_any_child($id_source));
			$this->sqllistinvolved .= "'".$id_source."')";
		}
		/**==================================================================
		 * Scan level item
		 * @id			: ID identifier
		 * @is_parent	: true means has at least one child else false
		 ====================================================================*/
		private function scan_level_item($id,$is_parent) 
		{
			//==================================================================
			// Recover all level children's with id parent = $id
			// Means recover all items on current level
			//==================================================================
			$query = "	SELECT
							MTN.`id` 			AS 'id',
							MTN.`parent` 		AS 'parent',
							MTN.`order`			AS 'order',
							MTC.`title`			AS 'value',
							CONCAT('".$this->internal_id."',MTN.`id`) AS 'MyTreeItem'
			   			FROM
			   				`".$this->tree_node."` MTN,
			   				`".$this->tree_caption."` MTC
			   			WHERE 1 = 1
							AND MTN.`id` = MTC.`id`
							AND MTN.`application_release` = MTC.`application_release`
				   			AND MTN.`application_release` = '".$this->application_release."'
			   				AND MTC.`language` = '".$this->language."'
			   				AND MTN.`parent` = ".$id." 
			   				ORDER BY MTN.`order` ASC
			   		";

			$result = $this->link_mt->query($query);
			//==================================================================

			//==================================================================
			// Scan all item in current level
			//==================================================================
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
			{
				$this->listinvolved[$row['id']] = true; // Record id in array
				$this->sqllistinvolved = $this->sqllistinvolved."'".$row['id']."',";
				if($this->get_any_child($row['id']))
				{
					// More deeper in tree
					$this->scan_level_item($row['id'], true);
				}
			}
			//==================================================================
			
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Build tree
		 * Final html result in $this->html_result
		 ====================================================================*/
		private function build_tree()
		{
			$this->html_result .= '<ul class="'.$this->style.'_ul_first" onkeydown="return input_main_key(event,\''.$this->internal_id.'\')">';

			//==================================================================
			// Always first this row in tree ( edit mode only )
			//==================================================================
			if ($this->edit_mode)
			{
				$this->html_result .= '<div class="'.$this->style.'_interow" id="'.$this->internal_id.'INT_0" OnClick="add_new_node(\''.$this->internal_id.'\',0)"></div>';
			}
			//==================================================================
			
			$this->scan_level(0,$this->get_any_child(1));

			$this->html_result .= '<ul class="'.$this->style.'_ul">';
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * search items in tree
		 ====================================================================*/
		private function search_items()
		{
			$inputsearch = str_replace("\\","\\\\",$this->inputsearch); // SRX_BACKSLASH_FIX_ISSUE
			if(substr($this->inputsearch,0,1) == "\\")
			{
				$pre_jocker = '';
			}
			else
			{
				$pre_jocker = '%';
			}
			
			$query = "	SELECT
							`MTN`.`id` AS 'id'
						FROM 
							`".$this->tree_node."` `MTN`,
							`".$this->tree_caption."` `MTC`
						WHERE 1 = 1
							AND `MTN`.`id` = `MTC`.`id`
							AND `MTN`.`application_release` = `MTC`.`application_release`
							AND `MTN`.`application_release`	= '".$this->application_release."'
							AND `MTC`.`language` = '".$this->language."'
							AND `MTC`.`title`		like '".$pre_jocker.$this->mysql_protect($inputsearch)."%'
					";
			$result = $this->link_mt->query($query);
			
			// Clear list
			$this->listexpand = ''; 
			$this->listhighlight = '';
			
			$this->count_item_found = 0;
			
			while ($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				// Prepare array for full expansion
				$this->listexpand[$row['id']] = true;
				
				// Build array to highlight item
				$this->listhighlight[$row['id']] = true;
				
				$this->count_item_found = $this->count_item_found + 1; // Count number of entries in current search
			}

			if(!$this->count_item_found)
			{
				// Search launched but no item found
				$this->count_item_found = 'NoItemFound';
			}
			
			$this->build_full_array_expansion();
			
			//$_SESSION[$this->ssid]['MT'][$this->internal_id]["expandlist"] = $this->listexpand;
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Build array of expansion item
		 * Read $this->listexpand
		 ====================================================================*/
		private function build_full_array_expansion()
		{
			if(is_array($this->listexpand) )
			{
				// Build expansion list to root for all item in listexpand array
				while(list($key, $val) = each($this->listexpand))
				{
					$this->get_all_parent_to_root($key);
				}
								
				// At last, remove all duplicate entries
				$this->fulllistexpand = array_unique($this->fulllistexpand);
			}
			// Add focused item to expand list
			if($this->myfocus != null)
			{
				$this->get_all_parent_to_root($this->myfocus);
			}
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Build list with all node to be expanded
		 * load list of item into this->fulllistexpand
		 ====================================================================*/
		private function get_all_parent_to_root($id)
		{
			array_push($this->fulllistexpand,$id); // Load parent item into temporary table
			
			$parent = $this->get_parent($id); // Next parent item

			if($parent != 0)
			{
				$this->get_all_parent_to_root($parent);		
			}
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * return parent of $id
		 * @id	: ID item identifier
		 ====================================================================*/
		private function get_parent($id)
		{
			$query = "	SELECT
							`MTN`.`parent` AS 'parent'
						FROM 
							`".$this->tree_node."` `MTN`
						WHERE 1 = 1
							AND `MTN`.`application_release`	= '".$this->application_release."'
							AND `MTN`.`id`		= ".$id."
					";
			$result = $this->link_mt->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);	
			return $row['parent'];		
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * count: return total of item in tree
		 * No input parameters 
		 * return numeric
		 ====================================================================*/
		private function count()
		{
			$query = "	SELECT count(1) AS 'total'
						FROM
							`".$this->tree_node."` `MTN`
						WHERE 1 = 1
							AND `MTN`.`application_release`	= '".$this->application_release."'
					";
			$result = $this->link_mt->query($query);

			$row = $result->fetch_array(MYSQLI_ASSOC);	
			return $row['total'];		
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * $id have at least one child ?
		 * return	: 1 if yes else false
		 * @id	: ID identifier
		 ====================================================================*/
		private function get_any_child($id)
		{
			$query = "	SELECT
							1
						FROM 
							`".$this->tree_node."` `MTN`
						WHERE 1 = 1
							AND `MTN`.`application_release`	= '".$this->application_release."'
							AND `MTN`.`parent`		= ".$id."
							LIMIT 1
							-- AND `MTN`.`order`		= 0 -- PERF !! TODO FORCE exact order list item
					";
			$result = $this->link_mt->query($query);

			return mysqli_num_rows($result);		
		}
		/*===================================================================*/	
		
		
		/**==================================================================
		 * Browse $id elements level and build HTML contents
		 * @id			: ID identifier
		 * @is_parent	: true means has at least one child else false
		 ====================================================================*/
		private function scan_level($id,$is_parent)
		{

			if ($is_parent)
			{ 
				// Parent's occurence ? => Build struct to go more deeper in tree
				$this->html_result .= '<ul class="'.$this->style.'_ul">';
			}
			
			//==================================================================
			// Recover all level children's with id parent = $id
			// Means recover all items on current level
			//==================================================================
			$query = "	SELECT
							`MTN`.`id` 			AS 'id',
							`MTN`.`parent` 		AS 'parent',
							`MTN`.`mark`			AS 'mark',
							`MTN`.`icon`			AS 'icon',
							`MTN`.`order`			AS 'order',
							`MTC`.`title`			AS 'value',
							CONCAT('".$this->internal_id."',`MTN`.`id`) AS 'MyTreeItem'
			   			FROM
			   				`".$this->tree_node."` `MTN`, 
			   				`".$this->tree_caption."` `MTC`
			   			WHERE 1 = 1
							AND `MTN`.`id` = `MTC`.`id`
							AND `MTN`.`application_release` = `MTC`.`application_release`
				   			AND `MTN`.`application_release` = '".$this->application_release."'
			   				AND `MTC`.`language` = '".$this->root_language."'
			   				AND `MTN`.`parent` = ".$id." 
			   				ORDER BY `MTN`.`order` ASC
			   		";
			$result = $this->link_mt->query($query);
			//==================================================================

			//==================================================================
			// Main Extand or collapse all items
			//==================================================================
			if($this->expand_mode)
			{
				$expend_class = "expanded";
			}
			else
			{
				$expend_class = "collapsed";
			}
			//==================================================================

			//==================================================================
			// Main On/Off switch to display checkbox
			//==================================================================
			if(!$this->displaycheckbox)
			{
				// don't display checkbox
				$displaycheckbox = "_nonemark";
			}
			else
			{
				// Display unset checkbox by default
				$displaycheckbox = "_unmark";
			}
			//==================================================================
			
			if(!$this->edit_mode)
			{
				//==================================================================
				// Display mode
				//==================================================================
				
				//==================================================================
				// Browse all items of tree on a level
				//==================================================================
				$highlight = "none";
				
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) 
				{
					if($row['icon'] == '')
					{
						$row['icon'] = 'void_icon';
					}
					//==================================================================
					// Read list of checkbox if any
					//==================================================================
					if($this->displaycheckbox)
					{
						// Ok, checkbox are visible, go on
						
						// Test inside mode
						if($this->auto_flag_tree) 
						{
							// Inside mode actif :
							// Read data from tree table
							if($row['mark'])
							{
								$displaycheckbox = "_mark";
							}
							else
							{
								$displaycheckbox = "_unmark";
							}
						}
						else
						{
							// Outide mode actif :
							// Read data mark from specific array listcheckbox
							if( array_key_exists($row['id'],$this->listcheckbox) )
							{
								$displaycheckbox = "_mark";
							}
							else
							{
								$displaycheckbox = "_unmark";
							}
						}
						
					}	
					//==================================================================
					
					//==================================================================
					// Read full item expansion
					//==================================================================
					if($this->fulllistexpand)
					{
						if ( in_array($row['id'],$this->fulllistexpand) && $expend_class == "collapsed")
						{
							$final_expend_class = "expanded";
						}
						else
						{
							$final_expend_class = $expend_class;
						}
					}
					else
					{
						$final_expend_class = $expend_class;
					}
					//==================================================================
					
					//==================================================================
					// Read item to highlight
					//==================================================================
					if(is_array($this->listhighlight))
					{
						if(array_key_exists($row['id'],$this->listhighlight))
						{
							$highlight = "highlight";
							//$_SESSION[$this->ssid]['MT'][$this->internal_id]["myfocus"] = $row['id']; // Last item focused
							$this->myfocus = $row['id'];
						}
						else
						{
							$highlight = "none";
						}
					}
					//==================================================================

					//==================================================================
					// Local language for node title already exists or not ??
					//==================================================================
					$query2 = "	SELECT
							`MTC`.`title` AS 'value'
			   			FROM
			   				`".$this->tree_caption."` `MTC`
			   			WHERE 1 = 1
							AND `MTC`.`id` = '".$row['id']."'
							AND `MTC`.`application_release` = `MTC`.`application_release`
			   				AND `MTC`.`language` = '".$this->language."'
			   		";
					$result2 = $this->link_mt->query($query2);
					$row2 = $result2->fetch_array(MYSQLI_ASSOC);
										
					if($row2['value'] == '')
					{
						$title = $row['value'];
						$class_title = $this->style.'_language_not_exists';
					}
					else
					{
						$title = $row2['value'];
						$class_title = '';
					}

					//==================================================================
					
					//==================================================================
					// Build item node in tree
					//==================================================================

					//=========================
					$final_title = htmlentities($title,ENT_QUOTES,'UTF-8');
                    if($title == 'horizontal [br] line')
                    {
                        //error_log('xxx'.$final_title);
                        error_log($this->convertBBCodetoHTML($final_title));
                    }
					// Only space in string
					if(trim($title) == '')
					{
						$final_title = str_replace(' ', '&nbsp;',$final_title);
					}
					if($this->use_bbcode)
					{
						$final_title = $this->convertBBCodetoHTML($final_title);
					}
					//=========================

					if ($this->get_any_child($row['id']))
					{	
						// A child exist for this id
						// Build Open structure for next level
						$this->html_result .= '<li tabindex="'.$this->offsettabindex.$row['id'].'" id="'.$row['MyTreeItem'].'" class="'.$final_expend_class.'"';

						// A custom function is defined for item selection ?
						if ($this->event_functionCall_on_click_item != null)
						{
							$this->html_result .= ' onClick="MT_selected_item(event,\''.$this->internal_id.'\',\''.$row['id'].'\',\'D\',\'\')"';
						}
						$this->html_result .= '><div class="'.$this->style.'_click_expand" id="INT_'.$row['id'].'" onClick="toogle_nodes(\''.$this->internal_id.'\',\''.$row['MyTreeItem'].'\',\''.$row['id'].'\',\'1\')"></div><div class="'.$this->style.$displaycheckbox.'" id="check'.$row['MyTreeItem'].'"></div><div class="'.$row['icon'].'" id="icon_'.$row['MyTreeItem'].'"></div><span id="up_cap_'.$row['MyTreeItem'].'" class="'.$this->style.'_item_caption_'.$highlight.' '.$class_title.'">'.$final_title.'</span>';

						//==================================================================
						// Recurif Call, Deeper in tree
						//==================================================================
						$this->scan_level($row['id'], true);
						//==================================================================
						
						// Close the next level
						$this->html_result .= '</li>';
					}
					else 
					{ 	
						// No more child
						// Just build an simple item
						$this->html_result .= '<li tabindex="'.$this->offsettabindex.$row['id'].'" id="'.$row['MyTreeItem'].'"';
						
						// A custom function is defined for item selection ?
						if ($this->event_functionCall_on_click_item != null)
						{
							$this->html_result .= ' onClick="MT_selected_item(event,\''.$this->internal_id.'\',\''.$row['id'].'\',\'D\',\'\')"';
						}
						$this->html_result .= '><div class="'.$this->style.$displaycheckbox.'" id="check'.$row['MyTreeItem'].'"></div><div class="'.$row['icon'].'" id="icon_'.$row['MyTreeItem'].'"></div><span id="up_cap_'.$row['MyTreeItem'].'" class="'.$this->style.'_item_caption_'.$highlight.' '.$class_title.'">'.$final_title.'</span></li>';

					}
					//==================================================================
					
				}					
				//==================================================================
			}
			else
			{
				//==================================================================
				// Edit / update mode
				//==================================================================

				// full_update = true only if current tree language = root language reference in configuration
				if($this->language == $this->root_language )
				{
					// Full update available
					$full_update = true;
				}
				else 
				{
					$full_update = false;
				}
				
				//==================================================================
				// Browse all items of tree on a level
				//==================================================================
				$highlight = "none";
				while ($row = $result->fetch_array(MYSQLI_ASSOC))
				{
					if($row['icon'] == '')
					{
						$row['icon'] = $this->style.'_empty_icon';
					}
					//==================================================================
					// Read list of checkbox if any
					//==================================================================
					if($this->displaycheckbox)
					{
						// Ok, checkbox are visible, go on
						
						// Test inside mode
						if($this->auto_flag_tree) 
						{
							// Inside mode actif :
							// Read data from tree table
							if($row['mark'])
							{
								$displaycheckbox = "_mark";
							}
							else
							{
								$displaycheckbox = "_unmark";
							}
						}
						else
						{
							// Outide mode actif :
							// Read data mark from specific array listcheckbox
							if( array_key_exists($row['id'],$this->listcheckbox) )
							{
								$displaycheckbox = "_mark";
							}
							else
							{
								$displaycheckbox = "_unmark";
							}
						}
						
					}	
					//==================================================================
										
					//==================================================================
					// Read full item expansion
					//==================================================================
					if($this->fulllistexpand)
					{
						if ( in_array($row['id'],$this->fulllistexpand) && $expend_class == "collapsed")
						{
							$final_expend_class = "expanded";
						}
						else
						{
							$final_expend_class = $expend_class;
						}
					}
					else
					{
						$final_expend_class = $expend_class;
					}
					//==================================================================
	
					//==================================================================
					// Read item to highlight
					//==================================================================
					if(is_array($this->listhighlight))
					{
						if(array_key_exists($row['id'],$this->listhighlight))
						{
							$highlight = "highlight";
							$this->listhighlight[$row['id']] = true; // Keep order in php memory session
							$this->myfocus = $row['id']; // Last item focused
						}
						else
						{
							$highlight = "none";
						}
					}
					//==================================================================
					
					$dev_string = '<span style="float:left;">('.$row['id'].')&nbsp;</span>'; // TODO remove all devstring then no more needed
					
					//==================================================================
					// Local language for node title already exists or not ??
					//==================================================================
					$query2 = "	SELECT
							`MTC`.`title` AS 'value'
			   			FROM
			   				`".$this->tree_caption."` `MTC`
			   			WHERE 1 = 1
							AND `MTC`.`id` = '".$row['id']."'
							AND `MTC`.`application_release` = `MTC`.`application_release`
			   				AND `MTC`.`language` = '".$this->language."'
			   		";					
					$result2 = $this->link_mt->query($query2);
					$row2 = $result2->fetch_array(MYSQLI_ASSOC);

					if($row2['value'] == '')
					{
						$title = $row['value'];
						$class_title = $this->style.'_language_not_exists';
					}
					else
					{
						$title = $row2['value'];
						$class_title = '';
					}
					
					//==================================================================

					//==================================================================
					// Full update only if you are in default language !!
					//==================================================================
					if($full_update)
					{
						// YES
						$str_add_parent = '';
					}
					else
					{
						// NO, you are in different language than define in conf parameter number 1
						$str_add_parent = '';
					}
					//==================================================================

					//==================================================================
					// Build item node in tree
					//==================================================================

					//=========================
					$final_title = htmlentities($title,ENT_QUOTES,'UTF-8');
					// Only space in string
					if(trim($title) == '')
					{
						$final_title = str_replace(' ', '&nbsp;',$final_title);
					}
					if($this->use_bbcode)
					{
						$final_title = $this->convertBBCodetoHTML($final_title);
					}
					//=========================

					if ($this->get_any_child($row['id'])) 
					{	
						// A child exist for this id
						// Build Open structure for next level
						$this->html_result .= '<li tabindex="'.$this->offsettabindex.$row['id'].'" id="'.$row['MyTreeItem'].'" class="'.$final_expend_class.'"';

						// A custom function is defined for item selection ?
						if ($this->event_functionCall_on_click_item != null)
						{
							$this->html_result .= ' onClick="MT_selected_item(event,\''.$this->internal_id.'\',\''.$row['id'].'\',\'U\',\'\')"';
						}
						$this->html_result .= '><div class="'.$this->style.'_click_expand" id="TOG_'.$this->internal_id.$row['id'].'" onClick="toogle_nodes(\''.$this->internal_id.'\',\''.$row['MyTreeItem'].'\',\''.$row['id'].'\',\'1\')"></div><div class="'.$this->style.$displaycheckbox.'" OnClick="manage_mark(\''.$this->internal_id.'\',\''.$row['id'].'\')" style="cursor: pointer;" id="check'.$row['MyTreeItem'].'"></div>'.$dev_string.'<div class="'.$row['icon'].'" style="cursor: pointer;" OnClick="item_icon_update(\''.$this->internal_id.'\',\''.$row['id'].'\',\''.$this->style.'\')" id="icon_'.$row['MyTreeItem'].'"></div><span id="up_cap_'.$row['MyTreeItem'].'" ';

						if($full_update)
						{
							$this->html_result .= 'onmouseup = "mouse_up_item()" onmousedown="mouse_down_item(\''.$this->internal_id.'\',\''.$row['id'].'\')" ';
						}
						$this->html_result .= 'onmouseout="out_of_item(\''.$this->internal_id.'\')" OnClick="item_update(\''.$this->internal_id.'\',\''.$row['id'].'\',\''.$this->style.'\',\''.$full_update.'\')" class="'.$this->style.'_item_caption_'.$highlight.' '.$class_title.'">'.$final_title.'</span><span id="'.$row['MyTreeItem'].'_origin" style="display:none;">'.htmlentities($title,ENT_QUOTES).'</span>';
						
						//==================================================================
						// Recurif Call, Deeper in tree
						//==================================================================
						$this->scan_level($row['id'], true);
						//==================================================================
						
						if($full_update)
						{
							// Only in full update mode
							$this->html_result .= '<div class="'.$this->style.'_interow" id="'.$this->internal_id.'INT_'.$row['id'].'" OnClick="add_new_node(\''.$this->internal_id.'\',\''.$row['id'].'\')"></div>';
						}
						// Close the next level
						$this->html_result .= '</li>';
					}
					else 
					{ 	
						// No more child
						// Just build an simple item
						$this->html_result .= '<li tabindex="'.$this->offsettabindex.$row['id'].'" id="'.$row['MyTreeItem'].'"';

						// A custom function is defined for item selection ?
						if ($this->event_functionCall_on_click_item != null)
						{
							$this->html_result .= ' onClick="MT_selected_item(event,\''.$this->internal_id.'\',\''.$row['id'].'\',\'U\',\'\')"';
						}
						$this->html_result .= '><div class="'.$this->style.$displaycheckbox.'" OnClick="manage_mark(\''.$this->internal_id.'\',\''.$row['id'].'\')" style="cursor: pointer;" id="check'.$row['MyTreeItem'].'"></div>'.$dev_string.'<div class="'.$row['icon'].'" style="cursor: pointer;" OnClick="item_icon_update(\''.$this->internal_id.'\',\''.$row['id'].'\',\''.$this->style.'\')" id="icon_'.$row['MyTreeItem'].'"></div><span id="up_cap_'.$row['MyTreeItem'].'" ';
						
						if($full_update)
						{
							$this->html_result .= 'onmouseup = "mouse_up_item()" onmousedown="mouse_down_item(\''.$this->internal_id.'\',\''.$row['id'].'\')"';
						}
						$this->html_result .= ' onmouseout="out_of_item(\''.$this->internal_id.'\')" OnClick="item_update(\''.$this->internal_id.'\',\''.$row['id'].'\',\''.$this->style.'\',\''.$full_update.'\')" class="'.$this->style.'_item_caption_'.$highlight.' '.$class_title.'">'.$final_title.'</span><span id="'.$row['MyTreeItem'].'_origin" style="display:none;">'.htmlentities($title,ENT_QUOTES).'</span>';
						if($full_update)
						{
							$this->html_result .= '<div class="'.$this->style.'_interow" id="'.$this->internal_id.'INT_'.$row['id'].'" OnClick="add_new_node(\''.$this->internal_id.'\',\''.$row['id'].'\')"></div>';
						}
						$this->html_result .= '</li>';
					}
					//==================================================================
					
				}					
				//==================================================================				
			}
			
			if ($is_parent)
			{	
				// End bloc for all children of $id's parent
				$this->html_result .= '</ul>';
			}
			
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * Protect special MySQL digit
		 * @txt : Text content to protect
		 * @return : $txt
		 ====================================================================*/	
		private function mysql_protect($txt) 
		{
			$txt = $this->link_mt->real_escape_string($txt);
			return $txt;
		}
		/*===================================================================*/		

		
		/**==================================================================
		 * Protect special MySQL digit
		 * @txt : Text content to protect
		 * @return : $txt
		 ====================================================================*/	
		private function javascript_protect($txt) 
		{
			$txt = str_replace("'","\'",$txt);
			return $txt;
		}
		/*===================================================================*/	

		
		/**==================================================================
		 * Convert BBCode to HTML
		 * @$p_text : Input string to convert in HTML
		 ====================================================================*/	
		public function convertBBCodetoHTML($p_text)
		{
            //$p_text = preg_replace('`\[EMAIL\]([^\[]*)\[/EMAIL\]`i','<a href="mailto:\\1">\\1</a>',$p_text);
            //$p_text = preg_replace('`\[EMAIL\](.*?)\[/EMAIL\]`i','<a href="mailto:\\1">\\1</a>',$p_text);
            // Information : Double [/email][/email] to avoid end of email area
            $p_text = preg_replace('`\[email\](.*?(\[/email\]\[/email\].*?)*)\[/email\](?!(\[/email\]))`ie','"<a href=\"mailto:".str_replace("[/email][/email]","[/email]","\\1")."\">".str_replace("[/email][/email]","[/email]","\\1")."</a>"',$p_text);

            //$p_text = preg_replace('`\[b\]([^\[]*)\[/b\]`i','<b>\\1</b>',$p_text);    // Generation 1 : First [ or ] encounter stop translation
            //$p_text = preg_replace('`\[b\](.*?)\[/b\]`i','<b>\\1</b>',$p_text);       // Generation 2 : Can't bold string [/b]
            // Generation 3 : Information : Double [/b][/b] to avoid end of bold area
            $p_text = preg_replace('`\[b\](.*?(\[/b\]\[/b\].*?)*)\[/b\](?!(\[/b\]))`ie','"<b>".str_replace("[/b][/b]","[/b]","\\1")."</b>"',$p_text);

            //$p_text = preg_replace('`\[i\]([^\[]*)\[/i\]`i','<i>\\1</i>',$p_text);
            //$p_text = preg_replace('`\[i\](.*?)\[/i\]`i','<i>\\1</i>',$p_text);
            // Information : Double [/i][/i] to avoid end of italic area
            $p_text = preg_replace('`\[i\](.*?(\[/i\]\[/i\].*?)*)\[/i\](?!(\[/i\]))`ie','"<i>".str_replace("[/i][/i]","[/i]","\\1")."</i>"',$p_text);

            //$p_text = preg_replace('`\[u\]([^\[]*)\[/u\]`i','<u>\\1</u>',$p_text);
            //$p_text = preg_replace('`\[u\](.*?)\[/u\]`i','<u>\\1</u>',$p_text);
            // Information : Double [/u][/u] to avoid end of underline area
            $p_text = preg_replace('`\[u\](.*?(\[/u\]\[/u\].*?)*)\[/u\](?!(\[/u\]))`ie','"<u>".str_replace("[/u][/u]","[/u]","\\1")."</u>"',$p_text);

            //$p_text = preg_replace('`\[s\]([^\[]*)\[/s\]`i','<s>\\1</s>',$p_text);
            //$p_text = preg_replace('`\[s\](.*?)\[/s\]`i','<s>\\1</s>',$p_text);
            // Information : Double [/s][/s] to avoid end of stroke line area
            $p_text = preg_replace('`\[s\](.*?(\[/s\]\[/s\].*?)*)\[/s\](?!(\[/s\]))`ie','"<s>".str_replace("[/s][/s]","[/s]","\\1")."</s>"',$p_text);

            //$p_text = preg_replace('`\[center\]([^\[]*)\[/center\]`','<p style="text-align: center;">\\1</p>',$p_text);
            //$p_text = preg_replace('`\[center\](.*?)\[/center\]`','<p style="text-align: center;">\\1</p>',$p_text);
            // Information : Double [/center][/center] to avoid end of center line area
            $p_text = preg_replace('`\[center\](.*?(\[/center\]\[/center\].*?)*)\[/center\](?!(\[/center\]))`ie','"<p style=\"text-align: center;\">".str_replace("[/center][/center]","[/center]","\\1")."</p>"',$p_text);

            //$p_text = preg_replace('`\[left\]([^\[]*)\[/left\]`i','<p style="text-align: left;">\\1</p>',$p_text);
            //$p_text = preg_replace('`\[left\](.*?)\[/left\]`i','<p style="text-align: left;">\\1</p>',$p_text);
            // Information : Double [/left][/left] to avoid end of left line area
            $p_text = preg_replace('`\[left\](.*?(\[/left\]\[/left\].*?)*)\[/left\](?!(\[/left\]))`ie','"<p style=\"text-align: left;\">".str_replace("[/left][/left]","[/left]","\\1")."</p>"',$p_text);

            //$p_text = preg_replace('`\[right\]([^\[]*)\[/right\]`i','<p style="text-align: right;">\\1</p>',$p_text);
            //$p_text = preg_replace('`\[right\](.*?)\[/right\]`i','<p style="text-align: right;">\\1</p>',$p_text);
            // Information : Double [/right][/right] to avoid end of right line area
            $p_text = preg_replace('`\[right\](.*?(\[/right\]\[/right\].*?)*)\[/right\](?!(\[/right\]))`ie','"<p style=\"text-align: right;\">".str_replace("[/right][/right]","[/right]","\\1")."</p>"',$p_text);

            //$p_text = preg_replace('`\[img\]([^\[]*)\[/img\]`i','<img src="\\1" />',$p_text);
            //$p_text = preg_replace('`\[img\](.*?)\[/img\]`i','<img src="\\1" />',$p_text);
            // Information : Double [/img][/img] to avoid end of picture area
            $p_text = preg_replace('`\[img\](.*?(\[/img\]\[/img\].*?)*)\[/img\](?!(\[/img\]))`ie','"<img src=\"".str_replace("[/img][/img]","[/img]","\\1")."\" />"',$p_text);

            //$p_text = preg_replace('`\[color=([^[]*)\]([^[]*)\[/color\]`i','<font color="\\1">\\2</font>',$p_text);
            //$p_text = preg_replace('`\[color=(.*?)\](.*?)\[/color\]`i','<font color="\\1">\\2</font>',$p_text);
            // Information : Double [/color][/color] to avoid end of color area
            $p_text = preg_replace('`\[color=(.*?)\](.*?(\[/color\]\[/color\].*?)*)\[/color\](?!(\[/color\]))`ie','"<font color=\"\\1\">".str_replace("[/color][/color]","[/color]","\\2")."</font>"',$p_text);

            //$p_text = preg_replace('`\[bg=([^[]*)\]([^[]*)\[/bg\]`i','<font style="background-color: \\1;">\\2</font>',$p_text);
            //$p_text = preg_replace('`\[bg=(.*?)\](.*?)\[/bg\]`i','<font style="background-color: \\1;">\\2</font>',$p_text);
            // Information : Double [/bg][/bg] to avoid end of background color area
            $p_text = preg_replace('`\[bg=(.*?)\](.*?(\[/bg\]\[/bg\].*?)*)\[/bg\](?!(\[/bg\]))`ie','"<font style=\"background-color:\\1;\">".str_replace("[/bg][/bg]","[/bg]","\\2")."</font>"',$p_text);

            //$p_text = preg_replace('`\[size=([^[]*)\]([^[]*)\[/size\]`i','<font size="\\1">\\2</font>',$p_text);
            //$p_text = preg_replace('`\[size=(.*?)\](.*?)\[/size\]`i','<font size="\\1">\\2</font>',$p_text);
            // Information : Double [/size][/size] to avoid end of font size area
            $p_text = preg_replace('`\[size=(.*?)\](.*?(\[/size\]\[/size\].*?)*)\[/size\](?!(\[/size\]))`ie','"<font size=\"\\1\">".str_replace("[/size][/size]","[/size]","\\2")."</font>"',$p_text);

            //$p_text = preg_replace('`\[font=([^[]*)\]([^[]*)\[/font\]`i','<font face="\\1">\\2</font>',$p_text);
            //$p_text = preg_replace('`\[font=(.*?)\](.*?)\[/font\]`i','<font face="\\1">\\2</font>',$p_text);
            // Information : Double [/font][/font] to avoid end of font area
            $p_text = preg_replace('`\[font=(.*?)\](.*?(\[/font\]\[/font\].*?)*)\[/font\](?!(\[/font\]))`ie','"<font face=\"\\1\">".str_replace("[/font][/font]","[/font]","\\2")."</font>"',$p_text);

            // Information : Double [/url][/url] to avoid end of URL area
            $p_text = preg_replace('`\[url\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`ie','"<a target=\"_blank\" href=\"".str_replace("[/url][/url]","[/url]","\\1")."\">".str_replace("[/url][/url]","[/url]","\\1")."</a>"',$p_text);
            $p_text = preg_replace('`\[url=(.*?)\](.*?(\[/url\]\[/url\].*?)*)\[/url\](?!(\[/url\]))`ie','"<a target=\"_blank\" href=\"\\1\">".str_replace("[/url][/url]","[/url]","\\2")."</a>"',$p_text);

            // Found a randomized string do not exists in string to convert
            $temp_str = '7634253332';while(stristr($p_text,$temp_str)){$temp_str = mt_rand();}
            $p_text = str_replace('[br][br]',$temp_str,$p_text);
            $p_text = preg_replace('`(?<!\[br\])\[br\](?!(\[br\]))`ie','str_replace("[br]","<br>","\\0")',$p_text);
            $p_text = str_replace($temp_str,'[br]',$p_text);

            // Found a randomized string do not exists in string to convert
            $temp_str = '7634253332';while(stristr($p_text,$temp_str)){$temp_str = mt_rand();}
            $p_text = str_replace('[hr][hr]',$temp_str,$p_text);
            $p_text = preg_replace('`(?<!\[hr\])\[hr\](?!(\[hr\]))`ie','str_replace("[hr]","<hr>","\\0")',$p_text);
            $p_text = str_replace($temp_str,'[hr]',$p_text);

            return $p_text;
		}
		/*===================================================================*/	
	}