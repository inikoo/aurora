<?php
global $width;
$width=$_REQUEST['width'];
if($width==1000){
	$header_art="header1000d.png";
	$footer_art="footer1000.png";
	$header_table_width=1000;
	$header_table_found_in=130;
	$header_table_search=505;
	$header_table_see_also=365;
}
else{
	$header_art="header1.jpg";
	$footer_art="footerbackground.png";
	$header_table_width=875;
	$header_table_found_in=130;
	$header_table_search=380;
	$header_table_see_also=365;
}


?>
body, html, div , table{	 font-family:"Arial", sans-serif; font-size:11.0px;font-weight: bold;  }
table {border-collapse: collapse;}

#header_container{position:absolute;top:24px;width:<?php echo $width ?>px;}




#header{
	background-image:url('../art/<?php echo $header_art?>'); 
	background-repeat:no-repeat; 
	background-position:center bottom; 
	color:black; 
	height: 90px; 
	background-repeat:no-repeat;  
    
	width: 100%
}
h1 {position:relative;top:5px;padding:0;margin:0px 0 0 180px;xfont-family: Delicious, sans-serif;font-size:35px;color:#111;text-shadow: 1px 2px 1px  #444}

@font-face {
	font-family: weathered;
	src: url('weathered.TTF');}
@font-face {
	font-family: weathered;
	font-weight: 1000;
	src: url('weathered.TTF');
}

@font-face {
	font-family: Delicious;
	src: url('ABOS.TTF');}
@font-face {
	font-family: Delicious;
	font-weight: 1000;
	src: url('ABOS.TTF');
}

#footerxx{
	background-image:url('../art/footerbackground.png'); 
	height: 54px; 
	background-repeat:no-repeat; 
	
	top:80px; 
	
	position:absolute;bottom:0px;
}


#footer_container{


position:absolute;

width:100%;
text-align:center;
}

#footer{
background-image:url('../art/<?php echo $footer_art ?>'); 
height:54px;

bottom:0;
width:<?php echo $width ?>px;
margin:auto auto;
margin-bottom:10px
}



#div2{
display:none;position:absolute;top:50px;padding:0;margin-left:730px;font-family:"Arial", sans-serif; font-weight:700; font-size:11.0px; 
        line-height:1.27; color:#ffffff; text-align:center;
}

#header_slogans{
position:absolute;top:60px;padding:0;margin-left:10px;font-family:"Arial", sans-serif; font-size:11.0px; line-height:1.27; 
        color:#000; text-align:center;
}

#aw_link{
	position:absolute;top:10px;padding:0;margin-left:45px;height:40px;width:70px;
}

table.footer_table{
	width:100%;

	
}


 .footer_table td.address{
	background-position:center bottom; 
	width:128px;padding-top:5px;padding-bottom:10px;
	font-family:"Arial", sans-serif; font-weight:700; font-size:9.0px; 
    line-height:1.22; color:#ffffff; 
}

 .footer_table span{margin-left:15px}

.footer_table td.description{
	text-align:center;
	font-family:"Arial", sans-serif; font-size:11.0px; line-height:1.27; 
    color:#ff8000; 
}
.footer_table td.other{
	background-position:center bottom; 
	width:401px;padding-top:5px;padding-bottom:10px
}


table.zheader_table{
	xwidth:100%;

	
}




 .zheader_table span{
 
 margin-left:15px;
 width:600px;
 ;padding-left:10px;
 }




.product_list td{padding:4px 5px 4px 10px;}
.product_list .top td  {border-top:1px solid #ccc}
.product_list .last td {border-top:1px solid #ddd}
.product_list .space td {padding-top:10px}
.product_list .button {cursor:pointer}

.product_list .rrp{color:	#900020}
.product_list .description{color:	#444;padding-left:5px}
.product_list p {line-height:150%}
.product_list .register {color:#444;font-style: italic;}
.product_list td.input input{border:1px solid #ccc;width:35px}

.product_list .price{color:#236E4B;text-align:right}
.product_list .out_of_stock{color:	red}
.product_list .discontinued{color:	red}

.product_list td.price {text-align:right;padding-right:0px}
.product_list td.input {padding-left:5px;padding-right:0px}

.product_list input.out_of_stock ,.product_list input.discontinued {background-color:#ccc;border:1px solid red; visibility:hidden}


.form td{padding:1px 5px 1px 10px;}




table.header_table{
	width:<?php echo $header_table_width?>px;

	
}
 .header_table td#found_in{
	background-image:url('../art/found_in.png'); 
	background-repeat:no-repeat; 
	background-position:center bottom; 
	width:<?php echo $header_table_found_in?>px;
	padding-top:5px;padding-bottom:10px;
	text-align:center;
	
}

.header_table td#search{
	vertical-align:bottom;text-align:center;
	width:<?php echo $header_table_search?>px;
}
.header_table td#see_also{
	background-image:url('../art/see_also.png'); 
	background-repeat:no-repeat; 
	background-position:center bottom; 
	width:<?php echo $header_table_see_also?>px;padding-top:5px;padding-bottom:10px;padding-left:20px;padding-right:10px;
	
	
}

.header_table  a{color:700000}
.header_table  a:hover{color:770777}
.header_table  a:visited{color:700070}