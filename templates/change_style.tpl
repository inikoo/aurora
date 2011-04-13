{include file='header.tpl'}
<div id="bd" >
	<div id="no_details_title" style="clear:right;{if $show_details}display:none;{/if}">
    <h1>{t}Change Theme{/t}</h1>
</div>
{literal}
<script language="javascript">
 $(document).ready(function(){
  $("#change").click(function(){
   $(".show").show();


});

});
function validateForm()
{
var x=document.forms["background"]["image"].value
if (x==null || x=="")
  {
  alert("Please choose a image");
  return false;
  }
}
</script>
{/literal}
<br>
<form action="change_style.php" method="post">
<div>
	<table border="0" width="350" cellpadding="10">
		

		<tr>
			 <td width="200px">Please select the style : </td>
               </tr>
               <tr>             
			<td style=" padding:10px 20px 10px 0px;"><a href="#" onclick="change_style(0);"  title="Default theme"><div style="padding:10px;border:2px #000000 solid;" id="dialog_link"><img height="120" width="160" src="art/ScreenshotDefault.png"></div></a></td>
			 <td style=" padding:10px 20px 10px 0px;"><a href="#" onclick="change_style(1);"  title="Brown theme"><div style="padding:10px;border:2px #000000 solid;" id="dialog_link"><img src="art/ScreenshotBrown.png"></div></a></td>
			<td style=" padding:10px 20px 10px 0px;"><a href="#" onclick="change_style(2);" title="Green theme"><div style="padding:10px;border:2px #000000 solid;" id="dialog_link"><img src="art/ScreenshotGreen.png"></div></a></td>
			<td style=" padding:10px 20px 10px 0px;"><a href="#" onclick="change_style(3);" title="Magento theme"><div style="padding:10px;border:2px #000000 solid;" id="dialog_link"><img height="120" width="160" src="art/ScreenshotMagento.png"></div></a></td>
			
		</tr>
		
		
		
	</table>
        
</div>
	<div id="display"></div>
</form>
          
<div id="dialog" style="width:100px;" title="">
			<p id="dialog_text" style="display:none;">Press Yes to change theme for all user<p>
		</div>

           <p id="change" style="cursor:pointer;color:steelBlue;">Change background image</p>

        <div class="show" style="display:none">
	<form action="change_style.php" name="background" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" >
	<table>
            <tr><td><input type="file" name="image" id="image"></td><td><input type="submit" name="submit" value="submit"></td></tr>
            
	</table>
        </form>

       </div>

</div>
{include file='footer.tpl'}
