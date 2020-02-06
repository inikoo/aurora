<?php

include_once 'class.DB_Table.php';


class PurchaseOrder extends DB_Table {

    public $table_name = 'Purchase Order';


    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;

        $this->ignore_fields = array('Purchase Order Key');


        if (is_string($arg1)) {
            if (preg_match(
                '/new|create/i', $arg1
            )) {
                $this->create($arg2);

                return;
            }
        }
        if (is_numeric($arg1)) {
            $this->get_data(
                'id', $arg1
            );

            return;
        }
        $this->get_data(
            $arg1, $arg2
        );

    }


    function create($data) {


        $account = get_object('account', 1);

        /**
         * @var $parent \Supplier|\Agent
         */
        $parent = get_object($data['Purchase Order Parent'], $data['Purchase Order Parent Key']);

        $this->editor = $data['editor'];

        $data['Purchase Order Date']      = gmdate('Y-m-d');
        $data['Purchase Order Date Type'] = 'Creation';

        $data['Purchase Order Creation Date']     = gmdate('Y-m-d H:i:s');
        $data['Purchase Order Last Updated Date'] = gmdate('Y-m-d H:i:s');
        $data['Purchase Order Metadata']          = '{}';


        if ($data['Purchase Order Parent'] == 'Supplier') {
            $sql = "UPDATE `Supplier Dimension` SET `Supplier Order Last Order ID` = LAST_INSERT_ID(`Supplier Order Last Order ID` + 1) WHERE `Supplier Key`=?";
        } elseif ($data['Purchase Order Parent'] == 'Agent') {
            $sql = "UPDATE `Agent Dimension` SET `Agent Order Last Order ID` = LAST_INSERT_ID(`Agent Order Last Order ID` + 1) WHERE `Agent Key`=?";
        } else {
            $this->error = true;

            return;
        }

        $this->db->prepare($sql)->execute(
            array(
                $data['Purchase Order Parent Key']
            )
        );
        $public_id = $this->db->lastInsertId();

        $data['Purchase Order Public ID'] = sprintf($parent->get('Order Public ID Format'), $public_id);

        $data['Purchase Order Public ID'] = $this->get_unique_public_id_suffix($data['Purchase Order Public ID'], $data['Purchase Order Parent'], $data['Purchase Order Parent Key']);


        $data['Purchase Order File As'] = $this->get_file_as($data['Purchase Order Public ID']);


        include_once 'utils/currency_functions.php';
        $data['Purchase Order Currency Exchange'] = currency_conversion(
            $this->db, $data['Purchase Order Currency Code'], $account->get('Account Currency'), '- 15 minutes'
        );


        $base_data = $this->base_data();


        if (!$parent->id) {
            $this->error = true;
            $this->msg   = 'Error supplier not found';

            return;
        }


        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $sql = sprintf(
            "INSERT INTO `Purchase Order Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();


            $this->get_data(
                'id', $this->id
            );


            $history_data = array(
                'History Abstract' => _('Purchase order created'),
                'History Details'  => '',
                'Action'           => 'created'
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;

            $parent->update_purchase_orders();


        }


    }


    function get_unique_public_id_suffix($code, $parent, $parent_key) {


        for ($i = 1; $i <= 200; $i++) {

            if ($i == 1) {
                $suffix = '';
            } elseif ($i <= 150) {
                $suffix = '.'.$i;
            } else {
                $suffix = '.'.uniqid('', true);
            }

            $sql = "SELECT `Purchase Order Public ID` FROM `Purchase Order Dimension`  WHERE  `Purchase Order Parent`=? AND `Purchase Order Parent Key`=? AND `Purchase Order Public ID`=?";


            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $parent,

                    $parent_key,
                    $code.$suffix
                )
            );
            if ($row = $stmt->fetch()) {

            } else {
                return $code.$suffix;
            }


        }

        return $suffix;
    }

    function get_file_as($name) {

        return $name;
    }

    function get_data($key, $id) {
        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Purchase Order Dimension` WHERE `Purchase Order Key`=%d", $id
            );

        } elseif ($key == 'public id') {
            $sql = sprintf(
                "SELECT * FROM `Purchase Order Dimension` WHERE `Purchase Order Public ID`=%s", prepare_mysql($id)
            );

        } elseif ($key == 'deleted') {
            $this->get_deleted_data($id);

            return;
        } else {
            return;

        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Purchase Order Key'];


            if ($this->data['Purchase Order Metadata'] == '') {
                $this->metadata = array();
            } else {
                $this->metadata = json_decode(
                    $this->data['Purchase Order Metadata'], true
                );
            }

            if ($this->data['Purchase Order Agent Data'] != '') {
                $this->data = array_merge($this->data, json_decode($this->data['Purchase Order Agent Data'], true));
            }


        }

    }

    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Purchase Order Deleted Dimension` WHERE `Purchase Order Deleted Key`=%d", $tag
        );

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id     = $this->data['Purchase Order Deleted Key'];
            $deleted_data = json_decode(
                gzuncompress($this->data['Purchase Order Deleted Metadata']), true
            );
            foreach ($deleted_data['data'] as $key => $value) {
                $this->data[$key] = $value;
            }
            $this->items = $deleted_data['items'];
        }
    }

    function create_delivery($data) {

        include_once 'utils/currency_functions.php';

        $account = get_object('Account', 1);
        // $warehouse=ger_object('Warehouse',$data['Supplier Delivery Warehouse Key']);


        $delivery_data = array(
            'Supplier Delivery Public ID'           => $data['Supplier Delivery Public ID'],
            'Supplier Delivery Parent'              => $this->get('Purchase Order Parent'),
            'Supplier Delivery Parent Key'          => $this->get('Purchase Order Parent Key'),
            'Supplier Delivery Parent Name'         => $this->get('Purchase Order Parent Name'),
            'Supplier Delivery Parent Code'         => $this->get('Purchase Order Parent Code'),
            'Supplier Delivery Parent Contact Name' => $this->get('Purchase Order Parent Contact Name'),
            'Supplier Delivery Parent Email'        => $this->get('Purchase Order Parent Email'),
            'Supplier Delivery Parent Telephone'    => $this->get('Purchase Order Parent Telephone'),
            'Supplier Delivery Parent Address'      => $this->get('Purchase Order Parent Address'),
            'Supplier Delivery Parent Country Code' => $this->get('Purchase Order Parent Country Code'),

            'Supplier Delivery Currency Code'      => $this->get('Purchase Order Currency Code'),
            'Supplier Delivery Incoterm'           => $this->get('Purchase Order Incoterm'),
            'Supplier Delivery Port of Import'     => $this->get('Purchase Order Port of Import'),
            'Supplier Delivery Port of Export'     => $this->get('Purchase Order Port of Export'),
            'Supplier Delivery Production'         => $this->get('Purchase Order Production'),
            'Supplier Delivery Purchase Order Key' => $this->id,
            'Supplier Delivery Currency Exchange'  => currency_conversion(
                $this->db, $this->get('Purchase Order Currency Code'), $account->get('Account Currency'), '- 15 minutes'
            ),


            //'Supplier Delivery Warehouse Key'=>$warehouse->id,
            //'Supplier Delivery Warehouse Metadata'=>json_encode($warehouse->data),

            'editor' => $this->editor
        );

        //  print_r($delivery_data);


        $delivery = new SupplierDelivery('new', $delivery_data);


        if ($delivery->error) {
            $this->error = true;
            $this->msg   = $delivery->msg;

        } elseif ($delivery->new or true) {


            foreach ($data['items'] as $potf_key => $units_qty) {


                $sql =
                    "SELECT `Purchase Order Submitted Unit Cost`,`Purchase Order Weight`,`Purchase Order CBM`,`Purchase Order Net Amount`,`Purchase Order Extra Cost Amount`,`Purchase Order Submitted Units` FROM `Purchase Order Transaction Fact`  WHERE `Purchase Order Transaction Fact Key`=?";


                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $potf_key
                    )
                );
                if ($row = $stmt->fetch()) {
                    if ($row['Purchase Order Submitted Units'] != 0) {
                        $net   = $units_qty * $row['Purchase Order Submitted Unit Cost'];
                        $extra = $units_qty * $row['Purchase Order Extra Cost Amount'] / $row['Purchase Order Submitted Units'];

                        if ($row['Purchase Order Weight'] == '') {
                            $weight = '';
                        } else {
                            $weight = $units_qty * $row['Purchase Order Weight'] / $row['Purchase Order Submitted Units'];
                        }

                        if ($row['Purchase Order CBM'] == '') {
                            $cbm = '';
                        } else {
                            $cbm = $units_qty * $row['Purchase Order CBM'] / $row['Purchase Order Submitted Units'];
                        }


                    } else {
                        $net    = 0;
                        $extra  = 0;
                        $weight = 0;
                        $cbm    = 0;
                    }


                    $sql = "UPDATE `Purchase Order Transaction Fact` SET 
                                `Purchase Order Transaction State`=?,
                                `Supplier Delivery Net Amount`=?,
                                `Supplier Delivery Extra Cost Amount`=?,
                                `Supplier Delivery Units`=?,
                                `Supplier Delivery Weight`=?,
                                 `Supplier Delivery CBM`=?,
                                
                                `Supplier Delivery Key`=?,`Supplier Delivery Transaction State`=?  ,`Supplier Delivery Transaction Placed`='No' WHERE `Purchase Order Transaction Fact Key`=?";

                    $this->db->prepare($sql)->execute(
                        array(
                            'Inputted',
                            $net,
                            $extra,
                            $units_qty,
                            $weight,
                            $cbm,
                            $delivery->id,
                            'InProcess',
                            $potf_key
                        )
                    );

                }


            }
            $delivery->update_totals();
            $this->update_totals();

            $parent = get_object(
                $this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']
            );

            if ($parent->get('Parent Skip Mark as Received') == 'Yes') {
                $delivery->update_state('Received');
            }


        }


        return $delivery;

    }

    function get($key = '') {
        $account = get_object('account', 1);

        if (!$this->id) {
            return false;
        }

        switch ($key) {
            case 'Estimated Production Datetime':


                include_once 'utils/object_functions.php';


                if ($this->data['Purchase Order Estimated Production Date'] and in_array(
                        $this->data['Purchase Order State'], array(
                                                               'InProcess',
                                                               'Submitted',
                                                               'Inputted',
                                                               'Dispatched'
                                                           )
                    )) {
                    return gmdate("Y-m-d H:i:s", strtotime($this->data['Purchase Order Estimated Production Date'].' +0:00'));
                } else {


                    if (in_array(
                        $this->data['Purchase Order State'], array(
                                                               'InProcess',
                                                               'Submitted',
                                                               'Inputted',
                                                               'Dispatched'
                                                           )
                    )) {


                        $parent = get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);


                        if ($parent->get($parent->table_name.' Average Production Days') != '' and is_numeric($parent->get($parent->table_name.' Average Production Days'))) {

                            //  print 'now +'.$parent->get($parent->table_name.' Average Delivery Days').' days';
                            if ($this->data['Purchase Order State'] == 'InProcess') {
                                return gmdate("Y-m-d H:i:s", strtotime('now +'.$parent->get($parent->table_name.' Average Production Days').' days +0:00'));
                            } else {


                                return gmdate("Y-m-d H:i:s", strtotime($this->get('Purchase Order Submitted Date').' +'.$parent->get($parent->table_name.' Average Production Days').' days +0:00'));

                            }

                        } else {


                            return '';
                        }
                    } else {

                        return '';
                    }

                }


                break;

            case 'Submitted Formatted Date':
                return strftime("%c", strtotime($this->get('Purchase Order Submitted Date').' +0:00'));

                break;
            case 'Estimated Receiving Datetime':


                include_once 'utils/object_functions.php';

                if ($this->data['Purchase Order Estimated Receiving Date'] and in_array(
                        $this->data['Purchase Order State'], array(
                                                               'InProcess',
                                                               'Submitted',
                                                               'Inputted',
                                                               'Dispatched'
                                                           )
                    )) {
                    return gmdate("Y-m-d H:i:s", strtotime($this->data['Purchase Order Estimated Receiving Date']));
                } else {


                    if (in_array(
                        $this->data['Purchase Order State'], array(
                                                               'InProcess',
                                                               'Submitted',
                                                               'Inputted',
                                                               'Dispatched'
                                                           )
                    )) {


                        $parent = get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);


                        if ($parent->get($parent->table_name.' Average Delivery Days') != '' and is_numeric($parent->get($parent->table_name.' Average Delivery Days'))) {

                            //  print 'now +'.$parent->get($parent->table_name.' Average Delivery Days').' days';
                            if ($this->data['Purchase Order State'] == 'InProcess') {
                                return gmdate("Y-m-d H:i:s", strtotime('now +'.$parent->get($parent->table_name.' Average Delivery Days').' days +0:00'));
                            } else {


                                return gmdate("Y-m-d H:i:s", strtotime($this->get('Purchase Order Submitted Date').' +'.$parent->get($parent->table_name.' Average Delivery Days').' days +0:00'));

                            }

                        } else {


                            return '';
                        }
                    } else {

                        return '';
                    }

                }

                break;

            case 'Estimated Production Formatted Date':


                if ($this->get('State Index') == 10) {

                    $parent = get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);


                    if ($parent->get($parent->table_name.' Average Production Days') != '' and is_numeric($parent->get($parent->table_name.' Average Production Days'))) {

                        return sprintf(_('%s after submit'), $parent->get('Production Time'));

                    } else {


                        return '';
                    }

                } else {
                    if ($this->get('Estimated Production Datetime')) {
                        return strftime("%e %b %Y", strtotime($this->get('Estimated Production Datetime')));
                    } else {
                        return '';
                    }
                }


                break;
            case 'Estimated Receiving Formatted Date':

                if ($this->get('State Index') == 10) {

                    $parent = get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);


                    if ($parent->get($parent->table_name.' Average Delivery Days') != '' and is_numeric($parent->get($parent->table_name.' Average Delivery Days'))) {

                        return sprintf(_('%s after submit'), $parent->get('Delivery Time'));

                    } else {


                        return '';
                    }

                } else {
                    if ($this->get('Estimated Receiving Datetime')) {
                        return strftime("%e %b %Y", strtotime($this->get('Estimated Receiving Datetime').' +0:00'));
                    } else {
                        return '';
                    }
                }

                break;
            case 'State Index':
            case 'Max State Index':

                switch (($key == 'State Index' ? $this->data['Purchase Order State'] : $this->data['Purchase Order Max Supplier Delivery State'])) {
                    case 'InProcess':
                        return 10;
                    case 'Submitted':
                        return 30;
                    case 'Inputted':
                        return 60;
                    case 'Dispatched':
                        return 70;
                    case 'Received':
                        return 80;

                    case 'Checked':
                        return 90;

                    case 'Placed':
                        return 100;

                    case 'Costing':
                        return 105;

                    case 'InvoiceChecked':
                        return 110;

                    case 'Cancelled':
                        return -10;


                    default:
                        return 0;

                }
                break;


            case 'Weight':
                include_once 'utils/natural_language.php';
                if ($this->data['Purchase Order Weight'] == '') {
                    if ($this->get('Purchase Order Number Items') > 0) {
                        return '<span class="italic very_discreet">'._('Unknown Weight').'</span>';
                    }
                } else {
                    return ($this->get('Purchase Order Missing Weights') > 0 ? '<i class="fa fa-exclamation-circle warning" aria-hidden="true" title="'._("Some supplier's products without weight").'" ></i> ' : '').weight($this->get('Purchase Order Weight'), 'Kg', 0);
                }
                break;
            case 'CBM':
                if ($this->data['Purchase Order CBM'] == '') {
                    if ($this->get('Purchase Order Number Items') > 0) {
                        return '<i class="fa fa-exclamation-circle error"></i> <span class="italic very_discreet error">'._('Unknown CBM').'</span>';
                    }
                } else {
                    return ($this->get('Purchase Order Missing CBMs') > 0 ? '<i class="fa fa-exclamation-circle warning" aria-hidden="true" title="'._("Some supplier's products without CBM").'" ></i> ' : '').number($this->data['Purchase Order CBM']).' m³';
                }
                break;
            case 'Estimated Receiving Date':
            case 'Estimated Production Date':
            case 'Agreed Receiving Date':
            case 'Creation Date':
            case 'Submitted Date':
            case 'Submitted Agent Date':
            case 'Cancelled Date':
                if ($this->data['Purchase Order '.$key] == '') {
                    return '';
                }

                return strftime(
                    "%e %b %Y", strtotime($this->data['Purchase Order '.$key].' +0:00')
                );

                break;

            case 'Received Date':
                if ($this->get('State Index') < 0 or $this->get('State Index') >= 60) {
                    return '';

                } else {

                    if ($this->data['Purchase Order Estimated Receiving Date']) {
                        return '<span class="discreet"><i class="fa fa-thumb-tack" aria-hidden="true"></i> '.strftime("%e %b %Y", strtotime($this->get('Estimated Receiving Date').' +0:00')).'</span>';
                    } else {

                        $parent = get_object(
                            $this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']
                        );

                        if ($this->data['Purchase Order State'] == 'InProcess') {


                            if ($parent->get($parent->table_name.' Average Delivery Days') and is_numeric($parent->get($parent->table_name.' Average Delivery Days'))) {
                                return '<span class="discreet italic">'.strftime("%d %b %Y", strtotime('now +'.$parent->get($parent->table_name.' Average Delivery Days').' days +0:00')).'</span>';

                            } else {
                                return '&nbsp;';
                            }
                        } else {

                            $parent = get_object(
                                $this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']
                            );
                            if ($parent->get(
                                    $parent->table_name.' Average Delivery Days'
                                ) and is_numeric(
                                    $parent->get(
                                        $parent->table_name.' Average Delivery Days'
                                    )
                                )) {
                                return '<span class="discreet italic">'.strftime(
                                        "%d %b %Y", strtotime(
                                                      $this->get('Purchase Order Submitted Date').' +'.$parent->get(
                                                          $parent->table_name.' Average Delivery Days'
                                                      ).' days +0:00'
                                                  )
                                    ).'</span>';

                            } else {
                                return '&nbsp;';
                            }
                        }

                    }
                }

                break;
            case ('Main Source Type'):
                switch ($this->data['Purchase Order Main Source Type']) {
                    case 'Post':
                        return _('post');


                    case 'Internet':
                        return _('online');

                    case 'Telephone':
                        return _('telephone');

                    case 'Fax':
                        return _('Fax');

                    case 'In Person':
                        return _('in Person');

                    case 'Other':
                        return _('other');


                    default:
                        return $this->data['Purchase Order Main Source Type'];

                }


            case ('State'):

                if ($this->data['Purchase Order Production'] == 'Yes') {
                    switch ($this->data['Purchase Order State']) {
                        case 'InProcess':
                            return _('Planning');

                        case 'Submitted':
                            return _('Manufacturing');

                        case 'Inputted':
                            return _('Delivery in process');

                        case 'Dispatched':
                            return _('Delivery dispatched');

                        case 'Received':
                            return _('Received');

                        case 'Checked':
                            return _('Checked');

                        case 'Placed':
                            return _('Booked in');

                        case 'Costing':
                            return _('Booked in').' ('._('Review costing').')';

                        case 'InvoiceChecked':
                            return _('Booked in').' ('._('Costing done').')';

                        case 'Cancelled':
                            return _('Cancelled');


                        default:
                            return $this->data['Purchase Order State'];

                    }
                } else {
                    switch ($this->data['Purchase Order State']) {
                        case 'InProcess':
                            return _('In Process');

                        case 'Submitted':
                            return _('Submitted');

                        case 'Inputted':
                            return _('Delivery in process');

                        case 'Dispatched':
                            return _('Delivery dispatched');
                            break;
                        case 'Received':
                            return _('Received');

                        case 'Checked':
                            return _('Checked');

                        case 'Placed':
                            return _('Booked in');

                        case 'Costing':
                            return _('Booked in').' ('._('Review costing').')';

                        case 'InvoiceChecked':
                            return _('Booked in').' ('._('Costing done').')';

                        case 'Cancelled':
                            return _('Cancelled');


                        default:
                            return $this->data['Purchase Order State'];

                    }
                }


                break;

            case ('Agent State'):
                switch ($this->data['Purchase Order State']) {
                    case 'InProcess':
                        return _('In process by client');
                    case 'Submitted':
                        return _("Client's order received");

                    case 'Confirmed':
                        return _("Suppliers orders");

                    case 'Checking':
                        return _('Checking');

                    case 'In Warehouse':
                    case 'Done':
                        return _('Dispatched');

                    case 'Cancelled':
                        return _('Cancelled');


                    default:
                        return $this->data['Purchase Order State'];

                }


            case ('Agent State Short'):
                switch ($this->data['Purchase Order State']) {
                    case 'InProcess':
                        return _('In process by client');
                        break;

                    case 'Submitted':
                        return _('CO received');
                        break;
                    case 'Confirmed':
                        return _("Suppliers orders");
                        break;
                    case 'Checking':
                        return _('Checking');
                        break;
                    case 'In Warehouse':
                    case 'Done':
                        return _('Dispatched');
                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;


                    default:
                        return $this->data['Purchase Order State'];
                        break;
                }

                break;

            case 'Total Amount':
            case 'Extra Cost Amount':
                return money(
                    $this->data['Purchase Order '.$key], $this->data['Purchase Order Currency Code']
                );
                break;

            case 'AC Subtotal Amount':

                return money(
                    ($this->data['Purchase Order Items Net Amount'] + $this->data['Purchase Order Extra Cost Amount']) * $this->get('Purchase Order Currency Exchange'), $account->get('Currency Code')
                );
                break;
            case 'AC Extra Costs Amount':
                //todo add this field to DB and use it the real value (May be need when more then one delivery per PO)
                return money(
                    0, $account->get('Currency Code')
                );
                break;

            case 'AC Total Amount':
                //todo add AC Extra Costs Amount (May be need when more then one delivery per PO)

                return money(
                    ($this->data['Purchase Order Items Net Amount'] + $this->data['Purchase Order Extra Cost Amount']) * $this->get('Purchase Order Currency Exchange'), $account->get('Currency Code')
                );


            case 'Number Items':
                return number($this->data ['Purchase Order Number Items']);

            case 'Number Supplier Delivery Items':

                if ($this->get('State Index') < 60) {
                    return '-';
                } else {
                    return number(
                        $this->data ['Purchase Order Number Supplier Delivery Items']
                    );
                }

                break;
            case 'Number Placed Items':

                if ($this->get('State Index') < 80) {
                    return '-';
                } else {
                    return number(
                        $this->data ['Purchase Order Number Placed Items']
                    );
                }

                break;


            case 'payment terms':

                return $this->metadata(preg_replace('/\s/', '_', $key));
            default:


                if (preg_match(
                    '/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/', $key
                )) {
                    $amount = 'Purchase Order '.$key;

                    return money(
                        $this->data[$amount], $this->data['Purchase Order Currency Code']
                    );
                }

                if (preg_match(
                    '/^(Total|Items|(Shipping |Charges )?Net).*(Amount Account Currency)$/', $key
                )) {
                    $key    = preg_replace(
                        '/ Account Currency/', '', $key
                    );
                    $amount = 'Purchase Order '.$key;

                    return money(
                        $this->data['Purchase Order Currency Exchange'] * $this->data[$amount], $account->get('Account Currency')
                    );


                }


                break;
        }


        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (array_key_exists(
            'Purchase Order '.$key, $this->data
        )) {
            return $this->data[$this->table_name.' '.$key];
        }

        return false;

    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }

    function update_totals() {


        $sql = sprintf(
            "SELECT 
                    sum(`Purchase Order Weight`) AS  weight,
                    sum(if(isNULL(`Purchase Order Weight`),1,0) )AS missing_weights ,
                    sum(if(isNULL(`Purchase Order CBM`),1,0) )AS missing_cbms , 
                    sum(`Purchase Order CBM` )AS cbm ,
                    count(DISTINCT `Supplier Key`) AS num_suppliers ,
                    count(*) AS num_items ,
                    sum(if((`Purchase Order Ordering Units`-`Purchase Order Submitted Cancelled Units`)>0,1,0)) AS num_ordered_items ,
                    sum(`Purchase Order Net Amount`) AS items_net, 
                    sum(`Purchase Order Extra Cost Amount`) AS extra_cost 
                            FROM `Purchase Order Transaction Fact`  POTF left join `Supplier Part Dimension` SPD  on (POTF.`Supplier Part Key`=SPD.`Supplier Part Key`)  WHERE `Purchase Order Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $total_net  = $row['items_net'];
                $extra_cost = $row['extra_cost'];
                $total      = $total_net + $extra_cost;

                $this->fast_update(
                    array(
                        'Purchase Order Items Net Amount'     => $row['items_net'],
                        'Purchase Order Extra Cost Amount'    => $row['extra_cost'],
                        'Purchase Order Number Suppliers'     => $row['num_suppliers'],
                        'Purchase Order Number Items'         => $row['num_items'],
                        'Purchase Order Ordered Number Items' => $row['num_ordered_items'],
                        'Purchase Order Weight'               => $row['weight'],
                        'Purchase Order CBM'                  => $row['cbm'],
                        'Purchase Order Missing Weights'      => $row['missing_weights'],
                        'Purchase Order Missing CBMs'         => $row['missing_cbms'],
                        'Purchase Order Total Amount'         => $total
                    )
                );
            }
        }

        $sql = sprintf(
            "SELECT count(*) AS num_items FROM `Purchase Order Transaction Fact` WHERE `Supplier Delivery Key`>0 AND  `Purchase Order Key`=%d", $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->fast_update(
                    array(
                        'Purchase Order Number Supplier Delivery Items' => $row['num_items'],
                    )
                );
            }
        }


        $this->fast_update(
            array(
                'Purchase Order State'                       => $this->get_purchase_order_state(),
                'Purchase Order Max Supplier Delivery State' => $this->get_max_purchase_order_state()
            )
        );


    }


    private function get_purchase_order_state() {

        //'NA','InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'


        $index = 0;
        $i     = 0;

        $has_no_received_items = false;

        $sql  = "select `Purchase Order Transaction State` from `Purchase Order Transaction Fact` where  `Purchase Order Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($this->id)
        );
        while ($row = $stmt->fetch()) {


            $_index = $this->get_purchase_order_item_state_index($row['Purchase Order Transaction State']);

            if ($_index > 0) {
                if ($i > 0) {

                    if ($_index < $index) {
                        $index = $_index;
                    }
                } else {
                    $index = $_index;
                }
                $i++;
            } else {

                if ($_index == 0) {
                    $has_no_received_items = true;
                }

            }
        }

        if ($i == 0) {
            if ($has_no_received_items) {
                $index = 0;
            } else {
                $index = -10;
            }

        }


        if ($index <= 0) {

            if ($this->data['Purchase Order State'] == 'InProcess') {
                return 'InProcess';
            } elseif ($index == 0) {
                return 'NoReceived';
            } else {
                return 'Cancelled';
            }

        } elseif ($index <= 10) {
            return 'InProcess';
        } elseif ($index <= 20) {
            return 'Submitted';
        } elseif ($index <= 35) {
            return 'Inputted';
        } elseif ($index <= 40) {
            return 'Dispatched';
        } elseif ($index <= 50) {
            return 'Received';
        } elseif ($index <= 60) {
            return 'Checked';
        } elseif ($index <= 70) {
            return 'Placed';
        } else {
            return 'InvoiceChecked';

        }


    }

    private function get_purchase_order_item_state_index($state) {

        switch ($state) {
            case 'Cancelled';
                $index = -10;
                break;
            case 'NoReceived';
                $index = 0;
                break;

            case 'InProcess';
                $index = 10;
                break;
            case 'Submitted';
                $index = 20;
                break;
            case 'ReceivedAgent':
                $index = 30;

                break;
            case 'Confirmed';
                $index = 31;
                break;
            case 'ProblemSupplier';
                $index = 32;
                break;
            case 'InDelivery';
                $index = 33;
                break;
            case 'Inputted';
                $index = 34;
                break;


            case 'Dispatched';
                $index = 40;
                break;
            case 'Received';
                $index = 50;
                break;
            case 'Checked';
                $index = 60;
                break;
            case 'Placed';


                $index = 70;
                break;
            case 'InvoiceChecked';


                $index = 80;
                break;
            default:
                exit('error get_purchase_order_item_state_index');

        }

        return $index;
    }

    private function get_max_purchase_order_state() {

        $index = 0;

        $sql  = "select `Purchase Order Transaction State` from `Purchase Order Transaction Fact` where  `Purchase Order Key`=?   ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($this->id)
        );
        while ($row = $stmt->fetch()) {
            $_index = $this->get_purchase_order_item_state_index($row['Purchase Order Transaction State']);
            if ($_index > 20) {
                if ($_index > $index) {
                    $index = $_index;
                }
            }
        }


        if ($index <= 20) {
            return 'NA';
        } elseif ($index <= 35) {
            return 'Inputted';
        } elseif ($index <= 40) {
            return 'Dispatched';
        } elseif ($index <= 50) {
            return 'Received';
        } elseif ($index <= 60) {
            return 'Checked';
        } elseif ($index <= 70) {
            return 'Placed';
        } else {
            return 'InvoiceChecked';
        }
    }


    public function get_deliveries($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.SupplierDelivery.php';
        }


        $deliveries = array();
        $sql        = sprintf(
            "SELECT `Supplier Delivery Key` FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Key`=%d  GROUP BY `Supplier Delivery Key`", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Supplier Delivery Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $deliveries[$row['Supplier Delivery Key']] = get_object('SupplierDelivery', $row['Supplier Delivery Key']);

                } else {
                    $deliveries[$row['Supplier Delivery Key']] = $row['Supplier Delivery Key'];
                }
            }
        }

        return $deliveries;

    }


    function update_item($data) {

        switch ($data['field']) {
            case 'Purchase Order Cartons':
            case 'Purchase Order SKOs':
            case 'Purchase Order Units':
                return $this->update_item_quantity($data);


            default:

                return false;
        }

    }

    function update_item_quantity($data) {


        $account = get_object('account', 1);


        $item_key          = $data['item_key'];
        $item_historic_key = $data['item_historic_key'];
        $qty               = $data['qty'];


        $supplier_part = get_object('SupplierPart', $item_key);


        switch ($data['field']) {
            case 'Purchase Order Cartons':
                $unit_qty = $qty * $supplier_part->get('Supplier Part Packages Per Carton') * $supplier_part->part->get('Part Units Per Package');
                break;
            case 'Purchase Order SKOs':
                $unit_qty = $qty * $supplier_part->part->get('Part Units Per Package');
                break;
            case 'Purchase Order Units':
                $unit_qty = $qty;
                break;
            default:
                /** @noinspection PhpUnhandledExceptionInspection */ throw new Exception("Invalid field in PO update_item_quantity");


        }

        $date            = gmdate('Y-m-d H:i:s');
        $transaction_key = '';

        if ($unit_qty == 0) {

            $sql = sprintf(
                "SELECT `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Key`=%d AND `Supplier Part Key`=%d ", $this->id, $item_key
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $sql = sprintf(
                        "DELETE FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Transaction Fact Key`=%d ", $row['Purchase Order Transaction Fact Key']
                    );
                    $this->db->exec($sql);
                    $transaction_key = $row['Purchase Order Transaction Fact Key'];

                }
            }


            $amount      = 0;
            $subtotals   = '';
            $qty_units   = 0;
            $qty_skos    = 0;
            $qty_cartons = 0;
            $input_class = '';

        } else {


            $amount = $unit_qty * $supplier_part->get('Supplier Part Unit Cost');

            $extra_amount = $unit_qty * $supplier_part->get('Supplier Part Unit Extra Cost');
            if (is_numeric($supplier_part->get('Supplier Part Carton CBM'))) {
                $cbm = $unit_qty * $supplier_part->get('Supplier Part Carton CBM') / $supplier_part->get('Supplier Part Packages Per Carton') / $supplier_part->part->get('Part Units Per Package');
            } else {
                $cbm = null;
            }


            if (is_numeric($supplier_part->part->get('Part Package Weight'))) {
                $weight = $unit_qty * $supplier_part->part->get('Part Package Weight') / $supplier_part->part->get('Part Units Per Package');
            } else {
                $weight = null;
            }


            $sql = sprintf(
                "SELECT `Purchase Order Transaction Fact Key`,`Note to Supplier Locked` FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Key`=%d AND `Supplier Part Key`=%d ", $this->id, $item_key
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $sql = "
                    update`Purchase Order Transaction Fact` set  
                        `Purchase Order Ordering Units`=?,
                        `Purchase Order Last Updated Date`=?,
                        `Purchase Order Net Amount`=? ,
                        `Purchase Order Extra Cost Amount`=? ,
                        `Purchase Order CBM`=?,
                        `Purchase Order Weight`=?  where  `Purchase Order Transaction Fact Key`=? ";


                    $this->db->prepare($sql)->execute(
                        array(
                            $unit_qty,
                            $date,
                            $amount,
                            $extra_amount,
                            $cbm,
                            $weight,
                            $row['Purchase Order Transaction Fact Key']
                        )
                    );


                    $transaction_key = $row['Purchase Order Transaction Fact Key'];


                } else {


                    $item_index = 1;
                    $sql        = "SELECT max(`Purchase Order Item Index`) item_index FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Key`=?";

                    $stmt2 = $this->db->prepare($sql);
                    $stmt2->execute(
                        array(
                            $this->id
                        )
                    );
                    while ($row2 = $stmt2->fetch()) {
                        if (is_numeric($row2['item_index'])) {
                            $item_index = $row2['item_index'] + 1;
                        }
                    }


                    $sql = "INSERT INTO `Purchase Order Transaction Fact` (`Purchase Order Transaction Part SKU`,`Purchase Order Item Index`,`Supplier Part Key`,`Supplier Part Historic Key`,`Currency Code`,`Purchase Order Last Updated Date`,`Purchase Order Transaction State`,
					`Supplier Key`,`Agent Key`,`Purchase Order Key`,`Purchase Order Ordering Units`,`Purchase Order Net Amount`,`Purchase Order Extra Cost Amount`,`Note to Supplier`,`Purchase Order CBM`,`Purchase Order Weight`,
					`User Key`,`Creation Date`
					) VALUES (?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?)";


                    $this->db->prepare($sql)->execute(
                        array(
                            $supplier_part->get('Supplier Part Part SKU'),
                            $item_index,
                            $item_key,
                            $item_historic_key,
                            $this->get('Purchase Order Currency Code'),
                            $date,
                            $this->get('Purchase Order State'),
                            $supplier_part->get('Supplier Part Supplier Key'),
                            ($supplier_part->get('Supplier Part Agent Key') == '' ? null : sprintf("%d", $supplier_part->get('Supplier Part Agent Key'))),
                            $this->id,
                            round($unit_qty, 6),
                            round($amount, 2),
                            round($extra_amount, 2),
                            $supplier_part->get('Supplier Part Note to Supplier'),
                            $cbm,
                            $weight,
                            $this->editor['User Key'],
                            $date
                        )
                    );


                    $transaction_key = $this->db->lastInsertId();


                }


            }


            include_once 'utils/supplier_order_functions.php';


            $_data     = array(
                'units_qty'                         => $unit_qty,
                'Part Units Per Package'            => $supplier_part->part->get('Part Units Per Package'),
                'Supplier Part Packages Per Carton' => $supplier_part->get('Supplier Part Packages Per Carton'),
                'Supplier Part Unit Cost'           => $supplier_part->get('Supplier Part Unit Cost'),
                'Supplier Part Currency Code'       => $supplier_part->get('Supplier Part Currency Code'),
                'Part Package Weight'               => $supplier_part->part->get('Part Package Weight'),
                'Supplier Part Carton CBM'          => $supplier_part->get('Supplier Part Carton CBM'),
                'account_currency_code'             => $account->get('Account Currency'),
                'currency_code'                     => $this->get('Purchase Order Currency Code'),
                'exchange'                          => $this->get('Purchase Order Currency Exchange'),


            );
            $subtotals = get_purchase_order_subtotals($_data);

            $qty_units   = $unit_qty;
            $qty_skos    = $unit_qty / $supplier_part->part->get('Part Units Per Package');
            $qty_cartons = $qty_skos / $supplier_part->get('Supplier Part Packages Per Carton');

            if ($unit_qty % ($supplier_part->part->get('Part Units Per Package') * $supplier_part->get('Supplier Part Packages Per Carton')) != 0 or $unit_qty % ($supplier_part->part->get('Part Units Per Package')) != 0) {
                $input_class = 'error';
            } else {
                $input_class = '';
            }


        }

        $this->update_purchase_order_item_state($transaction_key, $update_part_next_deliveries_data = false);
        $supplier_part->part->update_next_deliveries_data();
        $this->update_totals();
        $operations = array();

        if ($this->get('State Index') == 10) {


            if ($this->get('Purchase Order Number Items') == 0) {
                $operations = array(
                    'delete_operations',
                );
            } else {
                $operations = array(
                    'delete_operations',
                    'submit_operations'
                );
            }
        }


        if ($this->data['Purchase Order Parent'] == 'Agent' and $this->get('State Index') >= 20) {
            $supplier_part->get('Supplier Part Supplier Key');

            $sql = sprintf(
                'select `Agent Supplier Purchase Order Key` from `Agent Supplier Purchase Order Dimension` where `Agent Supplier Purchase Order Purchase Order Key`=%d and `Agent Supplier Purchase Order Supplier Key`=%d  ', $this->id,
                $supplier_part->get('Supplier Part Supplier Key')
            );

            if ($result = $this->db->query($sql)) {


                if ($row = $result->fetch()) {


                    $agent_supplier_purchase_order = get_object('AgentSupplierPurchaseOrder', $row['Agent Supplier Purchase Order Key']);

                } else {


                    include_once 'class.Agent_Supplier_Purchase_Order.php';


                    $supplier = get_object('Supplier', $supplier_part->get('Supplier Part Supplier Key'));

                    $order_data = array(
                        'Agent Supplier Purchase Order Supplier Key'       => $supplier->id,
                        'Agent Supplier Purchase Order Public ID'          => preg_replace('/\s/', '', $this->data['Purchase Order Public ID'].'.'.$supplier->get('Supplier Code')),
                        'Agent Supplier Purchase Order Purchase Order Key' => $this->id,
                        'Agent Supplier Purchase Order Currency Code'      => $this->data['Purchase Order Currency Code'],
                        'editor'                                           => $this->editor
                    );

                    //print_r($order_data);
                    $agent_supplier_purchase_order = new AgentSupplierPurchaseOrder('new', $order_data);


                }

                //print $transaction_key;

                if ($transaction_key > 0) {

                    $sql = sprintf(
                        "update`Purchase Order Transaction Fact` set  `Agent Supplier Purchase Order Key`=%s  where  `Purchase Order Transaction Fact Key`=%d ", $agent_supplier_purchase_order->id, $transaction_key
                    );

                    // print $sql;
                    $this->db->exec($sql);

                }

                $agent_supplier_purchase_order->update_totals();


            }


        }


        $this->update_metadata = array(
            'class_html' => array(
                'Purchase_Order_Total_Amount'                      => $this->get('Total Amount'),
                'Purchase_Order_Total_Amount_Account_Currency'     => $this->get('Total Amount Account Currency'),
                'Purchase_Order_Items_Net_Amount'                  => $this->get('Items Net Amount'),
                'Purchase_Order_Items_Net_Amount_Account_Currency' => $this->get('Items Net Amount Account Currency'),
                'Purchase_Order_AC_Total_Amount'                   => $this->get('AC Total Amount'),
                'Purchase_Order_AC_Extra_Costs_Amount'             => $this->get('AC Extra Costs Amount'),
                'Purchase_Order_AC_Subtotal_Amount'                => $this->get('AC Subtotal Amount'),
                'Purchase_Order_Weight'                            => $this->get('Weight'),
                'Purchase_Order_CBM'                               => $this->get('CBM'),
                'Purchase_Order_Number_Items'                      => $this->get('Number Items'),
            ),
            'operations' => $operations,
        );


        return array(
            'transaction_key' => $transaction_key,
            'to_charge'       => money($amount, $this->data['Purchase Order Currency Code']),
            'qty'             => $qty + 0,
            'subtotals'       => $subtotals,
            'qty_units'       => $qty_units,
            'qty_skos'        => $qty_skos,
            'qty_cartons'     => $qty_cartons,
            'input_class'     => $input_class
        );


    }

    /**
     * @param integer $potf_key
     * @param bool    $update_part_next_deliveries_data
     */
    function update_purchase_order_item_state($potf_key, $update_part_next_deliveries_data = true) {


        $sql  = "select PO.`Purchase Order State`,`Purchase Order Submitted Cancelled Units`,`Purchase Order Transaction Fact Key`,`Purchase Order Ordering Units`,`Purchase Order Submitted Units`,POTF.`Supplier Delivery Key` ,`Supplier Delivery Units`,
                `Supplier Delivery Transaction State`,`Purchase Order Transaction State`,`Purchase Order Transaction Part SKU`
                from `Purchase Order Transaction Fact`  POTF left join 
                `Purchase Order Dimension` PO on (POTF.`Purchase Order Key`=PO.`Purchase Order Key`) left join 
                `Supplier Delivery Dimension` SD on (POTF.`Supplier Delivery Key`=SD.`Supplier Delivery Key`) 
                where POTF.`Purchase Order Key`=?  and `Purchase Order Transaction Fact Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id,
                $potf_key
            )
        );
        while ($row = $stmt->fetch()) {

            $old_state = $row['Purchase Order Transaction State'];

            $purchase_orders_units_cancelled = $row['Purchase Order Submitted Cancelled Units'];
            if ($purchase_orders_units_cancelled == '') {
                $purchase_orders_units_cancelled = 0;
            }
            if ($row['Supplier Delivery Key'] > 0) {
                $submitted_delivery_units = $row['Purchase Order Submitted Units'];

            } else {
                $submitted_delivery_units = 0;

            }

            $deliveries_data   = array();
            $deliveries_data[] = array(
                'state' => $row['Supplier Delivery Transaction State'],
            );

            $state = $this->get_item_state($row['Purchase Order Submitted Units'] - $purchase_orders_units_cancelled, $submitted_delivery_units, $deliveries_data);


            $sql = "update `Purchase Order Transaction Fact` set `Purchase Order Transaction State`=? where `Purchase Order Transaction Fact Key`=?";


            $this->db->prepare($sql)->execute(
                array(
                    $state,
                    $row['Purchase Order Transaction Fact Key']
                )
            );


            if ($old_state != $state and $update_part_next_deliveries_data) {
                /**
                 * @var $part \Part
                 */
                $part = get_object('Part', $row['Purchase Order Transaction Part SKU']);
                $part->update_next_deliveries_data();
            }


        }

    }

    public function get_item_state($submitted, $in_delivery, $deliveries_data) {


        if ($in_delivery > 0) {
            if (($submitted) > $in_delivery) {
                $state = $this->get_purchase_order_item_state($submitted);
            } else {
                $state = $this->get_supplier_delivery_item_state($deliveries_data);
            }
        } else {
            $state = $this->get_purchase_order_item_state($submitted);

        }

        return $state;

    }

    private function get_purchase_order_item_state($submitted_quantity) {

        if ($this->data['Purchase Order State'] == 'InProcess') {
            return 'InProcess';

        } else {
            if ($submitted_quantity > 0) {
                return 'Submitted';
            } else {
                return 'Cancelled';
            }

        }
    }

    /**
     * @param $deliveries_data
     *
     * @return string 'Cancelled'|'NoReceived'|'InProcess'|'Submitted'|'ProblemSupplier'|'Confirmed'|'ReceivedAgent'|'InDelivery'|'Inputted'|'Dispatched'|'Received'|'Checked'|'Placed'|'InvoiceChecked'
     */
    private function get_supplier_delivery_item_state($deliveries_data) {


        $has_not_received_items = false;

        $index = 0;
        $i     = 0;
        foreach ($deliveries_data as $item_data) {
            $_index = $this->get_delivery_item_state_index($item_data['state']);

            if ($_index > 0) {
                if ($i > 0) {

                    if ($_index < $index) {
                        $index = $_index;
                    }
                } else {
                    $index = $_index;
                }
                $i++;
            } elseif ($_index == 0) {
                $has_not_received_items = true;
            }


        }

        if ($index <= 0) {
            if ($has_not_received_items) {
                $state = 'NoReceived';
            } else {
                $state = 'Cancelled';
            }

        } elseif ($index <= 10) {
            $state = 'Submitted';

        } elseif ($index <= 20) {
            $state = 'Dispatched';

        } elseif ($index <= 30) {
            $state = 'Received';

        } elseif ($index <= 40) {
            $state = 'Checked';

        } elseif ($index <= 50) {
            $state = 'Placed';

        } else {
            $state = 'InvoiceChecked';

        }


        return $state;


    }


    /**
     * @param $state string 'Cancelled'|'NoReceived'|'InProcess'|'Dispatched'|'Received'|'Checked'|'Placed'|'CostingDone'
     *
     * @return int
     */
    private function get_delivery_item_state_index($state) {

        switch ($state) {
            case 'Cancelled';
                $index = -10;
                break;
            case 'NoReceived';
                $index = 0;
                break;

            case 'InProcess';
                $index = 10;
                break;
            case 'Dispatched';
                $index = 20;
                break;
            case 'Received';
                $index = 30;
                break;
            case 'Checked';
                $index = 40;
                break;
            case 'Placed';
                $index = 50;
                break;
            case 'CostingDone';
                $index = 60;
                break;
            default:
                $index = 0;
        }

        return $index;
    }

    function delete() {

        include_once 'class.Attachment.php';
        $items = array();

        if ($this->data['Purchase Order State'] == 'InProcess') {


            $sql = sprintf(
                "SELECT POTF.`Supplier Part Historic Key`,`Purchase Order Ordering Units`,`Supplier Part Reference`,POTF.`Supplier Part Key`,`Supplier Part Part SKU` FROM `Purchase Order Transaction Fact` POTF
			LEFT JOIN `Supplier Part Historic Dimension` SPH ON (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
            LEFT JOIN  `Supplier Part Dimension` SP ON (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)

			 WHERE `Purchase Order Key`=%d", $this->id
            );


            if ($result = $this->db->query($sql)) {

                foreach ($result as $row) {
                    $items[] = array(
                        $row['Supplier Part Historic Key'],
                        $row['Supplier Part Reference'],
                        $row['Purchase Order Ordering Units'],
                        $row['Supplier Part Key'],
                        $row['Supplier Part Part SKU'],
                    );
                }
            }


            $data     = array(
                'data'  => $this->data,
                'items' => $items
            );
            $metadata = json_encode($data);


            $sql = sprintf(
                "INSERT INTO `Purchase Order Deleted Dimension`  (`Purchase Order Deleted Key`,`Purchase Order Deleted Public ID`,`Purchase Order Deleted Date`,`Purchase Order Deleted Metadata`) VALUES (%d,%s,%s,%s) ", $this->id,
                prepare_mysql($this->get('Purchase Order Public ID')), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(
                    gzcompress(
                        $metadata, 9
                    )
                )

            );


            $stmt = $this->db->prepare($sql);
            $stmt->execute();


            $history_data = array(
                'History Abstract' => _('Purchase order deleted'),
                'History Details'  => '',
                'Action'           => 'deleted'
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            $sql = sprintf(
                "DELETE FROM `Purchase Order Dimension` WHERE `Purchase Order Key`=%d", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Key`=%d", $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "SELECT `History Key`,`Type` FROM `Purchase Order History Bridge` WHERE `Purchase Order Key`=%d", $this->id
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    if ($row['Type'] == 'Attachments') {
                        $sql = sprintf(
                            "SELECT `Attachment Bridge Key`,`Attachment Key` FROM `Attachment Bridge` WHERE `Subject`='Purchase Order History Attachment' AND `Subject Key`=%d", $row['History Key']
                        );

                        if ($result2 = $this->db->query($sql)) {
                            foreach ($result2 as $row2) {

                                include_once 'class.Attachment.php';
                                $sql = sprintf(
                                    "DELETE FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=%d", $row2['Attachment Bridge Key']
                                );
                                $this->db->exec($sql);
                                $attachment = new Attachment(
                                    $row2['Attachment Key']
                                );
                                $attachment->delete();
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }


                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "SELECT `Attachment Bridge Key`,`Attachment Key` FROM `Attachment Bridge` WHERE `Subject`='Purchase Order' AND `Subject Key`=%d", $this->id
            );
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    include_once 'class.Attachment.php';
                    $sql = sprintf(
                        "DELETE FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=%d", $row['Attachment Bridge Key']
                    );
                    $this->db->exec($sql);
                    $attachment = new Attachment($row['Attachment Key']);
                    $attachment->delete();
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $this->deleted = true;

        } else {

            $this->error = true;
            $this->msg   = 'Can not deleted submitted purchase orders';
        }


        foreach ($items as $item) {
            $part = get_object('Part', $item[4]);
            $part->update_next_deliveries_data();

        }

    }

    function submit($data) {

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = $value;
            }

        }

        $sql = sprintf(
            "UPDATE `Purchase Order Dimension` SET `Purchase Order Submitted Date`=%s,`Purchase Order Estimated Receiving Date`=%s,`Purchase Order Main Source Type`=%s,`Purchase Order Main Buyer Key`=%s,`Purchase Order Main Buyer Name`=%s,`Purchase Order State`='Submitted' WHERE `Purchase Order Key`=%d",
            prepare_mysql($data['Purchase Order Submitted Date']), prepare_mysql($data['Purchase Order Estimated Receiving Date']), prepare_mysql($data['Purchase Order Main Source Type']), prepare_mysql($data['Purchase Order Main Buyer Key']),
            prepare_mysql($data['Purchase Order Main Buyer Name']), $this->id
        );


        $this->db->exec($sql);

        $sql = sprintf(
            "UPDATE `Purchase Order Transaction Fact` SET  `Purchase Order Last Updated Date`=%s ,`Purchase Order Transaction State`='Submitted'  WHERE `Purchase Order Key`=%d", prepare_mysql($data['Purchase Order Submitted Date']), $this->id
        );
        $this->db->exec($sql);


        $this->update_parts_next_delivery();

        $history_data = array(
            'History Abstract' => _('Purchase order submitted'),
            'History Details'  => ''
        );
        $this->add_subject_history($history_data);

    }

    function update_parts_next_delivery() {

        $account = get_object('account', 1);

        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'   => 'update_parts_next_delivery',
            'po_key' => $this->id,
            'editor' => $this->editor
        ), $account->get('Account Code'), $this->db
        );


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {
        switch ($field) {

            case 'payment terms':
                $this->fast_update_json_field('Purchase Order Metadata', preg_replace('/\s/', '_', $field), $value);

                break;
            case 'Purchase Order Port of Export':
            case 'Purchase Order Port of Import':
            case 'Purchase Order Warehouse Address':
            case 'Purchase Order Incoterm':
                $this->update_field($field, $value, $options);


                if ($value == '') {
                    $this->update_metadata['show'] = array(preg_replace('/\s/', '_', $field).'_empty');
                } else {
                    $this->update_metadata['hide'] = array(preg_replace('/\s/', '_', $field).'_empty');

                }


                break;

            case 'Purchase Order State':
                $this->update_state(
                    $value, $options, $metadata
                );
                break;
            case 'Purchase Order Estimated Receiving Date':
                $this->update_field($field, $value, $options);


                $this->update_purchase_order_date();
                $this->update_parts_next_delivery();

                $this->update_metadata = array(
                    'class_html' => array(
                        'Purchase_Order_Received_Date' => $this->get('Received Date'),

                    )
                );

                break;
            case 'Purchase Order Estimated Production Date':
                $this->update_field($field, $value, $options);

                $this->update_metadata = array(
                    'class_html' => array(
                        'Purchase_Order_Production_Date' => $this->get('Estimated Production Formatted Date'),

                    )
                );

                break;
            default:


                if (array_key_exists(
                    $field, $this->data
                )) {
                    if ($value != $this->data[$field]) {

                        $this->update_field(
                            $field, $value, $options
                        );
                    }
                }

                break;
        }


    }

    function update_state($value, $options = '', $metadata = array()) {

        if (isset($metadata['date']) and $metadata['date'] != '') {
            $date = $metadata['date'];
        } else {
            $date = gmdate('Y-m-d H:i:s');
        }


        $old_value  = $this->get('Purchase Order State');
        $operations = array();


        if ($old_value != $value) {
            switch ($value) {
                case 'InProcess':


                    $this->fast_update(
                        array(
                            'Purchase Order Submitted Date'            => '',
                            'Purchase Order Send Date'                 => '',
                            'Purchase Order Estimated Production Date' => '',
                            'Purchase Order Estimated Receiving Date'  => '',
                            'Purchase Order State'                     => $value,
                        )
                    );

                    $this->update_purchase_order_date();

                    $operations = array(
                        'delete_operations',
                        'submit_operations',
                        'all_available_items',
                        'new_item'
                    );


                    $history_data = array(
                        'History Abstract' => _('Purchase order send back to process'),
                        'History Details'  => '',
                        'Action'           => 'created'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


                    $sql = 'select `Purchase Order Transaction Fact Key`,`Supplier Part Unit Cost`,`Supplier Part Unit Extra Cost`,`Purchase Order Ordering Units`,SPD.`Supplier Part Historic Key`  
                                from `Purchase Order Transaction Fact` POTF left join `Supplier Part Dimension` SPD  on (POTF.`Supplier Part Key`=SPD.`Supplier Part Key`) where `Purchase Order Key`=?';

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(
                        array($this->id)
                    );
                    while ($row = $stmt->fetch()) {

                        $sql = "UPDATE `Purchase Order Transaction Fact` SET 
                            `Purchase Order Transaction State`=? ,
                            `Purchase Order Submitted Units`=NULL ,`Purchase Order Submitted Unit Cost`=NULL,`Purchase Order Submitted Units Per SKO`=NULL,`Purchase Order Submitted SKOs Per Carton`=NULL,`Purchase Order Submitted Unit Extra Cost Percentage`=NULL,
                                              `Purchase Order Net Amount`=?,`Purchase Order Extra Cost Amount`=? ,`Supplier Part Historic Key`=?
                            
                            WHERE `Purchase Order Transaction Fact Key`=?";


                        $this->db->prepare($sql)->execute(
                            array(

                                $value,
                                round($row['Supplier Part Unit Cost'] * $row['Purchase Order Ordering Units'], 2),
                                round($row['Supplier Part Unit Extra Cost'] * $row['Purchase Order Ordering Units'], 2),
                                $row['Supplier Part Historic Key'],

                                $row['Purchase Order Transaction Fact Key']
                            )
                        );


                    }

                    $this->update_purchase_order_items_state();


                    break;


                case 'Submitted':


                    $this->fast_update(
                        array(
                            'Purchase Order Submitted Date' => $date,
                            'Purchase Order Send Date'      => '',
                            'Purchase Order State'          => $value,
                        )
                    );


                    $parent = get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);


                    if ($parent->get($parent->table_name.' Average Production Days') != '' and is_numeric($parent->get($parent->table_name.' Average Production Days'))) {
                        $this->fast_update(
                            array(
                                'Purchase Order Estimated Production Date' => gmdate("Y-m-d H:i:s", strtotime('now +'.$parent->get($parent->table_name.' Average Production Days').' days +0:00')),
                            )
                        );
                    }


                    if ($parent->get($parent->table_name.' Average Delivery Days') != '' and is_numeric($parent->get($parent->table_name.' Average Delivery Days'))) {
                        $this->fast_update(
                            array(

                                'Purchase Order Estimated Receiving Date' => gmdate("Y-m-d H:i:s", strtotime('now +'.$parent->get($parent->table_name.' Average Delivery Days').' days +0:00')),
                            )
                        );
                    }

                    $this->update_purchase_order_date();


                    $operations = array(
                        'cancel_operations',
                        'undo_submit_operations',
                        'received_operations'
                    );

                    if ($old_value != 'Submitted') {
                        if ($this->get('State Index') <= 30) {
                            $history_abstract = _('Purchase order submitted');

                            if ($this->data['Purchase Order Parent'] == 'Agent') {
                                $this->create_agent_supplier_purchase_orders();
                            }


                        } else {
                            $history_abstract = _('Purchase order set back as submitted');

                        }


                        $sql = sprintf('select `Purchase Order Transaction Fact Key` ,`Supplier Part Key`,`Purchase Order Ordering Units` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d  ', $this->id);

                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {


                                $supplier_part = get_object('Supplier_Part', $row['Supplier Part Key']);

                                if ($supplier_part->get('Supplier Part Unit Extra Cost Percentage') == '') {
                                    $extra_cost_percentage = 0;
                                } else {
                                    $extra_cost_percentage = floatval($supplier_part->get('Supplier Part Unit Extra Cost Fraction'));

                                }

                                //print $supplier_part->get('Supplier Part Unit Extra Cost Percentage');
                                //print_r($supplier_part->data);


                                $sql = sprintf(
                                    'update `Purchase Order Transaction Fact` set `Purchase Order Submitted Units`=%f ,`Purchase Order Submitted Unit Cost`=%f,`Purchase Order Submitted Units Per SKO`=%d,`Purchase Order Submitted SKOs Per Carton`=%d ,`Purchase Order Submitted Unit Extra Cost Percentage`=%f ,`Supplier Part Historic Key`=%d where `Purchase Order Transaction Fact Key`=%d   ',

                                    $row['Purchase Order Ordering Units'], $supplier_part->get('Supplier Part Unit Cost'), $supplier_part->part->get('Part Units Per Package'), $supplier_part->get('Supplier Part Packages Per Carton'), $extra_cost_percentage,
                                    $supplier_part->get('Supplier Part Historic Key'), $row['Purchase Order Transaction Fact Key']
                                );

                                // print "$sql\n";
                                $this->db->exec($sql);
                            }
                        }


                        $history_data = array(
                            'History Abstract' => $history_abstract,
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );
                        $this->add_subject_history(
                            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                        );

                    }

                    $sql = sprintf(
                        'UPDATE `Purchase Order Transaction Fact` SET `Purchase Order Transaction State`=%s WHERE `Purchase Order Key`=%d ', prepare_mysql($value), $this->id
                    );
                    $this->db->exec($sql);
                    $this->update_purchase_order_items_state();

                    break;

                case 'Cancelled':

                    $this->fast_update(
                        array(
                            'Purchase Order Locked'                   => 'Yes',
                            'Purchase Order Cancelled Date'           => $date,
                            'Purchase Order Estimated Receiving Date' => '',
                            'Purchase Order State'                    => 'Cancelled',
                        )
                    );
                    $this->update_purchase_order_date();

                    $history_data = array(
                        'History Abstract' => _('Purchase order cancelled'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );
                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );

                    $sql  = "UPDATE `Purchase Order Transaction Fact` SET `Purchase Order Submitted Cancelled Units`=ifnull(`Purchase Order Submitted Units`,0)-ifnull(`Supplier Delivery Units`,0)  WHERE `Purchase Order Key`=? ";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(
                        array(
                            $this->id
                        )
                    );
                    $this->update_purchase_order_items_state();
                    break;


                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                case 'Placed':
                case 'Costing':
                case 'InvoiceChecked':

                    $this->fast_update(
                        array(
                            'Purchase Order Locked' => 'Yes'
                        )
                    );


                    foreach ($metadata as $key => $_value) {
                        $this->update_field(
                            $key, $_value, 'no_history'
                        );
                    }

                    $this->update_purchase_order_items_state();

                    if ($this->get('State Index') >= 80) {

                        $sql =
                            "select max(`Supplier Delivery Received Date`) as delivery_date ,  count(*) as num from `Supplier Delivery Dimension` where `Supplier Delivery Received Date`!='' and `Supplier Delivery Purchase Order Key`=? and `Supplier Delivery State` in ('Received','Checked','Placed','Costing','InvoiceChecked') ";

                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(
                            array(
                                $this->id
                            )
                        );
                        if ($row = $stmt->fetch()) {
                            if ($row['num'] > 0) {
                                $this->fast_update(
                                    array(
                                        'Purchase Order Received Date' => $row['delivery_date'],
                                    )
                                );
                            }

                        }

                    }

                    if ($this->get('State Index') >= 90) {

                        $sql =
                            "select max(`Supplier Delivery Checked Date`) as checked_date ,  count(*) as num from `Supplier Delivery Dimension` where `Supplier Delivery Checked Date`!='' and `Supplier Delivery Purchase Order Key`=? and `Supplier Delivery State` in ('Checked','Placed','Costing','InvoiceChecked') ";

                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(
                            array(
                                $this->id
                            )
                        );
                        if ($row = $stmt->fetch()) {
                            if ($row['num'] > 0) {
                                $this->fast_update(
                                    array(
                                        'Purchase Order Checked Date' => $row['checked_date'],
                                    )
                                );
                            }

                        }

                    }

                    $this->update_purchase_order_date();

                    break;


                default:
                    exit('unknown state:'.$value);
                    break;
            }


            $sql = sprintf(
                'UPDATE `Purchase Order Transaction Fact` SET `Purchase Order Last Updated Date`=%s WHERE `Purchase Order Key`=%d ', prepare_mysql($date), $this->id
            );
            $this->db->exec($sql);


            $this->update_parts_next_delivery();

            /**
             * @var $parent \Supplier|\Agent
             */
            $parent = get_object($this->data['Purchase Order Parent'], $this->data['Purchase Order Parent Key']);


            $parent->update_purchase_orders();


        }

        $this->update_metadata = array(
            'class_html'                => array(
                'Purchase_Order_State'                => $this->get('State'),
                'Purchase_Order_Submitted_Date'       => '&nbsp;'.$this->get('Submitted Date'),
                'Purchase_Order_Submitted_Agent_Date' => '&nbsp;'.$this->get('Submitted Agent Date'),
                'Purchase_Order_Received_Date'        => '&nbsp;'.$this->get('Received Date'),
                'Purchase_Order_Send_Date'            => '&nbsp;'.$this->get('Send Date'),

            ),
            'operations'                => $operations,
            'state_index'               => $this->get('State Index'),
            'pending_items_in_delivery' => $this->get('Purchase Order Ordered Number Items') - $this->get('Purchase Order Number Supplier Delivery Items')
        );


        switch ($this->get('Purchase Order State')) {
            case 'InProcess':
                $this->update_metadata['hide'] = array('pdf_purchase_order_container');
                break;
            case 'Submitted':
                $this->update_metadata['show'] = array('pdf_purchase_order_container');
                break;
        }


    }

    function update_purchase_order_date() {

        $state_index = $this->get('State Index');

        if ($state_index < 0) {
            $date = ($this->data['Purchase Order Cancelled Date'] == '' ? '' : gmdate('Y-m-d', strtotime($this->data['Purchase Order Cancelled Date'].' +0:00')));
            $type = 'Cancelled';
        } elseif ($state_index <= 10) {
            $date = ($this->data['Purchase Order Creation Date'] == '' ? '' : gmdate('Y-m-d', strtotime($this->data['Purchase Order Creation Date'].' +0:00')));
            $type = 'Created';
        } elseif ($state_index <= 70) {

            if ($this->data['Purchase Order Estimated Receiving Date'] == '') {
                $date = ($this->data['Purchase Order Submitted Date'] == '' ? '' : gmdate('Y-m-d', strtotime($this->data['Purchase Order Submitted Date'].' +0:00')));
                $type = 'Submitted';
            } else {
                $date = gmdate('Y-m-d', strtotime($this->data['Purchase Order Estimated Receiving Date'].' +0:00'));
                $type = 'ETA';
            }


        } else {
            $date = ($this->data['Purchase Order Received Date'] == '' ? '' : gmdate('Y-m-d', strtotime($this->data['Purchase Order Received Date'].' +0:00')));
            $type = 'Received';


        }

        $this->fast_update(
            array(
                'Purchase Order Date'      => $date,
                'Purchase Order Date Type' => $type,
            )
        );

    }

    function update_purchase_order_items_state() {

        $sql  = "select `Purchase Order Transaction Fact Key` from `Purchase Order Transaction Fact`  where `Purchase Order Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($this->id)
        );
        while ($row = $stmt->fetch()) {
            $this->update_purchase_order_item_state($row['Purchase Order Transaction Fact Key']);
        }

        $this->update_totals();


    }

    function create_agent_supplier_purchase_orders() {


        include_once 'class.Agent_Supplier_Purchase_Order.php';

        $sql = sprintf('select S.`Supplier Key`,S.`Supplier Code` from `Purchase Order Transaction Fact` POTF left join `Supplier Dimension` S on (S.`Supplier Key`=POTF.`Supplier Key`) where `Purchase Order Key`=%d group by `Supplier Key`', $this->id);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $order_data = array(
                    'Agent Supplier Purchase Order Supplier Key'       => $row['Supplier Key'],
                    'Agent Supplier Purchase Order Public ID'          => $this->data['Purchase Order Public ID'].'.'.$row['Supplier Code'],
                    'Agent Supplier Purchase Order Purchase Order Key' => $this->id,
                    'Agent Supplier Purchase Order Currency Code'      => $this->data['Purchase Order Currency Code'],
                    'editor'                                           => $this->editor
                );


                $agent_supplier_purchase_order = new AgentSupplierPurchaseOrder('new', $order_data);


                if ($agent_supplier_purchase_order->error) {
                    $this->error = true;
                    $this->msg   = $agent_supplier_purchase_order->msg;

                }


            }
        }


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Purchase Order Public ID':
                $label = _('public Id');
                break;
            case 'Purchase Order Incoterm':
                $label = _('Incoterm');
                break;
            case 'Purchase Order Port of Export':
                $label = _('port of export');
                break;
            case 'Purchase Order Port of Import':
                $label = _('port of import');
                break;
            case 'Purchase Order Estimated Receiving Date':
                $label = _('estimated receiving date');
                break;
            case 'Purchase Order Agreed Receiving Date':
                $label = _('agreed receiving date');
                break;
            case 'Purchase Order Account Number':
                $label = _('Account number');
                break;
            case 'Purchase Order Warehouse Address':
                $label = _('Delivery address');
                break;
            case 'Purchase Order Terms and Conditions':
                $label = _('terms and conditions');
                break;
            case 'Purchase Order Estimated Production Date':
                $label = _('estimated production date');
                break;
            case 'payment terms':
                $label = _('payment terms');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


}



