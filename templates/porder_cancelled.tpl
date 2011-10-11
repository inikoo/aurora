{include file='header.tpl'}
<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" >

<div class="order_actions" style="text-align:left">
    <span class="state_details" onClick="location.href='supplier.php?id={$supplier->get('Supplier Key')}'" >{t}Return to Supplier Page{/t}</span>
</div>
<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px;">
    <table style="width:200px;color:#ccc;border-top: 1px solid #ccc" class="order_header" >
      <tr><td>{t}Goods{/t}:</td><td id="goods" class="aright">{$po->get('Items Net Amount')}</td></tr>
      <tr><td>{t}Shipping{/t}:</td><td class="aright" id="shipping"  >{$po->get('Shipping Net Amount')}</td></tr>
      <tr><td>{t}Tax{/t}:</td><td id="vat" class="aright"   >{$po->get('Total Tax Amount')}</td></tr>
      <tr><td>{t}Total{/t}</td><td id="total" class="stock aright ">{$po->get('Total Amount')}</td></tr>
    
    </table>
    <div style="border:0px solid red;xwidth:290px;float:right">
    <table  border=0  class="order_header"  style="margin-right:30px;float:right">
      <tr><td class="aright" style="padding-right:40px">{t}Created{/t}:</td><td>{$po->get('Creation Date')}</td></tr>
      <tr><td class="aright" style="padding-right:40px">{t}Submitted{/t}:</td><td>{$po->get('Submitted Date')}</td></tr>
      <tr><td colspan="2" class="aright">{t}via{/t} {$po->get('Purchase Order Main Source Type')} {t}by{/t} {$po->get('Purchase Order Main Buyer Name')}</td></tr>
      <tr><td class="aright" style="padding-right:40px">{t}Cancelled{/t}:</td><td>{$po->get('Cancelled Date')}</td></tr>

    </table>
    </div>    
    <h1 style="padding:0px 0 10px 0;width:300px;xborder:1px solid red" id="po_title">{t}Purchase Order{/t}: {$po->get('Purchase Order Public ID')}</h1>
    <table border=0 >
      <tr><td>{t}Purchase Order Id{/t}:</td><td class="aright">{$po->get('Purchase Order Key')}</td></tr>
      <tr><td>{t}Supplier{/t}:</td><td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td></tr>
      <tr><td>{t}Items{/t}:</td><td class="aright" id="distinct_products">{$po->get('Number Items')}</td></tr>
    </table>

  
    <table style="clear:both;border:none;" class="notes">
      
      <tr><td style="border:none">{t}Notes{/t}:</td><td>{$po->get('Purchase Order Cancel Note ')}</td></tr>
    </table>
    
  <div style="clear:both"></div>
  
</div>





<div id="the_table" class="data_table" style="margin:20px 0px;clear:both">
  <span class="clean_table_title">{t}Supplier Products{/t}</span>
  	<div id="table_type">
	  
	</div>



  <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <span   style="float:right;margin-left:20px;display:none" class="state_details" state="{$show_all}"  id="show_all"  atitle="{if !$show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}"  >{if $show_all}{t}Show only ordered{/t}{else}{t}Show all products available{/t}{/if}</span>     
      

      
      <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	<tr><td  class="selected"  id="general" >{t}Used In{/t}</td>

	</tr>
      </table>
    
    </div>

  
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div class="clean_table_filter" {if !$show_all}style="visibility:hidden"{/if} id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{t}Product Code{/t}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
    <div class="clean_table_controls" {if !$show_all}style="visibility:hidden"{/if}  id="clean_table_controls0" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"  style="font-size:80%" class="data_table_container dtable btable "> </div>
</div>

</div> 

<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>







{include file='footer.tpl'}

