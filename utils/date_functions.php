<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2013 17:24:27 GMT

 Copyright (c) 2009, Inikoo

 Version 2.0

*/

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

	$dates = array();
	$current = strtotime($first);
	$last = strtotime($last);

	while ( $current <= $last ) {
		$dates[] = date($output_format, $current);
		$current = strtotime($step, $current);
	}

	return $dates;
}


function gettext_relative_time($difference) {


	if (!$difference)return '';

	$periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
	$lengths = array("60", "60", "24", "7", "4.35", "12", "10");


	for ($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
	$difference = round($difference);


	switch ( $periods[$j]) {
	case 'sec':
		$text = number($difference)." ".ngettext('second', 'seconds', $difference);
		break;
	case 'min':
		$text = number($difference)." ".ngettext('minute', 'minutes', $difference);
		break;
	case 'hour':
		$text = number($difference)." ".ngettext('hour', 'hours', $difference);
		break;
	case 'day':
		$text = number($difference)." ".ngettext('day', 'days', $difference);
		break;
	case 'week':
		$text = number($difference)." ".ngettext('week', 'weeks', $difference);
		break;
	case 'month':
		$text = number($difference)." ".ngettext('month', 'months', $difference);
		break;
	case 'years':
		$text = number($difference)." ".ngettext('year', 'years', $difference);
		break;
	case 'decade':
		$text = number($difference)." ".ngettext('decade', 'decades', $difference);
		break;
	default:
		if ($difference != 1) $periods[$j].= "s";
		$text = number($difference)." $periods[$j]";
	}


	return $text;
}


function prepare_mysql_dates($date1='', $date2='', $date_field='date', $options='') {



	$start='';
	$end='';
	if (preg_match('/start.*end/i', $options)) {
		$start=' start';
		$end=' end';

	}
	if (preg_match('/(dates?_only|dates? only|only dates|date|whole_day|only_dates?)/i', $options)) {
		$d_option='date';



		$date_only=true;
	} else {
		$d_option='datetime';
		$date_only=false;
	}


	$tmp=prepare_mysql_datetime($date1, $d_option.$start);



	$mysql_date1=$tmp['mysql_date'];
	$ok1=$tmp['ok'];
	if ($tmp['status']=='empty')
		$ok1=true;

	$tmp=prepare_mysql_datetime($date2, $d_option.$end);
	$mysql_date2=$tmp['mysql_date'];

	$ok2=$tmp['ok'];
	if ($tmp['status']=='empty')
		$ok2=true;

	if (!$ok1 or !$ok2)
		$error=1;
	else
		$error=0;







	if (is_array($date_field)) {
		$date_field1=addslashes($date_field[0]);
		$date_field2=addslashes($date_field[1]);
	}else {

		$date_field1=addslashes($date_field);
		$date_field2=addslashes($date_field);

	}

	if ($options=='whole_day') {
		$mysql_date1=($mysql_date1==''?'':$mysql_date1.' 00:00:00');
		$mysql_date2=($mysql_date2==''?'':$mysql_date2.' 23:59:59');

	}

	if ($mysql_date2=='' and $mysql_date1=='' ) {
		$mysql_interval="";
	}elseif ($mysql_date2!='' and $mysql_date1!='') {
		$mysql_interval=" and $date_field1>='$mysql_date1' and $date_field2<='$mysql_date2'";

	}elseif ($mysql_date2!='') {
		$mysql_interval=" and $date_field2<='$mysql_date2'";
	}else {
		$mysql_interval=" and $date_field1>='$mysql_date1' ";
	}



	return array('0'=>$mysql_interval, '1'=>$date1, '2'=>$date2, '3'=>$error, 'error'=>$error, 'mysql'=>$mysql_interval, 'from'=>$date1, 'to'=>$date2, 'mysql_from'=>$mysql_date1, 'mysql_to'=>$mysql_date2);


}


function prepare_mysql_datetime($datetime, $tipo='datetime') {

	//print "** $tipo \n";exit;
	if ($datetime=='')
		return array('mysql_date'=>'', 'status'=>_('Empty field'), 'ok'=>false);
	$time='';

	if (preg_match('/datetime/', $tipo)) {
		if (preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d\s[012]\d:[0123456]\d$/', $datetime))
			$datetime=$datetime.':00';

		if (preg_match('/^[0123]\d[\-\/][01]\d[\-\/][12]\d{3} /', $datetime)) {
			$_tmp=preg_split('/\s/', $datetime);

			$tmp=preg_split('/\-|\//', $_tmp[0]);
			if (count($tmp)==3) {
				$_datetime=$tmp[2].'-'.$tmp[1].'-'.$tmp[0];
			}

			$datetime=$_datetime.' '.$_tmp[1];




		}

		if (!preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d\s[012]\d:[0123456]\d:[0123456]\d$/', $datetime))
			return array('mysql_date'=>'', 'status'=>"Error, date time not recognised $datetime", 'ok'=>false);
		$ts=date('U', strtotime($datetime));
		list($date, $time)=preg_split('/\s+/', $datetime);

		//exit;

	} else {


		if (preg_match('/[0123]\d[\-\/][01]\d[\-\/][12]\d{3}/', $datetime)) {
			$tmp=preg_split('/\-|\//', $datetime);
			if (count($tmp)==3) {
				$datetime=$tmp[2].'-'.$tmp[1].'-'.$tmp[0];
			}
		}

		if (!preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d/', $datetime))
			return array('mysql_date'=>'', 'status'=>_('Invalid date'), 'ok'=>false);
		$date=$datetime;
		$ts=date('U', strtotime($date));
	}


	$date=str_replace('/', '-', $date);
	$date=preg_split('/-/', $date);


	if (preg_match('/datetime/', $tipo)) {

		$mysql_datetime= trim(join('-', $date).' '.$time);
	} else {


		$mysql_datetime= join('-', $date);
		if (preg_match('/start/i', $tipo))
			$mysql_datetime.=' 00:00:00';
		if (preg_match('/midday/i', $tipo))
			$mysql_datetime.=' 12:00:00';
		elseif (preg_match('/end/i', $tipo))
			$mysql_datetime.=' 23:59:59';

	}
	return array('ts'=>$ts, 'mysql_date'=>$mysql_datetime, 'status'=>'ok', 'ok'=>true);

}


function get_interval_db_name($interval) {

	switch ($interval) {


	case 'Total':
	case 'all':
		$db_interval='Total';

		break;

	case 'Last Month':
	case 'last_m':
		$db_interval='Last Month';

		break;

	case 'Last Week':
	case 'last_w':
		$db_interval='Last Week';



		break;

	case 'Yesterday':
	case 'yesterday':
		$db_interval='Yesterday';

		break;

	case 'Week To Day':
	case 'wtd':
	case 'weektoday':
		$db_interval='Week To Day';

		break;
	case 'Today':
	case 'today':
		$db_interval='Today';

		break;


	case 'Month To Day':
	case 'mtd':
	case 'monthtoday':

		$db_interval='Month To Day';

		break;
	case 'Year To Day':
	case 'ytd':
	case 'yeartoday';
		$db_interval='Year To Day';

		break;
	case '3 Year':
	case '3y':
	case 'three_year':
		$db_interval='3 Year';

		break;
	case '1 Year':
	case '1y':
	case 'year':
		$db_interval='1 Year';

		break;
	case '6 Month':
	case '6m':
	case 'six_month':
		$db_interval='6 Month';

		break;
	case '1 Quarter':
	case '1q':
	case 'quarter':
		$db_interval='1 Quarter';

		break;
	case '1 Month':
	case '1m':
	case 'month':
		$db_interval='1 Month';

		break;
	case '10 Day':
	case '10d':
	case 'ten_day':
		$db_interval='10 Day';

		break;
	case '1 Week':
	case '1w':
	case 'week':
		$db_interval='1 Week';

		break;
	case 'hour':

		$db_interval='1 Week';

		break;
	case '1 Day':
	case '1d':
	case 'day':
		$db_interval='1 Day';

		break;
	case '1 Hour':
	case '1h':
		$db_interval='1 Hour';

		break;

	default:
		return;
		break;
	}

	return $db_interval;
}


function calculate_interval_dates($interval, $from='', $to='') {

	$from_date=false;
	$to_date=false;

	$from_date_1yb=false;
	$to_1yb=false;

	switch ($interval) {


	case 'Total':
	case 'all':
	case 'All':
		$db_interval='Total';
		$from_date=false;
		$to_date=false;

		$from_date_1yb=false;
		$to_1yb=false;
		break;

	case 'Last Month':
	case 'last_m':
		$db_interval='Last Month';
		$from_date=date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
		$to_date=date('Y-m-d 23:59:59', mktime(0, 0, -1, date('m'), 1, date('Y')));

		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("$to_date -1 year"));
		//print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
		break;

	case 'Last Week':
	case 'last_w':
		$db_interval='Last Week';


		$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d", date('Y'), date('W'));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from_date=date('Y-m-d 00:00:00', strtotime($row['First Day'].' -1 week'));
			$to_date=date('Y-m-d 23:59:59', strtotime($row['First Day'].' -1 second'  ));

		} else {
			return;
		}



		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("$to_date -1 year"));
		break;

	case 'Yesterday':
	case 'yesterday':
		$db_interval='Yesterday';
		$from_date=date('Y-m-d 00:00:00', strtotime('today -1 day'));
		$to_date=date('Y-m-d 23:59:59', strtotime('today -1 day'));

		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("today -1 year"));
		break;

	case 'Week To Day':
	case 'wtd':
		$db_interval='Week To Day';

		$from_date=false;
		$from_date_1yb=false;

		$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d", date('Y'), date('W'));

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from_date=$row['First Day'].' 00:00:00';
			$lapsed_seconds=strtotime('now')-strtotime($from_date);

		} else {
			return;
		}
		$to_date=gmdate('Y-m-d 23:59:59');


		$sql=sprintf("select `First Day`  from  kbase.`Week Dimension` where `Year`=%d and `Week`=%d", date('Y')-1, date('W'));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from_date_1yb=$row['First Day'].' 00:00:00';
		}


		$to_1yb=date('Y-m-d H:i:s', strtotime($from_date_1yb." +$lapsed_seconds seconds"));



		break;
	case 'Today':
	case 'today':
		$db_interval='Today';
		$from_date=date('Y-m-d 00:00:00');
		$to_date=gmdate('Y-m-d 23:59:59');

		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;


	case 'Month To Day':
	case 'mtd':
		$db_interval='Month To Day';
		$from_date=date('Y-m-01 00:00:00');
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case 'Year To Day':
	case 'ytd':
		$db_interval='Year To Day';
		$from_date=date('Y-01-01 00:00:00');
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		//print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
		break;
	case '3 Year':
	case '3y':
		$db_interval='3 Year';
		$from_date=date('Y-m-d H:i:s', strtotime("now -3 year"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=false;
		$to_1yb=false;
		break;
	case '1 Year':
	case '1y':
	case 'year':
		$db_interval='1 Year';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 year"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '6 Month':
		$db_interval='6 Month';
		$from_date=date('Y-m-d H:i:s', strtotime("now -6 months"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Quarter':
	case '1q':
		$db_interval='1 Quarter';
		$from_date=date('Y-m-d H:i:s', strtotime("now -3 months"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Month':
	case '1m':
		$db_interval='1 Month';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 month"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '10 Day':
	case '10d':
		$db_interval='10 Day';
		$from_date=date('Y-m-d H:i:s', strtotime("now -10 days"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Week':
	case '1w':
		$db_interval='1 Week';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 week"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Day':
	case '1d':
		$db_interval='1 Day';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 day"));
		$to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Hour':
	case '1h':
		$db_interval='1 Hour';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 hour"));
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case 'interval':
		$db_interval='';

		$from_date=date('Y-m-d 00:00:00', strtotime("$from"));
		$to_date=date('Y-m-d 23:59:59', strtotime("$to"));
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("$to_date -1 year"));
		break;
	case 'date':
		$db_interval='';

		$from_date=date('Y-m-d 00:00:00', strtotime("$from"));
		$to_date=date('Y-m-d 23:59:59', strtotime("$from"));
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("$to_date -1 year"));
		break;


	default:

		return;
		break;
	}
	
	return array($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb);

}


?>
