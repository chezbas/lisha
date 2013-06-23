<script type="text/javascript">
	var __IFICHE__ = '__IFICHE__';
	var __ICODE__ = '__ICODE__';
	var tabbar;
	var tabbar_link;
	var tabbar_header;
	var tabbar_step;

	function gen_tab()
	{
		if(iobject_selected == __IFICHE__)
		{
			// iSheet
			
			tabbar = new iknow_tab('tabbar');
			tabbar.addTab("tab-1","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][499]);?>",'<div class="onglets"><table><tr><td><input type="button" value="<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][375]);?>" onclick="rapprocher_var();"/></td><td><input type="button" value="<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][426]);?>" onclick="purger_url();"/></td><td></td></tr><tr><td><?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][497]);?> : </td><td><input type="checkbox" id="chk_neutre"/> <?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][400]);?></td><td><input type="checkbox" id="chk_default"/> <?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][399]);?></td></tr><tr><td><?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][218]);?> : </td><td><input id="lib_link" type="text" value="<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][58]);?>"/></td><td></td></tr></table></div>');
			tabbar.addTab("tab-2","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][498]);?>",'<div style="background-image:url(../../../../../../images/header_ifiche_visu.jpg);height:50px;color:#FFF;"><div style="height:10px;"><?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][231]);?></div><div id="tabbar_link" class="onglets_step"></div></div>');

			tabbar_link = new iknow_tab('tabbar_link');
			tabbar_link.addTab("1","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][41]);?>",'<div id="tabbar_header" class="onglets"></div>');
			tabbar_link.addTab("2","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][40]);?>",'<div id="tabbar_step" class="onglets"></div>');
			tabbar_header = new iknow_tab('tabbar_header');
			tabbar_header.addTab("1_1","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][42]);?>",'');
			tabbar_header.addTab("1_2","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][43]);?>",'');
			tabbar_header.addTab("1_3","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][59]);?>",'');
			tabbar_header.addTab("1_4","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][60]);?>",'');
			tabbar_header.addTab("1_5","Tag",'');
			

			tabbar_step = new iknow_tab('tabbar_step');
			tabbar_step.addTab("2_1","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][81]);?>",'<span style="color:#000;"><?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][496]);?></span> <input type="text" size="3" value="1" id="dest_line" onchange="document.getElementById(\'dest_by_step\').value = this.value;"/>');
			tabbar_step.addTab("2_2","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][82]);?>",'<span style="color:#000;"><?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][496]);?></span> <input type="text" size="3" value="1" id="dest_by_step" onchange="document.getElementById(\'dest_line\').value = this.value;"/>');
			
			tabbar.setTabActive('tab-2');
			tabbar_link.setTabActive('1');
			tabbar_header.setTabActive('1_1');
			tabbar_step.setTabActive('2_1');
			tabbar.setTabActive('tab-1');
		}
		else
		{
			// iCode
			
			tabbar = new iknow_tab('tabbar');
			tabbar.addTab("tab-1","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][499]);?>",'<div class="onglets"><table><tr><td><input type="button" value="<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][375]);?>" onclick="rapprocher_var();"/></td><td><input type="button" value="<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][426]);?>" onclick="purger_url();"/></td><td></td></tr><tr><td><?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][497]);?> : </td><td><input type="checkbox" id="chk_neutre"/> <?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][400]);?></td><td><input type="checkbox" id="chk_default"/> <?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][399]);?></td></tr></tr><tr><td><?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][218]);?> : </td><td><input id="lib_link" type="text" value="<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][4]);?>"/></td><td></td></tr></table></div>');
			tabbar.addTab("tab-2","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][498]);?>",'<div style="background-image:url(../../../../../../images/header_icode_visu.jpg);height:50px;"><div style="height:10px;"></div><div id="tabbar_link" class="onglets_step"></div></div>');
			
			tabbar_link = new iknow_tab('tabbar_link');
			tabbar_link.addTab("1","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][41]);?>",'');
			tabbar_link.addTab("2","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][475]);?>",'');
			tabbar_link.addTab("3","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][59]);?>",'');
			tabbar_link.addTab("4","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][60]);?>",'');
			tabbar_link.addTab("5","<?php echo str_replace("'","\'",$_SESSION[$ssid]['message'][73]);?>",'');
			
			tabbar.setTabActive('tab-2');
			tabbar_link.setTabActive('2');
			tabbar.setTabActive('tab-1');
		}
	}
</script>