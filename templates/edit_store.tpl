{include file='header.tpl'}
<div id="bd" >
<div class="search_box" style="margin-top:15px">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
        {if $options.tipo=="url"}
            <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
        {else}
            <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
        {/if}
    {/foreach}
    </div>
</div>
<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Store{/t}: <span id="title_name">{$store->get('Store Name')}</span> (<span id="title_code">{$store->get('Store Code')}</span>)</h1>
</div>
  <div id="msg_div"></div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='campaigns'}selected{/if}"  id="campaigns">  <span> {t}Campaings{/t}</span></span></li>
    <li> <span class="item {if $edit=='discounts'}selected{/if}"  id="discounts">  <span> {t}Deals{/t}</span></span></li>

    <li> <span class="item {if $edit=='charges'}selected{/if}"  id="charges">  <span> {t}Charges{/t}</span></span></li>
    <li> <span class="item {if $edit=='shipping'}selected{/if}"  id="shipping">  <span> {t}Shipping{/t}</span></span></li>
    
      <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='departments'}selected{/if}" id="departments"  ><span> {t}Departments{/t}</span></span></li>
    <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
  </ul>
  
  <div class="tabbed_container" > 
    <div id="info_name" style="margin-left:20px;float:left;width:260px;{if !( $edit=='pictures' or $edit=='charges' )  }display:none{/if}">
      <table    class="show_info_product">
	<tr>
	  <td>{t}Store Code{/t}:</td><td  class="aright">{$store->get('Store Code')}</td>
	</tr>
	<tr>
	  <td>{t}Store Name{/t}:</td><td  class="aright">{$store->get('Store Name')}</td>
	</tr>
	
	
	
      </table>
    </div>
    <div  class="edit_block" style="{if $edit!="description"}display:none{/if}"  id="d_description">
      
     
	 <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_store" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_store" class="state_details">{t}Reset{/t}</span>
	
      </div>
	
	
     
	
	
      <table style="margin:0;clear:both" class="edit" border=0>
	<tr><td class="label" >{t}Store Code{/t}:</td><td>
		 <div  style="width:15em" >

	      <input  
		 id="code" 
		 
		 name="code" 
		 changed=0 
		 type='text' 
		 class='text' 
		 style="width:15em" 
		 MAXLENGTH="16" 
		 value="{$store->get('Store Code')}" 
		 ovalue="{$store->get('Store Code')}"  
		 />
		 <div id="code_Container" style="" ></div>
         </div>
	    </td>
	     <td id="code_msg" class="edit_td_alert" style="width:300px"></td>

	  </tr>
	  <tr><td class="label">{t}Store Name{/t}:</td><td>
	  <div  style="width:30em" >
	      <input   
		 id="name" 
		 name="name" 
		 changed=0 
		 type='text'  
		 MAXLENGTH="255" 
		 style="width:30em"  
		 class='text' 
		 value="{$store->get('Store Name')}"  
		  ovalue="{$store->get('Store Name')}"  
		 />
		 <div id="name_Container" style="" ></div>
         </div>
	    </td>
	     <td id="name_msg" class="edit_td_alert" style="width:300px"></td>
		 
	    
	  </tr>
	</table>
      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="pictures"}display:none{/if}"  id="d_pictures">
	
      </div>
       <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="discounts"}display:none{/if}"  id="d_discounts">
	


	 <div  class="new_item_dialog"  id="new_deal_dialog" style="display:none">
	   <div id="new_deal_messages" class="messages_block"></div>
	   


	   <table class="edit" >
	     <tr><td>{t}Deal Name{/t}:</td><td><input  id="new_deal_name" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)"  changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	     <tr><td>{t}Deal Description{/t}:</td><td><input   id="new_deal_description" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
	     </tr>
	  </table>
	 </div>
	 
	 <div   class="data_table" sxtyle="margin:25px 10px;">
	   <span class="clean_table_title">{t}Deals{/t}</span>
	  <table class="options" style="float:right;padding:0;margin:0">
	    <tr>
	      <td  id="add_deal">Add Deal</td>
	      <td  style="display:none" id="save_new_deal">Save New Deal</td>
	      <td  style="display:none" id="cancel_add_deal">Cancel</td>
	    </tr>
	  </table>
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info4" class="clean_table_info"><span id="rtext4"></span> <span class="rtext_rpp" id="rtext_rpp4"></span> <span class="filter_msg"  id="filter_msg4"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter4"><div class="clean_table_info"><span id="filter_name4">{$filter_name4}</span>: <input style="border-bottom:none" id='f_input4' value="{$filter_value0}" size=10/><div id='f_container4'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator4"></span></div></div>
	  </div>
	  <div  id="table4"   class="data_table_container dtable btable "> </div>
	 </div>
      </div>

       <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="campaigns"}display:none{/if}"  id="d_campaigns">
	 <div  class="new_item_dialog"  id="new_campaign_dialog" style="display:none">
	   <div id="new_campaign_messages" class="messages_block"></div>
	   <table class="edit" >
	     <tr><td>{t}Campaign Name{/t}:</td><td><input  id="new_campaign_name" onKeyUp="new_campaign_changed(this)"    onMouseUp="new_campaign_changed(this)"  onChange="new_campaign_changed(this)"  changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	     <tr><td>{t}Campaign Description{/t}:</td><td><input   id="new_campaign_description" onKeyUp="new_campaign_changed(this)"    onMouseUp="new_campaign_changed(this)"  onChange="new_campaign_changed(this)" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
	     </tr>
	  </table>
	 </div>
	 
	 <div   class="data_table" sxtyle="margin:25px 10px;">
	   <span class="clean_table_title">{t}Campaigns{/t}</span>
	  <table class="options" style="float:right;padding:0;margin:0">
	    <tr>
	      <td  id="add_campaign">Add Campaign</td>
	      <td  style="display:none" id="save_new_campaign">Save New Campaign</td>
	      <td  style="display:none" id="cancel_add_campaign">Cancel</td>
	    </tr>
	  </table>
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info3" class="clean_table_info"><span id="rtext3"></span> <span class="rtext_rpp" id="rtext_rpp3"></span> <span class="filter_msg"  id="filter_msg3"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter3"><div class="clean_table_info"><span id="filter_name3">{$filter_name3}</span>: <input style="border-bottom:none" id='f_input3' value="{$filter_value0}" size=10/><div id='f_container3'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator3"></span></div></div>
	  </div>
	  <div  id="table3"   class="data_table_container dtable btable "> </div>
	 </div>
    


      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="charges"}display:none{/if}"  id="d_charges">
	<div  class="new_item_dialog"  id="new_charge_dialog" style="display:none">
	  <div id="new_charge_messages" class="messages_block"></div>
	  <table class="edit" >
	    <tr><td>{t}Charge Name{/t}:</td><td><input  id="new_charge_name" onKeyUp="new_charge_changed(this)"    onMouseUp="new_charge_changed(this)"  onChange="new_charge_changed(this)"  changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	    <tr><td>{t}Charge Description{/t}:</td><td><input   id="new_charge_description" onKeyUp="new_charge_changed(this)"    onMouseUp="new_charge_changed(this)"  onChange="new_charge_changed(this)" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
	    </tr>
	  </table>
	</div>
	
	<div   class="data_table" sxtyle="margin:25px 10px;">
	  <span class="clean_table_title">{t}Charges{/t}</span>
	  <table class="options" style="float:right;padding:0;margin:0">
	    <tr>
	      <td  id="add_charge">Add Charge</td>
	      <td  style="display:none" id="save_new_charge">Save New Charge</td>
	      <td  style="display:none" id="cancel_add_charge">Cancel</td>
	    </tr>
	  </table>
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info2" class="clean_table_info"><span id="rtext2"></span> <span class="rtext_rpp" id="rtext_rpp2"></span> <span class="filter_msg"  id="filter_msg2"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter2"><div class="clean_table_info"><span id="filter_name2">{$filter_name2}</span>: <input style="border-bottom:none" id='f_input2' value="{$filter_value0}" size=10/><div id='f_container2'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator2"></span></div></div>
	  </div>
	  <div  id="table2"   class="data_table_container dtable btable "> </div>
	</div>
      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="shipping"}display:none{/if}"  id="d_shipping">
      </div>
      

      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="web"}display:none{/if}"  id="d_web">
      </div>
      
      <div  class="edit_block" style="{if $edit!="departments"}display:none{/if}"  id="d_departments">
       <div class="general_options" style="float:right">
	<span   style="margin-right:10px"  id="add_department" class="state_details" >Create Department</span>
	<span  style="margin-right:10px;display:none"  id="save_new_department" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;display:none" id="close_add_department" class="state_details">{t}Close Dialog{/t}</span>
	
      </div>
      <div  class="new_item_dialog"  id="new_department_dialog" style="display:none"  >

      <div id="new_department_messages" class="messages_block"></div>

      


	  <table class="edit">
	    <tr><td>{t}Code{/t}:</td><td><input  id="new_code" onKeyUp="new_dept_changed(this)"    onMouseUp="new_dept_changed(this)"  onChange="new_dept_changed(this)"  name="code" changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	    <tr><td>{t}Full Name{/t}:</td><td><input   id="new_name" onKeyUp="new_dept_changed(this)"    onMouseUp="new_dept_changed(this)"  onChange="new_dept_changed(this)"  name="name" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
	    </tr>
	  </table>
	  </div>
	
	<div   class="data_table" sxtyle="margin:25px 20px">
	  <span class="clean_table_title">{t}Departments{/t}</span>
	 
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	  </div>
	  <div  id="table0"   class="data_table_container dtable btable "> </div>
	</div>
     
      </div>
      
</div>      


<div id="the_table1" class="data_table" style="">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption1" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div id="clean_table_filter1" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

</div>
{include file='footer.tpl'}
