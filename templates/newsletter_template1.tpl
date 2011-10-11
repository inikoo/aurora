{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">

{include file='marketing_navigation.tpl'}




 
<div style="clear:left;margin:0 0px">
    <h1>{t}Marketing{/t}</h1>
</div>

</div>
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item selected{if $view=='Emarketing'}selected{/if}" id="metrics"  ><span>  {t}Emarketing{/t}</span></span></li>
    <li> <span class="item {if $view=='newsletter'}selected{/if}"  id="newsletter">  <span> {t}eNewsletters{/t}</span></span></li>
    <li> <span class="item {if $view=='email'}selected{/if}"  id="email">  <span> {t}Email Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web_internal'}selected{/if}"  id="web_internal">  <span> {t}Site Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web'}selected{/if}"  id="web">  <span> {t}Internet Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='other'}selected{/if}"  id="other">  <span> {t}Other Media Campaigns{/t}</span></span></li>
</ul>
 <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_metrics" style="{if $view!='Emarketing'}display:block;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


<h2>Emarketing</h2>
<div class="table_top_bar"></div>
<table class="options" style="float: left; margin: 0pt 0pt 0pt 0px; padding: 0pt;">
	<tbody><tr><td id="create_list"><a href="#">Create List</a></td>
        <td id="view_list"><a href="customers_lists.php">View List</a></td>
	  <td id="create_campaign"><a href="new_campaign.php">Create Campaign</a></td>	  
          <td id="view_campaign" style="background-color:#7296E1;"><a style="color:#ffffff;" href="campaign_builder.php">View Campaign</a></td>	</tr>
      </tbody></table>



{literal}


<script language="javascript" src="js/customise_template_validation.js">
</script>



{/literal}


<div style="padding:5px 50px 50px 10px;width:690px">


<!-template code-!>



<h2 style="clear:both">{t}Newsletter1 Template Preview{/t} </h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
<div id="campaign_div">{$msg}</div>

{literal}
	<STYLE>
	 .headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
	 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
	 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
	 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
	 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
	 .defaultText { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
	 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
	 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
	 a { color:#FF6600; color:#FF6600; color:#FF6600; }
	</STYLE>
{/literal}


<form action="send_mail.php" name="newsletter1_form" id="newsletter1_form" method="POST">	
<table cellpadding="10" cellspacing="0" bgcolor='#99CC00'style="min-width:600px;" >
<tr>
<td valign="top" align="center">

<table width="600" cellpadding="0" cellspacing="0">


<tr>
<td align="left" valign="middle" style="background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;"><center><h1>{$header}</h1></center></td>
</tr>


</table>

<table width="600" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">
<tr>

<td width="200" valign="top" style="background-color:#FFFFFF;border-right:1px dashed #CCCCCC;text-align:left;">
<span style="font-size:11px;font-weight:normal;color:#999999;font-family:arial;line-height:150%;">

<span style="font-size:15px;font-weight:bold;color:#222222;font-family:arial;line-height:150%;"></span>

{$block3}



</span>
</td>


<td bgcolor="#FFFFFF" valign="top" width="400" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">

<p>
<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;">{$contenttitle}</span><br></p>
<p style="width:450px;">
{$block1}
</p>
<p class="basic_template_img"><img height="107" width="350" src="{$image1}"></p>


<p style="width:450px;">

{$block2}
</p>
<p class="basic_template_img"><img height="107" width="350" src="{$image2}"></p>
</td>


</tr>

<tr>
<td style="background-color:#FFFFCC;border-top:10px solid #FFFFFF;" valign="top" colspan="2">
<span style="font-size:10px;color:#996600;line-height:100%;font-family:verdana;">
*|LIST:DESCRIPTION|* <br />

<br />
<a href="*|UNSUB|*">Unsubscribe</a> *|EMAIL|* from this list.<br />
<br />
Our mailing address is:<br />
*|LIST:ADDRESS|*<br />
<br />
Our telephone:<br />
*|LIST:PHONE|*<br />
<br />
Copyright (C) 2007 *|LIST:COMPANY|* All rights reserved.<br />

<br />
<a href="*|FORWARD|*">Forward</a> this email to a friend
  
</span>
</td>
</tr>

</table>



</td>
</tr>
</table>
    <input type="submit" name="send_mail" value="Send Mail">
	<input type="hidden" name="template" value="newsletter1">
</form>
</div> 

















<!-/template code-!>

</div> 










</div>
<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


  <span   class="clean_table_title" >{t}Email Campaigns{/t}</span>


  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    
   
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0  }
<div  id="table0"   class="data_table_container dtable btable"> </div>


</div>
<div id="block_web_internal" style="{if $view!='web_internal'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_web" style="{if $view!='web'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_other" style="{if $view!='other'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
</div>

{include file='footer.tpl'}

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
