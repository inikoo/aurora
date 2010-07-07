{include file='header.tpl'}
<div id="bd" style="padding:0px">

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
  
  <div id="search" style="margin:20px 20px 0 0;text-align:right;{if !$search_scope}display:none{/if}">
    <span class="search_title" >{t}{$search_label}{/t}:</span>
    <input size="25" class="text search" id="{$search_scope}_search" value="" state="" name="search"/><img align="absbottom" id="{$search_scope}_clean_search"  class="submitsearch" src="art/icons/zoom.png" >
    <div id="{$search_scope}_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="{$search_scope}_search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;left:-520px">
	<table id="{$search_scope}_search_results_table"></table>
      </div>
    </div>
  </div>
  
</div>  






<div style="clear:both">

{foreach from=$splinters key=key item=splinter}

{include file=$splinter.tpl index=$splinter.index}
{/foreach}

</div>
<div style="display:none">
<div id="message_list" style="width:700px;border:0px solid #777;margin-top:10px;padding:0 20px">
<table style="width:100%">
<tr><td style="width:100px">date</td><td>Abstract</td></tr>
</table>
</div>



{include file='widget.clock.tpl'}


<div style="float:left">
<img style="width:600px" src="art/home_baner_1.9.png">
</div>

<div style="clear:both">
<table>
<tr><td>Nov 4 2009</td><td>Problem Rendering the Close Button on Calendar Pop Out</td></tr>
<tr><td style="text-align:right">Jan   2010</td><td>Review Contact Deletion</td></tr>

<caption>{t}Known Issues{/t}</caption>
</table>
</div>
</div>
</div>
{include file='footer.tpl'}
