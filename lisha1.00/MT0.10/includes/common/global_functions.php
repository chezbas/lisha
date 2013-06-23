<?php
	/**==================================================================
	 * Write error with call file location in error_log file ( see your php.ini configuration for further details )
	 * parameters details :
	 * $type
	 * 	info :	By default, juste write text in log and continue
	 * 	fatal : Write error and die
	 * 
	 * $label : text to write in log
	 ====================================================================*/	
	function error_log_details($type = "info",$label = "no label") 
	{
		$w_call_detail = __FILE__." line (".__LINE__.") call from (".$_SERVER["SCRIPT_FILENAME"].") : ";
		switch($type) 
		{
			case "fatal":
				$w_prefixe = "Fatal error on ".$w_call_detail;
				error_log($w_prefixe.$label);
				die();
				break;
			case "info":
				$w_prefixe = "Info : ".$w_call_detail;
				error_log($w_prefixe.$label);
				break;
			default:
				$w_prefixe = "STOP : ".$w_call_detail;
				error_log($w_prefixe."!!!! unknown type parameters call !!!!!!");
				die();
				break;
		}
	}
	/*===================================================================*/	
	

	/**==================================================================
	 * convert to heure minute second
	 * &duree : unit second
	 ====================================================================*/	
	function hms($duree = 0) 
	{
		$heures = round($duree / 3600);
		$minutes = round(($duree - $heures * 3600) / 60);
		$secondes = round(($duree - $heures * 3600 - $minutes * 60) / 60);

		return str_pad($heures, 2, "0", STR_PAD_LEFT).':'.str_pad($minutes, 2, "0", STR_PAD_LEFT).':'.str_pad($secondes, 2, "0", STR_PAD_LEFT);
	}
	/*===================================================================*/	

	
	/**==================================================================
	 * Recover all URL parameters except key items in @param_exclure
	 * @geturl : means $_GET
	 * @param_exclure : array : Set of parameters to ignore
	 * 					eg : $aram_exclure = array(ssid); 
	 * @return : string that you can put on end url called to keep context 
	 ====================================================================*/	
	function url_get_exclusion($geturl,$param_exclure = array()) 
	{
		$w_url_param = '?';
		foreach ($_GET as $key => $value)
		{
			if(!in_array($key,$param_exclure))
			{
				$w_url_param .= '&'.$key.'='.$value;
			}
		}

		return $w_url_param;
	}
	/*===================================================================*/	

	
	/**==================================================================
	 * Protect javascript  simple quot call function
	 * @txt : Text content to protect
	 * @return : $txt
	 ====================================================================*/	
	function js_protect($txt) 
	{
		$txt = str_replace("'","\'",$txt);
		return $txt;
	}
	/*===================================================================*/	
?>