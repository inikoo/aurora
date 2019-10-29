<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created:

 Version 2.0 Tue 29 Oct 2019 14:58:35 +0800 MYT. Kuala Lumpur, Malaysia
*/


switch ($_REQUEST['action']) {
    case 'send_tag_id':

        if (empty($_REQUEST['box_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'box_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (empty($_REQUEST['tag_id'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'tag_id needed'
            );
            echo json_encode($response);
            exit;
        }

        if (empty($_REQUEST['date'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'date needed'
            );
            echo json_encode($response);
            exit;
        }


        /**
         * @var $box \Clocking_Machine
         */
        $box = get_object('Clocking_Machine', $_REQUEST['box_key']);

        if (!$box->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'box not found'
            );
            echo json_encode($response);
            exit;
        }




        $nfc_tag_data=array(
            'Clocking Machine NFC Tag ID'=>$_REQUEST['tag_id'],
            'Clocking Machine NFC Tag Last Scan'=>$_REQUEST['date'],
        );
        $nfc_tag=$box->create_nfc_tag($nfc_tag_data);

        if($box->new_nfc_tag){
            if($nfc_tag->id){
                $response = array(
                    'state' => 'Pending_Tag',
                    'nfc_tag_hash'=>$nfc_tag->get('Hash')
                );
                echo json_encode($response);
                exit;
            }else{
                $response = array(
                    'state' => 'Fail',
                    'msg'   => 'nfc card creation fail e1'
                );
                echo json_encode($response);
                exit;
            }

        }elseif($nfc_tag->id){



            $scan_data=$nfc_tag->scanned($box->id,$api_key_key,$_REQUEST['date']);




            if($scan_data['state']=='Success'){
                $response = array(
                    'state' => 'Success',
                    'staff_name'=>$scan_data['staff_name']
                );
            }elseif($scan_data['state']=='Pending_Tag'){
                $response = array(
                    'state' => 'Pending_Tag',
                    'nfc_tag_hash'=>$nfc_tag->get('Hash')
                );
            }else{
                $response = array(
                    'state' => 'Fail',
                    'msg'   => $scan_data['msg']
                );
            }







            echo json_encode($response);
            exit;
        }else{
            $response = array(
                'state' => 'Fail',
                'msg'   => 'nfc card creation fail e2'
            );
            echo json_encode($response);
            exit;
        }



        break;

}


