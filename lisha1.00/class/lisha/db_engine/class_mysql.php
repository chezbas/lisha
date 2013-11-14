<?php
	class mysql_engine
	{
		private $c_ident;
		private $c_resultat;
		public $link;
		public $link_lisha;
		private $c_sql;


		/**==================================================================
		* Constructor of MySQL engine
		====================================================================*/
		function __construct($p_ident)
		{
			$this->c_ident = $p_ident;
		}
		/**===================================================================*/


		/**==================================================================
		* Main database connexion schema
		====================================================================*/
		public function db_connect()
		{
			$this->link = new mysqli($this->c_ident['host'],$this->c_ident['user'],$this->c_ident['password'],$this->c_ident['schema']);
			if ($this->link->connect_errno)
			{
				error_log("Database connexion error : ".$this->link->connect_error);
				die();
			}
			$this->link->set_charset("utf8"); // Usefull for php 5.4
		}
		/**===================================================================*/


		/**==================================================================
		* OverLoad of SQL execution
		====================================================================*/
		public function exec_sql($sql,$line,$file,$function,$class,$link)
		{
			$this->c_sql = $sql;
			// !!! Caution : Return $this->resultat and not direct result of $link->query
			$this->c_resultat = $link->query($sql) or $this->die_sql($sql,$line,$file,$function,$class);

			return $this->c_resultat;
		}
		/**===================================================================*/


		/**==================================================================
		 * Catch query error
		 *
		 * Display HTML message with query that rise an error and write into error_log file
		 ====================================================================*/	
		private function die_sql($sql,$line,$file,$function,$class)
		{
			error_log($file.' : L '.$line.chr(10).$sql.chr(10).$this->link->errno);

			$err = '<span style="font-size:18px;font-weight:bold;">lisha</span><br /><br />';
			$err .= 'MySQL Error<br /><br />';
			$err .= '<b style="color:#DD2233;">Error '.$this->link->errno."</b> : <b>".$this->link->errno.'</b><br /><br />';
			$err .= 'Classe : '.$class.' - Ligne : '.$line.' - '.$file.' - '.$function.'<br /><br /><br />';
			$err .= '<textarea style="width:90%;height:200px;;">'.$sql.'</textarea>';

			echo $err;
			die();
		}
		/**===================================================================*/


		/**==================================================================
		 * Return number of rows from your last query
		 ====================================================================*/	
		public function rds_num_rows(&$resultat)
		{
			return $resultat->num_rows;
		}
		/**===================================================================*/


		/**==================================================================
		 * Return number of column in query
		 ====================================================================*/	
		public function rds_num_fields(&$resultat)
		{
			return $resultat->field_count;
		}
		/**===================================================================*/


		/**==================================================================
		 * Adjusts the result pointer to an arbitrary row in the result
		 ====================================================================*/	
		public function rds_data_seek(&$resultat,$row_number)
		{
			$resultat->data_seek($row_number);
		}
		/**===================================================================*/


		/**==================================================================
		 * Fetch a result row as an associative, a numeric array, or both
		 ====================================================================*/	
		public function rds_fetch_array(&$result)
		{
			return $result->fetch_array(MYSQLI_ASSOC);
		}
		/**===================================================================*/


		/**==================================================================
		 * Get result data
		 ====================================================================*/	
		public function rds_result(&$result,$row,$field)
		{
			// Move to target row
			$result->data_seek($row);

			// Get field information
			$field =$result->fetch_field();

			return $field->name;
		}
		/**===================================================================*/


		/**==================================================================
		 * Escapes special characters in a string for use in an SQL statement
		 ====================================================================*/	
		public function protect_sql($txt,&$link)
		{
			return $link->real_escape_string($txt);
		}
		/**===================================================================*/


		/**==================================================================
		 * Generic like
		 ====================================================================*/	
		public function get_like($txt)
		{
			return 'LIKE "'.$txt.'"';
		}
		/**===================================================================*/


		/**==================================================================
		 * Generic limit
		 ====================================================================*/	
		public function get_limit($offset,$rowcount)
		{
			return 'LIMIT '.$offset.','.$rowcount;
		}
		/**===================================================================*/


		/**==================================================================
		 * Generic field protection
		 ====================================================================*/	
		public function get_quote_col($col)
		{
			return '`'.$col.'`';
		}
		/**===================================================================*/


		/**==================================================================
		 * Generic quote
		 ====================================================================*/	
		public function get_quote_string($string)
		{
			return '"'.$string.'"';
		}
		/**===================================================================*/


		/**==================================================================
		 * Do a date conversion using database operator
		 * Have a look on his reverse function called get_str_to_date_format
		 *
		 * @column : date value
		 * @format : format date
		====================================================================*/
		public function get_date_format($column, $format)
		{
			$str_before = "DATE_FORMAT(";
			$str_after = ",'".$format."')";
			$string_final = $str_before.$column.$str_after;

			return $string_final;
		}
		/**===================================================================*/


		/**==================================================================
		 * Do a string to date conversion using database operator
		 * Have a look on his reverse function called get_date_format
		 *
		 * @column : column date
		 * @format : format date
		====================================================================*/
		public function get_str_to_date_format($column, $format)
		{
			$str_before = "STR_TO_DATE('";
			$str_after = "','".$format."')";
			$string_final = $str_before.$column.$str_after;

			return $string_final;
		}
		/**===================================================================*/


		/**==================================================================
		 * Do a replace using database operator
		 *
		 * @column : column date
		 * @search_value : search value to replace
		 * @new_value : replacement value
		====================================================================*/
		public function get_replace($column, $search_value, $new_value)
		{
			$str_before = "REPLACE(";
			$str_after = ",'".$search_value."','".$new_value."')";
			$string_final = $str_before.$column.$str_after;

			return $string_final;
		}
		/**===================================================================*/
	}