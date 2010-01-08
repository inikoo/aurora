{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}


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

