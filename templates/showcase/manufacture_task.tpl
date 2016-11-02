<div class="subject_profile">
    <div style="float:left;width:600px">
        <div class="showcase">

            <h1 class="Manufacture_Task_Name">{$manufacture_task->get('Name')}</h1>

            <table border=0>
                <tr>
                    <td class="label">{$manufacture_task->get_field_label('Manufacture Task Cost')|capitalize}</td>
                    <td class="Manufacture_Task_Cost">{$manufacture_task->get('Cost')}</td>
                </tr>
                <tr>
                    <td class="label">{$manufacture_task->get_field_label('Manufacture Task Targets')|capitalize}</td>
                    <td class="Manufacture_Task_Targets">{$manufacture_task->get('Targets')}</td>
                </tr>
            </table>
        </div>
        <div style="clear:both">
        </div>
        <div style="clear:both">
        </div>
    </div>

    <div style="clear:both">
    </div>
</div>


