<?php header("Content-Type: text/css"); ?>
html{background-color:#fff;background-image:url('<?php if(isset($_GET["c"])) {echo "../uploads/".isset($_GET["c"]);} else echo "../art/bg/theme_bg7.jpg"; ?>');
background-repeat:repeat-x;}


#hd{height:3.46em;background:#3b5998 url("../art/8.png") bottom left repeat-x;color:#fff;}

#hd #navsite li {
		float: right;
		background: transparent url("../art/radish_header_menu2h.png") 100%  no-repeat!important;
                
                background: none;	
		padding: 0 5px 0px 0;
		margin: 0 5px 0px 0;
	}
	#hd #navsite ul a {
	       
	    	float: left;
		display: block;
		padding: 2px 4px 5px 8px;
		background: transparent url("../art/radish_header_menu.png") 0%  no-repeat!important;
		background:none;
		font-weight: bold;
		outline: none;
		text-decoration: none;
	}
	#hd #navsite ul li:hover {background: transparent url("../art/radish_bg_side.png") 100%  no-repeat!important;background:none }
	#hd #navsite ul li:hover a { text-decoration:none;background: transparent url("../art/radish_header_menu_hover.png") 0%  no-repeat!important;background:none}
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
.table_manager div.centre_block {height:10px;border-top:1px solid .table_manager div.centre_block {height:10px;border-top:1px solid  #CF9199;background:Lavender;};background:Lavender;}
table.show_info_product {border-top:1px solid #CF9199;border-bottom:1px solid #CF9199;width:100%}

.options td.selected,.options span.selected{background:#CF9199;border:1px solid #CF9199;color:#f6f6f6;text-decoration:none}


span.nav2 {float:left;background:#CF9199;padding:0 10px;position:relative;bottom:6px;color:#fff;font-size:85%;margin:0 2px}


      /*---------------------------------themetable---------------------------------------------------------------*/

.data_table_container {width:100%;clear:both;border-top:1px solid #CF9199;border-bottom:1px solid #CF9199;margin-bottom:2px;padding-bottom:1px;}

.clean_table_add_items:hover { background:#edf3ff; }
.clean_table_info span.selected { background:#7296e1;color:white }

.inikoo .yui-dt-hd table{border-left:1px solid #7F7F7F;border-top:1px solid #7F7F7F;border-right:1px solid #7F7F7F;}

.inikoo .yui-dt-scrollable .yui-dt-hd{border-left:1px solid #7F7F7F;border-top:1px solid #7F7F7F;border-right:1px solid #7F7F7F;}
.inikoo .yui-dt-scrollable .yui-dt-bd{border-left:1px solid #7F7F7F;border-bottom:1px solid #7F7F7F;border-right:1px solid #7F7F7F;}

.inikoo .yui-dt th{border-bottom:1px solid #CF9199}
.inikoo .yui-dt-data tr{border-top:1px solid #CF9199}
.inikoo tr.yui-dt-even{background-color:#f2e3e5;}

.inikoo tr.yui-dt-even td.yui-dt-asc   ,.inikoo tr.yui-dt-even td.yui-dt-desc{background-color:#f2e3e5;}
.btable caption,.tablecaption  {background:orange;color:black;height:13.85em;padding:2px 0px 0px 3px;width:100%;font-size:93%;margin-top:10px;;border-bottom:1px solid #CF9199}
.inikoo .with_total tr.yui-dt-last td.yui-dt-asc{border-top:1px solid #CF9199;border-bottom:none;background:#fff}
.inikoo tr.yui-dt-last{border-bottom:1px solid #CF9199}
.inikoo .with_total tr.yui-dt-last td.yui-dt-desc {border-top:1px solid #CF9199;border-bottom:none;background:#fff}
.inikoo .with_total tr.yui-dt-last  {border-top:1px solid #CF9199;border-bottom:none;background:#fff}

.inikoo .with_total  .yui-dt-data  tr.yui-dt-last  {border-top:1px solid #CF9199;border-bottom:none;background:#fff}

/*---------------------------------theme index---------------------------------------------------------------*/




#wid_menu li.active {
color:#fff;
			background-color: #CF9199;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			-opera-border-radius: 3px;
			-moz-border-radius: 3px;
		}





/*---------------------------------theme dropdown---------------------------------------------------------------*/

.dropdown dt {  padding:0 10px; cursor:pointer; background:#CF9199;}







































