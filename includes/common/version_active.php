<?php
	/**==================================================================
	* extract software version from path name define in your virtual host
	====================================================================*/

	if (strlen($_SERVER["DOCUMENT_ROOT"]) != 0)
	{
		preg_match_all("#[\\\\|/]([a-z0-9.]*)#i",$_SERVER["DOCUMENT_ROOT"],$w_output);

		if(strlen($w_output[1][count($w_output[1])-1]) == 0)
		{
			$version_soft = $w_output[1][count($w_output[1])-2];
		}
		else
		{
			$version_soft = $w_output[1][count($w_output[1])-1];
		}
	}
	else
	{
		// PHP command line used. No cookie, just skip
		$version_soft = '';
	}