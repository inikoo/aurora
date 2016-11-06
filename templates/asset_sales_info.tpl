<div style="padding:20px">


    <table class="time_series_set_info">


        <tr class="strong top">
            <td colspan="4">{t}Sales history{/t}</td>

        </tr>

        <tr class="top">
            <td></td>
            <td>{t}Non zero records{/t}</td>
            <td>{t}Last updated{/t}</td>
            <td></td>
        </tr>

        {foreach from=$time_series_set item=time_series}
            <tr>
                <td>{$time_series['label']}</td>

                <td class="timeseries_number_records_{$time_series['Timeseries Frequency']}">{if $time_series['key']!=0}{$time_series['object']->get('Number Records')}{/if}</td>
                <td class="timeseries_last_updated_{$time_series['Timeseries Frequency']}">{if $time_series['key']!=0}{$time_series['object']->get('Updated')}{/if}</td>
                <td>
                    <table class="hide progress_bar_table_{$time_series['Timeseries Frequency']}">
                        <tr>
                            <td class="progress_bar_container  timeseries_progress_bar_container_{$time_series['Timeseries Frequency']}">
                                <span class="progress_bar_bg hide timeseries_progress_bar_bg_{$time_series['Timeseries Frequency']}"></span>
                                <div class="progress_bar hide timeseries_progress_bar_{$time_series['Timeseries Frequency']}"></div>
                            </td>
                        </tr>
                    </table>

                    <span class=" button discreet timeseries_operations_{$time_series['Timeseries Frequency']}"
                          onclick="create_sales_history('{$time_series['Timeseries Frequency']}','{$time_series['parent']}','{$time_series['parent_key']}',
                                  {
                                  'Timeseries Type': '{$time_series['Timeseries Type']}',
                                  'Timeseries Frequency': '{$time_series['Timeseries Frequency']}',
                                  'Timeseries Scope': '{$time_series['Timeseries Scope']}'
                                  }

                                  )"

                    >
                        <span class="label_create_sales_history_{$time_series['Timeseries Frequency']} {if $time_series['key']}hide{/if}"><i class="fa fa-plus padding_right_5"
                                                                                                                                             aria-hidden="true"></i> <span>{t}Create sales history{/t}</span></span>
                        <span class="label_recreate_sales_history_{$time_series['Timeseries Frequency']} {if !$time_series['key']}hide{/if}"><i class="fa fa-repeat padding_right_5"
                                                                                                                                                aria-hidden="true"></i> <span>{t}Recreate sales history{/t}</span></span>
                   </span>
                </td>


            </tr>
        {/foreach}
        <tr class="strong top ceiling">
            <td colspan="4">{t}Sales precomputed sales values{/t}</td>

        </tr>

        <tr class="top">
            <td></td>
            <td></td>
            <td>{t}Last updated{/t}</td>
            <td></td>
        </tr>
        <tr>
            <td>{t}<i>X</i> to day{/t}</td>
            <td></td>
            <td class="precomputed_sales_updated_X_To_Day">{$object->get('Acc To Day Updated')}</td>
            <td>
                <table class="hide progress_bar_table_X_To_Day">
                    <tr>
                        <td class="progress_bar_container  precomputed_sales_progress_bar_container_X_To_Day">
                            <span class="progress_bar_bg hide precomputed_sales_progress_bar_bg_X_To_Day"></span>
                            <div class="progress_bar hide precomputed_sales_progress_bar_X_To_Day"></div>
                        </td>
                    </tr>
                </table>

                <span class=" button discreet precomputed_sales_operations_X_To_Day" onclick="calculate_sales('X_To_Day','{$time_series['parent']}','{$time_series['parent_key']}')">
                        <span class="label_calculate_sales_X_To_Day {if $object->get('Acc To Day Updated')!=''}hide{/if}"><i class="fa fa-calculator padding_right_5" aria-hidden="true"></i> <span>{t}Calculate values{/t}</span></span>
                        <span class="label_recalculate_sales_X_To_Day {if $object->get('Acc To Day Updated')==''}hide{/if}"><i class="fa fa-repeat padding_right_5" aria-hidden="true"></i> <span>{t}Recalculate values{/t}</span></span>
                </span>

            </td>
        </tr>
        <tr>
            <td>{t}Ongoing intervals{/t}</td>
            <td></td>
            <td class="precomputed_sales_updated_Ongoing_Intervals">{$object->get('Acc Ongoing Intervals Updated')}</td>
            <td>
                <table class="hide progress_bar_table_Ongoing_Intervals">
                    <tr>
                        <td class="progress_bar_container  precomputed_sales_progress_bar_container_Ongoing_Intervals">
                            <span class="progress_bar_bg hide precomputed_sales_progress_bar_bg_Ongoing_Intervals"></span>
                            <div class="progress_bar hide precomputed_sales_progress_bar_Ongoing_Intervals"></div>
                        </td>
                    </tr>
                </table>

                <span class=" button discreet precomputed_sales_operations_Ongoing_Intervals" onclick="calculate_sales('Ongoing_Intervals','{$time_series['parent']}','{$time_series['parent_key']}')">
                        <span class="label_calculate_sales_Ongoing_Intervals {if $object->get('Acc Ongoing Intervals Updated')!=''}hide{/if}"><i class="fa fa-calculator padding_right_5" aria-hidden="true"></i> <span>{t}Calculate values{/t}</span></span>
                        <span class="label_recalculate_sales_Ongoing_Intervals {if $object->get('Acc Ongoing Intervals Updated')==''}hide{/if}"><i class="fa fa-repeat padding_right_5" aria-hidden="true"></i> <span>{t}Recalculate values{/t}</span></span>
                </span>
            </td>
        </tr>
        <tr>
            <td>{t}Previous intervals{/t}</td>
            <td></td>
            <td class="precomputed_sales_updated_Previous_Intervals">{$object->get('Acc Previous Intervals Updated')}</td>
            <td>
                <table class="hide progress_bar_table_Previous_Intervals">
                    <tr>
                        <td class="progress_bar_container  precomputed_sales_progress_bar_container_Previous_Intervals">
                            <span class="progress_bar_bg hide precomputed_sales_progress_bar_bg_Previous_Intervals"></span>
                            <div class="progress_bar hide precomputed_sales_progress_bar_Previous_Intervals"></div>
                        </td>
                    </tr>
                </table>

                <span class=" button discreet precomputed_sales_operations_Previous_Intervals" onclick="calculate_sales('Previous_Intervals','{$time_series['parent']}','{$time_series['parent_key']}')">
                        <span class="label_calculate_sales_Previous_Intervals {if $object->get('Acc Previous Intervals Updated')!=''}hide{/if}"><i class="fa fa-calculator padding_right_5" aria-hidden="true"></i> <span>{t}Calculate values{/t}</span></span>
                        <span class="label_recalculate_sales_Previous_Intervals {if $object->get('Acc Previous Intervals Updated')==''}hide{/if}"><i class="fa fa-repeat padding_right_5" aria-hidden="true"></i> <span>{t}Recalculate values{/t}</span></span>
                </span>
            </td>
        </tr>
    </table>

</div>

<script>

    function create_sales_history(type, parent, parent_key, time_series_data) {


        var request = '/ar_edit.php?tipo=create_time_series&parent=' + parent + '&parent_key=' + parent_key + '&time_series_data=' + JSON.stringify(time_series_data)
        console.log(request)

        $.getJSON(request, function (data) {

            if (data.state == 200) {
                get_sales_history_process_bar(data.fork_key, 'timeseries', type);
            }


        })

    }

    function get_sales_history_process_bar(fork_key, tag, type) {


        $('.timeseries_operations_' + type).addClass('hide')
        $('.progress_bar_table_' + type).removeClass('hide')


        var request = '/ar_fork.php?tipo=get_process_bar&fork_key=' + fork_key + '&tag=' + tag
        $.getJSON(request, function (data) {
            //console.log(data)
            if (data.state == 200) {


                if (data.fork_state == 'Queued') {


                    $('.timeseries_progress_bar_bg_' + type).removeClass('hide').html('&nbsp;' + data.msg)
                    $('.timeseries_progress_bar_' + type).css('width', data.percentage).removeClass('hide')
                    setTimeout(function () {
                        get_sales_history_process_bar(data.fork_key, data.tag, type)
                    }, 250);


                } else if (data.fork_state == 'In Process') {

                    $('.timeseries_progress_bar_bg_' + type).removeClass('hide').html('&nbsp;' + data.forks_info)
                    $('.timeseries_progress_bar_' + type).css('width', data.percentage).removeClass('hide').attr('title', data.progress).html('&nbsp;' + data.forks_info);
                    setTimeout(function () {
                        get_sales_history_process_bar(data.fork_key, data.tag, type)
                    }, 500);

                } else if (data.fork_state == 'Finished') {


                    $('.timeseries_progress_bar_bg_' + type).addClass('hide').html('')
                    $('.timeseries_progress_bar_' + type).css('width', '0px').removeClass('hide').attr('title', '').html('')
                    $('.timeseries_operations_' + type).removeClass('hide')
                    $('.progress_bar_table_' + type).addClass('hide')

                    $('.label_create_sales_history' + type).addClass('hide')
                    $('.label_recreate_sales_history' + type).removeClass('hide')

                }


                if (data.object_extra_data != undefined) {
                    console.log(data.object_extra_data.last_updated)
                    $('.timeseries_number_records_' + type).html(data.object_extra_data.records)
                    $('.timeseries_last_updated_' + type).html(data.object_extra_data.last_update)
                }

            }
        })


    }

    function calculate_sales(type, parent, parent_key) {


        var request = '/ar_edit.php?tipo=calculate_sales&parent=' + parent + '&parent_key=' + parent_key+'&scope='+type
        console.log(request)

        $.getJSON(request, function (data) {

            if (data.state == 200) {
                get_precomputed_sales_process_bar(data.fork_key, 'calculate_sales', type);
            }


        })

    }

    function get_precomputed_sales_process_bar(fork_key, tag, type) {


        $('.precomputed_sales_operations_' + type).addClass('hide')
        $('.progress_bar_table_' + type).removeClass('hide')


        var request = '/ar_fork.php?tipo=get_process_bar&fork_key=' + fork_key + '&tag=' + tag
        $.getJSON(request, function (data) {
            //console.log(data)
            if (data.state == 200) {


                if (data.fork_state == 'Queued') {


                    $('.precomputed_sales_progress_bar_bg_' + type).removeClass('hide').html('&nbsp;' + data.msg)
                    $('.precomputed_sales_progress_bar_' + type).css('width', data.percentage).removeClass('hide')
                    setTimeout(function () {
                        get_precomputed_sales_process_bar(data.fork_key, data.tag, type)
                    }, 250);


                } else if (data.fork_state == 'In Process') {

                    $('.precomputed_sales_progress_bar_bg_' + type).removeClass('hide').html('&nbsp;' + data.forks_info)
                    $('.precomputed_sales_progress_bar_' + type).css('width', data.percentage).removeClass('hide').attr('title', data.progress).html('&nbsp;' + data.forks_info);
                    setTimeout(function () {
                        get_precomputed_sales_process_bar(data.fork_key, data.tag, type)
                    }, 500);

                } else if (data.fork_state == 'Finished') {


                    $('.precomputed_sales_progress_bar_bg_' + type).addClass('hide').html('')
                    $('.precomputed_sales_progress_bar_' + type).css('width', '0px').removeClass('hide').attr('title', '').html('')
                    $('.precomputed_sales_operations_' + type).removeClass('hide')
                    $('.progress_bar_table_' + type).addClass('hide')

                    $('.label_calculate_sales_' + type).addClass('hide')
                    $('.label_recalculate_sales_' + type).removeClass('hide')

                }


                if (data.object_extra_data != undefined) {
                    $('.precomputed_sales_updated_' + type).html(data.object_extra_data.last_update)
                }

            }
        })


    }

</script>