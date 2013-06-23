<?php
	
	class mssqlserver_engine
	{
		private $c_ident;
		private $c_resultat;
		public $link;
		public $link_lisha;
		
		
		function __construct($p_ident)
		{
			$this->c_ident = $p_ident;
		}
		
		/**
		 * Connect to the database
		 */
		public function db_connect()
		{
			/**==================================================================
			 * Connect to the user databsae
			 ====================================================================*/		
			$this->link = mysql_connect($this->c_ident['host'],$this->c_ident['user'],$this->c_ident['password']);
			mysql_select_db($this->c_ident['schema'],$this->link) or die('dbconn: mysql_select_db: ' + mysql_error());
			/*===================================================================*/	
		}
		
		public function exec_sql($sql,$line,$file,$function,$class,$link)
		{
			mysql_query("SET NAMES 'utf8'",$link);
			
			// !!! Attention bien laisser $this->resultat, ne pas retourner directement mysql_query cela ne fonctionne pas !!!
			// $this->c_resultat = mysql_query($sql,$link) or die(mysql_error().'   '.$sql);//or $this->die_sql($sql,$line,$file,$function,$class,$link,round(microtime(true) - $start_hour,6));
			$this->c_resultat = mysql_query($sql,$link) or $this->die_sql($sql,$line,$file,$function,$class);
			return $this->c_resultat;
		}
		
		
		/**==================================================================
		 * Methods
		 ====================================================================*/	
		private function die_sql($sql,$line,$file,$function,$class)
		{
			error_log($file.' : L '.$line.chr(10).$sql.chr(10).mysql_error($this->link));
			$err = 'Erreur MySQL<br />';
			$err .= '<b style="color:#DD2233;">Erreur '.mysql_errno($this->link)."</b> : <b>".mysql_error($this->link).'</b><br />';
			$err .= 'Classe : '.$class.' - Ligne : '.$line.' - '.$file.' - '.$function.'<br />';
			$err .= $sql;
			
			echo $err;
			die();
		}
		
		
		public function rds_num_rows(&$resultat)
		{
			return mssql_num_rows($resultat);
		}

		public function rds_num_fields(&$resultat)
		{
			return mysql_num_fields($resultat);
		}
		
		public function rds_data_seek(&$resultat,$row_number)
		{
			mysql_data_seek($resultat,$row_number);
		}
		
		public function rds_fetch_array(&$result)
		{
			return mysql_fetch_array($result,MYSQL_ASSOC);
		}
		
			
		public function rds_result(&$result,$row,$field)
		{
			return mysql_result($result, $row,$field);
		}	
		
		public function protect_sql($txt,&$link)
		{
			return mysql_real_escape_string($txt,$link);
		}
		/**==================================================================
		 * Keyword
		 ====================================================================*/	
		public function get_like($txt)
		{
			return 'LIKE "'.$txt.'"';
		}
		
		public function get_limit($offset,$rowcount)
		{
			return 'LIMIT '.$offset.','.$rowcount;
		}	
		
		public function get_quote_col($col)
		{
			return '`'.$col.'`';
		}
		
		public function get_quote_string($string)
		{
			return '"'.$string.'"';
		}
	}

?>