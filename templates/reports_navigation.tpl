<dl class="dropdown">
  <dt style="width:89px" id="one-ddheader" onmouseover="ddMenu('one',1)" onmouseout="ddMenu('one',-1)" onclick="window.location='reports.php'" >{t}General Index{/t}</dt>
  <dd id="one-ddcontent" onmouseover="cancelHide('one')" onmouseout="ddMenu('one',-1)">
    <ul>
      
   {foreach from=$report_index  item=data key=key}
      {foreach from=$data.reports  item=report }

   <li><a href="{$report.url}">{$report.title}</a></li>
      {/foreach}

   {/foreach}

    </ul>
  </dd>
</dl>



<div class="search_box">
  <div class="general_options">
{foreach from=$general_options_list item=options }
{if $options.tipo=="url"}
 <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
{else}
 <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
{/if}
{/foreach}
</div>
 
</div>  


