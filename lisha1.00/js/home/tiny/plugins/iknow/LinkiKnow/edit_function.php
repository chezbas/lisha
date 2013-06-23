<?php
	define("__IFICHE__","__IFICHE__");
	define("__ICODE__","__ICODE__");
	define("__EXT__","__EXT__");
	
	function get_object()
	{
		if(stristr($_GET['url'],'ifiche.php') != false)
		{
			// iFiche
			$_POST['p_type'] = __IFICHE__;
			return __IFICHE__;
		}
		else
		{
			if(stristr($_GET['url'],'icode.php') != false)
			{
				// iCode
				$_POST['p_type'] = __ICODE__;
				return __ICODE__;
			}
			else
			{
				// Le lien ne pointe pas sur une iFiche ou un iCode
				$_POST['p_type'] = __EXT__;
				return __EXT__;
			}
		}
	}
	
	function get_object_id()
	{
		global $object_type;
		
		switch($object_type) 
		{
			case __IFICHE__:
				$motif_id = '#ifiche.php\?(.*)?ID=([0-9]+)#i';
				break;
			case __ICODE__:
				$motif_id = '#icode.php\?(.*)?ID=([0-9]+)#i';
				break;
		}
		
		preg_match_all($motif_id,$_GET['url'],$out);

		$_POST['object_id'] = $out[2][0];
		return $out[2][0];
	}
	
	
	/**==================================================================
	 * Return iObject version 
	 * $w_last_label : Use main text $_SESSION[$ssid]['message']['iknow'][504]
	 ====================================================================*/
	function get_object_version($w_last_label = '???')
	{
		global $object_type;
		
		switch($object_type) 
		{
			case __IFICHE__:
				$motif_version = '#ifiche.php\?(.*)?version=([0-9]+)#i';
				break;
			case __ICODE__:
				$motif_version = '#icode.php\?(.*)?version=([0-9]+)#i';
				break;
		}
		
		preg_match_all($motif_version,$_GET['url'],$out);

		if(isset($out[2][0]))
		{
			// A version was defined into the url 
			$_POST['object_version'] = $out[2][0];
			return $out[2][0];
		}
		else
		{
			// No version defined into the url
			$_POST['object_version'] = $w_last_label;
			return $w_last_label;
		}
	}
	
	function get_object_ik_cartridge()
	{
		global $object_ik_cartridge;
		
		$motif = '#&IK_CARTRIDGE=([0-9]+)#i';
		
		preg_match_all($motif,$_GET['url'],$out);

		if(isset($out[1][0]))
		{
			// IK_CARTRIDGE was defined into the url 
			return $out[1][0];
		}
		else
		{
			// IK_CARTRIDGE wasn't defined into the url
			return false;
		}
	}
	
	function get_object_ik_valmod()
	{
		global $object_ik_valmod;
		
		$motif = '#&IK_VALMOD=([0-9]+)#i';
		
		preg_match_all($motif,$_GET['url'],$out);

		if(isset($out[1][0]))
		{
			// IK_VALMOD was defined into the url 
			return $out[1][0];
		}
		else
		{
			// IK_VALMOD wasn't defined into the url
			return false;
		}
	}
	
	
	function get_url_params()
	{
		global $ssid;
		
		$j = 0;
		$url = str_replace('&amp;','&',$_GET['url']);
		$variables = explode('&',$url);
		$param_value = null;
		$param_name = null;
		
		// Browse the params of the url (expect ID and version)
		for($i = 1,$max_count=sizeof($variables); $i < $max_count; $i++)
		{
			$param = explode('=',$variables[$i]);
			
			if($param[0] != 'ID' && $param[0] != 'version' && $param[0] != 'tab-level' && $param[0] != 'IK_CARTRIDGE' && $param[0] != 'IK_VALMOD')
			{
				$param_name[$j] = $param[0];
				if(isset($param[1])){
					$param_value[$j] = $param[1];	
				}
				else
				{
					$param_value[$j] = null;
				}
				$j++;
			}
		}
		$j = 0;
        $link = mysql_connect($_SESSION['iknow'][$ssid]['serveur_bdd'],$_SESSION['iknow'][$ssid]['user_iknow'],$_SESSION['iknow'][$ssid]['password_iknow']);
        mysql_select_db($_SESSION['iknow'][$ssid]['schema_iknow']);		
		if(isset($param_name))
		{
			foreach ($param_name as $value)
			{
       			$sql = 'INSERT INTO `'.$_SESSION['iknow'][$ssid]['struct']['tb_url_temp']['name'] .'`(id_temp,Nom,Valeur) VALUES ("'.$_SESSION[$_GET['ssid']]['id_temp'].'","'.addslashes(urldecode($value)).'","'.addslashes(urldecode($param_value[$j])).'")';
       			mysql_query($sql,$link) or die(mysql_error());      					
       			$j++;
			}
		}	
	}
	
	
	function get_tab_level()
	{
		$motif_tablevel = '#.php\?(.*)?tab-level=([0-9]+_*[0-9]*_*[0-9]*)#i';
		
		preg_match_all($motif_tablevel,$_GET['url'],$out);
		
		if(isset($out[2][0]))
		{
			return $out[2][0];
		}
		else
		{
			return false;
		}
	}
	
?>