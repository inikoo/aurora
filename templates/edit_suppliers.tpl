{include file='header.tpl'}
<div id="bd" >

 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Edit Suppliers{/t}</h1>
  </div>

  <ul class="tabs" id="chooser_ul" style="clear:both">

    <li> <span class="item {if $edit=='suppliers'}selected{/if}"  id="suppliers">  <span> {t}Suppliers{/t}</span></span></li>
   
  </ul>

 <div class="tabbed_container" > 
 


  <div  class="edit_block" style="{if $edit!="suppliers"}display:none{/if}"  id="d_suppliers">
  
  <div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}Suppliers List{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" {if $products==0 }style="display:none"{/if}>
      <tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='products'}class="selected"{/if}  id="products"  >{t}Products{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
      </tr>
      </table>
    
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}

    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>

</div>

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
