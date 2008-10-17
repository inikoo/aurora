{include file='header.tpl'}
<div id="bd" >
<span class="nav2"><a href="suppliers.php">{t}Suppliers{/t}</a></span>
<span class="nav2"><a href="suppliers.php">{t}Purchase Orders{/t}</a></span>
<span class="nav2"><a href="suppliers.php">{t}Delivery Notes{/t}</a></span>

 <div id="yui-main">
    <div class="yui-b" >
       <h1>{$name} <span style="color:SteelBlue">{$id}</span></h1> 
<table style="width:500px" border=0>

<tr>
{if $principal_address}<td valign="top">{$principal_address}</td>{/if}
<td  valign="top">
<table border=0 style="padding:0">
{if $contact}<tr><td colspan=2>{$contact}</td ></tr>{/if}
{foreach from=$telecoms item=telecom}
<tr><td >
{if $telecom[0]=='mob'}<img src="art/icons/phone.png"/ title="{t}Mobile Phone{/t}">
{elseif   $telecom[0]=='tel'}<img src="art/icons/telephone.png"/ title="{t}Telephone{/t}">
{elseif   $telecom[0]=='email'}<img src="art/icons/email.png"/ title="{t}Email Address{/t}">
{elseif   $telecom[0]=='fax'}<img src="art/icons/printer.png"/ title="{t}Fax{/t}">
{/if}
</td><td class="aright" style="padding-left:10px">{$telecom[1]}</td></tr>
{/foreach}
</table>
</td>
</tr>

</table>



      
 <div class="data_table" style="margin:25px 20px;">
    <span class="clean_table_title">{t}Products{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>


    </div>
  </div>
    <div class="yui-b">
    </div>

</div> 
{include file='footer.tpl'}

