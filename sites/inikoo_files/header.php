<?php 
//global $found_in['url'];
global $found_in_label, $found_in_url;
global $see_also, $header_title, $path, $header_image;

//print $header_image;
if($header_image)
$style='style="background-image:url(\''.$path.'art/'.$header_image.'\')"'; 
else
$style='';
//print $found_in_url;

$search_input=file_get_contents("$path".'inikoo_files/templates/search_input.html');
$menubar=file_get_contents("$path".'inikoo_files/templates/menubar.html');
//$title='Tibetan Bowls and Artefacts';
$title=$header_title;
$header_info='Please note this is a we supply wholesale we supply wholesale to the gift trade';

$found_in['url']=$found_in_url;
$found_in['label']=$found_in_label;
if(isset($found_in['url']))
$found_in="<a href='".$found_in['url']."'>".$found_in['label']."</a>";
else
$found_in='';

$i=0;
$see_also_data="";
if(isset($see_also)){
foreach($see_also as $key=>$value){
$see_also_data.="<span><a href='".$value."'>".$key."</a></span>";
	if($i++>1) break;
}
}
/*
<span  >Chill Pilss</span>
<span  >Bath bombs</span>
<span style="" >Mini Incense sticks</span>
*/

$header=<<<EOD

<div id="header_container" >

<div id="header" $style>


<div style="height:55px;color:#800000">
<h1>$title</h1>
</div>
<div style="margin-left:172px" id="menu_bar">$menubar
</div>

<div id="header_slogans"><span id="slogan2">Giftware sourced worldwide</span></div>
<div id="div2">Please note this is a <br/> Trade Only Site </div>
<a href="http://www.ancientwisdom.biz"><span id="aw_link"></span></a>
<table  class="header_table" >
<tr>
<td class="found_in">$found_in</td>
<td >$search_input</td>
<td rowspan="3" class="see_also">

<span><b>See also:</b>$see_also_data</span>


</td>
</tr>
</table>
</div>




EOD;
if($path=="../")
	$header="";
?>