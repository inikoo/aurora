{include file='header.tpl'}
<div id="bd" >
 <span class="nav2 onleft"><span   id="orders" {if $view=='orders'}class="selected"{/if} >{t}Orders{/t}</span></span>
  <span class="nav2 onleft"><span id="invoices" {if $view=='invoices'}class="selected"{/if} >{t}Invoices{/t}</span></span>
  <span class="nav2 onleft"><span  id="dn"  {if $view=='dn'}class="selected"{/if} >{t}Delivery Notes{/t}</span></span>
  <span class="nav2 onleft"><a id="warehouse_operations" href="warehouse_orders.php">{t}Warehouse Operations{/t}</a></span>


 <div style="float:right;border: 0px solid #ddd;text-align:right;padding:10px">
    <form  id="prod_search_form" action="orders.php" method="GET" >
      <label>{t}Order Search{/t}:</label><input size="12" class="text search" id="prod_search" value="" name="search"/><img onclick="document.getElementById('prod_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
    </form>
 

  </div>



  <div  id="orders_table" class="data_table" style="{if $view!='orders'}display:none{/if};clear:both">
    <span class="clean_table_title">{t}Order List{/t}</span>
    
    <div id="table_type" class="table_type">
    <a  style="float:right"  class="table_type state_details"  href="orders_csv.php" >{t}Export (CSV){/t}</a>
     </div>
     
    
     <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      
      <div style="">
   <table  style="float:left;margin:0 0 0 0px ;padding:0;clear:left"  class="options_mini" >
     <tr  id="orders_show_only"  style="display:{if $view!='orders'}none{/if}"  >
       <td  style="xmargin:5px 15px 0 0px ;padding:0;border:none;color:#555"  >{t}show only{/t}:</td>
       
       <td  style="" {if $dispatch=='in_process'}class="selected"{/if}  id="in_process"  >{t}In Process{/t}</td>
       <td  style="" {if $dispatch=='dispached'}class="selected"{/if}  id="dispached"  >{t}Dispached{/t}</td>
       <td  style="" {if $dispatch=='cancelled'}class="selected"{/if}  id="cancelled"  >{t}Cancelled{/t}</td>
       <td  style="" {if $dispatch=='unknown'}class="selected"{/if}  id="unknown"  >{t}Unknown{/t}</td>

     </tr>
   

   </table>
</div>
    <div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">  
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px"><span id="clear_interval" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}</span> {t}Interval{/t}: <input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	<img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_interval"  xonclick="document.forms[1].submit()" alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
      </div>
      

    </div>
    
    
   
     {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

    <div  id="table0"   class="data_table_container dtable btable "> </div>
  
  
  </div>
  
  
   <div  id="invoices_table"   class="data_table" style="{if $view!='invoices'}display:none{/if};clear:both">
    <span class="clean_table_title">{t}Invoice List{/t}</span>
      
    <div id="table_type" class="table_type">
    <a  style="float:right"  class="table_type state_details"  href="invoices_csv.php" >{t}Export (CSV){/t}</a>
     </div>
    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <div style="">
   <table  style="float:left;margin:0 0 0 0px ;padding:0;clear:left"  class="options_mini" >
     <tr  id="orders_show_only"   >
       <td  style="xmargin:5px 15px 0 0px ;padding:0;border:none;color:#555"  >{t}show only{/t}:</td>
       
       <td  style="" {if $invoice_type=='refunds'}class="selected"{/if}  id="refunds"  >{t}Refunds{/t}</td>
     
     </tr>
   

   </table>
</div>
    <div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">  
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px">
      <span id="clear_intervali" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}
      </span> {t}Interval{/t}: <input id="v_calpop1i" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/>
      <img   id="calpop1i" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
      <span class="calpop">&rarr;</span> 
      <input   class="calpop" id="v_calpop2i" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/>
      <img   id="calpop2i" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	<img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_intervali"  xonclick="document.forms[1].submit()" alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1iContainer" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2iContainer" style="display:none; z-index:2;position:absolute"></div></div>
      </div>
    
    </div>
    
    
    
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

   
    
    <div  id="table1"   class="data_table_container dtable btable "> </div>
 
</div>

 <div   id="dn_table"  class="data_table" style="{if $view!='dn'}display:none{/if};clear:both">
    <span class="clean_table_title">{t}Delivery Note List{/t}</span>
    
        <div id="table_type" class="table_type">
    <a  style="float:right"  class="table_type state_details"  href="dn_csv.php" >{t}Export (CSV){/t}</a>
     </div>
    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <div style="">
   
</div>
    <div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">  
    <form action="orders.php?" method="GET" style="margin-top:10px">
      <div style="position:relative;left:18px">
      <span id="clear_intervaldn" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}
      </span> {t}Interval{/t}: <input id="v_calpop1dn" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/>
      <img   id="calpop1dn" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
      <span class="calpop">&rarr;</span> 
      <input   class="calpop" id="v_calpop2dn" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/>
      <img   id="calpop2dn" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	<img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" style="cursor:pointer" id="submit_intervaldn"   alt="{t}Go{/t}" /> 
      </div>
    </form>
    <div id="cal1dnContainer" style="position:absolute;display:none; z-index:2"></div>
    <div style="position:relative;right:-80px"><div id="cal2dnContainer" style="display:none; z-index:2;position:absolute"></div></div>
      </div>
    
    </div>

    
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }

    
   
    <div  id="table2"   class="data_table_container dtable btable "> </div>
 

  
  
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
