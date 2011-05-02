<?php header("Content-Type: text/css"); ?>
html{background-color:#fff;background-image:url('<?php if(isset($_GET["c"])) {echo "../uploads/".isset($_GET["c"]);} else echo "../art/bg/theme_bg5.jpg"; ?>');
background-repeat:repeat-x;}
body{color:#333;margin:0px;padding:0px;font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, sans-serif;}
td {text-align:left}
.hide{visibility:hidden}

#hd{height:3.46em;background:#3b5998 url("../art/6.png") bottom left repeat-x;color:#fff;}
#hd h1{padding:0 15px;font-size:167%;float:left;position:relative;top:10px;}
	#hd a{color:#fff;text-decoration:none}

	#hd a:hover{text-decoration:underline}	       
#hd #navsite ul {padding: 0 15px 0px 0;list-style: none;}
#hd #navsite li {
		float: right;
		background: transparent url("../art/christmas_header_menu2h.png") 100%  no-repeat!important;
                
                background: none;	
		padding: 0 5px 0px 0;
		margin: 0 5px 0px 0;
	}
	#hd #navsite ul a {
	       
	    	float: left;
		display: block;
		padding: 2px 4px 5px 8px;
		background: transparent url("../art/christmas_header_menu.png") 0%  no-repeat!important;
		background:none;
		font-weight: bold;
		outline: none;
		text-decoration: none;
	}
	#hd #navsite ul li:hover {background: transparent url("../art/christmas_bg_side.png") 100%  no-repeat!important;background:none }
	#hd #navsite ul li:hover a { text-decoration:none;background: transparent url("../art/christmas_header_menu_hover.png") 0%  no-repeat!important;background:none}
	#hd .selected li, #hd .selected:hover   {
background:transparent url("../art/header_menu2s.png") 100%  no-repeat !important;
background:none;
}
	#hd .selected a, #hd .selected:hover  a{
background: transparent url("../art/header_menus.png") 0%  no-repeat!important;
background:none;
color:#ff0 !important;
color:red;
}	
.table_manager div.centre_block {height:10px;border-top:1px solid .table_manager div.centre_block {height:10px;border-top:1px solid  #B0B66E;background:Lavender;};background:Lavender;}
table.show_info_product {border-top:1px solid #B0B66E;border-bottom:1px solid #B0B66E;width:100%}
xtable.show_info_product td {border-top:1px solid #ddd;border-bottom:1px solid #ddd}
.prodinfo{font-size:120%}
table.show_info_product td.number div{width:5em;text-align:right}



.options td,.options span{font-size:85%;border:1px solid #aaa;color:#aaa;text-align:center;padding:0px 7px 0px 7px;cursor:pointer;}

.options td:hover,  .options span:hover{border:1px solid #777;color:#777}

.options td.selected,.options span.selected{background:#B0B66E;border:1px solid #B0B66E;color:#f6f6f6;text-decoration:none}
.options td.disabled{background:#fff;border:1px solid #eee;color:#ddd;cursor:default}
.options tr.title{}
.options tr.title td{background:#fff;border:none;cursor:default;color:#555;text-align:left}
.options tr.title td:hover{color:#555}




span.nav2 {float:left;background:#B0B66E;padding:0 10px;position:relative;bottom:6px;color:#fff;font-size:85%;margin:0 2px}
span.nav2 a {color:#fff}
span.nav2 a.selected {color:yellow;font-weight:400}
span.nav2 span {color:#fff;cursor:pointer}
span.nav2 span.selected {color:yellow;font-weight:400;border:none;background:none}




      /*---------------------------------themetable---------------------------------------------------------------*/

 .data_table  {margin:0px}

.data_table_container {width:100%;clear:both;border-top:1px solid #B0B66E;border-bottom:1px solid #B0B66E;margin-bottom:2px;padding-bottom:1px;}

.data_table .but{margin:0 20px;color:#777;background:none }
.clean_table_add_items {border:1px solid #777;padding:2px 4px;position:relative;bottom:2px;cursor:pointer;margin-right:25px;border-bottom:none  }
.clean_table_add_items:hover { background:#edf3ff; }
.clean_table_info span.selected { background:#7296e1;color:white }

.inikoo .yui-dt thead{border-spacing:0}
.inikoo .yui-dt caption{padding-bottom:1em;text-align:left;}
.inikoo .yui-dt-hd table{border-left:1px solid #7F7F7F;border-top:1px solid #7F7F7F;border-right:1px solid #7F7F7F;}
.inikoo .yui-dt-bd table{}.inikoo .yui-dt-scrollable .yui-dt-hd table{border:0px;}
.inikoo .yui-dt-scrollable .yui-dt-bd table{border:0px;}
.inikoo .yui-dt-scrollable .yui-dt-hd{border-left:1px solid #7F7F7F;border-top:1px solid #7F7F7F;border-right:1px solid #7F7F7F;}
.inikoo .yui-dt-scrollable .yui-dt-bd{border-left:1px solid #7F7F7F;border-bottom:1px solid #7F7F7F;border-right:1px solid #7F7F7F;}

.inikoo .yui-dt th{border-bottom:1px solid #B0B66E}
.inikoo .yui-dt-data tr{border-top:1px solid #B0B66E}
.inikoo tr.yui-dt-even{background-color:#ededed;}
.inikoo tr.yui-dt-odd{background-color:#FFF;}
.inikoo tr.yui-dt-even td.yui-dt-asc   ,.inikoo tr.yui-dt-even td.yui-dt-desc{background-color:#ededed;}
.btable caption,.tablecaption  {background:orange;color:black;height:13.85em;padding:2px 0px 0px 3px;width:100%;font-size:93%;margin-top:10px;;border-bottom:1px solid #B0B66E}
.inikoo .with_total tr.yui-dt-last td.yui-dt-asc{border-top:1px solid #B0B66E;border-bottom:none;background:#fff}
.inikoo tr.yui-dt-last{border-bottom:1px solid #B0B66E}
.inikoo .with_total tr.yui-dt-last td.yui-dt-desc {border-top:1px solid #B0B66E;border-bottom:none;background:#fff}
.inikoo .with_total tr.yui-dt-last  {border-top:1px solid #B0B66E;border-bottom:none;background:#fff}
.table_top_bar{clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999}


.inikoo .with_total  .yui-dt-data  tr.yui-dt-last  {border-top:1px solid #B0B66E;border-bottom:none;background:#fff}

/*---------------------------------theme index---------------------------------------------------------------*/




#wid_menu li.active {
color:#fff;
			background-color: #B0B66E;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-opera-border-radius: 3px;
			-moz-border-radius: 3px;
		}





/*---------------------------------theme dropdown---------------------------------------------------------------*/

.dropdown dt {  padding:0 10px; cursor:pointer; background:#B0B66E;}







































