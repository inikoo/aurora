<?php
/*
 File: part.php 

 UI warehouse stock history page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Warehouse.php');


$page='warehouse_stock_history';
$smarty->assign('page',$page);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 $yui_path.'container/assets/skins/sam/container.css',

		 'css/common.css',
		
		 'css/button.css',
		 'css/table.css',
		  'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		'js/table_common.js',
	    'js/search.js',
		'common_plot.js.php?page='.$page,
		'warehouse_stock_history.js.php',
		'js/dropdown.js'
		);


//$smarty->assign('search_label',_('Parts'));
//$smarty->assign('search_scope','part');


$smarty->assign('display',$_SESSION['state']['warehouse']['display']);



if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])){
  $warehouse_id=$_REQUEST['id'];
  $warehouse= new Warehouse($warehouse_id);
  $_SESSION['state']['warehouse']['id']=$warehouse->id;
 }else{
  $warehouse_id=$_SESSION['state']['warehouse']['id'];
  $_SESSION['state']['warehouse']['id']=$warehouse_id;
  $warehouse= new Warehouse($warehouse_id);  
 }


   <div class="data_table"  style="clear:both">
	<span id="table_title" class="clean_table_title" style="xdisplay:none">{t}Items{/t}</span>

	<div id="dtable_type" style="border:1px solid red">
	 
	  <span id="table_type_list" style="dfloat:right;color:brown" class="table_type state_details {if $table_type=='list'}state_details_selected{/if}">{t}Recomendations{/t}</span>
	  <span id="table_type_list" style="dfloat:right;margin-right:10px" class="table_type state_details {if $table_type=='list'}state_details_selected{/if}">{t}List{/t}</span>
	  <span id="table_type_thumbnail" style="dfloat:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}state_details_selected{/if}">{t}Thumbnails{/t}</span>
	</div>
	
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

     
    <div id="list_options0" style="display:none"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <span   style="float:right;margin-left:20px" class="state_details" state="{$show_all}"  id="show_all"  atitle="{if !$show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}"  >{if $show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}</span>     
      

      
      <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	<tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Discounts{/t}</td>
	  <td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Properties{/t}</td>
	</tr>
      </table>
      <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	  <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>
      <table  id="avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>
    </div>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

   
    <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;display:none"></div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  
</div>












$subject_id=$warehouse->id;
include_once('plot.inc.php');

$smarty->assign('warehouse',$warehouse);
$smarty->assign('parent','warehouse');
$smarty->assign('title',$warehouse->data['Warehouse Name']);
$plot_tipo=$_SESSION['state']['warehouse_stock_history']['plot'];
$plot_data=$_SESSION['state']['warehouse_stock_history']['plot_data'];
$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_data',$plot_data);
//$smarty->assign('key_filter_number',$regex['key_filter_number']);
//$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('stock_history_type',$_SESSION['state']['warehouse']['stock_history']['type']);


$q='';
$tipo_filter=($q==''?$_SESSION['state']['warehouse']['transactions']['f_field']:'note');
$smarty->assign('filter_show1',$_SESSION['state']['warehouse']['transactions']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',($q==''?$_SESSION['state']['warehouse']['transactions']['f_value']:addslashes($q)));
$filter_menu=array(
		   'note'=>array('db_key'=>'note','menu_label'=>_('Note'),'label'=>_('Note')),
		   );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$smarty->display('warehouse_stock_history.tpl');
?>