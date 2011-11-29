<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{$title}</title>
    <link href="art/inikoo_logo.png" rel="shortcut icon" type="image/x-icon" />
    {foreach from=$css_files item=i }
    <link rel="stylesheet" href="{$i}" type="text/css" />
    {/foreach}	

    <link rel="stylesheet" href="css/print.css" type="text/css" media="print"/>

    {foreach from=$js_files item=i }
    <script type="text/javascript" src="{$i}"></script>
    {/foreach}        
  </head>

  <body  class=" yui-skin-sam inikoo">
    <div id="doc4" >
      <div id="hd" >
      <div class="top_navigation">
  	            <div id="top_navigation_timezone"  class='timezone'>{$timezone}</div>
  	            <div id="top_navigation_right_block" >
  	                <span id="top_navigation_message"></span>
  	                <a id="top_navigation_help" href="help.php?page={$page_name}" style="margin-left:20px;margin-right:15px">{t}Help{/t}</a>
  	                <span id="language_flag"><img src="art/flags/{$lang_country_code}.gif" alt="{$lang_country_code}"  /></span>
                    <span>{t}Hello{/t} {$user->get('User Alias')}</span>
  	                <a style="margin-left:20px;" href="index.php?logout=1">{t}Logout{/t}</a>
	                <a href="preferences.php"><img id="top_navigation_preferences" src="art/icons/cog.png" /></a>
                </div>
	        <div style="clear:both"></div>
	        
	      </div>
	      	    <img src="art/inikoo_logo_small.png" style="position:absolute;margin-left:20px;margin-top:2px;height:34px"/>

	    <div class="buttons menu">
	    
	    {foreach from=$nav_menu item=menu }
	            <button onClick="window.location='{$menu[1]}'" class="header {if $menu[2]==$parent} selected{/if}">{$menu[0]}</button>
	            {/foreach}
	            <div style="clear:both"></div>
	    </div>

      </div> 
      
