<div class="search_box" ></div>
<div id="contact_messages_div" >
      <span id="contact_messages"></span>
    </div>
<div >
     <div id="results" style="margin-top:0px;float:right;width:600px;"></div>
	 <div  style="float:left;width:600px;" >
    <table class="edit"  border="0" style="width:100%;margin-bottom:0px" >
	
	<tr class="title">
	<td colspan=3>{t}Staff Info{/t}</td>
	</tr>


	 <tr><td style="width:200px"class="label">{t}Staff Name{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="Staff_Name" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="Staff_Name_Container"  ></div>
       </div>
	   </td>
	   <td id="Staff_Name_msg" class="edit_td_alert" ></td>
	  </tr>



	 <tr><td style="width:200px"class="label">{t}Staff Alias{/t}:</td><td style="width:370px">
	  <div  >
	   <input  style="width:100%" id="Staff_Alias" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="" />
	   <div id="Staff_Alias_Container"  ></div>
       </div>
	   </td>
	   <td id="Staff_Alias_msg" class="edit_td_alert" ></td>
	  </tr>

 
	<tr style="display:none"><td class="label">{t}Staff Working{/t}:</td>
	  <td>
	    <div class="buttons small left" id="staff_working" value="Yes"  ovalue="Yes"   prefix="staff_working_" class="options" style="margin:5px 0">

	    <button  class="positive" name="1" onclick="radio_changed(this)" id="staff_working_1">{t}Yes{/t}</button>
	    <button class="negative" name="2" onclick="radio_changed(this)" id="staff_working_2">{t}No{/t}</button>

	    </div>
	  </td>
	 </tr>
  		
	<tr><td class="label">{t}Staff Supervisor{/t}:</td>
	  <td>
	    <div class="buttons small left" id="staff_supervisor" value="No"  ovalue="No"   prefix="staff_supervisor_" class="options" style="margin:5px 0">

	    <button  name="1" class="positive" onclick="radio_changed_staff(this)" id="staff_supervisor_1">{t}Yes{/t}</button>
	    <button name="2" class="negative" onclick="radio_changed_staff(this)" id="staff_supervisor_2">{t}No{/t}</button>

	    </div>
	  </td>
	 </tr>

	<tr>

	<td class="label"><div >{t}Staff Position{/t}:</div></td>
	<td>
		<select id="staff_position" onChange="change_position(this)">
			{foreach from=$staff_position item=item key=key  }
				<option value="{$key}">{$item}</option>
			{/foreach}
		</select>
	</td>   
	</tr>

</table>


<table class="options" style="float:right;padding:0;margin:0">
	<tr>
	<div class="buttons" >
			<button  style="margin-right:10px;visibility:"  id="save_new_staff" class="positive disabled">{t}Save{/t}</button>
			<button style="margin-right:10px;visibility:" id="reset_new_staff" class="negative">{t}Reset{/t}</button>
	</div>
	</tr>
</table>
    
      </div>
      <div style="clear:both;height:40px"></div>
	</div>
      </div>
<div class="star_rating" id="star_rating_template" style="display:none"><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /></div>
