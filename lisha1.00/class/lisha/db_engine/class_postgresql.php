<?php
	
	class postgresql_engine
	{
		private $c_ident;
		private $c_resultat;
		public $link;
		public $link_lisha;
		
		
		function __construct($p_ident)
		{
			$this->c_ident = $p_ident;
		}

		/**==================================================================
		 * Connect to the database
		 ====================================================================*/
		public function db_connect()
		{
			/**==================================================================
			 * Connect to the user databsae
			 ====================================================================*/		
			$this->link = pg_connect("host=".$this->c_ident['host']." dbname=".$this->c_ident['schema']." user=".$this->c_ident['user']." password=".$this->c_ident['password']);
			/*===================================================================*/	
		
			/**==================================================================
			 * Connect to the lisha database
			 ====================================================================*/
			//$this->link = pg_connect("host=".$this->c_ident['host']." dbname=lisha user=".$this->c_ident['user']." password=".$this->c_ident['password']);
			/*$this->link_lisha = mysql_connect('localhost','lisha','lisha');
			mysql_select_db('lisha',$this->link_lisha) or die('dbconn: mysql_select_db: ' + mysql_error());*/
			/*===================================================================*/
		}
		
		public function exec_sql($sql,$line,$file,$function,$class,$link)
		{
			$this->c_resultat = pg_query($sql) or die(chr(10).chr(10).chr(10).$sql.'    '.$file.' l'.$line);
			return $this->c_resultat;
		}
			
		/**==================================================================
		 * Methods
		 ====================================================================*/	
		public function rds_num_rows(&$resultat)
		{
			return pg_num_rows($resultat);
		}
		
		public function rds_num_fields(&$resultat)
		{
			return pg_num_fields($resultat);
		}
		
		public function rds_data_seek(&$resultat,$row_number)
		{
			pg_result_seek($resultat,$row_number);
		}

		public function rds_fetch_array(&$result)
		{
			return pg_fetch_array($result,null,PGSQL_ASSOC);
		}
		
		public function rds_result(&$result,$row,$field)
		{
			return pg_fetch_result($result,$row,$field);
		}	
		
		public function protect_sql($txt,&$link)
		{
			return pg_escape_string($link,$txt);
		}
		/**==================================================================
		 * Keyword
		 ====================================================================*/	
		public function get_like($txt)
		{
			return "ILIKE '".$txt."'";
		}
		
		public function get_limit($offset,$rowcount)
		{
			return 'LIMIT '.$rowcount.' OFFSET '.$offset;
		}	
		
		public function get_quote_col($col)
		{
			return '"'.$col.'"';
		}
		
		public function get_quote_string($string)
		{
			return '"'.$string.'"';
		}

		/**==================================================================
		 * Date Format
		 * @column : column date
		 * @format : format date
		====================================================================*/
		public function get_date_format($column, $format)
		{
			$str_before = "to_char(";
			$str_after = ",'".$format."')";
			$string_final = $str_before.$column.$str_after;
			return $string_final;
		}
		/*===================================================================*/

		/**==================================================================
		 * String to Date Format
		 * @column : column date
		 * @format : format date
		====================================================================*/
		public function get_str_to_date_format($column, $format)
		{
			$str_before = "to_date('";
			$str_after = "','".$format."')";
			$string_final = $str_before.$column.$str_after;
			return $string_final;
		}
		/*===================================================================*/

		/**==================================================================
		 * String to Date Format
		 * @column : column date
		 * @search_value : search value to replace
		 * @new_value : replacement value
		====================================================================*/
		public function get_replace($column, $search_value, $new_value)
		{
			$str_before = "replace(";
			$str_after = ",'".$search_value."','".$new_value."')";
			$string_final = $str_before.$column.$str_after;
			return $string_final;
		}
		/*===================================================================*/
	}

?>