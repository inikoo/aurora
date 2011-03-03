function process(){
	//var totalNumber = document.getElementById('max_num_mail').value;
	var campaign_name;
	var campaign_obj;
	var campaign_mail;
	var customer_list_key;
	//var campaign_content;
	var error = '';
	campaign_name = document.getElementById('campaign_name').value;
	campaign_obj = document.getElementById('campaign_obj').value;
	campaign_mail = document.getElementById('campaign_mail').value;
	customer_list_key = document.getElementById('customer_list_key').value;
	//campaign_content = document.getElementById('campaign_content').value;
	if(campaign_name == '')
	{
	   error = error + 'Please enter the name for Campaign';
	   alert(error);
		document.getElementById('campaign_name').focus();
		return false;
	}
	if(campaign_obj == '')
	{
	   error = error + 'Please enter the objective for Campaign';
		alert(error);
		campaign_obj = document.getElementById('campaign_obj').focus();
		return false;
	}	
	if(campaign_mail == '')
	{
	   error = error + 'Please enter the maximum number of mail for Campaign';
		alert(error);
		campaign_mail = document.getElementById('campaign_mail').focus();
		return false;
	}
	document.campaign.submit();
}
