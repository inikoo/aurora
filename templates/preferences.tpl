{include file='header.tpl'}
<div id="bd" >
<input id="user_key" value="{$user->id}" type="hidden"  />
<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  Preferences</span>
</div>



<h1><span class="id">Inikoo</span>, {t}System Preferences{/t}</h1>

<ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $view=='color'}selected{/if}"  id="details">  <span> {t}Color Scheme{/t}</span></span></li>
	
  </ul>
<div class="tabbed_container" >
<div  class="edit_block" style="{if $view!="color"}display:none{/if}"  id="d_color">

<h2>{t}Colours{/t}</h2>


<div class="buttons left">
    {foreach  from=$themes item=theme}
        <div class="theme_{$theme.key}" style="border:1px solid #ccc;float:left;padding:10px 20px;margin-right:10px;width:90px">
            <button onClick="change_theme({$theme.key})" class="theme_{$theme.key}">{$theme.name}</button>
        </div>
    {/foreach}
    <div style="clear:both"></div>
</div>

<h2 style="margin-top:20px;clear:both">{t}Backgrounds{/t}</h2>
<div class="buttons left" style="margin-top:10px">
    {foreach  from=$backgrounds item=background}
    <div style="width:90px;text-align:right;float:left;margin-right:10px;width:130px">
        <div onClick="change_background_theme({$background.key})" class="theme_background_{$background.key}" style="background-size: 100%;border:1px solid #ccc;width:100%;height:80px;cursor:pointer">
        </div>
        <span>{$background.name}</span>
        </div>
    {/foreach}
    <div style="clear:both"></div>
</div>



<div style="clear:both"></div>



</div>
</div>



</div>
{include file='footer.tpl'}
