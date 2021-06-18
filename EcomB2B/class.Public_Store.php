<?php

/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 09:21:33 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/

/**
 * Class Public_Store
 */
class Public_Store {

    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );

    public $table_name = 'Store';

    /**
     * @var string|bool
     */
    public $id = false;
    /**
     * @var array
     */
    public $data;
    /**
     * @var array
     */
    public $properties;
    /**
     * @var array
     */
    public $settings;
    /**
     * @var bool
     */
    public $error;
    /**
     * @var string
     */
    public $msg;
    /**
     * @var bool|\PDO
     */
    private $db;

    function __construct($a1) {
        global $db;
        $this->db = $db;
        $this->get_data($a1);
    }


    function get_data($tag) {

        $sql = sprintf(
            "SELECT * FROM `Store Dimension` WHERE `Store Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id         = $this->data['Store Key'];
            $this->properties = json_decode($this->data['Store Properties'], true);
            $this->settings   = json_decode($this->data['Store Settings'], true);
        }


    }

    function get_categories($type = 'families', $pages = '1-10', $output = 'data') {


        $categories = array();


        if ($pages != '') {

            $limit = preg_split('/-/', $pages);


            if (is_array($limit) and count($limit) == 2 and is_numeric($limit[0]) and is_numeric($limit[1])) {
                switch ($type) {
                    case 'departments':
                        $sql = sprintf(
                            "SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`='Category Categories' ) WHERE  `Webpage State`='Online' and  C.`Category Parent Key`=%d  and `Page Key` is not null order by `Webpage Name` LIMIT %d,%d ",
                            $this->get('Store Department Category Key'), $limit[0], $limit[1]
                        );

                        //   print $sql;

                        break;
                    case 'families':
                        $sql = sprintf(
                            "SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`='Category Products' ) WHERE   `Webpage State`='Online' and  C.`Category Parent Key`=%d  and `Page Key` is not null order by `Webpage Name` LIMIT %d,%d ",
                            $this->get('Store Family Category Key'), $limit[0], $limit[1]
                        );


                        break;
                    case 'web_departments':
                        include_once 'class.Public_Website.php';
                        $website = new Public_Website($this->get('Store Website Key'));


                        $sql = sprintf(
                            "SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`='Category Categories' ) WHERE   `Webpage State`='Online' and  C.`Category Parent Key`=%d  and `Page Key` is not null order by `Webpage Name` LIMIT %d,%d ",
                            $website->get('Website Alt Department Category Key'), $limit[0], $limit[1]
                        );


                        break;
                    case 'web_families':

                        include_once 'class.Public_Website.php';
                        $website = new Public_Website($this->get('Store Website Key'));

                        $sql = sprintf(
                            "SELECT  `Webpage Code`,`Webpage Name` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`='Category Products' ) WHERE   `Webpage State`='Online' and  C.`Category Parent Key`=%d  and `Page Key` is not null order by `Webpage Name` LIMIT %d,%d ",
                            $website->get('Website Alt Family Category Key'), $limit[0], $limit[1]
                        );

                        break;
                    default:

                        print $type;

                }


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        switch ($output) {
                            case 'menu':
                                $categories[] = array(
                                    'url'   => strtolower($row['Webpage Code']),
                                    'label' => $row['Webpage Name'],
                                    'new'   => false

                                );
                                break;
                        }

                    }
                }
            }


        }


        return $categories;


    }

    function get($key = '') {


        if (!$this->id) {
            return '';
        }


        switch ($key) {


            case 'Telephone':
            case 'Email':
            case 'Address':
            case 'Home Country Code 2 Alpha':
            case 'Label Signature':
                return $this->data['Store '.$key];


            case 'Store Customer Payment Account Key':
            case 'Store Currency Code':
            case 'Store Name':
            case 'Store Show in Warehouse Orders':
            case 'Store Order Public ID Format':
            case 'Store Department Category Key':
            case 'Store Family Category Key':
            case 'Store Timezone':
            case 'Store Key':
            case 'Store Email':
            case 'Store Website Key':
            case 'Store Home Country Code 2 Alpha':
            case 'Store Email Template Signature':
            case 'Store Google Map URL':
            case 'Store Can Collect':
            case 'Store Collect Address Line 1':
            case 'Store Collect Address Line 2':
            case 'Store Collect Address Sorting Code':
            case 'Store Collect Address Postal Code':
            case 'Store Collect Address Dependent Locality':
            case 'Store Collect Address Locality':
            case 'Store Collect Address Administrative Area':
            case 'Store Collect Address Country 2 Alpha Code':
            case 'Store URL':
            case 'Store Address':
            case 'Store Invoice Message':
            case 'Store Type':
            case 'Store External Invoicer Key':
            case 'Store Shopify API Key':
                return $this->data[$key];


            case 'Send Email Address':
                return $this->data['Store Email'];

            case 'Name':
                return $this->data['Store Name'];

            default:
                return '';

        }


    }


    function create_customer($data, $user_data) {

        include_once 'class.Public_Customer.php';

        $this->new_customer     = false;
        $this->new_website_user = false;


        if (empty($data['Customer Main Plain Email'])) {
            $this->error      = true;
            $this->msg        = _("Email missing");
            $this->error_code = 'email_missing';
            $this->metadata   = '';

            return array(
                false,
                false
            );
        }


        $sql  = 'select `Customer Key` from `Customer Dimension` where `Customer Main Plain Email`=? and `Customer Store Key`=? ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $data['Customer Main Plain Email'],
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $this->error      = true;
            $this->msg        = sprintf(_('Email %s is already registered'), $data['Customer Main Plain Email']);
            $this->error_code = 'duplicate_email';
            $this->metadata   = $data['Customer Main Plain Email'];

            return array(
                false,
                false
            );
        }

        $sql  = "SELECT `Website User Key` FROM `Website User Dimension` WHERE `Website User Handle`=? AND `Website User Website Key`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $data['Customer Main Plain Email'],
                $this->get('Store Website Key')
            )
        );
        if ($row = $stmt->fetch()) {
            $this->error      = true;
            $this->msg        = sprintf(_('Email %s is already registered'), $data['Customer Main Plain Email']).' (handle)';
            $this->error_code = 'duplicate_email';
            $this->metadata   = $data['Customer Main Plain Email'];

            return array(
                false,
                false
            );
        }




        $data['editor']                         = $this->editor;
        $data['Customer Store Key']             = $this->id;
        $data['Customer Billing Address Link']  = 'Contact';
        $data['Customer Delivery Address Link'] = 'Billing';

        $address_fields = array(
            'Address Recipient'            => $data['Customer Main Contact Name'],
            'Address Organization'         => $data['Customer Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $data['Customer Contact Address country'],

        );
        unset($data['Customer Contact Address country']);

        if (isset($data['Customer Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Customer Contact Address addressLine1'];
            unset($data['Customer Contact Address addressLine1']);
        }
        if (isset($data['Customer Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Customer Contact Address addressLine2'];
            unset($data['Customer Contact Address addressLine2']);
        }
        if (isset($data['Customer Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Customer Contact Address sortingCode'];
            unset($data['Customer Contact Address sortingCode']);
        }
        if (isset($data['Customer Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Customer Contact Address postalCode'];
            unset($data['Customer Contact Address postalCode']);
        }

        if (isset($data['Customer Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Customer Contact Address dependentLocality'];
            unset($data['Customer Contact Address dependentLocality']);
        }

        if (isset($data['Customer Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Customer Contact Address locality'];
            unset($data['Customer Contact Address locality']);
        }

        if (isset($data['Customer Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Customer Contact Address administrativeArea'];
            unset($data['Customer Contact Address administrativeArea']);
        }


        $customer     = new Public_Customer('new', $data, $address_fields);
        $website_user = '';
        if ($customer->id) {
            $this->new_customer_msg = $customer->msg;

            if ($customer->new) {


                if($this->get('Store Type')=='Fulfilment'){

                    $account=get_object('Account',1);
                    $account->load_properties();

                    $status=($customer->get('Customer Type by Activity') == 'ToApprove' ? 'ToApprove' : 'Approved');


                    $sql="insert into `Customer Fulfilment Dimension` (`Customer Fulfilment Customer Key`,`Customer Fulfilment Metadata`,`Customer Fulfilment Warehouse Key`,`Customer Fulfilment Status`) values (?,'{}',?,?)";
                    $this->db->prepare($sql)->execute(
                        array(
                            $customer->id,
                            $account->properties('fulfilment_warehouse_key'),
                            $status

                        )
                    );

                }

                $customer->fast_update_json_field('Customer Metadata', 'cur', $this->data['Store Currency Code']);

                include_once 'utils/network_functions.php';
                $website = get_object('website', $this->get('Store Website Key'));

                $user_data['Website User Handle']       = $customer->get('Customer Main Plain Email');
                $user_data['Website User Customer Key'] = $customer->id;
                $website_user                           = $website->create_user($user_data);

                include_once 'utils/new_fork.php';


                $this->new_customer = true;

                $this->new_website_user = $website_user->new;


                $website_user->fast_update(
                    array(
                        'Website User Has Login' => 'Yes',

                    )
                );

                $website_user->fast_update(
                    array(
                        'Website User Login Count'   => 1,
                        'Website User Last Login'    => gmdate('Y-m-d H:i:s'),
                        'Website User Last Login IP' => ip_from_cloudfare()
                    ), 'Website User Data'
                );


                $customer->update(array('Customer Website User Key' => $website_user->id), 'no_history');


                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'             => 'customer_created',
                    'customer_key'     => $customer->id,
                    'website_user_key' => $website_user->id,
                    'editor'           => $this->editor
                ), DNS_ACCOUNT_CODE
                );


            } else {
                $this->error = true;
                $this->msg   = $customer->msg;

            }

            return array(
                $customer,
                $website_user
            );
        } else {
            $this->error = true;
            $this->msg   = $customer->msg;
        }
    }

    function settings($key) {
        return (isset($this->settings[$key]) ? $this->settings[$key] : '');
    }


}

