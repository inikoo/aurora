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
$(document).ready(function(){
 
    var counter = 2;
 
    $("#addButton").click(function () {
 
	if(counter>10){
            alert("Only 10 textboxes allow");
            return false;

	}   
 
	var newTextBoxDiv = $(document.createElement('div'))
	     .attr("id", 'TextBoxDiv' + counter);
 
	newTextBoxDiv.after().html('<label>Textbox #'+ counter + ' : </label>' +

	      '<input type="text" name="email_text[]' + counter + 

	      '" id="textbox' + counter + '" value="" >');

 
	newTextBoxDiv.appendTo("#TextBoxesGroup");
 
 
	counter++;
     });
 
     $("#removeButton").click(function () {
	if(counter==1){
          alert("No more textbox to remove");

          return false;
       }   
 
	counter--;
 
        $("#TextBoxDiv" + counter).remove();
 
     });
 
     $("#getButtonValue").click(function () {
 
	var msg = '';

	for(i=1; i<counter; i++){
   	  msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();

	}
    	  alert(msg);

     });
  });

function addElement() {
  var ni = document.getElementById('myDiv');
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<input type="text" name="email[]" id="email"> <a href=\'#\' onclick=\'removeElement('+divIdName+')\'>Remove the div "'+divIdName+'"</a>';
  ni.appendChild(newdiv);
}

function removeElement(divNum) {
  var d = document.getElementById('myDiv');
  var olddiv = document.getElementById(divNum);
  d.removeChild(olddiv);
}
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


  <div id='TextBoxesGroup'>

	<div id="TextBoxDiv1">
<form action="a.php" method="get">
<input type="hidden" value="0" id="theValue" />
<p><a href="javascript:;" onclick="addElement();">Add Some Elements</a></p>
<div id="myDiv"> </div>
<input type="submit" name="s" value="OK">
</form>

	</div>
</div>

<input type='button' value='Add Button' id='addButton'>

<input type='button' value='Remove Button' id='removeButton'>

<input type='button' value='Get TextBox Value' id='getButtonValue'>
<p><a href="#" onclick="addEvent(1);"><span style="font-size:10px; color:#CC66OD;">Add Condition</span></a></p>
<div id="myDiv{$value[value].$list_key}" style="font-size:10px; color:#CC66OD;"> </div>
<p style="padding:10px;height:35px; width:850px;">
<input type="submit" name="sagement1" class="sagement_img" value="dsfs">
</form>
<input type="button" id="sagement" class="cancel_img" value=""></p>
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


