{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 March 2020  15:19::10  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


{if isset($staff)}
<div id="attendance_container"  class=" {if $staff->get('Staff Attendance Status')=='Work'}hide{/if}"  data-staff_key="{$staff->id}" style="border-bottom: 1px solid #ccc;padding:20px 40px">
    <span data-type="check_in"  data-source="WorkHome" class="button WorkHome attendance_button  discreet_on_hover {if !($staff->get('Staff Attendance Status')=='Off' or  $staff->get('Staff Attendance Status')=='Finish')  }hide{/if} " style="border:1px solid #ddd;padding:5px 30px"  ><i class="fal fa-fw fa-laptop-house"></i> {t}Check-in (home){/t}</span>
    <span data-type="check_in"  data-source="WorkOutside"  class="button WorkOutside attendance_button discreet_on_hover {if !($staff->get('Staff Attendance Status')=='Off' or  $staff->get('Staff Attendance Status')=='Finish')  }hide{/if}" style="border:1px solid #ddd;padding:5px 30px;margin-left:30px"  ><i class="fal fa-fw  fa-car-building"></i> {t}Check-in (On the road){/t}</span>

    <span data-type="check_out" data-source="Break"  class="button BreakOut attendance_button discreet_on_hover start_break {if  $staff->get('Staff Attendance Status')=='Off' or  $staff->get('Staff Attendance Status')=='Finish' or  $staff->get('Staff Attendance Status')=='Break'  }hide{/if}" style="border:1px solid #ddd;padding:5px 30px"  ><i class="fal fa-fw  fa-toilet-paper"></i> {t}Start break{/t}</span>
    <span data-type="check_in" data-source="Break"  class="button BreakIn attendance_button discreet_on_hover end_break {if  $staff->get('Staff Attendance Status')!='Break'  }hide{/if}" style="border:1px solid #ddd;padding:5px 30px"  ><i class="fal fa-fw  fa-toilet-paper-slash"></i> {t}End break{/t}</span>

    <span data-type="check_out" data-source=""  class="button Checkout  attendance_button discreet_on_hover check_out {if $staff->get('Staff Attendance Status')=='Off'  or $staff->get('Staff Attendance Status')=='Finish' }hide{/if}" style="border:1px solid #ddd;padding:5px 30px"  ><i class="fal fa-fw  fa-door-closed"></i> {t}Check-out{/t}</span>

</div>

{/if}


