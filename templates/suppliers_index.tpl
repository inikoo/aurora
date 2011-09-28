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

<div style="border:1px solid #777;font-size:120%;width:700px;padding:20px;margin-top:30px">
$user->get('User Alias'),<br><br>
<p>
Please note that this is an experimental system and is not finish yet, if you find and error
or want a new feature please tell us and we will try our best to do it as soon as posible.
</p>
We are planning to release a commercial <i>- bug free & super cool-</i> product in January 2012.
</p>

Thanks<br>
Inikoo Ltd<br>
<br>
<a href="mailto:raul@inikoo.com">raul@inikoo.com</a><br>

</div>


</div>
{include file='footer.tpl'}
