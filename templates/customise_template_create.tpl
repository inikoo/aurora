{include file='header.tpl'}
<div id="bd" >
{literal}
<script>
	$(document).ready(function(){
	$("#basic").click(function(){
	$("#Newsletter1").css("display", "none");
	$("#Newsletter2").css("display", "none");
	$("#Postcard").css("display", "none");
	$("#Basic_template").css("display", "block");
	});

$("#news1").click(function(){
$("#Basic_template").css("display", "none");
$("#Newsletter2").css("display", "none");
$("#Postcard").css("display", "none");
$("#Newsletter1").css("display", "block");
});

	$("#news2").click(function(){
	$("#Basic_template").css("display", "none");
	$("#Newsletter1").css("display", "none");
	$("#Postcard").css("display", "none");
	$("#Newsletter2").css("display", "block");
	});

$("#postcard").click(function(){
$("#Basic_template").css("display", "none");
$("#Newsletter2").css("display", "none");
$("#Newsletter1").css("display", "none");
$("#Postcard").css("display", "block");
});
});
</script>

<script language="javascript" src="js/customise_template_validation.js">
</script>



{/literal}

      <h2 style="clear:both">{t}Customise Template{/t} </h2>
<div style="padding:50px;width:690px">
	<div id="campaign_div">{$msg}</div>
<table style="background-color:#dddddd;min-width:650px;">
<tr><td style="min-width:156px;background-color:#445685;color:#ffffff;min-height:520px;float:left; font-size:14px;padding:5px;">
<p id="basic" style="cursor:pointer;">Basic Template</p>
<p id="news1" style="cursor:pointer;">Newsletter Template 1</p>
<p id="news2" style="cursor:pointer;">Newsletter Template 2</p>
<p id="postcard" style="cursor:pointer;">Post card</p>
</td>
<td style="max-height:529px;">

<table id="Basic_template" style="display:block;">
<tr><td colspan="2" style="font-size:15px;padding-bottom:10px;"><b>Basic Template</b></td></tr>

<form name="basic1" action="basic_template.php" method="post" enctype="multipart/form-data"  onsubmit="return basicForm();">

<tr>
<td style="float:left;">Header:</td>
<td><input type="text" name="basicheader" id="template header">

</td></tr>
<tr><td style="float:left;">Subtitle:</td>
<td><input type="text" name="basictitle" id="basictitle">
</td></tr>

<tr><td style="float:left;">Paragraph Block 1:</td>
<td><textarea cols="30" rows="4" name="basicPBlock1" id="basicPBlock1"></textarea>

</td></tr>
<tr><td style="float:left;">Block 1 Image:</td>
<td><input type="file" name="basicPBlock1image" id="basicPBlock1image" >

</td></tr>
<tr><td style="float:left;">Paragraph Block 2:</td>
<td><textarea cols="30" rows="4" name="basicPBlock2" id="basicPBlock2"></textarea>

</td></tr>
<tr><td style="float:left;">Block 2 Image:</td>
<td><input type="file" name="basicPBlock21image" id="basicPBlock21image">

</td></tr>
<tr><td style="float:left;">Paragraph Block 3:</td>
<td><textarea cols="30" rows="4" name="basicPBlock3" id="basicPBlock3"></textarea>

</td></tr>
<tr><td style="float:left;">Block 3 Image:</td>
<td><input type="file" name="basicPBlock3image" id="basicPBlock3image">

</td></tr>
<tr><td></td>
<td style="float:right;" ><br><input type="submit" name="basic" id="basic" value="Proceed">
		
</td></tr>

</form><br>
</table>








<table id="Newsletter1" style="display:none">
<tr><td colspan="2" style="font-size:15px;padding-bottom:10px;"><b>Newsletter Template 1</b></td></tr>

<form name="Newsletter1" action="newsletter_template1.php" method="post" enctype="multipart/form-data"  onsubmit="return basicForm2();">

<tr>
<td style="float:left;">Header:</td>
<td><input type="text" name="news1_header" id="template header">

</td></tr>
<tr><td style="float:left;">Subtitle:</td>
<td><input type="text" name="news1_title" id="template header">
</td></tr>

<tr><td style="float:left;">Paragraph Block 1:</td>
<td><textarea cols="30" rows="4" name="news1_Block1"></textarea>

</td></tr>
<tr><td style="float:left;">Block 1 Image:</td>
<td><input type="file" name="news1_Block1image" >

</td></tr>
<tr><td style="float:left;">Paragraph Block 2:</td>
<td><textarea cols="30" rows="4" name="news1_Block2"></textarea>

</td></tr>
<tr><td style="float:left;">Block 2 Image:</td>
<td><input type="file" name="news1_Block2image">

</td></tr>
<tr><td style="float:left;">Paragraph Block 3:</td>
<td><textarea cols="30" rows="4" name="news1_Block3"></textarea>

</td></tr>
<tr><td style="float:left;">Block 3 Image:</td>
<td><input type="file" name="news1_Block3image">

</td></tr>
<tr><td></td>
<td style="float:right;" ><br><input type="submit" name="news1" id="basic" value="Proceed">
		
</td></tr>

</form>
</table>








<table id="Newsletter2" style="display:none;">
<tr><td colspan="2" style="font-size:15px;padding-bottom:10px;"><b>Newsletter Template 2</b></td></tr>
<form name="Newsletter2" action="newsletter_template2.php" method="post" enctype="multipart/form-data" onsubmit="return basicForm3();">

<tr>
<td style="float:left;">Header:</td>
<td><input type="text" name="newsheader" id="template header">

</td></tr>
<tr><td style="float:left;">Subtitle:</td>
<td><input type="text" name="newstitle" id="template header">
</td></tr>

<tr><td style="float:left;">Paragraph Block 1:</td>
<td><textarea cols="30" rows="4" name="newsPBlock1"></textarea>

</td></tr>
<tr><td style="float:left;">Block 1 Image:</td>
<td><input type="file" name="newsPBlock1image" >

</td></tr>
<tr><td style="float:left;">Paragraph Block 2:</td>
<td><textarea cols="30" rows="4" name="newsPBlock2"></textarea>

</td></tr>
<tr><td style="float:left;">Block 2 Image:</td>
<td><input type="file" name="newsPBlock21image">

</td></tr>
<tr><td style="float:left;">Paragraph Block 3:</td>
<td><textarea cols="30" rows="4" name="newsPBlock3"></textarea>

</td></tr>
<tr><td style="float:left;">Block 3 Image:</td>
<td><input type="file" name="newsPBlock3image">

</td></tr>
<tr><td></td>
<td style="float:right;" ><br><input type="submit" name="basic" id="basic" value="Proceed">
		
</td></tr>

</form>
</table>



<table id="Postcard" style="display:none;">
<tr><td colspan="2" style="font-size:15px;padding-bottom:10px;"><b>Postcard</b></td></tr>
<form name="postcard" action="postcard_template.php" method="post"  enctype="multipart/form-data" onsubmit="return basicForm4();">
<tr><td style="float:left;">Header:</td>
<td><input type="text" name="Pcardheader" id="Pcardheader" size=20>

</td></tr>


<tr><td style="float:left;">Paragraph Block:</td>
<td><textarea cols="30" rows="5" name="PcardBlock"></textarea>

</td></tr>
<tr><td style="float:left;">Block Image:</td>
<td><input type="file" name="PcardImage" id="PcardImage" size=20>

</td></tr>
<tr><td></td>
<td style="float:right;" ><br><input type="submit" name="postcard" id="postcard" value="Proceed">

</td></tr>

</form>
</table>






</td></tr>

    </table>  
</div> 


</div>

{include file='footer.tpl'}
