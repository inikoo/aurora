<?php
require_once 'class.Timer.php';

require_once 'common.php';
require_once 'class.Company.php';
require_once 'class.Supplier.php';
require_once 'ar_edit_common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('set_main_address'):

    update_main_address();
    break;
case('new_company'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')

                         ));
    new_company($data);

    break;

case('new_customer'):

    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')

                         ));
    new_customer($data);


    break;

case('create_contact'):

case('new_contact'):
    $data=prepare_values($_REQUEST,array(
                             'value'=>array('type'=>'json array'),
                             'subject'=>array('type'=>'string'),
                             'subject_key'=>array('type'=>'key')
                         ));
    new_contact($data);

    break;
case('new_address'):
    new_address();
    break;
case('new_Delivery_address'):
case('new_delivery_address'):
    new_address();
    break;

case('edit_address_type'):
    edit_address_type();
    break;
case('edit_address'):
    $data=prepare_values($_REQUEST,array(
                             'value'=>array('type'=>'json array'),
                              'subject'=>array('type'=>'enum',
                                              'valid values regex'=>'/company|contact|supplier|customer/i'
                                             ),
                             'subject_key'=>array('type'=>'key'),
                             'id'=>array('type'=>'key')
                         ));
    edit_address($data);
    break;
case('edit_delivery_address'):
    edit_delivery_address();
    break;
case('edit_company'):
    edit_company();
    break;
case('edit_billing_data'):

case('edit_customer'):
    edit_customer();
    break;
case('edit_customers'):
    list_customers();
    break;
case('create_company_area'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')
                                      ,'parent_key'=>array('type'=>'key')
                         ));
    new_company_area($data);
    break;
case('create_company_department'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')
                                      ,'parent_key'=>array('type'=>'key')
                         ));
    new_company_department($data);
    break;
case('edit_contact'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key')
                                  ,'value'=>array('type'=>'json array')
                                           ,'subject_key'=>array('type'=>'key')
                         ));
    edit_contact($data);
    break;
case('edit_email'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),
                             'value'=>array('type'=>'json array','required elements'=>array(
                                                'Email'=>'string',
                                                'Email Key'=>'numeric'
                                            )),
                             'subject_key'=>array('type'=>'key'),
                             'subject'=>array('type'=>'enum',
                                              'valid values regex'=>'/company|contact/i'
                                             )
                         ));

    edit_email($data);
    break;
case('delete_company_area'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key')
                                  ,'delete_type'=>array('type'=>'string')
                         ));
    delete_company_area($data);
    break;
case('delete_company_department'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key')
                                  ,'delete_type'=>array('type'=>'string')
                         ));
    delete_company_department($data);
    break;
case ('edit_corporation'):
    $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'string'),
                             'newvalue'=>array('type'=>'string')
                         ));
    edit_corporation($data);
    break;

case('delete_contact'):
    $data=prepare_values($_REQUEST,array(
                             'contact_key'=>array('type'=>'key')
                         ));
    delete_contact($data);
    break;
case('remove_address'):
case('delete_address'):
    delete_address();
    break;
case('remove_email'):
case('delete_email'):
    delete_email();
    break;
case('delete_mobile'):
    delete_mobile();
    break;
case('edit_telecom'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),
                             'value'=>array(
                                         'type'=>'json array',
                                         'required elements'=>array(
                                                                 'Telecom'=>'string',
                                                                 'Telecom Key'=>'numeric',
                                                                 'Telecom Type'=>'string',

                                                                 'Telecom Is Main'=>'string',
                                                             )),
                             'subject_key'=>array('type'=>'key'),
                             'subject'=>array('type'=>'enum',
                                              'valid values regex'=>'/company|contact/i'
                                             )
                         ));
    edit_telecom($data);
    break;

case('edit_mobile'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),
                             'value'=>array(
                                         'type'=>'json array',
                                         'required elements'=>array(
                                                                 'Telecom'=>'string',
                                                                 'Telecom Key'=>'numeric',
                                                                 'Telecom Type'=>'string',
                                                                 'Telecom Is Main'=>'string',
                                                             )),
                             'subject_key'=>array('type'=>'key'),
                             'subject'=>array('type'=>'enum',
                                              'valid values regex'=>'/company|contact/i'
                                             )
                         ));
    edit_mobile($data);
    break;
case('add_mobile'):
    $data=prepare_values($_REQUEST,array(
                             'value'=>array(
                                         'type'=>'json array',
                                         'required elements'=>array(
                                                                 'Telecom'=>'string',
                                                                 'Telecom Key'=>'numeric',
                                                                 'Telecom Type'=>'string',
                                                                 'Telecom Is Main'=>'string',
                                                             )),
                             'subject_key'=>array('type'=>'key'),
                             'subject'=>array('type'=>'enum',
                                              'valid values regex'=>'/company|contact/i'
                                             )
                         ));
    add_mobile($data);
    break;
case('new_corporation'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')

                         ));
    new_corporation($data);

    break;
case('edit_company_areas'):
    list_company_areas();
    break;
case('edit_company_staff'):
    list_company_staff();
    break;
case('edit_company_departments'):
    list_company_departments();
    break;
case('edit_company_area'):
    $data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
    edit_company_area($data);
    break;
case('edit_company_department'):
    $data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
    edit_company_area($data);
    break;
default:

    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);
}
function edit_contact($data) {
    global $editor;


    $contact=new Contact($data['id']);

    if (!$contact->id) {
        $response=array('state'=>400,'msg'=>_('Contact not found'));
    }

    $translator=array(
                    'Contact_Name_Components'=>'Contact Name Components'
                                              ,'Contact_Gender'=>'Contact Gender'
                                                                ,'Contact_Title'=>'Contact Title'
                                                                                 ,'Contact_Profession'=>'Contact Profession'
                );
    $components_translator=array(
                               'Contact_First_Name'=>'Contact First Name'
                                                    ,'Contact_Surname'=>'Contact Surname'
                                                                       ,'Contact_Suffix'=>'Contact Suffix'
                                                                                         ,'Contact_Salutation'=>'Contact Salutation'

                           );


    foreach($data['value'] as $key=>$value) {
        if (array_key_exists($key, $translator)) {

            if ($key=='Contact_Name_Components') {
                $components=array();
                foreach($value as $component_key => $component_value) {
                    if (array_key_exists($component_key, $components_translator))
                        $components[$components_translator[$component_key]]=$component_value;

                }
                $contact_data[$translator[$key]]=$components;

            } else
                $contact_data[$translator[$key]]=$value;

        }

    }

    $contact->editor=$editor;


// print_r($contact_data);
// return;
    $contact->update($contact_data);

    $contact->reread();
    if ($contact->error_updated) {
        $response=array('state'=>200,'action'=>'error','msg'=>$contact->msg_updated);
    } else {

        if ($contact->updated) {
            $contact->reread();
            $updated_data_name_components=array(
                                              'Contact_First_Name'=>$contact->data['Contact First Name']
                                                                   ,'Contact_Surname'=>$contact->data['Contact Surname']
                                                                                      ,'Contact_Suffix'=>$contact->data['Contact Suffix']
                                                                                                        ,'Contact_Salutation'=>$contact->data['Contact Salutation']

                                          );

            $updated_data=array(
                              'Contact_Name'=>$contact->data['Contact Name']
                                             ,'Name_Data'=>$updated_data_name_components
                                                          ,'Contact_Gender'=>$contact->data['Contact Gender']
                                                                            ,'Contact_Title'=>$contact->data['Contact Title']
                                                                                             ,'Contact_Profession'=>$contact->data['Contact Profession']
                          );



            $response=array('state'=>200,'action'=>'updated','msg'=>$contact->msg_updated,'xhtml_subject'=>$contact->display('card'),'updated_data'=>$updated_data);
        } else {
            $response=array('state'=>200,'action'=>'nochange','msg'=>$contact->msg_updated);

        }

    }

    echo json_encode($response);

}
function edit_company() {
    global $editor;
    if (!isset($_REQUEST['key']) ) {
        $response=array('state'=>400,'msg'=>'Error no key');
        echo json_encode($response);
        return;
    }
    if ( !isset($_REQUEST['newvalue']) ) {
        $response=array('state'=>400,'msg'=>'Error no value');
        echo json_encode($response);
        return;
    }
    if ( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ) {
        $company_key=$_SESSION['state']['company']['id'];
    } else
        $company_key=$_REQUEST['id'];

    $company=new Company($company_key);
    $company->editor=$editor;
    if (!$company->id) {
        $response=array('state'=>400,'msg'=>_('Company not found'));
        echo json_encode($response);
        return;
    }

    $translator=array(
                    'name'=>'Company Name',
                    'fiscal_name'=>'Company Fiscal Name',
                    'tax_number'=>'Company Tax Number',
                    'registration_number'=>'Company Registration Number'


                );

    if (array_key_exists($_REQUEST['key'], $translator)) {

        $update_data=array(

                         $translator[$_REQUEST['key']]=>stripslashes(urldecode($_REQUEST['newvalue']))
                     );
        //print_r($update_data);
        $company->update($update_data);

        if ($company->error_updated) {
            $response=array('state'=>200,'action'=>'error','msg'=>$company->msg_updated,'key'=>$_REQUEST['key']);
        } else {

            if ($company->updated) {
                $response=array('state'=>200,'action'=>'updated','msg'=>$company->msg_updated,'key'=>$_REQUEST['key'],'newvalue'=>$company->new_value);
            } else {
                $response=array('state'=>200,'action'=>'nochange','msg'=>$company->msg_updated,'key'=>$_REQUEST['key']);

            }

        }


    } else {
        $response=array('state'=>400,'msg'=>_('Key not in Company'));
    }
    echo json_encode($response);

}
function edit_email($data) {
    global $editor;
    //  print_r($data);
    if (preg_match('/^company$/i',$data['subject']))
        $subject=new Company($data['subject_key']);
    else {
        $subject=new Contact($data['subject_key']);
    }

    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Subject not found');
        echo json_encode($response);
        return;
    }

    if (!isset($data['value']['Email'])) {
        $response=array('state'=>400,'msg'=>'No email value');
        echo json_encode($response);
        return;
    }

    $editing=false;
    $creating=false;

    $msg=_('No changes');

    if ($data['value']['Email Key']>0) {
        $action='updated';
        $email=new Email('id',$data['value']['Email Key']);
        if (!$email->id) {
            $response=array('state'=>400,'msg'=>'Email not found');
            echo json_encode($response);
            return;
        }
        $email->set_editor($editor);
        $email->update(array('Email'=>$data['value']['Email']));
        if ($email->error_updated) {
            $response=array('state'=>200,'action'=>'error','msg'=>$email->msg_updated,'email_key'=>$data['value']['Email Key']);
            echo json_encode($response);
            return;
        }

        if ($email->updated)
            $msg=_('Email updated');

        $update_data=array(
                         'Email Key'=>$data['value']['Email Key'],
                         'Email Description'=>$data['value']['Email Description'],
                         'Email Is Main'=>$data['value']['Email Is Main'],
                         'Email Contact Name'=>$data['value']['Email Contact Name']
                     );


        $subject->associate_email($email->id);
        if ($data['value']['Email Is Main']=='Yes')
            $subject->update_principal_email($email->id);
        if ($subject->updated)
            $msg=_('Email updated');
        $email->set_scope($data['subject'],$data['subject_key']);

    } else {
        $action='created';
        $email_data=array(
                        'Email'=>$data['value']['Email'],
                        'Email Description'=>$data['value']['Email Description'],
                        'Email Is Main'=>$data['value']['Email Is Main'],
                        'Email Contact Name'=>$data['value']['Email Contact Name']
                    );


        $email=new Email('find create',$email_data);
        if ($email->found) {
            $response=array('state'=>200,'action'=>'error','msg'=>'Email Found','email_key'=>$email->id);
            echo json_encode($response);
            return;
        }

        $subject->associate_email($email->id);


        if ($subject->error) {
            $response=array('state'=>200,'action'=>'error','msg'=>$subject->msg_updated,'email_key'=>$data['value']['Email Key']);
            echo json_encode($response);
            return;
        }
        if ($subject->inserted_email) {
            $email->set_scope($data['subject'],$data['subject_key']);
            $msg=_("Email created");
        } else {
            $response=array('state'=>200,'action'=>'nochange','msg'=>$subject->msg_updated,'email_key'=>$data['value']['Email Key']);
            echo json_encode($response);
            return;
        }
    }
    $updated_email_data=array(
                            'Email'=>$email->data['Email'],
                            'Email_Description'=>$email->data['Email Description'],
                            'Email_Contact_Name'=> $email->data['Email Contact Name'],
                            'Email_Is_Main'=> $email->data['Email Is Main']
                        );
    $subject->reread();
    $response=array(
                  'state'=>200,
                  'action'=>$action,
                  'msg'=>$msg,
                  'email_key'=>$data['value']['Email Key'],
                  'updated_data'=>$updated_email_data,
                  'xhtml_subject'=>$subject->display('card'),
                  'main_email_key'=>$subject->get_principal_email_key()
              );

    echo json_encode($response);

}



function add_mobile($data) {
    global $editor;
    if (preg_match('/^company$/i',$data['subject'])) {
        //todo things here
    }

    $contact=new Contact($data['subject_key']);

    $action='created';


    $mobile_data=array(
                     'Telecom'=>$data['value']['Telecom'],
                     'Telecom Type'=>$data['value']['Telecom Type'],
                     'Telecom Type'=>'Mobile',
                     'Telecom Raw Number'=>$data['value']['Telecom'],
                     'editor'=>$editor
                 );

    $mobile=new Telecom("find in Contact ".$contact->id." create  country code ".$contact->data['Contact Main Country Code']."   ",$mobile_data);




    if (!$mobile->id) {
        $response=array('state'=>200,'action'=>'error','msg'=>$mobile->msg);
        echo json_encode($response);
        return;
    }

    $contact->associate_mobile($mobile->id);
    if ($data['value']['Telecom Is Main']=='Yes' ) {
        $contact->update_principal_mobil($mobile->id);
    }

    if ($contact->add_telecom) {

        $updated_telecom_data=array(
                                  "Mobile_Key"=>$mobile->id,
                                  "Mobile"=>$mobile->display(),
                                  "Country_Code"=>$mobile->data['Telecom Country Telephone Code'],
                                  "National_Access_Code"=>$mobile->data['Telecom National Access Code'],
                                  "Number"=>$mobile->data['Telecom Number'],
                                  "Telecom_Is_Main"=>$data['value']['Telecom Is Main'],
                                  "Telecom Type Description"=>$mobile->data['Telecom Type'],
                              );

        $msg='';
        $response=array(
                      'state'=>200,
                      'action'=>$action,
                      'msg'=>$msg,
                      'telecom_key'=>$mobile->id,
                      'updated_data'=>$updated_telecom_data,
                      'xhtml_subject'=>$contact->display('card'),
                      'main_mobile_key'=>$contact->get_principal_mobile_key()
                  );

        echo json_encode($response);
        return;
    } else {
        $response=array('state'=>200,'action'=>'nochange','msg'=>$contact->msg_updated);
        echo json_encode($response);
        return;

    }



}
function edit_mobile($data) {
    global $editor;
    if (preg_match('/^company$/i',$data['subject'])) {
        //todo things here
    }

    $contact=new Contact($data['subject_key']);


    $mobile=new Telecom('id',$data['value']['Telecom Key']);
    if (!$mobile->id) {
        $response=array('state'=>400,'msg'=>'Telecom not found');
        echo json_encode($response);
        return;
    }
    $mobile->set_editor($editor);
    $mobile->update_number($data['value']['Telecom']);
    if ($mobile->error_updated) {
        $response=array('state'=>200,'action'=>'error','msg'=>$mobile->msg_updated);
        echo json_encode($response);
        return;
    }




    if ($data['value']['Telecom Is Main']=='Yes' ) {
        $contact->update_principal_mobil($mobile->id);
    }

    if ($mobile->updated or $contact->updated) {

        $updated_telecom_data=array(
                                  "Mobile_Key"=>$mobile->id,
                                  "Mobile"=>$mobile->display(),
                                  "Country_Code"=>$mobile->data['Telecom Country Telephone Code'],
                                  "National_Access_Code"=>$mobile->data['Telecom National Access Code'],
                                  "Number"=>$mobile->data['Telecom Number'],
                                  "Telecom_Is_Main"=>$data['value']['Telecom Is Main'],
                                  "Telecom Type Description"=>$mobile->data['Telecom Type'],
                              );
        $action='updated';
        $msg=_('Telecom updated');
        $response=array(
                      'state'=>200,
                      'action'=>$action,
                      'msg'=>$msg,
                      'telecom_key'=>$mobile->id,
                      'updated_data'=>$updated_telecom_data,
                      'xhtml_subject'=>$contact->display('card'),
                      'main_mobile_key'=>$contact->get_principal_mobile_key()
                  );

        echo json_encode($response);
        return;
    } else {
        $response=array('state'=>200,'action'=>'nochange','msg'=>$mobile->msg_updated);
        echo json_encode($response);
        return;

    }


}


function edit_telecom($data) {
    global $editor;

    if (preg_match('/^company$/i',$data['subject'])) {
        $subject=new Company($data['subject_key']);
        $subject_type='Company';
    } else {
        $subject=new Contact($data['subject_key']);
        $subject_type='Contact';

    }

    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Subject not found');
        echo json_encode($response);
        return;
    }

    $address_key=0;
    if (array_key_exists('Address Key',$data['value'])) {
        $address_key=$data['value']['Address Key'];
    }

    $editing=false;
    $creating=false;

    $msg=_('No changes');

    if ($data['value']['Telecom Key']>0) {
        $action='updated';
        $telecom=new Telecom('id',$data['value']['Telecom Key']);
        if (!$telecom->id) {
            $response=array('state'=>400,'msg'=>'Telecom not found');
            echo json_encode($response);
            return;
        }
        $telecom->set_editor($editor);
        $telecom->update_number($data['value']['Telecom']);
        if ($telecom->error_updated) {
            $response=array('state'=>200,'action'=>'error','msg'=>$telecom->msg_updated);
            echo json_encode($response);
            return;
        }

        if ($telecom->updated)
            $msg=_('Telecom updated');
        /*
                $update_data=array(
                                 'Telecom Key'=>$data['value']['Telecom Key'],
                                 'Telecom Is Main'=>$data['value']['Telecom Is Main'],
                                 'Telecom Type'=>$data['value']['Telecom Type']
                             );
                $subject->add_tel($update_data);
                if ($subject->updated)
                    $msg=_('Telecom updated');
                $telecom->set_scope($data['subject'],$data['subject_key']);
        */


    } else {
        $action='created';


        $telephone_data=array(
                            'Telecom'=>$data['value']['Telecom'],
//                        'Telecom Is Main'=>$data['value']['Telecom Is Main'],
                            'Telecom Type'=>$data['value']['Telecom Type']
                        );




        if ($data['value']['Telecom Category']=='Mobile') {
            $telephone_data['Telecom Type']='Mobile';
        }


        $telephone_data['Telecom Raw Number']=$data['value']['Telecom'];
        $telephone_data['editor']=$editor;
        // print_r($telephone_data);
        //exit;
        $telephone=new Telecom("find in $subject_type ".$subject->id." create  country code ".$subject->data[$subject_type.' Main Country Code']."   ",$telephone_data);

        if (!$telephone->id) {
            $response=array('state'=>200,'action'=>'error','msg'=>'Error finding the telecom');
            echo json_encode($response);
            return;
        }


        if ($data['value']['Telecom Category']=='Mobile') {
            $subject->associate_mobile($telephone->id);
        }

    }



    if ($data['value']['Telecom Is Main']=='Yes' and $data['value']['Telecom Category']=='Mobile') {
        $subject->update_principal_mobil($telephone->id);

    }



    if ($subject->error) {
        $response=array('state'=>200,'action'=>'error','msg'=>$subject->msg_updated);
        echo json_encode($response);
        return;
    }

    if ($subject->add_telecom) {
        $updated_telecom_data=array();

        $msg='';
        $response=array(
                      'state'=>200,
                      'action'=>$action,
                      'msg'=>$msg,
                      'telecom_key'=>$telephone->id,
                      'updated_data'=>$updated_telecom_data,
                      'xhtml_subject'=>$subject->display('card'),
                      'main_telecom_key'=>$subject->get_main_telecom_key()
                  );

        echo json_encode($response);
        return;
    } else {
        $response=array('state'=>200,'action'=>'nochange','msg'=>$subject->msg_updated);
        echo json_encode($response);
        return;

    }


}
function new_address() {
    global $editor;
    $warning='';
    if ( !isset($_REQUEST['value']) ) {
        $response=array('state'=>400,'msg'=>'Error no value');
        echo json_encode($response);
        return;
    }

    $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
    $tmp=preg_replace('/\\\\\"/','"',$tmp);

    $raw_data=json_decode($tmp, true);

    if (!is_array($raw_data)) {
        $response=array('state'=>400,'msg'=>'Wrong value');
        echo json_encode($response);
        return;
    }


    if ( !isset($_REQUEST['subject'])
            or !is_numeric($_REQUEST['subject_key'])
            or $_REQUEST['subject_key']<=0
            or !preg_match('/^(Company|Contact|Customer)$/',$_REQUEST['subject'])

       ) {
        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
        echo json_encode($response);
        return;
    }

    $subject=$_REQUEST['subject'];
    $subject_key=$_REQUEST['subject_key'];

    switch ($subject) {
    case('Company'):
        $subject_object=new Company($subject_key);
        break;
    case('Contact'):
        $subject_object=new Contact($subject_key);
        break;
    case('Customer'):
        $subject_object=new Customer($subject_key);
        break;
    default:

        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key (2)');
        echo json_encode($response);
        return;

    }

    $translator=array(
                    'country_code'=>'Address Country Code'
                                   ,'country_d1'=>'Address Country First Division'
                                                 ,'country_d2'=>'Address Country Second Division'
                                                               ,'town'=>'Address Town'
                                                                       ,'town_d1'=>'Address Town First Division'
                                                                                  ,'town_d2'=>'Address Town Second Division'
                                                                                             ,'postal_code'=>'Address Postal Code'
                                                                                                            ,'street'=>'Street Data'
                                                                                                                      ,'internal'=>'Address Internal'
                                                                                                                                  ,'building'=>'Address Building'
                                                                                                                                              ,'type'=>'Address Type'
                                                                                                                                                      ,'function'=>'Address Function'
                                                                                                                                                                  ,'description'=>'Address Description'

                );


    $data=array('editor'=>$editor);
    foreach($raw_data as $key=>$value) {
        if (array_key_exists($key, $translator)) {
            $data[$translator[$key]]=$value;
        }
    }
    // print $subject;

    $address=new Address("find in $subject $subject_key create",$data);

    if (!$address->id) {
        $response=array('state'=>400,'msg'=>'Error can not create address');
        echo json_encode($response);
        return;
    }
    if ($address->found) {
        $address_parents=  $address->get_parent_keys($subject);
        if (array_key_exists($subject_key,$address_parents)) {
            $response=array('state'=>200,'action'=>'nochange','msg'=>_('Address already in company'));
            echo json_encode($response);
            return;

        } else {
            $warning=_('Warning, address found also associated with')." ";
            switch ($subject) {
            case 'Customer':
                $parent_label='';
                foreach($address_parents as $parent_key) {
                    $parent=new Customer($parent_key);
                    $parent_label.=sprintf(', <a href="customer.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
                }
                $parent_label=preg_replace('/^,/','',$parent_label);
                $warning.=ngettext(count($address_parents),'Customer','Customers').' '.$parent_label;
                break;
            case 'Company':
                $parent_label='';
                foreach($address_parents as $parent_key) {
                    $parent=new Company($parent_key);
                    $parent_label.=sprintf(', <a href="company.php?id=%d">%s</a>',$parent->id,$parent->data['Company Name']);
                }
                $parent_label=preg_replace('/^,/','',$parent_label);
                $warning.=ngettext(count($address_parents),'Company','Companies').' '.$parent_label;
                break;
            case('Contact'):


                $parent_label='';
                foreach($address_parents as $parent_key) {
                    $parent=new Contact($parent_key);
                    if ($parent->data['Contact Company Key']!=$subject->data['Contact Company Key'] )
                        $parent_label.=sprintf(', <a href="contact.php?id=%d">%s</a>',$parent->id,$parent->display('name'));
                }
                if ($parent_label=='')
                    $warning='';
                else {
                    $parent_label=preg_replace('/^,/','',$parent_label);
                    $warning.=ngettext(count($address_parents),'Contact','Contacts').' '.$parent_label;
                }
                break;


            default:
                break;
            }


        }

    }

    if ($subject=='Customer') {
        $subject_object->associate_delivery_address($address->id);
    } else
        $subject_object->associate_address($address->id);

    if ($subject_object->updated) {

        $address_bridge_data=$subject_object->get_address_bridge_data($address->id);
        if (!$address_bridge_data) {
            $response=array('state'=>400,'action'=>'error','msg'=>'Address Not bridged');
            echo json_encode($response);
            return;
        }

        $updated_address_data=array(
                                  'country'=>$address->data['Address Country Name']
                                            ,'country_code'=>$address->data['Address Country Code']
                                                            ,'country_d1'=> $address->data['Address Country First Division']
                                                                          ,'country_d2'=> $address->data['Address Country Second Division']
                                                                                        ,'town'=> $address->data['Address Town']
                                                                                                ,'postal_code'=> $address->data['Address Postal Code']
                                                                                                               ,'town_d1'=> $address->data['Address Town First Division']
                                                                                                                          ,'town_d2'=> $address->data['Address Town Second Division']
                                                                                                                                     ,'fuzzy'=> $address->data['Address Fuzzy']
                                                                                                                                              ,'street'=> $address->display('street')
                                                                                                                                                        ,'building'=>  $address->data['Address Building']
                                                                                                                                                                    ,'internal'=> $address->data['Address Internal']
                                                                                                                                                                                ,'type'=>$address_bridge_data['Address Type']
                                                                                                                                                                                        ,'function'=>$address_bridge_data['Address Function']
                                                                                                                                                                                                    ,'description'=>$address->data['Address Description']
                              );


        $response=array(
                      'state'=>200
                              ,'action'=>'created'
                                        ,'msg'=>$subject_object->msg
                                               ,'updated_data'=>$updated_address_data
                                                               ,'xhtml_address'=>$address->display('xhtml')
                                                                                ,'address_key'=>$address->id
                  );
        echo json_encode($response);
        return;

    } else {
        $response=array('state'=>200,'action'=>'nochange','msg'=>_('Address already in company'));
        echo json_encode($response);
        return;
    }

}


function update_main_address() {

    $address_key=$_REQUEST['value'];
    if ( !isset($_REQUEST['subject'])
            or !is_numeric($_REQUEST['subject_key'])
            or $_REQUEST['subject_key']<=0
            or !preg_match('/^(Company|Contact|Customer)$/',$_REQUEST['subject'])

       ) {
        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
        echo json_encode($response);
        return;
    }

    $subject=$_REQUEST['subject'];
    $subject_key=$_REQUEST['subject_key'];
    switch ($subject) {
    case('Company'):
        $subject_object=new Company($subject_key);
        break;
    case('Contact'):
        $subject_object=new Contact($subject_key);
        break;
    case('Customer'):
        $subject_object=new Customer($subject_key);
        break;
    default:

        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key (2)');
        echo json_encode($response);
        return;

    }

    if ($subject=='Customer') {
        $type=$_REQUEST['key'];

        if ($type=='Delivery') {
            $subject_object->update_principal_delivery_address($address_key);
            if ($subject_object->error) {
                $response=array('state'=>400,'msg'=>$subject_object->msg);

            }
            elseif($subject_object->updated) {

                if ( ($subject_object->get('Customer Delivery Address Link')=='Contact') or ( $subject_object->get('Customer Delivery Address Link')=='Billing'  and  ($subject_object->get('Customer Main Address Key')==$subject_object->get('Customer Billing Address Key'))   ) ) {
                    $address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

                }
                elseif($subject_object->get('Customer Delivery Address Link')=='Billing') {
                    $address_comment='<span style="font-weight:600">'._('Same as billing address').'</span>';
                }
                else {
                    $address_comment=$subject_object->delivery_address_xhtml();
                }

                $response=array(
                              'state'=>200
                                      ,'action'=>'changed'
                                                ,'new_main_address'=>$subject_object->display_delivery_address('xhtml')
                                                                    ,'new_main_address_bis'=>$address_comment

                                                                                            ,'new_main_delivery_address_key'=>$subject_object->data['Customer Main Delivery Address Key']

                          );

            }
            else {
                $response=array('state'=>200,'action'=>'no_change','msg'=>_('Nothing to change'));


            }
            echo json_encode($response);
            return;

        }

    }



}


function edit_address_type() {
    global $editor;

    if ( !isset($_REQUEST['value']) ) {
        $response=array('state'=>400,'msg'=>'Error no value');
        echo json_encode($response);
        return;
    }

    $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
    $tmp=preg_replace('/\\\\\"/','"',$tmp);
    //$tmp=$_REQUEST['value'];
    $raw_data=json_decode($tmp, true);
    //   print "$tmp";
    // print_r($raw_data);

    if (!is_array($raw_data)) {
        $response=array('state'=>400,'msg'=>'Wrong value');
        echo json_encode($response);
        return;
    }
    if ( !isset($_REQUEST['id'])  or !is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0  ) {
        $response=array('state'=>400,'msg'=>'Error wrong id');
        echo json_encode($response);
        return;
    }



    if ( !isset($_REQUEST['subject'])
            or !is_numeric($_REQUEST['subject_key'])
            or $_REQUEST['subject_key']<=0
            or !preg_match('/^company|contact$/i',$_REQUEST['subject'])

       ) {
        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
        echo json_encode($response);
        return;
    }

    $subject=$_REQUEST['subject'];
    $subject_key=$_REQUEST['subject_key'];


    $address=new Address('id',$_REQUEST['id']);

    if (!$address->id) {
        $response=array('state'=>400,'msg'=>'Address not found');
        echo json_encode($response);
        return;
    }
    $address->set_editor($editor);
    $address->set_scope($subject,$subject_key);
    $address->update_metadata(
        array('Type'=>$raw_data)
    );


    $updated_data=array();
    foreach($address->get('Type') as $type)
    $updated_data[]=$type;

    if ($address->updated) {
        $response=array(
                      'state'=>200
                              ,'action'=>'updated'
                                        ,'msg'=>$address->msg_updated
                                               ,'key'=>''
                                                      ,'updated_data'=>$updated_data
                  );
    } else {
        if ($address->error_updated)
            $response=array('state'=>200,'action'=>'error','msg'=>$company->msg_updated,'key'=>'');
        else
            $response=array('state'=>200,'action'=>'nochange','msg'=>$address->msg_updated,'key'=>'');

    }


    echo json_encode($response);
}



function edit_billing_address($raw_data) {
    global $editor;
    $warning='';





    $customer=new Customer($_REQUEST['subject_key']);



    $address=new Address('id',$_REQUEST['id']);

    if (!$address->id) {
        $response=array('state'=>400,'msg'=>'Address not found');
        echo json_encode($response);
        return;
    }
    $address->set_editor($editor);



    $translator=array(
                    'country_code'=>'Address Country Code',
                    'country_d1'=>'Address Country First Division',
                    'country_d2'=>'Address Country Second Division',
                    'town'=>'Address Town',
                    'town_d1'=>'Address Town First Division',
                    'town_d2'=>'Address Town Second Division',
                    'postal_code'=>'Address Postal Code',
                    'street'=>'Street Data',
                    'internal'=>'Address Internal',
                    'building'=>'Address Building');


    $update_data=array('editor'=>$editor);

    foreach($raw_data as $key=>$value) {
        if (array_key_exists($key, $translator)) {
            $update_data[$translator[$key]]=$value;
        }
    }


    $deleted_address=0;
    $created_address=0;


    $proposed_address=new Address("find in Customer ".$customer->id,$update_data);


    if ($proposed_address->found and array_key_exists($proposed_address->id,$customer->get_address_keys())  ) {
        $old_billing_address_key=$customer->data['Customer Billing Address Key'];

        $customer->update_principal_billing_address($proposed_address->id);
        if ($customer->data['Customer Delivery Address Link']=='Billing')
            $customer->update_principal_delivery_address($proposed_address->id);


        if ($old_billing_address_key!=$customer->data['Customer Main Address Key']  or !array_key_exists($old_billing_address_key,$customer->get_delivery_address_keys())   ) {
            $old_address=new Address($old_billing_address_key);
            $deleted_address=$old_billing_address_key;
            $old_address->delete();
        }
        $address=new Address($proposed_address->id);


    } else {


        if ($customer->data['Customer Billing Address Link']=='Contact') {

            $address=new Address("find in Customer ".$customer->id." create force",$update_data);
            $customer->associate_billing_address($address->id);
            $customer->update_principal_billing_address($address->id);

            $created_address=$address->id;

        } else {

            $address->update($update_data,'cascade');
        }



    }





    $updated_address_data=array(
                              'country'=>$address->data['Address Country Name'],
                              'country_code'=>$address->data['Address Country Code'],
                              'country_d1'=> $address->data['Address Country First Division'],
                              'country_d2'=> $address->data['Address Country Second Division'],
                              'town'=> $address->data['Address Town'],
                              'postal_code'=> $address->data['Address Postal Code'],
                              'town_d1'=> $address->data['Address Town First Division'],
                              'town_d2'=> $address->data['Address Town Second Division'],
                              'fuzzy'=> $address->data['Address Fuzzy'],
                              'street'=> $address->display('street'),
                              'building'=>  $address->data['Address Building'],
                              'internal'=> $address->data['Address Internal'],
                              'description'=>$address->data['Address Description']

                          );

    $customer->update_principal_delivery_address($customer->data['Customer Main Delivery Address Key']);



    if ( ($customer->get('Customer Billing Address Link')=='Contact')  ) {
        $billing_address='<span style="font-weight:600">'._('Same as contact address').'</span>';

    } else {

        $billing_address=$customer->billing_address_xhtml();
    }

    if ( ($customer->get('Customer Delivery Address Link')=='Contact') or ( $customer->get('Customer Delivery Address Link')=='Billing'  and  ($customer->get('Customer Main Address Key')==$customer->get('Customer Billing Address Key'))   ) ) {
        $address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

    }
    elseif($customer->get('Customer Delivery Address Link')=='Billing') {
        $address_comment='<span style="font-weight:600">'._('Same as billing address').'</span>';
    }
    else {
        $address_comment=$customer->delivery_address_xhtml();
    }

    $response=array('state'=>200,'action'=>'updated','deleted_address'=>$deleted_address,'created_address'=>$created_address,'warning'=>$warning,'is_main'=>false,'is_main_delivery'=>false,'msg'=>$address->msg_updated,'key'=>$address->id,'updated_data'=>$updated_address_data,'xhtml_address'=>$address->display('xhtml'),'xhtml_delivery_address_bis'=>$address_comment,'xhtml_billing_address'=>$billing_address);

    echo json_encode($response);
    return;


}


function edit_address($data) {
    global $editor;
    $warning='';

    $id=$data['id'];
    $subject=$data['subject'];
    $subject_key=$data['subject_key'];
    $raw_data=$data['value'];
    if ($subject=='Customer' and $_REQUEST['key']=='Billing') {
        edit_billing_address($raw_data);
        exit;
    }

    $subject_key=$_REQUEST['subject_key'];
    switch ($subject) {
    case('Company'):
        $subject_object=new Company($subject_key);
        break;
    case('Contact'):
        $subject_object=new Contact($subject_key);
        break;
    case('Customer'):
        $subject_object=new Customer($subject_key);
        break;
    case('Supplier'):
        $subject_object=new Supplier($subject_key);
        break;    
   

    }

    $address=new Address('id',$id);

    if (!$address->id) {
        $response=array('state'=>400,'msg'=>'Address not found');
        echo json_encode($response);
        return;
    }
    $address->set_editor($editor);



    $translator=array(
                    'country_code'=>'Address Country Code',
                    'country_d1'=>'Address Country First Division',
                    'country_d2'=>'Address Country Second Division',
                    'town'=>'Address Town',
                    'town_d1'=>'Address Town First Division',
                    'town_d2'=>'Address Town Second Division',
                    'postal_code'=>'Address Postal Code',
                    'street'=>'Street Data',
                    'internal'=>'Address Internal',
                    'building'=>'Address Building',
                );


    $update_data=array('editor'=>$editor);

    foreach($raw_data as $key=>$value) {
        if (array_key_exists($key, $translator)) {
            $update_data[$translator[$key]]=$value;
        }
    }

// print_r($update_data);
    $proposed_address=new Address("find in $subject $subject_key",$update_data);

    if ($proposed_address->found) {
        $address_parents=  $proposed_address->get_parent_keys($subject);
        if (array_key_exists($subject_key,$address_parents)) {
            if ($subject=='Customer') {
                if (preg_match('/^contact$/i',$_REQUEST['key'])) {
                    $subject_object->update_principal_address($proposed_address->id);

                    // print "new Address address".$subject_object->data['Customer Main Address Key']."\n";
                    $address->delete();

                    return;
                } else {
                    $msg="This $subject has already another address with this data";
                    $response=array('state'=>200,'action'=>'nochange','msg'=>$msg );
                    echo json_encode($response);
                    return;
                }
            } else if ($subject=='Supplier') {
                if (preg_match('/^contact$/i',$_REQUEST['key'])) {
                    $subject_object->update_principal_address($proposed_address->id);

                    // print "new Address address".$subject_object->data['Customer Main Address Key']."\n";
                    $address->delete();

                    return;
                } else {
                    $msg="This $subject has already another address with this data";
                    $response=array('state'=>200,'action'=>'nochange','msg'=>$msg );
                    echo json_encode($response);
                    return;
                }
            }



        } else {
            $warning=_('Warning, address found also associated with')." ";
            switch ($subject) {
            case 'Customer':
                $parent_label='';
                foreach($address_parents as $parent_key) {
                    $parent=new Customer($parent_key);
                    $parent_label.=sprintf(', <a href="customer.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
                }
                $parent_label=preg_replace('/^,/','',$parent_label);
                $warning.=ngettext(count($address_parents),'Customer','Customers').' '.$parent_label;
                break;
            case 'Supplier':
                $parent_label='';
                foreach($address_parents as $parent_key) {
                    $parent=new Supplier($parent_key);
                    $parent_label.=sprintf(', <a href="supplier.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
                }
                $parent_label=preg_replace('/^,/','',$parent_label);
                $warning.=ngettext(count($address_parents),'Supplier','Suppliers').' '.$parent_label;
                break;
            case 'Company':
                $parent_label='';
                foreach($address_parents as $parent_key) {
                    $parent=new Company($parent_key);
                    $parent_label.=sprintf(', <a href="company.php?id=%d">%s</a>',$parent->id,$parent->data['Company Name']);
                }
                $parent_label=preg_replace('/^,/','',$parent_label);
                $warning.=ngettext(count($address_parents),'Company','Companies').' '.$parent_label;
                break;
            case('Contact'):


                $parent_label='';
                foreach($address_parents as $parent_key) {
                    $parent=new Contact($parent_key);
                    if ($parent->data['Contact Company Key']!=$subject->data['Contact Company Key'] )
                        $parent_label.=sprintf(', <a href="contact.php?id=%d">%s</a>',$parent->id,$parent->display('name'));
                }
                if ($parent_label=='')
                    $warning='';
                else {
                    $parent_label=preg_replace('/^,/','',$parent_label);
                    $warning.=ngettext(count($address_parents),'Contact','Contacts').' '.$parent_label;
                }
                break;


            default:
                break;
            }


        }



    }
















    $address->update($update_data,'cascade');


    if ($address->updated) {
        $updated_address_data=array(
                                  'country'=>$address->data['Address Country Name'],
                                  'country_code'=>$address->data['Address Country Code'],
                                  'country_d1'=> $address->data['Address Country First Division'],
                                  'country_d2'=> $address->data['Address Country Second Division'],
                                  'town'=> $address->data['Address Town'],
                                  'postal_code'=> $address->data['Address Postal Code'],
                                  'town_d1'=> $address->data['Address Town First Division'],
                                  'town_d2'=> $address->data['Address Town Second Division'],
                                  'fuzzy'=> $address->data['Address Fuzzy'],
                                  'street'=> $address->display('street'),
                                  'building'=>  $address->data['Address Building'],
                                  'internal'=> $address->data['Address Internal'],
                                  'description'=>$address->data['Address Description']

                              );
        $is_main='No';
        $is_main_delivery='No';
        $address_comment='';

        if ($subject_object->get_main_address_key()==$address->id) {
            $is_main='Yes';
        }
        if ($subject=='Customer' and $subject_object->data['Customer Main Delivery Address Key']==$address->id) {
            $is_main_delivery='Yes';

            if ( ($subject_object->get('Customer Delivery Address Link')=='Contact') or ( $subject_object->get('Customer Delivery Address Link')=='Billing'  and  ($subject_object->get('Customer Main Address Key')==$subject_object->get('Customer Billing Address Key'))   ) ) {
                $address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

            }
            elseif($subject_object->get('Customer Delivery Address Link')=='Billing') {
                $address_comment='<span style="font-weight:600">'._('Same as billing address').'</span>';
            }
            else {
                $address_comment=$subject_object->delivery_address_xhtml();
            }


            if ( ($subject_object->get('Customer Billing Address Link')=='Contact')  ) {
                $billing_address='<span style="font-weight:600">'._('Same as contact address').'</span>';

            } else {

                $billing_address=$subject_object->billing_address_xhtml();
            }


        }



        $response=array('state'=>200,'action'=>'updated','warning'=>$warning,'is_main'=>$is_main,'is_main_delivery'=>$is_main_delivery,'msg'=>$address->msg_updated,'key'=>$address->id,'updated_data'=>$updated_address_data,'xhtml_address'=>$address->display('xhtml'));

        if($subject=='Customer'){
         $response['xhtml_delivery_address_bis']=$address_comment;
                  $response['xhtml_billing_address']=$billing_address;

        
        }
        

    } else {
        if ($address->error_updated)
            $response=array('state'=>200,'action'=>'error','msg'=>$address->msg_updated,'key'=>$translator[$_REQUEST['key']]);
        else
            $response=array('state'=>200,'action'=>'nochange','msg'=>$address->msg_updated,'key'=>'');

    }


    echo json_encode($response);

}
function delete_email() {
    global $editor;
    if ( !isset($_REQUEST['value'])  ) {
        $response=array('state'=>400,'msg'=>'Error no value');
        echo json_encode($response);
        return;
    }
    if ( !isset($_REQUEST['subject'])
            or !is_numeric($_REQUEST['subject_key'])
            or $_REQUEST['subject_key']<=0       or !preg_match('/^company|contact$/i',$_REQUEST['subject'])

       ) {
        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
        echo json_encode($response);
        return;
    }
    $subject_type=$_REQUEST['subject'];
    $subject_key=$_REQUEST['subject_key'];


    if (preg_match('/^company$/i',$subject_type)) {
        $subject=new Company($subject_key);
        $is_company=true;
    } else {
        $subject=new Contact($subject_key);
        $is_company=false;
    }


    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Subject not found');
        echo json_encode($response);
        return;
    }

    $email_key=$_REQUEST['value'];
    if (!is_numeric($email_key)) {
        $email = new Email('email',$email_key);
        $email_key=$email->id;
    } else {
        $email = new Email($email_key);
        $email_key=$email->id;

    }
    $email->delete();
    if ($is_company) {
        $contact_found_keys=$subject->get_contact_keys();
        //print_r($contact_found_keys);
        foreach($contact_found_keys as $contact_found_key) {
            $contact=new Contact($contact_found_key);
            $contact->editor=$subject->editor;
            $contact->remove_email($email->id);
        }
    }
    if ($email->deleted) {
        $action='deleted';
        $msg=_('Email deleted');
        $subject->reread();
    } else {
        $action='nochange';
        $msg=_('Email could not be deleted');
    }

    $response=array('state'=>200,'action'=>$action,'msg'=>$msg,'email_key'=>$email_key,'xhtml_subject'=>$subject->display('card'),'main_email_key'=>$subject->get_principal_email_key());
    echo json_encode($response);
}
function delete_mobile() {
    global $editor;
    if ( !isset($_REQUEST['value'])  ) {
        $response=array('state'=>400,'msg'=>'Error no value');
        echo json_encode($response);
        return;
    }
    if ( !isset($_REQUEST['subject'])
            or !is_numeric($_REQUEST['subject_key'])
            or $_REQUEST['subject_key']<=0       or !preg_match('/^contact$/i',$_REQUEST['subject'])

       ) {
        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
        echo json_encode($response);
        return;
    }
    $subject_type=$_REQUEST['subject'];
    $subject_key=$_REQUEST['subject_key'];



    $subject=new Contact($subject_key);



    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Contact not found');
        echo json_encode($response);
        return;
    }
    $mobil = new Telecom($_REQUEST['value']);

    if (!$mobil->id) {
        $response=array('state'=>400,'msg'=>'Mobile not found');
        echo json_encode($response);
        return;
    }




    $mobil_key=$mobil->id;


    $mobil->delete();

    if ($mobil->deleted) {
        $action='deleted';
        $msg=_('Mobile deleted');
        $subject->reread();
    } else {
        $action='nochange';
        $msg=_('Mobile could not be deleted');
    }

    $response=array('state'=>200,'action'=>$action,'msg'=>$msg,'telecom_key'=>$mobil_key,'xhtml_subject'=>$subject->display('card'),'main_mobil_key'=>$subject->get_principal_mobile_key());
    echo json_encode($response);
}

function delete_address() {
    global $editor;

    if ( !isset($_REQUEST['value']) or !is_numeric($_REQUEST['value']) ) {
        $response=array('state'=>400,'msg'=>'Error no value');
        echo json_encode($response);
        return;
    }




    if ( !isset($_REQUEST['subject'])
            or !is_numeric($_REQUEST['subject_key'])
            or $_REQUEST['subject_key']<=0
            or !preg_match('/^(Company|Contact|Customer)$/',$_REQUEST['subject'])

       ) {
        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
        echo json_encode($response);
        return;
    }

    $subject=$_REQUEST['subject'];
    $subject_key=$_REQUEST['subject_key'];
    switch ($subject) {
    case('Company'):
        $subject_object=new Company($subject_key);
        break;
    case('Contact'):
        $subject_object=new Contact($subject_key);
        break;
    case('Customer'):
        $subject_object=new Customer($subject_key);
        break;
    default:

        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key (2)');
        echo json_encode($response);
        return;

    }






    $address_key=$_REQUEST['value'];
    $address=new Address($address_key);
    $address->delete();

    $action='deleted';
    $msg=_('Address Deleted');
    $subject_object->get_data('id',$subject_object->id);
    $main_address_key=$subject_object->get_main_address_key();
    $main_address=new Address($main_address_key);
    $main_address_data=array(
                           'country'=>$main_address->data['Address Country Name']
                                     ,'country_code'=>$main_address->data['Address Country Code']
                                                     ,'country_d1'=> $main_address->data['Address Country First Division']
                                                                   ,'country_d2'=> $main_address->data['Address Country Second Division']
                                                                                 ,'town'=> $main_address->data['Address Town']
                                                                                         ,'postal_code'=> $main_address->data['Address Postal Code']
                                                                                                        ,'town_d1'=> $main_address->data['Address Town First Division']
                                                                                                                   ,'town_d2'=> $main_address->data['Address Town Second Division']
                                                                                                                              ,'fuzzy'=> $main_address->data['Address Fuzzy']
                                                                                                                                       ,'street'=> $main_address->display('street')
                                                                                                                                                 ,'building'=>  $main_address->data['Address Building']
                                                                                                                                                             ,'internal'=> $main_address->data['Address Internal']
                                                                                                                                                                         ,'description'=>$main_address->data['Address Description']

                       );


    $address_comment='';


    $address_main_delivery='';

    $billing_address='';
    if ($subject=='Customer' ) {

        $address_main_delivery=$subject_object->delivery_address_xhtml();

        if ( ($subject_object->get('Customer Delivery Address Link')=='Contact') or ( $subject_object->get('Customer Delivery Address Link')=='Billing'  and  ($subject_object->get('Customer Main Address Key')==$subject_object->get('Customer Billing Address Key'))   ) ) {
            $address_comment='<span style="font-weight:600">'._('Same as contact address').'</span>';

        }
        elseif($subject_object->get('Customer Delivery Address Link')=='Billing') {
            $address_comment='<span style="font-weight:600">'._('Same as billing address').'</span>';
        }
        else {
            $address_comment=$subject_object->delivery_address_xhtml();
        }



        if ( ($subject_object->get('Customer Billing Address Link')=='Contact')  ) {
            $billing_address='<span style="font-weight:600">'._('Same as contact address').'</span>';

        } else {

            $billing_address=$subject_object->billing_address_xhtml();
        }


    }




    $response=array('state'=>200,'action'=>'deleted','key'=>'','main_address_data'=>$main_address_data,'xhtml_main_address'=>$main_address->display('xhtml'),'xhtml_delivery_address'=>$address_main_delivery,'xhtml_delivery_address_bis'=>$address_comment,'xhtml_billing_address'=>$billing_address);



//  $response=array('state'=>200,'action'=>$action,'msg'=>$msg,'address_key'=>$address_key);


    echo json_encode($response);
}


function delete_company_area($data) {
    include_once('class.CompanyArea.php');
    global $editor;
    $subject=new CompanyArea($data['id']);
    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Area not found');
        echo json_encode($response);
        return;
    }
    $subject->editor=$editor;
    $subject->delete();
    if ($subject->deleted) {
        $action='deleted';
        $msg=_('Area deleted');

    } else {
        $action='nochage';
        $msg=_('Area could not be deleted');
    }
    $response=array('state'=>200,'action'=>$action);
    echo json_encode($response);
}

function delete_company_department($data) {
    include_once('class.CompanyDepartment.php');
    global $editor;
    $subject=new CompanyDepartment($data['id']);
    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Department not found');
        echo json_encode($response);
        return;
    }
    $subject->editor=$editor;
    $subject->delete();
    if ($subject->deleted) {
        $action='deleted';
        $msg=_('Department deleted');

    } else {
        $action='nochage';
        $msg=_('Department could not be deleted');
    }
    $response=array('state'=>200,'action'=>$action);
    echo json_encode($response);
}



function edit_company2() {
    $company=new Company($_REQUEST['id']);
    $company->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue'])),stripslashes(urldecode($_REQUEST['oldvalue'])));

    if ($company->updated) {
        $response= array('state'=>200,'newvalue'=>$company->newvalue,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$company->msg,'key'=>$_REQUEST['key']);
    }
    echo json_encode($response);
}
function new_company($data) {
    Timer::timing_milestone('begin');
    global $editor;
    $data['editor']=$editor;

    $company=new Company('find create',$data['values']);
    if ($company->new) {
        $response= array('state'=>200,'action'=>'created','company_key'=>$company->id);
    } else {
        if ($company->found)
            $response= array('state'=>400,'action'=>'found','company_key'=>$company->found_key);
        else
            $response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);
    }

    //Timer::dump_profile();

    echo json_encode($response);

}

function new_customer($data) {
    Timer::timing_milestone('begin');
    global $editor;






    foreach($data['values'] as $key=>$value) {
        $data['values'][preg_replace('/^Company / ','Customer ',$key)]=$value;
    }
    foreach($data['values'] as $key=>$value) {
        $data['values'][preg_replace('/^Contact / ','Customer ',$key)]=$value;
    }
    if (isset($data['values']['Company Name']))
        $data['values']['Customer Company Name']=$data['values']['Company Name'];
//print_r($data['values']);
    $data['values']['editor']=$editor;


    if (isset($_REQUEST['delete_email']) and  $_REQUEST['delete_email']) {

        $email=new Email('email',$data['values']['Customer Main Plain Email']);
        if ($email->id) {
            $email->delete();
        }
    }


//print_r($data['values']);



    $customer=new Customer('find create',$data['values']);
    if ($customer->new) {
        $response= array('state'=>200,'action'=>'created','customer_key'=>$customer->id);
    } else {
        if ($customer->found)
            $response= array('state'=>400,'action'=>'found','customer_key'=>$customer->found_key);
        else
            $response= array('state'=>400,'action'=>'error','customer_key'=>0,'msg'=>$customer->msg);
    }

    //Timer::dump_profile();

    echo json_encode($response);

}





function new_corporation($data) {
    Timer::timing_milestone('begin');
    global $editor;

    $company=new Company('find create',$data['values']);

    if (!$company->id) {
        $response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);
        echo json_encode($response);
        exit;
    }


    $sql=sprintf("insert into `Corporation Dimension` (`Corporation Name`,`Corporation Company Key`) values (%s,%d)"
                 ,prepare_mysql($company->data['Company Name'])
                 ,$company->id
                );
    mysql_query($sql);

    $response= array('state'=>200,'action'=>'created','company_key'=>$company->id);


    echo json_encode($response);

}

function new_contact($data) {

    global $editor;
    $contact_data=array();
    foreach($data['value'] as $key=>$values) {

        if ($key=='Contact_Name_Components') {
            $tmp=array();
            foreach($values as $_key=>$_values) {
                $tmp[preg_replace('/\_/',' ',$_key)]=$_values;
            }
            $values=$tmp;
        }
        $contact_data[preg_replace('/\_/',' ',$key)]=$values;

    }

    switch ($data['subject']) {
    case('Company'):
        $company=new Company($data['subject_key']);

        $contact=new Contact('find create',$contact_data);

        $company->create_contact_bridge($contact->id);
        break;
    default:
        $contact=new Contact('find create',$contact_data);

    }



    if ($contact->new) {
        $response= array('state'=>200,'action'=>'created','contact_key'=>$contact->id);
    } else {
        if ($contact->found)
            $response= array('state'=>400,'action'=>'found','contact_key'=>$contact->found_key);
        else
            $response= array('state'=>400,'action'=>'error','contact_key'=>0,'msg'=>$contact->msg);
    }

    //Timer::dump_profile();

    echo json_encode($response);

}



function edit_customer() {
    $key=$_REQUEST['key'];


    $customer=new customer($_REQUEST['customer_key']);
    global $editor;
    $customer->editor=$editor;

    if ($key=='Attach') {
        // print_r($_FILES);
        $note=stripslashes(urldecode($_REQUEST['newvalue']));
        $target_path = "uploads/".'attach_'.date('U');
        $original_name=$_FILES['testFile']['name'];
        $type=$_FILES['testFile']['type'];
        $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

        if (move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
            $customer->add_attach($target_path,$data);

        }
    } else {



        $key_dic=array(
                     'fiscal_name'=>'Customer Fiscal Name'
                                   ,'name'=>'Customer Name'
                                           ,'email'=>'Customer Main Plain Email'
                                                    ,'telephone'=>'Customer Main Plain Telephone'
                                                                 ,'contact'=>'Customer Main Contact Name'
                                                                            ,"address"=>'Address'
                                                                                       ,"town"=>'Main Address Town'
                                                                                               ,"tax_number"=>'Customer Tax Number'
                                                                                                             ,"postcode"=>'Main Address Town'
                                                                                                                         ,"region"=>'Main Address Town'
                                                                                                                                   ,"country"=>'Main Address Country'
                                                                                                                                              ,"ship_address"=>'Main Ship To'
                                                                                                                                                              ,"ship_town"=>'Main Ship To Town'
                                                                                                                                                                           ,"ship_postcode"=>'Main Ship To Postal Code'
                                                                                                                                                                                            ,"ship_region"=>'Main Ship To Country Region'
                                                                                                                                                                                                           ,"ship_country"=>'Main Ship To Country'

                 );
        if (array_key_exists($_REQUEST['key'],$key_dic))
            $key=$key_dic[$_REQUEST['key']];

        if ($key=='Customer Fiscal Name') {
            $customer->update_fiscal_name(stripslashes(urldecode($_REQUEST['newvalue'])  ));
        }
        elseif ($key=='Customer Tax Number') {
            $customer->update_tax_number(stripslashes(urldecode($_REQUEST['newvalue'])  ));
        }
        else
            $customer->update(array($key=>stripslashes(urldecode($_REQUEST['newvalue'])  )  ));
    }


    if ($customer->updated) {
        $response= array('state'=>200,'newvalue'=>$customer->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$customer->msg,'key'=>$_REQUEST['key']);
    }
    echo json_encode($response);

}


function list_customers() {


    global $myconf;

    $conf=$_SESSION['state']['customers']['table'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];
    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['store_id'])    ) {
        $store=$_REQUEST['store_id'];
        $_SESSION['state']['customers']['store']=$store;
    } else
        $store=$_SESSION['state']['customers']['store'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    //$_SESSION['state']['customers']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    $_SESSION['state']['customers']['table']['order']=$order;
    $_SESSION['state']['customers']['table']['order_dir']=$order_direction;
    $_SESSION['state']['customers']['table']['nr']=$number_results;
    $_SESSION['state']['customers']['table']['sf']=$start_from;
    $_SESSION['state']['customers']['table']['where']=$where;
    $_SESSION['state']['customers']['table']['f_field']=$f_field;
    $_SESSION['state']['customers']['table']['f_value']=$f_value;


    $filter_msg='';
    $wheref='';


    if (is_numeric($store)) {
        $where.=sprintf(' and `Customer Store Key`=%d ',$store);
    }





    if (($f_field=='customer name'     )  and $f_value!='') {
        $wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
    }
    elseif(($f_field=='postcode'     )  and $f_value!='') {
        $wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";



    }
    else if ($f_field=='id'  )
        $wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
    else if ($f_field=='maxdesde' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
    else if ($f_field=='mindesde' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
    else if ($f_field=='max' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`<=".$f_value."    ";
    else if ($f_field=='min' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`>=".$f_value."    ";
    else if ($f_field=='maxvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
    else if ($f_field=='minvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`>=".$f_value."    ";






    $sql="select count(*) as total from `Customer Dimension`  $where $wheref";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from `Customer Dimension`  $where ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

            $total_records=$row['total_without_filters'];
            $filtered=$row['total_without_filters']-$total;
        }

    } else {
        $filtered=0;
        $filter_total=0;
        $total_records=$total;
    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('identified customers','identified customers',$total_records);
    if ($total_records>$number_results)
        $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('customer name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('customer name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('customers with name like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';





    $_order=$order;
    $_dir=$order_direction;
    // if($order=='location'){
//      if($order_direction=='desc')
//        $order='country_code desc ,town desc';
//      else
//        $order='country_code,town';
//      $order_direction='';
//    }

//     if($order=='total'){
//       $order='supertotal';
//    }


    if ($order=='name')
        $order='`Customer File As`';
    elseif($order=='id')
    $order='`Customer Key`';
    elseif($order=='location')
    $order='`Customer Main Location`';
    elseif($order=='orders')
    $order='`Customer Orders`';
    elseif($order=='email')
    $order='`Customer Email`';
    elseif($order=='telephone')
    $order='`Customer Main Telehone`';
    elseif($order=='last_order')
    $order='`Customer Last Order Date`';
    elseif($order=='contact_name')
    $order='`Customer Main Contact Name`';
    elseif($order=='address')
    $order='`Customer Main Location`';
    elseif($order=='town')
    $order='`Customer Main Town`';
    elseif($order=='postcode')
    $order='`Customer Main Postal Code`';
    elseif($order=='region')
    $order='`Customer Main Country First Division`';
    elseif($order=='country')
    $order='`Customer Main Country`';
    //  elseif($order=='ship_address')
    //  $order='`customer main ship to header`';
    elseif($order=='ship_town')
    $order='`Customer Main Delivery Address Town`';
    elseif($order=='ship_postcode')
    $order='`Customer Main Delivery Address Postal Code`';
    elseif($order=='ship_region')
    $order='`Customer Main Delivery Address Country Region`';
    elseif($order=='ship_country')
    $order='`Customer Main Delivery Address Country`';
    elseif($order=='net_balance')
    $order='`Customer Net Balance`';
    elseif($order=='balance')
    $order='`Customer Outstanding Net Balance`';
    elseif($order=='total_profit')
    $order='`Customer Profit`';
    elseif($order=='total_payments')
    $order='`Customer Net Payments`';
    elseif($order=='top_profits')
    $order='`Customer Profits Top Percentage`';
    elseif($order=='top_balance')
    $order='`Customer Balance Top Percentage`';
    elseif($order=='top_orders')
    $order='``Customer Orders Top Percentage`';
    elseif($order=='top_invoices')
    $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
    $order='`Customer Total Refunds`';

    elseif($order=='activity')
    $order='`Customer Type by Activity`';

    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds`  from `Customer Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results";
    //   print $sql;
    $adata=array();



    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





        $adata[]=array(

                     'customer_key'=>$data['Customer Key'],
                     'name'=>$data['Customer Name'],


                     'email'=>$data['Customer Main Plain Email'],
                     'telephone'=>$data['Customer Main XHTML Telephone'],

                     'contact_name'=>$data['Customer Main Contact Name'],
                     'address'=>$data['Customer Main Location'],
                     'town'=>$data['Customer Main Town'],
                     'postcode'=>$data['Customer Main Postal Code'],
                     'region'=>$data['Customer Main Country First Division'],
                     'country'=>$data['Customer Main Country'],

                     'ship_town'=>$data['Customer Main Delivery Address Town'],
                     'ship_postcode'>$data['Customer Main Delivery Address Postal Code'],
                     'ship_region'=>$data['Customer Main Delivery Address Region'],
                     'ship_country'=>$data['Customer Main Delivery Address Country'],

                     'go'=>sprintf("<a href='edit_customer.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$data['Customer Key'])

                 );
    }
    mysql_free_result($result);




    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'rtext'=>$rtext,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,

                                      'records_perpage'=>$number_results,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}


function list_company_areas() {
    $conf=$_SESSION['state']['company_areas']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['company_areas']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
        $number_results=$conf['nr'];


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;




    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent=$conf['parent'];

    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];




    $_SESSION['state']['company_areas']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
            ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
                                                      );




    $group='';





    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='company name' and $f_value!='')
        $wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Company Area Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Company Area Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('company area','company areas',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Company Area Name`';

    if ($order=='code')
        $order='`Company Area Code`';



    $sql="select  * from `Company Area Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        if ($row['Company Area Number Employees']>0) {
            $delete='';
        } else {
            $delete='<img src="art/icons/delete.png"/>';
        }

        $adata[]=array(


                     'id'=>$row['Company Area Key']

                          ,'go'=>sprintf("<a href='company_area.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Company Area Key'])

                                ,'code'=>$row['Company Area Code']
                                        ,'name'=>$row['Company Area Name']
                                                ,'delete'=>$delete
                                                          ,'delete_type'=>'delete'
                 );
    }
    mysql_free_result($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );




    echo json_encode($response);

}

function list_company_staff() {
    $conf=$_SESSION['state']['company_staff']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['company_staff']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
        $number_results=$conf['nr'];


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;




    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent=$conf['parent'];

    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];




    $_SESSION['state']['company_staff']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
            ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
                                                      );




    $group='';





    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='staff name' and $f_value!='')
        $wheref.=" and  `Staff Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
   // $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";
    $wheref.="";
    $sql="select count(*) as total from `Staff Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Staff Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('company staff','company staff',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Staff Name`';

    if ($order=='code')
        $order='`Staff Key`';



    $sql="select  * from `Staff Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        
            $delete='<img src="art/icons/delete.png"/>';
       
        $adata[]=array(


                     'id'=>$row['Staff Key']

                          ,'go'=>sprintf("<a href='edit_each_staff.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Staff Key'])

                                ,'code'=>$row['Staff Key']
                                        ,'name'=>$row['Staff Name']
                                                ,'delete'=>$delete
                                                          ,'delete_type'=>'delete'
                 );
    }
    mysql_free_result($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );




    echo json_encode($response);

}




function list_company_departments() {

    $conf=$_SESSION['state']['company_departments']['table'];
    if (isset( $_REQUEST['parent'])) {
        $parent=$_REQUEST['parent'];
        $_SESSION['state']['company_departments']['parent']=$parent;
    } else
        $parent= $_SESSION['state']['company_departments']['parent'];

    if ($parent=='area') {
        $conf_table='company_area';

        $conf=$_SESSION['state']['company_area']['departments'];

    } else {
        $conf_table='company_departments';
        $conf=$_SESSION['state'][$conf_table]['table'];

    }

    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['company_departments']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
        $number_results=$conf['nr'];


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;






    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];


    if ($parent=='area') {

        $_SESSION['state']['company_area']['departments']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent
                                                               );
    } else {
        $_SESSION['state']['company_departments']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent
                                                                );
    }


    if ($parent=='area') {
        $where.=sprintf(' and `Company Area Key`=%d',$_SESSION['state']['company_area']['id']);
    }


    $group='';





    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='company name' and $f_value!='')
        $wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Company Department Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Company Department Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('company department','company departments',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Company Department Name`';

    if ($order=='code')
        $order='`Company Department Code`';



    $sql="select  * from `Company Department Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        if ($row['Company Department Number Employees']>0) {
            $delete='';
        } else {
            $delete='<img src="art/icons/delete.png"/>';
        }
        $adata[]=array(


                     'id'=>$row['Company Department Key']

                          ,'go'=>sprintf("<a href='edit_company_department.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Company Department Key'])

                                ,'code'=>$row['Company Department Code']
                                        ,'name'=>$row['Company Department Name']
                                                ,'area'=>$row['Company Area Key']
                                                        ,'delete'=>$delete
                                                                  ,'delete_type'=>'delete'


                 );
    }
    mysql_free_result($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );




    echo json_encode($response);

}



function new_company_area($data) {
    global $editor;
    $company=new Company($data['parent_key']);
    $company->editor=$editor;
    if ($company->id) {
        $company->add_area($data['values']);
        if ($company->updated) {
            $response= array('state'=>200,'action'=>'created');

        } else {
            $response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

        }

    } else {
        $response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

    }
    echo json_encode($response);

}



function new_company_department($data) {
    global $editor;
    $company=new Company($data['parent_key']);
    $company->editor=$editor;
    if ($company->id) {
        $company->add_department($data['values']);
        if ($company->updated) {
            $response= array('state'=>200,'action'=>'created');

        } else {
            $response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

        }

    } else {
        $response= array('state'=>400,'action'=>'error','company_key'=>0,'msg'=>$company->msg);

    }
    echo json_encode($response);

}



function edit_company_area($data) {
    include_once('class.CompanyArea.php');
    global $editor;


    $company_area=new CompanyArea($data['id']);
    $company_area->editor=$editor;

    if (!$company_area->id) {
        $response=array('state'=>400,'msg'=>_('Company Area not found'));
        echo json_encode($response);
        return;
    }

    $translator=array(
                    'name'=>'Company Area Name'
                           ,'code'=>'Company Area Code'
                                   ,'description'=>'Company Area Description'

                );

    if (array_key_exists($data['key'], $translator)) {

        $update_data=array(

                         $translator[$data['key']]=>$data['newvalue']
                     );
        //print_r($update_data);
        $company_area->update($update_data);

        if ($company_area->error_updated) {
            $response=array('state'=>200,'action'=>'error','msg'=>$company_area->msg_updated,'key'=>$_REQUEST['key']);
        } else {

            if ($company_area->updated) {
                $response=array('state'=>200,'action'=>'updated','msg'=>$company_area->msg_updated,'key'=>$_REQUEST['key'],'newvalue'=>$company_area->new_value);
            } else {
                $response=array('state'=>200,'action'=>'nochange','msg'=>$company_area->msg_updated,'key'=>$_REQUEST['key']);

            }

        }


    } else {
        $response=array('state'=>400,'msg'=>'Key not in Scope');
    }
    echo json_encode($response);

}

function delete_contact($data) {
    $contact=new Contact($data['contact_key']);
    if ($contact->id) {
        $contact->delete();
        if ($contact->deleted) {
            $response=array('state'=>200,'action'=>'deleted','msg'=>$contact->msg);
            echo json_encode($response);
            exit;
        } else {
            $response=array('state'=>400,'action'=>'nochange','msg'=>$contact->msg);
            echo json_encode($response);
            exit;
        }
    }
    $response=array('state'=>400,'action'=>'error','msg'=>'Error');
    echo json_encode($response);
    exit;

}

function new_delivery_address() {
    global $editor;

    if ( !isset($_REQUEST['value']) ) {
        $response=array('state'=>400,'msg'=>'Error no value');
        echo json_encode($response);
        return;
    }

    $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
    $tmp=preg_replace('/\\\\\"/','"',$tmp);

    $raw_data=json_decode($tmp, true);

    if (!is_array($raw_data)) {
        $response=array('state'=>400,'msg'=>'Wrong value');
        echo json_encode($response);
        return;
    }


    if ( !isset($_REQUEST['subject'])
            or !is_numeric($_REQUEST['subject_key'])
            or $_REQUEST['subject_key']<=0
            or !preg_match('/^customer$/i',$_REQUEST['subject'])

       ) {
        $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
        echo json_encode($response);
        return;
    }

    $customer=$_REQUEST['subject'];
    $customer_key=$_REQUEST['subject_key'];
    $customer=new Customer($customer_key);


    $translator=array(
                    'country_code'=>'Address Country Code'
                                   ,'country_d1'=>'Address Country First Division'
                                                 ,'country_d2'=>'Address Country Second Division'
                                                               ,'town'=>'Address Town'
                                                                       ,'town_d1'=>'Address Town First Division'
                                                                                  ,'town_d2'=>'Address Town Second Division'
                                                                                             ,'postal_code'=>'Address Postal Code'
                                                                                                            ,'street'=>'Street Data'
                                                                                                                      ,'internal'=>'Address Internal'
                                                                                                                                  ,'building'=>'Address Building'
                                                                                                                                              ,'type'=>'Address Type'
                                                                                                                                                      ,'function'=>'Address Function'
                                                                                                                                                                  ,'description'=>'Address Description'

                );


    $data=array('editor'=>$editor);
    foreach($raw_data as $key=>$value) {
        if (array_key_exists($key, $translator)) {
            $data[$translator[$key]]=$value;
        }
    }

    $ship_to= new Ship_To('find create',$data);
    $data_ship_to=array(
                      'Ship To Key'=>$ship_to->id,
                      'Current Ship To Is Other Key'=>$customer->data['Customer Last Ship To Key'],
                      'Date'=>$editor['Date']
                  );
    $customer->update_ship_to($data_ship_to);

    if ($ship_to->new ) {


        $updated_address_data=array(
                                  'country'=>$ship_to->data['Ship To Country Name']
                                            ,'country_code'=>$ship_to->data['Ship To Country Code']
                                                            ,'country_d1'=> $ship_to->data['Ship To Line 4']
                                                                          ,'country_d2'=> ''
                                                                                        ,'town'=> $ship_to->data['Ship To Town']
                                                                                                ,'postal_code'=> $ship_to->data['Ship To Postal Code']
                                                                                                               ,'town_d1'=> ''
                                                                                                                          ,'town_d2'=> ''
                                                                                                                                     ,'fuzzy'=> ''
                                                                                                                                              ,'street'=> $ship_to->data['Ship To Line 1']
                                                                                                                                                        ,'building'=>  $ship_to->data['Ship To Line 2']
                                                                                                                                                                    ,'internal'=> $ship_to->data['Ship To Line 3']
                                                                                                                                                                                ,'type'=>''
                                                                                                                                                                                        ,'function'=>''
                                                                                                                                                                                                    ,'description'=>''
                              );

        $response=array(
                      'state'=>200
                              ,'action'=>'created'
                                        ,'msg'=>$customer->msg_updated
                                               ,'updated_data'=>$updated_address_data
                                                               ,'xhtml_address'=>$ship_to->display('xhtml')
                                                                                ,'address_key'=>$ship_to->id
                  );
        echo json_encode($response);
        return;

    } else {
        $response=array('state'=>200,'action'=>'nochange','msg'=>_('Address already in company'));
        echo json_encode($response);
        return;
    }

}


function edit_corporation($data) {
    include_once('class.Corporation.php');
    $corporation=new Corporation();
    $corporation->update(array($data['key']=>$data['newvalue']));
    if ($corporation->updated) {
        $response= array('state'=>200,'newvalue'=>$corporation->new_value,'key'=>$_REQUEST['okey']);

    } else {
        $response= array('state'=>400,'msg'=>$corporation->msg,'key'=>$_REQUEST['okey']);
    }
    echo json_encode($response);

}

?>
