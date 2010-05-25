<?php


if ($tipo=='quick_this_month') {
    $tipo='m';
    $_SESSION['state'][$report_name]['y']=date('Y');
    $_SESSION['state'][$report_name]['m']=date('m');
}
elseif($tipo=='quick_this_year') {
    $tipo='y';
    $_SESSION['state'][$report_name]['y']=date('Y');
}
elseif($tipo=='quick_this_week') {
    $tipo='w';
    $_SESSION['state'][$report_name]['y']=date('Y');
    $_SESSION['state'][$report_name]['w']=date('W');
}
elseif($tipo=='quick_yesterday') {
    $tipo='d';
    $_SESSION['state'][$report_name]['y']=date('Y',strtotime('yesterday'));
    $_SESSION['state'][$report_name]['m']=date('m',strtotime('yesterday'));
    $_SESSION['state'][$report_name]['d']=date('d',strtotime('yesterday'));


}
elseif($tipo=='quick_today') {
    $tipo='d';
    $_SESSION['state'][$report_name]['y']=date('Y');
    $_SESSION['state'][$report_name]['m']=date('m');
    $_SESSION['state'][$report_name]['d']=date('d');

}



if ($tipo=='all_invoices' or $tipo=='all' or $tipo=='quick_all') {
    $tipo='f';

    $sql=sprintf("select DATE(min(`Invoice Date`)) as date  from `Invoice Dimension` where `Invoice Store Key` in (%s)",$store_keys);;
    // print $sql;

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res)) {
        $from=date("d-m-Y",strtotime($row['date']));
    }
    $to=date("d-m-Y");
    $title=$root_title.sprintf(" (%s-%s)",strftime('%x',strtotime($from)),strftime('%x'));
    $period=_('All Invoices');
    $link="&tipo=f&from=".$from."&to=".$to;
    //print $link;
}
elseif($tipo=='f') {

    $from=$_REQUEST['from'];
    $to=$_REQUEST['to'];
    $title=$root_title.sprintf(" (%s-%s)",strftime('%x',strtotime($from)),strftime('%x',strtotime($to)));
    $period='';
    $link=$link="&tipo=f&from=".$from."&to=".$to;
}
elseif($tipo=='w') {

    if (isset($_REQUEST['y'])) {
        $year=$_REQUEST['y'];
        $_SESSION['state'][$report_name]['y']=$year;
    } else
        $year=$_SESSION['state'][$report_name]['y'];

    if (isset($_REQUEST['w'])) {
        $week=$_REQUEST['w'];
        $_SESSION['state'][$report_name]['w']=$week;
    } else
        $week=$_SESSION['state'][$report_name]['w'];

    $sql=sprintf("select UNIX_TIMESTAMP(`First Day`) as date ,`First Day` from kbase.`Week Dimension`  where `Year Week`='%04d%02d'",$year,$week);

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


        $_time=strtotime('@'.$row['date']);
        $_time_n=strtotime('@'.($row['date']+604800));
        $_time_p=strtotime('@'.($row['date']-604800));



        $_time_n_3weeks=date("Y-m-d H:i:s",strtotime('@'.($row['date']+3*604800)));
        $_time_p_3weeks=date("Y-m-d H:i:s",strtotime('@'.($row['date']-3*604800)));


    } else
        die('error no year-week found');
    mysql_free_result($result);


    $ffrom=date("d/m", $_time);
    $fto=date("d/m", $_time_n);
    $from=date("d-m-Y", $_time);
    $to=date("d-m-Y", $_time_n);
    $period=_('Week').' '.date("W Y", $_time);
    $title="$period ($ffrom-$fto)".$root_title;

    $smarty->assign('up',array('url'=>'tipo=y&y='.date("Y",$_time),'title'=>date("Y",$_time)));
    $smarty->assign('next',array('url'=>'tipo=w&w='.date("W",$_time_n).'&y='.date("Y",$_time_n),'title'=>_('Week').' '.date("W-Y",$_time_n)));
    $smarty->assign('prev',array('url'=>'tipo=w&w='.date("W",$_time_p).'&y='.date("Y",$_time_p),'title'=>_('Week').' '.date("W-Y",$_time_p)));


    $w=array();
    $sql=sprintf("select * from kbase.`Week Dimension` where `First Day`> %s and `First Day`<%s "
                 ,prepare_mysql($_time_p_3weeks)
                 ,prepare_mysql($_time_n_3weeks)

                );
$dmy=date("W",$_time);
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $this_dmy=date("W",strtotime($row['First Day']));
    
        $w[]=array(
                 "number"=>$row['Week'],
                 "mon"=>date("d",strtotime($row['First Day'])),
                 "tue"=>date("d",strtotime($row['First Day']." +1 day" )),
                 "wed"=>date("d",strtotime($row['First Day']." +2 day" )),
                 "thu"=>date("d",strtotime($row['First Day']." +3 day" )),
                 "fri"=>date("d",strtotime($row['First Day']." +4 day" )),
                 "sat"=>date("d",strtotime($row['First Day']." +5 day" )),
                 "sun"=>date("d",strtotime($row['First Day']." +6 day" )),
                   "mon_selected"=>($this_dmy==$dmy?1:0),
                 "tue_selected"=>($this_dmy==$dmy?1:0),
                 "wed_selected"=>($this_dmy==$dmy?1:0),
                 "thu_selected"=>($this_dmy==$dmy?1:0),
                 "fri_selected"=>($this_dmy==$dmy?1:0),
                 "sat_selected"=>($this_dmy==$dmy?1:0),
                 "sun_selected"=>($this_dmy==$dmy?1:0),
                 "m_mon"=>date("m",strtotime($row['First Day'])),
                 "m_tue"=>date("m",strtotime($row['First Day']." +1 day" )),
                 "m_wed"=>date("m",strtotime($row['First Day']." +2 day" )),
                 "m_thu"=>date("m",strtotime($row['First Day']." +3 day" )),
                 "m_fri"=>date("m",strtotime($row['First Day']." +4 day" )),
                 "m_sat"=>date("m",strtotime($row['First Day']." +5 day" )),
                 "m_sun"=>date("m",strtotime($row['First Day']." +6 day" )),

                 "year"=>$row['Year']

             );
    }
    mysql_free_result($result);
    $link="&tipo=w&y=".$year."&w=".$week;
    $smarty->assign('w',$w);






}
elseif($tipo=='m') {

    if (isset($_REQUEST['y'])) {
        $year=$_REQUEST['y'];
        $_SESSION['state'][$report_name]['y']=$year;

    } else
        $year=$_SESSION['state'][$report_name]['y'];

    if (isset($_REQUEST['m'])) {

        $month=$_REQUEST['m'];
        $_SESSION['state'][$report_name]['m']=$month;

    } else
        $month=$_SESSION['state'][$report_name]['m'];



    $_time=mktime(0, 0, 0,$month ,1 , $year);
    $_time_n=mktime(0, 0, 0,$month+1 ,1 , $year);
    $_time_p=mktime(0, 0, 0,$month-1 ,1 , $year);

    $from=date("d-m-Y", $_time);
    $to=date("d-m-Y", mktime(0, 0, 0, $month+1, 0, $year));
    $period=strftime("%B %Y", $_time);
    $title="$period ".$root_title;

    $smarty->assign('up',array('url'=>'tipo=y&y='.date("Y",$_time),'title'=>date("Y",$_time)));
    $smarty->assign('next',array('url'=>'tipo=m&m='.date("m",$_time_n).'&y='.date("Y",$_time_n),'title'=>date("F",$_time_n)));
    $smarty->assign('prev',array('url'=>'tipo=m&m='.date("m",$_time_p).'&y='.date("Y",$_time_p),'title'=>date("F",$_time_p)));



    $w=array();
    $sql=sprintf("select * from kbase.`Week Dimension` where (Month(`Last Day`)=%d or  Month(`First Day`)=%d   )  and `Year`=%d "
                 ,date("m",$_time)
                 ,date("m",$_time)
                 ,date("Y",$_time)

                );
    //  print $sql;
    $dmy=date('m',$_time);

    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $this_dmy=date('m',strtotime($row['First Day']));
        $w[]=array(
                 "number"=>$row['Week'],
                 "mon"=>date("d",strtotime($row['First Day'])),
                 "tue"=>date("d",strtotime($row['First Day']." +1 day" )),
                 "wed"=>date("d",strtotime($row['First Day']." +2 day" )),
                 "thu"=>date("d",strtotime($row['First Day']." +3 day" )),
                 "fri"=>date("d",strtotime($row['First Day']." +4 day" )),
                 "sat"=>date("d",strtotime($row['First Day']." +5 day" )),
                 "sun"=>date("d",strtotime($row['First Day']." +6 day" )),
                   "mon_selected"=>(date("m",strtotime($row['First Day']))==$dmy?1:0),
                 "tue_selected"=>(date("m",strtotime($row['First Day']." +1 day" ))==$dmy?1:0),
                 "wed_selected"=>(date("m",strtotime($row['First Day']." +2 day" ))==$dmy?1:0),
                 "thu_selected"=>(date("m",strtotime($row['First Day']." +3 day" ))==$dmy?1:0),
                 "fri_selected"=>(date("m",strtotime($row['First Day']." +4 day" ))==$dmy?1:0),
                 "sat_selected"=>(date("m",strtotime($row['First Day']." +5 day" ))==$dmy?1:0),
                 "sun_selected"=>(date("m",strtotime($row['First Day']." +6 day" ))==$dmy?1:0),
                 "m_mon"=>date("m",strtotime($row['First Day'])),
                 "m_tue"=>date("m",strtotime($row['First Day']." +1 day" )),
                 "m_wed"=>date("m",strtotime($row['First Day']." +2 day" )),
                 "m_thu"=>date("m",strtotime($row['First Day']." +3 day" )),
                 "m_fri"=>date("m",strtotime($row['First Day']." +4 day" )),
                 "m_sat"=>date("m",strtotime($row['First Day']." +5 day" )),
                 "m_sun"=>date("m",strtotime($row['First Day']." +6 day" )),
                 "year"=>$row['Year']

             );
    }
    mysql_free_result($result);
    $link="&tipo=m&y=".$year."&m=".$month;
    $smarty->assign('w',$w);







}
elseif($tipo=='y') {

    if (isset($_REQUEST['y']) and preg_match('/^\d{2,4}$/',$_REQUEST['y'])) {
        $year=$_REQUEST['y'];
        $_SESSION['state']['reports_sales']['1']=$year;
    } else {
        $year=$_SESSION['state']['reports_sales']['y'];

    }




    $_time=mktime(0, 0, 0,1 ,1 , $year);
    $_time_n=mktime(0, 0, 0,1 ,1 , $year+1);
    $_time_p=mktime(0, 0, 0,1 ,1 , $year-1);

    $from=date("d-m-Y", $_time);
    $to=date("d-m-Y", mktime(0, 0, 0, 1, 0, $year+1));
    $period=date("Y", $_time);
    $title="$period ".$root_title;

    $smarty->assign('tipo_title',_('Annual Report'));
    $smarty->assign('next',array('url'=>'tipo=y&y='.date("Y",$_time_n),'title'=>date("Y",$_time_n)));
    $smarty->assign('prev',array('url'=>'tipo=y&y='.date("Y",$_time_p),'title'=>date("Y",$_time_p)));
    $m=array();
    foreach( range(1,12) as $_m) {

        $m[]=substr(strftime("%b", mktime(0, 0, 0, $_m, 1, 2000)),0,1);

    }
    $link="&tipo=y&y=".$year;
    $smarty->assign('m',$m);













}
elseif($tipo=='d') {


    if (isset($_REQUEST['y'])) {

        $year=$_REQUEST['y'];
        $_SESSION['state'][$report_name]['y']=$year;

    } else
        $year=$_SESSION['state'][$report_name]['y'];

    if (isset($_REQUEST['m'])) {

        $month=$_REQUEST['m'];
        $_SESSION['state'][$report_name]['m']=$month;

    } else
        $month=$_SESSION['state'][$report_name]['m'];

    if (isset($_REQUEST['d'])) {
        $day=$_REQUEST['d'];
        $_SESSION['state'][$report_name]['d']=$day;

    } else
        $day=$_SESSION['state'][$report_name]['d'];




    $_time=mktime(0, 0, 0,$month ,$day , $year);
    $_time_n=mktime(0, 0, 0,$month ,$day+1 , $year);
    $_time_p=mktime(0, 0, 0,$month ,$day-1 , $year);


    $_time_n_3weeks=mktime(0, 0, 0,$month ,$day+14 , $year);
    $_time_p_3weeks=mktime(0, 0, 0,$month ,$day-14 , $year);



    $from=date("d-m-Y", $_time);
    $to=date("d-m-Y", $_time);
    $period=strftime("%a %e %b %Y", $_time);
    $title="$period ".$root_title;

    $smarty->assign('up',array('url'=>'tipo=m&m='.date("m",$_time_n).'&y='.date("Y",$_time),'title'=>strftime("%B",$_time)));
    $smarty->assign('next',array('url'=>'tipo=d&m='.date("m",$_time_n).'&y='.date("Y",$_time_n).'&d='.date("d",$_time_n),'title'=>strftime("%e %b %y",$_time_n)));
    $smarty->assign('prev',array('url'=>'tipo=d&m='.date("m",$_time_p).'&y='.date("Y",$_time_p).'&d='.date("d",$_time_n),'title'=>strftime("%e %b %y",$_time_p)));




    $w=array();
    $sql=sprintf("select * from kbase.`Week Dimension` where (Month(`Last Day`)=%d or  Month(`First Day`)=%d   )  and `Year`=%d "
                 ,date("m",$_time)
                 ,date("m",$_time)
                 ,date("Y",$_time)

                );
    //  print $sql;

    $result=mysql_query($sql);
    $dmy=date("dmy",$_time);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


        $w[]=array(

                 "number"=>$row['Week'],
                 "mon"=>date("d",strtotime($row['First Day'])),
                 "tue"=>date("d",strtotime($row['First Day']." +1 day" )),
                 "wed"=>date("d",strtotime($row['First Day']." +2 day" )),
                 "thu"=>date("d",strtotime($row['First Day']." +3 day" )),
                 "fri"=>date("d",strtotime($row['First Day']." +4 day" )),
                 "sat"=>date("d",strtotime($row['First Day']." +5 day" )),
                 "sun"=>date("d",strtotime($row['First Day']." +6 day" )),
                 "mon_selected"=>(date("dmy",strtotime($row['First Day']))==$dmy?1:0),
                 "tue_selected"=>(date("dmy",strtotime($row['First Day']." +1 day" ))==$dmy?1:0),
                 "wed_selected"=>(date("dmy",strtotime($row['First Day']." +2 day" ))==$dmy?1:0),
                 "thu_selected"=>(date("dmy",strtotime($row['First Day']." +3 day" ))==$dmy?1:0),
                 "fri_selected"=>(date("dmy",strtotime($row['First Day']." +4 day" ))==$dmy?1:0),
                 "sat_selected"=>(date("dmy",strtotime($row['First Day']." +5 day" ))==$dmy?1:0),
                 "sun_selected"=>(date("dmy",strtotime($row['First Day']." +6 day" ))==$dmy?1:0),
                 "m_mon"=>date("m",strtotime($row['First Day'])),
                 "m_tue"=>date("m",strtotime($row['First Day']." +1 day" )),
                 "m_wed"=>date("m",strtotime($row['First Day']." +2 day" )),
                 "m_thu"=>date("m",strtotime($row['First Day']." +3 day" )),
                 "m_fri"=>date("m",strtotime($row['First Day']." +4 day" )),
                 "m_sat"=>date("m",strtotime($row['First Day']." +5 day" )),
                 "m_sun"=>date("m",strtotime($row['First Day']." +6 day" )),
                 "year"=>$row['Year']

             );
    }
    mysql_free_result($result);
    $link="&tipo=d&d=".$day."&y=".$year."&m=".$month;
    $smarty->assign('w',$w);





}


?>