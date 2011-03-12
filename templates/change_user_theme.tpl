{include file='header.tpl'}
<div id="bd" >
	<div id="no_details_title" style="clear:right;{if $show_details}display:none;{/if}">
    <h1>{t}Change Style{/t}</h1>
</div>

<div class="top_row">
<h2>{t}Administration Account{/t}</h2>
</div>

<br>
<form action="change_style.php" method="post">
<div>
	<table border="0" width="350">
		

		<tr>
			 <td width="200px">Please select the style : </td> 
			<td><a href="#" onclick="change_user_theme(0);"><div style="width:20px; height:20px; background-color:Blue; border:1px;"></div></a></td>
			 <td><a href="#" onclick="change_user_theme(1);"><div style="width:20px; height:20px; background-color:#BD6A14; border:1px;"></div></a></td>
			
			
		</tr>
		
		
		
	</table>
</div>
	<div id="display"></div>
</form>
	

</div>
{include file='footer.tpl'}
