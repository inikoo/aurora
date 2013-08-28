<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
require_once 'common.php';

require_once 'class.Image.php';


require_once 'ar_edit_common.php';
if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>'Non acceptable request (t)');
    echo json_encode($response);
    exit;
}


$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case 'update_image':
    $data=prepare_values($_REQUEST,array(
                             'new_value'=>array('type'=>'string'),
                             'scope'=>array('type'=>'string'),
                             'scope_key'=>array('type'=>'key'),
                             'image_key'=>array('type'=>'key'),
                             'key'=>array('type'=>'string')
                         ));
    update_image($data);

    break;

case 'upload_image':

    $data=prepare_values($_REQUEST,array(
                             'scope'=>array('type'=>'string'),
                             'scope_key'=>array('type'=>'key')

                         ));
    upload_image($data);

    break;
default:

    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);

}


function update_image($data) {

    switch ($data['scope']) {
    case 'product':
        include_once('class.Product.php');
        $scope=new Product('pid',$data['scope_key']);
        break;
    case 'family':
        include_once('class.Family.php');
        $scope=new Family('id',$data['scope_key']);

        break;
    case 'department':
        include_once('class.Department.php');
        $scope=new Department('id',$data['scope_key']);
        break;
    case 'part':
        include_once('class.Part.php');
        $scope=new Part($data['scope_key']);
        break;
    case 'customer_profile':
        include_once('class.User.php');
        $scope=new User($data['scope_key']);
        break;
    case 'favicon':
        include_once('class.Site.php');
        $scope=new Site($data['scope_key']);
        break;
    default:
        $response=array('state'=>404,'resp'=>'Operation not found');
        echo json_encode($response);
        return;

        break;
    }






    if (!$scope->id) {
        $response=array('state'=>404,'resp'=>'Scope object not found');
        echo json_encode($response);
        return;

    }

    switch ($data['key']) {
    case 'principal':
        $scope->update_main_image($data['image_key']);
        $response=array('state'=>200,'principal_image_key'=>$scope->get_main_image_key());
        break;
    case 'caption':
        $scope->update_image_caption($data['image_key'],$data['new_value']);
        if ($scope->updated) {
           $response=array('state'=>200,'newvalue'=>$scope->new_value);
        } else {
           $response=array('state'=>200,'msg'=>$scope->msg);

        }

        break;

    case('delete'):
        $scope->remove_image($data['image_key']);
        if ($scope->updated) {
           $response=array('state'=>200,'image_key'=>$data['image_key']);
        } else {
           $response=array('state'=>400,'msg'=>$scope->msg);

        }


        break;
    default:
        $response=array('state'=>404,'resp'=>'Operation not found');
    
        break;
    }





    echo json_encode($response);

}




function upload_image($data) {


    if (isset($_FILES['testFile']['tmp_name'])) {

        include_once('class.Image.php');
        $image_data=array(
                        'file'=>$_FILES['testFile']['tmp_name'],
                        'source_path'=>'',
                        'name'=>$_FILES['testFile']['name'],
                        'caption'=>''
                    );



        $image=new Image('find',$image_data,'create');
        if (!$image->error) {

            $_scope=$data['scope'];


            switch ($_scope) {
            case 'product':
                include_once('class.Product.php');
                $scope=new Product('pid',$data['scope_key']);
                break;
            case 'family':
                include_once('class.Family.php');
                $scope=new Family('id',$data['scope_key']);

                break;
            case 'department':
                include_once('class.Department.php');
                $scope=new Department('id',$data['scope_key']);
                break;
            case 'part':
                include_once('class.Part.php');
                $scope=new Part($data['scope_key']);
                break;
            case 'customer_profile':
                include_once('class.User.php');
                $scope=new User($data['scope_key']);
                break;
            case 'favicon':
                include_once('class.Site.php');
                $scope=new Site($data['scope_key']);
                break;
            default:
                $response=array('state'=>404,'resp'=>'Operation not found');
                echo json_encode($response);
                return;

                break;
            }


            $scope->add_image($image->id);
            
            if($_scope=='part'){
            include_once('class.Product.php');
            	$products_pids=$scope->get_all_product_ids();
            	foreach($products_pids as $tmp){
            		
            		$product=new Product('pid',$tmp['Product ID']);
            		if($product->data['Product Use Part Pictures']=='Yes')
            		$product->add_image($image->id);
            		
            	}
            
            }
            

            if ($scope->updated) {

                //$scope->update_main_image();
                $msg=array(
                         'set_main'=>_('Set Main'),
                         'main'=>_('Main Image'),
                         'caption'=>_('Caption'),
                         'save_caption'=>_('Save caption'),
                         'delete'=>_('Delete')
                     );
                $response= array('state'=>200,'msg'=>$msg,'image_key'=>$image->id,'data'=>$scope->new_value);
            }
            elseif($scope->nochange) {

                $response= array('state'=>201,'msg'=>$scope->msg);


            }
            else {

                $response= array('state'=>400,'msg'=>$scope->msg);

            }


            echo json_encode($response);
            return;
        } else {
            $response= array('state'=>400,'msg'=>$image->msg);
            echo json_encode($response);
            return;
        }
    } else {
        $response= array('state'=>400,'msg'=>'no image');
        echo json_encode($response);
        return;
    }
}