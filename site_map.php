<?php
/*
 File: index.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');


$css_files=array(
		  $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'common.css',
		 
		 );
$js_files=array(
$yui_path.'utilities/utilities.js',



		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		'js/dropdown.js',
	
		'external_libs/jquery/jquery-1.3.2.min.js',
		
		);




$smarty->assign('parent','index.php');
$smarty->assign('title', _('Site Map'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$script=sprintf('
$(document).ready(function(){
				$("head").append(\'<link rel="stylesheet" href="css/sitemap.css" />\');
				$("#sitemap").find("ul").parent("li").children("a").each(function(){
					$(this).addClass("disclosure").addClass("disclosureactive");
					counter = 0;
					$(this).parent("li").children("ul").children("li").each(function(i){
						counter = counter + 1;
					});
					ulwidth = counter*100;
					ulmargin = 0-((ulwidth/2)-50);
					imgwidth = (counter-1)*100;
					$(this).parent("li").children("ul").css({width:ulwidth, marginLeft:ulmargin});
					$(this).parent("li").children("ul").append("<img class=\'topbit\' src=\'art/bordertop.gif\' width=\'"+imgwidth+"\' height=\'1\' />");
				});
				$("#sitemap").find("ul").parent("li").children("a").click(function(){
					$(this).parent("li").children("ul").addClass("target");
					$(this).parent("li").parent("ul").children("li").children("ul").not(".target").hide().parent("li").children("a").addClass("disclosureactive");
					$(this).parent("li").children("ul").removeClass("target");
					$(this).parent("li").children("ul").slideToggle("slow");
					$(this).toggleClass("disclosureactive");
					return false;
				});	
			});');


$smarty->assign('script',$script);


$smarty->display('site_map.tpl');





?>
