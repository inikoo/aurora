{include file='header.tpl'}
<div id="bd" style="padding:0px" >
<div style="padding:0 20px">
<div class="branch" style="width:280px;float:left;margin:0"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a> &rarr; <a  href="reports.php">{t}Reports{/t}</a> &rarr; {t}Pickers & Packers{/t}</span>
</div>
	{include file='calendar_splinter.tpl'} 


<h1 style="clear:left">{$title}</h1>

</div>
 <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='pickers'}selected{/if}" onclick="window.location='report_pp.php?tipo={$ref_tipo}&view=pickers'" id="pickers">  <span> {t}Pickers{/t}</span></span></li>
    <li> <span class="item {if $block_view=='packers'}selected{/if}" onclick="window.location='report_pp.php?tipo={$ref_tipo}&view=packers'"  id="packers">  <span> {t}Packers{/t}</span></span></li>
 
  </ul>
  
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px;padding-bottom:30px">


 <div class="data_table" style="clear:both;margin-top:20px">
	<span id="table_title" class="clean_table_title">{t}Packers{/t}</span>
     {include file='table_splinter.tpl' table_id=1 filter_name='' filter_value='' no_filter=true  }
	<div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>

</div>

</div>
{include file='footer.tpl'}

