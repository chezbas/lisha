function switch_lisha(mode) 
{ 	
	if (mode == 1)
	{
		document.getElementById('lisha_trans').style.display = 'none';
		document.getElementById('icon_checked').style.display = 'none';
		
		document.getElementById('lisha_check').style.display = 'block';
		document.getElementById('icon_transaction').style.display = 'block';
	}
	else
	{
		document.getElementById('lisha_trans').style.display = 'block';
		document.getElementById('icon_checked').style.display = 'block';
		
		document.getElementById('lisha_check').style.display = 'none';
		document.getElementById('icon_transaction').style.display = 'none';

	}
}