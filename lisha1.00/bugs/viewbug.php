<?php
	/**==================================================================
	 * Get/Set ssid window identifier
	 * Start unique php session with ssid name
	 ====================================================================*/
	require('../includes/common/ssid_session_start.php');
	/**===================================================================*/


	/**==================================================================
	 * Page buffering ( !! No output ( echo, print_r etc..) before this include !! )
	 ====================================================================*/
	require('../includes/common/buffering.php');
	/**===================================================================*/


	/**==================================================================
	 * Load global functions
	 ====================================================================*/	
	require('../includes/common/global_functions.php');
	/**===================================================================*/


	/**==================================================================
	 * Lisha configuration ( Need active php session and ssid definition )
	 ====================================================================*/
	require('../../includes/lishaSetup/main_configuration.php');
	/**===================================================================*/


	/**==================================================================
	 * Application release
	 * Page database connexion
	 * Load configuration parameters in session
	 ====================================================================*/	
	require('../includes/common/load_conf_session.php');
	/**===================================================================*/


	/**==================================================================
	 * Recover language from URL or Database
	 ====================================================================*/
	$path_root_lisha = '../';
	require('../includes/common/language.php');
	/**===================================================================*/

	//==================================================================
	// Load bug ID number
	//==================================================================
	if(!isset($_GET["ID"]))
	{
		error_log_details('fatal','No bug ID define. You have to provide one');
	}
	else
	{
		$page = $_GET["ID"];
	}
	//==================================================================

	$query = "SELECT
				BUG.`details` AS 'details',
				BUG.`solution` AS 'solution',
				TEXB.`text` AS 'business',
				BUG.`Type` AS 'Type',
				BUG.`description` AS 'description',
				BUG.`reference` AS 'reference',
				TEX.`text` AS 'ClassL',
				TEXS.`text` AS 'StatusL',
				CLAS.`symbol` AS 'flag',
				BUG.`Version` AS 'version'
			FROM 
				`bugsreports` BUG, `bugstexts` TEX, `bugstexts` TEXS, `bugstexts` TEXB, `bugsclass` CLAS
			WHERE 1 = 1
				AND BUG.`ID` = '".$page."'
				AND TEXS.`id` = BUG.`status`
				AND TEXS.`id_lang` = TEX.`id_lang`
				AND TEXB.`id` = BUG.`Business`
				AND TEXB.`id_lang` = TEX.`id_lang`
				AND CLAS.`id` = TEXS.`id`
				AND CLAS.`class` = 'status'
				AND TEX.`id` = BUG.`Classe`
				AND TEX.`id_lang` = '".$_SESSION[$ssid]['lisha']['langue']."'
				";

	$result = $link->query($query);

	$row = $result->fetch_array(MYSQLI_ASSOC);

	$texte = $row["details"];
	if(strlen($texte) == 0)
	{
		$no_details =  'style="display: none;"';
	}
	else
	{
		$no_details = '';
	}

	$classel = $row["ClassL"];
	$object = $row["Type"];
	$statusl = $row["StatusL"];
	$version = $row["version"];
	$flag = "<img src='".$row["flag"]."'>";
	$description = $row["description"];
	$solution = $row["solution"];
	$business = $row["business"];
	if(strlen($solution) == 0)
	{
		$no_solution =  'style="display: none;"';
	}
	else
	{
		$no_solution = '';
	}
	$reference = $row["reference"];

	// Recover URL parameters except some index
	$param = url_get_exclusion($_GET,array('lng'));

	/**==================================================================
	 * HTML declare page interpretation directive
	 ====================================================================*/	
	require('../includes/common/html_doctype.php');
	/**===================================================================*/
?>
<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="css/viewbug.css" type="text/css"> <!-- * load custom page style * -->

	<script type="text/javascript">
		var ssid = '<?php echo $ssid; ?>';
	</script>
		<?php 
			//==================================================================
			// Load text in both php session and javascript
			//==================================================================
			$type_screen = 'bug';
			require('../includes/common/textes.php');
			echo chr(10);
			/*===================================================================*/
		?>	
	<title><?php echo str_replace('$id',$page,$_SESSION[$ssid]['lisha']['page_text'][27]['TX']); ?> </title>
	</head>
	<body>
	<div class="screen_main"><?php echo str_replace('$id',$page,$_SESSION[$ssid]['lisha']['page_text'][27]['TX'])." - <a href='editbug.php?".$param."'>".$_SESSION[$ssid]['lisha']['page_text'][28]['TX'];?></a></div>
	<div class="separatorv"></div>

	<div class="business_main">
			<div class="business_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][16]['TX']." : "; ?></div>
			<div class="title"><?php echo $business; ?></div>
	</div>

	<div class="title_main">
			<div class="title_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][7]['TX']." : "; ?></div>
			<div class="title"><?php echo $description; ?></div>
	</div>

	<div class="object_main">
		<div class="object_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][3]['TX']." : "; ?></div>
		<div class="object"><?php echo $object; ?></div>
	</div>

	<div class="class_main">
		<div class="class_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][4]['TX']." : "; ?></div>
		<div class="class"><?php echo $classel; ?></div>
	</div>

	<div class="version_main">
		<div class="version_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][5]['TX']." : "; ?></div>
		<div class="version"><?php echo $version; ?></div>
	</div>


	<div class="status_main">
		<div class="status_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][8]['TX']." : "; ?></div>
		<div class="status"><?php echo $statusl." ".$flag; ?></div>
	</div>

	<div class="separatorv"></div>

	<div class="corps_main" <?php echo $no_details; ?>>
		<div class="corps_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][9]['TX']." : "; ?></div>
		<div class="corps"><?php echo $texte; ?></div>
	</div>

	<div class="separatorv" <?php echo $no_solution; ?>></div>

	<div class="solution_main" <?php echo $no_solution; ?>>
		<div class="solution_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][24]['TX']." : "; ?></div>
		<div class="solution"><?php echo $solution; ?></div>
	</div>


	<div class="separatorv"></div>

	<?php 
		if($reference == '')
	{
		$reference = '<span class="shadow">'.$_SESSION[$ssid]['lisha']['page_text'][26]['TX'].'</span>';
	}
	?>
	<div class="devindex_main">
		<div class="devindex_caption"><?php echo $_SESSION[$ssid]['lisha']['page_text'][29]['TX']." : "; ?></div>
		<div class="devindex"><?php echo $reference; ?></div>
	</div>
	</body>
</html>
