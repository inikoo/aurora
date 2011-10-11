{include file='header.tpl'}


<div id="bd"  style="padding:0px">
<div style="padding:0 20px">

 <br><br>
<div style="clear:left;margin:0 0px">
    <h1 style="text-transform: capitalize; width:200px; float:left;">{t}Campaign Builder{/t}</h1>
    <div class="campaign_cancel"> <a href="#">Save &amp; exit</a> </div>
</div>
<br><br><hr style="color:#DDDDDD">

<div><span style="font-size:18px;padding:5px;">Blank Subject</span> <div id="back-next-bottom"> <a href="marketing_campaign_builder.php"><input type="image" title="back to previous step" value="back" name="back" class="back" src="art/back-bottom2.png"></a>  <input type="image" title="proceed to next step" value="next" name="next" class="next" src="art/next-bottom.png">  </div> </div>
<div style="height:420px;">
<div style="float:left;">
<fieldset class="field_set"> <legend class="legend_part">campaign info</legend>

<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
<label  for="title">Name your campaign</label>
<div class="field-wrapper"> <input type="text" value="" class="av-text" id="title" tabindex="1" name="content[title]" size="61" height="4"> 
</div>
<label  for="title">Message subject <span class="asterisk">*</span></label>
<div class="field-wrapper"> <input type="text" value="" class=" av-text" id="title" tabindex="1" name="content[title]" size="61"> 
</div>
<label  for="title">From name  <span class="asterisk">*</span></label>
<div class="field-wrapper"> <input type="text" value="" class=" av-text" id="title" tabindex="1" name="content[title]" size="61"> 
</div>
<label  for="title">Reply-to email   <span class="asterisk">*</span></label>
<div class="field-wrapper"> <input type="text" value="" class=" av-text" id="title" tabindex="1" name="content[title]" size="61"> 
</div>
<div>
<input type="checkbox" name="content[personalizeToEmail]" value="on" id="personalize-to" style="-moz-user-select: none;">
<label class="inline-label" for="personalize-to">Personalize the "To:" field</label>
</div>
<br>
<label  for="title">Specify <span class="asterisk">*</span>|MERGETAGS|<span class="asterisk">*</span> for recipient name   <span class="asterisk">*</span></label>
<div class="field-wrapper"> <input type="text" value="" class=" av-text" id="title" tabindex="1" name="content[title]" size="61"> 
</div>




</fieldset>


</div>
<div style="float:right;width:420px;">

<fieldset class="field_set right_field"> <legend class="legend_part">tracking</legend>

<div class="group-label">Email tracking</div>


<input type="checkbox" name="" value="on" id="" style="-moz-user-select: none;">
<label class="inline-label" for="track opens">track opens</label><br>
<input type="checkbox" name="" value="on" id="" style="-moz-user-select: none;">
<label class="inline-label" for="track opens">track click</label>
<div class="checkbox-help">Required on free accounts, optional on paid account.</div>
<input type="checkbox" name="" value="on" id="" style="-moz-user-select: none;">
<label class="inline-label" for="track opens">track plain-text clicks</label>
<div class="checkbox-help">Required on free accounts, optional on paid account.</div>
</div>

</fieldset>

</div>





</div>

<div id="back-next-bottom"> <a href="marketing_campaign_builder.php"><input type="image" title="back to previous step" value="back" name="back" class="back" src="art/back-bottom2.png"></a>  <input type="image" title="proceed to next step" value="next" name="next" class="next" src="art/next-bottom.png">  </div><br>









	

		</div>
	</div>


</div>
</div>

{include file='footer.tpl'}


