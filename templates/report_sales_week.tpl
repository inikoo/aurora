{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}
<input type="hidden" value="{$store->id}" id="store_key">
<div style="float:right;font-size:90%;margin-top:3px">
 <span id="last_week"  class="state_details {if  $quick_period=='last_week'}selected{/if}" style="margin-left:10px">{t}Last week{/t}</span>

 <span id="this_week"  class="state_details {if  $quick_period=='this_week'}selected{/if}" style="margin-left:10px">{t}This week{/t}</span>
 <span id="other_date"  class="state_details {if  $quick_period=='other'}selected{/if}" style="margin-left:10px">{t}Other Dates{/t}</span>





</div>

  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1 style="padding-bottom:0;margin-bottom:0">{$subtitle1}</h1><h2 style="padding-top:0;margin-bottom:0">{$subtitle2}</h2>
  </div>
  <div id="info"  style="clear:left;margin-top:10px;padding:0 0px;width:770px;">
  
  <table class="report_sales1">
    <tr class="title"><td></td><td>{t}Sales Net{/t}</td><td>{t}Invoices{/t}</td><td>{t}Average Value{/t}</td></tr>

  <tr><td>{t}Monday{/t}</td><td>{$data[0].sales}</td><td>{$data[0].invoices}</td><td>{$data[0].avg}</td></tr>
    <tr><td>{t}Tuesday{/t}</td><td>{$data[1].sales}</td><td>{$data[1].invoices}</td><td>{$data[1].avg}</td></tr>
  <tr><td>{t}Wednesday{/t}</td><td>{$data[2].sales}</td><td>{$data[2].invoices}</td><td>{$data[2].avg}</td></tr>
  <tr><td>{t}Thursday{/t}</td><td>{$data[3].sales}</td><td>{$data[3].invoices}</td><td>{$data[3].avg}</td></tr>
  <tr><td>{t}Friday{/t}</td><td>{$data[4].sales}</td><td>{$data[4].invoices}</td><td>{$data[4].avg}</td></tr>
  {if $data[5].invoices}
    <tr><td>{t}Saturday{/t}</td><td>{$data[5].sales}</td><td>{$data[5].invoices}</td><td>{$data[5].avg}</td></tr>
{/if}
  {if $data[6].invoices}
  <tr><td>{t}Sunday{/t}</td><td>{$data[6].sales}</td><td>{$data[6].invoices}</td><td>{$data[6].avg}</td></tr>
{/if}
  <tr class="total"><td>{t}Total{/t}</td><td>{$data[7].sales}</td><td>{$data[7].invoices}</td><td>{$data[7].avg}</td></tr>

  </table>
  </div>

    
  
  
    <span class="clean_table_title">{t}Invoices{/t} <img id="export_csv1"   tipo="stores" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
  


    <div id="list_options0"> 
    
    
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
   
    
    
    </div>
    
    
    
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

   
    
    <div  id="table1"   class="data_table_container dtable btable"> </div>
 

  
  
</div>


  </div>
</div>
</div> 

<div id="dialog_other_date" >
<div style="padding:25px 5px 10px 5px">
{t}Other date{/t}:<input type="text" id="date" name="date" />
<div style="margin-top:10px">
<span class="button"  id="submit_report">{t}Change date{/t}</span>
</div>
</div>
</div>


{include file='footer.tpl'}
