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
	<table border="0" width="400">
		

		<tr>
			 <td width="200px">Please select the style : </td> 
			 <td><a href="#" onclick="change_style(1);"><div style="width:10px; height:10px; background-color:green; border:1px;"></div></a></td>
			 <td><a href="#" onclick="change_style(2);"><div style="width:10px; height:10px; background-color:#B90104; border:1px;"></div></a></td>
			 <td><a href="#" onclick="change_style(3);"><div style="width:10px; height:10px; background-color:#011345; border:1px;"></div></a></td>
			 <td><a href="#" onclick="change_style(4);"><div style="width:10px; height:10px; background-color:#000000; border:1px;"></div></a></td>
			 <td><a href="#" onclick="change_style(5);"><div style="width:10px; height:10px; background-color:#ECC965; border:1px;"></div></a></td>
		</tr>
		
		
		
	</table>
</div>
	<div id="display"></div>
</form>
	

</div>
{include file='footer.tpl'}
