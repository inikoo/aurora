<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2017 at 22:54:36 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_timeseries_record_showcase($data, $smarty, $user, $db, $account) {


    $timeseries_record = $data['_object'];


    if (!$timeseries_record->id) {
        return "";
    }
    //print $timeseries_record->get('Timeseries Frequency');
    switch ($timeseries_record->get('Timeseries Frequency')) {
        case 'Monthly':
        case 'Quarterly':
        case 'Yearly':


            $sql = sprintf(
                'SELECT `Timeseries Record Key` FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries_record->get('Timeseries Record Timeseries Key'),
                prepare_mysql(date('Y-m-d', strtotime($timeseries_record->get('Timeseries Record Date').' -1 year')))
            );
            //print $sql;

            if ($result=$db->query($sql)) {
                if ($row = $result->fetch()) {

                    //print_r($row);

                    $timeseries_record_1yb=get_object('timeseries_record',$row['Timeseries Record Key']);
                    //print_r($timeseries_record_1yb);
                    $delta_1yb=array(
                        'Supplier Deliveries'=>array(
                            'delta_percentage'=>delta_icon($timeseries_record->get('Timeseries Record Supplier Deliveries'),$timeseries_record_1yb->get('Timeseries Record Supplier Deliveries')).' '.
                                percentage($timeseries_record->get('Timeseries Record Supplier Deliveries'),$timeseries_record_1yb->get('Timeseries Record Supplier Deliveries')),
                            'delta'=>number($timeseries_record->get('Timeseries Record Supplier Deliveries')-$timeseries_record_1yb->get('Timeseries Record Supplier Deliveries')),
                            'diff'=>$timeseries_record->get('Timeseries Record Supplier Deliveries')-$timeseries_record_1yb->get('Timeseries Record Supplier Deliveries')

                        ),
                        'Purchased Amount'=>array(
                            'delta_percentage'=>delta_icon($timeseries_record->get('Timeseries Record Purchased Amount'),$timeseries_record_1yb->get('Timeseries Record Purchased Amount')).' '.
                                percentage($timeseries_record->get('Timeseries Record Purchased Amount'),$timeseries_record_1yb->get('Timeseries Record Purchased Amount')),
                            'delta'=>money($timeseries_record->get('Timeseries Record Purchased Amount')-$timeseries_record_1yb->get('Timeseries Record Purchased Amount'),$account->get('Currency Code')),
                            'diff'=>$timeseries_record->get('Timeseries Record Purchased Amount')-$timeseries_record_1yb->get('Timeseries Record Purchased Amount')

                        ),
                        'Deliveries'=>array(
                            'delta_percentage'=>delta_icon($timeseries_record->get('Timeseries Record Deliveries'),$timeseries_record_1yb->get('Timeseries Record Deliveries')).' '.
                                percentage($timeseries_record->get('Timeseries Record Deliveries'),$timeseries_record_1yb->get('Timeseries Record Deliveries')),
                            'delta'=>number($timeseries_record->get('Timeseries Record Deliveries')-$timeseries_record_1yb->get('Timeseries Record Deliveries')),
                            'diff'=>$timeseries_record->get('Timeseries Record Deliveries')-$timeseries_record_1yb->get('Timeseries Record Deliveries')

                        ),
                        'Dispatched'=>array(
                            'delta_percentage'=>delta_icon($timeseries_record->get('Timeseries Record Dispatched'),$timeseries_record_1yb->get('Timeseries Record Dispatched')).' '.
                                percentage($timeseries_record->get('Timeseries Record Dispatched'),$timeseries_record_1yb->get('Timeseries Record Dispatched')),
                            'delta'=>number($timeseries_record->get('Timeseries Record Dispatched')-$timeseries_record_1yb->get('Timeseries Record Dispatched')),
                            'diff'=>$timeseries_record->get('Timeseries Record Dispatched')-$timeseries_record_1yb->get('Timeseries Record Dispatched')

                        ),
                        'Sales'=>array(
                            'delta_percentage'=>delta_icon($timeseries_record->get('Timeseries Record Sales'),$timeseries_record_1yb->get('Timeseries Record Sales')).' '.
                                percentage($timeseries_record->get('Timeseries Record Sales'),$timeseries_record_1yb->get('Timeseries Record Sales')),
                            'delta'=>money($timeseries_record->get('Timeseries Record Sales')-$timeseries_record_1yb->get('Timeseries Record Sales'),$account->get('Currency Code')),
                            'diff'=>$timeseries_record->get('Timeseries Record Sales')-$timeseries_record_1yb->get('Timeseries Record Sales')

                        )
                       

                    );
                    //print_r($delta_1yb);
                    $smarty->assign('delta_1yb', $delta_1yb);


                }
            }else {
            	print_r($error_info=$db->errorInfo());
            	print "$sql\n";
            	exit;
            }

            break;
    }


    $smarty->assign('account', $account);

    $smarty->assign('timeseries_record', $timeseries_record);

    return $smarty->fetch('showcase/timeseries_record.tpl');


}


?>