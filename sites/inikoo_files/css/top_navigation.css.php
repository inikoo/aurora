<?php
global $width;
$width=$_REQUEST['width'];
?>

#top_navigator{height:24px;z-index:1000;background:black;text-align:right;padding:0px;font-size:12px;font-family:"Arial";color:#ddd;visibility:hidden;
width:<?php echo $width ?>px;vertical-align:bottom;
}



.soft_link{cursor:pointer;color:#ccc}
.soft_link:hover{text-decoration:underline}

.dialog {display:none;background:black;color:white;z-index:10000;padding:0px 20px 20px 20px;position:relative;top:-22px;float:right;}
.dialog table {font-size:12px;color:white;margin:0px 0px 0px 30px}

.dialog tr.space td{padding-top:15px}

.dialog tr.button td,.dialog tr.link td{text-align:right;}
.dialog h2 {font-size:15px;weight:800;xmargin:10px 10px 5px 30px}

.dialog table .title td{border-bottom:1px solid #ddd;height:30px;vertical-align:bottom;font-style:italic}
.dialog p{width: 300px;}
.dialog .label {width:60px;text-align:right;padding-right:10px}

#dialog_register_part_2 .label {width:120px;}

span.link{color:orange;cursor:pointer;margin-left:15px;}
span.link:hover{text-decoration:underline}

span.link2{color:orange;cursor:pointer;margin-left:10px;} 
span.link2:hover{text-decoration:underline}

.dialog input {width:200px;border: 1px solid #ccc}
.dialog input.error {background-color: #F5EEA2;}
#top_navigator button, .dialog button{cursor:pointer}
#top_navigator button{margin:0}


.logged {margin-top:20;text-aling:right}

#basket {height:22px;vertical-align:-6px;}
#show_actions_dialog{cursor:pointer;margin-left:10px;height:22px;vertical-align:-6px;}
.gear{visibility:hidden;height:22px;vertical-align:-6px;}