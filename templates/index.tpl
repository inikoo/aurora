{include file='header.tpl'}
<div id="bd" style="padding:0px 20px">



<div  class="branch" style="clear:left;"> 
  <span >{t}Dashboard{/t}</span>
</div>

<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
    <div class="buttons" style="float:right">
       
        <button  onclick="window.location='dashboard_configuration.php'" ><img src="art/icons/cog.png" alt=""> {t}Configure Dashboard{/t}</button>
      
    </div>
    <div class="buttons" style="float:left">
    </div>
    <div style="clear:both"></div>
</div>



<div class="dashboard_blocks" style="margin-top:20px">
	{foreach from=$blocks key=key item=block}
	    <div class="{$block.class}">
	    <iframe  onLoad="changeHeight(this);"  id="block_{$block.key}" src="{$block.src}&block_key={$block.key}" width="100%" frameborder=0  scrolling="no">
            <p>Your browser does not support iframes.</p>
        </iframe> 
	    </div>
	{/foreach}
</div>







</div>
{include file='footer.tpl'}
