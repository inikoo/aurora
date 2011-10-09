<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.EmailCampaign.php';

$campaign_key=1;

$email_campaign=new EmailCampaign($campaign_key);
//print_r($email_campaign->get_contents_array());

$contents=$email_campaign->get_contents_array();

foreach($contents as $content){
	$html=$content['html'];
	//print "$html\n";
	
	
	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>"; 

	if(preg_match_all("/$regexp/siU", $html, $matches)) {
		//print_r($matches[0]);
		
		foreach($matches[0] as $match){
			//print "$match\n";
			
			if(preg_match("/href=[\'\"]?([^\'\ >]+)/", $match, $href)) {
				$pattern=$href[0];
				
				//print "$pattern\n";
				$url=explode("\"", $pattern);
				//print $url[1];
				
				
				$sql=sprintf("select `Email Link Dimension Key` from `Email Link Dimension` where `Email Link URL`='%s'", $url[1]);
				//print $sql;
				$result=mysql_query($sql);
				if($row=mysql_fetch_array($result))
					$key=$row['Email Link Dimension Key'];
				else{
					$sql=sprintf("insert into `Email Link Dimension` (`Email Link URL`) values ('%s')", $url[1]);
					if($result=mysql_query($sql))
						$key=mysql_insert_id();
				}
				
				$pattern=preg_replace('/\//', "\/", $pattern);
				
				
				$replace_url="http://localhost/kaktus/link.php?id=$key";
				
				
				$html=preg_replace("/$pattern/", $replace_url, $html);
				
				
			}
			
		}
		
	}
	
	
}


$sql=sprintf("update `Email Content Dimension` set `Email Content HTML`='%s' where `Email Content Key`=%d", $html, $campaign_key);
mysql_query($sql);
//print "$html\n";



?>