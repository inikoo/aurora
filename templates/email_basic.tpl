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
{/if}
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor='#666666' class="yui-skin-sam" >

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
 {if $edit}
 .target.over {color:#000;border:1px solid #777;font-weight:800}
.target {border:1px solid #ccc;padding:0 20px;color:#777;text-align:center}
.email_paragraph{border:1px solid #fff;cursor:pointer}
div.email_paragraph:hover{border:1px solid #ddd;}
 {/if}
 
</STYLE>
{/literal}



<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor='#666666' >
<tr>
<td valign="top" align="center">


<table width="550" cellpadding="0" cellspacing="0">

<tr>
<td style="background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;" align="center"><span style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">Email not displaying correctly? <a href="*|ARCHIVE|*" style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">View it in your browser.</a></span></td>
</tr>
 
<tr>
<td   style="background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;"><center><IMG   id=editableImg1 SRC="{$header_src}" BORDER="0" title="{$store->get('Store Name')}"  alt="{$store->get('Store Name')}" align="center"></center></td>
</tr>


</table>



<table width="550" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">


<tr>
<td bgcolor="#FFFFFF" valign="top" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">
<div>
Dear {$customer->get('Customer Main Contact Name')}{if $customer->get('Customer Type')=='Company'}, {$customer->get('Customer Name')}{/if}
</div>

{foreach from=$paragraphs item=paragraph key=paragraph_key name=foo}
{if $paragraph.type=='Main'}

{if $edit}
<div  class="target" style="display:none"  id="target{$paragraph_key}" >
{t}Move paragraph here{/t}
</div>
<div onClick="delete_paragraph({$paragraph_key})" onmouseout="Dom.setStyle('delete_paragraph{$paragraph_key}','display','none')"   onmouseover="Dom.setStyle('delete_paragraph{$paragraph_key}','display','')"  id="delete_paragraph{$paragraph_key}" style="cursor:pointer;float:right;padding:2px 10px;border:1px solid #ccc;display:none">{t}Delete{/t} <img style="vertical-align:text-bottom;" src="art/icons/cross.png"/></div>
<div onClick="edit_paragraph(this,{$paragraph_key})" onmouseout="Dom.setStyle('delete_paragraph{$paragraph_key}','display','none')"   onmouseover="Dom.setStyle('delete_paragraph{$paragraph_key}','display','')"  id="paragraph{$paragraph_key}"  class="email_paragraph" >{/if}
<p>
<input type="hidden" id="paragraph_type{$paragraph_key}" value="Main">
<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;" id="paragraph_title{$paragraph_key}">{$paragraph.title}</span><br>
<span style="font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;" id="paragraph_subtitle{$paragraph_key}">{$paragraph.subtitle}</span><br>
<span  id="paragraph_content{$paragraph_key}" >
{$paragraph.content}
</span>
</p>
{if $edit}</div>{/if}

{/if}

{if $smarty.foreach.foo.last}
<div class="target" style="display:none" id="target0">
{t}Move paragraph here{/t}
</div>
<div class="target"  onClick="new_paragraph('Main')" style="cursor:pointer;margin-top:3px"  id="add_paragraph">
{t}Add a paragraph{/t}
</div>
{/if}

{/foreach}
</td>
</tr>

<tr>
<td style="background-color:#FFFFCC;border-top:10px solid #FFFFFF;" valign="top">
<span style="font-size:10px;color:#996600;line-height:100%;font-family:verdana;">
{$store->get('Store Description')} <br />
<br />
<a href="*|UNSUB|*">Unsubscribe</a> {$customer->get('Customer Main Plain Email')} from this list.<br />

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


<div id="dialog_change_header_image" style='font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, sans-serif;width:509px;font-size:90%'>
  <div id="change_header_image_msg"></div>
  <div style="text-align:right;padding:5px 10px 0 0;font-size:75% ">
    <span  class="unselectable_text button">{t}Add Image{/t}</span>
  </div>

<div style="padding: 0 15px;">
	<div id="fileProgress" style="border: black 1px solid; width:300px; height:40px;float:left">
		<div id="fileName" style="text-align:center; margin:5px; font-size:15px; width:290px; height:25px; overflow:hidden"></div>
		<div id="progressBar" style="width:300px;height:5px;background-color:#CCCCCC"></div>
	</div>
<div id="uploaderUI" style="width:70px;height:16px;margin-left:5px;float:left;cursor:pointer"></div>
<div class="uploadButton" style="float:left"><a class="rolloverButton disabled" href="#" onClick="upload(); return false;"></a></div>
<div class="clearButton" style="float:left"><a class="rolloverButton disabled" href="#" onClick="handleClearFiles(); return false;"></a></div>
</div>
<div style="clear:both"></div>
  <div  style="font-size:80%;padding:10px 15px 10px 15px;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Header Image List{/t}</span>
            {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
            <div  id="table0"   class="data_table_container dtable btable "> </div>
        </div>
    </div>

 
 
 
</div>


{/if}


</body>
</html>
