{include file='header.tpl'}
<div id="bd" style="padding:0 20px">
<h1>{t}Adding new customer{/t} ({$store->get('Store Code')})</h1>

<div style="clear:both;margin-top:0px;margin-right:0px;width:{if $options_box_width}{$options_box_width}{else}700px{/if};float:right;margin-bottom:10px" class="right_box">
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

<div id="yui-main" >
    
    
    
   { if $tipo=='company'}
    {include file='new_company_splinter.tpl'}
{else}
    {include file='new_contact_splinter.tpl'}

{/if}

      

    </div>
</div>
</div>
{include file='footer.tpl'}


