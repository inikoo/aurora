<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2013 17:24:27 GMT

 Copyright (c) 2009, Inikoo

 Version 2.0

*/


function get_period_data($period,$from='',$to='') {
	$period_label='';

	switch ($period) {
	case '1w':
		$from=date('Y-m-d',strtotime("now -1 week"));;
		$to=date("Y-m-d");
		$period_label=sprintf(" (%s-%s)",strftime('%x',strtotime($from)),_('today'));
		break;
	case '10d':
		$from=date('Y-m-d',strtotime("now -10 day"));;
		$to=date("Y-m-d");
		$period_label=sprintf(" (%s-%s)",strftime('%x',strtotime($from)),_('today'));
		break;
	case '1m':
		$from=date('Y-m-d',strtotime("now -1 month"));;
		$to=date("Y-m-d");
		$period_label=sprintf(" (%s-%s)",strftime('%x',strtotime($from)),_('today'));
		break;
	case '1q':
		$from=date('Y-m-d',strtotime("now -3 month"));;
		$to=date("Y-m-d");
		$period_label=sprintf(" (%s-%s)",strftime('%x',strtotime($from)),_('present'));
		
		break;
	case '1y':
		$from=date('Y-m-d',strtotime("now -1 year"));;
		$to=date("Y-m-d");
		$period_label=sprintf(" (%s-%s)",strftime('%x',strtotime($from)),_('present'));
		break;
	case '3y':
		$from= $from_date=date('Y-m-d',strtotime("now -3 year"));;
		$to=date("Y-m-d");
		$period_label=sprintf(" (%s-%s)",strftime('%x',strtotime($from)),_('present'));
		break;
	case 'yesterday':
		$from=date("Y-m-d",strtotime('yesterday'));
		$to=date("Y-m-d",strtotime('yesterday'));
		$period_label=strftime("%d %b %Y",strtotime('yesterday'));
		break;
	case 'today':
		$from=date("Y-m-d",strtotime('today'));
		$to=date("Y-m-d",strtotime('today'));
		$period_label=strftime("%d %b %Y");
		break;
	case 'ytd':
		$from=date("Y-01-01");
		$to=date("Y-m-d");
		$period_label=_('Year-to-Date').' '.strftime("%Y");
		break;
	case 'mtd':
		$from=date("Y-m-01");
		$to=date("Y-m-d");
		$period_label=_('Month-to-Date').' '.strftime("%B %Y");
		break;
	case 'wtd':
		$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y'),date('W'));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from=$row['First Day'];
		}
		$to=date("Y-m-d");
		$period_label=_('Week starting').' '.strftime("%d %b %Y",strtotime($from));
		break;
	case 'last_w':
		$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y'),date('W'));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from=date('Y-m-d',strtotime($row['First Day'].' -1 week'));
			$to=date("Y-m-d");
		}
		$period_label=_('Week starting').' '.strftime("%d %b %Y",strtotime($from));
		break;
	case 'last_m':
		
		$year=date('Y',mktime(0,0,0,date('m')-1,1,date('Y')));
		$month=date('m',mktime(0,0,0,date('m')-1,1,date('Y')));
		$_time=mktime(0, 0, 0,$month ,1 , $year);
		$from=date("Y-m-d", $_time);
		$to=date("Y-m-d", mktime(0, 0, 0, $month+1, 0, $year));
		$period_label=strftime("%B %Y", $_time);
		break;
	case 'all':
		$from='';
		$to='';
		$period_label=_('All');
		break;
	case 'f':
	
		if ($from==$to)
			$period_label=strftime("%d %b %Y", strtotime($from));
		else
			$period_label=strftime("%d %b %Y", strtotime($from)).'-'.strftime("%d %b %Y", strtotime($to));
		break;
		
		
	case 'day':
		$period_label=strftime("%d %b %Y", strtotime($from));;
		break;
	}

	return array($period_label,$from,$to);

}

?>
