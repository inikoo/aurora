{include file='header.tpl'}
<div id="bd" >
	<div id="no_details_title" style="clear:right;{if $show_details}display:none;{/if}">
    <h1>{t}Change Style{/t}</h1>
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

<div class="top_row">
<h2>{t}Administration Account{/t}</h2>
</div>

<br>
<form action="change_user_theme.php" method="post">
<div>
	<table border="0" width="350">
		

		<tr>
			 <td width="200px">Please select the style : </td> 
			<td><a href="#" onclick="change_user_theme(0);"><div style="width:20px; height:20px; background-color:Blue; border:1px;"></div></a></td>
			 <td><a href="#" onclick="change_user_theme(1);"><div style="width:20px; height:20px; background-color:#BD6A14; border:1px;"></div></a></td>
			 <td><a href="#" onclick="change_user_theme(2);"><div style="width:20px; height:20px; background-color:#72B80B; border:1px;"></div></a></td>
			
		</tr>
		
		
		
	</table>
</div>
	<div id="display"></div>
</form>
	
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
