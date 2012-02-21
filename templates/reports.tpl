{include file='header.tpl'}
<div id="bd" >
<div class="branch" style="width:280px;float:left;margin:0"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; <a  href="reports.php">{t}Reports{/t}</a>
</div>

{foreach from=$report_index item=report_category}

  
<div class="block_list" style="clear:both;">
<h2>{$report_category.title}</h2>
{foreach from=$report_category.reports item=report}
<div style="background-image:url('{$report.snapshot}');background-repeat:no-repeat;background-position:center 26px;" onClick="location.href='{$report.url}'">{$report.title}</div>

{/foreach}
</div>
{/foreach}





  </div> 
 

{include file='footer.tpl'}

