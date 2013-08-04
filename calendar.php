<?php
/*
 File: customer.php 

 UI customer page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Staff.php');


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<link rel='stylesheet' type='text/css' href='external_libs/fullcalendar/fullcalendar/fullcalendar.css' />
<script type='text/javascript' src='external_libs/fullcalendar/jquery/jquery-1.4.4.min.js'></script>
<script type='text/javascript' src='external_libs/fullcalendar/jquery/jquery-ui-1.8.6.custom.min.js'></script>
<script type='text/javascript' src='external_libs/fullcalendar/fullcalendar/fullcalendar.min.js'></script>
<script type='text/javascript'>

$(document).ready(function() {
	
		$('#calendar').fullCalendar({
		
			editable: true,
			
			events: "json-events.php",
			
			eventDrop: function(event, delta) {
				alert(event.title + ' was moved ' + delta + ' days\n' +
					'(should probably update your database)');
			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
			
		});
	});

</script>
<style type='text/css'>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
		
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}

	#calendar {
		width: 900px;
		margin: 0 auto;
		}
</style>
<?php

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 'css/text_editor.css',
		 'css/common.css',
		 'css/button.css',
		 'css/container.css',
		 'css/table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',		
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('calendar.tpl');

?>
