<!-- 
About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 April 2017 at 12:23:57 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
-->


<div class="item">
    <div class="question">
        <i class="fa fa-caret-right bullet fw"></i> {t}How to add new location?{/t}
    </div>
    <div class="answer hide">
        <p>
            {t}Click in the <i class="fa fa-plus"></i> icon at the table header{/t}
        </p>
    </div>
</div>
<div class="item">
    <div class="question">
        <i class="fa fa-caret-right bullet fw"></i> {t}How to add locations in bulk?{/t}
    </div>
    <div class="answer hide">
        <p>
            {t}Click in the <i class="fa fa-cloud-upload"></i> icon at the table header to upload a excel or a CSV file with the following fields{/t} — <i
                    class="fa fa-file-excel-o"></i> <a title="{t}You can use this file as template{/t}"
                                                       href="/upload_arrangement.php?object=location&parent=warehouse&parent_key=1"> {t}template{/t}</a>
            —
        </p>
        <ul>
            <li><b>{t}Location code{/t}</b> <i>({t}required, unique{/t})</i> [{t}string{/t}]</li>
            <li><b>{t}Flag{/t}</b> <i>({t}optional{/t})</i> ['Blue', 'Green', 'Orange', 'Pink', 'Purple', 'Red', 'Yellow'].</li>

            <li><b>{t}Used for{/t}</b> <i>({t}required{/t})</i> ['Picking', 'Storing', 'Loading', 'Displaying', 'Other']</li> {t}Main propose of this location{/t}
            <li><b>{t}Max weight{/t}</b> <i>({t}optional{/t})</i> [{t}numeric{/t}] ({t}Kilograms{/t})</li>
            <li><b>{t}Max volume{/t}</b> <i>({t}optional{/t})</i> [{t}numeric{/t}] ({t}Cubic meters{/t})</li>



        </ul>

    </div>
</div>
