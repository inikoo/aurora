{include file='header.tpl'}
<div id="bd" >



  <div id="control_panel" class="yui-b">

 <div style="text-align:right">
	<span class="state_details" id="continue_later"><a href="order.php?id={$delivery_note->id}">Continue Later</a></span>
	<span class="state_details" id="finish" style="margin-left:20px">Finish</span>

      </div>
    <div class="yui-b" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 10px 0;xheight:15em">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Picking for Delivery Note{/t} {$delivery_note->get('Delivery Note ID')}</h1>
        <h2 style="padding:0">{$delivery_note->get('Delivery Note Customer Name')} ({$customer->get_formated_id()}) {$delivery_note->get('Delivery Note Country 2 Alpha Code')}</h2>
      
	
	
<div style="clear:both"></div>
       </div>




          <div style="border:0px solid #ddd;width:430px;float:right;xdisplay:none">
          
	 <table border=0  style="width:100%;xborder-top:1px solid #333;xborder-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >
	  
	
<tbody id="resend" style="">	   
		        <tr><td  class="aright" >{t}Picker{/t}:</td><td id="Resend_Distinct_Products"  class="aright">{$delivery_note->get('Delivery Note XHTML Pickers')}</td></tr>

	        <tr><td  class="aright" >{t}Transactions{/t}:</td><td  class="aright"><span id="number_picked_transactions">{$delivery_note->get_number_picked_transactions()}</span>/<span id="number_picked_transactions">{$delivery_note->get_number_transactions()}</span> <span style="margin-left:10px">{$delivery_note->get('Faction Picked')}</span></td></tr>
	
</tbody>

	   
	   

	   
	 </table>
       </div>

      
       
       <div style="clear:both"></div>
      </div>
    <div class="data_table"  style="clear:both">
	<span id="table_title" class="clean_table_title">{t}Items{/t}</span>
	<div id="table_type">
	  <span id="set_as_all_picked" style="float:right;color:brown" class="table_type state_details ">{t}Set as all Picked{/t}</span>
	 
	</div>
<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0" style="font-size:85%"  class="data_table_container dtable btable "> </div>
</div>


</div>




  </div>
</div>
</div> 
{include file='footer.tpl'}
