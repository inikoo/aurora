{include file='header.tpl'}



{literal}
 	<script>
         $(document).ready(function(){
   	 $("#segment_part").click(function () {
          $('#part').slideDown("fast");
    	 });
       $("#sagement").click(function () {
          $('#part').slideUp("fast");
    	 });

});

</script>

{/literal}




<div id="bd"  style="padding:0px">
<div style="padding:0 20px">

 <br><br>
<div style="clear:left;margin:0 0px">
    <h1 style="text-transform: capitalize; width:200px; float:left;">{t}Campaign{/t}</h1>
    <div class="campaign_cancel"> <a href="#">Save &amp; exit</a> </div>
</div>
<br><br><hr style="color:#DDDDDD">

<div><span style="font-size:18px;padding:5px;">Untitled</span> <a style="float:right;" href="marketing_campaign_builder_setup.php"><img src="art/next-bottom.gif"</a> </div>

<fieldset class="field_set"> <legend class="legend_part">which list would you like to send this campaign to?</legend>

<div class="campaignlist_outer"><input type="radio" name="" id=""><label class="campaign_name">example (0 recipients)</label>
<input type="submit" class="send_entire_list" value="send to entire list">
<span class="segment" id="segment_part"><img src="art/resultset_next.png">Send to segment</span>
<div id="part" style="background-color:#ffffff;margin-top:14px;min-height:50px;display:none;">


  <p style="padding:7px;"> match 
	<select id="" class="" name=""> 
	<option selected="" value="any">any</option>
	 <option value="all">all</option> 
	</select> of the following: </p>

  <p style="padding:7px;">
	<select id="" class="" name=""> 
	<option value="timestamp_opt">Date Added</option><option value="info_changed">Last Changed</option><option selected="" value="merge0">Email Address</option><option value="merge1">First Name</option><option value="merge2">Last Name</option><option value="rating">Member Rating</option><option value="ipgeo">Location</option><option value="aim">Subscriber Activity</option>
	</select> 
     <select id="sagement2_{$value[value].$list_id}" class="" name="sagement2_{$value[value].$list_id}"> 
	<<option selected="" value="is">is</option><option value="not">is not</option><option value="contains">contains</option><option value="notcontain">does not contain</option><option value="starts">starts with</option><option value="ends">ends with</option><option value="greater">is greater than</option><option value="less">is less than</option>
	</select> 
       <input type="text" class="value_sagement" name="" id="">

</p>
<p><a href="#" onclick="addEvent(1);"><span style="font-size:10px; color:#CC66OD;">Add Condition</span></a></p>
<div id="myDiv{$value[value].$list_key}" style="font-size:10px; color:#CC66OD;"> </div>
<p style="padding:10px;">
<input type="submit" name="sagement" value="sagement">
<input type="button" id="sagement" value="cancel"></p>
</div>
</div>

</fieldset>
<a class="button_campaignListCreate" href="">setup a new list</a>	
<div> <a style="float:right;" href="marketing_campaign_builder_setup.php"><img src="art/next-bottom.gif"</a> </div>
		</div>
	</div>


</div>
</div>

{include file='footer.tpl'}


