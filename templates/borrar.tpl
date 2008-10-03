<table class="other_images " id="otherimages"    {if $images<2}{/if}  >
       <tr>
	 <td class="img_arrow" ><img src="art/icons/bullet_come.png" alt="<"/ style="visibility:hidden"></td>
	 <td  id="oim_0" align="center" pic_id="{$other_images_id[0]}"  >{if $other_images_id[0]!=0}<img  src="{$other_images_src[0]}"  />{/if}</td>
	 <td  id="oim_1" pic_id="{$other_images_id[1]}"   >{if $other_images_id[1]!=0}<img border=1 src="{$other_images_src[1]}"  />{/if}</td>
	 <td  id="oim_2" pic_id="{$other_images_id[2]}"   >{if $other_images_id[2]!=0}<img src="{$other_images_src[2]}"  />{/if}</td>
	 <td  id="oim_3" pic_id="{$other_images_id[3]}"   >{if $other_images_id[3]!=0}<img src="{$other_images_src[3]}"  />{/if}</td>
	 <td  id="oim_4" pic_id="{$other_images_id[4]}"   >{if $other_images_id[4]!=0}<img src="{$other_images_src[4]}"  />{/if}</td>
	 <td class="img_arrow" ><img src="art/icons/bullet_go.png" alt=">" style="visibility:hidden"    /></td>
       </tr>
       
</table>
