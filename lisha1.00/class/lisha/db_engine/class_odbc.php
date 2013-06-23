<?php
	
	class odbc_engine
	{
		private $c_ident;
		private $c_resultat;
		public $link;
		public $link_lisha;
		private $c_sql;
		
		
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
			$this->link = odbc_connect($this->c_ident['host'],$this->c_ident['user'],$this->c_ident['password']);
			/*===================================================================*/	
		}
		
		public function exec_sql($sql,$line,$file,$function,$class,$link)
		{
			return odbc_exec($link,"SET NAMES 'utf8'");
			$this->c_sql = $sql;
			// !!! Attention bien laisser $this->resultat, ne pas retourner directement mysql_query cela ne fonctionne pas !!!
			// $this->c_resultat = mysql_query($sql,$link) or die(mysql_error().'   '.$sql);//or $this->die_sql($sql,$line,$file,$function,$class,$link,round(microtime(true) - $start_hour,6));
			$this->c_resultat = odbc_exec($link,$sql) or $this->die_sql($sql,$line,$file,$function,$class);
			return $this->c_resultat;
		}
		
		
		/**==================================================================
		 * Methods
		 ====================================================================*/	
		private function die_sql($sql,$line,$file,$function,$class)
		{
			error_log($file.' : L '.$line.chr(10).$sql.chr(10).odbc_errormsg($this->link));
			$err = '<span style="font-size:18px;font-weight:bold;">lisha</span><br /><br />';
			$err .= 'Erreur MySQL<br /><br />';
			$err .= '<b style="color:#DD2233;">Erreur '.odbc_error($this->link)."</b> : <b>".odbc_errormsg($this->link).'</b><br /><br />';
			$err .= 'Classe : '.$class.' - Ligne : '.$line.' - '.$file.' - '.$function.'<br /><br /><br />';
			$err .= '<textarea style="width:90%;height:200px;;">'.$sql.'</textarea>';
			
			echo $err;
			die();
		}
		
		
		public function rds_num_rows(&$resultat)
		{
			return odbc_num_rows($resultat);
		}

		public function rds_num_fields(&$resultat)
		{
			return odbc_num_fields($resultat);
		}
		
		public function rds_data_seek(&$resultat,$row_number)
		{
			//mysql_data_seek($resultat,$row_number);
		}
		
		public function rds_fetch_array(&$result)
		{
			//	return odbc_fetch_array($result);
			return odbc_next_result($result);
			/*$i=0;
			while($ligne = odbc_fetch_row($result))
			{
				$i++;	
			}
			
			if($i == 0)return -1;
			else return $i;*/	
		}
		
	
			
		public function rds_result(&$result,$row,$field)
		{
			return odbc_result($result,$field);
		}	
		
		public function protect_sql($txt,&$link)
		{
			return addslashes($txt);
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