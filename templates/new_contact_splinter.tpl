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
	<tr>
	<td style=";" class="label">{t}Staff Name{/t}:</td>
	<td>
	<input style="width:18em" id="Staff_Name" value="" >
	<div id="Staff_Name_Container"  ></div>
	</td>
	</tr>


	<tr>
	<td style=";" class="label">{t}Staff Alias{/t}:</td>
	<td><div>
	<input style="width:18em" id="Staff_Alias" value="" >
	<div id="Staff_Alias_Container"  ></div>
	</div>
	</td>
	<td style="width:200px" id="Staff_Alias_msg" class="edit_td_alert"></td>
	</tr>
	<tr><td class="label">{t}Staff Working{/t}:</td>
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

	    <button  name="1" class="positive" onclick="radio_changed(this)" id="staff_supervisor_1">{t}Yes{/t}</button>
	    <button name="2" class="negative" onclick="radio_changed(this)" id="staff_supervisor_2">{t}No{/t}</button>

	    </div>
	  </td>
	 </tr>


</table>


<table class="options" style="float:right;padding:0;margin:0">
	<tr>
<div class="buttons" >
		<button  style="margin-right:10px;visibility:hidden"  id="save_add_staff_description" class="positive">{t}Save{/t}</button>
		<button style="margin-right:10px;visibility:hidden" id="reset_add_staff_description" class="negative">{t}Reset{/t}</button>
	</div>
	</tr>
</table>
    
      </div>
      <div style="clear:both;height:40px"></div>
	</div>
      </div>
<div class="star_rating" id="star_rating_template" style="display:none"><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /></div>
