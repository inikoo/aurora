{include file='header.tpl'}
<div id="bd" >
<div class="search_box" style="margin-top:15px">
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

{foreach from=$splinters key=key item=splinter}

{include file=$splinter.tpl index=$splinter.index}
{/foreach}


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
