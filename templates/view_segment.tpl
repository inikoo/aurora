<br>
<br>
{literal}
<style>
.text{
    color: #2B2B2B !important;
    font-size: 12px;
    font-weight: bold;
}
.value{
	 color: #2B2B2B !important;
    font-size: 11px;
    

 }
.text_color{
  
  color: #B67550 !important;
    font-size: 11px;
}
</style>
{/literal}
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align="left" colspan=8><h2>Campaign Recipients</h2></td>	
	</tr>
 <tr bgcolor="#D1EBF1" height="25px">

  <td width="10%" class="text"></td>
  <td  class="text">Email Address </td>
  <td  class="text">First Name </td>
  <td  class="text">Last Name </td>
  <td  class="text">Preferred format </td>
 
  <td  class="text">last changed </td>

 </tr>

{section name=value loop=$value}	
 <tr bgcolor="#EEEEEE">

  <td width="10%" align="center"><input type="submit" name="view_detail" value="View"></td>
  <td class="value">	{$value[value].$people_email}	</td>
  <td class="value">	{$value[value].$people_fname}	</td>
  <td class="value">	{$value[value].$people_lname} 	</td>
  <td class="value">	{$value[value].$people_type} 	</td>
 
  <td class="value">	00:00:00 00/00/0000  </td>

 </tr>

{sectionelse}
  <tr height="30px"><td colspan="6" align="center" class="text_color">{$no_result}</td></tr>


{/section}

</table>
