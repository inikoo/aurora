function getFormData()
{
	var totalNumber = document.getElementById('max_num_mail').value;
	var campaign_name;
	var campaign_obj;
	var campaign_mail;
	var customer_list_key;
	var campaign_content;

		campaign_name = document.getElementById('campaign_name').value;
		campaign_obj = document.getElementById('campaign_obj').value;
		campaign_mail = document.getElementById('campaign_mail').value;
		campaign_content = document.getElementById('campaign_content').value;
		customer_list_key = document.getElementById('customer_list_key').value;

	var error = '';

	if(campaign_name == '')
	{
	   error = error + 'Please enter the name for Campaign\n';
	}

	if(campaign_obj == '')
	{
	   error = error + 'Please enter the objective for Campaign\n';
	}	

	if(campaign_mail == '')
	{
	   error = error + 'Please enter the maximum mail for Campaign\n';
	}
	else
	{
		if(isNaN(campaign_mail))	
		{
		   error= error+ 'Invalid entry in mail\n';
		}
	}
				
	if(campaign_mail > totalNumber)
	{
		 error = error + 'You have '+totalNumber+' number of emails. Limit exceeded.\n';				
		
	}

	if(campaign_content == '')
	{
	   error = error + 'Please enter the content for Campaign\n';
	}
	
	if(error.length>0){
		alert(error);
		return false;
	}


	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
		//alert(xmlhttp.responseText);
	    document.getElementById("campaign_div").innerHTML=xmlhttp.responseText;
	    }
	  }
	xmlhttp.open("GET","create_campaign_data.php?campaign_name="+campaign_name+'&campaign_obj='+campaign_obj+'&campaign_mail='+campaign_mail+'&campaign_content='+campaign_content+'&customer_list_key='+customer_list_key,true);
	xmlhttp.send();


}


