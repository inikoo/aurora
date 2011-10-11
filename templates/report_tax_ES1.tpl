{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}

<div style="float:right;font-size:70%">
{t}Limit{/t}: <input type="text" id="limite" name="limite" size=10 value="{$umbral}"/>
{t}Year{/t}:  <input type="text" id="year" name="year" size=4 value="{$year}"/> <span class="state_details" style="font-size:100%;margin-left:10px" id="submit_report">Prepare Report</span>

</div>

  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>Modelo 347</h1>
  </div>
  <div id="info"  style="clear:left;margin-top:10px;padding:0 0px;width:770px;{if $details==0}display:none{/if}"></div>

    
    <div id="the_table" class="data_table" style="clear:both">
      <span class="clean_table_title">{$titulo}</span>
      
      
     <div id="table_type">
         <a  style="float:right"  class="table_type state_details"  href="report_tax_ES1_csv.php?umbral={$umbral}&year={$year}" >{t}Export (CSV){/t}</a>

     </div>
     
      
       <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  

      <div  class="clean_table_caption"  style="clear:both;margin-top:10px">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>

	<div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator"></span></div></div>

      </div>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
</div>


  </div>
</div>
</div> 


{include file='footer.tpl'}
