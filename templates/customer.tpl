{include file='header.tpl'}
<div id="bd" >


<span class="nav2"><a href="customers.php">{$home}</a></span>


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



<div >
<h2 style="font-size:150%">Orders Overview</h2>
<table style="padding:0;margin:0;border-top:1px solid black;;border-bottom:1px solid black;width:500px">
	  <tr><td>
{if $invoices==1}
{$name} {t}has place one order of{/t} {$total_net}.  
{elseif $invoices>1 } 
{$name} {t}has placed{/t} <b>{$invoices}</b> {t}orders so far{/t}, {t}which amounts to a total of{/t} <b>{$total_net}</b> {t}plus tax{/t} ({t}an average of{/t} {$total_net_average} {t}per order{/t}).
{if $orders_interval}<br/>{t}This customer usually places an order every{/t} {$orders_interval}.{/if}
{/if}

</td></tr>


	</table>

</div>

      
 <div class="data_table" style="margin:25px 20px;">
    <span class="clean_table_title">{t}{$table_title}{/t}</span>
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

