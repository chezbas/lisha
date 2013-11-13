<?php
class class_sgbd
{
	protected $link;
	protected $link_lisha;
	protected $resultat;
	protected $c_db_engine;
	private $c_obj_bdd;
	private $c_dir_obj;			// Directory of lisha object

	public function __construct($p_db_engine,$p_ident,$p_dir_obj)
	{
		$this->c_dir_obj = $p_dir_obj;
		$this->c_db_engine = $p_db_engine;

		switch($this->c_db_engine) 
		{
			case __MYSQL__:
				// MySQL engine
				$this->c_obj_bdd = new mysql_engine($p_ident);
				break;
			case __POSTGRESQL__:
				// PostgreSQL engine
				$this->c_obj_bdd = new postgresql_engine($p_ident);
				break;
			case __ODBC__:
				// ODBC engine
				$this->c_obj_bdd = new odbc_engine($p_ident);
				break;
		}
	}


	/**
	 * Connect to the database
	 */
	protected function db_connect()
	{
		$this->c_obj_bdd->db_connect();
		$this->link = &$this->c_obj_bdd->link;
		$this->link_lisha = &$this->c_obj_bdd->link_lisha;
	}

	public function exec_sql($sql,$line,$file,$function,$class,$link,$store_recordset = true)
	{
		if($store_recordset)
		{
			$this->resultat = $this->c_obj_bdd->exec_sql($sql,$line,$file,$function,$class,$link);
		}
		else
		{
			$result =  $this->c_obj_bdd->exec_sql($sql,$line,$file,$function,$class,$link);
			return $result;
		}
	}

	public function rds_num_rows(&$resultat)
	{
		return $this->c_obj_bdd->rds_num_rows($resultat);
	}

	public function rds_num_fields(&$resultat)
	{
		return $this->c_obj_bdd->rds_num_fields($resultat);
	}

	public function rds_fetch_array(&$result)
	{
		return $this->c_obj_bdd->rds_fetch_array($result);
	}

	public function rds_result(&$result,$row,$field)
	{
		return $this->c_obj_bdd->rds_result($result,$row,$field);
	}	

	public function rds_data_seek(&$resultat,$row_number)
	{
		return $this->c_obj_bdd->rds_data_seek($resultat,$row_number);
	}	

	public function protect_sql($txt,&$link)
	{
		return $this->c_obj_bdd->protect_sql($txt,$link);
	}
	/**==================================================================
	 * Keyword
	 ====================================================================*/	
	protected function get_like($txt)
	{
		return $this->c_obj_bdd->get_like($txt);
	}

	protected function get_limit($rowcount,$offset)
	{
		return $this->c_obj_bdd->get_limit($rowcount,$offset);
	}	

	protected function get_quote_col($col)
	{
		return $this->c_obj_bdd->get_quote_col($col);
	}

	protected function get_quote_string($string)
	{
		return $this->c_obj_bdd->get_quote_string($string);
	}

	protected function get_date_format($column, $format)
	{
		return $this->c_obj_bdd->get_date_format($column, $format);
	}

	protected function get_str_to_date_format($column, $format)
	{
		return $this->c_obj_bdd->get_str_to_date_format($column, $format);
	}

	protected function get_replace($column, $search_value, $new_value)
	{
		return $this->c_obj_bdd->get_replace($column, $search_value, $new_value);
	}
}