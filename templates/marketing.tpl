{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">
{include file='marketing_navigation.tpl'}




 
<div style="clear:left;margin:0 0px">
    <h1>{t}Marketing{/t}</h1>
</div>

</div>
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='metrics'}selected{/if}" id="metrics"  ><span>  {t}Metrics{/t}</span></span></li>
    <li> <span class="item {if $view=='newsletter'}selected{/if}"  id="newsletter">  <span> {t}eNewsletters{/t}</span></span></li>
    <li> <span class="item {if $view=='email'}selected{/if}"  id="email">  <span> {t}Email Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web_internal'}selected{/if}"  id="web_internal">  <span> {t}Site Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web'}selected{/if}"  id="web">  <span> {t}Internet Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='other'}selected{/if}"  id="other">  <span> {t}Other Media Campaigns{/t}</span></span></li>
</ul>
 <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div id="block_metrics" style="{if $view!='metrics'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_web_internal" style="{if $view!='web_internal'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_web" style="{if $view!='web'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_other" style="{if $view!='other'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>

</div>
{include file='footer.tpl'}
