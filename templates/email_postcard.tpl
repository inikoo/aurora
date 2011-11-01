<html>
{if $edit}
<head>
  {foreach from=$css_files item=i }
    <link rel="stylesheet" href="{$i}" type="text/css" />
    {/foreach}	
    {foreach from=$js_files item=i }
    <script type="text/javascript" src="{$i}"></script>
    {/foreach}
    <script type="text/javascript">{$script}</script>
</head>

{literal}

<STYLE>


 .target.over {color:#000;border:1px solid #777;font-weight:800}
.target {border:1px solid #ccc;padding:0 20px;color:#777;text-align:center}
.email_paragraph{border:1px solid #fff;cursor:pointer}
div.email_paragraph:hover{border:1px solid #ddd;}

 
</STYLE>
{/literal}

{/if}
{literal}
<STYLE>

 a { color:#{/literal}{$color_scheme.Link_Container}{literal} }
.footer a{ color:#{/literal}{$color_scheme.Link_Footer}{literal} }
.header a{ color:#{/literal}{$color_scheme.Link_Header}{literal} }

</STYLE>
{/literal}
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor='#{$color_scheme.Background_Body}' class="yui-skin-sam" >



<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor='#{$color_scheme.Background_Body}' >

<tr>
<td valign="top" align="center">


<table class="header" width="600" cellpadding="0" cellspacing="0">

<tr>
<td style="background-color:#{$color_scheme.Background_Header};border-bottom:0px solid #{$color_scheme.Background_Container};text-align:center;" align="center"><span style="font-size:10px;color:#{$color_scheme.Text_Header};line-height:200%;font-family:verdana;text-decoration:none;">Email not displaying correctly? <a href="*|ARCHIVE|*" style="font-size:10px;color:#{$color_scheme.Link_Header};line-height:200%;font-family:verdana;text-decoration:none;">View it in your browser.</a></span></td>
</tr>
 
<tr>
<td   style="color:#{$color_scheme.Text_Header};background-color:#{$color_scheme.Background_Header};border-bottom:6px solid #{$color_scheme.Background_Container};"><center><IMG  id="header_image"  SRC="{$header_src}" WIDTH=600 BORDER="0" title="{$store->get('Store Name')}"  alt="{$store->get('Store Name')}" align="center"></center></td>
</tr>

<tr>
<td style="background-color:#FFFFFF;border-top:0px solid #FFFFFF;border-bottom:0px solid #333333;"><center><a href="#"><img src="{$postcard_src}" width="600"  border="0" alt=""></a></center></td>
</tr>
</table>



<table width="600" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">


<tr>
<td bgcolor="#{$color_scheme.Background_Container}" valign="top" style="font-size:12px;color:#{$color_scheme.Text_Container};line-height:150%;font-family:trebuchet ms;">
<div>
%greeting%
</div>

{foreach from=$paragraphs item=paragraph key=paragraph_key name=foo}
{if $paragraph.type=='Main'}

{if $edit}
<div  class="target" style="display:none"  id="target{$paragraph_key}" >
{t}Move paragraph here{/t}
</div>
<div onClick="delete_paragraph({$paragraph_key})" onmouseout="Dom.setStyle('delete_paragraph{$paragraph_key}','display','none')"   onmouseover="Dom.setStyle('delete_paragraph{$paragraph_key}','display','')"  id="delete_paragraph{$paragraph_key}" style="cursor:pointer;float:right;padding:2px 10px;border:1px solid #ccc;display:none">{t}Delete{/t} <img style="vertical-align:text-bottom;" src="art/icons/cross.png"/></div>
<div onClick="edit_paragraph(this,{$paragraph_key})" onmouseout="Dom.setStyle('delete_paragraph{$paragraph_key}','display','none')"   onmouseover="Dom.setStyle('delete_paragraph{$paragraph_key}','display','')"  id="paragraph{$paragraph_key}"  class="email_paragraph" >
{/if}
<p>
<input type="hidden" id="paragraph_type{$paragraph_key}" value="Main">
<span style="font-size:20px;font-weight:bold;color:#{$color_scheme.H1};font-family:arial;line-height:110%;" id="paragraph_title{$paragraph_key}">{$paragraph.title}</span><br>
<span style="font-size:11px;font-weight:normal;color:#{$color_scheme.H2};font-style:italic;font-family:arial;" id="paragraph_subtitle{$paragraph_key}">{$paragraph.subtitle}</span><br>
<span style="color:#{$color_scheme.Text_Content};"  id="paragraph_content{$paragraph_key}" >
{$paragraph.content}
</span>
</p>
{if $edit}</div>{/if}

{/if}
{if $edit}
{if $smarty.foreach.foo.last}
<div class="target" style="display:none" id="target0">
{t}Move paragraph here{/t}
</div>
<div class="target"  onClick="new_paragraph('Main')" style="cursor:pointer;margin-top:3px"  id="add_paragraph">
{t}Add a paragraph{/t}
</div>
{/if}
{/if}
{/foreach}
</td>
</tr>

<tr>
<td class="footer" style="background-color:#{$color_scheme.Background_Footer};border-top:10px solid #{$color_scheme.Background_Container};" valign="top">
<span style="font-size:10px;color:#{$color_scheme.Text_Footer};line-height:100%;font-family:verdana;">
{$store->get('Store Description')} <br />
<br />
<a href="*|UNSUB|*">Unsubscribe</a> %email% from this list.<br />

{if $store->get('Store Address')}
<br />
Our mailing address is:<br />
{$store->get('Store Address')}<br />
{/if}
<br />
Our telephone:<br />
{$store->get('Store Telephone')}<br />
<br />
Copyright (C) {$smarty.now|date_format:'%Y'} {$store->get('Store Name')} All rights reserved.<br />
<br />
<a href="*|FORWARD|*">Forward</a> this email to a friend
  

</span>
</td>
</tr>

</table>












</td>
</tr>
</table>


{if $edit}
  <input type="hidden" id="email_campaign_key" value="{$email_campaign->id}" />
  <input type="hidden" id="email_content_key" value="{$email_content_key}" />

<div id="dialog_edit_paragraph" style='display:none;font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, sans-serif;width:509px;font-size:90%'>
  <div id="edit_paragraph_msg"></div>
  <input type="hidden" id="paragraph_key" value="">
    <input type="hidden" id="email_content_key" value="">
 
    <input type="hidden" id="paragraph_type" value="">

  <table style="padding:0px 10px;margin:5px 10px 5px 10px ;font-size:80%;width:490px" >
 
 <tr><td style="width:60px">{t}Title{/t}:</td><td><input id="paragraph_title" value="" style="width:100%;"/></td></tr>

 <tr><td>{t}Subtitle{/t}:</td><td><input id="paragraph_subtitle" value="" style="width:100%" /></td></tr>
    <tr><td colspan=2>
	<textarea style="width:100%;height:110px;font-size:120%" id="paragraph_content" ></textarea>
      </td>
    <tr>



    <tr class="buttons" style="height:60px;font-size:100%;dispay:block;margin-top:20px">
<td>
    <span   onclick="save_paragraph()" id="save_paragraph"  class="unselectable_text button"    >{t}Save{/t}</span></td></tr>
</td>
</table>
</div>





{/if}


</body>
</html>
