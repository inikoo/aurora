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
<h1>{t}Wellcome to{/t} {$my_name} {t}supplier's page{/t}</h1>

<div style="clear:both">

    <ul style="padding-left:10px">
    <li>Explore how your products are performing</li>
    <li>Forecast future sales</li>
    <li>Be in direct comunication with our buying department.</li>
    </ul>

</div>



</div>
{include file='footer.tpl'}
