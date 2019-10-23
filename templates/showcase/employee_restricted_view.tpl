


<div class="asset_container">

    {assign "image_key" $employee->get_main_image_key()}
    {if $image_key>0}
    <div class="block picture">

        <div class="data_container">
            <div id="main_image"  class="wraptocenter main_image {if $image_key==''}hide{/if}">
                <img  src="/{if $image_key}image.php?id={$image_key}&amp;s=170x270{else}art/nopic.png{/if}"> </span>
            </div>
        </div>

    </div>
    {/if}
    <div class="block info">

        <div class="name_and_categories">

            <span class="strong"><span class="Staff_Name">{$employee->get('Name')}</span> </span>


        </div>

        <div id="overviews">
            <table border="0" class="overview" style="">
                <tr class="main {if $employee->get('Staff Currently Working')=='Yes'}hide{/if} ">
                    <td class="aright title">{t}Ex-employee{/t}</td>
                </tr>
                <tr class="main {if $employee->get('Staff Currently Working')=='Yes'}hide{/if} ">
                    <td class="aright ">{$employee->get('Valid From')} - {$employee->get('Valid To')}</td>
                </tr>
                <tr class="main {if $employee->get('Staff Currently Working')=='No'}hide{/if} ">
                    <td class="aright  Staff_Clocking_Data">{$employee->get('Clocking Data')}</td>
                </tr>


            </table>
        </div>
    </div>
    <div style="clear:both"></div>
</div>

